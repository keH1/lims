<?php

class Electric extends Model
{
    private string $location = '/electric/list/';

    /*  key=>[$i,$k] $i = 1 то выполняется запрос в getFromSQL  SELECT * FROM  $key
                          0, то запрос пишется в getFromSQL
                     $k = 0, то select передается как есть, если
                     $k = 1, то добавляется select $keyPlusAll = [['id'=-1,'name'='Все']] иначе
                     введите свой массив для добавления к $keyPlusAll в формате смотри на выше
    */
    private array $selectInList = [
        'room' => [0, 1]
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
        $organizationId = App::getOrganizationId();
        if ($typeName == null) {
            $dataAdd = $data;
        } elseif ($typeName == 'addMeasurement') {
            $dataAdd['electric_control'] = $data['electric_control'];
            $dataAdd['electric_control']['id_electric_norm'] = $this->getFromSQL("lastElectricNorm")[0]['id'];
            $dataAdd['electric_control']['organization_id'] = $organizationId;
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }
        return $this->insertToSQL($dataAdd);
    }

    private function getFromSQL(string $typeName, array $filters = null): array
    {
        $organizationId = App::getOrganizationId();

        if ($typeName == 'getList') {
            $request = "
            SELECT DATE_FORMAT(electric_control.date, '%d.%m.%Y') AS date_dateformat, 
                   electric_control.*, 
                   CONCAT(ROOMS.number, ' - ', ROOMS.name) AS name, 
                   TRIM(CONCAT_WS(' ', b_user.NAME, b_user.LAST_NAME)) AS global_assigned_name, 
                   IF(voltage_ua_conclusion = 0 
                          AND voltage_ub_conclusion = 0 
                          AND voltage_uc_conclusion = 0
                          AND frequency_conclusion = 0, 
                       'Соответствует', 'Не cоответствует') AS conclusion
            FROM (SELECT electric_control.*, 
                         electric_norm.frequency_min, electric_norm.frequency_max, 
                         electric_norm.voltage_UA_min, electric_norm.voltage_UA_max, 
                         electric_norm.voltage_UB_min, electric_norm.voltage_UB_max, 
                         electric_norm.voltage_UC_min, electric_norm.voltage_UC_max, 
                         IF(electric_control.voltage_ua <= electric_norm.voltage_ua_max
                                AND electric_control.voltage_ua >= electric_norm.voltage_ua_min, 
                             0, 1) AS voltage_ua_conclusion, 
                         IF(electric_control.voltage_ub <= electric_norm.voltage_ub_max
                                AND electric_control.voltage_ub >= electric_norm.voltage_ub_min, 
                             0, 1) AS voltage_ub_conclusion, 
                         IF(electric_control.voltage_uc <= electric_norm.voltage_uc_max
                                AND electric_control.voltage_uc >= electric_norm.voltage_uc_min, 
                             0, 1) AS voltage_uc_conclusion, 
                         IF(electric_control.frequency <= electric_norm.frequency_max
                                AND electric_control.frequency >= electric_norm.frequency_min, 
                             0, 1) AS frequency_conclusion
                  FROM electric_control
                  JOIN electric_norm ON electric_control.id_electric_norm = electric_norm.id) AS electric_control
            JOIN ROOMS ON ROOMS.id = electric_control.id_room
            JOIN b_user ON b_user.id = electric_control.global_assigned
            WHERE electric_control.organization_id = {$organizationId}
                AND id_room {$filters['idWhichFilter']} 
                AND date BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']}
            HAVING  {$filters['having']}
            ORDER BY {$filters['order']}
                {$filters['limit']}
            ";
        } elseif ($typeName == 'lastElectricNorm') {
            $request = "SELECT *, MAX(electric_norm.global_entry_date) AS max
                FROM electric_norm WHERE electric_norm.organization_id = {$organizationId}           
            ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'room') {
                    $request = "
                SELECT r.ID AS id,
                CONCAT(r.number,' - ', r.name) AS name
                FROM ROOMS as r
                JOIN ba_laba as lab ON r.LAB_ID = lab.ID
                WHERE lab.organization_id = {$organizationId}        
             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);
    }


    public function autoFill($dateStart, $dateEnd, $autoFrom, $autoTo, $holiday)
    {
        $organizationId = App::getOrganizationId();
        $rooms = [];
        $userModel = new User();


//        $sql = $this->DB->Query("SELECT * FROM ROOMS");
        // Пожелание ТЭ помещение только электрощитовая
        $sql = $this->DB->Query("SELECT * FROM ROOMS WHERE ID = 19 AND organization_id = {$organizationId}");
        while ($row = $sql->Fetch()) {
            $rooms[] = $row;
        }

        $start = new DateTime($dateStart);
        $end = new DateTime($dateEnd);

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
		if (!$holiday) {
			// best stored as array, so you can add more than one
			$holidays = ['2023-02-23', '2023-03-08'];
		}
        $i = 0;
        foreach ($period as $dt) {
            $curr = $dt->format('D');
            $date = $dt->format('Y-m-d');
			if (!$holiday) {
				// substract if Saturday or Sunday
				if ($curr == 'Sat' || $curr == 'Sun' || in_array($date, $holidays)) {
					continue;
				}
			}
            $vol = rand($autoFrom, $autoTo);

//			$checkUser = $userModel->checkWorkActive(115, $date);

			$user = '115';
			if (!empty($checkUser)) {
				$user = $userModel->getDeputy($checkUser['VALUE']);
			}

            foreach ($rooms as $value) {
                $voltage_UA = $vol;
                $voltage_UB = $vol;
                $voltage_UC = $vol;
                $frequency = 50;

                $minDate = strtotime($date . ' 08:00:00');
                $maxDate = strtotime($date . ' 10:00:00');
                $randDate = rand($minDate, $maxDate);
                $resultDate = date('Y-m-d H:i:s', $randDate);

                $data = $this->DB->Query("select * from electric_control where id_room = {$value['ID']} and `date` like '{$date}' and organization_id = {$organizationId}")->Fetch();



                if (empty($data)) {
                    $i++;
                    $dateInsert = [
                        'id_room' => $value['ID'],
                        'voltage_UA' => $voltage_UA,
                        'voltage_UB' => $voltage_UB,
                        'voltage_UC' => $voltage_UC,
                        'frequency' => $frequency,
                        'date' => "'{$date}'",
                        'global_assigned' => $user,
                        'global_entry_date' => "'{$resultDate}'",
                        'id_electric_norm' => 1,
                        'organization_id' => $organizationId,
                    ];

                    $this->DB->Insert('electric_control', $dateInsert);
                }
            }
        }
        return $i;
    }
}

