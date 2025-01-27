<?php

class Fridgecontrol extends Model
{
    private string $location = '/fridgecontrol/list/';

    /*  key=>[$i,$k] $i = 1 то выполняется запрос в getFromSQL  SELECT * FROM  $key
                         0, то запрос пишется в getFromSQL
                    $k = 0, то select передается как есть, если
                    $k = 1, то добавляется select $keyPlusAll = [['id'=-1,'name'='Все']] иначе
                    введите свой массив для добавления к $keyPlusAll в формате смотри на выше
   */
    private array $selectInList = [
        'fridge' => [0, 1]
    ];


    public function addToSQL(array $data, string $typeName = null): int
    {
        if ($typeName == null) {
            $dataAdd = $data;
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }

        return $this->insertToSQL($dataAdd);

    }

    public
    function getList($filter = []): array

    {
        $filtersForGetList = $this->filtersForGetListDefault;
        $filtersForGetList['order'] = "date_time DESC";
        $result['recordsTotal'] = count($this->getFromSQL('getList', $filtersForGetList));
        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'havingDateId'));
        //Дальше допфильтрацию не вставлять

        $result['recordsFiltered'] = count($this->getFromSQL('getList', $filtersForGetList));

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'orderLimit'));

        return array_merge($result, $this->getFromSQL('getList', $filtersForGetList));
    }

    public
    function getSelect(): array
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

    public
    function getLocation(): string
    {
        return $this->location;
    }

    public
    function getFromSQL(string $typeName, array $filters = null): array
    {

        if ($typeName == 'getList') {
            $request = "
            SELECT fridge.id
                 , fridge_control.date_time
                 , DATE_FORMAT(fridge_control.date_time,
                               '%H:%i %d.%m.%Y')                 AS date_time_dateformat
                 , fridge_control.temperature
                 , CONCAT(unit_fridge.name, ' ', object, ' ', type_oborud, ' Зав №',
                          factory_number)                        AS name
                 , CONCAT(first_range, ' — ', last_range, ' °C') AS range_full
                 , CONCAT(IFNULL(b_user.last_name, '-'), ' ',
                          IFNULL(b_user.name, ''))               AS global_assigned_name
                 , IF(fridge_control.temperature <= last_range AND
                      fridge_control.temperature >= first_range,
                      'Соответствует',
                      'Не cоответствует')                        AS conclusion
            FROM fridge_control
                     JOIN fridge ON fridge_control.id_fridge = fridge.id
                     JOIN unit_fridge ON fridge.id_unit_fridge = unit_fridge.id
                     JOIN ba_oborud ON fridge.id_ba_oborud = ba_oborud.id
                     LEFT JOIN b_user ON fridge_control.global_assigned = b_user.id                
            HAVING fridge.id {$filters['idWhichFilter']}
                AND DATE(date_time) BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']} 
                AND {$filters['having']}
                    ORDER BY {$filters['order']}
                {$filters['limit']}
             ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'fridge') {
                    $request = "
                SELECT fridge.id,
                CONCAT(unit_fridge.name,' ',OBJECT,' ',TYPE_OBORUD,' Зав №',FACTORY_NUMBER) AS name
                 FROM fridge
                JOIN unit_fridge ON fridge.id_unit_fridge =unit_fridge.id
                JOIN ba_oborud ON fridge.id_ba_oborud=ba_oborud.ID             
             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент {$this->selectInList[$typeName][0]} в константе selectInList");
            }
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);
    }


    public function autoFill($dateStart, $dateEnd)
    {
        $fridge = [];

        $userModel = new User();

        $sql = $this->DB->Query("SELECT * FROM fridge");
        while ($row = $sql->Fetch()) {
            $fridge[] = $row;
        }

        $start = new DateTime($dateStart);
        $end = new DateTime($dateEnd);

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        $holidays = ['2023-02-23','2023-03-08'];

        $i = 0;
        foreach($period as $dt) {
			$curr = $dt->format('D');
			$date = $dt->format('Y-m-d');
			//костыль
			if (strtotime($date) < strtotime('2023-01-23') || strtotime($date) > strtotime('2023-03-31')) {
				// substract if Saturday or Sunday
				if ($curr == 'Sat' || $curr == 'Sun' || in_array($date, $holidays)) {
					continue;
				}
			}

            foreach ($fridge as $value) {

                if (!empty($checkUser)) {
                    $user = $userModel->getDeputy($checkUser['VALUE']);
                }

                $aver = ($value['first_range'] + $value['last_range']) / 2;
                $min = ($aver - $aver * 0.2) * 10;
                $max = ($aver + $aver * 0.2) * 10;
                $temperature = rand($min, $max) / 10;

                $minDate = strtotime($date . ' 08:00:00');
                $maxDate = strtotime($date . ' 10:00:00');
                $randDate = rand($minDate, $maxDate);
                $resultDate = date('Y-m-d H:i:s', $randDate);

                $data = $this->DB->Query("select * from fridge_control where id_fridge = {$value['id']} and `date_time` like '{$date}%'")->Fetch();
				$dataAssigned = $this->DB->Query("select bo.ID_ASSIGN1, bo.ID_ASSIGN2 from ba_oborud as bo, fridge as f where bo.ID = f.id_ba_oborud and f.id = {$value['id']}")->Fetch();

				if (!empty($dataAssigned['ID_ASSIGN1'])) {
					$userActive = $userModel->checkWorkActive($dataAssigned['ID_ASSIGN1'], $date);

					$userId = $dataAssigned['ID_ASSIGN1'];
					if (!empty($userActive)) {
						$userId = $dataAssigned['ID_ASSIGN2'];
					}
				} else {
					$userId = 111;
				}

                if ( empty($data) ) {
                    $i++;
                    $dateInsert = [
                        'id_fridge' => $value['id'],
                        'temperature' => $temperature,
                        'global_assigned' => $userId,//$_SESSION['SESS_AUTH']['USER_ID'],
                        'date_time' => "'{$resultDate}'",
                        'global_entry_date' => "'{$resultDate}'",
                    ];

                    $this->DB->Insert('fridge_control', $dateInsert);
                }
            }
        }

        return $i;
    }
}
