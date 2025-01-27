<?php

class Graduationphmetr extends Model
{
    private string $location = '/graduationphmetr/list/';

    private float $phNorm = 0.05;

    /*  key=>[$i,$k] $i = 1 то выполняется запрос в getFromSQL  SELECT * FROM  $key
                          0, то запрос пишется в getFromSQL
                     $k = 0, то select передается как есть, если
                     $k = 1, то добавляется select $keyPlusAll = [['id'=-1,'name'='Все']] иначе
                     введите свой массив для добавления к $keyPlusAll в формате смотри на выше
    */
    private array $selectInList = [
        'ph_metr' => [0, 1]
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
        if ($typeName == null) {
            $dataAdd = $data;
        } elseif ($typeName == 'addMeasurement') {
            $dataFirstAdd['ph_metr_graduation'] = $data['ph_metr_graduation'];
            $idFirstAdd = $this->insertToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $dataArrayAdd = $data['measurements'];
            foreach ($dataArrayAdd as $item) {
                $item['ph_metr_measurement']['id_ph_metr_graduation'] = $idFirstAdd;
                $idItemAdd = $this->insertToSQL($item);
                if (!$idItemAdd) {
                    return 0;
                }
            }
            return 1;
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }
        return $this->insertToSQL($dataAdd);
    }

    private function getFromSQL(string $typeName, array $filters = null): array
    {
        if ($typeName == 'getList') {
            $request = "
            SELECT ph_metr_full.*,
                DATE_FORMAT(ph_metr_full.date,
                               '%H:%i %d.%m.%Y')                 AS date_dateformat,  
                IF((m1_conclusion + m2_conclusion + m3_conclusion) = 0,'Соответствует', 'Не cоответствует')
                    AS conclusion
                FROM (SELECT ph_metr_graduation.date,
                   CONCAT(SUBSTRING(OBJECT, 1, 31), ' ', SUBSTRING(TYPE_OBORUD, 1, 21), ' Зав № ', FACTORY_NUMBER
                       )            AS name,
                   ph_metr.id,
                   ph_metr_buffer.value,                   
                   ph_metr_measurement.m1,
                   IF(ph_metr_measurement.m1 <= ph_metr_buffer.value + {$this->phNorm}
                              AND ph_metr_measurement.m1>= ph_metr_buffer.value - {$this->phNorm},
                       0, 1) AS m1_conclusion,
                   ph_metr_measurement.m2,
                   IF(ph_metr_measurement.m2 <= ph_metr_buffer.value + {$this->phNorm}
                              AND ph_metr_measurement.m2>= ph_metr_buffer.value - {$this->phNorm},
                       0, 1) AS m2_conclusion,
                   ph_metr_measurement.m3,
                   IF(ph_metr_measurement.m3 <= ph_metr_buffer.value + {$this->phNorm}
                              AND ph_metr_measurement.m3>= ph_metr_buffer.value - {$this->phNorm},
                       0, 1) AS m3_conclusion,
                   b_user.LAST_NAME AS global_assigned_name,
                   ph_metr_measurement.m1 - {$this->phNorm} as m1_1,
                   ph_metr_measurement.m1 + {$this->phNorm} as m1_2,
                   ph_metr_measurement.m2 - {$this->phNorm} as m2_1,
                   ph_metr_measurement.m2 + {$this->phNorm} as m2_2,
                   ph_metr_measurement.m3 - {$this->phNorm} as m3_1,
                   ph_metr_measurement.m3 + {$this->phNorm} as m3_2
            FROM ph_metr_graduation
                     JOIN ph_metr_measurement ON ph_metr_measurement.id_ph_metr_graduation = ph_metr_graduation.id
                     JOIN ph_metr_buffer ON ph_metr_measurement.id_ph_metr_buffer = ph_metr_buffer.id
                     JOIN ph_metr ON ph_metr_graduation.id_ph_metr = ph_metr.id
                     JOIN ba_oborud ON ba_oborud.ID = ph_metr.id_ba_oborud
                     JOIN b_user ON b_user.ID = ph_metr_graduation.global_assigned) AS ph_metr_full 
                HAVING id {$filters['idWhichFilter']} AND
                   date >= {$filters['dateStart']} AND date <= {$filters['dateEnd']}   AND
                   {$filters['having']}
                ORDER BY {$filters['order']}
                {$filters['limit']}
            ";

        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'ph_metr') {
                    $request = "
                    SELECT CONCAT(SUBSTRING(OBJECT, 1, 31), ' ', SUBSTRING(TYPE_OBORUD, 1, 21), ' Зав № ', FACTORY_NUMBER
                               ) AS name,
                           ph_metr.id
                    FROM ph_metr
                             JOIN ba_oborud ON ba_oborud.ID = ph_metr.id_ba_oborud                               
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
        $pH = [2 => 4.01, 3 => 6.86, 4 => 9.18];

        $phMetrG = [];
        $sql = $this->DB->Query("SELECT * FROM ph_metr_graduation");
        while ($row = $sql->Fetch()) {
            $phMetrG[] = $row;
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

            // substract if Saturday or Sunday
            if ($curr == 'Sat' || $curr == 'Sun' || in_array($date, $holidays)) {
                continue;
            }

            foreach ($phMetrG as $value) {
                $data = $this->DB->Query("select * from ph_metr_measurement where id_ph_metr_graduation = {$value['id']} and `global_entry_date` like '{$date}%'")->Fetch();

                if ( empty($data) ) {
                    $i++;

                    $minDate = strtotime($date . ' 08:00:00');
                    $maxDate = strtotime($date . ' 10:00:00');
                    $randDate = rand($minDate, $maxDate);
                    $resultDate = date('Y-m-d H:i:s', $randDate);

                    foreach ($pH as $idBuffer => $valMetr) {

                        $m1 = rand(($valMetr - 0.01) * 100, ($valMetr + 0.01) * 100) / 100;
                        $m2 = rand(($valMetr - 0.01) * 100, ($valMetr + 0.01) * 100) / 100;
                        $m3 = rand(($valMetr - 0.01) * 100, ($valMetr + 0.01) * 100) / 100;

                        $dateInsert = [
                            'id_ph_metr_graduation' => $value['id'],
                            'id_ph_metr_buffer' => $idBuffer,
                            'm1' => $m1,
                            'm2' => $m2,
                            'm3' => $m3,
                            'global_assigned' => 111,//$_SESSION['SESS_AUTH']['USER_ID'],
                            'global_entry_date' => "'{$resultDate}'",
                        ];

                        $this->DB->Insert('ph_metr_measurement', $dateInsert);
                    }
                }
            }
        }

        return $i;
    }
}

