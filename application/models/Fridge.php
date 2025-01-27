<?php


class Fridge extends Model
{
    public string $location = '/fridge/list/';

    public function getList($filter = []): array
    {
        $filters = [
            'having' => "",
            'limit' => "",
            'order' => "",
        ];

        $tableColumnForFilter = [
            'name',
            'OBJECT',
            'TYPE_OBORUD',
            'FACTORY_NUMBER',
            'INV_NUM',
            'range_full',
            'global_assigned_name'
        ];
        function addHaving($filter, $item): string
        {
            $filterUsed = $filter['search'][$item];


            if (isset($filterUsed)) {
                return "$item LIKE '%$filterUsed%' AND ";
            }
            return '';
        }

        if (!empty($filter)) {
            foreach ($tableColumnForFilter as $item) {
                $filters['having'] .= addHaving($filter, $item);
            }

            if (isset($filter['paginate'])) {
                $offset = 0;
                // количество строк на страницу
                if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                    $length = $filter['paginate']['length'];

                    if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                        $offset = $filter['paginate']['start'];
                    }
                    $filters['limit'] = "LIMIT $offset, $length";
                }
            }
        }

        $orderFilter = [
            'by' => 'id',
            'dir' => 'DESC'
        ];

        if (!empty($filter['order'])) {
            $orderFilter['dir'] = $filter['order']['dir'];
            $orderFilter['by'] = $filter['order']['by'];
        }

        $filters['order'] = "{$orderFilter['by']} {$orderFilter['dir']} ";

        //Затычка, что бы не было пустого WHERE в SQL запросе
        $filters['having'] .= "1 ";
        $result = $this->getFromSQL('getList', $filters);

        $dataTotal = count($this->getFromSQL('allRecord'));
        $dataFiltered = count($result);

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    public function addToSQL(array $data, string $type = null): int
    {
        $namesTable = [
            'fridge'
        ];

        if ($type == null) {
            $name = array_key_first($data);
            if (!in_array($name, $namesTable)) {
                return 0;
            }
            $dataAdd = $data[$name];
        }

        return $this->insertToSQL($dataAdd, $name);
    }

    public function getFromSQL(
        string $name,
        array $filters = null
    ): array {
        $namesTable = [
            'allRecord' => 'water'
        ];
        $response = [];
        if (isset($namesTable[$name])) {
            $requestFromSQL = $this->DB->Query("SELECT * from $namesTable[$name]");
        }

        if ($name == 'getList') {
            $requestFromSQL = $this->DB->Query(
                "SELECT fridge.id,OBJECT,
                        TYPE_OBORUD,
                        FACTORY_NUMBER,
                        INV_NUM,
                        unit_fridge.name,
                        CONCAT(first_range,' — ',last_range,' °C' ) AS range_full,
                        CONCAT (IFNULL(b_user.LAST_NAME,'-'),' ',IFNULL(b_user.NAME,'')) as global_assigned_name
                FROM fridge
                JOIN unit_fridge ON fridge.id_unit_fridge =unit_fridge.id
                JOIN ba_oborud ON fridge.id_ba_oborud=ba_oborud.ID
                LEFT JOIN b_user ON  fridge.global_assigned =b_user.ID
                HAVING {$filters['having']}
                ORDER BY {$filters['order']}
                {$filters['limit']}
        "
            );
        }
        if ($name == 'oborud') {
            $requestFromSQL = $this->DB->Query(
                "
                SELECT ID AS id,
                CONCAT(
                    SUBSTRING(OBJECT,1,31),' ',SUBSTRING(TYPE_OBORUD,1,21),' Зав №',FACTORY_NUMBER
                    ) AS name
                FROM ba_oborud
                WHERE IDENT='VO'
            AND OBJECT LIKE '%холод%'
             "
            );
        }
        if ($name == 'type_fridge') {
            $requestFromSQL = $this->DB->Query(
                "SELECT * FROM unit_fridge                
             "
            );
        }

        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }
        
        return $response;
    }
}

