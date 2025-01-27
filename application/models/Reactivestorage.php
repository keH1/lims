<?php

class Reactivestorage extends Model
{
    private string $location = '/reactivestorage/list/';
    private array $selectInList = [
        'reactive' => [0, 1],
        'reactive_receive' => [0, 0],
    ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;

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
        if ($typeName == null) {
            $dataAdd = $data;
        } else if ($typeName == 'reactiveConsume') {
            $extractID = explode("-", $data['reactive_consume']['id_merge']);

            unset($data['reactive_consume']['id_merge']);
            $dataFirstAdd['reactive_consume'] = $data['reactive_consume'];
            $dataFirstAdd['reactive_consume'] ['id_library_reactive'] = $extractID[0];
            $dataFirstAdd['reactive_consume'] ['id_all_receive'] = $extractID[1];

            return $this->insertToSQL($dataFirstAdd);
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }
        return $this->insertToSQL($dataAdd);
    }

    public function getFromSQL(string $typeName, array $filters = null): array
    {
        if ($typeName == 'getList') {
            $request = " 
        SELECT reactive.id_library_reactive                                                         AS id
             , CONCAT(reactive.number, '-', receive.number)                                         AS number
             , reactive.name
             , number_batch
             , DATE_FORMAT(date_expired, '%d.%m.%Y') AS date_expired_dateformat
             , date_expired
             , CONCAT(ROUND(receive.quantity,5), ' ', unit_of_quantity.name)                                 AS receive_quantity
             , CONCAT(ROUND(consume.quantity,5), ' ', unit_of_quantity.name)                                 AS consume_quantity
             , CONCAT(ROUND((receive.quantity - IFNULL(consume.quantity, 0)),5), ' ', unit_of_quantity.name) AS total_quantity
             , IF(date_expired<CURDATE(),1,0) AS is_expired
        FROM (SELECT id_library_reactive
                   , CONCAT(id_library_reactive, '-', id) AS idlr_idrr
                   , number
                   , number_batch
                   , date_expired
                   , quantity
              FROM reactive_receive
              UNION
              SELECT id_library_reactive
                   , CONCAT(id_library_reactive, '-', id)                          AS idlr_idrr
                   , number
                   , number_batch
                   , DATE_ADD(date_production, INTERVAL storage_life_in_year YEAR) AS date_expired
                   , quantity
              FROM gso_receive
              UNION
              SELECT id_library_reactive
                   , CONCAT(id_library_reactive, '-', id)                          AS idlr_idrr
                   , number
                   , number_batch
                   , DATE_ADD(date_production, INTERVAL storage_life_in_year YEAR) AS date_expired
                   , quantity
              FROM standart_titr_receive) AS receive
                 JOIN(SELECT CONCAT(reactive_model.name,
                                    '(', reactive_pure.short_name, ') ', reactive.doc_name) AS name
                           , reactive.id_library_reactive
                           , reactive.number
                           , id_unit_of_quantity
                      FROM reactive_model
                               JOIN reactive ON reactive.id_reactive_model = reactive_model.id
                               JOIN reactive_pure ON reactive_pure.id = reactive.id_pure
                      UNION
                      SELECT CONCAT(name, '(', doc, ')') AS name
                           , id_library_reactive
                           , CONCAT('ГСО-', gso.number
                              ) AS number
                           , id_unit_of_quantity
                      FROM gso
                      UNION
                      SELECT CONCAT('Стандарт-титр ', name) AS name
                           , id_library_reactive
                           , CONCAT('CТ-', standart_titr.number ) AS number
                           , id_unit_of_quantity
                      FROM standart_titr) AS reactive ON reactive.id_library_reactive = receive.id_library_reactive
                 LEFT JOIN (SELECT SUM(quantity) AS quantity
                                 , id_library_reactive
                                 , idlr_idrr
                            FROM (SELECT id_library_reactive
                                       , CONCAT(id_library_reactive, '-', id_all_receive) AS idlr_idrr
                                       , quantity
                                  FROM reactive_consume) AS consume
                            GROUP BY idlr_idrr) AS consume ON consume.idlr_idrr = receive.idlr_idrr
                 LEFT JOIN unit_of_quantity ON reactive.id_unit_of_quantity = unit_of_quantity.id
            
              HAVING id {$filters['idWhichFilter']} AND                
                   {$filters['having']}
            ORDER BY {$filters['order']}
            {$filters['limit']}
            ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($typeName == 'reactive') {
                $request = "
                SELECT id_id                     AS id
                     , CONCAT(number, ' ', reactives.name) AS name
                     , unit_of_quantity.name     AS unit
                FROM (
                    SELECT CONCAT('ГСО-', gso.number,
                                  '-', gso_receive.number)    AS number
                         , is_precursor
                         , CONCAT(gso.name, ' ', gso_receive_specification.concentration, ' ',
                                  unit_of_concentration.name) AS name
                         , id_unit_of_quantity
                         , CONCAT(gso_receive.id_library_reactive, '-',
                                  gso_receive.id)             AS id_id
                    FROM gso
                             INNER JOIN gso_receive ON gso_receive.id_library_reactive =
                                                       gso.id_library_reactive
                             INNER JOIN gso_receive_specification
                                        ON gso_receive_specification.id_gso_receive = gso_receive.id
                             INNER JOIN unit_of_concentration ON unit_of_concentration.id =
                                                                 gso_receive_specification.id_unit_of_concentration
                      UNION
                      SELECT CONCAT('CТ-', standart_titr.number,
                                    '-', standart_titr_receive.number) AS number
                           , is_precursor
                           , CONCAT('Стандарт-титр ', name)            AS name
                           , id_unit_of_quantity
                           , CONCAT(standart_titr_receive.id_library_reactive, '-',
                                    standart_titr_receive.id)          AS id_id
                      FROM standart_titr
                               JOIN standart_titr_receive
                                    ON standart_titr_receive.id_library_reactive =
                                       standart_titr.id_library_reactive
                      UNION
                      SELECT CONCAT(reactive.number,
                                    '-', reactive_receive.number) AS number
                           , is_precursor
                           , CONCAT(reactive_model.name, ' (',
                                    reactive_pure.short_name, ') - ',
                                    reactive.doc_name)            AS name
                           , id_unit_of_quantity
                           , CONCAT(reactive_receive.id_library_reactive, '-',
                                    reactive_receive.id)          AS id_id
                      FROM reactive
                               JOIN reactive_receive ON reactive.id_library_reactive =
                                                        reactive_receive.id_library_reactive
                               JOIN reactive_model
                                    ON reactive.id_reactive_model = reactive_model.id
                               JOIN reactive_pure
                                    ON reactive.id_pure = reactive_pure.id) AS reactives
                         LEFT JOIN unit_of_quantity
                                   ON unit_of_quantity.id = reactives.id_unit_of_quantity                                                       
             ";
            } elseif ($typeName == 'reactive_receive') {
                $request = "
                SELECT id_id AS id
                    , CONCAT(number,' ',name) AS name
                FROM (SELECT CONCAT('ГСО-', gso.number,
                              '-', gso_receive.number) AS number
                     , is_precursor
                     , CONCAT(gso.name, ' ', gso_receive_specification.concentration, ' ',
                                  unit_of_concentration.name) AS name
                     , id_unit_of_quantity
                     , CONCAT(gso_receive.id_library_reactive, '-',
                              gso_receive.id)          AS id_id
                FROM gso
                         INNER JOIN gso_receive ON gso_receive.id_library_reactive =
                                                       gso.id_library_reactive
                        INNER JOIN gso_receive_specification
                                        ON gso_receive_specification.id_gso_receive = gso_receive.id
                        INNER JOIN unit_of_concentration ON unit_of_concentration.id =
                                                            gso_receive_specification.id_unit_of_concentration
                
                UNION
                SELECT CONCAT('CТ-', standart_titr.number,
                              '-', standart_titr_receive.number) AS number
                     , is_precursor
                     , CONCAT('Стандарт-титр ', name)            AS name
                     , id_unit_of_quantity
                     , CONCAT(standart_titr_receive.id_library_reactive, '-',
                              standart_titr_receive.id)          AS id_id
                FROM standart_titr
                         JOIN standart_titr_receive
                              ON standart_titr_receive.id_library_reactive =
                                 standart_titr.id_library_reactive
                UNION
                SELECT CONCAT(reactive.number,
                              '-', reactive_receive.number) AS number
                     , is_precursor
                     , CONCAT(reactive_model.name, ' (',
                              reactive_pure.short_name, ') - ',
                              reactive.doc_name)            AS name
                     , id_unit_of_quantity
                     , CONCAT(reactive_receive.id_library_reactive, '-',
                              reactive_receive.id)          AS id_id
                FROM reactive
                         JOIN reactive_receive ON reactive.id_library_reactive =
                                                  reactive_receive.id_library_reactive
                         JOIN reactive_model
                              ON reactive.id_reactive_model = reactive_model.id
                         JOIN reactive_pure
                              ON reactive.id_pure = reactive_pure.id) AS reactives                                       
             ";
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);
    }


    public function getByID(string $name, $ID): string
    {
        $getFrom = [
            'reactiveName' => 'IDReactiveName'
        ];

        $getName = $this->getFromSQL($getFrom[$name], $ID);
        var_dump($getName);
        return array_values_first($getName[0]);
    }

}

