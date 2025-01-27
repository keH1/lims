<?php


class Coal extends Model
{
    public string $location = '/coal/list/';

    public function getList($filter = []): array
    {
        $filters = [
            'having' => "",
            'limit' => "",
            'order' => "",
        ];

        $tableColumnForFilter = [
            'date_regeneration_end',
            'type_bdb',
            'eb_date',
            'fb_date',
            'results',
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
            'by' => $tableColumnForFilter[0],
            'dir' => 'DESC'
        ];

        if (!empty($filter['order'])) {
            $orderFilter['dir'] = $filter['order']['dir'];
            $orderFilter['by'] = $filter['order']['by'];
        }

        $filters['order'] = "{$orderFilter['by']} {$orderFilter['dir']} ";

        if ( $filter['idCoal'] != null ) {
            $filters['idCoal'] = '="' . $filter['idCoal'] . '"';
        } else {
            $filters['idSclae']= '>0';
        }

        $filters['month'] = '';
        if ( isset($filter['month']) ) {
            $filters['month'] .= 'AND (' . $tableColumnForFilter[0] . ' BETWEEN "' . $filters['month'] . '-01" and DATE_ADD("' . $filters['month'] . '-01", INTERVAL 1 MONTH)- INTERVAL 1 DAY)';
        }

        if ( $filter['date_start'] != '' ) {
            $filters['month'] .= " and sc.{$tableColumnForFilter[0]} >= '{$filter['date_start']}-01' ";
        }
        if ( $filter['date_end'] != '' ) {
            $filters['month'] .= " and sc.{$tableColumnForFilter[0]} <= LAST_DAY('{$filter['date_end']}-01') ";
        }



        // Затычка, что бы не было пустого WHERE в SQL запросе
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
            'coal_regeneration',
			'empty_bdb',
			'full_bdb'
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

	public function getFromSQL(string $name, array $filters = null): array
	{
		$namesTable = [
			'allRecord' => 'coal_regeneration'
		];

		$response = [];

		if (isset($namesTable[$name])) {
			$requestFromSQL = $this->DB->Query("SELECT * from $namesTable[$name]");
		}

		if ($name == 'getList') {
			$requestFromSQL = $this->DB->Query(
				"SELECT cr.*,
					ebdb.date_test as eb_date, ebdb.impuls_1 as eb_i1, ebdb.impuls_2 as eb_i2,
					ebdb.impuls_3 as eb_i3, ebdb.t_1 as eb_t1, ebdb.t_2 as eb_t2, ebdb.t_3 as eb_t3,
					ebdb.speed_1 as eb_s1, ebdb.speed_2 as eb_s2, ebdb.speed_3 as eb_s3, ebdb.average as eb_average, 
					ebdb.type_bdb as type_bdb,
					fbdb.date_test as fb_date, fbdb.impuls_1 as fb_i1, fbdb.impuls_2 as fb_i2,
					fbdb.impuls_3 as fb_i3, fbdb.t_1 as fb_t1, fbdb.t_2 as fb_t2, fbdb.t_3 as fb_t3,
					fbdb.speed_1 as fb_s1, fbdb.speed_2 as fb_s2, fbdb.speed_3 as fb_s3, fbdb.average as fb_average, 
					fbdb.A_b as A_b,
					CONCAT (IFNULL(bu.LAST_NAME,'-'),' ',IFNULL(bu.NAME,'')) as global_assigned_name
                    FROM coal_regeneration as cr
                    LEFT JOIN empty_bdb as ebdb ON cr.id = ebdb.id_cr
                    LEFT JOIN full_bdb as fbdb ON cr.id = fbdb.id_cr
                    LEFT JOIN b_user as bu ON  cr.global_assigned = bu.ID                
                    HAVING  {$filters['having']}
                    ORDER BY {$filters['order']}
                    {$filters['limit']}
                    "
			);
		}
		if ($name == 'CoalRegeneration') {
			$requestFromSQL = $this->DB->Query("SELECT cr.id,
 												cr.date_regeneration_end, ebdb.id as e_id, fbdb.id as f_id
 												FROM coal_regeneration as cr
 												LEFT JOIN empty_bdb as ebdb ON cr.id = ebdb.id_cr
                    							LEFT JOIN full_bdb as fbdb ON cr.id = fbdb.id_cr 
 												");
		}

		$i = 1;

		while ($row = $requestFromSQL->Fetch()) {

			if ($name == 'getList') {

				$row['results'] = '';

				if (!empty($row['A_b'])) {
					$row['results'] = $row['A_b'] <= 1.9;
				}
			}

			$response[] = $row;

		}

		return $response;
	}


    /**
     * @return array|false
     */
    public function getMinMaxDateFridgeControl()
    {
        return $this->DB->Query(
            "select max(date_calibration) as max_date, min(date_calibration) as min_date 
                    from coal_regeneration
                    where date_calibration <> '0000-00-00 00:00:00'"
        )->Fetch();
    }
}
