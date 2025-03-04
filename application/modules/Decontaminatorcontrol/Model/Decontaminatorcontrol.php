<?php

class Decontaminatorcontrol extends Model
{
    private string $location = '/decontaminatorcontrol/list/';

    // 1, то добавляется select $keyPlusAll = [['id'=-1,'name'='Все']]
    private array $selectsInList = [
        'decontaminator' => 1,
        'decontaminator_on' => 0
    ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;

        $result['recordsTotal'] = count($this->getListFromSQL($filtersForGetList));
        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...


        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'havingDateId'));
        //Дальше допфильтрацию не вставлять

        $result['recordsFiltered'] = count($this->getListFromSQL($filtersForGetList));

        $filtersForGetList = array_merge($filtersForGetList, $this->transformFilter($filter, 'orderLimit'));

        return array_merge($result, $this->getListFromSQL($filtersForGetList));
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getSelects(): array
    {
        $selects = [];
        $arrayAll = [["id" => "-1", "name" => "Все"]];

        foreach ($this->selectsInList as $key => $item) {
            $selects[$key] = $this->getSelectsFromSQL($key);
            if ($item == 1) {
                $selects[$key . "PlusAll"] = array_merge($arrayAll, $selects[$key]);
            }
        }

        return $selects;
    }

    public function addToSQL(array $data, string $type = null): int
    {
        $namesTable = [
            'dcon_on' => 'jn_lbf_dcon_on',
            'dcon_off' => 'jn_lbf_dcon_off'
        ];

        if ($type == null) {
            $name = array_key_first($data);
            $dataAdd = $data[$name];
        } else {
            $dataAdd = $data;
            $name = $type;
        }

        if (key_exists($name, $namesTable)) {
            $name = $namesTable[$name];
        } else {
            $name = var_export($name, true);
            $data = var_export($data, true);
            throw new InvalidArgumentException("Невалидное имя $name в массиве $data  в функции addToSQL");
        }

        return $this->insertToSQLv2($dataAdd, $name);
    }

    private function getListFromSQL(array $filters): array
    {
        $request =
            "
            SELECT rb_eq_dcon_pntr.id
                 , CONCAT(ba_oborud.TYPE_OBORUD, ' Зав №', ba_oborud.FACTORY_NUMBER) AS name
                 , rb_eq_dcon_type.obj_of_dcon
                 , ROOMS.NUMBER
                 , DATE_FORMAT(date, '%d.%m.%Y')                                     AS date_dateformat
                 , date
                 , CASE
                       WHEN jn_lbf_dcon_off.is_disinfected = 0 THEN 'Нет'
                       WHEN jn_lbf_dcon_off.is_disinfected = 1 THEN 'Да'
                       ELSE ''
                END                                                                  AS is_disinfected_full
                 , rb_eq_dcon_type.morg_type
                 , rb_eq_dcon_type.rad_type
                 , TIME_FORMAT(jn_lbf_dcon_on.time,
                               '%H:%i')                                              AS time_switch_on
                 , user_on.LAST_NAME                                                 as user_on
                 , TIME_FORMAT(jn_lbf_dcon_off.time,
                               '%H:%i')                                              AS time_switch_off
                 , user_off.LAST_NAME                                                as user_off
            FROM jn_lbf_dcon_on
                     INNER JOIN rb_eq_dcon_pntr
                                ON rb_eq_dcon_pntr.id = jn_lbf_dcon_on.rb_eq_dcon_pntr_id
                     INNER JOIN ba_oborud ON ba_oborud.ID = rb_eq_dcon_pntr.ba_oborud_id
                     INNER JOIN rb_eq_dcon_type
                                ON rb_eq_dcon_pntr.rb_eq_dcon_type_id = rb_eq_dcon_type.id
                     INNER JOIN ROOMS ON ROOMS.ID = ba_oborud.roomnumber
                     INNER JOIN b_user user_on ON user_on.ID = jn_lbf_dcon_on.g_user_id_ins
                     LEFT JOIN jn_lbf_dcon_off
                               ON jn_lbf_dcon_on.id = jn_lbf_dcon_off.jn_morg_dcon_on_id
                     LEFT JOIN b_user user_off
                               ON user_off.ID = jn_lbf_dcon_off.g_user_id_ins                        
            HAVING id {$filters['idWhichFilter']}
                  AND  date BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']}
                 AND {$filters['having']}
            ORDER BY {$filters['order']}
            {$filters['limit']}
        ";

        return $this->requestFromSQL($request);
    }

    private function getSelectsFromSQL(string $typeName): array
    {
        if ($typeName == 'decontaminator') {
            $request =
                "
                SELECT rb_eq_dcon_pntr.id
                     , CONCAT(ba_oborud.TYPE_OBORUD, ' Зав №', ba_oborud.FACTORY_NUMBER, ' (к.',
                              ROOMS.NUMBER, ')') AS name
                FROM rb_eq_dcon_pntr
                         INNER JOIN ba_oborud ON ba_oborud.ID = rb_eq_dcon_pntr.ba_oborud_id
                         INNER JOIN ROOMS ON ROOMS.ID = ba_oborud.roomnumber                
             ";
        }elseif ($typeName == 'decontaminator_on'){
            $request =
                "
            SELECT jn_lbf_dcon_on.id
                 , CONCAT(SUBSTRING(ba_oborud.TYPE_OBORUD, 1, 31), ' Зав №',
                          ba_oborud.FACTORY_NUMBER, ' (к.',
                          ROOMS.NUMBER, ') Время вкл. ', TIME_FORMAT(jn_lbf_dcon_on.time,
                                                                     '%H:%i')) AS name
            FROM rb_eq_dcon_pntr
                     INNER JOIN ba_oborud ON ba_oborud.ID = rb_eq_dcon_pntr.ba_oborud_id
                     INNER JOIN ROOMS ON ROOMS.ID = ba_oborud.roomnumber
                     INNER JOIN jn_lbf_dcon_on
                                ON rb_eq_dcon_pntr.id = jn_lbf_dcon_on.rb_eq_dcon_pntr_id
                     LEFT JOIN jn_lbf_dcon_off
                               ON jn_lbf_dcon_on.id = jn_lbf_dcon_off.jn_morg_dcon_on_id
            WHERE jn_lbf_dcon_off.id IS NULL                              
             ";

        } else {
            $typeName = var_export($typeName, true);
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getSelectsFromSQL");
        }
        return $this->requestFromSQL($request);

    }

}

