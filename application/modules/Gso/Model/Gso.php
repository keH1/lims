<?php

/**
 * Модель для работы с ГСО
 */
class Gso extends Model
{
    private string $location = '/gso/list/';

    private array $selectInList = [
        'aggregate_state' => [1, 0],
        'specification' => [0, 0],
        'unit_of_concentration' => [1, 0],
        'gso_full_name' => [0, 1],
        'gso_purpose' => [1, 0],
        'unit_of_quantity' => [1, 0],
        'gso_manufacturer' => [1, 0],
        'gso_receive' => [0, 0],
    ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;
        $filtersForGetList['order'] = "date_receive DESC";

        $result['recordsTotal'] = count($this->getFromSQL('getList', $filtersForGetList));
        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...

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
        $namesTable = [
            'gso_manufacturer' => 'gso_manufacturer',
            'gso' => 'gso',
            'gso_specification' => 'gso_specification',
            'gso_receive' => 'gso_receive',
            'gso_receive_specification' => 'gso_receive_specification',
            'library_reactive' => 'library_reactive'
        ];
        $libraryReactive = [
            'reactive' => 1,
            'gso' => 2,
            'standart_titr' => 3,
            'reactive_lab' => 4,
        ];
        if ($type == null) {
            $name = array_key_first($data);
            if (!isset($namesTable[$name])) {
                return 0;
            }
            $dataAdd = $data[$name];
        }

        if ($type == 'gsoAndSpecification') {
            $dataFirstAdd['library_reactive']['id_library_reactive_table_name'] = $libraryReactive['gso'];
            $idFirstAdd = $this->addToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $data['gso'] ['id_library_reactive'] = $idFirstAdd;
            $dataSecondAdd['gso'] = $data['gso'];

            $idSecondAdd = $this->addToSQL($dataSecondAdd);
            if (!$idSecondAdd) {
                return 0;
            }
            $data['gso_specification']['id_gso'] = $idSecondAdd;
            $dataThirdAdd['gso_specification'] = $data['gso_specification'];
            return $this->addToSQL($dataThirdAdd);
        }

        if ($type == 'gsoReceive') {
            $idLibraryReactive = $this->getByID('idLibraryReactive', $data['gso_receive']['id_gso']);
            $data['gso_receive']['id_library_reactive'] = $idLibraryReactive;
            $dataFirstAdd['gso_receive'] = $data['gso_receive'];
            $idFirstAdd = $this->addToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $data['gso_receive_specification']['id_gso_receive'] = $idFirstAdd;
            $dataSecondAdd['gso_receive_specification'] = $data['gso_receive_specification'];
            return $this->addToSQL($dataSecondAdd);
        }

        return $this->insertToSQL($dataAdd, $name, $_SESSION['SESS_AUTH']['USER_ID']);
    }

