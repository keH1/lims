<?php


class Microb extends Model
{
    private string $location = '/microb/list/';

    // 1 это простой запрос в getFromSQL вида SELECT * FROM  $key
    public array $selectInList
        = [
            'microb_type_microb'                    => [1, 0],
            'microb_type_control'                   => [1, 1],
            'microb_type_medium_grow'               => [1, 0],
            'microb_type_medium_transport'          => [1, 0],
            'microb_unit_box'                       => [1, 0],
            'microb_unit_room'                      => [1, 0],
            'microb_unit_filter_equipment'          => [1, 0],
            'microb_unit_thermostat'                => [1, 0],
            'microb_sampling_without_sowing'        => [0, 0],
            'microb_sampling_without_result_sowing' => [0, 0],
            'employee'                              => [0, 0],
        ];
    /*  $microbTypeControl[i],где  i = номер в БД microb_type_control
        [0] 'имя таблицы в БД',
        [1] количество точек отбора -1 берется из $_POST
        [2] среда при отборе имя таблицы или 0, если таковая отсутствует
        [3] Норма содержания микробов
            [j => k], где  j = номер в БД microb_type_microb,R
                             k = норма в КОЕ, при -1 норма не известна/определена
                             при i = 4, k  определяется в КОЕ/м3
              1 => БГКП
              2 => Синегнойная палочка
              3 => Золотистый стафилококк
              4 => ОМЧ
    */
    private array $microbTypeControl
        = [
            1 => [
                'microb_control_air_in_box', -1, 'microb_medium_grow', [
                    1 => -1, 2 => -1, 3 => 4, 4 => 3
                ]
            ],
            2 => [
                'microb_control_air_in_room', -1, 0, [
                    1 => -1, 2 => -1, 3 => 4, 4 => 500
                ]
            ],
            3 => [
                'microb_control_surface', -1, 'microb_medium_transport', [
                    1 => 0, 2 => 0, 3 => 0, 4 => 0
                ]
            ],
            4 => [
                'microb_control_filter_equipment', 1, 0, [
                    1 => 0, 2 => 0, 3 => 0, 4 => 0
                ]
            ],
            5 => [
                'microb_control_employee', 2, 'microb_medium_transport', [
                    1 => 0, 2 => 0, 3 => 0, 4 => 0
                ]
            ],
        ];

    public function getList($filter = []): array
    {
        $filtersForGetList = $this->filtersForGetListDefault;

        $filtersForGetList["order"]
            = "microb_sampling.sample_number DESC , microb_controls.number_sample_point ASC";

        $result['recordsTotal'] = count($this->getFromSQL('getList',
            $filtersForGetList));
        //всю допфильтрацию вставлять после $result['recordsTotal'] = ... до $result['recordsFiltered'] = ...

        $filtersForGetList = array_merge($filtersForGetList,
            $this->transformFilter($filter, 'havingDateId'));
        //Дальше допфильтрацию не вставлять

        $result['recordsFiltered'] = count($this->getFromSQL('getList',
            $filtersForGetList));

        $filtersForGetList = array_merge($filtersForGetList,
            $this->transformFilter($filter, 'orderLimit'));

        return array_merge($result,
            $this->getFromSQL('getList', $filtersForGetList));
    }

