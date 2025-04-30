<?php


class Precursor extends Model
{
    private string $location = '/precursor/list/';

    private array $selectInList = [
        'precursor' => [0, 1]
    ];

    public function getList(array $filter = []): array
    {

        $filtersForGetList = $this->filtersForGetListDefault;

        $filtersForGetList['order'] = "reactive_remain_full.date ASC";

        $result['recordsTotal'] = count($this->getFromSQL('getList', $filtersForGetList));

        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...

        if (isset($filter['order']['by'])) {
            if ($filter['order']['by'] === 'global_assigned_name_receive') {
                $filter['order']['by'] = "LEFT(TRIM(CONCAT_WS(' ', user_receive.NAME, user_receive.LAST_NAME)), 1)";
            }
            if ($filter['order']['by'] === 'global_assigned_name_remain') {
                $filter['order']['by'] = "LEFT(TRIM(CONCAT_WS(' ', user_remain.NAME, user_remain.LAST_NAME)), 1)";
            }
        }

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'havingDateId'));
        //Дальше допфильтрацию не вставлять


        $result['recordsFiltered'] = count($this->getFromSQL('getList', $filtersForGetList));

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'orderLimit'));


        $resultTemp = $this->getFromSQL('getList', $filtersForGetList);

        return array_merge($result, $resultTemp);

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
        if ($typeName == null) {
            $dataAdd = $data;
        } elseif ($typeName == 'addRemain') {
            $dataFirstAdd['reactive_remain'] = $data['reactive_remain'];
            $dataFirstAdd['reactive_remain']['date'] = $data['reactive_remain']['date'] . '-01';
            /*$lastReactiveRemain = $this->getFromSQL("lastDate", ['idWhichFilter' => $dataFirstAdd['reactive_remain']['id_library_reactive']]);
            $dataFirstAdd['reactive_remain']['id_reactive_remain'] = $lastReactiveRemain[0]['id'];*/

            return $this->insertToSQL($dataFirstAdd);
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }
        return $this->insertToSQL($dataAdd);
    }

    private function getRequest(string $name): string
    {
        $organizationId = App::getOrganizationId();
        $receiveReactiveColumns = ["id_library_reactive", "CONCAT(id_library_reactive,'-',id) AS id_id",
            "DATE_FORMAT(date_receive, '%Y%m') AS y_m",
            "date_receive", "doc_receive_name", "doc_receive_date", "quantity", "global_assigned"];
        $receiveReactiveTables = ["reactive_receive", "standart_titr_receive", "gso_receive"];
        if ($name == "reactive") {
            $request = "SELECT id_library_reactive,
                               CONCAT(number, ' - ', reactive_model.name, ' (', reactive_pure.short_name, ') - ', reactive.doc_name) AS name,
                               id_unit_of_quantity,
                               is_precursor
                        FROM reactive
                                 LEFT JOIN reactive_model ON reactive_model.id = reactive.id_reactive_model
                                 LEFT JOIN reactive_pure ON reactive.id_pure = reactive_pure.id
                        WHERE reactive.organization_id = {$organizationId}
         ";
        } elseif ($name == "standartTitr") {
            $request = "SELECT id_library_reactive,
                               CONCAT('Стандарт-Титр ', standart_titr.name) AS name,
                               id_unit_of_quantity,
                               is_precursor
                        FROM standart_titr
                        WHERE standart_titr.organization_id = {$organizationId}

        ";
        } elseif ($name == "gso") {
            $request = "
                        SELECT id_library_reactive,
                               CONCAT('ГСО - ', name) AS name,
                               id_unit_of_quantity,
                               is_precursor
                        FROM gso
                        WHERE gso.organization_id = $organizationId
        ";

        } elseif ($name == "all_reactives") {
            $request = " {$this->getRequest("reactive")}
                               UNION
                              {$this->getRequest("standartTitr")}
                               UNION
                              {$this->getRequest("gso")}
        ";
            /*$request = "SELECT * FROM (SELECT
                            id_library_reactive,
                            CONCAT(number,' - ', reactive_model.name,' (',reactive_pure.short_name,') - ', reactive.doc_name) as name,
                            id_unit_of_quantity,
                            is_precursor
                    FROM reactive
                    LEFT JOIN reactive_model ON reactive_model.id= reactive.id_reactive_model
                    LEFT JOIN reactive_pure ON reactive.id_pure = reactive_pure.id
                               UNION
                              SELECT
                                id_library_reactive,
                                CONCAT('Стандарт-Титр ', standart_titr.name)  AS name,
                                id_unit_of_quantity,
                                is_precursor
                                FROM standart_titr
                               UNION
                              SELECT
                        id_library_reactive,
                        CONCAT('ГСО - ',name) AS name,
                        id_unit_of_quantity,
                        is_precursor
                FROM gso) AS reactives";*/
        } elseif ($name == "reactive_consume_full") {
            $request = "SELECT id_library_reactive,
                               CONCAT(id_library_reactive, '-', id_all_receive) AS id_id,
                               CONCAT(id_library_reactive, '-', y_m, '-', rnk) AS id_y_m_r,
                               date,
                               quantity,
                               type,
                               global_assigned
                        FROM (SELECT @rank := CASE
                                                  WHEN @partval = id_library_reactive AND @rankval = y_m THEN @rank + 1
                                                  WHEN @partval = id_library_reactive AND (@rankval := y_m) IS NOT NULL THEN 1
                                                  WHEN (@partval := id_library_reactive) IS NOT NULL AND (@rankval := y_m) IS NOT NULL THEN 1
                            END AS rnk,
                                     reactive_consume.*
                              FROM (SELECT DATE_FORMAT(date, '%Y%m') AS y_m, reactive_consume.* FROM reactive_consume ORDER BY id_library_reactive, date)
                                       AS reactive_consume,
                                   (SELECT @rank := NULL, @partval := NULL, @rankval := NULL) AS x
                            WHERE reactive_consume.organization_id = {$organizationId}
                              ORDER BY id_library_reactive, y_m) AS reactive_consume_full
            ";
        } elseif ($name == "all_reactive_receive_full") {
            $selectColumn = implode(',', $receiveReactiveColumns);

            foreach ($receiveReactiveTables as $item) {
                $reactiveReceiveForUnion[] = "SELECT $selectColumn FROM $item WHERE organization_id = {$organizationId}";
            }
            $requestUnion = implode(' UNION ', $reactiveReceiveForUnion);
            $requestOrderBy = "SELECT * FROM ($requestUnion)
                                AS reactive_receive ORDER BY id_library_reactive,date_receive
            ";
            $requestWithRank = "SELECT @rank := CASE
                                WHEN @partval = id_library_reactive AND @rankval = y_m THEN @rank + 1
                                WHEN @partval = id_library_reactive AND (@rankval := y_m) IS NOT NULL THEN 1
                                WHEN (@partval := id_library_reactive) IS NOT NULL AND (@rankval := y_m) IS NOT NULL THEN 1
                                END AS rnk,
                                reactive_receive.* FROM ($requestOrderBy)
                                AS reactive_receive, (SELECT @rank := NULL, @partval := NULL, @rankval := NULL) AS x
            ";
            $request = "SELECT id_library_reactive,
                               id_id,
                               CONCAT(id_library_reactive, '-', y_m, '-', rnk) AS id_y_m_r,
                               date_receive,
                               doc_receive_name,
                               doc_receive_date,
                               quantity,
                               global_assigned
                        FROM ($requestWithRank)AS all_reactive_receive_full
            ";

            /* $request="SELECT id_library_reactive,
                                 id_id,
                                 CONCAT(id_library_reactive, '-', y_m, '-', rnk) AS id_y_m_r,
                                 date_receive,
                                 doc_receive_name,
                                 doc_receive_date,
                                 quantity,
                                 global_assigned
                          FROM (SELECT @rank := CASE
                                  WHEN @partval = id_library_reactive AND @rankval = y_m THEN @rank + 1
                                  WHEN @partval = id_library_reactive AND (@rankval := y_m) IS NOT NULL THEN 1
                                  WHEN (@partval := id_library_reactive) IS NOT NULL AND (@rankval := y_m) IS NOT NULL THEN 1
                                  END AS rnk,
                                  reactive_receive.* FROM (SELECT * FROM (SELECT id_library_reactive,CONCAT(id_library_reactive,'-',id) AS id_id,DATE_FORMAT(date_receive, '%Y%m') AS y_m,date_receive,doc_receive_name,doc_receive_date,quantity,global_assigned
                                              FROM reactive_receive UNION SELECT id_library_reactive,CONCAT(id_library_reactive,'-',id) AS id_id,DATE_FORMAT(date_receive, '%Y%m') AS y_m,date_receive,doc_receive_name,doc_receive_date,quantity,global_assigned
                                              FROM standart_titr_receive UNION SELECT id_library_reactive,CONCAT(id_library_reactive,'-',id) AS id_id,DATE_FORMAT(date_receive, '%Y%m') AS y_m,date_receive,doc_receive_name,doc_receive_date,quantity,global_assigned
                                              FROM gso_receive)
                                  AS reactive_receive ORDER BY id_library_reactive,date_receive
              )
                                  AS reactive_receive, (SELECT @rank := NULL, @partval := NULL, @rankval := NULL) AS x
              )AS all_reactive_receive_full";*/

        } elseif ($name == "reactive_receive_consume_full") {
            $request = " 
                SELECT 
                    reactive_consume_full.id_library_reactive,
                    reactive_consume_full.id_id,
                    reactive_consume_full.id_y_m_r,
                    date_receive,
                    doc_receive_name,
                    doc_receive_date,
                    reactive_consume_full.quantity AS quantity_consume,
                    reactive_consume_full.global_assigned AS assigned_consume,
                    date,
                    all_reactive_receive_full.quantity AS quantity_receive,
                    type,
                    all_reactive_receive_full.global_assigned AS assigned_receive
                    FROM
                ({$this->getRequest('reactive_consume_full')}) AS reactive_consume_full
                 LEFT JOIN 
                ({$this->getRequest('all_reactive_receive_full')}) AS all_reactive_receive_full
                ON reactive_consume_full.id_y_m_r=all_reactive_receive_full.id_y_m_r
                UNION
                 SELECT
                    all_reactive_receive_full.id_library_reactive,
                    all_reactive_receive_full.id_id,
                    all_reactive_receive_full.id_y_m_r,
                    date_receive,
                    doc_receive_name,
                    doc_receive_date,
                    reactive_consume_full.quantity AS quantity_consume,
                    reactive_consume_full.global_assigned AS assigned_consume,
                    date,
                    all_reactive_receive_full.quantity AS quantity_receive,
                    type,
                    all_reactive_receive_full.global_assigned AS assigned_receive
                 FROM
                      ({$this->getRequest('reactive_consume_full')}) AS reactive_consume_full
                 RIGHT JOIN
                          ({$this->getRequest('all_reactive_receive_full')}) AS all_reactive_receive_full
                            ON reactive_consume_full.id_y_m_r=all_reactive_receive_full.id_y_m_r
         ";

        } elseif ($name == "reactive_remain_full") {
            $request = "
            SELECT reactive_remain_begin.quantity                                                             AS quantity_begin,
                   IFNULL(reactive_remain_end.date, reactive_remain_begin.date)                               AS date,
                   reactive_remain_end.quantity                                                               AS quantity_end,
                   IFNULL(reactive_remain_end.id_library_reactive,
                          reactive_remain_begin.id_library_reactive)                                          AS id_library_reactive,
                   IFNULL(reactive_remain_end.id_y_m_r, reactive_remain_begin.id_y_m_r)                       AS id_y_m_r,
                   reactive_remain_end.global_assigned
            FROM (SELECT CONCAT(id_library_reactive, '-', DATE_FORMAT(reactive_remain.date, '%Y%m'), '-1') AS id_y_m_r,
                         reactive_remain.*
                  FROM reactive_remain WHERE reactive_remain.organization_id = {$organizationId} ) AS reactive_remain_end
                     LEFT JOIN(SELECT CONCAT(id_library_reactive, '-',
                                             DATE_FORMAT(DATE_ADD(reactive_remain.date, INTERVAL 1 MONTH), '%Y%m'),
                                             '-1') AS id_y_m_r,
                                      reactive_remain.*
                               FROM reactive_remain  WHERE reactive_remain.organization_id = {$organizationId}) AS reactive_remain_begin
                              ON reactive_remain_end.id_y_m_r = reactive_remain_begin.id_y_m_r
            UNION
            SELECT reactive_remain_begin.quantity                                                             AS quantity_begin,
                   IFNULL(reactive_remain_end.date, reactive_remain_begin.date)                               AS date,
                   reactive_remain_end.quantity                                                               AS quantity_end,
                   IFNULL(reactive_remain_end.id_library_reactive,
                          reactive_remain_begin.id_library_reactive)                                          AS id_library_reactive,
                   IFNULL(reactive_remain_end.id_y_m_r, reactive_remain_begin.id_y_m_r)                       AS id_y_m_r,
                   reactive_remain_end.global_assigned
            FROM (SELECT CONCAT(id_library_reactive, '-', DATE_FORMAT(reactive_remain.date, '%Y%m'), '-1') AS id_y_m_r,
                         reactive_remain.*
                  FROM reactive_remain WHERE reactive_remain.organization_id = {$organizationId}) AS reactive_remain_end
                     RIGHT JOIN (SELECT CONCAT(id_library_reactive, '-',
                                               DATE_FORMAT(DATE_ADD(reactive_remain.date, INTERVAL 1 MONTH), '%Y%m'),
                                               '-1') AS id_y_m_r,
                                        reactive_remain.*
                                 FROM reactive_remain WHERE reactive_remain.organization_id = {$organizationId}) AS reactive_remain_begin
                                ON reactive_remain_end.id_y_m_r = reactive_remain_begin.id_y_m_r       
                      
            ";
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $name в функции getRequest");
        }

        return $request;
    }

    private
    function getFromSQL(string $typeName, array $filters = null): array
    {
        $organizationId = App::getOrganizationId();
        if ($typeName == 'getList') {

            $request = "
            SELECT
                all_reactives.id_library_reactive AS id_reactive,
                all_reactives.name AS  reactive_name,
                reactive_remain_full.date AS primary_date,
                DATE_FORMAT(reactive_remain_full.date, '%m.%Y') AS month_year_dateformat,
                reactive_remain_full.date AS month_year,
                CONCAT( IFNULL( reactive_remain_full.quantity_begin,0),' ',unit_of_quantity.name) AS quantity_begin_full,
                DATE_FORMAT(reactive_receive_consume_full.date_receive,'%d.%m.%Y')                 AS date_receive_dateformat,
                reactive_receive_consume_full.date_receive,
                CONCAT ('№ ', doc_receive_name,' от ',DATE_FORMAT(doc_receive_date,'%d.%m.%Y' )) AS doc_name,
                CONCAT(  reactive_receive_consume_full.quantity_receive,' ',unit_of_quantity.name) AS quantity_receive_full,
                CONCAT(IFNULL( reactive_remain_full.quantity_begin,0)+IFNULL(reactive_receive_consume_full.quantity_receive,0)
                    ,' ',unit_of_quantity.name) AS quantity_remain_plus_receive_full,
                reactive_receive_consume_full.type,
                DATE_FORMAT(reactive_receive_consume_full.date,'%d.%m.%Y')                 AS date_consume_dateformat,
                reactive_receive_consume_full.date AS date_consume,
                CONCAT(reactive_receive_consume_full.quantity_consume,' ',unit_of_quantity.name) AS quantity_consume_full,
                TRIM(CONCAT_WS(' ', user_receive.NAME, user_receive.LAST_NAME)) as global_assigned_name_receive,
                
                CONCAT(IFNULL( reactive_remain_full.quantity_begin,0)+IFNULL(reactive_receive_consume_full.quantity_receive,0)
                    - IFNULL( reactive_receive_consume_full.quantity_consume,0),
                    ' ',unit_of_quantity.name) AS quantity_remain_month_full,
                CONCAT( reactive_remain_full.quantity_end,' ',unit_of_quantity.name) AS quantity_actual_remain_end_full,
                TRIM(CONCAT_WS(' ', user_remain.NAME, user_remain.LAST_NAME)) as global_assigned_name_remain,
                
                
            IF(date_receive IS  NOT NULL,MONTH(date_receive),MONTH(reactive_receive_consume_full.date)) AS mm
                FROM ({$this->getRequest('reactive_remain_full')}) AS reactive_remain_full
                LEFT JOIN  ({$this->getRequest('reactive_receive_consume_full')}) AS reactive_receive_consume_full
                            ON reactive_receive_consume_full.id_y_m_r= reactive_remain_full.id_y_m_r
                LEFT JOIN ({$this->getRequest('all_reactives')}) AS all_reactives
                            ON all_reactives.id_library_reactive=reactive_remain_full.id_library_reactive
                LEFT JOIN unit_of_quantity ON all_reactives.id_unit_of_quantity = unit_of_quantity.id
                
                LEFT JOIN b_user AS user_remain ON user_remain.ID=reactive_remain_full.global_assigned
                LEFT JOIN b_user AS user_receive ON user_receive.ID=reactive_receive_consume_full.assigned_receive
            
                WHERE is_precursor =1
            HAVING all_reactives.id_library_reactive {$filters['idWhichFilter']} AND
                   primary_date>= {$filters['dateStart']} AND primary_date <= {$filters['dateEnd']}   AND
                   {$filters['having']}
                ORDER BY {$filters['order']}
                {$filters['limit']}
            
            ";
        } elseif ($typeName == 'lastDate') {
            $request = "SELECT *
                FROM reactive_remain
                WHERE id_library_reactive =  {$filters['idWhichFilter']} AND organization_id = {$organizationId}
                ORDER BY date DESC
                LIMIT 1
            ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName WHERE organization_id = {$organizationId}
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'precursor') {
                    $request = "
                SELECT IF(reactive_remain.date IS NOT NULL, DATE_ADD(MAX(reactive_remain.date),interval 1 month), MIN(date_receive)) AS last_date,
               unit_of_quantity.name AS unit,
               all_reactives.id_library_reactive AS id,
                       all_reactives.*
        FROM ({$this->getRequest('all_reactives')}) AS all_reactives
                 LEFT JOIN reactive_remain ON reactive_remain.id_library_reactive = all_reactives.id_library_reactive
                 JOIN({$this->getRequest('all_reactive_receive_full')}) all_reactive_receive_full ON all_reactives.id_library_reactive = all_reactive_receive_full.id_library_reactive
                LEFT JOIN unit_of_quantity ON unit_of_quantity.id= all_reactives.id_unit_of_quantity
        WHERE is_precursor = 1
        GROUP BY all_reactives.id_library_reactive
                             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);

    }


}

