<?php

/**
 * Модель для работы с ГОСТами
 * Class Gost
 */
class Gost extends Model
{
    /**
     * @param $data
     * @return array
     */
    private function prepearGostData($data)
    {
        $columns = $this->getColumnsByTable('ulab_gost');

        $sqlData = [];

        $data['reg_doc'] = StringHelper::removeSpace($data['reg_doc']);

        foreach ($columns as $column) {
            if ( $column == 'id' ) {
                continue;
            }
            if ($column === 'year') {
                if (isset($data[$column]) && is_numeric($data[$column])) {
                    $sqlData[$column] = intval($data[$column]);
                } else {
                    $sqlData[$column] = "NULL";
                }
                continue;
            }

            if ( isset($data[$column]) ) {
                $sqlData[$column] = $this->quoteStr($this->DB->ForSql(trim($data[$column])));
            }
        }

        return $sqlData;
    }


    /**
     * @param $data
     * @return false|mixed|string
     */
    public function addGost($data)
    {
        $data["organization_id"] = App::getOrganizationId();
        $sqlData = $this->prepearGostData($data);

        $historyModel = new History();
        $userModel = new User();

        $user = $userModel->getUserData(App::getUserId());

        $dataHistory = [
            'DATE' => date('Y-m-d H:i:s'),
            'TYPE' => "Сздан новый ГОСТ: {$data['reg_doc']}",
            'USER_ID' => App::getUserId(),
            'ASSIGNED' => $user['user_name'],
            'organization_id' => App::getOrganizationId(),
        ];

        $historyModel->addHistory($dataHistory);

        return $this->DB->Insert('ulab_gost', $sqlData);
    }


    /**
     * Копирует ГОСТ.
     * @param $id - ид госта источника
     */
    public function copy($id)
    {
        $data = $this->getGost($id);

        $data['reg_doc'] = 'КОПИЯ ' . $data['reg_doc'];

        $newId = $this->addGost($data);

        if ( (int)$newId > 0 ) {
            $methodModel = new Methods();

            $methodList = $methodModel->getListByGostId($id);

            foreach ($methodList as $method) {
                $methodModel->copyMethod($method['id'], $newId);
            }
        }

        $historyModel = new History();
        $userModel = new User();

        $user = $userModel->getUserData(App::getUserId());

        $dataHistory = [
            'DATE' => date('Y-m-d H:i:s'),
            'TYPE' => "Скопирован ГОСТ. ид: {$id}",
            'USER_ID' => App::getUserId(),
            'ASSIGNED' => $user['user_name'],
            'organization_id' => App::getOrganizationId(),
        ];

        $historyModel->addHistory($dataHistory);

        return $newId;
    }

    /**
     * @param $idGost
     * @return array|false
     */
    public function getGost($idGost)
    {
        $organizationId = App::getOrganizationId();
        return $this->DB->Query("select * from `ulab_gost` where id = {$idGost} and organization_id = {$organizationId}")->Fetch();
    }

    /**
     * @param $id
     */
    public function deletePermanentlyGost($id)
    {
        $organizationId = App::getOrganizationId();
        $methodModel = new Methods();
        $methodList = $methodModel->getListByGostId($id);
        foreach ($methodList as $method) {
            $methodModel->deletePermanentlyMethod($method['id']);
        }
        $this->DB->Query("delete from ulab_gost where id = {$id} and organization_id = {$organizationId}");
    }


    /**
     * @param $id
     * @param $data
     * @return false|mixed|string
     */
    public function updateGost($id, $data)
    {
        $organizationId = App::getOrganizationId();
        $data["organization_id"] = $organizationId;
        $sqlData = $this->prepearGostData($data);

        $historyModel = new History();
        $userModel = new User();

        $user = $userModel->getUserData(App::getUserId());

        $dataHistory = [
            'DATE' => date('Y-m-d H:i:s'),
            'TYPE' => "Отредактирован ГОСТ. ид: {$id}",
            'USER_ID' => App::getUserId(),
            'ASSIGNED' => $user['user_name'],
            'organization_id' => $organizationId,
        ];

        $historyModel->addHistory($dataHistory);

        $where = "WHERE id = {$id} and organization_id = {$organizationId}";
        return $this->DB->Update('ulab_gost', $sqlData, $where);
    }


