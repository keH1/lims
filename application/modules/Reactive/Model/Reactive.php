<?php

/**
 * Модель для работы с рецептами расторов
 * Class Reactive
 */
class Reactive extends Model
{
    private string $location = '/reactive/list/';
    /*  key=>[$i,$k] $i = 1 то выполняется запрос в getFromSQL  SELECT * FROM  $key
                              0, то запрос пишется в getFromSQL
                         $k = 0, то select передается как есть, если
                         $k = 1, то добавляется select $keyPlusAll = [['id'=-1,'name'='Все']] иначе
                         введите свой массив для добавления к $keyPlusAll в формате смотри на выше
        */
    private array $selectInList = [
        'aggregate_full' => [0, 0],
        'pure' => [0, 0],
        'reactive_type' => [0, 0],
        'reactive' => [0, 1],
        'reactive_receive' => [0, 0],
    ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;
        $filtersForGetList['order'] = "date_receive DESC";

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            } else {
                $order['dir'] = 'DESC';
            }

            $filtersForGetList['order'] = "{$filter['order']['by']} {$order['dir']}";

        }

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

    public function addToSQL(array $data, string $typeName = null): int
    {
        $libraryReactive = [
            'reactive' => 1,
            'gso' => 2,
            'standart_titr' => 3,
            'reactive_lab' => 4,
        ];
        if ($typeName == null) {
            $dataAdd = $data;
        } elseif ($typeName == 'addReactiveModelNoUnitMeasurement') {
            $dataAdd['reactive_model'] = $data['reactive_model'];
            $dataAdd['reactive_model']['id_unit_of_quantity'] = $data['reactive_model']['id_aggregate_state'];
            return $this->insertToSQL($dataAdd);
        } elseif ($typeName == 'addReactive') {
            $dataFirstAdd['library_reactive']['id_library_reactive_table_name'] = $libraryReactive['reactive'];
            $idFirstAdd = $this->insertToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $dataSecondAdd['reactive'] = $data['reactive'];
            $dataSecondAdd['reactive'] ['id_library_reactive'] = $idFirstAdd;
            return $this->insertToSQL($dataSecondAdd);
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }
        return $this->insertToSQL($dataAdd);
    }

    private function getFromSQL(string $typeName, array $filters = null): array
    {
        if ($typeName == 'getList') {

            $request = "
                SELECT CONCAT(reactive.number,IFNULL(CONCAT('-',reactive_receive.number),'' )) AS number, 
                        reactive_model.name,                       
                        CONCAT( aggregate_state.name,' (',unit_of_quantity.name,')  ') as aggregate_name,
                        reactive_pure.short_name,
                        reactive.doc_name,
                        CONCAT ('№ ', reactive_receive.doc_receive_name,' от ',DATE_FORMAT(reactive_receive.doc_receive_date,'%d.%m.%Y' )) as doc_receive_full_name,
                        DATE_FORMAT(reactive_receive.date_receive,'%d.%m.%Y') as date_receive_dateformat,
                        reactive_receive.number_batch,
                        CONCAT( reactive_receive.quantity,' ',unit_of_quantity.name) as full_quantity,
                        DATE_FORMAT(reactive_receive.date_production,'%d.%m.%Y') as date_production_dateformat,
                        DATE_FORMAT(reactive_receive.date_expired,'%d.%m.%Y') as date_expired_dateformat,
                        CONCAT (b_user.LAST_NAME) as global_assigned_name,
                        reactive.id,
                        reactive_model.is_precursor,
                        IF(reactive_receive.date_expired<CURDATE(),1,0) AS is_expired
            FROM reactive_model
            LEFT JOIN reactive ON reactive.id_reactive_model = reactive_model.id
            LEFT JOIN reactive_pure ON reactive.id_pure = reactive_pure.id           
            LEFT JOIN reactive_receive ON reactive_receive.id_reactive = reactive.id
            LEFT JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity= unit_of_quantity.id
            LEFT JOIN aggregate_state ON reactive_model.id_aggregate_state = aggregate_state.id
            LEFT JOIN b_user ON  reactive_receive.global_assigned =b_user.ID
            HAVING id {$filters['idWhichFilter']}             
                 AND                   {$filters['having']}
                ORDER BY {$filters['order']}
                {$filters['limit']}    
        ";

            // AND  (date_receive >= {$filters['dateStart']} AND date_receive <= {$filters['dateEnd']} OR date_receive IS NULL)
        } elseif ($typeName == 'data_for_update') {
            $request = "
                SELECT *                   
                FROM $filters[0]   
                WHERE id = $filters[1]                  
                             ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'aggregate_full') {
                    $request = "
                SELECT aggregate_state.id,
                        CONCAT (aggregate_state.name,' - ',unit_of_quantity.name) AS name
                        FROM aggregate_state 
                        JOIN unit_of_quantity ON unit_of_quantity.id=aggregate_state.id             
             ";
                } elseif ($typeName == 'reactive_type') {
                    $request = "
                        SELECT reactive_model.id, unit_of_quantity.name AS unit,
                    CONCAT( reactive_model.name,'- ',aggregate_state.name,' (',unit_of_quantity.name,')  ') AS name
                    FROM reactive_model 
                    JOIN aggregate_state ON reactive_model.id_aggregate_state  = aggregate_state .id 
                    JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity = unit_of_quantity.id           
             ";
                } elseif ($typeName == 'reactive') {
                    $request = "
                    SELECT reactive.id,
                           unit_of_quantity.name                                          AS unit,
                           CONCAT(reactive.number, '-', reactive_model.name,
                                  '(', reactive_pure.short_name, ') ', reactive.doc_name) AS name,
                           reactive_model.id                                              AS id_model,
                            MAX(reactive_receive.number)                                  AS number_receive,
                            reactive.number,
                            reactive.id_library_reactive
                    FROM reactive_model
                             JOIN aggregate_state ON reactive_model.id_aggregate_state = aggregate_state.id
                             JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity = unit_of_quantity.id
                             JOIN reactive ON reactive.id_reactive_model = reactive_model.id
                             JOIN reactive_pure ON reactive_pure.id = reactive.id_pure
                             LEFT JOIN reactive_receive ON reactive.id = reactive_receive.id_reactive
                    GROUP BY reactive.id                 
             ";
                } elseif ($typeName == 'pure') {
                    $request = "
                    SELECT reactive_pure.id,
                           CONCAT(reactive_pure.name,' (',reactive_pure.short_name,')') AS name
                        FROM reactive_pure      
             ";
                } elseif ($typeName == 'unit_of_quantity') {
                    $request = "
                SELECT reactive_model.id, unit_of_quantity.name AS unit,
            CONCAT( reactive_model.name,'- ',aggregate_state.name,' (',unit_of_quantity.name,')  ') AS name
            FROM reactive_model 
            JOIN aggregate_state ON reactive_model.id_aggregate_state  = aggregate_state .id 
            JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity = unit_of_quantity.id           
             ";
                } elseif ($typeName == 'reactive_receive') {
                    $request = "
                   SELECT reactive.id,
                       IF(reactive_receive.id IS NOT NULL,
                          CONCAT(reactive.number, '-', reactive_receive.number, ' ', reactive_model.name,
                                 '(', reactive_pure.short_name, ') Дата пост: ', reactive_receive.date_receive,
                                 ' № партии: ', reactive_receive.number_batch),
                          CONCAT(reactive.number, ' ', reactive_model.name,
                                 '(', reactive_pure.short_name, ') Реактив не проведен')
                           )             AS name,
                       reactive_receive.id AS id_receive 
                FROM reactive
                         LEFT JOIN reactive_receive ON reactive.id = reactive_receive.id_reactive
                         LEFT JOIN reactive_model ON reactive.id_reactive_model = reactive_model.id
                         LEFT JOIN reactive_pure ON reactive_pure.id = reactive.id_pure
                             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);

    }

    public function getUpdateData(array $filter): array
    {
        array_walk($filter, function(&$value) {
            $value = is_numeric($value)
                ? (int)$value
                : $this->DB->ForSql(trim(strip_tags($value)));
        });

        return $this->getFromSQL("data_for_update", $filter);
    }

    public function newUpdateSQL(array $newRecord, string $typeName = null): int
    {
        $nameTable = array_key_first($newRecord);
        $recordId = (int)$newRecord[$nameTable]['id'];

        $historyId = $this->copyRecordToHistory($nameTable, $recordId);

        if (empty($historyId) || !preg_match('/^[a-zA-Z_]+$/', $nameTable)) {
            return 0;
        }

        $newRecord[$nameTable]['global_assigned'] = (int)$_SESSION['SESS_AUTH']['USER_ID'];
        $newRecord[$nameTable]['global_entry_date'] = date('Y-m-d H:i:s');

        $sqlData = $this->prepearTableData($nameTable, $newRecord[$nameTable]);

        return $this->DB->Update($nameTable, $sqlData, "where id = {$recordId}");
    }

    public function copyRecordToHistory(string $tableName, int $recordId): int
    {
        if ($recordId <= 0 || !preg_match('/^[a-zA-Z_]+$/', $tableName)) {
            return 0;
        }

        // Таблицы истории
        $historyTable = $tableName . '_history';

        $globalAssigned = (int)$_SESSION['SESS_AUTH']['USER_ID'];
        $globalEntryDate = date('Y-m-d H:i:s');

        $columnsMetadata = $this->getColumnsMetadata($historyTable);

        $record = $this->DB->Query("SELECT * FROM {$tableName} WHERE id = {$recordId}")->Fetch();

        $historyData = [];
        foreach ($columnsMetadata as $column => $meta) {
            if ($column === 'id_old') {
                $historyData[$column] = $record['id'];
            } elseif ($column === 'global_assigned') { // Текущий пользователь
                $historyData[$column] = $globalAssigned;
            } elseif ($column === 'global_entry_date') { // Текущая дата
                $historyData[$column] = $globalEntryDate;
            } elseif (substr($column, -4) === '_old') { // Старые пользователи и дата
                $baseField = substr($column, 0, -4);
                if (isset($record[$baseField])) {
                    $historyData[$column] = $record[$baseField];
                }
            } else {
                if (isset($record[$column])) {
                    $historyData[$column] = $record[$column]; // Остальные поля
                }
            }
        }

        $sqlData = $this->prepearTableData($historyTable, $historyData);
        $insertId = $this->DB->Insert($historyTable, $sqlData);

        return intval($insertId);
    }
}

