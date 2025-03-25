<?php

/**
 * Класс модель техзадания
 * Class Requirement
 */
class Requirement extends Model
{
    const LABS = [
        'LFHI' => 54,
        'LFMI' => 56,
        'DSL' => 55,
        'LSM' => 57,
        'OSK' => 58
    ];


    /**
     * @param int $dealId
     * @return array
     */
    public function getMaterialFromTz(int $dealId)
    {
        if ( empty($dealId) ) {
            return [];
        }

        $result = [];

        $sql = $this->DB->Query(
            "select distinct m.ID as id, m.NAME as name 
                    from ulab_material_to_request as umtr
                    inner join MATERIALS as m on m.ID = umtr.material_id
                    where umtr.deal_id = {$dealId}
                    order by m.NAME"
        );

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int $dealId
     * @return array
     */
    public function getProbeFromTz(int $dealId)
    {
        if ( empty($dealId) ) {
            return [];
        }

        $result = [];

        $sql = $this->DB->Query(
            "select distinct umtr.id, umtr.cipher, m.ID as material_id, m.NAME as material_name
                    from ulab_material_to_request as umtr
                    inner join MATERIALS as m on m.ID = umtr.material_id
                    where umtr.deal_id = {$dealId}
                    order by umtr.probe_number"
        );

        while ($row = $sql->Fetch()) {
            if ( empty($row['cipher']) ) {
                $number = $row['probe_number'] + 1;
                $row['cipher'] = "Не присвоен шифр #{$number}";
            }

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int $dealId
     * @return array
     */
    public function getMethodsFromTz(int $dealId)
    {
        if ( empty($dealId) ) {
            return [];
        }

        $methodsModel = new Methods();

        $result = [];

        $sql = $this->DB->Query(
            "select distinct ugtp.method_id 
                    from ulab_material_to_request as umtr
                    inner join ulab_gost_to_probe as ugtp on ugtp.material_to_request_id = umtr.id
                    where umtr.deal_id = {$dealId}"
        );

        while ($row = $sql->Fetch()) {
            $result[] = $methodsModel->get($row['method_id']);
        }

        return $result;
    }


    /**
     * получает сумму цен методик
     * @param $dealId
     * @return int|float
     */
    public function getPrice($dealId)
    {
        if (empty($dealId)) {
            return 0;
        }

        $sql = $this->DB->Query("
            select sum(gost.price) as price 
            from ulab_gost_to_probe as gost
            inner join ulab_material_to_request as material on material.id = gost.material_to_request_id
            where material.deal_id = {$dealId}
        ")->Fetch();

        if ( isset($sql['price']) ) {
            return (float) $sql['price'];
        } else {
            return 0;
        }
    }


    /**
     * обновляем цену, учитываем скидку
     * @param $dealId
     * @return array
     */
    public function updatePrice($dealId)
    {
        $requestModel = new Request();

        $price = $this->getPrice($dealId);
        $priceDiscount = $price;

        $tzData = $requestModel->getTzByDealId($dealId);

        if ( $tzData['discount_type'] == 'percent' ) {
            $priceDiscount -= $price * $tzData['DISCOUNT'] / 100;
        } elseif ( $tzData['discount_type'] == 'rub' ) {
            $priceDiscount -= $tzData['DISCOUNT'];
        }

        if ( $priceDiscount < 0 ) {
            $priceDiscount = 0;
        }

        $requestModel->updateTz($dealId, ['PRICE' => $price, 'price_discount' => $priceDiscount]);

        return [
            'price' => $price,
            'price_discount' => $priceDiscount,
            'price_ru' => StringHelper::priceFormatRus($priceDiscount),
        ];
    }


    public function getTzIdByDealId(int $dealId)
    {
        $result = $this->DB->Query("SELECT ID FROM `ba_tz` WHERE ID_Z = {$dealId}")->Fetch();

        if ($result === false) {
            return 0;
        }

        return $result['ID'];
    }

    public function getDealIdByTzId(int $tzId): int
    {
        $result = $this->DB->Query("SELECT ID_Z FROM `ba_tz` WHERE ID = {$tzId}")->Fetch();

        if ($result === false) {
            return 0;
        }

        return (int)$result['ID_Z'];
    }

    public function getKP($tzId)
    {
        return $this->DB->Query("SELECT * FROM `KP` WHERE `TZ_ID` = {$tzId}")->Fetch();
    }

    public function getContracts($dealId)
    {
        return $this->DB->Query("SELECT * FROM `DEALS_TO_CONTRACTS` WHERE `ID_DEAL` = {$dealId}")->Fetch();
    }


    /**
     * @param $dealId
     * @param $filter
     * @return array
     */
    public function getMaterialProbeJournal($dealId, $filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                // ид материалов из таблицы materials
                if ( isset($filter['search']['material_id']) ) {
                    $where .= "mtr.material_id = '{$filter['search']['material_id']}' AND ";
                }
                // шифр пробы №акт.поряд_номер/год
                if ( isset($filter['search']['cipher']) ) {
                    $where .= "mtr.cipher LIKE '%{$filter['search']['cipher']}%' AND ";
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
        $where .= "1 ";

        $data = $this->DB->Query(
            "SELECT 
                        mtr.*, m.NAME as material_name, count(gtp.id) as count_methods
                    FROM ulab_material_to_request as mtr
                    inner join MATERIALS as m ON m.ID = mtr.material_id
                    left join ulab_gost_to_probe as gtp on gtp.material_to_request_id = mtr.id
                    WHERE mtr.deal_id = {$dealId} and {$where}
                    group by mtr.id
                    ORDER BY mtr.material_number asc, mtr.probe_number asc {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT 
                        mtr.id
                    FROM ulab_material_to_request as mtr
                    inner join MATERIALS as m ON m.ID = mtr.material_id
                    WHERE mtr.deal_id = {$dealId}"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT 
                        mtr.id
                    FROM ulab_material_to_request as mtr
                    inner join MATERIALS as m ON m.ID = mtr.material_id
                    WHERE mtr.deal_id = {$dealId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {

            if ( empty($row['cipher']) ) {
                $number = $row['probe_number'] + 1;
                $row['cipher'] = "Не присвоен шифр #{$number}";
            }

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @param $dealId
     * @param $filter
     * @return array
     */
    public function getMethodJournal($dealId, $filter)
    {
        $userModel = new User();
        $normDocGostModel = new NormDocGost();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                // ид материалов из таблицы materials
                if ( isset($filter['search']['probe_id']) ) {
                    $in = implode(', ', $filter['search']['probe_id']);
                    $where .= "mtr.id in ({$in}) AND ";
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
        $where .= "1 ";

        $data = $this->DB->Query(
            "SELECT 
                        mtr.cipher, mtr.material_number, mtr.probe_number,
                        m.NAME as material_name, 
                        gtp.*, 
                        methods.id as method_id, methods.name as method_name, methods.clause, methods.in_field, methods.is_extended_field, methods.is_confirm, methods.is_actual, methods.in_field,
                        gost.reg_doc, gost.year
                    FROM ulab_material_to_request as mtr
                    inner join MATERIALS as m ON m.ID = mtr.material_id
                    inner join ulab_gost_to_probe as gtp ON gtp.material_to_request_id = mtr.id
                    inner join ulab_methods as methods ON gtp.new_method_id = methods.id
                    inner join ulab_gost as gost ON gost.id = methods.gost_id
                    WHERE mtr.deal_id = {$dealId} and {$where}
                    ORDER BY mtr.material_number asc, mtr.probe_number asc, gtp.gost_number asc {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT 
                        gtp.id
                    FROM ulab_material_to_request as mtr
                    inner join MATERIALS as m ON m.ID = mtr.material_id
                    inner join ulab_gost_to_probe as gtp ON gtp.material_to_request_id = mtr.id
                    inner join ulab_methods as methods ON gtp.new_method_id = methods.id
                    inner join ulab_gost as gost ON gost.id = methods.gost_id
                    WHERE mtr.deal_id = {$dealId}"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT 
                        gtp.id
                    FROM ulab_material_to_request as mtr
                    inner join MATERIALS as m ON m.ID = mtr.material_id
                    inner join ulab_gost_to_probe as gtp ON gtp.material_to_request_id = mtr.id
                    inner join ulab_methods as methods ON gtp.new_method_id = methods.id
                    inner join ulab_gost as gost ON gost.id = methods.gost_id
                    WHERE mtr.deal_id = {$dealId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {

            if ( empty($row['cipher']) ) {
                $number = $row['probe_number'] + 1;
                $row['cipher'] = "Не присвоен шифр #{$number}";
            }

            $row['material_probe'] = "{$row['material_name']} | {$row['cipher']}";
            $normDocInfo = $normDocGostModel->getMethod($row['norm_doc_method_id']);

            $strMpName = StringHelper::removeSpace($row['method_name']);
            $clause = StringHelper::removeSpace($row['clause']);
            $strYear = !empty($row['year']) ? "-{$row['year']}" : '';
            $strClause = !empty($clause) ? " {$clause}" : '';
            $row['method_view_name'] = "{$row['reg_doc']}{$strYear}{$strClause} | {$strMpName}";

            if ( !empty($normDocInfo['view_name']) ) {
//                $strMpName = StringHelper::removeSpace($row['measured_properties_name']);
//                $clause = StringHelper::removeSpace($row['tu_clause']);
//                $strYear = !empty($row['tu_year']) ? "-{$row['tu_year']}" : '';
//                $strClause = !empty($clause) ? " {$clause}" : '';
                $row['tu_view_name'] = $normDocInfo['view_name'];
            } else {
                $row['tu_view_name'] = "--/--";
            }

            $row['gost_number']++;

            $row['assigned_name'] = 'Не назначен';

            if ( !empty($row['assigned_id']) ) {
                $user = $userModel->getUserData($row['assigned_id']);

                $row['assigned_name'] = $user['short_name'];
            }

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @param int $dealId
     * @return array
     */
    public function getContractByDealId(int $dealId): array
    {
        $result = [];

        $contract = $this->DB->Query(
            "SELECT d_c.*, d_c.ID d_c_id, d.*, d.ID d_id 
                    FROM DEALS_TO_CONTRACTS d_c 
                    INNER JOIN DOGOVOR d ON d_c.ID_CONTRACT=d.ID 
                    WHERE d_c.ID_DEAL = {$dealId}")->Fetch();

        if (!empty($contract)) {
            $contract['DATE'] = date("d.m.Y", strtotime($contract['DATE']));
            $result = $contract;
        }

        return $result;
    }


    /**
     * @param $dealId
     * @return array|false
     */
    public function getDogovor($dealId)
    {
        $result = $this->DB->Query("SELECT DOGOVOR_NUM FROM `ba_tz` WHERE ID_Z = {$dealId} ")->Fetch();

        if ( !empty($result) ) {
            $idDogovorArr = explode('ID', $result['DOGOVOR_NUM']);

            if (isset($idDogovorArr[1])) {
                $idDogovor = $idDogovorArr[1];
            } else {
                $idDogovor = $idDogovorArr[0];
            }
        } else {
            return [];
        }

        return $this->DB->Query("SELECT * FROM `DOGOVOR` WHERE ID = '{$idDogovor}'")->Fetch();
    }

    public function getActBase($dealId)
    {
        $result = $this->DB->Query("SELECT * FROM `ACT_BASE` WHERE `ID_Z` = {$dealId}")->Fetch();
        $date = strtotime($result['ACT_DATE']);
        $result['date_ru'] = StringHelper::dateRu($result['ACT_DATE']);
        $result['year'] = (int)date("Y", $date)%10 ? substr(date("Y", $date), -2) : date("Y", $date);

        return $result;
    }

    public function getAct($tzId)
    {
        return $this->DB->Query("SELECT * FROM `ACT` WHERE `TZ_ID` = {$tzId}")->Fetch();
    }

    public function getActVr($tzId)
    {
        return $this->DB->Query("SELECT * FROM `AKT_VR` WHERE `TZ_ID`= {$tzId}")->Fetch();
    }

    public function getProtocols($tzId)
    {

        $result = $this->DB->Query("SELECT * FROM `PROTOCOLS` WHERE `ID_TZ` = {$tzId}");

        $return = [];

        while ($row = $result->Fetch()) {
            $return[] = $row;
        }

        return $return;
    }

    public function getTzDoc($tzId)
    {
        return $this->DB->Query("SELECT * FROM `TZ_DOC` WHERE `TZ_ID` = {$tzId}")->Fetch();
    }

    /**
     * @deprecated
     *@return array
     */
    public function getQuarry(): array
    {
        $result = [];

        $quarry = $this->DB->Query("SELECT * FROM `Quarry`");

        while ($row = $quarry->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getTzByDealId(int $dealId)
    {
        $result = [];

        $baTz = $this->DB->Query("SELECT * FROM `ba_tz` WHERE `ID_Z` = {$dealId} ");

        while ($row = $baTz->Fetch()) {
            //TODO: deserialize
            $addMails = unserialize($row['SAVE_MAIL']);

            $row['addMail'] = [];
            foreach ($addMails as $mail) {
                if ( !empty($mail) ) {
                    $row['addMail'][] = $mail;
                }
            }

            $tz = unserialize($row['TZ']);
            $row['TZ'] = $tz;

            $probe = unserialize($row['PROBE']);
            $row['PROBE'] = $probe;

            $row['add_info'] = json_decode($row['add_info'])?? [];

            $regx = "/(\d+)$/";
            preg_match($regx, $row['DOGOVOR_NUM'], $match);
            $row['DOGOVOR_NUM'] = $match[1] ?? '';

            if ( !empty($row['PRICE']) ) {
                $row['PRICE'] = StringHelper::priceFormatRus($row['PRICE']);
            } else {
                $row['PRICE'] = '--';
            }

            $result = $row;
        }

        return $result;
    }


    /**
     * @param int $tzId
     * @return array
     */
    public function getTzByTzId(int $tzId): array
    {
        $result = [];

        $baTz = $this->DB->Query("SELECT * FROM `ba_tz` WHERE `ID` = {$tzId} ");

        while ($row = $baTz->Fetch()) {
            //TODO: deserialize
            $addMails = unserialize($row['SAVE_MAIL']);

            $row['addMail'] = [];
            foreach ($addMails as $mail) {
                if ( !empty($mail) ) {
                    $row['addMail'][] = $mail;
                }
            }

            $tz = unserialize($row['TZ']);
            $row['TZ'] = $tz;

            $probe = unserialize($row['PROBE']);
            $row['PROBE'] = $probe;

            $row['add_info'] = json_decode($row['add_info'])?? [];

            $regx = "/(\d+)$/";
            preg_match($regx, $row['DOGOVOR_NUM'], $match);
            $row['DOGOVOR_NUM'] = $match[1] ?? '';

            $price = 0;
            if ( !empty($row['PRICE']) ) {
                $price = (float)$row['PRICE'];

                if ( $row['discount_type'] == 'percent' ) {
                    $price -= $price * $row['DISCOUNT'] / 100;
                } elseif ( $row['discount_type'] == 'rub' ) {
                    $price -= $row['DISCOUNT'];
                }
            }

            if ( $price < 0 ) {
                $price = 0;
            }

            $row['price_discount'] = $price;
            $row['price_ru'] = StringHelper::priceFormatRus($price);

            $result = $row;
        }

        return $result;
    }


    /**
     * @deprecated
     * @param int $dealId
     * @param array $materialDataList
     * @param array $probeDataList
     * @return bool[]|string[]
     */
    public function setMaterialProbeGostToRequest(int $dealId, array $materialDataList, array $probeDataList): array
    {
        $this->DB->Query("DELETE FROM `MATERIALS_TO_REQUESTS` WHERE ID_DEAL = {$dealId}");

        $this->DB->Query("DELETE FROM `ulab_material_to_request` WHERE deal_id = {$dealId}");

        $m_number = 1;
        foreach ($materialDataList as $keyMaterial => $material) {
            $k = 1;

            $materialData = [
                'ID_DEAL' => $dealId,
                'ID_MATERIAL' => $material['id'],
                'NAME_MATERIAL' => $this->quoteStr($this->DB->ForSql($material['name'])),
                'OBIEM' => $probeDataList[$keyMaterial]
            ];

            $materialToRequestId = $this->DB->Insert('MATERIALS_TO_REQUESTS', $materialData);

            if (empty($materialToRequestId)) {
                return [
                    'error' => "Не удалось создать материал '{$material['name']}'",
                ];
            }

            $dataProbe = [
                'deal_id' => $dealId,
                'material_id' => $material['id'],
                'material_number' => $m_number
            ];

            for ($i = 0; $i < $probeDataList[$keyMaterial]; $i++) {
                $key_g = 1;

                $dataProbe['probe_number'] = $k;
                $ulabMaterialToRequest = $this->DB->Insert('ulab_material_to_request', $dataProbe);
                $k++;


                $probeData = [
                    'material_request_id' => (int)$materialToRequestId
                ];

                $probeToMaterials = $this->DB->Insert('probe_to_materials', $probeData);

                if (empty($probeToMaterials)) {
                    return [
                        'error' => "Не удалось создать пробу для материала '{$material['name']}'",
                    ];
                }

                foreach ($material['gosts']['gost_method'] as $key => $item) {

                    $gostToProbeData = [
                        'method_id' => (int)$item['id'],
                        'new_method_id' => (int)$item['id'],
                        'conditions_id' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'tech_condition_id' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'price' => $this->quoteStr($this->DB->ForSql($material['gosts']['price'][$key])),
                        'material_to_request_id' => $ulabMaterialToRequest,
                        'assigned_id' => $this->quoteStr($this->DB->ForSql($material['gosts']['assigned'][$key])),
                        'gost_number' => $key_g
                    ];

                    $ulabGostToProbe = $this->DB->Insert('ulab_gost_to_probe', $gostToProbeData);


                    $gostData = [
                        'probe_id' => (int)$probeToMaterials,
                        'gost_method' => (int)$item['id'],
                        'new_method_id' => (int)$item['id'],
                        'gost_conditions' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'tech_condition_id' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'price' => $this->quoteStr($this->DB->ForSql($material['gosts']['price'][$key])),
                        'assigned' => $this->quoteStr($this->DB->ForSql($material['gosts']['assigned'][$key]))
                    ];

                    $gostToProbe = $this->DB->Insert('gost_to_probe', $gostData);

                    if (empty($gostToProbe)) {
                        return [
                            'error' => "Не удалось создать ГОСТ для материала '{$material['name']}'",
                        ];
                    }
                    $key_g++;
                }
            }
            $m_number++;
        }

        return [
            'success' => true
        ];
    }


    /**
     * Обновить материал и госты
     * @param array $materialDataList
     * @param array $probeDataList
     * @return bool[]|string[]
     */
    public function updateMaterialProbeGostToRequest($dealId, $materialDataList, $probeDataList)
    {
        $m_number = 1;
        foreach ($materialDataList as $keyMaterial => $material) {
            $k = 1;

            $materialData = [
                'ID_DEAL' => $dealId,
                'ID_MATERIAL' => $material['id'],
                'NAME_MATERIAL' => $this->quoteStr($this->DB->ForSql($material['name'])),
                'OBIEM' => $probeDataList[$keyMaterial]
            ];

            if ( !empty($material['mtr_id']) ) {
                $this->DB->Update('MATERIALS_TO_REQUESTS', $materialData, "WHERE ID = {$material['mtr_id']}");
            } else {
                $material['mtr_id'] = $this->DB->Insert('MATERIALS_TO_REQUESTS', $materialData);
            }


            $dataProbe = [
                'deal_id' => $dealId,
                'material_id' => $material['id'],
                'material_number' => $m_number,
                'mtr_id' => $material['mtr_id'],
            ];

            $this->DB->Query("DELETE FROM `probe_to_materials` WHERE material_request_id = {$material['mtr_id']}");
            $this->DB->Query("DELETE FROM `ulab_material_to_request` WHERE probe_number > {$probeDataList[$keyMaterial]} and mtr_id = {$material['mtr_id']}");
            for ($i = 0; $i < $probeDataList[$keyMaterial]; $i++) {
                $key_g = 1;

                $dataProbe['probe_number'] = $k;

                $c = $this->DB->Query("select id from ulab_material_to_request where mtr_id = {$material['mtr_id']} and probe_number = {$k}")->Fetch();

                if ( isset($c['id']) ) {
                    $this->DB->Update('ulab_material_to_request', $dataProbe, "WHERE mtr_id = {$material['mtr_id']} and probe_number = {$k}");
                    $ulabMaterialToRequest = $c['id'];
                } else {
                    $ulabMaterialToRequest = $this->DB->Insert('ulab_material_to_request', $dataProbe);
                }

                $k++;


                $probeData = [
                    'material_request_id' => (int)$material['mtr_id']
                ];

                $probeToMaterials = $this->DB->Insert('probe_to_materials', $probeData);

                foreach ($material['gosts']['gost_method'] as $key => $item) {

                    $gostToProbeData = [
                        'method_id' => (int)$item['id'],
                        'new_method_id' => (int)$item['id'],
                        'conditions_id' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'tech_condition_id' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'price' => $this->quoteStr($this->DB->ForSql($material['gosts']['price'][$key])),
                        'gost_number' => $key_g,
                        'assigned_id' => $this->quoteStr($this->DB->ForSql($material['gosts']['assigned'][$key]))
                    ];

                    $c2 = $this->DB->Query("select * from ulab_gost_to_probe where material_to_request_id = {$ulabMaterialToRequest} and gost_number = {$key_g}")->SelectedRowsCount();

                    if ( $c2 ) {
                        $this->DB->Update('ulab_gost_to_probe', $gostToProbeData, "WHERE material_to_request_id = {$ulabMaterialToRequest} and gost_number = {$key_g}");
                    } else {
                        $gostToProbeData['material_to_request_id'] = $ulabMaterialToRequest;
                        $this->DB->Insert('ulab_gost_to_probe', $gostToProbeData);
                    }

                    $gostData = [
                        'probe_id' => (int)$probeToMaterials,
                        'gost_method' => (int)$item['id'],
                        'new_method_id' => (int)$item['id'],
                        'gost_conditions' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'tech_condition_id' => (int)$material['gosts']['gost_conditions'][$key]['id'],
                        'price' => $this->quoteStr($this->DB->ForSql($material['gosts']['price'][$key])),
                        'assigned' => $this->quoteStr($this->DB->ForSql($material['gosts']['assigned'][$key]))
                    ];

                    $this->DB->Insert('gost_to_probe', $gostData);

                    $key_g++;
                }
            }
            $m_number++;
        }

        return [
            'success' => true
        ];
    }


    /**
     * @param $dealId
     * @return bool
     */
    public function isExistTz($dealId)
    {
        $sql = $this->DB->Query(
            "select 
                        ugtp.id 
                    from ulab_material_to_request as umtr
                    inner join ulab_gost_to_probe as ugtp on ugtp.material_to_request_id = umtr.id
                    where umtr.deal_id = {$dealId}"
        )->Fetch();

        return !empty($sql);
    }


    /**
     * Удаляет материал из заявки
     * @deprecated
     * @param $dealId
     * @param $materialId
     * @param $mtrId
     * @param $number
     */
    public function deleteMaterial($dealId, $materialId, $mtrId, $number)
    {
        $sql = $this->DB->Query("select * from probe_to_materials where material_request_id = {$mtrId}");

        while ($row = $sql->Fetch()) {
            $this->DB->Query("delete from gost_to_probe where probe_id = {$row['id']}");
        }

        $sql2 = $this->DB->Query("select * from ulab_material_to_request where deal_id = {$dealId} and material_id = {$materialId} and material_number = {$number}");

        while ($row = $sql2->Fetch()) {
            $this->DB->Query("delete from ulab_gost_to_probe where material_to_request_id = {$row['id']}");
        }

        $this->DB->Query("delete from probe_to_materials where material_request_id = {$mtrId}");

        $this->DB->Query("delete from ulab_material_to_request where deal_id = {$dealId} and material_id = {$materialId} and material_number = {$number}");

        $this->DB->Query("delete from MATERIALS_TO_REQUESTS where ID = {$mtrId}");
    }


    /**
     * Удаляет методику из материала
     * @param $gtpId
     * @param $dealId
     * @param $materialId
     * @param $numberGost
     * @param $number
     */
    public function deleteMaterialGost($gtpId, $dealId, $materialId, $numberGost, $number)
    {
        $sql2 = $this->DB->Query("select * from ulab_material_to_request where deal_id = {$dealId} and material_id = {$materialId} and material_number = {$number}");

        while ($row = $sql2->Fetch()) {
            $this->DB->Query("delete from ulab_gost_to_probe where material_to_request_id = {$row['id']} and gost_number = {$numberGost}");

            $sql3 = $this->DB->Query("select * from ulab_gost_to_probe where material_to_request_id = {$row['id']}");

            $i = 1;
            while ($row2 = $sql3->Fetch()) {
                $this->DB->Update('ulab_gost_to_probe', ['gost_number' => $i++], "where id = {$row2['id']}");
            }
        }

        $this->DB->Query("delete from gost_to_probe where id = {$gtpId}");
    }


    /**
     * удаляет испытание из пробы по ид
     * @param $tzId
     * @param $ugtpId
     * @return array
     */
    public function deleteProbeMethod($tzId, $ugtpId)
    {
        if ( empty($ugtpId) ) {
            return [
                'success' => false,
                'error' => "Нечего удалять"
            ];
        }

        $results = $this->DB->Query(
            "select 
                ugtp.measuring_sheet, ugtp.material_to_request_id, ugtp.gost_number,  ugtp.actual_value as ugtp_actual_value, 
                utr.actual_value as utr_actual_value, 
                umtr.deal_id 
            from ulab_gost_to_probe as ugtp
            inner join ulab_material_to_request as umtr on umtr.id = ugtp.material_to_request_id 
            left join ulab_trial_results as utr on utr.gost_to_probe_id = ugtp.id 
            where ugtp.id = {$ugtpId}"
        )->Fetch();

        $value = json_decode($results['utr_actual_value'], true);
        $sheet = json_decode($results['measuring_sheet'], true);
        $actualValue = $results['deal_id'] >= DEAL_NEW_RESULT ? $results['ugtp_actual_value'] : $value[0];

        if ( $actualValue != '' || $sheet ) {
            return [
                'success' => false,
                'error' => "Невозможно удалить испытание, по которому уже внесены результаты"
            ];
        }

        if ( empty($results) ) {
            return [
                'success' => false,
                'error' => "Нечего удалять"
            ];
        }

        $this->DB->Query("delete from ulab_gost_to_probe where id = {$ugtpId}");

        $sql = $this->DB->Query(
            "select id 
                    from ulab_gost_to_probe 
                    where material_to_request_id = {$results['material_to_request_id']} and gost_number > {$results['gost_number']} 
                    order by gost_number asc"
        );

        $i = $results['gost_number'];
        while ($row = $sql->Fetch()) {
            $this->DB->Update('ulab_gost_to_probe', ['gost_number' => $i++], "where id = {$row['id']}");
        }

        // обновляем цены у заявки
        $price = $this->updatePrice($results['deal_id']);

        $this->confirmTzClear($tzId);

        return [
            'success' => true,
            'data' => $price,
        ];
    }


    /**
     * @param $data
     */
    public function changeGostNumber($data)
    {
        foreach ($data as $row) {
            $this->DB->Update('ulab_gost_to_probe', ['gost_number' => $row['newPosition']], "where id = {$row['id']}");
        }
    }


    /**
     * @param $tzId
     * @param $ugtpId
     * @param $data - ['key' => val]
     * @return array
     */
    public function updateProbeGost($tzId, $ugtpId, $data)
    {
        if ( empty($ugtpId) ) {
            return [
                'success' => false,
                'error' => "Нечего обновлять"
            ];
        }

        $requestModel = new Request();

        if ( isset($data['new_method_id']) ) {
            $methodModel = new Methods();

            $method = $methodModel->get($data['new_method_id']);

            $data['price'] = $method['price'];
            $data['assigned_id'] = '';
        }

        $this->DB->Update('ulab_gost_to_probe', $data, "where id = {$ugtpId}");

        $dealId = $requestModel->getDealIdByTzId($tzId);

        // обновляем цены у заявки
        $price = $this->updatePrice($dealId);

        $this->updateAssigned($dealId);

        return [
            'success' => true,
            'data' => $price,
        ];
    }


    /**
     * @param $dealId
     * @param $oldMaterial
     * @param $newMaterialId
     */
    public function updateMaterialTz($dealId, $oldMaterial, $newMaterialId)
    {
        $this->DB->Update('ulab_material_to_request', ['material_id' => $newMaterialId], "where deal_id = {$dealId} and material_id = {$oldMaterial}");
    }


    /**
     * @param $umtrId
     * @param $data
     */
    public function updateProbeInfo($umtrId, $data)
    {
        $sqlData = $this->prepearTableData('ulab_material_to_request', $data);

        $this->DB->Update('ulab_material_to_request', $sqlData, "where id = {$umtrId}");
    }


    /**
     * удаляет пробу и испытания
     * @param $probeId
     * @return array
     */
    public function deleteProbe($dealId, $probeId)
    {
        $probeData = $this->DB->Query(
            "select *
            from ulab_material_to_request 
            where id = {$probeId}"
        )->Fetch();

        if ( !empty($probeData['in_act']) ) {
            return [
                'success' => false,
                'error' => "Невозможно удалить пробу, по которой сформирован акт."
            ];
        }

        $sql = $this->DB->Query(
            "select ugtp.id, ugtp.measuring_sheet, 
                utr.actual_value utr_actual_value, ugtp.actual_value ugtp_actual_value, 
                umtr.deal_id 
            from ulab_gost_to_probe ugtp
            left join ulab_trial_results utr on utr.gost_to_probe_id = ugtp.id 
            left join ulab_material_to_request umtr on umtr.id = ugtp.material_to_request_id 
            where ugtp.material_to_request_id = {$probeId}"
        );

        $gtpIds = [];
        while ($row = $sql->Fetch()) {
            $value = json_decode($row['utr_actual_value'], true);
            $sheet = json_decode($row['measuring_sheet'], true);
            $gtpIds[] = $row['id'];
            $actualValue = $row['deal_id'] >= DEAL_NEW_RESULT ? $row['ugtp_actual_value'] : $value[0];

            if ( $actualValue != '' || $sheet ) {
                return [
                    'success' => false,
                    'error' => "Невозможно удалить пробу. Есть испытания, у которых внесены результаты."
                ];
            }
        }

        if ( !empty($gtpIds) ) {
            $strId = implode(',', $gtpIds);

            $this->DB->Query("delete from ulab_trial_results where gost_to_probe_id in ({$strId})");
        }

        $this->DB->Query("delete from ulab_gost_to_probe where material_to_request_id = {$probeId}");

        $this->DB->Query("delete from ulab_material_to_request where id = {$probeId}");

        $requestModel = new Request();

        $tzId = $requestModel->getTzIdByDealId($dealId)['ID'];

        // обновляем цены у заявки
        $price = $this->updatePrice($dealId);

        $this->confirmTzClear($tzId);

        return [
            'success' => true,
            'data' => $price,
        ];
    }


    /**
     * @param $dealId
     * @param $materialId
     * @return array
     */
    public function deleteMaterialNew($dealId, $materialId)
    {
        $sql = $this->DB->Query(
            "select mtr.id, mtr.cipher, ugtp.measuring_sheet, 
                utr.actual_value utr_actual_value, ugtp.actual_value ugtp_actual_value 
            from ulab_material_to_request mtr
            inner join ulab_gost_to_probe ugtp on ugtp.material_to_request_id = mtr.id
            left join ulab_trial_results utr on utr.gost_to_probe_id = ugtp.id
            where mtr.material_id = {$materialId} and mtr.deal_id = {$dealId}"
        );

        $mtrIds = [];
        while ($row = $sql->Fetch()) {
            $value = json_decode($row['utr_actual_value'], true);
            $sheet = json_decode($row['measuring_sheet'], true);
            $mtrIds[] = $row['id'];
            $actualValue = $dealId >= DEAL_NEW_RESULT ? $row['ugtp_actual_value'] : $value[0];

            if ( !empty($row['cipher']) ) {
                return [
                    'success' => false,
                    'error' => "Невозможно удалить материал. Есть пробы, по которым сформирован акт."
                ];
            }

            if ( $actualValue != '' || $sheet ) {
                return [
                    'success' => false,
                    'error' => "Невозможно удалить материал. Есть испытания, у которых внесены результаты."
                ];
            }
        }

        if ( !empty($mtrIds) ) {
            $strId = implode(',', $mtrIds);

            $this->DB->Query("delete from ulab_gost_to_probe where material_to_request_id in ({$strId})");
        }

        $this->DB->Query("delete from ulab_material_to_request where material_id = {$materialId} and deal_id = {$dealId}");

        $requestModel = new Request();

        $tzId = $requestModel->getTzIdByDealId($dealId)['ID'];

        $this->confirmTzClear($tzId);

        return ['success' => true];
    }


    /**
     * меняет материал
     * @param $dealId
     * @param $data
     */
    public function updateMaterial($dealId, $data)
    {
        foreach ($data as $materialId => $newMaterialId) {
            if ( $materialId != $newMaterialId ) {
                $this->DB->Update('ulab_material_to_request', ['material_id' => $newMaterialId], "WHERE deal_id = {$dealId} and material_id = {$materialId}");
            }
        }
    }


    /**
     * обновляет информацию по пробам и методам
     * @param $dealId
     * @param $data
     */
    public function updateProbeMethod($dealId, $data)
    {
        $materialModel = new Material();
        $requestModel = new Request();
        $probeModel = new Probe();
        $arrMaterial = [];

        foreach ($data as $materialId => $materialData) {

            $arrMaterial[] = $materialModel->getById((int)$materialId)['NAME'];

            foreach ($materialData['probe'] as $probeId => $probeData) {

                $sqlProbeData = $this->prepearTableData('ulab_material_to_request', $probeData);

                if ( stripos($probeId, 'new') === false ) {
                    $this->DB->Update('ulab_material_to_request', $sqlProbeData, "WHERE id = {$probeId}");
                } else {
                    $sqlProbeData['material_id'] = $materialId;
                    $sqlProbeData['deal_id'] = $dealId;
                    $probeId = $this->DB->Insert('ulab_material_to_request', $sqlProbeData);
                }

				$probeModel->setLabHeaderByUmtrId($probeId);

                // обновляет методы
                foreach ($probeData['method'] as $ugtpId => $methodData) {
                    if ( empty($methodData['new_method_id']) ) { continue; }
                    $methodData['method_id'] = $methodData['new_method_id'];
                    $methodData['conditions_id'] = $methodData['tech_condition_id'];

                    $sqlMethodData = $this->prepearTableData('ulab_gost_to_probe', $methodData);

                    if ( stripos($ugtpId, 'new') === false ) {
                        $this->DB->Update('ulab_gost_to_probe', $sqlMethodData, "WHERE id = {$ugtpId}");
                    } else {
                        $sqlMethodData['material_to_request_id'] = $probeId;
                        $this->DB->Insert('ulab_gost_to_probe', $sqlMethodData);
                    }
                }
            }
        }
        $strMaterial = implode(', ', $arrMaterial);
        $requestModel->updateTz($dealId, ['MATERIAL' => "'{$strMaterial}'"]);
    }


    /**
     * получение рук лабораторий и статуса тз
     * @param $dealId
     * @return array
     */
    public function getLabHead($dealId)
    {
        $sql = $this->DB->Query(
            "select distinct 
                        ugtp.new_method_id as method_id 
                    from ulab_material_to_request as umtr
                    left join ulab_gost_to_probe as ugtp on ugtp.material_to_request_id = umtr.id
                    where umtr.deal_id = {$dealId}"
        );

        $methodModel = new Methods();
        $labModel = new Lab();
        $userModel = new User();

        $labsId = [
            'user' => [],
            'is_curr_user' => 0,
            'curr_user_status' => 0,
            'check_state' => CHECK_TZ_NOT_SENT,
        ];
        $state = [];
        while ($row = $sql->Fetch()) {
            $labs = $methodModel->getLab($row['method_id']);
            foreach ($labs as $lab) {
                $user = $userModel->checkHeader($labModel->getDepartmentIdByLabId($lab));
                if ( empty($user) || in_array($lab, [5, 6]) ) { continue; }

                $user['is_confirm'] = $this->getHasConfirmUser($dealId, $user['user_id']);
                $state[] = $user['is_confirm'];

                if ( $_SESSION['SESS_AUTH']['USER_ID'] == $user['user_id'] ) {
                    $labsId['is_curr_user'] = 1;
                    $labsId['curr_user_status'] = $user['is_confirm'];
                }

                $labsId['user'][$lab] = $user;
            }
        }

        if ( empty($state) || array_search(CHECK_TZ_NOT_SENT, $state) !== false ) {
            $labsId['check_state'] = CHECK_TZ_NOT_SENT;
        } elseif ( array_search(CHECK_TZ_NOT_APPROVE, $state) !== false ) {
            $labsId['check_state'] = CHECK_TZ_NOT_APPROVE;
        } elseif ( array_search(CHECK_TZ_WAIT, $state) !== false ) {
            $labsId['check_state'] = CHECK_TZ_WAIT;
        } else {
            $labsId['check_state'] = CHECK_TZ_APPROVE;
        }

        return $labsId;
    }


    public function updateAssigned($dealId)
    {
        $this->DB->Query("delete from assigned_to_request WHERE deal_id = {$dealId} AND is_creator = 0 AND is_main = 0");

        $sql = $this->DB->Query(
            "select distinct 
                        ugtp.new_method_id as method_id, ugtp.assigned_id 
                    from ulab_material_to_request as umtr
                    left join ulab_gost_to_probe as ugtp on ugtp.material_to_request_id = umtr.id
                    where umtr.deal_id = {$dealId}"
        );

        $methodModel = new Methods();
        $labModel = new Lab();
        $userModel = new User();

        $users = [];
        $labList = [];
        while ($row = $sql->Fetch()) {
            if ( !empty($row['assigned_id']) ) {
                $users[$row['assigned_id']] = $row['assigned_id'];
            }

            $labs = $methodModel->getLab($row['method_id']);
            foreach ($labs as $lab) {
                $depId = $labModel->getDepartmentIdByLabId($lab);
                $labList[$lab] = $depId;
                $user = $userModel->checkHeader($depId);
                if ( empty($user) || in_array($lab, [5, 6]) ) { continue; }

                $users[$user['user_id']] = $user['user_id'];
            }
        }

        if ( !empty($labList) ) {
            $labStr = implode(',', $labList);
            $this->DB->Update("ba_tz", ['LABA_ID' => "'{$labStr}'"], "where ID_Z = {$dealId}");
        }

        foreach ($users as $id) {
            $ass = [
                'deal_id' => $dealId,
                'user_id' => $id,
            ];

            $this->DB->Insert('assigned_to_request', $ass);
        }
    }


    /**
     * возвращает статус тз (подтвержден, не подтвержден, в ожидании, не отправлено на рассмотрение)
     * @param $dealId
     * @return int
     */
    public function getStateConfirm($dealId)
    {
        $requestModel = new Request();
        $tzId = $requestModel->getTzIdByDealId($dealId)['ID'];

        $sql = $this->DB->Query("select * from CHECK_TZ where tz_id = {$tzId}");

        $stateList = [];
        while ($row = $sql->Fetch()) {
            if ($row['confirm'] == 1 && empty($row['date_return'])) {
                $stateList[] = CHECK_TZ_APPROVE;
            } else if ($row['confirm'] == 0 && empty($row['date_return'])) {
                $stateList[] = CHECK_TZ_WAIT;
            } else {
                $stateList[] = CHECK_TZ_NOT_APPROVE;
            }
        }

        if ( empty($stateList) ) {
            return CHECK_TZ_NOT_SENT;
        }

        if ( array_search(CHECK_TZ_NOT_APPROVE, $stateList) !== false ) {
            $state = CHECK_TZ_NOT_APPROVE;
        } else if ( array_search(CHECK_TZ_WAIT, $stateList) !== false ) {
            $state = CHECK_TZ_WAIT;
        } else {
            $state = CHECK_TZ_APPROVE;
        }

        return $state;
    }


    /**
     * отправка тз на проверку
     * @param $dealId
     */
    public function confirmTzSent($dealId)
    {
        $labHead = $this->getLabHead($dealId);

        $requestModel = new Request();

        $tzId = $requestModel->getTzIdByDealId($dealId)['ID'];

        $this->confirmTzClear($tzId);

        foreach ($labHead['user'] as $user) {
            $data = [
                'tz_id' => $tzId,
                'user_sent_id' => $_SESSION['SESS_AUTH']['USER_ID'],
                'date_submission' => date('Y-m-d H:i:s'),
                'leader' => $user['user_id'],
            ];

            $sqlData = $this->prepearTableData('CHECK_TZ', $data);

            $this->DB->Insert('CHECK_TZ', $sqlData);
        }
    }


    /**
     * отправка тз на проверку конкретным пользователям
     * @param $dealId
     * @param $userList
     */
    public function confirmTzSentUsers($dealId, $userList)
    {
        $requestModel = new Request();

        $tzId = $requestModel->getTzIdByDealId($dealId)['ID'];

        $this->confirmTzClear($tzId);

        foreach ($userList as $userId) {
            $data = [
                'tz_id' => $tzId,
                'user_sent_id' => $_SESSION['SESS_AUTH']['USER_ID'],
                'date_submission' => date('Y-m-d H:i:s'),
                'leader' => $userId,
            ];

            $sqlData = $this->prepearTableData('CHECK_TZ', $data);

            $this->DB->Insert('CHECK_TZ', $sqlData);
        }
    }


    /**
     * очистить отправку на подтверждение тз
     * @param $tzId
     */
    public function confirmTzClear($tzId)
    {
        $this->DB->Query("delete from CHECK_TZ where tz_id = {$tzId}");
    }


    /**
     * рук лаб подтверждает ТЗ
     * @param $tzId
     * @param $userId
     */
    public function confirmTzApprove($tzId, $userId) {
        $data = [
            'confirm' => 1,
            'date_reply' => date('Y-m-d H:i:s'),
        ];

        $sqlData = $this->prepearTableData('CHECK_TZ', $data);

        $this->DB->Update('CHECK_TZ', $sqlData, "where tz_id = {$tzId} and leader = {$userId}");
    }


    /**
     * рук лаб возвращает ТЗ
     * @param $tzId
     * @param $userId
     */
    public function confirmTzNotApprove($tzId, $userId, $desc = "") {
        $data = [
            'confirm' => 0,
            'date_return' => date('Y-m-d H:i:s'),
            'desc_return' => $desc,
        ];

        $sqlData = $this->prepearTableData('CHECK_TZ', $data);

        $this->DB->Update('CHECK_TZ', $sqlData, "where tz_id = {$tzId} and leader = {$userId}");
    }


    /**
     * @param $tzId
     * @return array
     */
    public function getDescConfirmTzNotApprove($tzId)
    {
        $userModel = new User();

        $sql = $this->DB->Query("select * from CHECK_TZ where tz_id = {$tzId} and confirm = 0 and date_return is not null");

        $result = [];

        while ($row = $sql->Fetch()) {
            $userInfo = $userModel->getUserShortById($row['leader']);

            $row['user_name'] = $userInfo['short_name'];
            $row['date_return'] = date('d.m.Y H:i:s', strtotime($row['date_return']));

            $result[] = $row;
        }

        return $result;
    }


    /**
     * проверил юзер тз
     * @param $dealId
     * @param $userId
     * @return int
     */
    public function getHasConfirmUser($dealId, $userId)
    {
        if ( empty($userId) ) {
            return CHECK_TZ_NOT_SENT;
        }

        $requestModel = new Request();

        $tzId = $requestModel->getTzIdByDealId($dealId)['ID'];

        $check = $this->DB->Query("select * from CHECK_TZ where tz_id = {$tzId} and leader = {$userId}")->Fetch();

        if ( empty($check) ) {
            return CHECK_TZ_NOT_SENT;
        } elseif ($check['confirm'] == 0 && !empty($check['date_return'])) {
            return CHECK_TZ_NOT_APPROVE;
        } else {
            return $check['confirm'];
        }
    }


    /**
     * @deprecated
     * @param string $gostName
     * @param int $dealId
     * @return array
     */
    public function getGostsByName(string $gostName, int $dealId): array
    {
        $result = [];

        if (empty($gostName)) {
            return $result;
        }

        $baGost = $this->DB->Query("SELECT * FROM ba_gost WHERE NUM_OA_NEW <> 0 AND NON_ACTUAL <> 1 AND GOST = {$this->quoteStr($this->DB->ForSql($gostName))}");

        $contract = $this->DB->Query("
            SELECT d_c.*, d_c.ID d_c_id, d.*, d.ID d_id
                FROM `DEALS_TO_CONTRACTS` d_c
                INNER JOIN `DOGOVOR` d ON d_c.ID_CONTRACT=d.ID
                WHERE `ID_DEAL` = {$dealId}
        ")->Fetch();

        while ($row = $baGost->Fetch()) {
            if (!empty($contract['ID']) && !empty($contract['LONGTERM']) && $row['ID']) {
                $priceForContracts = $this->DB->Query("
                    SELECT * FROM `PRICE_FOR_CONTRACTS`
                        WHERE `ID_CONTRACT` = {$contract['d_id']} AND `ID_GOST` = {$row['ID']}
                ")->Fetch();

                $row['PRICE'] = $priceForContracts['PRICE'] ?? $row['PRICE'];
            }

            $gostName = !empty($row['GOST']) ? trim($row['GOST']) : '';
            $gostYear = !empty($row['GOST_YEAR']) ? '-' . trim($row['GOST_YEAR']) : '';
            $gostPunkt = !empty($row['GOST_PUNKT']) ? ' ' . trim($row['GOST_PUNKT']) : '';
            $gostSpecification = !empty($row['SPECIFICATION']) ? ' | ' . trim($row['SPECIFICATION']) : '';

            $row['view_gost'] = $gostName . $gostYear . $gostPunkt . $gostSpecification;

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @deprecated
     * @param int $gostId
     * @return array
     */
    public function getGostById(int $gostId): array
    {
        $result = [];

        $gost = $this->DB->Query("SELECT * FROM ba_gost WHERE ID = {$gostId}")->Fetch();

        if ($gost) {
            $gostName = !empty($gost['GOST']) ? trim($gost['GOST']) : '';
            $gostYear = !empty($gost['GOST_YEAR']) ? '-' . trim($gost['GOST_YEAR']) : '';
            $gostPunkt = !empty($gost['GOST_PUNKT']) ? ' ' . trim($gost['GOST_PUNKT']) : '';
            $gostSpecification = !empty($gost['SPECIFICATION']) ? ' | ' . trim($gost['SPECIFICATION']) : '';
            $gost['is_old'] = empty($gost['ulab_method_id']) && $gost['IN_OA'] == 1;
            $gost['view_gost'] = $gostName . $gostYear . $gostPunkt . $gostSpecification;

            $result = $gost;
        }

        return $result;
    }


    /**
     * @deprecated
     * @param int $probeId
     * @return array
     */
    public function getGostsToProbe(int $probeId): array
    {
        $methodModel = new Methods();
        $tcModel = new TechCondition();
        $result = [];

        $gostToProbe = $this->DB->Query(
            "SELECT *, id as gtp_id FROM gost_to_probe WHERE probe_id = {$probeId}"
        );

        while ($row = $gostToProbe->Fetch()) {
            $method = [];
            $conditions = [];

            /*if ($row['new_method_id']) {
                $method = $methodModel->get($row['new_method_id']);
            } else */if ($row['gost_method']) {
                $method = $this->getGostById($row['gost_method']);
            }

            /*if ($row['tech_condition_id']) {
                $conditions = $tcModel->get($row['tech_condition_id']);
            } else */if ($row['gost_conditions']) {
                $conditions = $this->getGostById($row['gost_conditions']);
            }

            $row['method'] = $method;
            $row['conditions'] = $conditions;

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @deprecated
     * @param int $probeId
     * @return array
     */
    public function getUlabGostsToProbe(int $probeId): array
    {
        $methodModel = new Methods();
        $tcModel = new TechCondition();

        $result = [];

        $gostToProbe = $this->DB->Query(
            "SELECT * FROM gost_to_probe WHERE probe_id = {$probeId}"
        );

        while ($row = $gostToProbe->Fetch()) {
            $method = [];
            $conditions = [];

            $assignedIdList = [];
            if ($row['new_method_id']) {
                $method = $methodModel->get($row['new_method_id']);
                $assignedIdList = $methodModel->getAssigned($row['new_method_id']);
            }

            if ($row['tech_condition_id']) {
                $conditions = $tcModel->get($row['tech_condition_id']);
            } else if ($row['gost_conditions']) {
                $conditions = $this->getGostById($row['gost_conditions']);
            }

            $row['method'] = $method;
            $row['assigned_list'] = $assignedIdList;
            $row['conditions'] = $conditions;

            $result[] = $row;
        }

        return $result;
    }

    public function getFirstProbeToMaterials(int $materialToRequestId): array
    {
        $result = [];

        $probeToMaterials = $this->DB->Query(
            "SELECT *, COUNT(id) counts FROM probe_to_materials WHERE material_request_id = {$materialToRequestId}"
        )->Fetch();

        if (count($probeToMaterials?? []) > 0) {
            $result = $probeToMaterials;
        }

        return $result;
    }

    public function getGostsByMaterialId(int $materialId): array
    {
        $result = [];

        $gosts = $this->DB->Query("SELECT DISTINCT GOST FROM ba_gost WHERE NUM_OA_NEW > 0 AND NON_ACTUAL <> 1 AND DOP = {$materialId} ORDER BY GOST");

        while ($row = $gosts->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    public function getMaterialProbeGostToRequest(int $dealId): array
    {
        $result = [];

        $materials = $this->DB->Query(
            "SELECT m.ID id, m.NAME name, mtr.ID mtr_id, mtr.OBIEM amount 
                    FROM MATERIALS_TO_REQUESTS mtr, MATERIALS m 
                    WHERE mtr.ID_DEAL = {$dealId} AND mtr.ID_MATERIAL <> 0 AND m.ID = mtr.ID_MATERIAL"
        );

        $key = 0;

        while ($row = $materials->Fetch()) {
            $row['name'] = !empty($row['name']) ? $row['name'] : 'Материал не выбран';

            $result['material'][] = $row;

            $probe = $this->getFirstProbeToMaterials($row['mtr_id']);

            $result['amount'][] = $row['amount']; //TODO: Почему сделали в MATERIALS_TO_REQUESTS поле OBIEM?

            if (!empty($probe['id'])) {
                //$result['amount'][] = $probe['counts'];

                $gosts = $this->getGostsToProbe($probe['id']);

                foreach ($gosts as $k => $i) {
                    $result['methods'][$key][$k]['name'] = $i['method']['view_gost'];
                    $result['methods'][$key][$k]['id'] = $i['method']['ID']?? $i['method']['id'];
                    $result['methods'][$key][$k]['gtp_id'] = $i['gtp_id'];
                    $result['methods'][$key][$k]['is_old'] = $i['method']['is_old'];

                    $result['conditions'][$key][$k]['name'] = $i['conditions']['view_gost'];
                    $result['conditions'][$key][$k]['id'] = $i['conditions']['ID']?? $i['conditions']['id'];

                    //$result['price'][$key][$k] = $i['method']['PRICE'];
                    $result['price'][$key][$k] = (float)$i['price'];
					$result['assigned'][$key][$k] = $i['assigned'];
                }
            }

            $gostsGroup = $this->getGostsByMaterialId($row['id']);

            if (!empty($gostsGroup)) {
                $result['gosts_group'][$key] = $gostsGroup;
            }

            $key++;
        }

        return $result;
    }


    /**
     * @param int $dealId
     * @return array
     */
    public function getUlabMaterialProbeGostToRequest(int $dealId): array
    {
        $result = [];

        $materials = $this->DB->Query(
            "SELECT m.ID id, m.NAME name, mtr.ID mtr_id, mtr.OBIEM amount 
                    FROM MATERIALS_TO_REQUESTS mtr, MATERIALS m 
                    WHERE mtr.ID_DEAL = {$dealId} AND mtr.ID_MATERIAL <> 0 AND m.ID = mtr.ID_MATERIAL"
        );

        $key = 0;

        while ($row = $materials->Fetch()) {
            $row['name'] = !empty($row['name']) ? $row['name'] : 'Материал не выбран';

            $result['material'][] = $row;

            $probe = $this->getFirstProbeToMaterials($row['mtr_id']);

            $result['amount'][] = $row['amount']; //TODO: Почему сделали в MATERIALS_TO_REQUESTS поле OBIEM?

            if (!empty($probe['id'])) {
                //$result['amount'][] = $probe['counts'];

                $gosts = $this->getUlabGostsToProbe($probe['id']);

                foreach ($gosts as $k => $i) {
                    $result['methods'][$key][$k]['name'] = $i['method']['view_gost'];
                    $result['methods'][$key][$k]['id'] = $i['method']['id'];
                    $result['methods'][$key][$k]['assigned_list'] = $i['assigned_list'];

                    $result['conditions'][$key][$k]['name'] = $i['conditions']['view_name'];
                    $result['conditions'][$key][$k]['id'] = $i['conditions']['ID']?? $i['conditions']['id'];

                    $result['price'][$key][$k] = (float)$i['price'];
                    $result['assigned'][$key][$k] = $i['assigned'];
                }
            }

            $key++;
        }

        return $result;
    }


    /**
     * @param int $tzId
     * @param array $data
     */
    public function updateTzByIdTz(int $tzId, array $data)
    {
        $data['TAKEN_SERT_ISP'] = $data['tests_for'] == 'certification' || isset($data['TAKEN_SERT_ISP']) ? 1 : 0;
        $data['add_info'] = json_encode($data['add_info']);

        $sqlData = $this->prepearTableData('ba_tz', $data);

        $where = "WHERE ID = {$tzId}";
        $this->DB->Update('ba_tz', $sqlData, $where);
    }


    /**
     * @param $dealId
     * @param $materialId
     * @param $number
     */
    public function addMaterialToTz($dealId, $materialId, $number)
    {
        $data = [
            'deal_id' => $dealId,
            'material_id' => $materialId,
        ];

        $sql = $this->DB->Query("select max(probe_number) as probe_number, material_number from ulab_material_to_request where deal_id = {$dealId} and material_id = {$materialId}")->Fetch();
        $probeNumber = $sql['probe_number'];
        $data['material_number'] = $sql['material_number'];

        if ( !is_numeric($probeNumber) ) {
            $probeNumber = -1;

            $sql = $this->DB->Query("select max(material_number) as material_number from ulab_material_to_request where deal_id = {$dealId}")->Fetch();
            $materialNumber = $sql['material_number'];
            $data['material_number'] = ++$materialNumber;
        }

        for ($i = 0; $i < $number; $i++) {
            $data['probe_number'] = ++$probeNumber;

            $this->DB->Insert('ulab_material_to_request', $data);
        }
    }


    /**
     * @param $dealId
     * @param $arrMaterialId
     * @param $number
     */
    public function addProbeToMaterial($dealId, $arrMaterialId, $number)
    {
        foreach ($arrMaterialId as $materialId) {
            $sql = $this->DB->Query("select max(probe_number) as probe_number, material_number from ulab_material_to_request where deal_id = {$dealId} and material_id = {$materialId}")->Fetch();
            $probeNumber = $sql['probe_number'];

            $data = [
                'deal_id' => $dealId,
                'material_id' => $materialId,
                'material_number' => $sql['material_number'],
            ];

            for ($i = 0; $i < $number; $i++) {
                $data['probe_number'] = ++$probeNumber;

                $this->DB->Insert('ulab_material_to_request', $data);
            }
        }
    }


    /**
     * @param $probeIdList - material_to_request_id
     * @param $data
     */
    public function addMethodsToProbe($probeIdList, $data)
    {
        foreach ($probeIdList as $probeId) {
            $sql = $this->DB->Query("select max(gost_number) as gost_number from ulab_gost_to_probe where material_to_request_id = {$probeId}")->Fetch();
            $gostNumber = $sql['gost_number'];
            if ( !is_numeric($gostNumber) ) {
                $gostNumber = -1;
            }

            foreach ($data as $item) {
                $sqlData = [
                    'material_to_request_id' => $probeId,
                    'method_id' => $item['new_method_id'],
                    'new_method_id' => $item['new_method_id'],
                    'conditions_id' => $item['tech_condition_id'],
                    'norm_doc_method_id' => $item['norm_doc_method_id'],
                    'tech_condition_id' => $item['tech_condition_id'],
                    'price' => $item['price'],
                    'assigned_id' => $item['assigned_id'],
                    'gost_number' => ++$gostNumber,
                ];

                $this->DB->Insert('ulab_gost_to_probe', $sqlData);
            }
        }
    }


    /**
     * @return array
     */
    public function getObjects(): array
    {
        $result = [];
        $materials = $this->DB->Query("SELECT * FROM DEV_OBJECTS");

        while ($row = $materials->Fetch()) {
            $row['COORD'] = unserialize($row['COORD']);;

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int $dealId
     * @param int $companyId
     * @return array
     */
    public function getRequestsToCompany(int $dealId, int $companyId): array
    {
        $results = [];

        if (empty($companyId)) {
            return $results;
        }

        $deals = CCrmDeal::GetListEx(['DATE_CREATE' => 'DESC'], ['COMPANY_ID' => $companyId, '!=ID' => $dealId]);

        while ($deal = $deals->Fetch()) {
            $request = $this->getTzByDealId($deal['ID']);

            if (empty($request['PRICE']) || $request['PRICE'] === '--') {
                continue;
            }

            $request['DATE_CREATE'] = !empty($request['DATE_CREATE']) && $request['DATE_CREATE'] !== '' ?
                StringHelper::dateRu($request['DATE_CREATE']) : '';

            $results[] = $request;
        }

        return $results;
    }


    public function getTakenRequest($takenTzId): array
    {
        $results = [];

        if (empty($takenTzId) || !is_numeric($takenTzId)) {
            return $results;
        }

        $request = $this->getTzByTzId($takenTzId);

        if (count($request?? []) > 0) {
            $request['DATE_CREATE'] = !empty($request['DATE_CREATE']) && $request['DATE_CREATE'] !== '' ?
                StringHelper::dateRu($request['DATE_CREATE']) : '';

            $results = $request;
        }

        return $results;
    }

    /**
     * @param array $methodsId
     * @return array
     */
    public function getLabsByMethodsId(array $methodsId): array
    {
        $response = [];
        $strMethodsId = implode(', ', $methodsId);

        var_dump($strMethodsId);
        exit();

        $gosts = $this->DB->Query("SELECT * FROM ba_gost WHERE ID IN ({$strMethodsId})");

        while ($row = $gosts->Fetch()) {
            if ($row['LFHI']) $response[$row['ID']][] = self::LABS['LFHI'];
            if ($row['LFMI']) $response[$row['ID']][] = self::LABS['LFMI'];
            if ($row['DSL'])  $response[$row['ID']][] = self::LABS['DSL'];
            if ($row['LSM'])  $response[$row['ID']][] = self::LABS['LSM'];
            if ($row['OSK'])  $response[$row['ID']][] = self::LABS['OSK'];

            if (empty($row['LFHI']) && empty($row['LFMI']) && empty($row['DSL']) && empty($row['LSM']) && empty($row['OSK'])) {
                $response[$row['ID']] = [];
            }
        }

        return $response;
    }

    /**
     * @param array $labsId
     * @return array
     */
    public function getUserByLabId(array $labsId):array
    {
        $response = [];

        if (empty($labsId)) {
            return $response;
        }

        $by = 'ID';
        $order = 'asc';

        $dbUsers = CUser::GetList(
            $by,
            $order,
            ['ACTIVE' => 'Y', 'UF_DEPARTMENT' => $labsId],
            ['SELECT' => array('UF_*')]
        );

        $num = 0;
        while ($row = $dbUsers->Fetch()) {
            $shortName = StringHelper::shortName($row['NAME']);
            $departmentsData = CIntranetUtils::GetDepartmentsData($row["UF_DEPARTMENT"]);

            $keyLab = current($row["UF_DEPARTMENT"]);

            $response[$keyLab][$num]['department_name'] = current($departmentsData);
            $response[$keyLab][$num]['user_id'] = $row['ID'];
            $response[$keyLab][$num]['name'] = $row['NAME'];
            $response[$keyLab][$num]['last_name'] = $row['LAST_NAME'];
            $response[$keyLab][$num]['second_name'] = $row['SECOND_NAME'];
            $response[$keyLab][$num]['user_name'] = "{$row['LAST_NAME']} {$row['NAME']}";
            $response[$keyLab][$num]['short_name'] = "{$shortName}. {$row['LAST_NAME']}";
            $response[$keyLab][$num]['department'] = $row['UF_DEPARTMENT'];

            $num++;
        }

        return $response;
    }

    /**
     * @param int $tzId
     * @return array
     */
    public function getUnconfirmedTz(int $tzId): array
    {
        $response = [];

        if (empty($tzId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM `CHECK_TZ` WHERE `tz_id`={$tzId} AND confirm = 0 AND `date_return` IS NULL");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

	/**
	 * @param int $tzId
	 * @return bool
	 */
	public function isConfirm(int $tzId)
	{
		$result = $this->DB->Query("SELECT count(*) c FROM `CHECK_TZ` WHERE `tz_id`={$tzId} AND confirm = 0 AND `date_return` IS NULL")->Fetch();
		if ($result['c'] > 0) {
			return false;
		}

		return true;
	}


    /**
     * @param int $tzId
     * @return array
     */
    public function getConfirmedTz(int $tzId): array
    {
        $response = [];

        if (empty($tzId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM `CHECK_TZ` WHERE `tz_id`={$tzId} AND confirm = 1 AND `date_return` IS NULL");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    //TODO: Костыль для утверждения заявки

	/**
	 * @param int $tzId
	 * @return bool
	 */
	public function isConfirmTz(int $tzId)
	{
		$result = $this->DB->Query("SELECT * FROM `CHECK_TZ` WHERE `tz_id`={$tzId}");

		if ($result->SelectedRowsCount() > 0) {
			while ($row = $result->Fetch()) {
				if (empty($row['confirm'])) {
					return false;
				}
			}
			return true;
		}
		return false;
	}


    /**
     * @param int $tzId
     * @return array
     */
    public function getLabLeadersTZ(int $tzId): array
    {
        $response = [];

        if (empty($tzId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM CHECK_TZ WHERE tz_id={$tzId} AND leader IS NOT NULL AND leader <> '' AND date_return IS NULL");

        while ($row = $result->Fetch()) {
            $response[] = $row['leader'];
        }

        return $response;
    }

    /**
     * @param int $tzId
     * @return array
     */
    public function getCheckTzByIdTz(int $tzId): array
    {
        $response = [];

        if (empty($tzId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM CHECK_TZ WHERE tz_id={$tzId} AND `date_return` IS NULL");

        while ($row = $result->Fetch()) {
            $response[$row['leader']] = $row;
        }

        return $response;
    }

    /**
     * @param array $methodsId
     * @return array
     */
    public function getMethodsNotInOA(array $methodsId): array
    {
        $response = [];

        if (empty($methodsId)) {
            return $response;
        }

        $strMethodsId = implode(', ', $methodsId);

        $result = $this->DB->Query("SELECT * FROM ba_gost WHERE IN_OA <> 1 AND ID IN ({$strMethodsId}) AND ID NOT IN (2875, 5962) AND GOST_TYPE <> 'metodic_otbor'");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    /**
     * @param int $tzId
     * @return array
     */
    public function getInvoice(int $tzId): array
    {
        $response = [];

        if (empty($tzId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM INVOICE WHERE TZ_ID = {$tzId}")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * @param int $dealId
     * @return array
     */
    public function getActBaseByDealId(int $dealId): array
    {
        $response = [];

        if (empty($dealId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM `ACT_BASE` WHERE `ID_Z`={$dealId}")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * @param array $data
     */
    public function saveHistory(array $data)
    {
        foreach ($data as $key => $item) {
            if (is_string($item)) {
                $data[$key] = $this->quoteStr($this->DB->ForSql(trim($item)));
            }
        }

        $this->DB->Insert('HISTORY', $data);
    }

    /**
     * @param int $tzId
     * @return array
     */
    public function getLastHistory(int $tzId): array
    {
        $response = [];

        if (empty($tzId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM HISTORY WHERE TZ_ID = {$tzId} ORDER BY DATE DESC LIMIT 1")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }


    //TODO: Временное сохранение данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
    public function savingSerializedData(int $tzId, array $arrData, array $probe)
    {
        $arr = [];
        $i = 0;
        foreach ($arrData as $keys => $arrDatums) {
            foreach ($arrDatums as $key => $arrDatum) {
                foreach ($arrDatum as $item) {
                    $i++;
                    $arr[$i]['mater'] = $key;
                    $arr[$i]['gost'] = $item['gost'];
                    $arr[$i]['obiem'] = $item['obiem'];
                    $arr[$i]['gost_new'] = $item['gost_new'];
                    $arr[$i]['price'] = $item['price'];
                    //TODO: ДИЧАЙШИЙ КОСТЫЛЬ ДЛЯ ВЫВОДА МАТЕРИАЛОВ ИЗ КОПИРОВАННЫХ ЗАЯВОК! До рефакторинга копирования
                    $mater[] = $arr[$i]['mater'];
                }
            }
        }

        $dataTz = [
            'TZ' => serialize($arr),
            'PROBE' => serialize($probe),
            'MATERIAL' => implode(', ', $mater) //переделать
        ];

        $this->updateTzByIdTz($tzId, $dataTz);
    }

    //TODO: Сохранение измененных шифров в ACT_BASE и probe_to_materials
    public function savingShNumbersToActBase(int $dealId, int $actNum) {

        $y_date = $this->getActBase($dealId);

        $sh = 1;
        $probe_arr = [];
        $res_sh_num = $this->DB->Query("SELECT * FROM `MATERIALS_TO_REQUESTS` WHERE `ID_DEAL`= {$dealId}");
        $h = 0;
        while ($probeinform = $res_sh_num->Fetch()) {
            $z = 0;
            $NumProbe = [];
            for ($z = 0; $z < $probeinform['OBIEM']; $z++) {
                $NumProbe[$z] = $actNum . '.' . $sh . '/' . $y_date['year'];
                $sh++;
                $probe_arr[$h] = $NumProbe;
            }
            $h++;
        }
        $probe_arr = json_encode($probe_arr);
        $this->DB->Query("UPDATE `ACT_BASE` SET `PROBE` = '{$probe_arr}' WHERE ID_Z={$dealId}");
    }

    /**
     * @param int $dealId
     * @return array
     */
    public function getCountMaterials(int $dealId): array
    {
        $response = [];

        $result = $this->DB->Query("SELECT count(DISTINCT(umtr.material_id)) count_material 
            FROM ulab_material_to_request umtr WHERE umtr.deal_id = {$dealId} AND umtr.mtr_id <> 0")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * TODO: временный метод пока не будет сделано редактирование ТЗ с заполнеными данными результатов испытаний
     * получить кол-во заполненных данных в результатах испытаний
     * @param int $dealId
     * @return array
     */
    public function getCountFilledResultData(int $dealId): array
    {
        $response = [];

        if (empty($dealId)) {
            return $response;
        }

        $result = $this->DB->Query("SELECT COUNT(*) count_umtr     
            FROM ulab_material_to_request umtr 
            INNER JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id 
            LEFT JOIN ulab_trial_results utr ON utr.gost_to_probe_id = ugtp.id 
            LEFT JOIN PROTOCOLS p ON p.ID = umtr.protocol_id 
            WHERE umtr.deal_id = {$dealId} AND (utr.id IS NOT NULL OR p.ID IS NOT NULL)")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    
    /**
     * @param int $ugtpId
     * @return array
     */
    public function getGostToProbe(int $ugtpId): array
    {
        $response = [];

        if (empty($ugtpId)) {
            return $response;
        }

        $result = $this->DB->Query(
            "SELECT ugtp.*, umtr.probe_number, umtr.material_number, umtr.cipher, umtr.id as umtr_id, umtr.material_id
                    FROM ulab_gost_to_probe as ugtp 
                    inner join ulab_material_to_request as umtr on umtr.id = ugtp.material_to_request_id
                    WHERE ugtp.id = {$ugtpId}"
        )->Fetch();

        if (!empty($result)) {
            $result['measuring_sheet'] = json_decode($result['measuring_sheet'], true);
            if ( empty($result['cipher']) ) {
                $number = $result['probe_number'] + 1;
                $result['cipher'] = "Не присвоен шифр #{$number}";
            }

            $response = $result;
        }

        return $response;
    }


    public function getAssignedGostToProbe($dealId)
    {
        $result = [];

        $response = $this->DB->Query("
            SELECT ugtp.assigned_id
            FROM ulab_material_to_request umtr
            LEFT JOIN  ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id
            WHERE deal_id = {$dealId}
        ");

        while($row = $response->Fetch()) {
            $result[] = $row['assigned_id'];
        }

        return $result;
    }
}
