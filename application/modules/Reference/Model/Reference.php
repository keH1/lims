<?php

/**
 * Модель для работы со справочниками
 * Class Reference
 */
class Reference extends Model
{
    /**
     * @param $filter
     * @return array
     */
    public function getDataToJournalMeasuredProperties($filter)
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                if ( isset($filter['search']['name']) ) {
                    $where .=
                        "`name` like '%{$filter['search']['name']}%' and ";
                }
                // везде
                if ( isset($filter['search']['everywhere']) ) {
                    $where .=
                        "";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'fsa_id':
                        $order['by'] = 'fsa_id';
                        break;
                    case 'name':
                        $order['by'] = 'name';
                        break;
                    case 'is_used':
                        $order['by'] = 'is_used';
                        break;
                }
            }

            // работа с пагинацией
            if ( isset($filter['paginate']) ) {
                $offset = 0;
                // количество строк на страницу
                if ( isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0 ) {
                    $length = $filter['paginate']['length'];

                    if ( isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0 ) {
                        $offset = $filter['paginate']['start'];
                    }
                    $limit = "LIMIT {$offset}, {$length}";
                }
            }
        }
        $where .= "organization_id = {$organizationId}";


        $data = $this->DB->Query(
            "SELECT `id`, `fsa_id`, `name`, `is_used`, `is_actual`
                    FROM ulab_measured_properties
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_measured_properties
                    WHERE organization_id = {$organizationId}"
        )->Fetch();
        $dataFiltered = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_measured_properties
                    WHERE {$where}"
        )->Fetch();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal['val'];
        $result['recordsFiltered'] = $dataFiltered['val'];

        return $result;
    }


    /**
     * @param $filter
     * @return array
     */
    public function getDataToJournalUnits($filter)
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                if ( isset($filter['search']['name']) ) {
                    $where .= "`name` like '%{$filter['search']['name']}%' and ";
                }
                if ( isset($filter['search']['unit_rus']) ) {
                    $where .= "`unit_rus` like '%{$filter['search']['unit_rus']}%' and ";
                }
                // везде
                if ( isset($filter['search']['everywhere']) ) {
                    $where .=
                        "";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'fsa_id':
                        $order['by'] = 'fsa_id';
                        break;
                    case 'name':
                        $order['by'] = 'name';
                        break;
                    case 'unit_rus':
                        $order['by'] = 'unit_rus';
                        break;
                    case 'is_used':
                        $order['by'] = 'is_used';
                        break;
                }
            }

            // работа с пагинацией
            if ( isset($filter['paginate']) ) {
                $offset = 0;
                // количество строк на страницу
                if ( isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0 ) {
                    $length = $filter['paginate']['length'];

                    if ( isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0 ) {
                        $offset = $filter['paginate']['start'];
                    }
                    $limit = "LIMIT {$offset}, {$length}";
                }
            }
        }
        $where .= "organization_id = {$organizationId} ";


        $data = $this->DB->Query(
            "SELECT `id`, `fsa_id`, `unit_rus`, `name`, `is_used`, `is_actual`
                    FROM ulab_dimension
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_dimension
                    WHERE organization_id = {$organizationId}"
        )->Fetch();
        $dataFiltered = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_dimension
                    WHERE {$where}"
        )->Fetch();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal['val'];
        $result['recordsFiltered'] = $dataFiltered['val'];

        return $result;
    }


    /**
     * @param $id
     */
    public function changeUsedMeasuredProperties($id)
    {
        $organizationId = App::getOrganizationId();
        $this->DB->Query("update `ulab_measured_properties` set `is_used` = ! `is_used` where id = {$id} and organization_id = {$organizationId}");
    }


    /**
     * @param $id
     */
    public function changeUsedUnits($id)
    {
        $organizationId = App::getOrganizationId();
        $this->DB->Query("update `ulab_dimension` set `is_used` = ! `is_used` where id = {$id} and organization_id = {$organizationId}");
    }


    /**
     * @return array
     */
    public function syncMeasuredPropertiesFsa()
    {
        $organizationId = App::getOrganizationId();
        $array = array(
            'pageSize'   => 100000,
        );

        $ch = curl_init('http://5.143.238.171:8080/rpi/measuredProperties?' . http_build_query($array));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $json = curl_exec($ch);

        if (curl_errno($ch)) {
            $msg = curl_error($ch);
            curl_close($ch);

            return [
                'success' => false,
                'error' => "Ошибка: {$msg}"
            ];
        }

        curl_close($ch);

        $result = json_decode($json, true);

        $this->DB->Update('ulab_measured_properties', ['is_actual' => 0]);

        $countUpdate = 0;
        $countInsert = 0;
        foreach ($result['items'] as $item) {
            $name = $this->quoteStr($this->DB->ForSql(trim($item['name'])));
            $isActual = $item['actual']? 1 : 0;
            $updateAffected = $this->DB->Update('ulab_measured_properties', ['is_actual' => $isActual, 'name' => $name], "where fsa_id = {$item['id']}");

            if ( !$updateAffected ) {
                $countInsert++;
                $this->DB->Insert('ulab_measured_properties', ['fsa_id' => $item['id'], 'name' => $name, 'is_actual' => $isActual]);
            } else {
                $countUpdate++;
            }
        }

        $nonActualTotal = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_measured_properties
                    WHERE is_actual = 0 and organization_id = {$organizationId}"
        )->Fetch();

        return ['success' => true, 'msg' => "Синхронизация прошла успешно. Обновлено: {$countUpdate}. Добавлено: {$countInsert}. Неактуально: {$nonActualTotal['val']}"];
    }


    /**
     * @return array
     */
    public function syncMeasuredProperties()
    {
        try {
            $organizationId = App::getOrganizationId();
            $ch = curl_init('https://ulab.niistrom.pro/API/getMeasuredProperties.php');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $json = curl_exec($ch);

            if (curl_errno($ch)) {
                $msg = curl_error($ch);
                curl_close($ch);

                return [
                    'success' => false,
                    'error' => "Ошибка: {$msg}"
                ];
            }

            curl_close($ch);

            $result = json_decode($json, true);

            $this->DB->Update('ulab_measured_properties', ['is_actual' => 0], "where fsa_id is not null");

            $countUpdate = 0;
            $countInsert = 0;
            foreach ($result['data'] as $item) {
                if ( empty($item['fsa_id']) ) {
                    continue;
                }
                $name = $this->quoteStr($this->DB->ForSql(trim($item['name'])));
                $isActual = $item['is_actual']? 1 : 0;
                $updateAffected = $this->DB->Update('ulab_measured_properties', ['is_actual' => $isActual, 'name' => $name,'organization_id' => $organizationId], "where fsa_id = {$item['fsa_id']}");

                if ( !$updateAffected ) {
                    $countInsert++;
                    $this->DB->Insert('ulab_measured_properties', ['fsa_id' => $item['fsa_id'], 'name' => $name, 'is_actual' => $isActual,'organization_id' => $organizationId]);
                } else {
                    $countUpdate++;
                }
            }

            $nonActualTotal = $this->DB->Query(
                "SELECT count(*) val
                        FROM ulab_measured_properties
                        WHERE is_actual = 0 and organization_id = {$organizationId}"
            )->Fetch();
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => "Ошибка: " . $e->getMessage()
            ];
        }

        return ['success' => true, 'msg' => "Синхронизация прошла успешно. Обновлено: {$countUpdate}. Добавлено: {$countInsert}. Неактуально: {$nonActualTotal['val']}"];
    }


    /**
     * @return array
     */
    public function syncUnitsFsa()
    {
        $organizationId = App::getOrganizationId();
        $array = array(
            'pageSize'   => 100000,
        );

        $ch = curl_init('http://5.143.238.171:8080/rpi/measuringUnits?' . http_build_query($array));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $json = curl_exec($ch);

        if (curl_errno($ch)) {
            $msg = curl_error($ch);
            curl_close($ch);

            return [
                'success' => false,
                'error' => "Ошибка: {$msg}"
            ];
        }

        curl_close($ch);

        $result = json_decode($json, true);

        $this->DB->Update('ulab_dimension', ['is_actual' => 0]);

        $countUpdate = 0;
        $countInsert = 0;
        foreach ($result['items'] as $item) {
            $name = $this->quoteStr($this->DB->ForSql(trim($item['name'])));
            $unitRu = $this->quoteStr($this->DB->ForSql(trim($item['unitRus'])));
            $unitEn = $this->quoteStr($this->DB->ForSql(trim($item['unitEng'])));
            $isActual = $item['isActual']? 1 : 0;
            $updateAffected = $this->DB->Update(
                'ulab_dimension',
                [
                    'is_actual' => $isActual,
                    'name' => $name,
                    'unit_eng' => $unitEn,
                    'unit_rus' => $unitRu,
                ],
                "where fsa_id = {$item['id']}"
            );

            if ( !$updateAffected ) {
                $countInsert++;
                $this->DB->Insert(
                    'ulab_dimension',
                    [
                        'fsa_id' => $item['id'],
                        'name' => $name,
                        'unit_eng' => $unitEn,
                        'unit_rus' => $unitRu,
                        'is_actual' => $isActual
                    ]
                );
            } else {
                $countUpdate++;
            }
        }

        $nonActualTotal = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_dimension
                    WHERE is_actual = 0 and organization_id = {$organizationId}"
        )->Fetch();

        return ['success' => true, 'msg' => "Синхронизация прошла успешно. Обновлено: {$countUpdate}. Добавлено: {$countInsert}. Неактуально: {$nonActualTotal['val']}"];
    }


    /**
     * @return array
     */
    public function syncUnits()
    {
        try {
            $organizationId = App::getOrganizationId();
            $ch = curl_init('https://ulab.niistrom.pro/API/getUnits.php');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $json = curl_exec($ch);

            if (curl_errno($ch)) {
                $msg = curl_error($ch);
                curl_close($ch);

                return [
                    'success' => false,
                    'error' => "Ошибка: {$msg}"
                ];
            }

            curl_close($ch);

            $result = json_decode($json, true);

            $this->DB->Update('ulab_dimension', ['is_actual' => 0], "where fsa_id is not null");

            $countUpdate = 0;
            $countInsert = 0;
            foreach ($result['data'] as $item) {
                if ( empty($item['fsa_id']) ) {
                    continue;
                }
                $name = $this->quoteStr($this->DB->ForSql(trim($item['name'])));
                $unitRu = $this->quoteStr($this->DB->ForSql(trim($item['unit_rus'])));
                $unitEn = $this->quoteStr($this->DB->ForSql(trim($item['unit_eng'])));
                $isActual = $item['is_actual']? 1 : 0;
                $updateAffected = $this->DB->Update(
                    'ulab_dimension',
                    [
                        'is_actual' => $isActual,
                        'name' => $name,
                        'unit_eng' => $unitEn,
                        'unit_rus' => $unitRu,
                    ],
                    "where fsa_id = {$item['fsa_id']}"
                );

                if ( !$updateAffected ) {
                    $countInsert++;
                    $this->DB->Insert(
                        'ulab_dimension',
                        [
                            'fsa_id' => $item['fsa_id'],
                            'name' => $name,
                            'unit_eng' => $unitEn,
                            'unit_rus' => $unitRu,
                            'is_actual' => $isActual
                        ]
                    );
                } else {
                    $countUpdate++;
                }
            }

            $nonActualTotal = $this->DB->Query(
                "SELECT count(*) val
                        FROM ulab_dimension
                        WHERE is_actual = 0 and organization_id = {$organizationId}"
            )->Fetch();
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => "Ошибка: " . $e->getMessage()
            ];
        }

        return ['success' => true, 'msg' => "Синхронизация прошла успешно. Обновлено: {$countUpdate}. Добавлено: {$countInsert}. Неактуально: {$nonActualTotal['val']}"];
    }
}