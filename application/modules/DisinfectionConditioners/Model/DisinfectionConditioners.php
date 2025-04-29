<?php

/**
 * Журнал учета работ по очистки и дезинфекции кондиционеров
 * Class DisinfectionConditioners
 */
class DisinfectionConditioners extends Model
{
    public string $location = '/disinfectionConditioners/list/';

    public function getList($filter = []): array
    {
        $filters = [
            'having' => "",
            'limit' => "",
            'order' => "",
        ];

        $tableColumnForFilter = [
            'date',
            'NUMBER',
            'conditioner',
            'disinfectant',
            'global_assigned_name',
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
            // По дате проведения работ
            if (isset($filter['search']['date_dateformat'])) {
                $filters['having'] .= "DATE_FORMAT(dc.date, '%d.%m.%Y') LIKE '%{$filter['search']['date_dateformat']}%' AND ";
            }
            // По дате приготовления раствора
            if (isset($filter['search']['date_sol_dateformat'])) {
                $filters['having'] .= "DATE_FORMAT(dc.date_sol, '%d.%m.%Y') LIKE '%{$filter['search']['date_sol_dateformat']}%' AND ";
            }

            foreach ($tableColumnForFilter as $item) {
                $filters['having'] .= addHaving($filter, $item);
            }

            if (isset($filter['paginate'])) {
                $offset = 0;
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
            'by' => $tableColumnForFilter[0],
            'dir' => 'DESC'
        ];

        if (!empty($filter['order'])) {
            if (isset($filter['order']['by']) && $filter['order']['by'] === 'global_assigned_name') {
                $orderFilter['by'] = "LEFT(TRIM(CONCAT_WS(' ', bu.NAME, bu.LAST_NAME)), 1)";
            } else {
                $orderFilter['by'] = $filter['order']['by'];
            }
            $orderFilter['dir'] = $filter['order']['dir'];
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
            'disinfection_conditioners'
        ];

        if ($type == null) {
            $name = array_key_first($data);
            if (!in_array($name, $namesTable,true)) {
                return 0;
            }
            $dataAdd = $data[$name];
        }

        return $this->insertToSQL($dataAdd, $name, App::getUserId());
    }

    public function getFromSQL(string $name, array  $filters = null): array
    {
        $organizationId = App::getOrganizationId();
        $namesTable = [
            'allRecord' => 'disinfection_conditioners'
        ];
        $response = [];
        if (isset($namesTable[$name])) {
            $requestFromSQL = $this->DB->Query("SELECT * from {$namesTable[$name]}");
        }

        if ($name == 'getList') {
            $requestFromSQL = $this->DB->Query(
               "SELECT DATE_FORMAT(dc.date,'%d.%m.%Y') AS date_dateformat, 
                       dc.date,
                       dc.room_id,
                       dc.conditioner,
                       dc.disinfectant,
                       DATE_FORMAT(dc.date_sol,'%d.%m.%Y') AS date_sol_dateformat, 
                       dc.date_sol,
                       dc.user_id, 
                       concat(r.NAME, ' ', r.NUMBER) as NUMBER, 
                       TRIM(CONCAT_WS(' ', bu.NAME, bu.LAST_NAME)) as global_assigned_name 
                FROM disinfection_conditioners AS dc
                    
                LEFT JOIN ROOMS as r ON dc.room_id = r.ID
 
                LEFT JOIN b_user as bu ON dc.global_assigned = bu.ID
                WHERE dc.organization_id = {$organizationId}
                HAVING {$filters['having']}
                ORDER BY {$filters['order']}
                {$filters['limit']}
            ");
        }

        while ($row = $requestFromSQL->Fetch()) {
            $row['date'] = date('d.m.Y', strtotime($row['date']));
            $row['date_sol'] = date('d.m.Y', strtotime($row['date_sol']));
            $response[] = $row;
        }

        return $response;
    }
}