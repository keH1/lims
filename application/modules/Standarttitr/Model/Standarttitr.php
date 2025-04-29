<?php

/**
 * Модель для работы с рецептами расторов
 * Class Reactive
 */
class Standarttitr extends Model
{
    private string $location = '/standarttitr/list/';

    private array $selectInList = [
        'standart_titr_manufacturer' => [1, 0],
        'standart_titr_full_name' => [0, 1],
        'standart_titr_receive' => [0, 0]
    ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;

        $result['recordsTotal'] = count($this->getFromSQL('getList', $filtersForGetList));
        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...

        if (isset($filter['order']['by']) && $filter['order']['by'] === 'global_assigned_name') {
            $filter['order']['by'] = "LEFT(TRIM(CONCAT_WS(' ', b_user.NAME, b_user.LAST_NAME)), 1)";
        }

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'havingDateId'));
        //Дальше допфильтрацию не вставлять

        $result['recordsFiltered'] = count($this->getFromSQL('getList', $filtersForGetList));

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'orderLimit'));

        return array_merge($result, $this->getFromSQL('getList', $filtersForGetList));
    }

    public function getSelect(): array
    {
        $selects = [];
        $arrayAll = [["id" => "-1", "name" => "Все"]];
        foreach ($this->selectInList as $key => $item) {
            $selects[$key] = $this->getFromSQL($key);
            if ($item[1] == 1) {
                $selects[$key . "PlusAll"] = array_merge($arrayAll, $selects[$key]);
            } elseif ($item[1] != 0) {
                $selects[$key . "PlusAll"] = array_merge($item[1], $selects[$key]);
            }
        }

        return $selects;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function addToSQL(array $data, string $type = null): int
    {
        /*  if (count($data) > 1 && $type = null) {
            echo('Массив должен быть из одного элемента или введите тип добавления в БД');
            exit;
        }*/
        $namesTable = [
            'standart_titr_manufacturer' => 'standart_titr_manufacturer',
            'standart_titr_receive' => 'standart_titr_receive',
            'standart_titr' => 'standart_titr',
            'library_reactive' => 'library_reactive'
        ];
        $libraryReactive = [
            'reactive' => 1,
            'gso' => 2,
            'standart_titr' => 3,
            'reactive_lab' => 4,
        ];
        $unitOfQuantity = [
            'шт' => 6
        ];
        if ($type == null) {
            $name = array_key_first($data);
            if (!isset($namesTable[$name])) {
                return 0;
            }
            $dataAdd = $data[$name];
        }

        if ($type == 'standartTitr') {
            $dataFirstAdd['library_reactive']['id_library_reactive_table_name'] = $libraryReactive['standart_titr'];
            $idFirstAdd = $this->addToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $data['standart_titr'] ['id_library_reactive'] = $idFirstAdd;
            $data['standart_titr']['id_unit_of_quantity'] = $unitOfQuantity['шт'];
            $dataSecondAdd['standart_titr'] = $data['standart_titr'];
            return $this->addToSQL($dataSecondAdd);
        }

        return $this->insertToSQL($dataAdd, $name);
    }

    public function getFromSQL(string $typeName, array $filters = null): array
    {
        $organizationId = App::getOrganizationId();
        if ($typeName == 'getList') {
            $request = "
            SELECT CONCAT('СТ-', standart_titr.number, IFNULL
                (CONCAT('-', standart_titr_receive.number), '')) AS number
                 , standart_titr.id
                 , standart_titr.name
                 , standart_titr_receive.number_batch
                 , standart_titr_receive.volume
                 , standart_titr_receive.coefficient
                 , standart_titr_receive.doc_standart_titr
                 , standart_titr_manufacturer.name     AS manufacturer_name
                 , DATE_FORMAT(standart_titr_receive.doc_receive_date,
                               '%d.%m.%Y')             AS doc_receive_date_dateformat
                 , CONCAT('№ ', standart_titr_receive.doc_receive_name, ' от ',
                          DATE_FORMAT(standart_titr_receive.doc_receive_date,
                                      '%d.%m.%Y'))     AS doc_receive_full
                 , CONCAT(standart_titr_receive.quantity,
                          ' ампул')                    AS quantity_full
                 , DATE_FORMAT(standart_titr_receive.date_production,
                               '%d.%m.%Y')             AS date_production_dateformat
                 , CONCAT(standart_titr_receive.storage_life_in_year, ' год(а), до ',
                          DATE_FORMAT(DATE_ADD(standart_titr_receive.date_production,
                                               INTERVAL
                                                       standart_titr_receive.storage_life_in_year *
                                                       $this->dayInYear DAY),
                                      '%d.%m.%Y'))     AS storage_full
                 , TRIM(CONCAT_WS(' ', b_user.NAME, b_user.LAST_NAME)) AS global_assigned_name 
                 , IF(DATE_ADD(standart_titr_receive.date_production,
                               INTERVAL standart_titr_receive.storage_life_in_year *
                                        $this->dayInYear DAY) < CURDATE(), 1,
                      0)                               AS is_expired
            FROM standart_titr
                     JOIN standart_titr_receive
                          ON standart_titr_receive.id_standart_titr = standart_titr.id
                     left JOIN standart_titr_manufacturer
                          ON standart_titr_receive.id_standart_titr_manufacturer =
                             standart_titr_manufacturer.id
                     LEFT JOIN b_user ON standart_titr_receive.global_assigned = b_user.id
            WHERE standart_titr.organization_id = {$organizationId}
            HAVING  id {$filters['idWhichFilter']}             
                     AND                   {$filters['having']}
                    ORDER BY date_receive IS NULL DESC, {$filters['order']}
                    
                    {$filters['limit']} 
        ";
            $this->pre("
            SELECT CONCAT('СТ-', standart_titr.number, IFNULL
                (CONCAT('-', standart_titr_receive.number), '')) AS number
                 , standart_titr.id
                 , standart_titr.name
                 , standart_titr_receive.number_batch
                 , standart_titr_receive.volume
                 , standart_titr_receive.coefficient
                 , standart_titr_receive.doc_standart_titr
                 , standart_titr_manufacturer.name     AS manufacturer_name
                 , DATE_FORMAT(standart_titr_receive.doc_receive_date,
                               '%d.%m.%Y')             AS doc_receive_date_dateformat
                 , CONCAT('№ ', standart_titr_receive.doc_receive_name, ' от ',
                          DATE_FORMAT(standart_titr_receive.doc_receive_date,
                                      '%d.%m.%Y'))     AS doc_receive_full
                 , CONCAT(standart_titr_receive.quantity,
                          ' ампул')                    AS quantity_full
                 , DATE_FORMAT(standart_titr_receive.date_production,
                               '%d.%m.%Y')             AS date_production_dateformat
                 , CONCAT(standart_titr_receive.storage_life_in_year, ' год(а), до ',
                          DATE_FORMAT(DATE_ADD(standart_titr_receive.date_production,
                                               INTERVAL
                                                       standart_titr_receive.storage_life_in_year *
                                                       $this->dayInYear DAY),
                                      '%d.%m.%Y'))     AS storage_full
                 , TRIM(CONCAT_WS(' ', b_user.NAME, b_user.LAST_NAME)) AS global_assigned_name 
                 , IF(DATE_ADD(standart_titr_receive.date_production,
                               INTERVAL standart_titr_receive.storage_life_in_year *
                                        $this->dayInYear DAY) < CURDATE(), 1,
                      0)                               AS is_expired
            FROM standart_titr
                     JOIN standart_titr_receive
                          ON standart_titr_receive.id_standart_titr = standart_titr.id
                     left JOIN standart_titr_manufacturer
                          ON standart_titr_receive.id_standart_titr_manufacturer =
                             standart_titr_manufacturer.id
                     LEFT JOIN b_user ON standart_titr_receive.global_assigned = b_user.id
            WHERE standart_titr.organization_id = {$organizationId}
            HAVING  id {$filters['idWhichFilter']}             
                     AND                   {$filters['having']}
                    ORDER BY date_receive IS NULL DESC, {$filters['order']}
                    
                    {$filters['limit']} 
        ");
        } elseif ($typeName == 'data_for_update') {
            $request = "
                SELECT *                   
                FROM {$filters['type']}   
                WHERE id = {$filters['id']}";
        } elseif ($typeName == 'standart_titr_receive_for_update') {
            $request = "
            SELECT standart_titr_receive.*
                 , standart_titr.number AS standart_titr_number
                 , standart_titr.id AS  standart_titr_id
            FROM standart_titr_receive
            LEFT JOIN standart_titr ON standart_titr.id_library_reactive =
                                    standart_titr_receive.id_library_reactive  
                WHERE standart_titr_receive.id = {$filters['id']} AND standart_titr_receive.organization_id = {$organizationId}                  
                             ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM {$typeName}
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'standart_titr_full_name') {
                    $request = "
                    SELECT standart_titr.id
                         , CONCAT('СТ-',standart_titr.number, '-', standart_titr.name
                        )                                    AS name
                         , MAX(standart_titr_receive.number) AS number_receive
                         , standart_titr.number
                    FROM standart_titr
                             LEFT JOIN standart_titr_receive
                                       ON standart_titr.id = standart_titr_receive.id_standart_titr
                    where standart_titr.organization_id = {$organizationId}
                    GROUP BY standart_titr.id       
             ";
                } elseif ($typeName == 'standart_titr_receive') {
                    $request = "
                    SELECT standart_titr.id
                         , IF(standart_titr_receive.id IS NOT NULL,
                              CONCAT('СТ-',standart_titr.number, '-', standart_titr_receive.number, ' ',
                                     standart_titr.name,
                                     ' Дата пост: ', standart_titr_receive.date_receive,
                                     ' № партии: ', standart_titr_receive.number_batch),
                              CONCAT('СТ-',standart_titr.number, ' ', standart_titr.name
                                  , ' Реактив не проведен')
                        )                           AS name
                         , standart_titr_receive.id AS id_receive
                    FROM standart_titr
                             LEFT JOIN standart_titr_receive
                                       ON standart_titr.id = standart_titr_receive.id_standart_titr   
                    where standart_titr.organization_id = {$organizationId}
                             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);
    }

    public
    function getByID(string $name, $ID): string
    {
        $getFrom = [
            'reactiveName' => 'IDReactiveName'
        ];

        $getName = $this->getFromSQL($getFrom[$name], $ID);
        var_dump($getName);
        return array_values_first($getName[0]);
    }

    public function getUpdateData(array $filter): array
    {
        if ($filter['type']=='standart_titr_receive'){

            return $this->getFromSQL("standart_titr_receive_for_update", $filter);
        }
        return $this->getFromSQL("data_for_update", $filter);
    }

    public function newUpdateSQL(array $newRecord, string $typeName = null): int
    {
        $nameTable = array_key_first($newRecord);

        $newRecord[$nameTable]['is_precursor'] = isset($newRecord[$nameTable]['is_precursor'])? 1 : 0;

        $sqlData = $this->prepearTableData($nameTable, $newRecord[$nameTable]);

        return $this->DB->Update($nameTable, $sqlData, "where id = {$newRecord[$nameTable]['id']}");
    }
}

