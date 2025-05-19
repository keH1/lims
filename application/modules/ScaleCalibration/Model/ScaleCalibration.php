<?php


class ScaleCalibration extends Model
{
    public string $location = '/scale/list/';

    public function getList($filter = []): array
    {
        $filters = [
            'having' => "",
            'limit' => "",
            'order' => "",
        ];

        $tableColumnForFilter = [
            'date_calibration',
            'name',
            'temperature',
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
                    $filters['limit'] = "LIMIT {$offset}, {$length}";
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

        if ( (int)$filter['idScale'] > 0 ) {
            $filters['idScale'] = "bs.ID = {$filter['idScale']}";
        } else {
            $filters['idScale'] = '1';
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
        $data['organization_id'] = App::getOrganizationId();
        $namesTable = [
            'scale_calibration'
        ];

        if ($type == null) {
            $name = array_key_first($data);
            if (!in_array($name, $namesTable, true)) {
                return 0;
            }
            $dataAdd = $data[$name];
        }

        return $this->insertToSQL($dataAdd, $name, App::getUserId());


    }

	public function getFromSQL(string $name, array $filters = null): array
	{
        $organizationId = App::getOrganizationId();

		$response = [];

        if ($name == 'getList') {
            $requestFromSQL = $this->DB->Query(
                "SELECT sc.*, sc.id_weight, sc.id_scale,
                            CONCAT (IFNULL(bu.LAST_NAME,'-'),' ',IFNULL(bu.NAME,'')) as global_assigned_name
                    FROM scale_calibration as sc
                    LEFT JOIN b_user as bu ON  sc.global_assigned = bu.ID
                    HAVING sc.id_scale {$filters['idScale']}
                           {$filters['month']} and {$filters['having']}
                    ORDER BY {$filters['order']}
                    {$filters['limit']}"
			);
		} elseif ($name == 'allRecord') {
            $requestFromSQL = $this->DB->Query(
                "SELECT sc.*, CONCAT (IFNULL(bu.LAST_NAME,'-'),' ',IFNULL(bu.NAME,'')) as global_assigned_name
                        FROM scale_calibration as sc                        
                        LEFT JOIN b_user as bu ON  sc.global_assigned = bu.ID
                        "
            );
        }
        $i = 1;

        $scale = [
            '235' => ['name' => 'GX-6100, Зав №14594617'],
            '237' => ['name' => 'GX-6100, Зав №14574512'],
            '239' => ['name' => 'GX-6100, Зав №14574507']
        ];

        $weight = [
            '279' => ['name' => 'Калибратор давления, Зав №SN-2024-279']
        ];

        while ($row = $requestFromSQL->Fetch()) {

            if ($name == 'getList') {
                $row['number'] = $i;
                $round = strlen(explode('.', $row['scale_error'])[1]);
                $result = round($row['weight_result'] - $row['mass_weight'], $round);
                $row['results'] = $row['scale_error'] >= abs($result);
                $row['scale_error'] =  str_replace('.', ',', $row['scale_error']);
                $row['date_calibration'] = date('d.m.Y', strtotime($row['date_calibration']));
                $row['weight_name'] = $weight[$row['id_weight']]['name'];
                $row['scale_name'] = $scale[$row['id_scale']]['name'];
//				$row['weight_result'] =  str_replace('.', ',', $row['weight_result']);


                $row['weight_result'] =  number_format($row['weight_result'], $round, ',', '');
//				$row['mass_weight'] =  str_replace('.', ',', $row['mass_weight']);
                $row['mass_weight'] =  number_format($row['mass_weight'], $round, ',', '');
                $i++;
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
        $organizationId = App::getOrganizationId();
        return $this->DB->Query(
            "select max(date_calibration) as max_date, min(date_calibration) as min_date 
                    from scale_calibration
                    where date_calibration <> '0000-00-00 00:00:00'  AND organization_id = {$organizationId}"
        )->Fetch();
    }

	public function autoFill($dateStart, $dateEnd)
	{
        $organizationId = App::getOrganizationId();
		$userModel = new User();
		$start = new DateTime($dateStart);
		$end = new DateTime($dateEnd);

		// create an iterateable period of date (P1D equates to 1 day)
		$period = new DatePeriod($start, new DateInterval('P1D'), $end);

		// best stored as array, so you can add more than one
		$holidays = ['2023-02-23','2023-03-08'];

		$scaleWeightArr = $this->getScale();

		$i = 0;
		foreach($period as $dt) {
			$curr = $dt->format('D');
			$date = $dt->format('Y-m-d');

			// substract if Saturday or Sunday
			if ($curr == 'Sat' || $curr == 'Sun' || in_array($date, $holidays)) {
				continue;
			}

			foreach ($scaleWeightArr as $value) {

//				echo '<pre>';
//				print_r($value['id_scale']);
//				exit();
				$minDate = strtotime($date . ' 08:00:00');
				$maxDate = strtotime($date . ' 08:15:00');
				$randDate = rand($minDate, $maxDate);
				$resultDate = date('Y-m-d H:i:s', $randDate);

				$scaleError = $this->DB->Query("select scaleError from ba_oborud where organization_id = {$organizationId} AND ID = {$value['id_scale']}")->Fetch();
				$weightMass = $this->DB->Query("select weightMass from ba_oborud where organization_id = {$organizationId} AND ID = {$value['id_weight']}")->Fetch();

				$data = $this->DB->Query("select * from scale_calibration where organization_id = {$organizationId} AND id_scale = {$value['id_scale']} 
                                  and org
                                  and `date_calibration` like '{$date}'")->Fetch();
				$dataAssigned = $this->DB->Query("select ID_ASSIGN1, ID_ASSIGN2 from ba_oborud where organization_id = {$organizationId} AND ID = {$value['id_scale']}")->Fetch();

				if (!empty($dataAssigned['ID_ASSIGN1'])) {
					$userActive = $userModel->checkWorkActive($dataAssigned['ID_ASSIGN1'], $date);

					$userId = $dataAssigned['ID_ASSIGN1'];
					if (!empty($userActive)) {
						$userId = $dataAssigned['ID_ASSIGN2'];
					}
				} else {
					$userId = 111;
				}

				$round = pow(10, strlen(explode('.', $scaleError['scaleError'])[1]));
				$scaleResult = rand(($weightMass['weightMass'] * $round) - ($scaleError['scaleError'] * $round), ($weightMass['weightMass']  * $round) + ($scaleError['scaleError']  * $round)) / $round;


				if ( empty($data) ) {
					$i++;
					$dateInsert = [
						'id_scale' => $value['id_scale'],
						'id_weight' => $value['id_weight'],
						'date_calibration' => "'$date'",
						'mass_weight' => "'{$weightMass['weightMass']}'",
						'weight_result' => "'{$scaleResult}'",
						'scale_error' => "'{$scaleError['scaleError']}'",
						'global_assigned' => $userId,
						'global_entry_date' => "'{$resultDate}'"
					];

					$this->DB->Insert('scale_calibration', $dateInsert);
				}
			}
		}

		return $i;
	}

	private function getScale()
	{
		$result = [];

		$res = $this->DB->Query("SELECT * FROM `scale_weight`");

		while ($row = $res->Fetch()) {
			$result[] = $row;
		}

		return $result;
	}
}
