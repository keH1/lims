<?php

/**
 * Модель для работы с рецептами растворов
 * Class Recipe
 */
class Recipe extends Model
{
    public string $location = "/recipe/list/";

    public function getList($filter): array
    {
        $having = "";
        $limit = "";
        $order = "";
        $tableColumnForFilter = [
            'name' => 'name',
            'concentration_full' => 'concentration_full',
            'type_name' => 'type_name',
            'GOST' => 'GOST',
            'reactives_full' => 'reactives_full',
            'solvent_full' => 'solvent_full',
            'quantity_solution_full' => 'quantity_solution_full',
            'storage_life' => 'storage_life',
            'check_in_day' => 'check_in_day',
            'global_assigned_name'=>'global_assigned_name'
        ];

        function addHaving($filter, $key, $value): string
        {
            $filterUsed = $filter['search'][$key];
            if (isset($filterUsed)) {
                return "$value LIKE '%$filterUsed%' AND ";
            }
            return '';
        }

        if (!empty($filter)) {
            foreach ($tableColumnForFilter as $key => $value) {
                $having .= addHaving($filter, $key, $value);
            }

            if (isset($filter['paginate'])) {
                $offset = 0;
                // количество строк на страницу
                if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                    $length = $filter['paginate']['length'];

                    if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                        $offset = $filter['paginate']['start'];
                    }
                    $limit = "LIMIT $offset, $length";
                }
            }
        }
        $orderFilter = [
            'by' => 'recipe_model.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $orderFilter['dir'] = 'ASC';
            }
            $orderFilter['by'] = $tableColumnForFilter[$filter['order']['by']];
        }
        $order = "{$orderFilter['by']} {$orderFilter['dir']} ";

        $result = [];
        //Затычка, что бы не было пустого WHERE в SQL запросе
        $having .= "1 ";
        $result = $this->getFromSQL('getList', $having, $order, $limit);
        $dataTotal = count($this->getFromSQL('allRecord'));
        $dataFiltered = count($result);

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    public function addToSQL(array $data, string $type = null): int
    {
        $namesTable = [
            'reactive_model' => 'reactive_model',
            'unit_reactive' => 'unit_reactive',
            'reactive' => 'reactive',
            'receive' => 'receive_reactive',
            'solvent' => 'unit_solvent',
            'solution_as_reactive' => 'solution_as_reactive',
            'recipe_model' => 'recipe_model',
            'reactive_lab' => 'reactive_lab',
            'library_reactive' => 'library_reactive',
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
        if ($type == 'modelRecipe') {
            $dataFirstAdd['recipe_model'] = $data['recipe_model'];
            $idFirstAdd = $this->addToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $dataArray = $data['reactives'];
            foreach ($dataArray as $item) {
                $itemAdd['unit_reactive'] = $item;
                $itemAdd['unit_reactive']['id_recipe_model'] = $idFirstAdd;
                $itemAdd['unit_reactive']['is_solvent'] = 0;
                $itemIsAdd = $this->addToSQL($itemAdd);
                if (!$itemIsAdd) {
                    return 0;
                }
            }
            $data['solvent']['id_recipe_model'] = $idFirstAdd;
            $dataSecondAdd['unit_reactive'] = $data['solvent'];
            $dataSecondAdd['unit_reactive']['is_solvent'] = 1;
            return $this->addToSQL($dataSecondAdd);
        }
        if ($type == 'solutionAsReactive') {
            $dataFirstAdd['library_reactive']['id_library_reactive_table_name'] = $libraryReactive['reactive_lab'];
            $idFirstAdd = $this->addToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $data['reactive_lab'] ['id_library_reactive'] = $idFirstAdd;
            $idUnitOfQuantity = $this->getByID('idUnitOfQuantity', $data['reactive_lab']['id_recipe_model']);
            $data['reactive_lab']['id_unit_of_quantity'] = $idUnitOfQuantity;
            $dataSecondAdd['reactive_lab'] = $data['reactive_lab'];
            return $this->addToSQL($dataSecondAdd);
        }

        return $this->insertToSQL($dataAdd, $name, $_SESSION['SESS_AUTH']['USER_ID']);
    }

    public function getFromSQL(string $name, string $having = null, string $order = null, string $limit = null): array
    {
        $nameArray = [
            'doc' => 'ba_gost',
            'unit_of_concentration' => 'unit_of_concentration',
            'allRecord' => 'recipe_model',
            'unit_of_quantity' => 'unit_of_quantity',
            'recipe_type' => 'recipe_type',
            'global_assigned_name' => 'global_assigned_name'
        ];
        $response = [];
        if (isset($nameArray[$name])) {
            $requestFromSQL = $this->DB->Query("select * from $nameArray[$name]");
        }
        if ($name == 'getList') {
            $requestFromSQL = $this->DB->Query(
                "SELECT recipe_model.*
       ,CONCAT(storage_life_in_day,'<br>',IFNULL(check_property,'')) AS storage_life
       ,ba_gost.GOST,recipe_type.name AS type_name,
       CONCAT (IFNULL(b_user.LAST_NAME,'-'),' ',IFNULL(b_user.NAME,'')) as global_assigned_name,
             GROUP_CONCAT( CASE  WHEN is_solvent = 0 THEN
                            CONCAT( react.name ,' <br> К-во = ',unit_reactive .quantity,' ', react.unit_name  ,'<br>')
                            END SEPARATOR ' ') reactives_full,
            GROUP_CONCAT( CASE WHEN is_solvent = 1 THEN
                            CONCAT( react.name ,' <br> К-во = ',unit_reactive .quantity,' ', react.unit_name  ,'<br>') 
                            END SEPARATOR ' ') solvent_full,
            GROUP_CONCAT( CASE WHEN is_solvent = 1 THEN
                            CONCAT(recipe_model.quantity_solution, ' ', react.unit_name) 
                            END SEPARATOR ' ') quantity_solution_full,
            CONCAT(recipe_model.concentration,'  ',unit_of_concentration.name) AS concentration_full
            FROM recipe_model
            JOIN ba_gost ON recipe_model.id_doc = ba_gost.ID
            JOIN unit_reactive ON unit_reactive.id_recipe_model=recipe_model.id
            JOIN (  SELECT gso.id,id_library_reactive,gso.name,
                                    unit_of_quantity.name as unit_name 
                                    FROM gso
                                    JOIN unit_of_quantity ON gso.id_unit_of_quantity = unit_of_quantity.id
                            UNION
                            SELECT reactive_lab.id,id_library_reactive,
                                    CONCAT('Лаб реактив ', reactive_lab.name) as name,
                                    unit_of_quantity.name as unit_name 
                            FROM reactive_lab
                                JOIN unit_of_quantity ON reactive_lab.id_unit_of_quantity = unit_of_quantity.id
                            UNION
                                SELECT standart_titr.id,id_library_reactive,
                                        CONCAT('Титр ', standart_titr.name)  AS name,
                                        unit_of_quantity.name as unit_name  
                                 FROM standart_titr
                                JOIN unit_of_quantity ON standart_titr.id_unit_of_quantity = unit_of_quantity.id
                            UNION
                                SELECT reactive.id, reactive.id_library_reactive,
                                        CONCAT( reactive_model.name,' (',reactive_pure.short_name,') - ', reactive.doc_name) as name,
                                         unit_of_quantity.name as unit_name 
                                FROM reactive
                                JOIN reactive_model ON reactive.id_reactive_model = reactive_model.id
                                JOIN reactive_pure ON reactive.id_pure = reactive_pure.id 
                                JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity = unit_of_quantity.id                   
                ) react ON  react.id_library_reactive= unit_reactive.id_library_reactive
            JOIN unit_of_concentration ON recipe_model.id_unit_of_concentration = unit_of_concentration.id
            JOIN recipe_type ON  recipe_model.id_recipe_type= recipe_type.id
            LEFT JOIN b_user ON  recipe_model.global_assigned =b_user.ID
            GROUP BY recipe_model.id
            HAVING $having
            ORDER BY $order
            $limit
        "
            );
        }
        if ($name == 'reactive') {
            $requestFromSQL = $this->DB->Query(
                "
            SELECT library_reactive.id, react.name, react.unit
            FROM library_reactive
             JOIN (SELECT gso.id,
                          id_library_reactive,
                          CONCAT('ГСО-', gso.number, ' ', gso.name, ' ', gso.doc,
                                 ' ',
                                 gso_specification.approximate_concentration,
                                 unit_of_concentration.name) AS name,
                          unit_of_quantity.name              as unit
                   FROM gso
                            JOIN unit_of_quantity
                                 ON gso.id_unit_of_quantity = unit_of_quantity.id
                            LEFT JOIN gso_specification
                                      ON gso_specification.id_gso = gso.id
                            LEFT JOIN unit_of_concentration
                                      ON unit_of_concentration.id =
                                         gso_specification.id_unit_of_concentration
                   UNION
                   SELECT reactive_lab.id,
                          id_library_reactive,
                          CONCAT('Лаб реактив ', reactive_lab.name) as name,
                          unit_of_quantity.name                     as unit
                   FROM reactive_lab
                            JOIN unit_of_quantity
                                 ON reactive_lab.id_unit_of_quantity =
                                    unit_of_quantity.id
                   UNION
                   SELECT standart_titr.id,
                          id_library_reactive,
                          CONCAT('СТ-', standart_titr.number, ' ',
                                 standart_titr.name) AS name,
                          unit_of_quantity.name      as unit
                   FROM standart_titr
                            JOIN unit_of_quantity
                                 ON standart_titr.id_unit_of_quantity =
                                    unit_of_quantity.id
                   UNION
                   SELECT reactive.id,
                          reactive.id_library_reactive,
                          CONCAT(reactive.number, '- ', reactive_model.name, ' (',
                                 reactive_pure.short_name, ') - ',
                                 reactive.doc_name) as name,
                          unit_of_quantity.name     as unit
                   FROM reactive
                            JOIN reactive_model
                                 ON reactive.id_reactive_model = reactive_model.id
                            JOIN reactive_pure
                                 ON reactive.id_pure = reactive_pure.id
                            JOIN unit_of_quantity
                                 ON reactive_model.id_unit_of_quantity =
                                    unit_of_quantity.id) react
                  ON react.id_library_reactive = library_reactive.id 
"
            );
        }
        if ($name == 'solvent') {
            $requestFromSQL = $this->DB->Query(
                "SELECT library_reactive.id ,react.name,react.unit
 FROM library_reactive
 JOIN (   SELECT reactive_lab.id,id_library_reactive,
                                    CONCAT('Лаб реактив ', reactive_lab.name) as name,
                                    unit_of_quantity.name as unit
                            FROM reactive_lab
                                JOIN unit_of_quantity ON reactive_lab.id_unit_of_quantity = unit_of_quantity.id
                            UNION
                                SELECT reactive.id, reactive.id_library_reactive,
                                        CONCAT( reactive_model.name,' (',reactive_pure.short_name,') - ', reactive.doc_name) as name,
                                         unit_of_quantity.name as unit 
                                FROM reactive
                                JOIN reactive_model ON reactive.id_reactive_model = reactive_model.id
                                JOIN reactive_pure ON reactive.id_pure = reactive_pure.id 
                                JOIN unit_of_quantity ON reactive_model.id_unit_of_quantity = unit_of_quantity.id                   
                ) react ON  react.id_library_reactive= library_reactive.id
             "
            );
        }
        if ($name == 'recipe') {
            $requestFromSQL = $this->DB->Query(
                "SELECT recipe_model.id,
            CONCAT( recipe_model.name,' C=',recipe_model.concentration,' ', unit_of_concentration.name) as name
            FROM recipe_model
            JOIN unit_of_concentration ON recipe_model.id_unit_of_concentration = unit_of_concentration.id            
             "
            );
        }
        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    public function getByID(string $name, $ID): string
    {
        if ($name == 'idUnitOfQuantity') {
            $requestFromSQL = $this->DB->Query(
                "SELECT reactive.id_unit_of_quantity 
                        FROM recipe_model
                        JOIN  unit_reactive ON  unit_reactive.id_recipe_model=recipe_model.id
                        JOIN (  SELECT reactive_lab.id,id_library_reactive,id_unit_of_quantity
                                FROM reactive_lab
                                UNION
                                    SELECT reactive.id, reactive.id_library_reactive,reactive_model.id_unit_of_quantity                                       
                                    FROM reactive
                                    JOIN reactive_model ON reactive.id_reactive_model = reactive_model.id
                                ) reactive ON reactive.id_library_reactive = unit_reactive.id_library_reactive
                        WHERE is_solvent =1 and recipe_model.id = $ID        
             "
            );
        }

        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }

        return array_values($response[0])[0];
    }
}

