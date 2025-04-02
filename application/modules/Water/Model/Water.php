<?php


class Water extends Model
{
    private string $location = '/water/list/';

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

    public function getLocation(): string
    {
        return $this->location;
    }

    public function addToSQL(array $data, string $typeName = null): int
    {
        if ($typeName == null) {
            $dataAdd = $data;
        } elseif ($typeName == 'addAnalysis') {
            $dataAdd['water'] = $data['water'];
            $dataAdd['water']['id_water_norm'] = 1;
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции addToSQL");
        }
        return $this->insertToSQL($dataAdd);

    }

    private function getFromSQL(string $typeName, array $filters = null): array
    {
        $parameters = ['uep', 'nh4', 'no3', 'so4', 'cl', 'al', 'fe', 'ca', 'pb', 'cu', 'zn'];
        if ($typeName == 'getList') {
            $parametersConclusion['ph'] = "IF(water.ph <= water_norm.ph_max
                              AND water.ph  >= water_norm.ph_min, 0, 1) AS ph_conclusion";
            $fullConclusion['ph'] = "ph_conclusion";
            foreach ($parameters as $item) {
                $parametersConclusion[$item] =
                    "IF(water.$item <= water_norm.$item,0,1) AS {$item}_conclusion";
                $fullConclusion[$item] = "{$item}_conclusion";
            }
            $parametersConclusionImplode = implode(',', $parametersConclusion);
            $fullConclusionImplode = implode(' + ', $fullConclusion);

            $request =
                "SELECT water_full.*,       
                     IF(($fullConclusionImplode) = 0,'Соответствует', 'Не cоответствует') AS conclusion
                FROM (SELECT water.*,
                            $parametersConclusionImplode,
                               b_user.LAST_NAME as global_assigned_name
                        FROM water
                                 LEFT JOIN b_user ON  water.global_assigned =b_user.ID
                        LEFT JOIN water_norm ON water.id_water_norm=water_norm.id) water_full
                HAVING date_check >= {$filters['dateStart']} AND date_check  <= {$filters['dateEnd']}
                        AND {$filters['having']}
                ORDER BY {$filters['order']}                    
                {$filters['limit']}";
        } elseif ($typeName == 'lastWaterNorm') {
            $request = "SELECT *, MAX(water_norm.global_entry_date) AS max
                FROM water_norm";
        } else throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");

        return $this->requestFromSQL($request);
    }


    public function autoFill($dateStart, $dateEnd)
    {
        $start = new DateTime($dateStart);
        $end = new DateTime($dateEnd);

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        $holidays = ['2023-02-23','2023-03-08'];

        $i = 0;
        $m = '';
        foreach($period as $dt) {
            $curr = $dt->format('D');
            $date = $dt->format('Y-m-d');
            $month =   $dt->format('m');

            // substract if Saturday or Sunday
            if ($curr == 'Sat' || $curr == 'Sun' || in_array($date, $holidays)) {
                continue;
            }

            $ph = rand(58, 62) / 10;
            $uep = rand(25, 35) / 10000;

            $minDate = strtotime($date . ' 08:00:00');
            $maxDate = strtotime($date . ' 10:00:00');
            $randDate = rand($minDate, $maxDate);
            $resultDate = date('Y-m-d H:i:s', $randDate);

            $data = $this->DB->Query("select * from water where `date_check` like '{$date}'")->Fetch();

            if ( empty($data) ) {
                $i++;
                $dateInsert = [
                    'ph' => $ph,
                    'uep' => $uep,
                    'id_water_norm' => 1,
                    'date_check' => "'{$date}'",
                    'global_assigned' => 111,//$_SESSION['SESS_AUTH']['USER_ID'],
                    'global_entry_date' => "'{$resultDate}'",
                ];

                if ( $m != $month ) {
                    $dateInsert['nh4'] = 0;
                    $dateInsert['no3'] = 0;
                    $dateInsert['so4'] = 0;
                    $dateInsert['cl'] = 0;
                    $dateInsert['al'] = 0;
                    $dateInsert['fe'] = 0;
                    $dateInsert['ca'] = 0;
                    $dateInsert['pb'] = 0;
                    $dateInsert['cu'] = 0;
                    $dateInsert['zn'] = 0;
                    $dateInsert['kmno4'] = 0;

                    $m = $month;
                }

                $this->DB->Insert('water', $dateInsert);
            }
        }


        return $i;
    }
}