    /**
     * @return array
     */
    public function getUlabGostList()
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $sql = $this->DB->Query("SELECT * FROM ulab_gost WHERE organization_id = {$organizationId}");

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }





    /**
     * @deprecated
     * @return array
     */
    public function getList(): array
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $baGost = $this->DB->Query("SELECT * FROM ba_gost WHERE `NUM_OA_NEW` <> '0' AND `NON_ACTUAL` <> 1 AND organization_id = {$organizationId}");

        while ($row = $baGost->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * добавление в старый ba_gost
     * @deprecated
     * @param array $data
     * @return int
     */
    public function add(array $data): int
    {
        $data['organization_id'] = App::getOrganizationId();
        foreach ($data as $key => $item) {
            if (is_string($item)) {
                $data[$key] = $this->quoteStr($this->DB->ForSql(trim($item)));
            }
        }

        $result = $this->DB->Insert('ba_gost', $data);

//        echo'<pre>';var_dump('$result', $result);echo'</pre>';

        return intval($result);
    }


    /**
     * @deprecated
     * @param int $gostId
     * @param array $data
     */
    public function update(int $gostId, array $data)
    {
        $data['organization_id'] = App::getOrganizationId();
        foreach ($data as $key => $item) {
            if (is_string($item)) {
                $data[$key] = $this->quoteStr($this->DB->ForSql(trim($item)));
            }
        }

        $where = "WHERE ID = {$gostId}";
        $this->DB->Update('ba_gost', $data, $where);
    }


    public function getListAndPricesByDealId(int $dealId, $tu = false)
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        if ( $tu ) {
            $where = " ulab_method_id is null ";
        } else {
            $where = " ((`IN_OA` <> '0' AND ulab_method_id is not null) or (`IN_OA` = '0' and ulab_method_id is null and `NUM_OA_NEW` <> 0)) ";
        }

        $baGost = $this->DB->Query("SELECT distinct * FROM ba_gost WHERE  `NON_ACTUAL` <> 1 AND organization_id = {$organizationId} AND {$where}");


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

            $gostName = !empty($row['GOST']) ? trim(preg_replace('/\s+/', ' ',$row['GOST'])) : '';
            $gostYear = !empty($row['GOST_YEAR']) ? '-' . trim(preg_replace('/\s+/', ' ',$row['GOST_YEAR'])) : '';
            $gostPunkt = !empty($row['GOST_PUNKT']) ? ' ' . trim(preg_replace('/\s+/', ' ',$row['GOST_PUNKT'])) : '';
            $gostSpecification = !empty($row['SPECIFICATION']) ? ' | ' . trim(preg_replace('/\s+/', ' ',$row['SPECIFICATION'])) : '';

            $row['view_gost'] = $gostName . $gostYear . $gostPunkt . $gostSpecification;

            $result[] = $row;
        }

        return $result;
    }


    public function getUlabGostAndPrice($dealId = 0)
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        // TODO: пока без цены
        $gostSql = $this->DB->Query(
            "SELECT g.*, m.*, m.id method_id, mp.name mp_name 
                    FROM ulab_methods m 
                    inner join ulab_gost g on m.gost_id = g.id
                    left join ulab_measured_properties mp on m.measured_properties_id = mp.id
                    WHERE m.gost_id = g.id AND g.organization_id = {$organizationId}");

        while ($row = $gostSql->Fetch()) {
            if ( !empty($row['mp_name']) ) {
                $row['name'] = $row['mp_name'];
            } else {
                $row['name'] = StringHelper::removeSpace($row['name']);
            }

            $row['clause'] = StringHelper::removeSpace($row['clause']);

            $strYear = !empty($row['year']) ? "-{$row['year']}" : '';
            $strClause = !empty($row['clause']) ? " {$row['clause']}" : '';
            $result[] = [
                'PRICE' => $row['price'],
                'ID' => $row['method_id'],
                'NORM_TEXT' => $row['is_text_norm'],
                'view_gost' => "{$row['reg_doc']}{$strYear}{$strClause} | {$row['name']}",
            ];
        }

        return $result;
    }


	/**
	 * @param $idGost
	 * @return mixed
	 */
	public function getTuByGostID($idGost)
    {
        $organizationId = App::getOrganizationId();
        return $this->DB->Query("SELECT `ID_TU` FROM `ba_gost` WHERE `ID` = {$idGost} AND organization_id = {$organizationId}")->Fetch();
	}

	/**
	 * @param $idGost
	 * @return mixed
	 */
	public function getGostForOption($idGost)
    {
        $organizationId = App::getOrganizationId();
		$tu = $this->DB->Query("SELECT `NORM_TEXT`, `ID`, `GOST`, `SPECIFICATION`, `GOST_YEAR`, `GOST_PUNKT` 
								FROM `ba_gost` WHERE `ID` = {$idGost} AND organization_id = {$organizationId}")->Fetch();

		$gostName = !empty($tu['GOST']) ? trim($tu['GOST']) : '';
		$gostYear = !empty($tu['GOST_YEAR']) ? '-' . trim($tu['GOST_YEAR']) : '';
		$gostPunkt = !empty($tu['GOST_PUNKT']) ? ' ' . trim($tu['GOST_PUNKT']) : '';
		$gostSpecification = !empty($tu['SPECIFICATION']) ? ' | ' . trim($tu['SPECIFICATION']) : '';

		$view = $gostName . $gostYear . $gostPunkt . $gostSpecification;

		$tu['view_gost'] = $view;

		return $tu;
	}


	/**
	 * @param $list
	 * @return array
	 */
	public function getAssignedByGostList($list)
	{
        $organizationId = App::getOrganizationId();
		$a = [];

		if (!empty($list)) {
			$user = new User();
			$gostArr = [];
			foreach ($list as $item) {
				foreach ($item as $gost) {
				    if ( empty($gost['id']) ) { continue; }
					$gostArr[] = $gost['id'];
				}
			}

			$arr = implode(',', $gostArr);

			if ( empty($arr) ) {
			    return [];
            }

			$gostsArr = $this->DB->Query("SELECT * FROM `ba_gost` WHERE `ID` IN ({$arr}) AND organization_id = {$organizationId}");

			while ($gost_assigned = $gostsArr->Fetch()) {
				$assigned = unserialize($gost_assigned['ASSIGNED']);
				foreach ($assigned as $val) {
					$name = $user->getUserById($val);
					$a[$gost_assigned['ID']][] = [
						'id' => $val,
						'name' => StringHelper::shortName($name['NAME']) . '. ' . $name['LAST_NAME']
					];
				}
			}
		}

        return $a;
	}


    /**
     * @param $list
     * @return array
     */
    public function getUlabAssignedByGostList($list)
    {
        $a = [];

        if (!empty($list)) {
            $user = new User();

            foreach ($list as $item) {
                foreach ($item as $gost) {
                    foreach ($gost['assigned_list'] as $ass) {
                        $name = $user->getUserById($ass);
                        $a[$gost['id']][] = [
                            'id' => $ass,
                            'name' => StringHelper::shortName($name['NAME']) . '. ' . $name['LAST_NAME']
                        ];
                    }
                }
            }
        }

        return $a;
    }


	public function getAssignedByGostID($gostId)
	{
        $organizationId = App::getOrganizationId();
		$user = new User();

		$a = [];

		$gostsArr = $this->DB->Query("SELECT * FROM `ba_gost` WHERE `ID` = {$gostId} AND organization_id = {$organizationId}");

		while ($gost_assigned = $gostsArr->Fetch()) {
			$assigned = unserialize($gost_assigned['ASSIGNED']);
			foreach ($assigned as $val) {
				$name = $user->getUserById($val);
				$a[] = [
					'id' => $val,
					'name' => StringHelper::shortName($name['NAME']) . '. ' . $name['LAST_NAME']
				];
			}
		}

		return $a;
	}


    public function getUlabAssignedByGostID($gostId)
    {
        $userModel = new User();
        $methodModel = new Methods();

        $a = [];

        $assigned = $methodModel->getAssigned($gostId);

        foreach ($assigned as $val) {
            $name = $userModel->getUserById($val);
            $a[] = [
                'id' => $val,
                'name' => StringHelper::shortName($name['NAME']) . '. ' . $name['LAST_NAME']
            ];
        }

        return $a;
    }


    /**
     * @param $dealID
     * @return array
     */
    public function getGostMaterialByDealID($dealID)
	{
        $organizationId = App::getOrganizationId();
		$result = [];

		$res = $this->DB->Query(
		    "select mtr.material_id, m.id as m_id, mtr.ID, m.name as method_name, mat.NAME, g.reg_doc, m.clause, count(mtr.material_id) as amount,
			    gtp.price as price, tc.reg_doc as tcName, tc.measured_properties_name
            from ulab_material_to_request mtr
            inner join ulab_gost_to_probe gtp ON mtr.id = gtp.material_to_request_id
            inner join `ulab_methods` as m on m.id = gtp.new_method_id
            inner join MATERIALS as mat ON mtr.material_id = mat.ID
            left join `ulab_tech_condition` as tc on tc.id = gtp.tech_condition_id
            inner join `ulab_gost` as g on g.id = m.gost_id 
            left join `ulab_dimension` as d on d.id = m.unit_id
            left join ulab_measured_properties as p on p.id = m.measured_properties_id 
            where mtr.deal_id = {$dealID} and g.organization_id = {$organizationId}
            group by mtr.material_id, gtp.method_id, gtp.tech_condition_id, gtp.id order by mtr.material_number, mtr.material_id, mtr.ID, gtp.gost_number
		");

		while ($row = $res->Fetch()) {
			$result[] = [
				'mtr_id' => $row['ID'],
				'material_id' => $row['material_id'],
				'amount' => $row['amount'],
				'm_id' => $row['m_id'],
				'material_name' => $row['NAME'],
				'method_name' => $row['method_name'],
				'gost_name' => $row['reg_doc'] . ' ' . $row['clause'],
				'price' =>  number_format($row['price'], 2, ',', ''),
				'tech_condition' => !empty($row['tcName']) ? $row['tcName'] . ' | ' . $row['measured_properties_name'] : '',
				'sum' => number_format($row['price'] * $row['amount'], 2, ',', ''),
			];
		}

		return $result;
	}

	
	public function getMaterialByUgtpId($id)
	{
        $organizationId = App::getOrganizationId();
		return $this->DB->Query("SELECT umtr.`cipher`, umtr.`name_for_protocol`, m.`NAME` FROM `ulab_gost_to_probe` ugtp
 									INNER JOIN `ulab_material_to_request` umtr ON ugtp.`material_to_request_id` = umtr.`id`
 									LEFT JOIN `MATERIALS` m ON umtr.`material_id` = m.`ID`
 									WHERE ugtp.`id` = {$id} AND m.organization_id = {$organizationId}")->Fetch();
	}
}