    private function getFromSQL(string $typeName, array $filters = null): array
    {
        if ($typeName == 'getList') {
            $request = "
            SELECT CONCAT('ГСО-', gso.number,IFNULL
                   (CONCAT('-',gso_receive.number),''))    AS number
                 , gso.id
                 , gso.name
                 , gso.doc
                 , aggregate_state.name            AS aggregate_name
                 , CONCAT(gso_specification.name, ' ',
                          gso_specification.approximate_concentration, ' ',
                          spec.name)               AS name_specification_full
                 , gso_purpose.name                AS gso_purpose
                 , CONCAT('№ ', gso_receive.doc_receive_name, ' от ',
                          DATE_FORMAT(gso_receive.doc_receive_date,
                                      '%d.%m.%Y')) AS doc_receive_full
                 , DATE_FORMAT(gso_receive.date_receive,
                               '%d.%m.%Y')         AS date_receive_dateformat
                 , gso_receive.number_batch
                 , CONCAT(gso_receive.quantity, ' ',
                          unit_of_quantity.name)   AS quantity_full
                 , gso_receive_specification.specification
                 , CONCAT(gso_receive_specification.concentration, ' ', rec_spec.name,
                          ' ± ', gso_receive_specification.inaccuracy,
                          ' %')                    AS concentration_full
                 , gso_receive.certificate
                 , gso_receive.passport
                 , gso_manufacturer.name           AS manufacturer_name
                 , DATE_FORMAT(gso_receive.date_production,
                               '%d.%m.%Y')         AS date_production_dateformat
                 , CONCAT(gso_receive.storage_life_in_year, ' год(а), до ',
                          DATE_FORMAT(DATE_ADD(gso_receive.date_production, INTERVAL
                                               gso_receive.storage_life_in_year * 360 DAY),
                                      '%d.%m.%Y')) AS storage_full
                 , CONCAT(IFNULL(b_user.last_name, '-'), ' ',
                          IFNULL(b_user.name, '')) AS global_assigned_name
                 , IF(DATE_ADD(gso_receive.date_production, INTERVAL
                               gso_receive.storage_life_in_year * 360 DAY) < CURDATE(), 1,
                      0)                           AS is_expired
            FROM gso
                     LEFT JOIN gso_specification ON gso_specification.id_gso = gso.id
                     LEFT JOIN aggregate_state
                               ON gso.id_aggregate_state = aggregate_state.id
                     LEFT JOIN gso_purpose ON gso.id_gso_purpose = gso_purpose.id
                     LEFT JOIN gso_receive ON gso_receive.id_gso = gso.id
                     LEFT JOIN unit_of_quantity
                               ON gso.id_unit_of_quantity = unit_of_quantity.id
                     LEFT JOIN gso_receive_specification
                               ON gso_receive_specification.id_gso_receive = gso_receive.id
                     LEFT JOIN gso_manufacturer
                               ON gso_receive.id_gso_manufacturer = gso_manufacturer.id
                     LEFT JOIN (SELECT * FROM unit_of_concentration) spec
                               ON gso_specification.id_unit_of_concentration = spec.id
                     LEFT JOIN (SELECT * FROM unit_of_concentration) rec_spec
                               ON gso_receive_specification.id_unit_of_concentration =
                                  rec_spec.id
                     LEFT JOIN b_user ON gso_receive.global_assigned = b_user.id
            
                HAVING id {$filters['idWhichFilter']}             
                     AND                   {$filters['having']}
                    ORDER BY date_receive IS NULL DESC, {$filters['order']}
                    {$filters['limit']} 
        ";
        } elseif ($typeName == 'data_for_update') {
            $request = "
                SELECT *                   
                FROM $filters[0]   
                WHERE id = $filters[1]                
                             ";
        } elseif ($typeName == 'gso_for_update') {
            $request = "
                SELECT *                   
                FROM gso 
                WHERE id = {$filters['id']}                 
                             ";
        } elseif ($typeName == 'specification_for_update') {
            $request = "
            SELECT approximate_concentration
                 , id_unit_of_concentration
                 , CONCAT(GOST, ' ', SPECIFICATION) AS name
                 , gso_specification.id
            FROM gso_specification
                     LEFT JOIN ba_gost ON ba_gost.SPECIFICATION = gso_specification.name
            WHERE id_gso = {$filters['id']}
            GROUP BY gso_specification.id                   
                             ";
        } elseif ($typeName == 'gso_receive_for_update') {
            $request = "
                SELECT *                   
                FROM gso_receive
                WHERE id = {$filters['id']}                 
                             ";
        } elseif ($typeName == 'receive_specification_for_update') {
            $request = "
            SELECT * FROM gso_receive_specification
            WHERE id_gso_receive = {$filters['id']}                 
                             ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'gso_full_name') {
                    $request = "
                SELECT gso.id
                     , unit_of_quantity.name              AS unit
                     , CONCAT('ГСО-',gso.number,'-', gso.doc, ' ', gso.name, ' ',
                              gso_specification.approximate_concentration, ' ',
                              unit_of_concentration.name) AS name
                     , MAX(gso_receive.number)            AS number_receive
                     , gso.number
                FROM gso
                         JOIN gso_specification ON gso_specification.id_gso = gso.id
                         JOIN unit_of_concentration
                              ON gso_specification.id_unit_of_concentration =
                                 unit_of_concentration.id
                         JOIN unit_of_quantity ON gso.id_unit_of_quantity = unit_of_quantity.id
                         LEFT JOIN gso_receive ON gso.id = gso_receive.id_gso
                GROUP BY gso.id            
             ";
                } elseif ($typeName == 'specification') {
                    $request = "
                    SELECT CONCAT(gost, ' ', specification) AS name
                        FROM ba_gost
                        ORDER BY specification ASC
            ";
                } elseif ($typeName == 'gso_receive') {
                    $request = "
                    SELECT gso.id
                         , IF(gso_receive.id IS NOT NULL,
                              CONCAT('ГСО-',gso.number, '-', gso_receive.number, ' ', gso.name,' ',
                                     gso.doc, ' Дата пост: ', gso_receive.date_receive,
                                     ' № партии: ', gso_receive.number_batch),
                              CONCAT('ГСО-',gso.number, ' ', gso.name,
                                     gso.doc, ' Реактив не проведен')
                        )                 AS name
                         , gso_receive.id AS id_receive
                    FROM gso
                             LEFT JOIN gso_receive ON gso.id = gso_receive.id_gso                                
                             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);
    }

