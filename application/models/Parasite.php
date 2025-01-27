<?php

class Parasite extends Model
{
    private string $location = '/parasite/list/';

    private array $selectsInList = [
        'control_objects' => 1, 'employees' => 0, 'surfaces' => 0, 'solutions_flush' => 0
        , 'sample_parasite' => 0, 'doc_parasite' => 0, 'flot_liquid' => 0, 'sample_simple' => 0
    ];

    //'microb_unit_room' => [1, 0], 'parasite_type_method' => [1, 0],
    //        'parasite_type_preparation' => [1, 0],'parasite_sampling' => [0, 0],

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;

        $filtersForGetList["order"] = "sample_number DESC , number_sample_point ASC";

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
        if ($type == null) {
            $name = array_key_first($data);
            $dataAdd = $data[$name];
        } else {
            $dataAdd = $data;
            $name = $type;
        }

        if ($type == 'addResultParasite') {
            $dataFirstAdd = $data['jn_lbf_psorg_porg_data'];
            $firstAddId = $this->insertToSQLv2($dataFirstAdd, 'jn_lbf_psorg_porg_data');
            if (!$firstAddId) {
                return 0;
            }
            $dataArrayAdd = $data['jn_lbf_psorg_porg_reg'];

            foreach ($dataArrayAdd as $key => $item) {

                $dataSecondAdd = $data['jn_lbf_psorg_dot'];
                $dataSecondAdd['number'] = $key;
                $secondAddId = $this->insertToSQLv2($dataSecondAdd, 'jn_lbf_psorg_dot');
                if (!$secondAddId) {
                    return 0;
                }
                $dataThirdAdd = $item;

                $dataThirdAdd['jn_lbf_psorg_porg_data_id'] = $firstAddId;
                $dataThirdAdd['jn_lbf_psorg_dot_id'] = $secondAddId;
                if ($dataThirdAdd['result'] == 0) {
                    $dataThirdAdd['is_conform'] = 1;
                }

                $thirdAddId = $this->insertToSQLv2($dataThirdAdd, 'jn_lbf_psorg_porg_reg');

                if (!$thirdAddId) {
                    return 0;
                }
            }
            return 1;
        } elseif ($type == 'addResultSimple') {
            if ($data["is_sorg_reg"] == 1) {
                $dotsFromSQL = $this->getWhereIDFromSQL('dots', $data["jn_lbf_psorg_smpl_id"]);
                foreach ($dotsFromSQL as $item) {
                    $dotsID[$item["number"]] = $item["id"];
                }
                $dataFirstAdd = $data['jn_lbf_psorg_sorg_data'];
                $firstAddId = $this->insertToSQLv2($dataFirstAdd, 'jn_lbf_psorg_sorg_data');
                if (!$firstAddId) {
                    return 0;
                }
                $dataArrayAdd = $data['jn_lbf_psorg_sorg_reg'];
                for ($i = 1; $i < count($dotsID) + 1; $i++) {
                    $dataSecondAdd = $dataArrayAdd[$i];
                    $dataSecondAdd['jn_lbf_psorg_sorg_data_id'] = $firstAddId;
                    $dataSecondAdd['jn_lbf_psorg_dot_id'] = $dotsID[$i];

                    if ($dataSecondAdd['result'] == 0) {
                        $dataSecondAdd['is_conform'] = 1;
                    }

                    $thirdAddId = $this->insertToSQLv2($dataSecondAdd, 'jn_lbf_psorg_sorg_reg');
                    if (!$thirdAddId) {
                        return 0;
                    }
                }
            }
            return $this->updateDotsInSQL($data["jn_lbf_psorg_smpl_id"], $data["is_sorg_reg"]);

        }
        return $this->insertToSQLv2($dataAdd, $name);
    }

    private function updateDotsInSQL(int $idSample, int $isSorgReg): int
    {
        $dataForUpdateDots = $this->getWhereIDFromSQL('porgIsConform', $idSample);

        if ($isSorgReg) {
            $sorg = $this->getWhereIDFromSQL('sorgIsConform', $idSample);
            foreach ($sorg as $item) {
                if ($item["is_conform"] == 0) {
                    $dataForUpdateDots[$item["number"] - 1]["is_conform"] = 0;
                }
            }
        }

        foreach ($dataForUpdateDots as $item) {
            $item["is_sorg_reg"] = $isSorgReg;

            $isUpdate = $this->updateSql("jn_lbf_psorg_dot", $item, $item["id"]);
            if (!$isUpdate) {
                return 0;
            }
        }

        return $this->updateSimpleInSQL($dataForUpdateDots, $idSample);

    }

    private function updateSimpleInSQL(array $data, int $idSample): int
    {
        $flag = 0;
        $dataForUpdate["is_conform"] = 0;

        foreach ($data as $item) {
            if (!$item["is_conform"]) {
                $flag++;
            }
        }
        if (!$flag) {
            $dataForUpdate["is_conform"] = 1;
        }
        return $this->updateSql("jn_lbf_psorg_smpl", $dataForUpdate, $idSample);
    }

    public function getListFromSQL(array $filters): array
    {
        $request = "
        SELECT jn_lbf_psorg_smpl.rb_lbf_cont_obj_id
             , jn_lbf_psorg_smpl.is_conform                                        AS sample_conform
             , jn_lbf_psorg_smpl.number                                            AS sample_number
             , DATE_FORMAT(jn_lbf_psorg_smpl.select_datetime,
                           '%H:%i %d.%m.%Y')                                       AS select_datetime_dateformat
             , jn_lbf_psorg_smpl.select_datetime
             , rb_lbf_cont_obj.name                                                AS name_type_control
             , pntr.name                                                           AS name_location
             , rb_lbf_psorg_flush.name                                             AS name_solution_sampling
             , IFNULL(
                IF(jn_lbf_psorg_smpl.rb_lbf_cont_obj_id = 3, jn_lbf_psorg_dot.number,
                   CONCAT(jn_lbf_psorg_dot.number, '-',
                          (IF(jn_lbf_psorg_dot.number = 1, 'Руки',
                              'Спец. одежда'))))
            , CONCAT(jn_lbf_psorg_smpl.quantity_sample_point, ' т. отбора')
            )                                                                      AS number_sample_point
             , sample_user.last_name                                               AS sample_user
             , DATE_FORMAT(jn_lbf_psorg_porg_data.datetime_start,
                           '%H:%i %d.%m.%Y')                                       AS porg_datetime_start_dateformat
             , jn_lbf_psorg_porg_data.datetime_start                               AS porg_datetime_start
             , porg_doc.name                                                       AS porg_doc_name
             , porg_doc.type                                                       AS porg_preparation_type
             , IFNULL(CONCAT(rb_lbf_psorg_flot.name, '<br>плотн. = ',
                             jn_lbf_psorg_porg_data.flot_density, ' г/л'),
                      CONCAT('Скорость = ', jn_lbf_psorg_porg_data.centrifuge_speed,
                             ' об/мин<br>Время = ',
                             jn_lbf_psorg_porg_data.centrifuge_time_min,
                             ' мин'))                                               AS porg_parameter_preparation
             , DATE_FORMAT(jn_lbf_psorg_porg_data.datetime_finish,
                           '%H:%i %d.%m.%Y')                                       AS porg_datetime_finish_dateformat
             , jn_lbf_psorg_porg_data.datetime_finish                              AS porg_datetime_finish
             , CASE
                   WHEN jn_lbf_psorg_porg_reg.result = 0 THEN 'Не обнаружено'
                   WHEN jn_lbf_psorg_porg_reg.result = 1 THEN 'Обнаружено'
                   ELSE '' END                                                     AS porg_result_full
             , porg_user.last_name                                                 AS porg_user
             , DATE_FORMAT(jn_lbf_psorg_sorg_data.datetime_start,
                           '%H:%i %d.%m.%Y')                                       AS sorg_datetime_start_dateformat
             , jn_lbf_psorg_sorg_data.datetime_start                               AS sorg_datetime_start
             , sorg_doc.name                                                       AS sorg_doc_name
             , DATE_FORMAT(jn_lbf_psorg_sorg_data.datetime_finish,
                           '%H:%i %d.%m.%Y')                                       AS sorg_datetime_finish_dateformat
             , jn_lbf_psorg_sorg_data.datetime_finish                              AS sorg_datetime_finish
             , CASE
                   WHEN jn_lbf_psorg_sorg_reg.result = 0 THEN 'Не обнаружено'
                   WHEN jn_lbf_psorg_sorg_reg.result = 1 THEN 'Обнаружено'
                   ELSE '' END                                                     AS sorg_result_full
             , sorg_user.last_name                                                 AS sorg_user
             , CASE
                   WHEN jn_lbf_psorg_dot.is_conform = 1 THEN 'Соответствует'
                   WHEN jn_lbf_psorg_dot.is_conform = 0 THEN 'Не соответствует'
                   ELSE '' END                                                     AS dot_is_conform_full
             , CASE
                   WHEN jn_lbf_psorg_smpl.is_conform  = 1 THEN 'Соответствует'
                   WHEN jn_lbf_psorg_smpl.is_conform  = 0 THEN 'Не соответствует'
                   ELSE '' END                                                     AS sample_conform_full
            FROM jn_lbf_psorg_smpl
                 INNER JOIN rb_lbf_cont_obj
                            ON rb_lbf_cont_obj.id = jn_lbf_psorg_smpl.rb_lbf_cont_obj_id
                 INNER JOIN (SELECT id_id
                                  , b_user.last_name AS name
                             FROM rb_lbf_empl_pntr
                                      INNER JOIN b_user
                                                 ON b_user.id = rb_lbf_empl_pntr.b_user_id
                             UNION
                             SELECT id_id
                                  , CONCAT('Комната № ', ROOMS.number) AS name
                             FROM rb_lbf_surf_pntr
                                      INNER JOIN ROOMS ON rb_lbf_surf_pntr.rooms_id = ROOMS.id) pntr
                            ON jn_lbf_psorg_smpl.jn_lbf___pntr_id_id = pntr.id_id
                 INNER JOIN rb_lbf_psorg_flush
                            ON jn_lbf_psorg_smpl.rb_lbf_psorg_flush_id =
                               rb_lbf_psorg_flush.id
                 LEFT JOIN jn_lbf_psorg_dot ON jn_lbf_psorg_smpl.id =
                                               jn_lbf_psorg_dot.jn_lbf_psorg_smpl_id
                 LEFT JOIN jn_lbf_psorg_porg_reg ON jn_lbf_psorg_dot.id =
                                                    jn_lbf_psorg_porg_reg.jn_lbf_psorg_dot_id
                 LEFT JOIN jn_lbf_psorg_porg_data
                           ON jn_lbf_psorg_porg_reg.jn_lbf_psorg_porg_data_id =
                              jn_lbf_psorg_porg_data.id
                 LEFT JOIN rb_lbf_psorg_doc AS porg_doc
                           ON jn_lbf_psorg_porg_data.rb_lbf_psorg_doc_id
                               = porg_doc.id
                 LEFT JOIN rb_lbf_psorg_flot
                           ON jn_lbf_psorg_porg_data.rb_lbf_psorg_flot_id
                               = rb_lbf_psorg_flot.id
                 LEFT JOIN jn_lbf_psorg_sorg_reg ON jn_lbf_psorg_dot.id =
                                                    jn_lbf_psorg_sorg_reg.jn_lbf_psorg_dot_id
                 LEFT JOIN jn_lbf_psorg_sorg_data
                           ON jn_lbf_psorg_sorg_reg.jn_lbf_psorg_sorg_data_id =
                              jn_lbf_psorg_sorg_data.id
                 LEFT JOIN rb_lbf_psorg_doc AS sorg_doc
                           ON jn_lbf_psorg_sorg_data.rb_lbf_psorg_doc_id
                               = sorg_doc.id
                 INNER JOIN b_user AS sample_user
                            ON jn_lbf_psorg_smpl.g_user_id_ins = sample_user.id
                 LEFT JOIN b_user AS porg_user
                            ON jn_lbf_psorg_porg_reg.g_user_id_ins = porg_user.id
                 LEFT JOIN b_user AS sorg_user
                            ON jn_lbf_psorg_sorg_reg.g_user_id_ins = sorg_user.id        
                HAVING jn_lbf_psorg_smpl.rb_lbf_cont_obj_id {$filters['idWhichFilter']} 
                            AND jn_lbf_psorg_smpl.select_datetime BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']}
                            AND  {$filters['having']}
                    ORDER BY {$filters['order']}
                    {$filters['limit']}  
        ";

        /*$request = "
            SELECT parasite_sampling.id_parasite_type_control
                 , parasite_sampling_is_not_conform.is_not_conform AS is_not_conform_sampling
                 , parasite_sampling.sampling_id
                 , DATE_FORMAT(parasite_sampling.datetime_finish,
                               '%H:%i %d.%m.%Y')                   AS datetime_finish_dateformat
                 , parasite_sampling.datetime_finish
                 , parasite_type_control.name                      AS name_type_control
                 , CASE
                       WHEN id_parasite_type_control = 1
                           THEN CONCAT('Комната № ', microb_unit_room.name)
                       ELSE point_sample.last_name END             AS name_location
                 , parasite_solution_sampling.name                 AS name_solution_sampling
                 , IFNULL(
                     IF(id_parasite_type_control = 1, number_sample_point,
                    CONCAT(number_sample_point, '-', (IF(number_sample_point = 1, 'Руки',
                                               'Спец. одежда'))))
                     ,CONCAT(quantity_sample_point,' т. отбора')
                     )        AS number_sample_point
                 , DATE_FORMAT(datetime_start_parasite,
                               '%H:%i %d.%m.%Y')                   AS datetime_start_parasite_dateformat
                 , datetime_start_parasite
                 , parasite_type_method_for_parasite.name          AS parasite_type_method_for_parasite_name
                 , parasite_type_preparation.name                  AS parasite_type_preparation_name
                 , parasite_parameter_preparation
                 , DATE_FORMAT(datetime_finish_parasite,
                               '%H:%i %d.%m.%Y')                   AS datetime_finish_parasite_dateformat
                 , datetime_finish_parasite
                 , CASE
                       WHEN result_parasite = 0 THEN 'Не обнаружено'
                       WHEN result_parasite = 1 THEN 'Обнаружено'
                       ELSE '' END                                 AS result_parasite_full
                 , DATE_FORMAT(datetime_start_simple,
                               '%H:%i %d.%m.%Y')                   AS datetime_start_simple_dateformat
                 , datetime_start_simple
                 , parasite_type_method_for_simple.name            AS parasite_type_method_for_simple_name
                 , DATE_FORMAT(datetime_finish_simple,
                               '%H:%i %d.%m.%Y')                   AS datetime_finish_simple_dateformat
                 , datetime_finish_simple
            
                 , result_simple
                 , parasite_type_control.name                      AS name_type_control
                 , CASE
                       WHEN result_simple = 0 THEN 'Не обнаружено'
                       WHEN result_simple = 1 THEN 'Обнаружено'
                       ELSE '' END                                 AS result_simple_full
                 , CASE
                       WHEN parasite_result.is_not_conform = 0 THEN 'Соответсвует'
                       WHEN parasite_result.is_not_conform = 1 THEN 'Не соответсвует'
                       ELSE '' END                                 AS conclusion
                 , CONCAT(IFNULL(b_user.last_name, '-'), ' ',
                          IFNULL(b_user.name, ''))                 AS global_assigned_name
            FROM parasite_sampling
                     JOIN parasite_type_control
                          ON parasite_sampling.id_parasite_type_control =
                             parasite_type_control.id
                     JOIN parasite_solution_sampling
                          ON parasite_sampling.id_parasite_solution_sampling =
                             parasite_solution_sampling.id
                     LEFT JOIN b_user AS point_sample
                               ON parasite_sampling.id_location = point_sample.id
                     LEFT JOIN microb_unit_room
                               ON parasite_sampling.id_location = microb_unit_room.id
                     LEFT JOIN parasite_result ON parasite_result.id_parasite_sampling =
                                                  parasite_sampling.id
                     LEFT JOIN parasite_type_method AS parasite_type_method_for_parasite
                               ON parasite_result.id_parasite_type_method_for_parasite =
                                  parasite_type_method_for_parasite.id
                     LEFT JOIN parasite_type_method AS parasite_type_method_for_simple
                               ON parasite_result.id_parasite_type_method_for_simple =
                                  parasite_type_method_for_simple.id
                     LEFT JOIN parasite_type_preparation
                               ON parasite_result.id_parasite_type_preparation =
                                  parasite_type_preparation.id
                     LEFT JOIN b_user ON parasite_result.global_assigned = b_user.id
                     LEFT JOIN parasite_sampling_is_not_conform
                               ON parasite_sampling_is_not_conform.id_parasite_sampling =
                                  parasite_sampling.id            
          HAVING parasite_sampling.id_parasite_type_control {$filters['idWhichFilter']} 
                            AND parasite_sampling.datetime_finish BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']}
                            AND  {$filters['having']}
                    ORDER BY {$filters['order']}
                    {$filters['limit']}    
            ";*/

        return $this->requestFromSQL($request);
    }

    private function getSelectsFromSQL(string $typeName): array
    {
        if ($typeName === 'control_objects') {
            $request =
                "
                SELECT rb_lbf_cont_obj.id
                     , rb_lbf_cont_obj.name
                FROM rb_lbf_cont_obj
                WHERE is_parasite = 1                
             ";
        } elseif ($typeName === 'employees') {
            $request =
                "
                SELECT id_id
                       ,b_user.LAST_NAME AS name
                FROM rb_lbf_empl_pntr
                INNER JOIN b_user ON b_user.ID=rb_lbf_empl_pntr.b_user_id                              
             ";

        } elseif ($typeName === 'surfaces') {
            $request =
                "
                SELECT id_id
                     , ROOMS.NUMBER AS name
                FROM rb_lbf_surf_pntr
                         INNER JOIN ROOMS ON rb_lbf_surf_pntr.ROOMS_ID = ROOMS.ID                             
             ";
        } elseif ($typeName === 'solutions_flush') {
            $request =
                "
                SELECT id        
                     , name
                FROM rb_lbf_psorg_flush                             
             ";
        } elseif ($typeName === 'sample_parasite') {
            $request =
                "
                SELECT jn_lbf_psorg_smpl.id
                     , CONCAT(jn_lbf_psorg_smpl.number, '-', name) AS name
                     , jn_lbf_psorg_smpl.number
                     , quantity_sample_point
                FROM jn_lbf_psorg_smpl
                         INNER JOIN (SELECT id_id
                                          , b_user.last_name AS name
                                     FROM rb_lbf_empl_pntr
                                              INNER JOIN b_user
                                                         ON b_user.id = rb_lbf_empl_pntr.b_user_id
                                     UNION
                                     SELECT id_id
                                          , CONCAT('Помещение № ', ROOMS.number) AS name
                                     FROM rb_lbf_surf_pntr
                                              INNER JOIN ROOMS ON rb_lbf_surf_pntr.rooms_id = ROOMS.ID)
                    AS pntrs ON pntrs.id_id = jn_lbf_psorg_smpl.jn_lbf___pntr_id_id
                         LEFT JOIN jn_lbf_psorg_dot ON jn_lbf_psorg_smpl.id =
                                                       jn_lbf_psorg_dot.jn_lbf_psorg_smpl_id
                WHERE jn_lbf_psorg_dot.id IS NULL
                GROUP BY jn_lbf_psorg_smpl.id                                        
             ";
        } elseif ($typeName === 'sample_simple') {
            $request =
                "
            SELECT jn_lbf_psorg_smpl.id
                 , CONCAT(jn_lbf_psorg_smpl.number, '-', name) AS name
                 , jn_lbf_psorg_smpl.number
                 , quantity_sample_point
            FROM jn_lbf_psorg_smpl
                     INNER JOIN (SELECT id_id
                                      , b_user.last_name AS name
                                 FROM rb_lbf_empl_pntr
                                          INNER JOIN b_user
                                                     ON b_user.id = rb_lbf_empl_pntr.b_user_id
                                 UNION
                                 SELECT id_id
                                      , CONCAT('Помещение № ', ROOMS.number) AS name
                                 FROM rb_lbf_surf_pntr
                                          INNER JOIN ROOMS ON rb_lbf_surf_pntr.rooms_id = ROOMS.id)
                AS pntrs ON pntrs.id_id = jn_lbf_psorg_smpl.jn_lbf___pntr_id_id
                     LEFT JOIN jn_lbf_psorg_dot ON jn_lbf_psorg_smpl.id =
                                                   jn_lbf_psorg_dot.jn_lbf_psorg_smpl_id
            WHERE jn_lbf_psorg_dot.id IS NOT NULL
              AND is_sorg_reg IS NULL
            GROUP BY jn_lbf_psorg_smpl.id                                                      
             ";
        } elseif ($typeName === 'doc_parasite') {
            $request =
                "
                SELECT id
                     , name
                FROM rb_lbf_psorg_doc
                WHERE is_sorg = 0                                            
             ";
        } elseif ($typeName === 'flot_liquid') {
            $request =
                "
                SELECT id
                     , name
                FROM rb_lbf_psorg_flot 
                ";
        } else {
            $typeName = var_export($typeName, true);
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getSelectsFromSQL");
        }
        return $this->requestFromSQL($request);

    }

    private function getWhereIDFromSQL(string $type, int $sampleID): array
    {
        if ($type == "dots") {
            $request = "
        SELECT id, number
        FROM jn_lbf_psorg_dot
        WHERE jn_lbf_psorg_smpl_id = $sampleID
        ";
        } elseif ($type == "porgIsConform") {
            $request = "
            SELECT jn_lbf_psorg_dot.id
                 , jn_lbf_psorg_porg_reg.is_conform
            FROM jn_lbf_psorg_porg_reg
                     INNER JOIN jn_lbf_psorg_dot
                                ON jn_lbf_psorg_porg_reg.jn_lbf_psorg_dot_id =
                                   jn_lbf_psorg_dot.id
            WHERE jn_lbf_psorg_smpl_id = $sampleID
            ORDER BY number 
            ";
        } elseif ($type == "sorgIsConform") {
            $request = "
            SELECT jn_lbf_psorg_dot.id
                 , jn_lbf_psorg_sorg_reg.is_conform
                 , number
            FROM jn_lbf_psorg_sorg_reg
                     INNER JOIN jn_lbf_psorg_dot
                                ON jn_lbf_psorg_sorg_reg.jn_lbf_psorg_dot_id =
                                   jn_lbf_psorg_dot.id
            WHERE jn_lbf_psorg_smpl_id = $sampleID
            ORDER BY number 
            ";
        }


        return $this->requestFromSQL($request);
    }

}