    public function getSelect(): array
    {
        $selects = [];
        $arrayAll = [["id" => "-1", "name" => "Все"]];
        foreach ($this->selectInList as $key => $item) {
            $selects[$key] = $this->getFromSQL($key);
            if ($item[1] == 1) {
                $selects[$key."PlusAll"] = array_merge($arrayAll,
                    $selects[$key]);
            } elseif ($item[1] != 0) {
                $selects[$key."PlusAll"] = array_merge($item[1],
                    $selects[$key]);
            }
        }
        return $selects;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function addToSQL(array $data, string $type = null): int
    {
        $namesTable = [
            'microb_sampling', 'microb_control_air_in_box',
            'microb_control_air_in_room', 'microb_control_surface',
            'microb_control_filter_equipment', 'microb_control_employee',
            'microb_medium_transport',
            'microb_medium_grow', 'microb_sowing', 'microb_result_sowing',
            'microb_sampling_is_not_conform'
        ];

        if ($type == null) {
            $name = array_key_first($data);
            if (!in_array($name, $namesTable)) {
                return 0;
            }
            $dataAdd = $data[$name];
        }
        if ($type == 'addSamplingMediumControl') {
            $idTypeControl = $data['microb_sampling']['id_microb_type_control'];
            $nameTypeControl = $this->microbTypeControl[$idTypeControl][0];
            $nameMedium = $this->microbTypeControl[$idTypeControl][2];

            $dataFirstAdd['microb_sampling'] = $data['microb_sampling'];
            if ($this->microbTypeControl[$idTypeControl][1] > 0) {
                $dataFirstAdd['microb_sampling']['quantity_sample_point']
                    = $this->microbTypeControl[$idTypeControl][1];
            }

            $quantitySamplePoint
                = $dataFirstAdd['microb_sampling']['quantity_sample_point'];

            $idFirstAdd = $this->addToSQL($dataFirstAdd);
            if (!$idFirstAdd) {
                return 0;
            }
            $dataSecondAdd[$nameMedium] = $data[$nameMedium];
            $dataThirdAdd[$nameTypeControl] = $data[$nameTypeControl];

            if ($nameMedium !== 0) {
                $dataSecondAdd[$nameMedium] = $data[$nameMedium];

                $idSecondAdd = $this->addToSQL($dataSecondAdd);
                if (!$idSecondAdd) {
                    return 0;
                }
                $dataThirdAdd[$nameTypeControl]['id_'.$nameMedium]
                    = $idSecondAdd;
            }

            $dataThirdAdd[$nameTypeControl]['id_microb_sampling'] = $idFirstAdd;

            for ($i = 1; $i < $quantitySamplePoint + 1; $i++) {
                $dataThirdAdd[$nameTypeControl]['number_sample_point'] = $i;
                $idThirdAdd = $this->addToSQL($dataThirdAdd);
                var_dump($idThirdAdd);
                if (!$idThirdAdd) {
                    return 0;
                }
            }

            return 1;
        }
        if ($type == 'addSowing') {
            $dataSecondAdd['microb_sowing'] = $data['microb_sowing'];

            if (!isset($dataSecondAdd['microb_sowing']['id_microb_medium_grow'])) {
                $dataFirstAdd['microb_medium_grow']
                    = $data['microb_medium_grow'];
                $idFirstAdd = $this->addToSQL($dataFirstAdd);
                if (!$idFirstAdd) {
                    return 0;
                }
                $dataSecondAdd['microb_sowing']['id_microb_medium_grow']
                    = $idFirstAdd;
            }

            return $this->addToSQL($dataSecondAdd);
        }

        if ($type == 'addResultSowing') {
            $isNotConformSampling = 0;
            $dataFirstAdd['microb_result_sowing']
                = $data['microb_result_sowing'];
            $idMicrobSampling
                = $data['microb_result_sowing']['id_microb_sampling'];
            $filters['id_microb_sampling'] = $idMicrobSampling;
            $isGrowPositive = $data['microb_result_sowing']['is_grow_positive'];

            $dataSecondAdd['microb_sampling_is_not_conform']['id_microb_sampling']
                = $idMicrobSampling;
            $dataSecondAdd['microb_sampling_is_not_conform']['is_not_conform']
                = 0;
            $dataArrayAdd['results'] = $data['results'];
            foreach ($dataArrayAdd['results'] as $key => $item) {
                $filters['number_sample_point'] = $key;
                $idMicrobControl = $this->getFromSQL('getIdMicrobControl',
                    $filters)[0]['id_microb_control'];
                $rowMicrobSamplingAndControl
                    = $this->getFromSQL('getRowMicrobAndControl', $filters)[0];
                $idMicrobTypeControl
                    = $rowMicrobSamplingAndControl['id_microb_type_control'];

                $dataFirstAdd['microb_result_sowing']['id_microb_control']
                    = $idMicrobControl;
                $dataFirstAdd['microb_result_sowing']['result']
                    = $item['result'];
                $dataFirstAdd['microb_result_sowing']['is_not_conform'] = 0;

                if ($idMicrobTypeControl == 2) {
                    if ($item['result'] == -1) {
                        $resultForConform = $item['result'];
                    } else {
                        $resultForConform = $item['result']
                            / $rowMicrobSamplingAndControl['volume_air_litre']
                            * 1000; //в КОЕ/м3
                    }
                } else {
                    $resultForConform = $item['result'];
                }

                $normMicrobe
                    = $this->microbTypeControl[$idMicrobTypeControl][3][$rowMicrobSamplingAndControl['id_microb_type_microb']];

                if ($normMicrobe == -1) {
                    $dataFirstAdd['microb_result_sowing']['is_not_conform']
                        = -1;
                    $dataSecondAdd['microb_sampling_is_not_conform']['is_not_conform']
                        = -1;
                } elseif ($resultForConform > $normMicrobe
                    || $resultForConform == -1
                    || $isGrowPositive == 1
                ) {
                    $dataFirstAdd['microb_result_sowing']['is_not_conform'] = 1;
                    if ($dataSecondAdd['microb_sampling_is_not_conform']['is_not_conform']
                        != -1
                    ) {
                        $dataSecondAdd['microb_sampling_is_not_conform']['is_not_conform']
                            = 1;
                    }
                }

                $idAdd = $this->addToSQL($dataFirstAdd);
                if (!$idAdd) {
                    return 0;
                }
            }
            return $this->addToSQL($dataSecondAdd);
        }

        return $this->insertToSQL($dataAdd, $name);
    }

    public
    function getFromSQL(
        string $typeName,
        array $filters = null
    ): array {
        if ($typeName == 'getList') {
            $request = "SELECT microb_sampling.sample_number,
                        microb_sampling.id,
                        microb_sampling.id_microb_type_control,
                        microb_sampling.id_microb_type_microb,
                        DATE_FORMAT(microb_sampling.datetime_finish,'%H:%i %d.%m.%Y') AS datetime_finish_dateformat,
                        microb_sampling.datetime_finish,
                        microb_type_microb.name AS name_type_microb,
                        microb_type_control.name AS name_type_control,
                        microb_controls.name AS name_control,
                        microb_controls.property_select,
                        microb_controls.number_sample_point,
                        DATE_FORMAT(microb_sowing.datetime_start,'%H:%i %d.%m.%Y') AS datetime_start_dateformat,
                        microb_sowing.datetime_start,
                        CONCAT(microb_sowing.temperature_inсubation,' ± ',microb_sowing.temperature_inсubation_range ) AS temperature_inсubation_full,                    
                        CONCAT(microb_sowing.time_inсubation_hour,' ± ',microb_sowing.time_inсubation_hour_range ) AS time_inсubation_hour_full,                        
                        microb_medium_grow.number_batch,
                        microb_type_medium_grow.name AS medium_grow_name,
                        microb_unit_thermostat.name AS thermostat_name,
                        DATE_FORMAT(microb_result_sowing.datetime_finish,'%H:%i %d.%m.%Y') AS datetime_finish_result_dateformat,
                        microb_result_sowing.datetime_finish AS datetime_finish_result,
                        microb_controls.exposition_time,
                        microb_controls.medium,
                        microb_controls.volume_air,
                        microb_controls.place_selection,
                        CONCAT (IFNULL(b_user.LAST_NAME,'-'),' ',IFNULL(b_user.NAME,'')) as global_assigned_name,
                        CONCAT(
                        CASE
                           WHEN microb_result_sowing.result = -1 THEN 'сплошной рост'
                           ELSE
                               CASE
                                   WHEN microb_sampling.id_microb_type_control = 2
                                       THEN CONCAT(ROUND(microb_result_sowing.result /
                                                         microb_controls.volume_air_litre *
                                                         1000, 0), ' КОЕ/м3')
                                   ELSE CONCAT(microb_result_sowing.result, ' KOE<br>')
                                   END
                           END)AS result_full,
                        CASE    WHEN microb_result_sowing.is_not_conform = 0 THEN 'Соответствует'
                                WHEN microb_result_sowing.is_not_conform = 1 THEN 'Не соответствует'
                                WHEN microb_result_sowing.is_not_conform = -1 THEN 'Нет данных о требованиях'
                                ELSE '' END AS conclusion,
                        microb_sampling_is_not_conform.is_not_conform AS conform_sampling,
                        CASE    WHEN  microb_result_sowing.is_grow_positive = 0 THEN 'Рост отс.'
                                WHEN microb_result_sowing.is_not_conform = 1 THEN 'Есть рост'
                                ELSE '' END AS is_grow_positive_full
                        FROM microb_sampling
                        LEFT JOIN microb_type_microb ON microb_sampling.id_microb_type_microb=microb_type_microb.id
                        LEFT JOIN microb_type_control ON microb_sampling.id_microb_type_control = microb_type_control.id
                        LEFT JOIN (
                                    SELECT id_microb_sampling,
       number_sample_point,
       CONCAT('№ бокса ', microb_unit_box.name)                                     AS name,
       CONCAT('Время ', exposition_time_min, ' мин<br>Среда: ', microb_type_medium_grow.name, ' № ',
              microb_medium_grow.number_batch)                                      AS property_select,
       CONCAT(id_microb_sampling, '-', microb_control_air_in_box.id)                AS id_id,
       CONCAT(exposition_time_min,' мин') AS exposition_time,
       CONCAT(microb_type_medium_grow.name, ' № ', microb_medium_grow.number_batch) AS medium,
       NULL                                                                         AS volume_air,
       NULL                                                                         AS place_selection,
       NULL                                     AS volume_air_litre
FROM microb_control_air_in_box
         LEFT JOIN microb_unit_box ON microb_control_air_in_box.id_microb_unit_box = microb_unit_box.id
         LEFT JOIN microb_medium_grow ON microb_control_air_in_box.id_microb_medium_grow = microb_medium_grow.id
         LEFT JOIN microb_type_medium_grow ON microb_medium_grow.id_microb_type_medium_grow = microb_type_medium_grow.id
UNION
SELECT id_microb_sampling,
       number_sample_point,
       CONCAT('№ помещения ', microb_unit_room.name)                  AS name,
       CONCAT('Объем воздуха = ', volume_air_litre, ' л')             AS property_select,
       CONCAT(id_microb_sampling, '-', microb_control_air_in_room.id) AS id_id,
       NULL,
       NULL,
       CONCAT(volume_air_litre, ' л')                                 AS volume_air,
       NULL,
       volume_air_litre
FROM microb_control_air_in_room
         LEFT JOIN microb_unit_room ON microb_control_air_in_room.id_microb_unit_room = microb_unit_room.id
UNION
SELECT id_microb_sampling,
       number_sample_point,
       b_user.LAST_NAME                                                                          AS name,
       CONCAT(IF(number_sample_point = 1, 'руки', 'спец.одежда'), '<br>Трансп. среда: ',
              microb_type_medium_transport.name, '<br>№ ', microb_medium_transport.number_batch) AS property_select,
       CONCAT(id_microb_sampling, '-', microb_control_employee.id)                               AS id_id,
       NULL,
       CONCAT(microb_type_medium_transport.name, '<br>№ ', microb_medium_transport.number_batch) AS medium,
       NULL,
       IF(number_sample_point = 1, 'руки', 'спец.одежда')                                        AS place_selection,
       NULL
FROM microb_control_employee
         LEFT JOIN b_user ON microb_control_employee.id_employee = b_user.ID
         LEFT JOIN microb_medium_transport ON
        microb_control_employee.id_microb_medium_transport = microb_medium_transport.id
         LEFT JOIN microb_type_medium_transport
              ON microb_medium_transport.id_microb_type_medium_transport = microb_type_medium_transport.id
UNION
SELECT id_microb_sampling,
       number_sample_point,
       CONCAT('Фильтровальная установка №', microb_unit_filter_equipment.name) AS name,
       CONCAT('№ фильтра ', number_batch_filter, '<br>Партия дист.воды № ',
              number_batch_water)                                              AS property_select,
       CONCAT(id_microb_sampling, '-', microb_control_filter_equipment.id)     AS id_id,
       NULL,
       NULL,
       NULL,
       NULL,
       NULL
FROM microb_control_filter_equipment
         LEFT JOIN microb_unit_filter_equipment
              ON microb_control_filter_equipment.id_microb_unit_filter_equipment = microb_unit_filter_equipment.id
UNION
SELECT id_microb_sampling,
       number_sample_point,
       CONCAT('№ помещения ', microb_unit_room.name)              AS name,
       CONCAT('Трансп. среда: ', microb_type_medium_transport.name, '<br>№ ',
              microb_medium_transport.number_batch)               AS property_select,
       CONCAT(id_microb_sampling, '-', microb_control_surface.id) as id_id,
       NULL,
       CONCAT(microb_type_medium_transport.name, '<br>№ ',
              microb_medium_transport.number_batch)               AS medium,
       NULL,
       NULL,
       NULL
FROM microb_control_surface
         LEFT JOIN microb_unit_room ON microb_control_surface.id_microb_unit_room = microb_unit_room.id
         LEFT JOIN microb_medium_transport ON
        microb_control_surface.id_microb_medium_transport = microb_medium_transport.id
         LEFT JOIN microb_type_medium_transport
              ON microb_medium_transport.id_microb_type_medium_transport = microb_type_medium_transport.id        
                        ) AS microb_controls ON microb_controls.id_microb_sampling=microb_sampling.id
                        LEFT JOIN microb_sowing ON microb_sowing.id_microb_sampling=microb_sampling.id
                        LEFT JOIN microb_medium_grow ON microb_sowing.id_microb_medium_grow=microb_medium_grow.id
                        LEFT JOIN microb_type_medium_grow ON microb_medium_grow.id_microb_type_medium_grow =microb_type_medium_grow.id
                        LEFT JOIN microb_unit_thermostat ON microb_sowing.id_microb_unit_thermostat=microb_unit_thermostat.id
                        LEFT JOIN (
                            SELECT datetime_finish,result,global_assigned,is_not_conform,is_grow_positive,
                                   CONCAT (id_microb_sampling,'-',id_microb_control) as id_id
                            FROM microb_result_sowing                       
                        ) microb_result_sowing ON microb_result_sowing.id_id=microb_controls.id_id                        
                        LEFT JOIN b_user ON  microb_result_sowing.global_assigned = b_user.ID
                        LEFT JOIN microb_sampling_is_not_conform ON microb_sampling_is_not_conform.id_microb_sampling=microb_sampling.id
          HAVING microb_sampling.id_microb_type_control {$filters['idWhichFilter']} 
                AND microb_sampling.datetime_finish BETWEEN {$filters['dateStart']} AND {$filters['dateEnd']}
               AND  {$filters['having']}
                    ORDER BY {$filters['order']}
                    {$filters['limit']}          
        ";
        } elseif ($typeName == 'getIdMicrobControl') {
            $microbControlsForUnion = [];

            foreach ($this->microbTypeControl as $item) {
                $microbControlsForUnion[] = "SELECT  id AS id_microb_control,
                                            id_microb_sampling, number_sample_point
                                            FROM $item[0]";
            }
            $microbControlsAfterUnion = implode(' UNION ',
                $microbControlsForUnion);

            $request = "
                SELECT id_microb_control FROM ($microbControlsAfterUnion) AS microb_controls
                WHERE  id_microb_sampling = {$filters['id_microb_sampling']}
                AND number_sample_point={$filters['number_sample_point']}
             ";
        } elseif ($typeName == 'getRowMicrobAndControl') {
            $request = "
                SELECT * FROM microb_sampling 
               LEFT JOIN microb_control_air_in_room ON microb_control_air_in_room.id_microb_sampling =microb_sampling.id
                WHERE  microb_sampling.id = {$filters['id_microb_sampling']}                
             ";
        } elseif (array_key_exists($typeName, $this->selectInList)) {
            if ($this->selectInList[$typeName][0] == 1) {
                $request = "
                SELECT * FROM $typeName
             ";
            } elseif ($this->selectInList[$typeName][0] == 0) {
                if ($typeName == 'employee') {
                    $request = "
                SELECT user_id   AS id
                     , last_name AS name
                FROM b_user_index
                WHERE uf_department_name = 'Лаборатория биологических факторов'              
             ";
                } elseif ($typeName == 'microb_sampling_without_sowing') {
                    $request = "
                SELECT microb_sampling.id,microb_sampling.id_microb_type_control,
                CONCAT (microb_sampling.sample_number,'-',microb_type_microb.name,' ',microb_type_control.name,' ',microb_controls.name ) AS name, 
                microb_medium_grow.id_microb_type_medium_grow,
                microb_medium_grow.number_batch,microb_control_air_in_box.id_microb_medium_grow
                FROM microb_sampling
                LEFT JOIN microb_type_microb ON microb_sampling.id_microb_type_microb=microb_type_microb.id
                LEFT JOIN microb_type_control ON microb_sampling.id_microb_type_control = microb_type_control.id
                LEFT JOIN (
                SELECT id_microb_sampling,
                CONCAT ('№ бокса ',microb_unit_box.name) AS name
                FROM microb_control_air_in_box
                LEFT JOIN microb_unit_box ON microb_control_air_in_box.id_microb_unit_box= microb_unit_box.id
                UNION
                SELECT id_microb_sampling
                ,CONCAT ('№ помещения ',microb_unit_room.name) AS name
                FROM microb_control_air_in_room
                LEFT JOIN microb_unit_room ON microb_control_air_in_room.id_microb_unit_room=microb_unit_room.id
                UNION
                SELECT id_microb_sampling
                , b_user.last_name AS name FROM 
                microb_control_employee
                LEFT JOIN b_user ON microb_control_employee.id_employee=b_user.id
                UNION
                SELECT id_microb_sampling,
                CONCAT ('Фильтровальная установка №', microb_unit_filter_equipment.name) AS name
                FROM 	microb_control_filter_equipment
                LEFT JOIN microb_unit_filter_equipment ON  microb_control_filter_equipment.id_microb_unit_filter_equipment=microb_unit_filter_equipment.id
                UNION
                SELECT id_microb_sampling
                ,CONCAT ('№ помещения ',microb_unit_room.name) AS name
                FROM microb_control_surface
                LEFT JOIN microb_unit_room ON microb_control_surface.id_microb_unit_room=microb_unit_room.id
                GROUP BY id_microb_sampling
                ) AS microb_controls ON microb_controls.id_microb_sampling=microb_sampling.id
                LEFT JOIN microb_sowing ON microb_sowing.id_microb_sampling=microb_sampling.id
                LEFT JOIN microb_control_air_in_box  ON microb_control_air_in_box.id_microb_sampling=microb_sampling.id
                LEFT JOIN microb_medium_grow ON microb_control_air_in_box.id_microb_medium_grow=microb_medium_grow.id
                WHERE microb_sowing.id IS NULL
                GROUP  BY microb_sampling.id                
             ";
                } elseif ($typeName
                    == 'microb_sampling_without_result_sowing'
                ) {
                    $request = "
                SELECT microb_sampling.id,microb_sampling.id_microb_type_control,microb_sampling.sample_number,
                CONCAT (microb_sampling.sample_number,'-',microb_type_microb.name,' ',microb_type_control.name,' ',microb_controls.name ) AS name, 
                microb_sampling.quantity_sample_point
                FROM microb_sampling
                LEFT JOIN microb_type_microb ON microb_sampling.id_microb_type_microb=microb_type_microb.id
                LEFT JOIN microb_type_control ON microb_sampling.id_microb_type_control = microb_type_control.id
                LEFT JOIN (
                SELECT id_microb_sampling,
                CONCAT ('№ бокса ',microb_unit_box.name) AS name
                FROM microb_control_air_in_box
                LEFT JOIN microb_unit_box ON microb_control_air_in_box.id_microb_unit_box= microb_unit_box.id
                UNION
                SELECT id_microb_sampling
                ,CONCAT ('№ помещения ',microb_unit_room.name) AS name
                FROM microb_control_air_in_room
                LEFT JOIN microb_unit_room ON microb_control_air_in_room.id_microb_unit_room=microb_unit_room.id
                UNION
                SELECT id_microb_sampling
                , b_user.last_name AS name FROM 
                microb_control_employee
                LEFT JOIN b_user ON microb_control_employee.id_employee=b_user.id
                UNION
                SELECT id_microb_sampling,
                CONCAT ('Фильтровальная установка №', microb_unit_filter_equipment.name) AS name
                FROM 	microb_control_filter_equipment
                LEFT JOIN microb_unit_filter_equipment ON  microb_control_filter_equipment.id_microb_unit_filter_equipment=microb_unit_filter_equipment.id
                UNION
                SELECT id_microb_sampling
                ,CONCAT ('№ помещения ',microb_unit_room.name) AS name
                FROM microb_control_surface
                LEFT JOIN microb_unit_room ON microb_control_surface.id_microb_unit_room=microb_unit_room.id
                GROUP BY id_microb_sampling
                ) AS microb_controls ON microb_controls.id_microb_sampling=microb_sampling.id
                LEFT JOIN microb_sowing ON microb_sowing.id_microb_sampling=microb_sampling.id
                LEFT JOIN microb_result_sowing ON microb_result_sowing .id_microb_sampling=microb_sampling.id
                WHERE microb_sowing.id IS NOT NULL AND microb_result_sowing.id IS NULL                
             ";
                }
            } else {
                throw new InvalidArgumentException("Неизвестный аргумент $typeName в константе selectInList");
            }
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeName в функции getFromSQL");
        }

        return $this->requestFromSQL($request);
    }

    public function addSeedingResult(int $id)
    {
        $data = $this->DB->Query(
            "SELECT `quantity_sample_point`
             FROM microb_sampling
             WHERE `id` = '".$id."'
        ")->Fetch();

        return $data;
    }

    public function getSampleData(string $sampleNumber)
    {
        $dataSample = $this->getDataSample($sampleNumber);
        $idMicrobTypeControl = $dataSample["id_microb_type_control"];

        $tableName = $this->microbTypeControl[$idMicrobTypeControl][0];
        $idMicrobSampling = (int) $dataSample["id"];

        $dataMicrobControl = $this->getDataMicrobControl($tableName,
            $idMicrobSampling);

        $deleteFromData = array_flip([
            'id', 'id_microb_sampling', 'number_sample_point',
            'global_entry_date', 'global_assigned',
            'id_microb_medium_transport', 'id_microb_medium_grow'
        ]);
        $dataMicrobControlAfterDelete = array_diff_key($dataMicrobControl,
            $deleteFromData);

        $tableNameMicrobMedium
            = $this->microbTypeControl[$idMicrobTypeControl][2];
        $dataNameMicrobMediumAfterDelete = [];
        if ($tableNameMicrobMedium) {
            $idMicrobMedium = (int) $dataMicrobControl["id_"
            .$tableNameMicrobMedium];
            $dataNameMicrobMedium
                = $this->getDataMicrobMediuml($tableNameMicrobMedium,
                $idMicrobMedium);
            $dataNameMicrobMediumAfterDelete = [];
            $dataNameMicrobMediumAfterDelete
                = array_diff_key($dataNameMicrobMedium,
                $deleteFromData);
        }
        $dataResponse = [
            'datetime_finish'        => $dataSample['datetime_finish'],
            'id_microb_type_control' => $dataSample['id_microb_type_control'],
            'quantity_sample_point'  => $dataSample['quantity_sample_point'],
            'sample_number'          => $sampleNumber,
        ];
        return array_merge($dataResponse, $dataMicrobControlAfterDelete,
            $dataNameMicrobMediumAfterDelete);
    }

    private function getDataSample(string $id): array
    {
        $request = "
        SELECT * FROM microb_sampling
        WHERE sample_number = $id
        ";
        return $this->requestFromSQL($request)[0];
    }

    private function getDataMicrobControl(
        string $tableName,
        int $idMicrobSampling
    ): array {
        $request = "
        SELECT * FROM {$tableName}
        WHERE id_microb_sampling = {$idMicrobSampling}
        ";
        return $this->requestFromSQL($request)[0];
    }

    private function getDataMicrobMediuml(
        string $tableName,
        int $idMicrobMedium
    ): array {
        $request = "
        SELECT * FROM {$tableName}
        WHERE id = {$idMicrobMedium}
        ";
        return $this->requestFromSQL($request)[0];
    }
}

