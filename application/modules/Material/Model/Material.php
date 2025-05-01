<?php

/**
 * Модель для работы с материалами
 * Class Material
 */
class Material extends Model
{
    CONST SIEVE_ARR = ['200', '120', '100', '80', '60', '40', '31,5', '22,4', '20', '16', '15', '11,2', '10', '8',
        '5', '4', '2,5', '2', '1,25', '1', '0,63', '0,5', '0,315', '0,25', '0,16', '0,125', '0,1', '0,071', '0,063',
        '0,05', 'менее 0,05', '5,6', '45', '63', '90', '12,5', '7,5', '25', '50', '30', '87,5', '70', '55', '126',
        '180', '2,8', '3', '17,5', 'менее 0,16', 'менее 0,125', '125', '56 мкм', '20 мкм', '10 мкм', '5 мкм',
        '2 мкм', 'менее 2 мкм', '150', '105', '35', '1000', '850', '700', '600', '500', 'менее 500', 'менее 1 мм',
        '0,8', '0,4', '0,2', '1.6', 'менее 2,5 мм', 'менее 5 мм', 'менее 10 мм', 'менее 20 мм'];

    /**
     * @param bool $isActive
     * @return array
     */
    public function getList($isActive = true): array
    {
        $organizationId = App::getOrganizationId();

        $str = '';
        if ( $isActive ) {
            $str = "and is_active = 1";
        }
        $result = [];
        $materials = $this->DB->Query(
            "SELECT * FROM `MATERIALS` 
            WHERE `NAME` <> '' {$str} AND `organization_id` = {$organizationId} 
            group by `NAME`, `GROUPS`
            ORDER BY `NAME` ASC"
        );

        while ($row = $materials->Fetch()) {
            $row['GROUPS'] = unserialize($row['GROUPS']);

            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param int $id
     */
    public function changeActiveMaterial(int $id): void
    {
        $organizationId = App::getOrganizationId();

        $this->DB->Query("update `MATERIALS` set `is_active` = ! `is_active` where ID = {$id} AND `organization_id` = {$organizationId}");
    }


    /**
     * @param int $materialId
     * @return array|false
     */
    public function getById(int $materialId)
    {
        $organizationId = App::getOrganizationId();

        $material = $this->DB->Query(
            "SELECT * FROM `MATERIALS` WHERE ID = {$materialId} AND `organization_id` = {$organizationId}");

        return $material->Fetch();
    }

	/**
	 * @param int $umtrId
	 */
    public function getMaterialByUmtrId(int $umtrId)
	{
		$material = $this->DB->Query("SELECT material_id FROM `ulab_material_to_request` WHERE id = {$umtrId}");

		return $material->Fetch()['material_id'];
	}

    /**
     * @param $id
     */
    public function deleteMaterial($id)
    {
        $organizationId = App::getOrganizationId();
        $this->DB->Query("delete FROM `MATERIALS` WHERE ID = {$id} AND `organization_id` = {$organizationId}");
    }


    /**
     * получает список групп материалов
     * @param $materialId
     * @return array
     */
    public function getGroupMaterial($materialId)
    {

        $sql = $this->DB->Query(
            "select 
                        mg.id as group_id, mg.name as group_name, mgtu.* 
                    from materials_groups as mg
                    left join materials_groups_tu as mgtu on mg.id = mgtu.materials_groups_id
                    where mg.material_id = {$materialId}
                    order by mg.id"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[$row['group_id']]['name'] = $row['group_name'];
            $result[$row['group_id']]['id'] = $row['group_id'];

            $result[$row['group_id']]['tu'][] = [
                'id' => $row['id'],
                'tu_id' => $row['tu_id'],
                'norm_doc_method_id' => $row['norm_doc_method_id'],
                'comparison_val_1' => $row['comparison_val_1'],
                'val_1' => $row['val_1'],
                'comparison_val_2' => $row['comparison_val_2'],
                'val_2' => $row['val_2'],
            ];
        }

        return $result;
    }


    /**
     * получает список групп материалов
     * @param $normDocId
     * @param int $materialsGroupsId
     * @return array
     */
    public function getGroupMaterialByNormDoc($normDocId, $materialsGroupsId = 0 )
    {
        $organizationId = App::getOrganizationId();

        $where = "";
        if ( !empty($materialsGroupsId) ) {
            $where = "and mgtu.materials_groups_id = {$materialsGroupsId}";
        }

        $sql = $this->DB->Query(
            "select 
                        m.NAME as material_name, m.ID as material_id, mg.id as group_id, mg.name as group_name, mgtu.* 
                    from materials_groups as mg
                    inner join materials_groups_tu as mgtu on mg.id = mgtu.materials_groups_id
                    inner join MATERIALS as m on m.ID = mg.material_id 
                    where mgtu.norm_doc_method_id = {$normDocId} AND m.organization_id = {$organizationId} {$where}
                    order by m.ID, mg.id"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * получает список групп материалов
     * @param $materialId
     * @return array
     */
    public function getGroupMaterialList()
    {
        $organizationId = App::getOrganizationId();

        $sql = $this->DB->Query(
            "select 
                        m.NAME as material_name, m.ID as material_id, mg.id as group_id, mg.name as group_name 
                    from materials_groups as mg
                    inner join MATERIALS as m on m.ID = mg.material_id
                    where m.organization_id = {$organizationId} 
                    order by m.ID, mg.id"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * Добавляем группу материала
     * @param $materialId
     * @param $groupName
     * @return false|mixed|string
     */
    public function addGroupMaterial($materialId, $groupName)
    {
        $groupName = StringHelper::removeSpace($groupName);
        $sqlData = $this->prepearTableData('materials_groups', ['material_id' => $materialId, 'name' => $groupName]);

        return $this->DB->Insert('materials_groups', $sqlData);
    }


    /**
     * Добавляем ТУ в группу материала
     * @param $groupId
     * @param $data
     */
    public function updateTuGroupMaterial($groupId, $data)
    {
        $this->DB->Query("delete from materials_groups_tu where materials_groups_id = {$groupId}");

        foreach ($data as $row) {
            if ( $row['val_1'] == '' ) {
                unset($row['val_1']);
            }
            if ( $row['val_2'] == '' ) {
                unset($row['val_2']);
            }
            $sqlData = $this->prepearTableData('materials_groups_tu', $row);
            $sqlData['materials_groups_id'] = $groupId;

            $this->DB->Insert('materials_groups_tu', $sqlData);
        }
    }


    /**
     * @param $materialId
     * @param $data
     */
    public function updateGroupAndTu($materialId, $data)
    {
        foreach ($data as $groupId => $group) {
            $groupName = StringHelper::removeSpace($group['name']);
            $sqlData = $this->prepearTableData('materials_groups', ['material_id' => $materialId, 'name' => $groupName]);

            $groupId = (int)$groupId;
            $this->DB->Update('materials_groups', $sqlData, "where id = {$groupId}");

            $this->updateTuGroupMaterial($groupId, $group['tu']);
        }
    }


    /**
     * @param $groupId
     */
    public function deleteGroup($groupId)
    {
        if ( empty($groupId) ) { return; }

        $this->DB->Query("delete from materials_groups_tu where materials_groups_id = {$groupId}");

        $this->DB->Query("delete from materials_groups where id = {$groupId}");
    }


    /**
     * @deprecated
     * @param int $dealId
     * @return array
     */
    public function getMaterialsToRequest(int $dealId)
    {
        $result = [];
        $materials = $this->DB->Query(
            "SELECT m.ID id, m.NAME name, mtr.OBIEM count, mtr.ID mtrId 
                    FROM `MATERIALS_TO_REQUESTS` mtr, `MATERIALS` m 
                    WHERE mtr.ID_DEAL = {$dealId} AND mtr.ID_MATERIAL <> 0 AND m.ID = mtr.ID_MATERIAL"
        );

        while ($row = $materials->Fetch()) {
            $row['name'] = htmlentities($row['name']);
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int $dealId
     * @return array
     */
    public function getMaterialProbeToRequest(int $dealId, $materialId = '', $probeId = '')
    {
        $methodsModel = new Methods();
        $probeModel = new Probe();

        $where = '';

        if ( !empty($materialId) ) {
            $where .= "umtr.material_id = {$materialId} and ";
        }
        if ( !empty($probeId) ) {
            $where .= "umtr.id = {$probeId} and ";
        }

        $where .= '1';

        $sql = $this->DB->Query(
            "select 
                        umtr.*,
                        mater.NAME material_name, mater.GROUPS,
                        ugtp.id as ugtp_id, ugtp.new_method_id as method_id, ugtp.measuring_sheet,  
                        ugtp.tech_condition_id as condition_id, ugtp.assigned_id, ugtp.price, ugtp.gost_number, 
                        res.actual_value 
                    from ulab_material_to_request as umtr
                    left join MATERIALS as mater on mater.ID = umtr.material_id
                    left join ulab_gost_to_probe as ugtp on ugtp.material_to_request_id = umtr.id
                    left join ulab_trial_results as res on res.gost_to_probe_id = ugtp.id
                    where umtr.deal_id = {$dealId} and {$where}
                    order by umtr.id, ugtp.gost_number
                    "
        );

        $result = [];
        $labInfo = [];
        while ($row = $sql->Fetch()) {

            if ( !isset($result[$row['material_id']]['price']) ) {
                $result[$row['material_id']]['price'] = 0;
            }

            $result[$row['material_id']]['material_id'] = $row['material_id'];
            $result[$row['material_id']]['mtr_id'] = $row['mtr_id'];
            $result[$row['material_id']]['deal_id'] = $row['deal_id'];
            $result[$row['material_id']]['material_id'] = $row['material_id'];
            $result[$row['material_id']]['price'] += $row['price'];

            $result[$row['material_id']]['material_name'] = $row['material_name'];
            $result[$row['material_id']]['groups'] = unserialize($row['GROUPS']);

            if ( !isset($result[$row['material_id']]['probe'][$row['id']]['price']) ) {
                $result[$row['material_id']]['probe'][$row['id']]['price'] = 0;
            }

            if ( !isset($result[$row['material_id']]['probe'][$row['id']]['number_method']) ) {
                $result[$row['material_id']]['probe'][$row['id']]['number_method'] = 0;
            }

            $count = $row['probe_number'] + 1;
            $result[$row['material_id']]['probe'][$row['id']]['cipher'] = empty($row['cipher'])? 'Не присвоен шифр #'.$count : $row['cipher'];
            $result[$row['material_id']]['probe'][$row['id']]['probe_number'] = $row['probe_number'];
            $result[$row['material_id']]['probe'][$row['id']]['name_for_protocol'] = htmlentities($row['name_for_protocol']);
            $result[$row['material_id']]['probe'][$row['id']]['group'] = $row['group'];
            $result[$row['material_id']]['probe'][$row['id']]['place'] = htmlentities($row['place']);
            $result[$row['material_id']]['probe'][$row['id']]['price'] += $row['price'];

            if ( empty($row['ugtp_id']) ) { $row['ugtp_id'] = 'new_0'; }

            $methodData = $methodsModel->get($row['method_id']);

            if ( !isset($labInfo[$row['id']]) ) {
                $labInfo[$row['id']] = $methodData['lab_info'];
            } else {
                $labInfo[$row['id']] = array_merge($labInfo[$row['id']], $methodData['lab_info']);
            }

            if ( !empty($labInfo[$row['id']]) ) {
                $labInfo[$row['id']] = array_unique($labInfo[$row['id']], SORT_REGULAR);
            }

            $result[$row['material_id']]['probe'][$row['id']]['is_in_act'] = $row['in_act'];
            $result[$row['material_id']]['probe'][$row['id']]['lab_info'] = $labInfo[$row['id']];
            $result[$row['material_id']]['probe'][$row['id']]['state'] = $row['state'];
            $result[$row['material_id']]['probe'][$row['id']]['user_status'] = $probeModel->getAcceptStatusUser($row['id'], App::getUserId());

            $acceptStatus = $probeModel->getAcceptStatus($row['id']);
            $acceptUser = [];
            foreach ($acceptStatus as $status) {
                if ( $status['accept_probe'] == 1 ) {
                    $acceptUser[] = $status['user_info'];
                }
            }

            if ( !empty($acceptUser) ) {
                $result[$row['material_id']]['probe'][$row['id']]['state'] = "Проба принята: " . implode(', ', array_column($acceptUser, 'short_name'));
            }


            if ( !empty($methodData) ) {
                $result[$row['material_id']]['probe'][$row['id']]['number_method']++;
            }

            if ( isset($row['price']) && !is_null($row['price']) ) {
                $methodData['price'] = $row['price'];
            }

            $methodData['gost_number'] = $row['gost_number'];

            $methodData['is_have_result'] = 0;
            $sheet = json_decode($row['measuring_sheet'], true);
            $actualVal = json_decode($row['actual_value'], true);
            if ( !empty($sheet) || $actualVal[0] != '' ) {
                $methodData['is_have_result'] = 1;
            }

            $methodData['assigned_id'] = $row['assigned_id'];
            $methodData['ugtp_id'] = $row['ugtp_id'];

            $result[$row['material_id']]['probe'][$row['id']]['method'][] = $methodData;

            $result[$row['material_id']]['probe'][$row['id']]['condition'][] = $row['condition_id'];
        }

        return $result;
    }


    /**
     * mtr = MATERIALS_TO_REQUESTS
     * @param $oldMtrIdList
     * @param $newMtrIdList
     */
    public function copyProbeAndGostByMtr($oldMtrIdList, $newMtrIdList)
    {
        foreach ($oldMtrIdList as $i => $oltId) {
            $stmpOld = $this->DB->Query("SELECT id FROM probe_to_materials WHERE material_request_id = {$oltId}");

            $dataNewPtm = [
                'material_request_id' => $newMtrIdList[$i]
            ];

            while ($row = $stmpOld->Fetch()) {
                $newProbeId = $this->DB->Insert('probe_to_materials', $dataNewPtm);

                $stmpOldGost = $this->DB->Query("SELECT gost_method, gost_conditions, price FROM gost_to_probe WHERE probe_id = {$row['id']}");

                while ($rowGost = $stmpOldGost->Fetch()) {
                    $dataNewProbeGost = [
                        'probe_id' => $newProbeId,
                        'gost_method' => $rowGost['gost_method'],
                        'gost_conditions' => $rowGost['gost_conditions'],
                        'price' => $this->quoteStr($this->DB->ForSql($rowGost['price'])),
                    ];

                    // TODO: новая область
                    if (App::getUserId() == 61) {
                        $gostData['new_method_id'] = $rowGost['gost_method'];
                    }

                    $this->DB->Insert('gost_to_probe', $dataNewProbeGost);
                }
            }
        }
    }


    /**
     * @param $dealId
     * @param $dataList
     * @return array
     */
    public function setMaterialToRequest($dealId, $dataList, $additionalData = false)
    {
        // TODO: не помню зачем, возможно не надо
//        $this->DB->Query("DELETE FROM `MATERIALS_TO_REQUESTS` WHERE ID_DEAL = {$dealId}");

        $mtrArrayId = [];

        $existingWorkIds = [];
        if ($additionalData) {
            foreach ($additionalData as $data) {
                if (!empty($data['id'])) {
                    $existingWorkIds[] = $data['id'];
                }
            }
            
            if (!empty($existingWorkIds)) {
                $existingWorkIdsStr = implode(',', $existingWorkIds);
                $this->DB->Query("DELETE FROM ulab_material_to_request WHERE deal_id = {$dealId} AND work_id NOT IN ({$existingWorkIdsStr})");
                $this->DB->Query("DELETE FROM government_work WHERE deal_id = {$dealId} AND id NOT IN ({$existingWorkIdsStr})");
            } else {
                $this->DB->Query("DELETE FROM ulab_material_to_request WHERE deal_id = {$dealId}");
                $this->DB->Query("DELETE FROM government_work WHERE deal_id = {$dealId}");
            }
        }

        $m_number = 1;
        foreach ($dataList as $key => $material) {
            $k = 1;

            $data = [
                'ID_DEAL' => $dealId,
                'ID_MATERIAL' => $material['id'],
                'OBIEM' => $material['count'],
                'NAME_MATERIAL' => $material['name'],
            ];
            // TODO: устарело
            $sqlData = $this->prepearTableData('MATERIALS_TO_REQUESTS', $data);
            $mtrId = $this->DB->Insert('MATERIALS_TO_REQUESTS', $sqlData);
            $mtrArrayId[] = $mtrId;

            if ($additionalData) {
                $workId = 0;
                
                if (!empty($additionalData[$key]['id'])) {
                    $workId = $additionalData[$key]['id'];
                    $additionalData[$key]['deal_id'] = $dealId;
                    $sqlAdditionalData = $this->prepearTableData('government_work', $additionalData[$key]);
                    $this->DB->Update('government_work', $sqlAdditionalData, "WHERE id = {$workId}");
                    
                    $this->DB->Query("UPDATE ulab_material_to_request SET material_id = {$material['id']} WHERE deal_id = {$dealId} AND work_id = {$workId}");
                } else {
                    $additionalData[$key]['deal_id'] = $dealId;
                    $sqlAdditionalData = $this->prepearTableData('government_work', $additionalData[$key]);
                    $workId = $this->DB->Insert('government_work', $sqlAdditionalData);
                }
                
                if ($workId > 0) {
                    $this->DB->Query("DELETE FROM ulab_material_to_request WHERE deal_id = {$dealId} AND work_id = {$workId}");
                }
            }

            $dataProbe = [
                'mtr_id' => $mtrId,
                'deal_id' => $dealId,
                'material_id' => $material['id'],
                'material_number' => $m_number,
                'work_id' => $workId
            ];

            for ($i = 0; $i < $material['count']; $i++) {
                $dataProbe['probe_number'] = $k;
                $sqlDataProbe = $this->prepearTableData('ulab_material_to_request', $dataProbe);
                $this->DB->Insert('ulab_material_to_request', $sqlDataProbe);
                $k++;
            }

            $m_number++;
        }

        return $mtrArrayId;
    }


    /**
     * @param $name
     * @return false|mixed|string
     */
    public function add($name)
    {
        $organizationId = App::getOrganizationId();
        $name = StringHelper::removeSpace($name);
        $name = $this->DB->ForSql($name);

        $sql = $this->DB->Query("select ID from MATERIALS 
            where organization_id = {$organizationId} and NAME like '{$name}'")->Fetch();

        if (!empty($sql['ID'])) {
            return intval($sql['ID']);
        }

        $data = [
            'NAME' => $name,
            'organization_id' => App::getOrganizationId()
        ];
        $sqlData = $this->prepearTableData('MATERIALS', $data);

        $result = $this->DB->Insert('MATERIALS', $sqlData);

        return intval($result);
    }


    /**
     * @param int $materialId
     * @return array
     */
    public function getBaGostByMaterialId(int $materialId): array
    {
        $result = [];

        //$baGost = $this->DB->Query("SELECT * FROM ba_gost WHERE `NUM_OA_NEW` > '0' AND `NON_ACTUAL` <> 1 AND `DOP` ={$materialId} GROUP BY GOST");
        $baGost = $this->DB->Query("SELECT * FROM ba_gost WHERE `NUM_OA_NEW` <> 0 AND `NON_ACTUAL` <> 1 AND `DOP` ={$materialId} GROUP BY GOST");

        while ($row = $baGost->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    public function deleteProbe($materialRequestId)
    {
        $this->DB->Query("DELETE FROM probe_to_materials WHERE material_request_id = {$materialRequestId}");
    }

    /**
     * @param $data
     * @return false|mixed|string
     */
    public function probeAdd($data) {
        $probeData = [
            'material_request_id' => $data['material_request_id'],
            'cipher' => $this->quoteStr($this->DB->ForSql(trim($data['cipher']))),
        ];

        return $this->DB->Insert('probe_to_materials', $probeData);
    }


    /**
     * @param $probeId
     * @return array
     */
    public function getGostByProbe($probeId)
    {
        $gost = $this->DB->Query("SELECT * FROM `gost_to_probe` WHERE `probe_id` = {$probeId}");

        $result = [];

        while ($row = $gost->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param $data
     * @return false|mixed|string
     */
    public function gostAdd($data) {
        $gostData = [
            'probe_id' => $data['probe_id'],
            'gost_method' => $data['gost_method'],
            'new_method_id' => $data['gost_method'],
            'gost_conditions' => $data['gost_conditions'],
            'price' => $data['price']
        ];

        return $this->DB->Insert('gost_to_probe', $gostData);
    }


    public function fillCipher($dealId = 0)
    {
        $where = "1";
        if ( $dealId > 0 ) {
            $where = "mtr.ID_DEAL = {$dealId}";
        }

        $stmpMtr = $this->DB->Query(
            "SELECT 
                        @row_number:=CASE
                            WHEN @deal = mtr.ID_DEAL THEN @row_number + 1
                            ELSE 1
                        END AS num,
                        @deal:=mtr.ID_DEAL as ID_DEAL, ptm.id AS ptm_id, mtr.ID mtr_id, mtr.ID_MATERIAL,
                        ab.ACT_NUM, ab.ACT_DATE 
                    FROM 
                        MATERIALS_TO_REQUESTS AS mtr, ACT_BASE AS ab, 
                        probe_to_materials as ptm, 
                        (SELECT @deal:=0,@row_number:=0) as t
                    WHERE 
                        mtr.ID_DEAL = ab.ID_Z AND ptm.material_request_id = mtr.ID AND {$where}
                    ORDER BY 
                        mtr.ID_DEAL, mtr.ID, ptm.id"
        );

        while ($row = $stmpMtr->Fetch()) {
            $date = strtotime($row['ACT_DATE']);
            $year = date("Y", $date)%10 ? substr(date("Y", $date), -2) : date("Y", $date);

            $cipher = $row['ACT_NUM'] . '.' . $row['num'] . '/' . $year;

            //echo $cipher . '<br>';

            $date = [
                'cipher' => $this->quoteStr($cipher)
            ];

            $this->DB->Update("probe_to_materials", $date, "WHERE id = {$row['ptm_id']}");
        }
    }

    /**
     * добавить шифр для новой таблицы ulab_material_to_request
     * @param int $dealId
     */
    public function addCipher($dealId = 0)
    {
        $where = "1";
        if ( $dealId > 0 ) {
            $where = "umtr.deal_id = {$dealId}";
        }

        $umtr = $this->DB->Query(
            "SELECT
               @row_number:=CASE
                WHEN @deal = umtr.deal_id THEN @row_number + 1
                ELSE 1
            END AS num,
              @deal:=umtr.deal_id as deal_id, umtr.id umtr_id, umtr.material_id, ab.ACT_NUM, ab.ACT_DATE, @row_number, @deal
            FROM 
              ulab_material_to_request AS umtr, 
              ACT_BASE AS ab, 
              (SELECT @deal:=0,@row_number:=0) as t
            WHERE 
              umtr.deal_id = ab.ID_Z AND umtr.mtr_id <> 0 AND {$where} 
            ORDER BY 
              umtr.material_number, umtr.id"
        );

        $i = 1;
        while ($row = $umtr->Fetch()) {
            $date = strtotime($row['ACT_DATE']);
            $year = date("Y", $date) % 10 ? substr(date("Y", $date), -2) : date("Y", $date);

            if (!empty($row['cipher'])) {
                continue;
            }

            $cipher = $row['ACT_NUM'] . '.' . $i . '/' . $year;

            $dateProbe = [
                'cipher' => "'{$cipher}'"
            ];

            $this->DB->Update("ulab_material_to_request", $dateProbe, "WHERE id = {$row['umtr_id']}");
            $i++;
        }
    }

    public function getZern()
    {
        $result = [];

        $res = $this->DB->Query("SELECT * FROM ZERN");

        while ($row = $res->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getSieveAndNorm($id)
    {
        $result = [];

        $res = $this->DB->Query("SELECT * FROM ZERN WHERE ID = {$id}")->Fetch();

        $norm1 = unserialize($res['NORM1']);
        $norm2 = unserialize($res['NORM2']);
        $sieve = [];
        $norm_from = [];
        $norm_to = [];
        $m = [];



        foreach ($norm1 as $key => $val) {
            if ($val == '') {
                continue;
            }
            $norm_from[] = $val;
            $norm_to[] = $norm2[$key];
            $sieve[] = (float)str_replace(',', '.', self::SIEVE_ARR[$key]);
        }



        arsort($sieve);

        foreach ($sieve as $k => $item) {
            if (stripos($sieve[$k], 'менее') !== false) {
                $m[] = [
                    'name' => str_replace('.', ',', $sieve[$k]),
                    'nf1' => $norm_from[$k],
                    'nt1' => $norm_to[$k],
                ];
            } else {
                $sieve1[] = str_replace('.', ',', $sieve[$k]);
                $norm_from1[] = $norm_from[$k];
                $norm_to1[] = $norm_to[$k];
            }
        }

        foreach ($m as $val) {
            array_push($sieve1, $val['name']);
            array_push($norm_from1, $val['nf1']);
            array_push($norm_to1, $val['nt1']);
        }

        $result = [
            'NAME' => $res['NAME'],
            'norm_from' => $norm_from1,
            'norm_to' => $norm_to1,
            'sieve' => $sieve1,
        ];

        return $result;
    }


    /**
     * @param $matID
     * @return array
     */
    public function getGostToMaterialByMatID($matID) {

        $result = [];

        $res = $this->DB->Query(
            "SELECT 
                        m.*, 
                        g.reg_doc, g.year, g.description, g.materials
                    FROM `gost_to_material` gm
                    inner join `ulab_methods` as m on gm.method_id = m.id 
                    inner join `ulab_gost` as g on g.id = m.gost_id
                    WHERE gm.`material_id` = {$matID} ");

        while ($row = $res->Fetch()) {
            $strYear = !empty($row['year']) ? "-{$row['year']}" : '';
            $strClause = !empty($row['clause']) ? " {$row['clause']}" : '';
            $row['view_gost'] = "{$row['reg_doc']}{$strYear}{$strClause} | {$row['name']}";

            $result[] = $row;
        }
        return $result;
    }


    /**
     * @param $id
     * @param $data
     */
    public function setGostToMaterial($id, $data)
    {
        $this->DB->Query("DELETE FROM `gost_to_material` WHERE `material_id` = {$id}");

        foreach ($data as $gost) {

            $dataGost = [
                'method_id' => $gost,
                'material_id' => $id,
            ];

            $sqlData = $this->prepearTableData('gost_to_material', $dataGost);
            $this->DB->Insert('gost_to_material', $sqlData);

        }
    }


    /**
     * @param array $filter
     * @return mixed
     */
    public function getDatatoJournalMaterial(array $filter = [])
    {
        $organizationId = App::getOrganizationId();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'm.NAME',
            'dir' => 'DESC'
        ];

        if ( !empty($filter['search']) ) {
            // Заявка
            if (isset($filter['search']['NAME'])) {
                $where .= "m.NAME LIKE '%{$filter['search']['NAME']}%' AND ";
            }
            if (isset($filter['search']['is_active'])) {
                $where .= "m.is_active = {$filter['search']['is_active']} AND ";
            }
        }

        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }
            switch ($filter['order']['by']) {
                case 'requestTitle':
                    $order['by'] = 'm.NAME';
                    break;
                case 'is_active':
                    $order['by'] = 'm.is_active';
                    break;
            }
        }

        if (isset($filter['paginate'])) {
            $offset = 0;
            if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                $length = $filter['paginate']['length'];

                if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                    $offset = $filter['paginate']['start'];
                }
                $limit = "LIMIT {$offset}, {$length}";
            }
        }

        $where .= "m.organization_id = {$organizationId}";

        $data = $this->DB->Query(
            "SELECT * 
                    FROM MATERIALS m                   
                    WHERE `NAME` <> '' and `NAME` is not null and {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT m.ID, m.NAME 
                    FROM MATERIALS m 
                    where `NAME` <> '' and `NAME` is not null AND m.organization_id = {$organizationId}"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT m.ID, m.NAME 
                    FROM MATERIALS m 
                    where `NAME` <> '' and `NAME` is not null and {$where}
                    "
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * @param $id
     * @param $data
     */
    public function updateScheme($id, $data)
    {
        $dataSql = $this->prepearTableData('ulab_material_scheme', $data);
        $this->DB->Update('ulab_material_scheme', $dataSql, "where id = {$id}");
    }

    /**
     * @param $id
     * @param $data
     */
    public function addNewScheme($data)
    {
        $dataSql = $this->prepearTableData('ulab_material_scheme', $data);
        return $this->DB->Insert('ulab_material_scheme', $dataSql);
    }


    /**
     * @param $id
     * @param $data
     */
    public function addGostToScheme($id, $data)
    {
        $this->DB->Query("delete from ulab_scheme_param where scheme_id = {$id}");

        if (!empty($data)) {
            foreach ($data as $gost) {
                $gost['scheme_id'] = $id;

                $sqlData = $this->prepearTableData('ulab_scheme_param', $gost);
                $this->DB->Insert('ulab_scheme_param', $sqlData);
            }
        }
    }


    /**
     * @param $id
     */
    public function deleteScheme($id)
    {
        $this->DB->Query("delete from ulab_scheme_param where scheme_id = {$id}");
        $this->DB->Query("delete from ulab_material_scheme where id = {$id}");
    }


    /**
     * @param $materialId
     * @return array
     */
    public function getSchemeByMaterial($materialId)
    {
        $result = [];
        $param = [];

        $res = $this->DB->Query("SELECT ms.id, ms.name, 
						sp.method_id, sp.tu_id, sp.nd_id
                    FROM `ulab_material_scheme` as ms
					LEFT JOIN `ulab_scheme_param` as sp ON sp.scheme_id = ms.id
                    WHERE ms.`material_id` = {$materialId}");

        while ($row = $res->Fetch()) {
            $param[$row['id']][] = [
                'method_id' => $row['method_id'],
                'tu_id' => $row['tu_id'],
                'nd_id' => $row['nd_id'],
            ];

            $result[$row['id']] = [
                'id' => $row['id'],
                'name' => $row['name']
            ];

            $result[$row['id']]['param'] = $param[$row['id']];
        }

        return $result;
    }


    /**
     * @param $schemeId
     * @return array
     */
    public function getSchemeParam($schemeId)
    {
        $result = [];

        $res = $this->DB->Query(
            "SELECT *
                    FROM `ulab_scheme_param`
                    WHERE `scheme_id` = {$schemeId}"
        );

        while ($row = $res->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param int $id_material
     * @param string $name
     * @return bool
     */
    public function setName(int $id_material, string $name): bool
    {
        $organizationId = App::getOrganizationId();
        $sqlData = $this->prepearTableData('MATERIALS', ['NAME' => $name]);

        $where = "WHERE ID = {$id_material} AND organization_id = {$organizationId}";
        return (bool)$this->DB->Update('MATERIALS', $sqlData, $where);
    }

    /**
     * @return array
     */
    public function getMaterialsKeyValue(): array
    {
        $organizationId = App::getOrganizationId();

        $materials = [];
        $res = $this->DB->Query("SELECT `ID`, `NAME` FROM `MATERIALS` WHERE `organization_id` = {$organizationId}");

        while ($row = $res->Fetch()) {
            $materials[$row['ID']] = $row['NAME'];
        }

        return $materials;
    }

    /**
     * @param int $id_material
     * @param string $name
     */
    public function setNewName(int $id_material, string $name)
    {
        $data = [
            'NAME' => $name
        ];
        $sqlData = $this->prepearTableData('MATERIALS', $data);

        $this->DB->Update('MATERIALS', $sqlData, "WHERE ID = {$id_material}");
    }
}