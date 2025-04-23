<?php

class Solution extends Model
{
    private string $location = '/solution/list/';

    private array $libraryReactive
        = [
            'reactive'      => 1,
            'gso'           => 2,
            'standart_titr' => 3,
            'reactive_lab'  => 4,
        ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;

        $result['recordsTotal'] = count($this->getFromSQL('getList',
            $filtersForGetList));
        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...

        $filtersForGetList = array_merge($filtersForGetList,
            $this->transformFilter($filter, 'havingDateId'));
        //Дальше допфильтрацию не вставлять

        $result['recordsFiltered'] = count($this->getFromSQL('getList',
            $filtersForGetList));

        $filtersForGetList = array_merge($filtersForGetList,
            $this->transformFilter($filter, 'orderLimit'));

        return array_merge($result,
            $this->getFromSQL('getList', $filtersForGetList));
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getReactivesList(int $idRecipe): array
    {
        $requestReactivesIdFromSQL = "
         SELECT unit_reactive.id_library_reactive AS id
             , is_solvent
             , reactives.name
             , quantity
         FROM unit_reactive
                 LEFT JOIN (SELECT gso.id_library_reactive
                                 , CONCAT('ГСО-', gso.number, '-', gso.name) AS name
                            FROM gso
                            UNION
                            SELECT id_library_reactive
                                 , CONCAT('Лаб. реактив ', reactive_lab.name) AS name
                            FROM reactive_lab
                            UNION
                            SELECT id_library_reactive
                                 , CONCAT('СТ-', standart_titr.number, '-',
                                          standart_titr.name) AS name
                            FROM standart_titr
                            UNION
                            SELECT reactive.id_library_reactive
                                 , CONCAT(reactive.number, '-', reactive_model.name,
                                          ' (',
                                          reactive_pure.short_name, ') - ',
                                          reactive.doc_name) AS name
                            FROM reactive
                                     JOIN reactive_model
                                          ON reactive.id_reactive_model = reactive_model.id
                                     JOIN reactive_pure
                                          ON reactive.id_pure = reactive_pure.id) reactives
                           ON reactives.id_library_reactive =
                              unit_reactive.id_library_reactive                           
        WHERE id_recipe_model = {$idRecipe};            
        ";
        $reactivesIdFromSQL = $this->requestFromSQL($requestReactivesIdFromSQL);
        $reactivesList = [];

        foreach ($reactivesIdFromSQL as $reactive) {
            $reactiveList = [];
            $reactiveList[$reactive['name']]
                = $this->getReactiveListFromSQL($reactive['id'],
                $reactive['quantity']);

            if ($reactive['is_solvent'] == 0) {
                $reactivesList['reactives'][] = $reactiveList;
            } else {
                $reactivesList['solution'][] = $reactiveList;
            };
        }

        return $reactivesList;
    }

    protected function getReactiveListFromSQL(int $id, $quantity): array
    {
        $requestReactivesReceiveFromSQL = "
            SELECT reactives.id_library_reactive
                 , reactives_receive.id                  AS id_receive
                 , CONCAT(reactives.number, IFNULL(reactives_receive.number, ''), '-',
                          reactives.name)                AS name
                 , DATE_FORMAT(date_expired, '%d.%m.%Y') AS date_expired_dateformat
                 , CONCAT((reactives_receive.quantity -
                           IFNULL(reactives_consume.quantity, 0)), ' ',
                          unit_of_quantity.name)         AS quantity_full
                 , CONCAT({$quantity}, ' ',
                          unit_of_quantity.name)         AS quantity_consume_full
                 , {$quantity} AS quantity_consume
                 , CONCAT((reactives_receive.quantity -
                           IFNULL(reactives_consume.quantity, 0)) - {$quantity}, ' ',
                          unit_of_quantity.name)         AS total_full
            FROM (SELECT gso.id_library_reactive
                       , CONCAT('ГСО-', gso.number, '-') AS number
                       , name
                       , id_unit_of_quantity
                  FROM gso
                  UNION
                  SELECT id_library_reactive
                       , CONCAT('Лаб. реактив') AS number
                       , name
                       , id_unit_of_quantity
                  FROM reactive_lab
                  UNION
                  SELECT id_library_reactive
                       , CONCAT('СТ-', standart_titr.number, '-') AS number
                       , name
                       , id_unit_of_quantity
                  FROM standart_titr
                  UNION
                  SELECT reactive.id_library_reactive
                       , CONCAT(reactive.number, '-') AS number
                       , CONCAT(reactive_model.name,
                                ' (',
                                reactive_pure.short_name, ') - ',
                                reactive.doc_name)    AS name
                       , id_unit_of_quantity
                  FROM reactive
                           JOIN reactive_model
                                ON reactive.id_reactive_model = reactive_model.id
                           JOIN reactive_pure
                                ON reactive.id_pure = reactive_pure.id) reactives
                     JOIN (SELECT id
                                , CONCAT(id_library_reactive, '-', id) AS id_id
                                , id_library_reactive
                                , number
                                , quantity
                                , date_expired
                           FROM reactive_receive
                           UNION
                           SELECT id
                                , CONCAT(id_library_reactive, '-', id) AS id_id
                                , id_library_reactive
                                , number
                                , quantity
                                , DATE_ADD(gso_receive.date_production, INTERVAL
                                           gso_receive.storage_life_in_year * 360
                                           DAY)                        AS date_expired
                           FROM gso_receive
                           UNION
                           SELECT id
                                , CONCAT(id_library_reactive, '-', id) AS id_id
                                , id_library_reactive
                                , number
                                , quantity
                                , DATE_ADD(standart_titr_receive.date_production, INTERVAL
                                           standart_titr_receive.storage_life_in_year * 360
                                           DAY)                        AS date_expired
                           FROM standart_titr_receive
                           UNION
                           SELECT reactive_lab_receive.id
                                , CONCAT(reactive_lab_receive.id_library_reactive, '-',
                                         reactive_lab_receive.id) AS id_id
                                , reactive_lab_receive.id_library_reactive
                                , NULL
                                , reactive_lab_receive.quantity
                                , DATE_ADD(reactive_lab_receive.date_receive, INTERVAL
                                           recipe_model.storage_life_in_day
                                           DAY)                   AS date_expiry
                           FROM reactive_lab_receive
                                    JOIN reactive_lab
                                         ON reactive_lab_receive.id_reactive_lab =
                                            reactive_lab.id
                                    JOIN recipe_model
                                         ON recipe_model.id = reactive_lab.id_recipe_model) reactives_receive
                          ON reactives_receive.id_library_reactive =
                             reactives.id_library_reactive
            JOIN unit_of_quantity
                          ON unit_of_quantity.id = reactives.id_unit_of_quantity
            LEFT JOIN(SELECT CONCAT(id_library_reactive, '-',
                                     id_all_receive) AS id_id
                            , SUM(quantity)          AS quantity
                      FROM reactive_consume
                      GROUP BY id_id) reactives_consume
                      ON reactives_consume.id_id = reactives_receive.id_id
            WHERE date_expired >= CURDATE()
              AND reactives_receive.quantity -
                  IFNULL(reactives_consume.quantity, 0)-{$quantity} > 0
            AND reactives.id_library_reactive = {$id}
        ";
        if ('id_library_reactive' == 8) {
            $qq = $requestReactivesReceiveFromSQL;
            echo '<pre>';
            var_dump($qq);
            echo '</pre>';
            exit;
        }


        return $this->requestFromSQL($requestReactivesReceiveFromSQL);
    }


    public
    function addToSQL(
        array $data,
        string $type = null
    ): int {
        $organizationId = App::getOrganizationId();

        $namesTable = [
            'library_reactive'     => 'library_reactive',
            'reactive_lab'         => 'reactive_lab',
            'reactive_lab_receive' => 'reactive_lab_receive',
            'reactive_consume'     => 'reactive_consume'
        ];
        if ($type == null) {
            $name = array_key_first($data);
            if (!isset($namesTable[$name])) {
                return 0;
            }
            $dataAdd = $data[$name];
        } else if ($type == 'solutionAndConsume') {
            $idRecipeModelCount = $this->getByID('idRecipeModelCount',
                $data['id_recipe_model'])[0];
            $unitsReactive = $this->getByID('unitsReactive',
                $data['id_recipe_model']);

            if ($idRecipeModelCount['count'] == 0) {
                $dataFirstAdd['library_reactive']['organization_id'] = $organizationId;
                $dataFirstAdd['library_reactive']['id_library_reactive_table_name']
                    = $this->libraryReactive['reactive_lab'];
                $idFirstAdd = $this->addToSQL($dataFirstAdd);
                if (!$idFirstAdd) {
                    return 0;
                }

                $dataSecondAdd['reactive_lab']['organization_id'] = $organizationId;
                $dataSecondAdd['reactive_lab']['name'] = $data['name'];
                $dataSecondAdd['reactive_lab']['id_recipe_model']
                    = $data['id_recipe_model'];
                $dataSecondAdd['reactive_lab']['id_unit_of_quantity']
                    = $unitsReactive[0]['id_unit_of_quantity'];
                $dataSecondAdd['reactive_lab']['id_library_reactive']
                    = $idFirstAdd;
                $dataSecondAdd['reactive_lab']['organization_id']
                    = $organizationId;

                $idSecondAdd = $this->addToSQL($dataSecondAdd);
                if (!$idSecondAdd) {
                    return 0;
                }
            } elseif ($idRecipeModelCount['count'] == 1) {
                $idFirstAdd = $idRecipeModelCount['id_library_reactive'];
                $idSecondAdd = $idRecipeModelCount['id'];
            } elseif ($idRecipeModelCount['count'] > 1) {
                var_dump("Ошибка, количество реактивов с одинаковым рецептом не должно быть больше 1");
                exit;
            }

            $dataThirdAdd['reactive_lab_receive']['organization_id'] = $organizationId;
            $dataThirdAdd['reactive_lab_receive']['id_library_reactive']
                = $idFirstAdd;
            $dataThirdAdd['reactive_lab_receive']['date_receive']
                = $data['date_preparation'];
            $dataThirdAdd['reactive_lab_receive']['quantity']
                = $unitsReactive[0]['quantity_solution'];
            $dataThirdAdd['reactive_lab_receive']['id_reactive_lab']
                = $idSecondAdd;

            $idThirdAdd = $this->addToSQL($dataThirdAdd);

            if (!$idThirdAdd) {
                return 0;
            }

            $reactives = $data['reactive'];
            foreach ($reactives as $key => $i) {
                $reactivesId[] = $key;
            }
            foreach ($unitsReactive as $item) {
                $unitsReactiveId[] = $item['id_library_reactive'];
            }

            $itemAdd['reactive_consume']['organization_id'] = $organizationId;
            $itemAdd['reactive_consume']['date']
                = $data['date_preparation'];
            $itemAdd['reactive_consume']['type']
                = "Приготовление реактива {$data['name']}";
            $itemAdd['reactive_consume']['id_reactive_lab_receive']
                = $idThirdAdd;
            foreach ($reactives as $reactive) {
                $itemAdd['reactive_consume']['id_library_reactive']
                    = $reactive['id_library_reactive'];
                $itemAdd['reactive_consume']['quantity']
                    = $reactive['quantity_consume'];
                $itemAdd['reactive_consume']['id_all_receive']
                    = $reactive['id_receive'];

                $itemIsAdd = $this->addToSQL($itemAdd);
                if (!$itemIsAdd) {
                    return 0;
                }
            }
            return 1;
        }

        return $this->insertToSQL($dataAdd, $name, App::getUserId());
    }

    public function getFromSQL(string $typeName, array $filters = null): array
    {
        $organizationId = App::getOrganizationId();
        /*
        if ($typeName == 'getListOLD') {
            $requestFromSQL = $this->DB->Query("
            SELECT reactive_lab_receive.id
                 , CONCAT(reactive_lab_receive.quantity, ' ',
                          unit_of_quantity.name)       AS quantity_full
                 , reactive_lab_receive.date_receive
                 , DATE_ADD(reactive_lab_receive.date_receive, INTERVAL
                            recipe_model.storage_life_in_day
                            DAY)                       AS date_expiry
                 , recipe_model.name                   AS name_recipe
                 , ba_gost.gost
                 , CONCAT(IFNULL(b_user.last_name, '-'), ' ',
                          IFNULL(b_user.name, ''))     AS global_assigned_name
                 , IF(DATE_ADD(reactive_lab_receive.date_receive, INTERVAL
                               recipe_model.storage_life_in_day
                               DAY) < CURDATE(), 1, 0) AS is_expired
            FROM reactive_lab
                     JOIN reactive_lab_receive
                          ON reactive_lab_receive.id_reactive_lab = reactive_lab.id
                     JOIN unit_of_quantity
                          ON reactive_lab.id_unit_of_quantity = unit_of_quantity.id
                     JOIN recipe_model ON reactive_lab.id_recipe_model = recipe_model.id
                     JOIN ba_gost ON recipe_model.id_doc = ba_gost.id
                     LEFT JOIN b_user ON reactive_lab_receive.global_assigned = b_user.id            
            HAVING $having
            ORDER BY $order 
            $limit
        "
            );
        }*/
        if ($typeName == 'getList') {
            $request = "
            SELECT reactive_lab_receive.id
                 , CONCAT(reactive_lab_receive.quantity, ' ',
                          unit_of_quantity.name)       AS quantity_full
                 , DATE_FORMAT(reactive_lab_receive.date_receive, '%d.%m.%Y')
                     AS date_receive_dateformat
                 , reactive_lab_receive.date_receive
                 , DATE_FORMAT(DATE_ADD(reactive_lab_receive.date_receive, INTERVAL
                            recipe_model.storage_life_in_day
                            DAY), '%d.%m.%Y') AS date_expiry_dateformat                    
                 , DATE_ADD(reactive_lab_receive.date_receive, INTERVAL
                            recipe_model.storage_life_in_day
                            DAY)                       AS date_expiry
                 , recipe_model.name                   AS name_recipe
                 , ba_gost.gost
                 , CONCAT(IFNULL(b_user.last_name, '-'), ' ',
                          IFNULL(b_user.name, ''))     AS global_assigned_name
                 , IF(DATE_ADD(reactive_lab_receive.date_receive, INTERVAL
                               recipe_model.storage_life_in_day
                               DAY) < CURDATE(), 1, 0) AS is_expired
                 , reactives_full.names
            FROM (SELECT reactive_consume.id_reactive_lab_receive
                       , GROUP_CONCAT(
                        CONCAT('<b>',reactives.number, IFNULL(reactives_receive.number,''), ' ',
                               reactives.name, '</b><br> К-во = '
                            , reactive_consume.quantity, ' ',
                               unit_of_quantity.name, '<br>') SEPARATOR
                        ' ') AS names
                  FROM (SELECT reactive_consume.*
                               , CONCAT(id_library_reactive, '-', id_all_receive) AS id_id
                          FROM reactive_consume WHERE reactive_consume.organization_id = {$organizationId}) AS reactive_consume
                           JOIN (SELECT id
                                      , CONCAT(id_library_reactive, '-', id) AS id_id
                                      , id_library_reactive
                                      , number
                                 FROM reactive_receive WHERE reactive_receive.organization_id = {$organizationId} 
                                 UNION
                                 SELECT id
                                      , CONCAT(id_library_reactive, '-', id) AS id_id
                                      , id_library_reactive
                                      , number
                                 FROM gso_receive WHERE gso_receive.organization_id = {$organizationId} 
                                 UNION
                                 SELECT id
                                      , CONCAT(id_library_reactive, '-', id) AS id_id
                                      , id_library_reactive
                                      , number
                                 FROM standart_titr_receive WHERE standart_titr_receive.organization_id = {$organizationId} 
                                 UNION
                                 SELECT reactive_lab_receive.id
                                      , CONCAT(reactive_lab_receive.id_library_reactive,
                                               '-',
                                               reactive_lab_receive.id) AS id_id
                                      , reactive_lab_receive.id_library_reactive
                                      , NULL
                                 FROM reactive_lab_receive WHERE reactive_lab_receive.organization_id = {$organizationId}) reactives_receive
                                ON reactives_receive.id_id =
                                     reactive_consume.id_id
                           LEFT JOIN
                       (SELECT gso.id_library_reactive  
                             , CONCAT('ГСО-', gso.number, '-') AS number
                             , name
                             , id_unit_of_quantity
                        FROM gso WHERE gso.organization_id = {$organizationId}
                        UNION
                        SELECT id_library_reactive
                             , 'Лаб. реактив' AS number
                             , name
                             , id_unit_of_quantity
                        FROM reactive_lab WHERE reactive_lab.organization_id = {$organizationId} 
                        UNION
                        SELECT id_library_reactive
                             , CONCAT('СТ-', standart_titr.number, '-') AS number
                             , name
                             , id_unit_of_quantity
                        FROM standart_titr WHERE standart_titr.organization_id = {$organizationId} 
                        UNION
                        SELECT reactive.id_library_reactive
                             , CONCAT(reactive.number, '-') AS number
                             , CONCAT(reactive_model.name,
                                      ' (',
                                      reactive_pure.short_name, ') - ',
                                      reactive.doc_name)    AS name
                             , id_unit_of_quantity
                        FROM reactive
                                 JOIN reactive_model
                                      ON reactive.id_reactive_model = reactive_model.id
                                 JOIN reactive_pure
                                      ON reactive.id_pure = reactive_pure.id 
                                      WHERE reactive.organization_id = {$organizationId}) reactives
                       ON reactives.id_library_reactive =
                          reactive_consume.id_library_reactive
                           JOIN unit_of_quantity
                                ON unit_of_quantity.id = reactives.id_unit_of_quantity  
                  GROUP BY reactive_consume.id_reactive_lab_receive) reactives_full
                     LEFT JOIN reactive_lab_receive 
                                ON reactive_lab_receive.id = reactives_full.id_reactive_lab_receive 
                                AND reactive_lab_receive.organization_id = {$organizationId} 
                     LEFT JOIN reactive_lab
                               ON reactive_lab_receive.id_reactive_lab = reactive_lab.id 
                               AND reactive_lab.organization_id = {$organizationId} 
                     LEFT JOIN recipe_model
                               ON reactive_lab.id_recipe_model = recipe_model.id 
                     LEFT JOIN ba_gost ON recipe_model.id_doc = ba_gost.id 
                     LEFT JOIN b_user ON reactive_lab_receive.global_assigned = b_user.id
                     JOIN unit_of_quantity
                          ON reactive_lab.id_unit_of_quantity = unit_of_quantity.id                       
            HAVING  id {$filters['idWhichFilter']} AND name_recipe is not null
               AND date_receive BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']} 
                 AND {$filters['having']}
            ORDER BY {$filters['order']}
                {$filters['limit']}
        ";

        } /*else if ($typeName == 'getListReactive') {
            $requestFromSQL = "
            SELECT
                        recipe_model.id, react.name,unit_reactive.is_solvent,recipe_model.name as recipe_name,
                    CONCAT(react.quantity_receive-IFNULL(react.quantity_consume,0), ' ', unit_name) AS quantity_storage_full,
                    CONCAT(unit_reactive.quantity, ' ', unit_name) AS quantity_recipe_full,
                    CONCAT(react.quantity_receive-IFNULL(react.quantity_consume,0)-unit_reactive.quantity, ' ', unit_name) AS   quantity_total_full
            FROM recipe_model 
            JOIN unit_reactive ON unit_reactive.id_recipe_model = recipe_model.id
            JOIN (      SELECT consume.*,gso.name,gso.id_library_reactive,
                            gso_receive.quantity AS quantity_receive,
                            unit_of_quantity.name AS unit_name
                            FROM gso_receive
                            JOIN  gso ON gso.id_library_reactive=gso_receive.id_library_reactive
                            JOIN unit_of_quantity ON gso.id_unit_of_quantity = unit_of_quantity.id
                            LEFT JOIN (SELECT id_all_receive,id_recipe_model,
                            SUM(quantity) AS quantity_consume 
                            FROM reactive_consume
                            JOIN library_reactive ON reactive_consume.id_library_reactive = library_reactive.id
                            WHERE id_library_reactive_table_name = 2
                            GROUP BY id_all_receive
                            ) consume ON consume.id_all_receive = gso_receive.id
                            UNION
                            SELECT consume.*,standart_titr.name,standart_titr.id_library_reactive,
                            standart_titr_receive.quantity AS quantity_receive,
                            unit_of_quantity.name AS unit_name
                            FROM standart_titr_receive
                            JOIN  standart_titr ON standart_titr.id_library_reactive=standart_titr_receive.id_library_reactive
                            JOIN unit_of_quantity ON standart_titr.id_unit_of_quantity = unit_of_quantity.id
                            LEFT JOIN (SELECT id_all_receive,id_recipe_model,
                            SUM(quantity) AS quantity_consume 
                            FROM reactive_consume
                            JOIN library_reactive ON reactive_consume.id_library_reactive = library_reactive.id
                            WHERE id_library_reactive_table_name = 3
                            GROUP BY id_all_receive
                            ) consume ON consume.id_all_receive = standart_titr_receive.id
                            UNION
                             SELECT consume.*,reactive_lab.name,reactive_lab.id_library_reactive,
                            reactive_lab_receive.quantity AS quantity_receive,
                            unit_of_quantity.name AS unit_name
                            FROM reactive_lab_receive
                            JOIN  reactive_lab ON reactive_lab.id_library_reactive=reactive_lab_receive.id_library_reactive
                            JOIN unit_of_quantity ON reactive_lab.id_unit_of_quantity = unit_of_quantity.id
                            LEFT JOIN (SELECT id_all_receive,id_recipe_model,
                            SUM(quantity) AS quantity_consume 
                            FROM reactive_consume
                            JOIN library_reactive ON reactive_consume.id_library_reactive = library_reactive.id
                            WHERE id_library_reactive_table_name = 4
                            GROUP BY id_all_receive
                            ) consume ON consume.id_all_receive = reactive_lab_receive.id
                            UNION
                             SELECT consume.*,
                            CONCAT( reactive_model.name,' (',reactive_pure.short_name,') - ', reactive.doc_name) as name,
                            reactive.id_library_reactive,
                            reactive_receive.quantity AS quantity_receive,
                            unit_of_quantity.name AS unit_name
                            FROM reactive_receive
                            JOIN  reactive ON reactive.id_library_reactive=reactive_receive.id_library_reactive
                            JOIN reactive_model ON reactive.id_reactive_model = reactive_model.id
                            JOIN reactive_pure ON reactive.id_pure = reactive_pure.id 
                            JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity = unit_of_quantity.id
                            LEFT JOIN (SELECT id_all_receive,id_recipe_model,
                            SUM(quantity) AS quantity_consume 
                            FROM reactive_consume
                            JOIN library_reactive ON reactive_consume.id_library_reactive = library_reactive.id
                            WHERE id_library_reactive_table_name = 1
                            GROUP BY id_all_receive
                            ) consume ON consume.id_all_receive = reactive_receive.id
                    ) react ON  react.id_library_reactive= unit_reactive.id_library_reactive
        HAVING   unit_reactive.is_solvent = $order
        AND      recipe_model.id = $having
        " );
        }*/
        else {
            if ($typeName == 'recipe') {
                $request = "
            SELECT recipe_model.id
                 , CONCAT(ba_gost.gost, ' ', recipe_model.name, ' C=', recipe_model.concentration, ' ',
                          unit_of_concentration.name) AS name
            FROM recipe_model
                     JOIN unit_of_concentration ON recipe_model.id_unit_of_concentration = unit_of_concentration.id
                     JOIN ba_gost ON ba_gost.id = recipe_model.id_doc           
             ";
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");
            }
        }

        return $this->requestFromSQL($request);
    }

    public
    function getByID(
        string $name,
        $ID
    ): array {
        $organizationId = App::getOrganizationId();

        if ($name == 'idRecipeModelCount') {
            $requestFromSQL = $this->DB->Query(
                " SELECT  IFNULL(SUM(1),0) AS count, id_library_reactive, id
                        FROM reactive_lab
                        WHERE id_recipe_model = $ID AND organization_id = {$organizationId}
             "
            );
        }
        if ($name == 'unitsReactive') {
            $requestFromSQL = $this->DB->Query(
                " 
                SELECT unit_reactive.quantity AS quantity_reactive
                     , reactive.id_unit_of_quantity
                     , quantity_solution
                     , unit_reactive.id_library_reactive
                     , recipe_model.name
                FROM unit_reactive
                         LEFT JOIN (SELECT gso.id_unit_of_quantity, gso.id_library_reactive
                                    FROM gso
                                    UNION
                                    SELECT id_unit_of_quantity, reactive_lab.id_library_reactive
                                    FROM reactive_lab WHERE organization_id = {$organizationId} 
                                    UNION
                                    SELECT id_unit_of_quantity
                                         , standart_titr.id_library_reactive
                                    FROM standart_titr
                                    UNION
                                    SELECT id_unit_of_quantity, reactive.id_library_reactive
                                    FROM reactive
                                             JOIN reactive_model
                                                  ON reactive.id_reactive_model = reactive_model.id) reactive
                                   ON reactive.id_library_reactive =
                                      unit_reactive.id_library_reactive
                JOIN recipe_model ON recipe_model.id = unit_reactive.id_recipe_model                
                WHERE id_recipe_model = $ID
                ORDER BY is_solvent DESC
             "
            );
        }
        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }
}