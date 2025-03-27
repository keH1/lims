<?php

/**
 * Модель для работы с Пробами
 * Class Probe
 */
class Probe extends Model
{
    /**
     * @param $probeId
     * @param $action
     */
    public function addHistory($probeId, $action)
    {
        $data = [
            'user_id' => $_SESSION['SESS_AUTH']['USER_ID'],
            'probe_id' => $probeId,
            'date' => date('Y-m-d H:i:s'),
            'action' => $action,
        ];

        $sqlData = $this->prepearTableData('ulab_probe_history', $data);

        $this->DB->Insert('ulab_probe_history', $sqlData);
    }


    /**
     * @param $probeId
     * @return array
     */
    public function getHistory($probeId)
    {
        $userModel = new User();

        $sql = $this->DB->Query("select * from ulab_probe_history where probe_id = {$probeId} order by id asc");

        $result = [];

        while ($row = $sql->Fetch()) {
            $user = $userModel->getUserData($row['user_id']);
            $row['short_name'] =  $user['short_name'];
            $row['date'] = date('d.m.Y H:i:s', strtotime($row['date']));

            $result[] = $row;
        }

        return $result;
    }


    /**
     * редактирует данные пробы
     * @param $id
     * @param $data
     */
    public function edit($id, $data)
    {
        $sqlData = $this->prepearTableData('ulab_material_to_request', $data);

        $this->DB->Update('ulab_material_to_request', $sqlData, "where id = {$id}");
    }


    /**
     * получить одну пробу по ид
     * @param $id
     * @return array|false
     */
    public function get($id)
    {
        return $this->DB->Query("select * from ulab_material_to_request where id = {$id}")->Fetch();
    }


    /**
     * копирует информацию из пробы в пробу
     * @param $probeId
     * @param $sourceProbeId
     */
    public function copyProbeInfo($probeId, $sourceProbeId)
    {
        $sourceProbe = $this->get($sourceProbeId);

        $data = [
            'cipher' => $this->quoteStr($sourceProbe['cipher']),
            'name_for_protocol' => $this->quoteStr($sourceProbe['name_for_protocol']),
            'place' => $this->quoteStr($sourceProbe['place']),
            'date_probe' => $this->quoteStr($sourceProbe['date_probe']),
            'quarry_id' => $sourceProbe['quarry_id'],
        ];

        $this->DB->Update('ulab_material_to_request', $data, "where id = {$probeId}");
    }