    public
    function checkData(array $data, $type = null): bool
    {
        /* if (count($data) > 1 && $type = null) {
             echo('Массив должен быть из одного элемента или введите тип проверки');
             exit;
         }*/
        if ($type == null) {
            $type = array_key_first($data);
            $data = $data[$type];
        }
        if ($type == 'gso_manufacturer') {
            $modelReactiveName = $this->getFromSQL('whereManufacturerName', $data['name']);
            return count($modelReactiveName) != 0;
        }
        if ($type == 'reactiveNumber') {
            $numberReactive = $this->getFromSQL('whereReactiveNumber', $data['number']);
            return count($numberReactive) != 0;
        }

        if ($type == 'reactivePure') {
            $numberReactive = $this->getFromSQL('inReactivePure', $data['id_reactive_model'], $data['id_pure']);
            return count($numberReactive) != 0;
        }
        return true;
    }

    public
    function getByID(string $name, $ID): string
    {
        if ($name == 'idLibraryReactive') {
            $requestFromSQL = $this->DB->Query(
                " SELECT id_library_reactive 
                        FROM gso 
                        WHERE id = $ID
             "
            );
        }

        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }

        return array_values($response[0])[0];
    }

    public function getUpdateData(string $type, array $filters): array
    {

        if ($type == "gso") {

            $dataForUpdate["gso"] = $this->getFromSQL("gso_for_update", $filters)[0];
            $dataForUpdate["gso_specification"] = $this->getFromSQL("specification_for_update", $filters)[0];

        } else if ($type == "receive") {
            $dataForUpdate["receive"] = $this->getFromSQL("gso_receive_for_update", $filters)[0];
            $dataForUpdate["receive_specification"] = $this->getFromSQL("receive_specification_for_update", $filters)[0];
        } else throw new InvalidArgumentException("Неизвестный аргумент $type в функции getUpdateData");

        return $dataForUpdate;
    }

    public function newUpdateSQL(array $newRecord, string $typeName = null): int
    {
        $nameTable = array_key_first($newRecord);
        $filter = [$nameTable, $newRecord[$nameTable]['id']];
        $oldRecord[$nameTable] = $this->getFromSQL('data_for_update', $filter)[0];

        $idFirstAdd = $this->historyToSQL($oldRecord);
        if (!$idFirstAdd) {
            return 0;
        }

        return $this->newUpdateToSQL($newRecord);
    }
}

