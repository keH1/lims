<?php

use \Bitrix\Main\Loader;

/**
 * Модель для заявки на испытания
 * Class Request
 */
class Request extends Model
{
    const DATE_START = '2021-01-01';
    //ID Сделки с которого начинается рефакторинг Результатов испытания (TODO: Для новых лабораторий удалить или добавить если производится рефакторинг результатов испытаний, так же убрать из карточки card.php, tz_show.php, probe.php)
    const RESULT_REFACTORING_START_ID = 8846;


    /**
     * получает текст типа заявки по ид типа.
     * @param $typeId
     * @return string
     */
    public function getTypeRequest($typeId)
    {
        $data = ['type_id' => $typeId];

        $sqlData = $this->prepearTableData('ba_tz_type', $data);

        $type = $this->DB->Query("select `title` from ba_tz_type where type_id = {$sqlData['type_id']}")->Fetch();

        return $type['title']?? "КОМ";
    }


    /**
     * получает список типов заявок
     * @return array
     */
    public function getTypeRequestList()
    {
        $sql = $this->DB->Query("select * from ba_tz_type where 1");

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * получить ID сделки с которого начинается рефакторинг "Результатов испытания"
     * получить
     * @return int
     */
    public function getResultRefactoringStartId(): int
    {
        return self::RESULT_REFACTORING_START_ID;
    }

    public function getList(): array
    {
        $cdbResult = $this->DB->Query("select * from ba_tz");

        $requestList = [];

        while ( $row = $cdbResult->Fetch() ) {
            $requestList[] = $row;
        }

        return $requestList;
    }

    /**
     * @param int $idDeal - ид сделки (из битрикса)
     * @return array|false|null
     */
    public function getDealById(int $idDeal)
    {
        return CCrmDeal::GetByID($idDeal);
    }


    /**
     * @param $dealId
     * @return array|mixed
     */
    public function getCrmUserFields($dealId)
    {
        global $USER_FIELD_MANAGER;

        return $USER_FIELD_MANAGER->GetUserFields('CRM_DEAL', $dealId);
    }


    public function saveSign($dealId, $base64)
    {
        $path = "/home/bitrix/www/protocol_generator/archive_client/{$dealId}";

        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $data = substr($base64, strpos($base64, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                return [
                    'success' => false,
                    'error' => "Не корректный тип изображения"
                ];
            }

            $data = str_replace( ' ', '+', $data );
            $data = base64_decode($data);

            if ($data === false) {
                return [
                    'success' => false,
                    'error' => "Не удалось распознать base64"
                ];
            }
        } else {
            return [
                'success' => false,
                'error' => "Не корректные данные"
            ];
        }

        if ( !is_dir($path) ) {
            $mkdirResult = mkdir($path);

            if ( $mkdirResult === false ) {
                return [
                    'success' => false,
                    'error' => "Не удалось создать папку"
                ];
            }
        }

        $saveResult = file_put_contents("{$path}/sign.{$type}", $data);

        if ( $saveResult === false ) {
            return [
                'success' => false,
                'error' => "Не удалось сохранить файл"
            ];
        }

        return [
            'success' => true,
        ];
    }


    /**
     * @param $companyId
     * @return array
     */
    public function getContractsByCompanyId($companyId): array
    {
        $result = [];

        if ( empty($companyId) ) {
            return $result;
        }

        $ba_contr = $this->DB->Query("SELECT * FROM `DOGOVOR` WHERE `CLIENT_ID` = {$companyId}");

        while ($row = $ba_contr->Fetch()) {
            $row['DATE'] = date("d.m.Y", strtotime($row['DATE']));
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param int $dealId
     * @return array
     */
    public function getTzByDealId(int $dealId)
    {
        $result = [];

        $ba_tz = $this->DB->Query(
            "SELECT tz.*, c.leader, c.confirm, count(c.id) c_count, count(c.date_return) с_date_return  
                    FROM `ba_tz` tz 
                    LEFT JOIN CHECK_TZ c ON tz.ID = c.tz_id 
                    WHERE tz.`ID_Z` = {$dealId} ");

        while ($row = $ba_tz->Fetch()) {
            //TODO: deserialize
            $addMailList = unserialize($row['SAVE_MAIL']);
            $row['addMail'] = [];
            foreach ($addMailList as $mail) {
                if ( !empty($mail) ) {
                    $row['addMail'][] = $mail;
                }
            }

            $tz = unserialize($row['TZ']);
            $row['TZ'] = $tz;

            $probe = unserialize($row['PROBE']);
            $row['PROBE'] = $probe;

            $regx = "/(\d+)$/";
            preg_match($regx, $row['DOGOVOR_NUM'], $match);
            $row['DOGOVOR_NUM'] = $match[1] ?? '';

			$row['price_ru'] = StringHelper::priceFormatRus($row['price_discount']);
            $row['act_information'] = json_decode($row['act_information'], true);

            $result = $row;
        }

        return $result;
    }

    /**
     * @param $tzId
     * @return bool
     */
	public function isConfirmTz(int $tzId)
	{
		$result = $this->DB->Query("SELECT * FROM `CHECK_TZ` WHERE `tz_id`={$tzId}");

		if ($result->SelectedRowsCount() > 0) {
			while ($row = $result->Fetch()) {
				if ($row['confirm'] == 0 || empty($row['confirm'])) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

    /**
     * @param $tzId
     * @return array
     */
    public function getInvoice($tzId)
    {
        return $this->DB->Query("SELECT * FROM `INVOICE` WHERE `TZ_ID`={$tzId}")->Fetch();
    }

    /**
     * @param $tzId
     * @return array
     */
    public function getProtocols($tzId) {
        $protocols = $this->DB->Query("SELECT * FROM `PROTOCOLS` WHERE `ID_TZ`={$tzId}");

        $result = [];

        while ($row = $protocols->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param $dealId
     * @param $data
     * @return false|mixed|string
     */
    public function addTz($dealId, $data)
    {
        $dateCreate = date('d.m.Y');
        $dateCreateTimestamp = date('Y-m-d H:i:s');

        $data['ID_Z'] = $dealId;
        $data['DATE_CREATE'] = $dateCreate;
        $data['DATE_CREATE_TIMESTAMP'] = $dateCreateTimestamp;

        $sqlData = $this->prepearTableData('ba_tz', $data);

        return $this->DB->Insert('ba_tz', $sqlData);
    }

    /**
     * @param $sourceDealId
     * @param $newDealId
     */
    public function copyTz($sourceDealId, $newDealId)
    {
        $ba_tz = $this->DB->Query(
            "SELECT TZ, OBJECT, PROBE, SAVE_MAIL, MATERIAL, 
                    COMPANY_ID, COMPANY_TITLE, TYPE_ID, ASSIGNED, 
                    DOGOVOR_NUM, DESCRIPTION, OBJECT, DOGOVOR_TABLE, PRICE, LABA_ID 
                FROM `ba_tz` 
                WHERE `ID_Z` = {$sourceDealId}")->Fetch();

        $dealData = $this->getDealById($newDealId);

        $data = [
            'TYPE_ID' =>        $this->quoteStr($dealData['TYPE_ID']),
            'ASSIGNED' =>       $this->quoteStr($dealData['ASSIGNED']),
            'REQUEST_TITLE' =>  $this->quoteStr($dealData['TITLE']),
            'STAGE_ID' =>       $this->quoteStr('NEW'),
            'TZ' =>             $this->quoteStr($ba_tz['TZ']),
            'OBJECT' =>         $this->quoteStr($ba_tz['OBJECT']),
//            'PROBE' =>          $this->quoteStr($ba_tz['PROBE']),
            'SAVE_MAIL' =>      $this->quoteStr($ba_tz['SAVE_MAIL']),
            'MATERIAL' =>       $this->quoteStr($ba_tz['MATERIAL']),
            'COMPANY_ID' =>     $this->quoteStr($ba_tz['COMPANY_ID']),
            'COMPANY_TITLE' =>  $this->quoteStr($ba_tz['COMPANY_TITLE']),
            'DOGOVOR_NUM' =>    $this->quoteStr($ba_tz['DOGOVOR_NUM']),
            'DOGOVOR_TABLE' =>  $this->quoteStr($ba_tz['DOGOVOR_TABLE']),
            'DESCRIPTION' =>    $this->quoteStr($ba_tz['DESCRIPTION']),
            'PRICE' =>          $this->quoteStr($ba_tz['PRICE']),
            'LABA_ID' =>        $this->quoteStr($ba_tz['LABA_ID']),
        ];

        $this->addTz($newDealId, $data);
    }


    /**
     * @param $dealId
     * @param $amount
     * @param $date
     */
    public function addPay($dealId, $amount, $date)
    {
        $this->DB->Query("UPDATE `ba_tz` SET `OPLATA` = `OPLATA` + {$amount}, `DATE_OPLATA` = {$date} WHERE ID_Z = {$dealId}");
    }

	/**
	 * @param $dealId
	 * @param $amount
	 * @param $date
	 */
    public function addMessage($dealId, $amount)
    {
		$res = $this->DB->Query("SELECT `REQUEST_TITLE`, `COMPANY_TITLE` FROM `ba_tz` WHERE `ID_Z` = {$dealId}")->Fetch();
		CModule::IncludeModule('im');
		$ar = Array(
			"TO_CHAT_ID" => 855, // ID чата
			"FROM_USER_ID" => 62, // ID пользователя состоящего в чате
			"MESSAGE"     => "По заявке [URL=/ulab/request/card/{$dealId}]{$res['REQUEST_TITLE']} {$res['COMPANY_TITLE']}[/URL] внесена оплата в размере {$amount} рублей", // Произвольный текст
		);
		CIMChat::AddMessage($ar);

		$array = [62,83];
		foreach ($array as $a) {
			$notify = [
				"NOTIFY_TITLE" => 'что-то там',
				"TO_USER_ID" => $a,
				"FROM_USER_ID" => 62,
				"NOTIFY_TYPE" => IM_NOTIFY_FROM,
				"NOTIFY_MESSAGE" => "По заявке <a href='/ulab/request/card/{$dealId}'>{$res['REQUEST_TITLE']} {$res['COMPANY_TITLE']}</a> внесена оплата в размере {$amount} рублей",
			];
			CIMNotify::Add($notify);
		}
    }

    /**
     * @param $dealId
     * @param $data
     * @return bool|int|string
     */
    public function updateTz($dealId, $data)
    {
        $sqlData = $this->prepearTableData('ba_tz', $data);

        $where = "WHERE ID_Z = {$dealId}";
        return $this->DB->Update('ba_tz', $sqlData, $where);
    }

    public function getCountDeal($type = '')
    {
        $curYear = date("Y");
        
        if ($type == '9') {
            $deals = CCrmDeal::GetList(
                ['DATE_CREATE' => 'DESC'], 
                [
                    '>DATE_CREATE' => "01.01.{$curYear} 00:00:00",
                    'TYPE_ID' => "'9'"
                ]
            );
            return $deals->SelectedRowsCount();
        }
        
        $filter = ['>DATE_CREATE' => "01.01.{$curYear} 00:00:00"];
        
        if (!empty($type)) {
            $filter['TYPE_ID'] = "'{$type}'";
        } else {
            $filter['!TYPE_ID'] = [1, 9];
        }
        
        $deals = CCrmDeal::GetList(['DATE_CREATE' => 'DESC'], $filter);

        // TODO: разобраться
        $count = $deals->SelectedRowsCount();
        if ($curYear == 2022) {
            $count += 22;
        }

        return $count;
    }

    public function getCurrentY()
    {
        return (int)date("Y")%10 ? substr(date("Y"), -2) : date("Y");
    }

    /**
     * @param $data
     * @return false|int
     */
    public function create($data)
    {
        $year = $this->getCurrentY();
        $countDeal = $this->getCountDeal($data['type']) + 1;

        $newDeal = new CCrmDeal;

        // TODO: убрать костыль
        while (1) {
            $typeRus = $this->DB->ForSql(trim(strip_tags($data['type_rus'])));
            $title = "{$typeRus} №{$countDeal}/{$year}";

            $tmp = $this->DB->Query("SELECT ID FROM `b_crm_deal` WHERE `TITLE` = '{$title}'")->Fetch();

            if (empty($tmp)) {
                break;
            } else {
                $countDeal++;
            }
        }

        $arFields = [
            "TITLE" => $title,
            "COMPANY_ID" => $data['company_id'],
            "TYPE_ID" => $data['type'],
            "ASSIGNED_BY_ID" => $data['assigned'],
        ];

        $result = $newDeal->Add($arFields);

        $arDealUpdate = [
            'STAGE_ID' => 'PREPARATION',
            'UF_CRM_1571643970' => $data['arrAssigned'],
        ];

        if ( $result ) {
            $newDeal->Update($newDeal,
                $arDealUpdate,
                true,
                true,
                ['DISABLE_USER_FIELD_CHECK' => true]
            );
        }

        return $result;
    }

    /**
     * @param $data
     * @return bool
     */
    public function update( $data ): bool
    {
        $deal = new CCrmDeal;

        $arDealUpdate = [
            "COMPANY_ID" => $data['company_id'],
            "ASSIGNED_BY_ID" => $data['assigned'],
            "TYPE_ID" => $data['type'],
            "TITLE" => $data['title'],
            'STAGE_ID' => 'PREPARATION',
        ];

        return $deal->Update($data['ID'],
            $arDealUpdate,
            true,
            true,
            ['DISABLE_USER_FIELD_CHECK' => true]
        );
    }

    /**
     * @param $dealId
     * @param $stage
     */
    public function updateStageDeal($dealId, $stage, $stageNumber = 0)
    {
        $deal = new CCrmDeal;
        $arDealUpdate = ['STAGE_ID' => $stage];
        $deal->Update($dealId,
            $arDealUpdate,
            true,
            true,
            ['DISABLE_USER_FIELD_CHECK' => true]
        );

        $data = [
            'STAGE_ID' => $stage,
            'STAGE_NUMBER' => $stageNumber
        ];
        $this->updateTz($dealId, $data);

        if ($stage == 2) {
        	$idTz = $this->getTzIdByDealId($dealId);
			$this->DB->Query("DELETE FROM CHECK_TZ WHERE tz_id = {$idTz['ID']}");
		}
    }

    public function getTzIdByDealId(int $dealId)
    {
        return $this->DB->Query("SELECT ID FROM `ba_tz` WHERE ID_Z = {$dealId}")->Fetch();
    }

    public function getDealIdByTzId(int $tzId)
    {
        $sql = $this->DB->Query("SELECT ID_Z FROM `ba_tz` WHERE ID = {$tzId}")->Fetch();
        return $sql['ID_Z'];
    }

    public function fillAssigned()
    {
        if ( !isset($_COOKIE['test']) ) {
            return;
        }

        global $USER_FIELD_MANAGER;

        $baTz = $this->DB->Query("SELECT ID_Z FROM `ba_tz` WHERE 1");

        while($row = $baTz->Fetch()) {
            $userFields = $USER_FIELD_MANAGER->GetUserFields('CRM_DEAL', $row['ID_Z']);

            if ( !empty($row['ID_Z']) ) {
                $deal = CCrmDeal::GetByID($row['ID_Z']);
                if ( !empty($deal['ASSIGNED_BY_ID']) ) {
                    $data = [
                        'deal_id' => $row['ID_Z'],
                        'user_id' => (int)$deal['ASSIGNED_BY_ID'],
                        'is_main' => 1,
                    ];
                    $this->DB->Insert('assigned_to_request', $data);
                    foreach ($userFields['UF_CRM_1571643970']['VALUE'] as $ass) {
                        $data = [
                            'deal_id' => $row['ID_Z'],
                            'user_id' => (int)$ass,
                        ];
                        $this->DB->Insert('assigned_to_request', $data);
                    }
                }
            }
        }
    }

    public function getProtocolsByTzId(int $tzId): array
    {
        $result = [];

        $protocols = $this->DB->Query("SELECT * FROM `PROTOCOLS` WHERE `ID_TZ` = {$tzId}");

        while ($row = $protocols->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getProtocolListByTzIds(array $tzIds): array
    {
        $result = [];

        $strId = implode(',', $tzIds);

        $protocols = $this->DB->Query("SELECT * FROM `PROTOCOLS` WHERE `ID_TZ` IN ({$strId})");

        while ($row = $protocols->Fetch()) {
            $result[$row['ID_TZ']][] = $row;
        }

        return $result;
    }

    /**
     * @param $dealId
     * @return array
     */
    public function getAssignedByDealId($dealId)
    {
        $users = $this->DB->Query("SELECT user_id, is_main FROM `assigned_to_request` WHERE deal_id = {$dealId}");

        $result = [];
        while ($row = $users->Fetch()) {
            $user = CUser::GetByID($row['user_id'])->Fetch();
            $name = trim($user['NAME']);
            $lastName = trim($user['LAST_NAME']);
            $shortName = StringHelper::shortName($name);

            $resultData = [
                'user_id'       => $row['user_id'],
                'name'          => trim($name),
                'last_name'     => trim($lastName),
                'user_name'     => "{$name} {$lastName}",
                'short_name'    => "{$shortName}. {$lastName}",
                'is_main'       => $row['is_main'],
                'department'    => $user["UF_DEPARTMENT"],
            ];

            $result[] = $resultData;
        }

        // TODO: старое
        if (empty($result)) {
            global $USER_FIELD_MANAGER;

            $userFields = $USER_FIELD_MANAGER->GetUserFields('CRM_DEAL', $dealId);

            $deal = CCrmDeal::GetByID($dealId);

            if ( !empty($deal['ASSIGNED_BY_ID']) ) {
                $user = CUser::GetByID($deal['ASSIGNED_BY_ID'])->Fetch();
                $name = trim($user['NAME']);
                $lastName = trim($user['LAST_NAME']);
                $shortName = StringHelper::shortName($name);

                $resultData = [
                    'user_id' => $deal['ASSIGNED_BY_ID'],
                    'name' => $name,
                    'last_name' => $lastName,
                    'user_name' => "{$lastName} {$name}",
                    'short_name' => "{$shortName}. {$lastName}",
                    'is_main' => 1,
                    'department' => $user["UF_DEPARTMENT"],
                ];

                $result[] = $resultData;
            }

            foreach ($userFields['UF_CRM_1571643970']['VALUE'] as $ass) {
                $user = CUser::GetByID($ass)->Fetch();
                $name = trim($user['NAME']);
                $lastName = trim($user['LAST_NAME']);
                $shortName = StringHelper::shortName($name);

                $resultData = [
                    'user_id'       => $ass,
                    'name'          => $name,
                    'last_name'     => $lastName,
                    'user_name'     => "{$lastName} {$name}",
                    'short_name'    => "{$shortName}. {$lastName}",
                    'is_main'       => 0,
                    'department'    => $user["UF_DEPARTMENT"],
                ];

                $result[] = $resultData;
            }
        }

        return $result;
    }


    /**
     *
     * @param $userId
     * @return array
     */
    public function tzUnderConsideration($userId)
    {
        $year = (int) date("Y", time())%10 ? substr(date("Y", time()), -2) : date("Y", time());
        $yearLast = $year - 1;

        //TODO: почему account not null?
        /*$stmp = $this->DB->Query(
            "SELECT DISTINCT
                        ba.`REQUEST_TITLE`, ba.`COMPANY_TITLE`, ba.`ID_Z`, ba.`NUM_P`,
                        ba.`ACCOUNT`, ch.`tz_id`, ch.`date_reply`, ch.`date_return`, ch.`confirm`
                    FROM
                        `CHECK_TZ` ch, `ba_tz` ba
                    WHERE
                        ba.`ACCOUNT` is NULL AND ch.`leader` = {$userId} AND ch.confirm = 0 AND ch.date_return IS NULL
                        AND ch.`tz_id` = ba.`ID` AND (ba.REQUEST_TITLE LIKE '%/{$year}' or ba.REQUEST_TITLE LIKE '%/{$yearLast}')
                    ORDER BY ch.`date_submission` DESC"
        );*/

		$stmp = $this->DB->Query(
			"SELECT DISTINCT 
                        ba.`REQUEST_TITLE`, ba.`COMPANY_TITLE`, ba.`ID_Z`, ba.`NUM_P`, 
                        ba.`ACCOUNT`, ch.`tz_id`, ch.`date_reply`, ch.`date_return`, ch.`confirm`
                    FROM 
                        `CHECK_TZ` ch, `ba_tz` ba 
                    WHERE 
                        ba.`ACCOUNT` is NULL AND ch.`leader` = {$userId} AND ch.confirm = 0 AND ch.date_return IS NULL 
                        AND ch.`tz_id` = ba.`ID` AND (ba.REQUEST_TITLE LIKE '%/{$year}' or ba.REQUEST_TITLE LIKE '%/{$yearLast}')
                    ORDER BY ch.`date_submission` DESC"
		);

        $result = [];

        while ($row = $stmp->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param $userId
     * @return array
     */
    public function probeInLab($userId)
    {
//        $stmp = $this->DB->Query(
//            "SELECT DISTINCT
//                    ba.`REQUEST_TITLE`, ba.`COMPANY_TITLE`, ba.`ID_Z`
//                FROM
//                    `ba_tz` as ba, assigned_to_request as atr
//                WHERE
//                    ba.`ID_Z` = atr.deal_id AND atr.user_id = {$userId} AND ba.`PROBE_IN_LAB` = '1'
//                    AND (ba.RESULTS IS NULL OR ba.RESULTS = '') AND ba.ACT_NUM IS NOT NULL AND ba.ACT_NUM <> ''");
        $stmp = $this->DB->Query(
            "SELECT ptl.*, ba.`REQUEST_TITLE`, ba.`COMPANY_TITLE`, ba.`ID_Z`, umtr.cipher 
                FROM probe_to_lab ptl
                join ulab_material_to_request as umtr on umtr.id = ptl.umtr_id
				join ba_tz as ba on umtr.deal_id = ba.ID_Z
                WHERE ptl.user_id = {$userId} AND ptl.accept_probe = 0 and umtr.cipher != '' ");

        $result = [];

        while ($row = $stmp->Fetch()) {
            $result[$row['ID_Z']][] = $row;
        }

        return $result;
    }


    /**
     * @param $userId
     * @return array
     */
    public function probeInLabPayed($userId)
    {
        $stmp = $this->DB->Query(
            "SELECT ptl.*, ba.`REQUEST_TITLE`, ba.`COMPANY_TITLE`, ba.`ID_Z`, umtr.cipher 
                FROM probe_to_lab ptl
                join ulab_material_to_request as umtr on umtr.id = ptl.umtr_id
				join ba_tz as ba on umtr.deal_id = ba.ID_Z
                WHERE ptl.user_id = {$userId} AND ptl.accept_probe = 0 and ba.OPLATA > 0 and umtr.cipher != '' ");

//        $stmp = $this->DB->Query(
//            "SELECT DISTINCT
//                    ba.`REQUEST_TITLE`, ba.`COMPANY_TITLE`, ba.`ID_Z`
//                FROM
//                    `ba_tz` as ba, assigned_to_request as atr
//                WHERE
//                    ba.`ID_Z` = atr.deal_id AND atr.user_id = {$userId} AND ba.`PROBE_IN_LAB` = '1'
//                    AND (ba.RESULTS IS NULL OR ba.RESULTS = '') AND ba.ACT_NUM IS NOT NULL AND ba.ACT_NUM <> '' and ba.OPLATA > 0");

        $result = [];

        while ($row = $stmp->Fetch()) {
            $result[$row['ID_Z']][] = $row;
        }

        return $result;
    }


    /**
     * заявки, у которых не проставлены ответственные у методик в тз
     * @param $userId - ид руководителя
     * @return array
     */
    public function getRequestListNoSetAssigned($userId)
    {
        $labModel = new Lab();

        $labInfo = $labModel->getLabByUserId($userId);

        $sql = $this->DB->Query(
            "select distinct b.ID, b.ID_Z, b.REQUEST_TITLE 
                    from ba_tz as b
                    inner join ulab_material_to_request as probe on b.ID_Z = probe.deal_id
                    inner join ulab_gost_to_probe as method on `probe`.id = `method`.material_to_request_id and (`method`.assigned_id is null or `method`.assigned_id = '')
                    inner join ulab_methods_lab as lab on lab.method_id = `method`.new_method_id and lab.lab_id = {$labInfo['ID']}
                    where b.OPLATA > 0 and b.ACT_NUM is not null and b.DATE_SOZD >= '2023-10-01' and b.STAGE_ID NOT IN ('LOSE', '5', '6', '7', '8', '9', '10', '11', '12', '13')
                    order by b.id desc "
        );

        $result = [];
        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int $userId
     * @param array $filter = [ 'search' => [], 'order' => [], 'paginate' => [] ]
     * @return array
     */
    public function getDataToJournalRequests(int $userId, array $filter = []): array
    {
        $requirementModel = new Requirement();

        $having = "";
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
                // Заявка
                if ( isset($filter['search']['requestTitle']) ) {
                    $text = htmlentities($filter['search']['requestTitle']);

                    $where .= "b.REQUEST_TITLE LIKE '%{$text}%' AND ";
                }
                // ID заявки
				if ( isset($filter['search']['deal_id']) ) {
					$where .= "b.ID_Z = {$filter['search']['deal_id']} AND ";
				}
                // Дата
                if ( isset($filter['search']['DATE_CREATE_TIMESTAMP']) ) {
                    $where .= "LOCATE('{$filter['search']['DATE_CREATE_TIMESTAMP']}', DATE_FORMAT(b.DATE_CREATE_TIMESTAMP, '%d.%m.%Y')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(b.DATE_CREATE_TIMESTAMP >= '{$filter['search']['dateStart']}' AND b.DATE_CREATE_TIMESTAMP <= '{$filter['search']['dateEnd']}') AND ";
                }
                // Клиент
                if ( isset($filter['search']['COMPANY_TITLE']) ) {
                    $text = htmlentities($filter['search']['COMPANY_TITLE']);

                    $where .= "b.COMPANY_TITLE LIKE '%{$text}%' AND ";
                }
                // Крайний срок
                if ( isset($filter['search']['DEADLINE_TABLE']) ) {
                    $where .= "b.DEADLINE_TABLE LIKE '%{$filter['search']['DEADLINE_TABLE']}%' AND ";
                }
                if ( isset($filter['search']['departure_date']) ) {
                    $where .= "gw.departure_date LIKE '%{$filter['search']['departure_date']}%' AND ";
                }
                if ( isset($filter['search']['object_gov']) ) {
                    $where .= "gw.object LIKE '%{$filter['search']['object_gov']}%' AND ";
                }
                // Тип заявки
                if ( isset($filter['search']['TYPE_ID']) ) {
                    $where .= "b.TYPE_ID = '9' AND ";
                } else {
                    $where .= "b.TYPE_ID <> '9' AND ";
                }
                // Счет
                if ( isset($filter['search']['ACCOUNT']) ) {
                    $where .= "b.ACCOUNT LIKE '%{$filter['search']['ACCOUNT']}%' AND ";
                }
                // Объект испытаний
                if ( isset($filter['search']['MATERIAL']) ) {
                    $text = htmlentities($filter['search']['MATERIAL']);

                    $having .= "MATERIAL LIKE '%{$text}%' AND ";
                }
                // Ответственный
                if ( isset($filter['search']['ASSIGNED']) ) {
                    $where .=
                        "(usr.NAME LIKE '%{$filter['search']['ASSIGNED']}%' or 
                        usr.LAST_NAME LIKE '%{$filter['search']['ASSIGNED']}%' or
                        CONCAT(SUBSTRING(usr.NAME, 1, 1), '. ', usr.LAST_NAME) LIKE '%{$filter['search']['ASSIGNED']}%') AND ";
                }
                // Акт ПП
                if ( isset($filter['search']['NUM_ACT_TABLE']) ) {
                    $where .= "b.NUM_ACT_TABLE LIKE '%{$filter['search']['NUM_ACT_TABLE']}%' AND ";
                }
                // ТЗ
                if ( isset($filter['search']['tz']) ) {
                    if ( $filter['search']['tz'] == 'n' ) {
                        $where .= "tzdoc.pdf is null AND ";
                    } else if ( $filter['search']['tz'] == 'y' ) {
                        $where .= "tzdoc.pdf is not null AND ";
                    }

                }
                // Договор
                if ( isset($filter['search']['DOGOVOR_TABLE']) ) {
                    $where .= "d.NUMBER LIKE '%{$filter['search']['DOGOVOR_TABLE']}%' AND ";
                }
                // Стоимость
                if ( isset($filter['search']['price_discount']) ) {
                    $where .= "b.price_discount like '%{$filter['search']['price_discount']}%' AND ";
                }
                // Дата оплаты
                if ( isset($filter['search']['DATE_OPLATA']) ) {
                    $where .= "b.DATE_OPLATA LIKE '%{$filter['search']['DATE_OPLATA']}%' AND ";
                }
                // Производитель
                if ( isset($filter['search']['MANUFACTURER_TITLE']) ) {
                    $text = htmlentities($filter['search']['MANUFACTURER_TITLE']);

                    $where .= "b.MANUFACTURER_TITLE LIKE '%{$text}%' AND ";
                }
                // Последнее изменение (пользователь)
                if ( isset($filter['search']['USER_HISTORY']) ) {
                    $where .= "b.USER_HISTORY LIKE '%{$filter['search']['USER_HISTORY']}%' AND ";
                }
                // Лаборатории
                if ( isset($filter['search']['lab']) ) {
                    $where .= "b.LABA_ID LIKE '%{$filter['search']['lab']}%' AND ";
                }
                // Протокол
                if ( isset($filter['search']['PROTOCOLS']) && !empty($filter['search']['PROTOCOLS']) ) {
                    $where .= "p.NUMBER_AND_YEAR LIKE '%{$filter['search']['PROTOCOLS']}%' AND ";
                }
                // стадии
                if ( isset($filter['search']['stage']) ) {
                    switch ($filter['search']['stage']) {
                        case 1: // Пробы не поступили
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING') AND IF(b.ACT_NUM, TRUE, FALSE) = FALSE AND ";
                            break;
                        case 2: // Пробы поступили
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING', 'FINAL_INVOICE') AND IF(b.ACT_NUM, TRUE, FALSE) = TRUE AND ";
                            break;
                        case 3: // Проводятся испытания
                            $where .= "b.STAGE_ID = 1 AND ";
                            break;
                        case 4: // Испытания завершены
                            $where .= "b.STAGE_ID IN ('2', '3', '4') AND ";
                            break;
                        case 5: // Заявка неуспешна
                            $where .= "b.STAGE_ID IN ('5', '6', '7', '8', '9', 'LOSE') AND ";
                            break;
                        case 6: // Заявка не оплачена
                            $where .= "b.`PRICE` IS NOT NULL AND b.`PRICE` > 0 AND (b.`OPLATA` = 0 || b.`OPLATA` IS NULL) AND b.STAGE_ID NOT IN ('LOSE', '5', '6', '7', '8', '9', '10', '11', '12', '13') AND ";
                            break;
                        case 7: // Заявка оплачена не полностью
                            $where .= "b.OPLATA < b.PRICE AND b.`OPLATA` > 0 AND ";
                            break;
                        case 8: // По заявке переплата
                            $where .= "b.OPLATA > b.PRICE AND ";
                            break;
                        case 9: // Заявка оплачена полностью
                            $where .= "b.OPLATA = b.PRICE AND ";
                            break;
                        case 10: // Все кроме новых и неуспешных
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING', 'FINAL_INVOICE', '1', '2', '3', '4', 'WON') AND IF(b.ACT_NUM, TRUE, FALSE) = TRUE AND ";
                            break;
                        case 11: // Успешно завершенные
                            $where .= "b.STAGE_ID = 'WON' AND ";
                            break;
                        case 12: // Пробы не приняты
                            $where .= "b.PROBE_IN_LAB = 0 AND ";
                            break;
                        case 'wait_won': // Ожидает завершения
                            $where .= "b.OPLATA = b.PRICE and (b.STAGE_ID = 2 or b.STAGE_ID = 4) and (act.NUMBER is not null and act.NUMBER not like 'Ждем ответ от 1С') AND ";
                            break;
                        case 'wait_lose': // Ожидает закрытия
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING') AND IF(b.ACT_NUM, TRUE, FALSE) = FALSE AND (b.DATE_CREATE_TIMESTAMP <= curdate() - interval 3 month) AND ";
                            break;
						case 'for_meating': // Ожидает закрытия
							$where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING', 'FINAL_INVOICE', 1) AND IF(b.ACT_NUM, TRUE, FALSE) = TRUE AND ";
							break;
                        case 'in_work': // Заявка в работе
                            $where .= "b.STAGE_ID NOT IN ('LOSE', '5', '6', '7', '8', '9', '10', '11', '12', '2', '4', 'WON') AND ";
                            break;
                    }
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'requestTitle':
                        $order['by'] = 'b.REQUEST_TITLE';
                        break;
                    case 'DATE_CREATE_TIMESTAMP':
                        $order['by'] = 'b.DATE_CREATE_TIMESTAMP';
                        break;
                    case 'COMPANY_TITLE':
                        $order['by'] = 'b.COMPANY_TITLE';
                        break;
                    case 'DEADLINE_TABLE':
                        $order['by'] = 'b.DEADLINE_TABLE';
                        break;
                    case 'ACCOUNT':
                        $order['by'] = 'b.ACCOUNT';
                        break;
                    case 'MATERIAL':
                        $order['by'] = "group_concat(distinct mater.NAME SEPARATOR ', ')";
                        break;
                    case 'NUM_ACT_TABLE':
                        $order['by'] = 'YEAR(ACT_DATE) DESC, a.ACT_NUM';
                        break;
                    case 'DOGOVOR_TABLE':
                        $order['by'] = 'd.NUMBER';
                        break;
                    case 'price_discount':
                        $order['by'] = 'b.price_discount';
                        break;
                    case 'DATE_OPLATA':
                        $order['by'] = 'b.DATE_OPLATA';
                        break;
                    case 'MANUFACTURER_TITLE':
                        $order['by'] = 'b.MANUFACTURER_TITLE';
                        break;
                    case 'USER_HISTORY':
                        $order['by'] = 'b.USER_HISTORY';
                        break;
                    case 'departure_date':
                        $order['by'] = 'gw.departure_date';
                        break;
                    case 'object_gov':
                        $order['by'] = 'gw.object';
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
        $where .= "1 ";
        $having .= "1";

        $result = [];

        $data = $this->DB->Query(
            "SELECT DISTINCT b.ID b_id, b.TZ, b.STAGE_ID, b.ID_Z, b.ACT_NUM, b.REQUEST_TITLE, b.TAKEN_SERT_ISP, b.RESULTS, b.TAKEN_ID_DEAL, b.TYPE_ID,
                        CONVERT(substring_index(substring_index(b.REQUEST_TITLE, '№', -1), '/', 1 ),UNSIGNED INTEGER) request,
                        b.DATE_CREATE_TIMESTAMP, b.COMPANY_TITLE, b.DEADLINE,  b.DEADLINE_TABLE, b.ACCOUNT, b.COMPANY_ID, 
                        b.NUM_ACT_TABLE, b.PRICE, b.price_discount, b.OPLATA, b.DATE_OPLATA, b.PDF,
                        b.discount_type, b.DISCOUNT, 
                        b.MANUFACTURER_TITLE, b.USER_HISTORY, b.LABA_ID, b.ACTUAL_VER b_actual_ver, c.leader, c.confirm,
                        bcc.TITLE as company_title_bcc,
                        count(c.id) c_count, count(c.date_return) с_date_return, k.ID k_id , d.IS_ACTION, CONCAT(d.CONTRACT_TYPE, ' ', d.NUMBER, ' от ', DATE_FORMAT(d.DATE, '%d.%m.%Y')) as DOGOVOR_TABLE,
                        tzdoc.pdf tz_pdf,
                        gw.departure_date, gw.object as object_gov,
                        group_concat(distinct CONCAT(SUBSTRING(usr.NAME, 1, 1), '. ', usr.LAST_NAME) SEPARATOR ', ') as ASSIGNED,
                        group_concat(distinct mater.NAME SEPARATOR ', ') as MATERIAL
                    FROM ba_tz b
                    LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID 
                    LEFT JOIN CHECK_TZ c ON b.ID=c.tz_id
                    LEFT JOIN KP k ON b.ID=k.TZ_ID 
                    LEFT JOIN PROTOCOLS p ON p.ID_TZ=b.ID 
                    LEFT JOIN DEALS_TO_CONTRACTS dtc ON dtc.ID_DEAL=b.ID_Z
                    LEFT JOIN DOGOVOR d ON d.ID=dtc.ID_CONTRACT 
                    LEFT JOIN AKT_VR act ON act.TZ_ID=b.ID 
                    LEFT JOIN assigned_to_request ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user usr ON ass.user_id = usr.ID 
                    LEFT JOIN TZ_DOC tzdoc ON tzdoc.TZ_ID = b.ID 
                    LEFT JOIN b_crm_company bcc ON bcc.ID = b.COMPANY_ID 
                    LEFT JOIN government_work as gw ON gw.deal_id = b.ID_Z 
                    left join ulab_material_to_request as umtr on umtr.deal_id = b.ID_Z
                    left join MATERIALS as mater on umtr.material_id = mater.ID 
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
                    GROUP BY b.ID
                    HAVING {$having} 
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT b.ID val
                    FROM ba_tz AS b
                    LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID 
                    LEFT JOIN CHECK_TZ AS c ON b.ID=c.tz_id
                    LEFT JOIN KP AS k ON b.ID=k.TZ_ID 
                    LEFT JOIN PROTOCOLS p ON p.ID_TZ=b.ID 
                    LEFT JOIN DOGOVOR d ON d.TZ_ID=b.ID
                    LEFT JOIN assigned_to_request ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user usr ON ass.user_id = usr.ID
                    LEFT JOIN b_crm_company bcc ON bcc.ID = b.COMPANY_ID
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> ''
                    GROUP BY b.ID"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT b.ID val, group_concat(distinct mater.NAME SEPARATOR ', ') as MATERIAL
                    FROM ba_tz AS b
                    LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID 
                    LEFT JOIN CHECK_TZ AS c ON b.ID=c.tz_id
                    LEFT JOIN KP AS k ON b.ID=k.TZ_ID 
                    LEFT JOIN PROTOCOLS p ON p.ID_TZ=b.ID
                    LEFT JOIN DOGOVOR d ON d.TZ_ID=b.ID
                    LEFT JOIN AKT_VR act ON act.TZ_ID=b.ID 
                    LEFT JOIN assigned_to_request ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user usr ON ass.user_id = usr.ID 
                    LEFT JOIN b_crm_company bcc ON bcc.ID = b.COMPANY_ID    
                    LEFT JOIN TZ_DOC tzdoc ON tzdoc.TZ_ID = b.ID 
                    left join ulab_material_to_request as umtr on umtr.deal_id = b.ID_Z
                    left join MATERIALS as mater on umtr.material_id = mater.ID
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
                    GROUP BY b.ID
                    HAVING {$having} "
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $row['start_new_area'] = DEAL_START_NEW_AREA;
            $isExistTz = $requirementModel->isExistTz($row['ID_Z']);

            $protocols = $this->getProtocolsByTzId($row['b_id']);

            $crmDeal = $this->getDealById($row['ID_Z']);

            $row['REQUEST_TITLE'] = $crmDeal['TITLE'];

            $protocolsData = [];
            $firstProtocol = [];
            $mangoClass = '';
            $greenClass = '';

            $stage = $this->getStage($row);

            if (!empty($row['c_count'])) {
                if (!empty($row['leader']) && $row['leader'] == $userId && empty($row['confirm'])) {
                    $mangoClass = 'mango-class';
                }

                if (!empty($check_tz['confirm']) && empty($res_kp['ID']) && in_array($userId, [35, 62])) {
                    $greenClass = 'green_class';
                }
            }
			// оповещение о закрытом договоре

            if (isset($row['IS_ACTION']) && $row['IS_ACTION'] == 0) {
				$row['bgOrder'] = 'bg-light-red';
			}

            if (!empty($row['tz_pdf'])) {
                $row['bgPdf'] = 'bg-green-transp';
            } else {
                $row['bgPdf'] = '';
            }

            $row['titleStage'] = $stage['title'];
            $row['bgStage'] = $stage['color'];

            $row['bgCheck'] = $mangoClass;

            $row['bgConfirm'] = $greenClass;

            $row['certificate'] = $row['REQUEST_TITLE'] && $row['TAKEN_SERT_ISP'] == 1 ? ' C' : '';

            $row['b_tz_id'] = $isExistTz ? $row['b_id'] : '';

            if ( empty($row['price_discount']) ) {
                $row['price_discount'] = $row['PRICE'];
            }

			$row['price_ru'] = StringHelper::priceFormatRus($row['price_discount']);

			if ($row['TAKEN_ID_DEAL'] || $row['TYPE_ID'] == 7) {
				$row['bgPrice'] = 'bg-green-transp';
			} elseif ($row['price_discount'] && $row['OPLATA'] >= $row['price_discount'] || !$row['price_discount']) {
				$row['bgPrice'] = 'bg-transparent';
			} else {
				$row['bgPrice'] = 'bg-light-red';
			}

            $row['type_text'] = $this->getTypeRequest($row['TYPE_ID']);

			// Название компании
			if (!empty($row['COMPANY_ID'])) {
				$row['COMPANY_TITLE'] = $row['company_title_bcc'];
			}

            $row['linkName'] = $row['b_id'] && $row['ACT_NUM'] ? 'Открыть' : '';

            if (count($protocols) > 0) {
                $firstProtocol = current($protocols);
                foreach ($protocols as $key => $value) {
                    $numberAndYear = !empty($value['NUMBER_AND_YEAR']) ? $value['NUMBER_AND_YEAR'] : '';
                    $protocolsData[$key] = [
                        'ID' => $value['ID'],
                        'NUMBER_AND_YEAR' => $numberAndYear,
                        'ACTUAL_VERSION' => unserialize($value['ACTUAL_VERSION']),
                        'PDF' => $value['PDF'],
                        'PROTOCOL_OUTSIDE_LIS' => $value['PROTOCOL_OUTSIDE_LIS'],
                        'YEAR' => !empty($value['DATE']) ? date('Y', strtotime($value['DATE'])) : ''
                    ];

                    if (empty($value['PDF'])) {
                        $files = scandir($_SERVER['DOCUMENT_ROOT'] . '/protocol_generator/archive/' . $row['b_id'] . $protocolsData[$key]['YEAR'] . '/' . $protocolsData[$key]['ID'] . '/');

                        $protocolsData[$key]['FILES'] = !empty($files) ? $files : [];
                    } else {
                        $protocolsData[$key]['FILES'] = [];
                    }
                }
            }

            $row['firstProtocolId'] = $firstProtocol['ID'] ?? null;

            $row['PROTOCOLS'] = $protocolsData;

            $row['NUM_P_TABLE'] = !empty($row['NUM_P_TABLE']) ? $row['NUM_P_TABLE'] : '';

            $row['NUM_ACT_TABLE'] = $row['NUM_ACT_TABLE'] ?? '';

            $row['ACT_NUM'] = !empty($row['NUM_ACT_TABLE'])
                ? preg_replace('/\/[^.]+/', '', $row['NUM_ACT_TABLE'])
                : ''; //TODO: Проверить почему не всегда записывалось поле ACT_NUM и заменить

            $row['TAKEN_SERT_ISP'] = (int)$row['TAKEN_SERT_ISP'];

            $row['YEAR_ACT'] = !empty($row['DEADLINE']) ? date('Y',  strtotime($row['DATE_ACT'])) : '';

            $row['DEADLINE_TABLE'] = !empty($row['DEADLINE_TABLE']) ? date('d.m.Y',  strtotime($row['DEADLINE_TABLE'])) : '';
            $row['deadlineISO'] = !empty($row['DEADLINE_TABLE']) ? date('Y-m-d',  strtotime($row['DEADLINE_TABLE'])) : '';//TODO: Проверить почему не всегда записывается поле DEADLINE и заменить

            $row['dateOplataISO'] = !empty($row['DATE_OPLATA']) ? date('Y-m-d',  strtotime($row['DATE_OPLATA'])) : '';

            $textColor = '';
            if (date('Y-m-d') > $row['deadlineISO']
                && !in_array($row['STAGE_ID'], ['2', '4', 'WON', 'LOSE', '5', '6', '7', '8', '9'])) {
                $textColor = 'text-red';
            }

            $row['textColor'] = $textColor;

            $row['DATE_CREATE_TIMESTAMP'] = !empty($row['DATE_CREATE_TIMESTAMP']) && $row['DATE_CREATE_TIMESTAMP'] != "0000-00-00 00:00:00"
                ? date('Y-m-d',  strtotime($row['DATE_CREATE_TIMESTAMP']))
                : '';

            $row['dateCreateRu'] = !empty($row['DATE_CREATE_TIMESTAMP']) && $row['DATE_CREATE_TIMESTAMP'] != "0000-00-00 00:00:00"
                ? date('d.m.Y',  strtotime($row['DATE_CREATE_TIMESTAMP']))
                : '';

            $row['departure_date'] = !empty($row['departure_date']) && $row['departure_date'] != "0000-00-00"
                ? date('d.m.Y',  strtotime($row['departure_date']))
                : '';

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @param $row - ba_tz
     * @return string[]
     */
    public function getStage($row)
    {
        $requirementModel = new Requirement();

        switch ($row['STAGE_ID']) {
            case 'NEW':
            case 'PREPARATION':
            case 'PREPAYMENT_INVOICE':
            case 'EXECUTING':
                $bgColor = 'bg-light-blue';
                $title = 'Испытания еще не проводились. Пробы не получены.';
                break;
            case 'FINAL_INVOICE':
                $bgColor = 'bg-yellow';
                $title = 'Пробы получены. Проводятся испытания';
                break;
            case '1':
                $bgColor = 'bg-yellow';
                $title = 'Пробы получены. Проводятся испытания.';
                break;
            case '2':
                $bgColor = 'bg-purple';
                $title = 'Испытания в лаборатории завершены. Оплата получена или не требуется. Акты ВР не отправлены.';
                break;
            case '4':
                $bgColor = 'bg-light-green';
                $title = 'Акты ВР отправлены заказчику.';
                break;
            case 'WON':
                $bgColor = 'bg-green';
                $title = 'Акты ВР получены. Заявка успешно завершена.';
                break;
            case 'LOSE':
                $bgColor = 'bg-red';
                $title = 'Испытания не проведены. Заявка прекращена.';
                break;
            case '7':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Не проводим подобные испытания.';
                break;
            case '6':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчик не вышел на связь.';
                break;
            case '5':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчика не устроила цена.';
                break;
            case '8':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Создана другая заявка.';
                break;
            case '9':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчик выбрал лабораторию в своем городе.';
                break;
            case '10':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчик решил не проводить испытания.';
                break;
            case '11':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Отказались сами в связи с высокой загруженностью.';
                break;
            case '12':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Судебная экспертиза.';
                break;
            case '13':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Участие в тендере.';
                break;
            default:
                $bgColor = 'bg-red';
                $title = '';
                break;
        }

        if (
            $row['ACT_NUM']
            && in_array($row['STAGE_ID'], ['NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING'])
        ) {
            $bgColor='bg-yellow';
            $title = 'Пробы получены. Проводятся испытания';
        }

        if ( !empty($row['price_discount']) ) {
            $row['PRICE'] = $row['price_discount'];
        }

        if (
            $row['PRICE'] && $row['RESULTS']
            && (!$row['OPLATA'] || (float)$row['OPLATA'] < (float)$row['PRICE'])
            && $row['STAGE_ID'] === '2'
            && empty($row['TAKEN_ID_DEAL'])
        ) {
            $bgColor = 'bg-dark-red';
            $title = 'Испытания в лаборатории завершены. Оплата не поступила.';
        }

        $confirm = $requirementModel->getStateConfirm($row['ID_Z']);
        if ($confirm == CHECK_TZ_WAIT) {
            $bgColor = 'bg-dark-blue';
            $title = 'Заявка на стадии проверки ТЗ';
        }

        return [
            'title' => $title,
            'color' => $bgColor,
        ];
    }


    /**
     * @return array
     */
    public function getCheckTz(): array
    {
        $result = [];

        $checkTz = $this->DB->Query("SELECT ba.REQUEST_TITLE, ba.COMPANY_TITLE, ba.ID_Z, ba.ACCOUNT, ch.tz_id, ch.date_reply, ch.date_return, ch.confirm 
            FROM CHECK_TZ ch, ba_tz ba WHERE ba.ACCOUNT IS NULL AND ch.tz_id = ba.ID");

        while ($row = $checkTz->Fetch()) {
            $bgColor = $status = '';

            if (empty($row['date_reply']) && empty($row['date_return'])) {
                $status = 'На рассмотрении руководителя лаборатории';
            } elseif (!empty($row['date_reply']) && empty($row['date_return']) &&  !empty($row['confirm'])) {
                $status = 'Заявка принята руководителем. Счет не выставлен.';
                $bgColor = 'bg-light-pink';
            }

            unset($row['date_reply'], $row['date_return']);

            $row['REQUEST_TITLE'] = StringHelper::encode($row['REQUEST_TITLE']);

            $row['cropRequestTitle'] = StringHelper::cropString($row['REQUEST_TITLE'], 20, '...');

            $row['COMPANY_TITLE'] = StringHelper::encode($row['COMPANY_TITLE']);

            $row['cropCompanyTitle'] = StringHelper::cropString($row['COMPANY_TITLE'], 40, '...');

            $row['status'] = StringHelper::encode($status);

            $row['cropStatus'] = StringHelper::cropString($status, 40, '...');

            $row['bgColor'] = $bgColor;

            $result[] = $row;
        }

        return $result;
    }

    public function getDateStart()
    {
        return self::DATE_START;
    }

    /**
     * @param $idDeal
     * @param $comment
     */
    public function addComment($idDeal, $comment)
    {
        $this->DB->Query("DELETE FROM `COMMENTS` WHERE `ID_REQ` = {$idDeal}");

        $this->DB->Insert(
            'COMMENTS',
            [
                'ID_REQ' => $idDeal,
                'TEXT' => $this->quoteStr($this->DB->ForSql($comment))
            ]
        );
    }

    /**
     * @param $idDeal
     * @return mixed
     */
    public function getComment($idDeal)
    {
        $result = $this->DB->Query("SELECT `TEXT` FROM `COMMENTS` WHERE `ID_REQ` = {$idDeal}")->Fetch();
        return $result['TEXT'];
    }


    /**
     * @param $file
     * @param $dealId
     * @return array
     */
    public function savePhoto($file, $dealId) {
		// проверка что файл формата .png или .jpg //
		$pattern = "/\.(png|jpg|jpeg)$/i";
		if ( !preg_match($pattern, $file['name']) ) {
			$result = [
				'success' => false,
				'error' => [
					'message' => 'Ошибка! Файл должен быть png, jpg, jpeg',
				]
			];

			return $result;
		}

		$uploaddir = '/home/bitrix/www/photo/' . $dealId;

		return $this->saveFile($uploaddir, $file['name'], $file['tmp_name']);
	}


    /**
     * @param $file
     * @param $dealId
     * @return array
     */
	public function saveAnyFile($path, $file)
    {
        $uploaddir = UPLOAD_DIR . "/{$path}";

        return $this->saveFile($uploaddir, $file['name'], $file['tmp_name']);
    }


    /**
     * @param $path - путь_до_файла/имя
     */
    public function deleteUploadedFile($path)
    {
        $file = UPLOAD_DIR . "/{$path}";

        unlink($file);
    }


    /**
     * Журнал акта приёмки проб
     * @param array $filter
     * @return array
     */
    public function getDatatoJournalActProbe(array $filter = [])
    {
        $where = "";
        $having = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];

        $labModel = new Lab();
        $permissionModel = new Permission();
        $perm = $permissionModel->getUserPermission(App::getUserId());

        if ( $perm['view_name'] == 'lab' ) {
//            $where .= "ass.user_id = '{$_SESSION['SESS_AUTH']['USER_ID']}' AND ";
        }

        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                // Заявка
                if ( isset($filter['search']['REQUEST_TITLE']) ) {
                    $where .= "b.REQUEST_TITLE LIKE '%{$filter['search']['REQUEST_TITLE']}%' AND ";
                }
                // Шифры
                if ( isset($filter['search']['CIPHER']) ) {
                    $where .= "umtr.cipher LIKE '%{$filter['search']['CIPHER']}%' AND ";
                }
                // Дата
                if ( isset($filter['search']['DATE_ACT']) ) {
                    $where .= "LOCATE('{$filter['search']['DATE_ACT']}', DATE_FORMAT(a.ACT_DATE, '%d.%m.%Y')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(a.ACT_DATE >= '{$filter['search']['dateStart']}' AND a.ACT_DATE <= '{$filter['search']['dateEnd']}') AND ";
                }
                // Клиент
                if ( isset($filter['search']['COMPANY_TITLE']) ) {
                    $where .= "b.COMPANY_TITLE LIKE '%{$filter['search']['COMPANY_TITLE']}%' AND ";
                }
                // Объект испытаний
                if ( isset($filter['search']['MATERIAL']) ) {
                    $where .= "b.MATERIAL LIKE '%{$filter['search']['MATERIAL']}%' AND ";
                }
                // Ответственный
                if (isset($filter['search']['ASSIGNED'])) {
                    $having .= "ASSIGNED LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
                }
                // Акт ПП
                if ( isset($filter['search']['NUM_ACT_TABLE']) ) {
                    $where .= "b.NUM_ACT_TABLE LIKE '%{$filter['search']['NUM_ACT_TABLE']}%' AND ";
                }
                // Договор
                if ( isset($filter['search']['DOGOVOR_TABLE']) ) {
                    $where .= "b.DOGOVOR_TABLE LIKE '%{$filter['search']['DOGOVOR_TABLE']}%' AND ";
                }
                // Лаборатории
                if ( isset($filter['search']['lab']) ) {
                    $where .= "b.LABA_ID LIKE '%{$filter['search']['lab']}%' AND ";
                }
                if ( isset($filter['search']['LAB']) ) {
                    $sql = $this->DB->Query(
                        "select `id_dep` from ba_laba where (`NAME` like '%{$filter['search']['LAB']}%' or `short_name` like '%{$filter['search']['LAB']}%' )"
                    );

                    $depsId = [];

                    $tmpWhere = "(";
                    while ($row = $sql->Fetch()) {
                        $tmpWhere .= "b.LABA_ID LIKE '%{$row['id_dep']}%' or ";
                        $depsId[] = $row['id_dep'];
                    }
                    $tmpWhere .= " 0 ) and ";

                    if ( !empty($depsId) ) {
                        $where .= $tmpWhere;
                    } else {
                        $where .= "0 and ";
                    }
                }
                // Протокол
                if ( isset($filter['search']['PROTOCOLS']) ) {
                    $where .= "prtcl.NUMBER_AND_YEAR LIKE '%{$filter['search']['PROTOCOLS']}%' AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'NUM_ACT_TABLE':
                        $order['by'] = 'YEAR(a.ACT_DATE) DESC, a.ACT_NUM';
                        break;
                    case 'DOGOVOR_TABLE':
                        $order['by'] = 'b.DOGOVOR_TABLE';
                        break;
                    case 'DATE_ACT':
                        $order['by'] = 'b.DATE_ACT';
                        break;
                    case 'COMPANY_TITLE':
                        $order['by'] = 'b.COMPANY_TITLE';
                        break;
                    case 'MATERIAL':
                        $order['by'] = 'b.MATERIAL';
                        break;
                    case 'ASSIGNED':
                        $order['by'] = "LEFT(GROUP_CONCAT(DISTINCT TRIM(CONCAT_WS(' ', u.NAME, u.LAST_NAME)) SEPARATOR ', '), 1)";
                        break;
                    case 'REQUEST_TITLE':
                        $order['by'] = 'b.REQUEST_TITLE';
                        break;
                    default:
                        $order['by'] = 'b.ID';
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
        $having .= "1";

        $result = [];


        $data = $this->DB->Query(
            "SELECT b.ID b_id, b.NUM_ACT_TABLE, b.ID_Z, b.DOGOVOR_TABLE, b.REQUEST_TITLE, b.LABA_ID,  
                        b.DATE_ACT, b.COMPANY_TITLE, b.MATERIAL, a.ACT_NUM, 
                        GROUP_CONCAT(DISTINCT TRIM(CONCAT_WS(' ', u.NAME, u.LAST_NAME)) SEPARATOR ', ') as ASSIGNED, 
                        GROUP_CONCAT(IF(umtr.cipher='', null, umtr.cipher) SEPARATOR ', ') as CIPHER,
                        GROUP_CONCAT(distinct IF(prtcl.NUMBER_AND_YEAR='', null, prtcl.NUMBER_AND_YEAR) SEPARATOR ', ') as PROTOCOLS
                    FROM ba_tz b
                    LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID
                    inner JOIN ulab_material_to_request as umtr ON umtr.deal_id = b.ID_Z
                    LEFT JOIN PROTOCOLS as prtcl ON prtcl.ID_TZ = b.ID
                    LEFT JOIN assigned_to_request as ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user as u ON u.ID = ass.user_id
                    WHERE b.TYPE_ID != '3' AND {$where}
                    GROUP BY b.ID 
                    HAVING {$having} 
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT b.ID val
                    FROM ba_tz AS b
                    LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID
                    inner JOIN ulab_material_to_request as umtr ON umtr.deal_id = b.ID_Z
                    LEFT JOIN assigned_to_request as ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user as u ON u.ID = ass.user_id
                    WHERE b.TYPE_ID != '3'
                    GROUP BY b.ID"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT b.ID val, GROUP_CONCAT(DISTINCT TRIM(CONCAT_WS(' ', u.NAME, u.LAST_NAME)) SEPARATOR ', ') as ASSIGNED 
                    FROM ba_tz AS b
                    LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID
                    inner JOIN ulab_material_to_request as umtr ON umtr.deal_id = b.ID_Z
                    LEFT JOIN PROTOCOLS as prtcl ON prtcl.ID_TZ = b.ID
                    LEFT JOIN assigned_to_request as ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user as u ON u.ID = ass.user_id
                    WHERE b.TYPE_ID != '3' AND {$where}
                    GROUP BY b.ID 
                    HAVING {$having}"
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $row['DATE_ACT'] = !empty($row['DATE_ACT']) ? date('d.m.Y',  strtotime($row['DATE_ACT'])) : '';

            $arrNameLabs = [];
            $labs = [];
            if (!empty($row['LABA_ID'])) {
                $labs = explode(',', $row['LABA_ID']);
            }

            foreach ($labs as $lab) {
                $objLab = $labModel->getLabByDepartment($lab);
                if ( empty($objLab) ) { continue; }
                $arrNameLabs[] = !empty($objLab['short_name'])? $objLab['short_name'] : $objLab['NAME'];
            }

            if ( !empty($arrNameLabs) ) {
                $row['LAB'] = implode(', ', $arrNameLabs);
            } else {
                $row['LAB'] = '';
            }

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * Получает данные для журнала счетов
     * @param array $filter
     * @return array
     */
    public function getDatatoJournalInvoice(array $filter = [])
    {
        $where = "";
        $having = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            if ( !empty($filter['search']) ) {
                // Заявка
                if ( isset($filter['search']['REQUEST_TITLE']) ) {
                    $where .= "b.REQUEST_TITLE LIKE '%{$filter['search']['REQUEST_TITLE']}%' AND ";
                }
                // №
                if ( isset($filter['search']['ACCOUNT']) ) {
                    $where .= "b.ACCOUNT LIKE '%{$filter['search']['ACCOUNT']}%' AND ";
                }
                // Дата
                if ( isset($filter['search']['DATE']) ) {
                    $where .= "LOCATE('{$filter['search']['DATE']}', DATE_FORMAT(i.DATE, '%d.%m.%Y')) > 0 AND ";
                }
                // Дата
                if ( isset($filter['search']['DATE_ACT_VR']) ) {
                    $where .= "LOCATE('{$filter['search']['DATE_ACT_VR']}', DATE_FORMAT(a.DATE, '%d.%m.%Y')) > 0 AND ";
                }
                // Сумма
                if ( isset($filter['search']['price_discount']) ) {
                    if ( is_numeric($filter['search']['price_discount']) ) {
                        $price = floatval($filter['search']['price_discount']);
                        $where .= "b.price_discount like '%{$price}%' AND ";
                    } else {
                        $where .= "1=0 AND ";
                    }
                }
                // Act VR
                if ( isset($filter['search']['ACT_VR']) ) {
                    $where .= "a.NUMBER LIKE '%{$filter['search']['ACT_VR']}%' AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(i.DATE >= '{$filter['search']['dateStart']}' AND i.DATE <= '{$filter['search']['dateEnd']}') AND ";
                }
                // Клиент
                if ( isset($filter['search']['COMPANY_TITLE']) ) {
                    $searchRaw = trim($filter['search']['COMPANY_TITLE']);
                    $searchHtml = htmlspecialchars($searchRaw, ENT_QUOTES, 'UTF-8');
                    $where .= "(b.COMPANY_TITLE LIKE '%{$searchRaw}%' OR b.COMPANY_TITLE LIKE '%{$searchHtml}%') AND ";
                }
                // Объект испытаний
                if ( isset($filter['search']['MATERIAL']) ) {
                    $where .= "b.MATERIAL LIKE '%{$filter['search']['MATERIAL']}%' AND ";
                }
                // Ответственный
                if (isset($filter['search']['ASSIGNED'])) {
                    $having .= "ASSIGNED LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
                }
                // Акт ПП
                if ( isset($filter['search']['NUM_ACT_TABLE']) ) {
                    $where .= "b.NUM_ACT_TABLE LIKE '%{$filter['search']['NUM_ACT_TABLE']}%' AND ";
                }
                // Договор
                if ( isset($filter['search']['DOGOVOR_TABLE']) ) {
                    $where .= "b.DOGOVOR_TABLE LIKE '%{$filter['search']['DOGOVOR_TABLE']}%' AND ";
                }
                // Лаборатории
                if ( isset($filter['search']['lab']) ) {
                    $where .= "b.LABA_ID LIKE '%{$filter['search']['lab']}%' AND ";
                }
                // Стадия
                if ( isset($filter['search']['stage']) ) {
                    switch ($filter['search']['stage']) { // PRICE OPLATA
                        case '1': // Счет не оплачен
                            $where .= "b.price_discount > 0 and (b.OPLATA = 0 or b.OPLATA is NULL) AND ";
                            break;
                        case '2': // Счет оплачен не полностью
                            $where .= "b.price_discount > 0 and b.OPLATA > 0 and b.OPLATA < b.price_discount AND ";
                            break;
                        case '3': // Счет оплачен полностью
                            $where .= "b.OPLATA >= b.price_discount AND ";
                            break;
                        case '4': // Акт ВР сформирован и не отправлен
                            break;
                        default: // Все счета
                    }
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'ACCOUNT':
                        $order['by'] = 'year(i.DATE) desc, b.ACCOUNT';
                        break;
                    case 'price_discount':
                        $order['by'] = 'b.price_discount';
                        break;
                    case 'ACT_VR':
                        $order['by'] = 'a.NUMBER';
                        break;
                    case 'DOGOVOR_TABLE':
                        $order['by'] = 'b.DOGOVOR_TABLE';
                        break;
                    case 'DATE':
                        $order['by'] = 'i.DATE';
                        break;
                    case 'DATE_ACT_VR':
                        $order['by'] = 'a.DATE';
                        break;
                    case 'SEND_DATE_ACT_VR':
                        $order['by'] = 'a.SEND_DATE';
                        break;
                    case 'COMPANY_TITLE':
                        $order['by'] = 'b.COMPANY_TITLE';
                        break;
                    case 'MATERIAL':
                        $order['by'] = 'b.MATERIAL';
                        break;
                    case 'ASSIGNED':
                        $order['by'] = "LEFT(GROUP_CONCAT(DISTINCT TRIM(CONCAT(usr.NAME, ' ', usr.LAST_NAME)) SEPARATOR ', '), 1)";
                        break;
                    case 'REQUEST_TITLE':
                        $order['by'] = 'b.REQUEST_TITLE';
                        break;
                    default:
                        $order['by'] = 'b.ID';
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

        $where .= "1";
        $having .= "1";

        $result = [];

        $stageArray = array('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING', 'FINAL_INVOICE', '1', '2', '4', 'WON');

        $data = $this->DB->Query(
            "SELECT DISTINCT 
                        b.ID_Z, b.ID, b.REQUEST_TITLE, b.DOGOVOR_TABLE, b.MATERIAL, b.COMPANY_TITLE, b.ACCOUNT, b.price_discount, b.OPLATA, b.STAGE_ID, 
                        i.DATE, 
                        a.DATE AS DATE_ACT_VR, a.SEND_DATE AS SEND_DATE_ACT_VR, a.NUMBER AS ACT_VR,
                        GROUP_CONCAT(DISTINCT CONCAT(usr.NAME, ' ', usr.LAST_NAME) SEPARATOR ', ') as ASSIGNED 
                    FROM `ba_tz` b 
                    INNER JOIN `INVOICE` i ON b.ID=i.TZ_ID 
                    LEFT JOIN `AKT_VR` a ON b.ID=a.TZ_ID
                    LEFT JOIN `assigned_to_request` as ass ON ass.deal_id=b.ID_Z
                    LEFT JOIN `b_user` as usr ON usr.ID=ass.user_id
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
                    GROUP BY b.ID 
                    HAVING {$having} 
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT 
                        b.ID val 
                    FROM `ba_tz` b 
                    INNER JOIN `INVOICE` i ON b.ID=i.TZ_ID 
                    LEFT JOIN `AKT_VR` a ON b.ID=a.TZ_ID
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> ''
                    GROUP BY b.ID"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT 
                        b.ID val, GROUP_CONCAT(CONCAT(usr.NAME, ' ', usr.LAST_NAME) SEPARATOR ', ') as ASSIGNED 
                    FROM `ba_tz` b 
                    INNER JOIN `INVOICE` i ON b.ID=i.TZ_ID 
                    LEFT JOIN `AKT_VR` a ON b.ID=a.TZ_ID
                    LEFT JOIN `assigned_to_request` as ass ON ass.deal_id=b.ID_Z
                    LEFT JOIN `b_user` as usr ON usr.ID=ass.user_id
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
                    GROUP BY b.ID
                    HAVING {$having} "
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $row['STAGE_NUMBER'] = '';
            $row['DATE'] = !empty($row['DATE']) ? date('d.m.Y',  strtotime($row['DATE'])) : '';
            $row['DATE_ACT_VR'] = !empty($row['DATE_ACT_VR']) ? date('d.m.Y',  strtotime($row['DATE_ACT_VR'])) : '';
            $row['SEND_DATE_ACT_VR'] = !empty($row['SEND_DATE_ACT_VR']) ? date('d.m.Y',  strtotime($row['SEND_DATE_ACT_VR'])) : '';

            $price = floatval($row['price_discount']);
            $oplata = floatval($row['OPLATA']);

            if (
                in_array($row['STAGE_ID'], $stageArray) &&
                $price > 0.0 &&
                empty($oplata)
            ) {
                $row['color'] = 'bg-red';
                $row['title'] = 'Счет не оплачен';
            } else if (
                in_array($row['STAGE_ID'], $stageArray) &&
                $price > 0.0 &&
                !empty($oplata) &&
                $price > $oplata
            ) {
                $row['color'] = 'bg-light-pink';
                $row['title'] = 'Счет оплачен не полностью';
            } else if (
                in_array($row['STAGE_ID'], $stageArray) &&
                $price <= $oplata
            ) {
                $row['color'] = 'bg-green';
                $row['title'] = 'Счет оплачен полностью';
            } else if (
                in_array($row['STAGE_ID'], $stageArray)
                && empty($price)
            ) {
                $row['color'] = 'bg-grey';
                $row['title'] = 'Оплата не требуется';
            } else {
                $row['color'] = 'bg-grey';
                $row['title'] = 'Заявка неуспешна';
            }

            $row['PRICE'] = StringHelper::priceFormatRus($row['PRICE']);

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @return array
     */
    public function getConfirmNotAccountTz()
    {
        $year = date("Y", time())%10 ? substr(date("Y", time()), -2) : date("Y", time());

        $smtp = $this->DB->Query(
            "SELECT b.ID_Z, b.ID, b.REQUEST_TITLE, b.COMPANY_TITLE 
                    FROM ba_tz b, `CHECK_TZ` ch 
                    WHERE b.ACCOUNT IS NULL AND ch.tz_id = b.ID AND ch.confirm = 1 AND b.REQUEST_TITLE LIKE '%/{$year}' GROUP BY b.ID ORDER BY b.ID DESC");

        $result = [];

        while ($row = $smtp->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * добавляет к ответственным в заявке новых ответственных
     * @param int $dealId - ид сделки
     * @param array $arrayUserId - список ид новых ответственных
     */
	public function addAssignedToRequest(int $dealId, array $arrayUserId)
    {
        $userModel = new User();

        $assigned = $userModel->getAssignedByDealId($dealId);

        $userIdList = [];

        foreach ($assigned as $row) {
            $userIdList[] = $row['user_id'];
        }

        $resultArray = array_diff($arrayUserId, $userIdList);

        foreach ($resultArray as $item) {
            $this->DB->Insert('assigned_to_request', ['deal_id' => $dealId, 'user_id' => (int)$item]);
        }
    }

    public function getStageRequestById($dealId)
	{
		$request =  CCrmDeal::GetByID($dealId);

		return $request['STAGE_ID'];
	}

	public function getTakenDealByDealId($dealId)
	{
		$takenDeal = $this->DB->Query("SELECT TAKEN_ID_DEAL FROM ba_tz WHERE ID_Z = {$dealId}")->Fetch();

		return $takenDeal['TAKEN_ID_DEAL'];
	}

    public function updateDealScheme($dealId, $schemeId)
    {
        $this->DB->Query("UPDATE ba_tz SET scheme_id = '{$schemeId}' WHERE ID_Z = {$dealId}");
    }

    public function getDealScheme($schemeId)
    {
        return $this->DB->Query("SELECT name FROM ulab_material_scheme WHERE id = '{$schemeId}'")->Fetch()['name'];
    }

    /**
     * @desc Получает специфичные данные для разных типов заявок
     * @param int $dealId
     * @param string $table
     * @return array
     */
    public function getApplicationTypeData(int $dealId, string $table)
    {
        $query = $this->DB->Query(
            "SELECT t.*,
                    umtr.material_id,
                    l.NAME AS laboratory_name,
                    m.NAME AS material_name,
                    (SELECT COUNT(*) 
                     FROM ulab_material_to_request 
                     WHERE deal_id = {$dealId} 
                        AND work_id = t.id 
                        AND material_id = umtr.material_id) AS quantity
             FROM {$table} AS t 

             LEFT JOIN (
                SELECT DISTINCT work_id, material_id 
                FROM ulab_material_to_request 
                WHERE deal_id = {$dealId}
             ) AS umtr
             ON t.id = umtr.work_id 

             LEFT JOIN ba_laba AS l
             ON t.lab_id = l.ID

             LEFT JOIN MATERIALS AS m
             ON umtr.material_id = m.ID

             WHERE t.deal_id = {$dealId}"
        );

        $result = [];
        while ($row = $query->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }
}