    /**
     * получает список проб в акте
     * @param $dealId
     * @return array
     */
    public function getProbeInAct($dealId)
    {
        $sql = $this->DB->Query("select * from ulab_material_to_request where deal_id = {$dealId} and `in_act` = 1");

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * получение рук лабораторий по пробе
     * @param $umtrId
     * @return array
     */
    public function getLabHeadByProbe($umtrId)
    {
        $sql = $this->DB->Query(
            "select distinct 
                        new_method_id as method_id 
                    from ulab_gost_to_probe
                    where material_to_request_id = {$umtrId}"
        );

        $methodModel = new Methods();
        $labModel = new Lab();
        $userModel = new User();

        $heads = [];

        while ($row = $sql->Fetch()) {

            $labs = $methodModel->getLab($row['method_id']);

            foreach ($labs as $lab) {
                $user = $userModel->checkHeader($labModel->getDepartmentIdByLabId($lab));

                if ( empty($user) || in_array($lab, [5, 6]) ) { continue; }

                $heads[$lab] = $user;
            }
        }

        return $heads;
    }


    /**
     * отправить пробу руководителям лабораторий
     * @param $umtrId
     */
    public function transferProbe($umtrId)
    {
        $this->DB->Query("update ulab_material_to_request set `state` = 2 where id = {$umtrId}");

        $heads = $this->getLabHeadByProbe($umtrId);

        foreach ($heads as $user) {
            $this->DB->Insert('probe_to_lab', ['umtr_id' => $umtrId, 'user_id' => $user['user_id']], '', false, '', true);
        }

        $this->addHistory($umtrId, "Передал в пробоподготовку");
    }


    /**
     * пользователь принимает пробу
     * @param $umtrId
     * @param $userId
     */
    public function takeProbe($umtrId, $userId)
    {
        $this->DB->Update("probe_to_lab", ['accept_probe' => 1], "where umtr_id = {$umtrId} and user_id = {$userId}");

        $this->addHistory($umtrId, "Принял в лабораторию");
    }


    /**
     * получить информацию о руководителей, которым отправилась проба
     * @param $umtrId
     * @return array
     */
    public function getAcceptStatus($umtrId)
    {
        $userModel = new User();

        $sql = $this->DB->Query("select * from probe_to_lab where umtr_id = {$umtrId}");

        $result = [];

        while ($row = $sql->Fetch()) {
            $row['user_info'] = $userModel->getUserData($row['user_id']);
            $result[] = $row;
        }

        return $result;
    }


    /**
     * возвращает статус принятой пробы у пользователя. -1 - не отправлено пользователю. 0 - еще не принято. 1 - принято.
     * @param $umtrId
     * @param $userId
     * @return int|mixed
     */
    public function getAcceptStatusUser($umtrId, $userId)
    {
        $row = $this->DB->Query("select accept_probe from probe_to_lab where umtr_id = {$umtrId} and user_id = {$userId}")->Fetch();

        if ( isset($row['accept_probe']) ) {
            return $row['accept_probe'];
        } else {
            return -1;
        }
    }


    /**
     * @param $dealId
     * @return array
     */
    public function getCipher($dealId)
    {
        $result = [];

        $sql = $this->DB->Query("select `cipher` from ulab_material_to_request where `deal_id` = {$dealId}");

        while ($row = $sql->Fetch()) {
            $result[] = $row['cipher'];
        }

        return $result;
    }


	public function getCipherByMtrID($mtrID) {

		$result = [];

		$res = $this->DB->Query("SELECT ptm.`cipher`, ptm.`id`
										FROM `MATERIALS_TO_REQUESTS` mtr, `probe_to_materials` ptm
										WHERE ptm.`material_request_id` = mtr.`ID` AND mtr.`ID`= {$mtrID}
										");

		while ($row = $res->Fetch()) {

			$rowArr = [
				'cipher' => $row['cipher'],
				'id' => $row['id']
			];

//			$result[] = $row['cipher'];
			$result[] = $rowArr;

		}

		return $result;

	}


    /**
     * @param $umtrId
     * @return mixed
     */
	public function getDealIdByProbe($umtrId)
    {
        $dealId = $this->DB->Query("select deal_id from ulab_material_to_request where id = {$umtrId}")->Fetch();

        return $dealId['deal_id'];
    }


	public function getProbeByDealId($dealId) : array
	{
		$methodModel = new Methods();
		$userModel = new User();
		$result = [];
		$gosts = [];
		$tester = [];

		$res = $this->DB->Query("SELECT *, umtr.id as umtr_id 
										from ulab_material_to_request as umtr
										left join ulab_gost_to_probe as ugtp ON ugtp.material_to_request_id = umtr.id
										left join MATERIALS m on umtr.material_id = m.ID
								WHERE umtr.deal_id = {$dealId} order by umtr.id, umtr.probe_number, ugtp.gost_number");

		while ($row = $res->Fetch()) {

			$method = $methodModel->get($row['method_id']);
			if (!empty($row['assigned_id'])) {
				$test = $userModel->getUserShortById($row['assigned_id']);
			}

			$gosts[$row['umtr_id']][] = $method['view_gost'];
			$tester[$row['umtr_id']][] = $test['short_name'];

			$result[$row['umtr_id']] = [
				'cipher' => !empty($row['cipher']) ? $row['cipher'] : 'Не присвоен шифр',
				'material' => $row['NAME'],
				'gost' => $gosts[$row['umtr_id']],
				'tester' => $tester[$row['umtr_id']],
			];

		}

		return $result;
	}


    /**
     * Создает или обновляет Акт приемки проб
     * @param $data
     */
	public function insertUpdateActProbe($data)
    {
        $historyModel = new History();
        $userModel = new User();
        $requestModel = new Request();
		$probeModel = new Probe();

        $actId = (int)$data['act_id'];
        $actType = (int)$data['actType'];
        $dealId = (int)$data['deal_id'];

        $curYear = date("Y");

        $actData =  [
            'ACT_DATE' => $data['actDate'],
            'ID_Z' => $data['deal_id'],
			'ID_TZ' => $data['tz_id'],
			'PLACE_PROBE' => $data['samplePlace'],
			'DATE_PROBE' => $data['sampleDate'],
			'PROBE_PROIZV' => $data['sampleMaker'],
			'deliveryman' => $data['deliveryman'],
			'act_type' => $data['actType'],
			'SELECTION_TYPE' => isset($data['exampleCheck1'])? 1 : 0,
        ];

        $requestData = [
            'SELECTION_TYPE' => isset($data['exampleCheck1'])? 1 : 0,
            'DESCRIPTION' => $data['description'],
            'QUARRY_ID' => $data['quarry'],
            'DATE_ACT' => $data['actDate'],
            'PROBE_IN_LAB' => 1,
        ];

        if ( !empty($actId) ) { // обновление
            $historyType = "Обновление АКТа приемки проб";
            $sqlData = $this->prepearTableData('ACT_BASE', $actData);
            $this->DB->Update('ACT_BASE', $sqlData, "where ID = {$actId}");
        } else { // добавление
            $historyType = "Формирование АКТа приемки проб";
            $maxNumAct = $this->DB->Query(
                "select max(CONVERT(ACT_NUM, UNSIGNED INTEGER )) as max 
                FROM `ACT_BASE` 
                WHERE `act_type` = {$actType} AND `ACT_DATE` > '{$curYear}-01-01'"
            )->Fetch();

            $numAct = $maxNumAct['max'] + 1;

            $actData['ACT_NUM'] = $numAct;

            $sqlData = $this->prepearTableData('ACT_BASE', $actData);
            $this->DB->Insert('ACT_BASE', $sqlData);

            $requestData['ACT_NUM'] = $numAct;
            $requestData['NUM_ACT_TABLE'] = "$numAct/$curYear";
        }

        $requestSqlData = $this->prepearTableData('ba_tz', $requestData);
        $requestModel->updateTz($dealId, $requestSqlData);

        // TODO: для старой версии
        $where = "mtr.ID_DEAL = {$dealId}";

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
            $ptmId = (int)$row['ptm_id'];
            $date = strtotime($row['ACT_DATE']);
            $year = date("Y", $date) % 10 ? substr(date("Y", $date), -2) : date("Y", $date);

            $cipher = $row['ACT_NUM'] . '.' . $row['num'] . '/' . $year;

            $dateProbe = [
                'cipher' => $cipher
            ];

            $sqlData = $this->prepearTableData('probe_to_materials', $dateProbe);
            $this->DB->Update("probe_to_materials", $sqlData, "WHERE id = {$ptmId}");
        }


        // собирает шифры для проб в заявке, для таблицы ulab_material_to_request
        $umtrWhere = "umtr.deal_id = {$dealId}";

        $umtr = $this->DB->Query(
            "SELECT
               @row_number:=CASE
                WHEN @deal = umtr.deal_id THEN @row_number + 1
                ELSE 1
            END AS num,
              @deal:=umtr.deal_id as deal_id, umtr.id umtr_id, umtr.material_id, umtr.cipher, ab.ACT_NUM, ab.ACT_DATE, @row_number, @deal, umtr.material_number
            FROM 
              ulab_material_to_request AS umtr, 
              ACT_BASE AS ab, 
              (SELECT @deal:=0,@row_number:=0) as t
            WHERE 
              umtr.deal_id = ab.ID_Z AND {$umtrWhere} 
            ORDER BY 
              umtr.material_number, umtr.id"
        );

        $i = 1;
        while ($row = $umtr->Fetch()) {
            $umtrId = (int)$row['umtr_id'];
            $date = strtotime($row['ACT_DATE']);
            $year = date("Y", $date) % 10 ? substr(date("Y", $date), -2) : date("Y", $date);


            $cipher = $row['ACT_NUM'] . '.' . $i . '/' . $year;

            $dateProbe = [
                'cipher' => $cipher
            ];

            $sqlData = $this->prepearTableData('ulab_material_to_request', $dateProbe);
            $this->DB->Update("ulab_material_to_request", $sqlData, "WHERE id = {$umtrId}");
            $i++;

            $probeModel->addHistory($umtrId, "Пробу принял");
        }


        $currentUser = $userModel->getCurrentUser();
        $history = [
            'DATE' => $date,
            'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
            'TZ_ID' => $data['tz_id'],
            'USER_ID' => $currentUser['ID'],
            'TYPE' => $historyType,
            'REQUEST' => $requestData['TITLE'],
        ];
        $historyModel->addHistory($history);
    }

	/**
	 * Создает или обновляет Акт приемки проб
	 * @param $data
	 */
	public function insertUpdateActProbeNew($data)
	{
		$historyModel = new History();
		$userModel = new User();
		$requestModel = new Request();
		$requirementModel = new Requirement();

        $dealId = (int)$data['deal_id'];

		$tzId = $requirementModel->getTzIdByDealId($dealId);

		$curYear = date("Y");
		$curYearCipher = date("y");

		$actData =  [
			'ACT_DATE' => $data['ACT_DATE'],
			'ACT_NUM' => $data['ACT_NUM'],
			'ID_Z' => $data['deal_id'],
			'ID_TZ' => $tzId,
			'deliveryman' => $data['deliveryman'],
			'act_type' => 1,
			'creater' => $_SESSION['SESS_AUTH']['USER_ID']
		];

		if ( !empty($data['act_id']) ) { // обновление
		    $actId = (int)$data['act_id'];
			$historyType = "Обновление АКТа приемки проб";
			$sqlData = $this->prepearTableData('ACT_BASE', $actData);
			$this->DB->Update('ACT_BASE', $sqlData, "where ID = {$actId}");
		} else { // добавление
			$historyType = "Формирование АКТа приемки проб";

			$sqlData = $this->prepearTableData('ACT_BASE', $actData);
			$this->DB->Insert('ACT_BASE', $sqlData);

			$requestData['ACT_NUM'] = $data['ACT_NUM'];
			$requestData['DATE_ACT'] = $data['ACT_DATE'];
			$requestData['NUM_ACT_TABLE'] = $data['ACT_NUM'] . "/$curYear";
			$requestData['PROBE_IN_LAB'] = 1;
			$requestData['SELECTION_TYPE'] = $data['SELECTION_TYPE'];
		}

		$requestSqlData = $this->prepearTableData('ba_tz', $requestData);
		$requestModel->updateTz($dealId, $requestSqlData);

		$i = 1;
		foreach ($data['probe'] as $umtr_id => $val) {
            $umtrId = (int)$umtr_id;

			$val['cipher'] = $data['ACT_NUM'] . '.' . $i . "/$curYearCipher";
			$val['in_act'] = 1;

			$this->setLabHeaderByUmtrId($umtrId);

			$umtrSqlData = $this->prepearTableData('ulab_material_to_request', $val);
			$this->DB->Update('ulab_material_to_request', $umtrSqlData, "where id = {$umtrId}");
			$i++;

			$this->addHistory($umtrId, "Пробу принял");
		}


		$date = date('Y-m-d h:i:s');
		$currentUser = $userModel->getCurrentUser();
		$history = [
			'DATE' => $date,
			'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
			'TZ_ID' => $data['tz_id'],
			'USER_ID' => $currentUser['ID'],
			'TYPE' => $historyType,
			'REQUEST' => $requestData['TITLE'],
		];
		$historyModel->addHistory($history);
	}

    public function getNewActNumber() {

		$curYear = date("Y");

		$maxNumAct = $this->DB->Query(
			"select max(CONVERT(ACT_NUM, UNSIGNED INTEGER )) as max 
                FROM `ACT_BASE` 
                WHERE `act_type` = 1 AND `ACT_DATE` > '{$curYear}-01-01'"
		)->Fetch();

		$num = $maxNumAct['max'] + 1;

		return $num;
	}


    /**
     * @param $umtrId
     * @return array
     */
	public function getProbeLab($umtrId)
    {
        $sql = $this->DB->Query("select * from `probe_to_lab`  WHERE `umtr_id` = {$umtrId}");

        $result = [];
        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


	/**
	 * @param $idReq
	 */
	public function acceptProbe($idReq)
	{
		$this->DB->Query("UPDATE `probe_to_lab` SET `accept_probe` = 1 WHERE `id` = {$idReq}");
	}

	/**
	 * @param $idReq
	 */
	public function removeAcceptProbe($idReq)
	{
		$this->DB->Query("UPDATE `probe_to_lab` SET `accept_probe` = 0 WHERE `id` = {$idReq}");
	}

	public function setLabHeaderByUmtrId($umtr_id)
	{
        $umtr_id = (int)$umtr_id;
		$sql = $this->DB->Query("select * 
							from ulab_material_to_request as umtr
							join ulab_gost_to_probe as ugtp on umtr.id = material_to_request_id 
							where umtr.id = {$umtr_id}");

		$methodModel = new Methods();
		$labModel = new Lab();
		$userModel = new User();

		$users = [];
		$labList = [];

		while ($row = $sql->Fetch()) {
			$labs = $methodModel->getLab($row['method_id']);
			foreach ($labs as $lab) {
				$depId = $labModel->getDepartmentIdByLabId($lab);
				$labList[$lab] = $depId;
				$user = $userModel->checkHeader($depId);
				if ( empty($user) || in_array($lab, [5, 6]) ) { continue; }

				$userArr[$user['user_id']] = $user['user_id'];
			}

		}

		$headerArr = $this->checkLabHeaderByUmtrId($umtr_id);

		if (!empty($headerArr)) {
			$newHeader = array_diff($headerArr, $userArr);

			foreach ($newHeader as $userId) {

				$data = ['umtr_id' => $umtr_id,
					'user_id' => $userId];

				$this->DB->Insert('probe_to_lab', $data);
			}
		} else {
			foreach ($userArr as $userId) {

				$data = ['umtr_id' => $umtr_id,
					'user_id' => $userId];

				$this->DB->Insert('probe_to_lab', $data);
			}
		}

	}

	public function checkLabHeaderByUmtrId($umtr_id)
	{
		$sql = $this->DB->Query("select * 
							from probe_to_lab as ptl 
							where umtr_id = {$umtr_id}");

		$users = [];

		while ($row = $sql->Fetch()) {
			$users[] = $row['user_id'];
		}

		return $users;
	}

	public function setSelectionType($id, $prop)
    {

        $st = 'NULL';
        if ($prop == 'true') {
            $st = 1;
        }

        $a = $this->DB->Query("update ba_tz set `SELECTION_TYPE` = {$st} WHERE `ID_Z` = {$id}");
    }
}
