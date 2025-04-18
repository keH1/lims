<?php

/**
 * Модель для работы с пользователем
 * Class User
 */
class User extends Model
{
	/**
	 * @param $id
	 * @return mixed
	 */
	public function getUserById($id)
	{
		return CUser::GetByID($id)->Fetch();

	}
    /**
     * Получить инфу текущего авторизированного юзера
     * @return array
     */
    public function getCurrentUser()
    {
        $user = CUser::GetByID(App::getUserId());

        if ( !empty($user) ) {
            global $USER;
            $userInfo = $user->Fetch();
            $userInfo['groups'] = $USER->GetUserGroupArray();

            return $userInfo;
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        $order = 'ASC';
        $by='ID';
        $filter = ['ACTIVE' => 'Y'];
        $tmp = 'sort';
        $users = CUser::GetList($by, $order, $filter);

        $result = [];

        while ($row = $users->Fetch()) {
            $row['WORK_DEPARTMENT'] =trim($row['WORK_DEPARTMENT']);
            $row['WORK_POSITION'] = mb_strtoupper(mb_substr($row['WORK_POSITION'], 0, 1)) . mb_substr($row['WORK_POSITION'], 1);
            $row['NAME'] = trim($row['NAME']);
            $row['LAST_NAME'] = trim($row['LAST_NAME']);

            $result[] = $row;
        }

        return $result;
    }

    /**
     * Получить список юзеров
     * @param string[] $order - сортировка: ['имя_поля' => 'asc|desc']
     * @param array $bitrixFilter - фильтр битрикса: ['ACTIVE' => 'Y']
     * @param array $bitrixParams
     * @param array $customFilter
     * @return array
     */
    public function getUserList($order = ['LAST_NAME' => 'asc'], $bitrixFilter = [], $bitrixParams = [], $customFilter = []): array
    {
        $tmp = [];
        $users = CUser::GetList($order, $tmp, $bitrixFilter, $bitrixParams);

        $result = [];

        while ($row = $users->Fetch()) {
            if ( !empty($customFilter) ) {
                $userData = CUser::GetByID($row['ID'])->Fetch();

                if (
                    (isset($customFilter['UF_DEPARTMENT']) && !empty($userData['UF_DEPARTMENT'])
                    && empty(array_intersect($customFilter['UF_DEPARTMENT'], $userData['UF_DEPARTMENT'])))
                    && (isset($customFilter['ID'])
                    && !in_array($row['ID'], $customFilter['ID']))
                ) {
                    continue;
                }
            }

            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param false $isMain
     * @return array
     */
    public function getAssignedUserList($isMain = false): array
    {
        $filter['ACTIVE'] = 'Y';

//        if ($isMain) {
//            $filter['GROUPS_ID'] = [25, 23];
//        }

        $users = $this->getUserList(
            ['LAST_NAME' => 'asc'],
            $filter,
            [],
            [
                // TODO: надо исправить этот костыль
//                'UF_DEPARTMENT' => [54, 55, 56, 57, 58], // ИД лабораторий
//                'ID' => [1, 9, 35, 53, 62, 56, 43, 83] // ИД отдельных сотрудников
            ]
        );

        foreach ($users as &$user) {
            $user['NAME'] = trim($user['NAME']);
            $user['LAST_NAME'] = trim($user['LAST_NAME']);
        }

        return $users;
    }


    /**
     * @param $labIdList
     * @return array
     */
    public function getAssignedUserListByLab($labIdList): array
    {
        if ( empty($labIdList) ) {
            return [];
        }
        
        $labDepartment = [
            1 => '54', // ЛФХИ
            4 => '55', // ДСЛ
            3 => '56', // ЛФМИ
            2 => '57', // ЛСМ
            5 => '58', // ОСК
            6 => '53', // Администрация ИЦ
        ];


        $departmentList = [];

        foreach ($labIdList as $lab) {
            if ( !isset($labDepartment[$lab]) ) { continue; }
            
            $departmentList[] = $labDepartment[$lab];
        }

        $str = implode(', ', $departmentList);

        $result = [];

        if ( !empty($str) ) {
            $sql = $this->DB->Query(
                "select u.ID, u.NAME, u.LAST_NAME from b_intranet_user2dep as d, b_user as u 
                where u.ID = d.USER_ID and d.DEPARTMENT_ID in ({$str}) and u.ACTIVE = 'Y'
                order by d.DEPARTMENT_ID, u.LAST_NAME"
            );

            while ($row = $sql->Fetch()) {
                $result[] = $row;
            }
        }

        return $result;
    }


    /**
     * @param $dealId
     * @param $dataList
     * @return bool
     */
    public function setAssignedUserList($dealId, $dataList)
    {
        $resultQuery = $this->DB->Query("DELETE FROM `assigned_to_request` WHERE deal_id = {$dealId} AND is_main = 1");

        if ( $resultQuery === false ) {
            return false;
        }

        $isFirst = true;
        foreach ($dataList as $item) {
            $data = [
                'deal_id' => $dealId,
                'user_id' => $item,
                // 'is_main' => $isFirst? 1 : 0,
                'is_main' => 1,
            ];
            if ($isFirst) $isFirst = false;

            $sqlData = $this->prepearTableData('assigned_to_request', $data);
            $resultInsert = $this->DB->Insert('assigned_to_request', $sqlData);

            if ( $resultInsert === false ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $dealId
     * @return array
     */
    public function getAssignedByDealId($dealId, $isMain = false )
    {
        if ( $isMain ) {
            $where = "is_main = 1";
        } else {
            $where = '1';
        }

        $users = $this->DB->Query("SELECT user_id, is_main FROM `assigned_to_request` WHERE deal_id = {$dealId} and {$where} order by is_main desc");

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

            $result[$row['user_id']] = $resultData;
        }

        return array_values($result);
    }

    /**
     * получает группы к которым принадлежит пользователь
     * @return array
     */
    public function getUserGroups(): array
    {
        return App::getUserGroupIds() ?? [];
    }

    /**
     * получает данные пользователя
     * @param int $id
     * @return array
     */
    public function getUserData(int $id): array
    {
        $response = [];

        $user = CUser::GetByID($id)->Fetch();

        if ( !empty($user) ) {
            global $USER;
            $shortName = StringHelper::shortName($user['NAME']);

            $user['short_name'] = "{$shortName}. {$user['LAST_NAME']}";
            $user['user_name'] = "{$user['NAME']} {$user['LAST_NAME']}";
            $user['groups'] = $USER->GetUserGroupArray();
            $user['id'] = $user['ID'];

            $response = $user;
        }

        return $response;
    }

    /**
     * @param int $groupId
     * @return array
     */
    public function getUsersByGroupId(int $groupId): array
    {
        $response = [];

        $result = CGroup::GetGroupUser($groupId);

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * @return mixed
     */
    public function getCurrentUserId()
    {
        return App::getUserId();
    }

	/**
	 * @param $id
	 * @return string
	 */
	public function getDepartmentName($id)
	{
		$department = [
			'54' => 'ЛФХИ',
			'55' => 'ДСЛ',
			'56' => 'ЛФМИ',
			'57' => 'ЛСМ',
			'58' => 'ОСК',
			'59' => 'Бухгалтерия',
		];

		return $department[$id];
	}

    /**
     * @param bool $isMain
     * @return array
     */
    public function getUsersForSecondment($isMain = false): array
    {
        $order = ['LAST_NAME' => 'asc'];
        $filter['ACTIVE'] = 'Y';

        if ($isMain) {
            $filter['GROUPS_ID'] = [25, 23];
        }

        $params = [];
        $customFilter = [];

        $users = $this->getUserList(
            $order,
            $filter,
            $params,
            $customFilter
        );

        foreach ($users as &$user) {
            $user['NAME'] = !empty($user['NAME']) ?
                htmlentities(trim($user['NAME']), ENT_QUOTES, 'UTF-8') : '';
            $user['LAST_NAME'] = !empty($user['LAST_NAME']) ?
                htmlentities(trim($user['LAST_NAME']), ENT_QUOTES, 'UTF-8') : '';
            $user['SECOND_NAME'] = !empty($user['SECOND_NAME']) ?
                htmlentities(trim($user['SECOND_NAME']), ENT_QUOTES, 'UTF-8') : '';
            $user['WORK_POSITION'] = !empty($user['WORK_POSITION']) ?
                htmlentities(trim($user['WORK_POSITION']), ENT_QUOTES, 'UTF-8') : '';
        }

        return $users;
    }

    public function getUserByDepartment()
	{
		$tmp = [];
		$order = ['LAST_NAME' => 'asc'];
		$bitrixFilter = ['ACTIVE' => 'Y'];
		$bitrixParams = [];
		$users = CUser::GetList($order, $tmp, $bitrixFilter, $bitrixParams);

		$userDep = [];

		$department = [
			'54' => 'ЛФХИ',
			'55' => 'ДСЛ',
			'56' => 'ЛФМИ',
			'57' => 'ЛСМ',
			'58' => 'ОСК',
		];

		while ($row = $users->Fetch()) {
			$userData = CUser::GetByID($row['ID'])->Fetch();

			$name = trim($userData['NAME']);
			$lastName = trim($userData['LAST_NAME']);
			$shortName = StringHelper::shortName($name);

				if (!empty($userData['UF_DEPARTMENT'][0] && array_key_exists($userData['UF_DEPARTMENT'][0], $department))) {
					$userDep[$userData['UF_DEPARTMENT'][0]][$row['ID']] = [
						'user_id'       => $row['ID'],
						'name'          => $name,
						'last_name'     => $lastName,
						'user_name'     => "{$lastName} {$name}",
						'short_name'    => "{$shortName}. {$lastName}",
						'is_main'       => 0,
						'department'    => $userData['UF_DEPARTMENT'][0],
						'department_name'    => $this->getDepartmentName($userData['UF_DEPARTMENT'][0]),
					];
				}
		}
		ksort($userDep);
		return $userDep;
	}

    /**
     * @param $id
     * @return mixed
     */
	public function getDepartmentByUserId($id)
	{
//		$res = $this->DB->Query("SELECT `DEPARTMENT_ID` as depId FROM `b_intranet_user2dep` WHERE `USER_ID` = {$id}")->Fetch();
        $res = $this->DB->Query("SELECT `UF_DEPARTMENT` as depId FROM `b_uts_user` WHERE `VALUE_ID` = {$id}")->Fetch();

        $tmp = unserialize($res['depId']);

        return $tmp[0];
	}


    /**
     * получает список пользователей по ид департамента (из битрикса)
     * @param $depId
     * @return array
     */
	public function getUserByDep($depId)
    {
        $sql = $this->DB->Query(
            "SELECT u.ID, u.`NAME`, u.`LAST_NAME`, u.WORK_POSITION as depId FROM `b_uts_user` as uts
            inner join b_user as u on uts.VALUE_ID = u.ID
            WHERE `UF_DEPARTMENT` like '%i:{$depId};%'"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    public function getUserFromDep()
    {
        $sql = $this->DB->Query(
            "SELECT u.ID, u.`NAME`, u.`LAST_NAME`, u.WORK_POSITION, uts.`UF_DEPARTMENT` FROM `b_uts_user` as uts
            inner join b_user as u on uts.VALUE_ID = u.ID where u.ACTIVE='Y'"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $deps = unserialize($row['UF_DEPARTMENT']);

            $shortName = StringHelper::shortName($row['NAME']);
            $row['short_name'] = "{$shortName}. {$row['LAST_NAME']}";

            foreach ($deps as $dep) {
                $result[$dep][] = $row;
            }

        }

        return $result;
    }


	public function getUsersByIdArr($idArr)
    {
        $result = [];
        
        if (empty($idArr)) {
            $where = " 1";
        } else {
            $idStr = implode(",", $idArr);
            $where = "ID IN ({$idStr})";
        }


        $stmt = $this->DB->Query("
            SELECT ID, NAME, SECOND_NAME, LAST_NAME, CONCAT(LAST_NAME, ' ', NAME, ' ', SECOND_NAME) AS fio
            FROM b_user 
            WHERE {$where}
        ");

        while ($row = $stmt->fetch()) {
            $result[$row["ID"]] = $row;
        }

        return $result;
    }

    /**
     * @param $dep
     * @return array
     */
    public function checkHeader($dep)
    {
        $res = $this->DB->Query("SELECT *  FROM `b_uts_iblock_5_section` lab, `b_user` u 
						WHERE lab.`VALUE_ID` = {$dep} AND 
						lab.`UF_HEAD` = u.`ID`")->Fetch();

        if ( empty($res) ) { return []; }

        $name = trim($res['NAME']);
        $lastName = trim($res['LAST_NAME']);
        $secondName = trim($res['SECOND_NAME']);
        $shortName = StringHelper::getInitials($name).' '. StringHelper::getInitials($secondName).' '.$lastName;
        $work_position =  trim($res['WORK_POSITION']);

        $result = [
            'user_id'       => $res['ID'],
            'name'          => trim($res['NAME']),
            'last_name'     => trim($res['LAST_NAME']),
            'user_name'     => "{$name} {$lastName}",
            'short_name'    => $shortName,
            'work_position' => $work_position
        ];

        return $result;
    }

    public function getUserShortById($id)
    {
        $result = [];

        $res = $this->DB->Query("SELECT *  FROM `b_user` u 
						WHERE u.`ID` =" . $id)->Fetch();

        $name = trim($res['NAME']);
        $lastName = trim($res['LAST_NAME']);
        $secondName = trim($res['SECOND_NAME']);
        $shortName = trim(
            ($name ? StringHelper::getInitials($name) . ' ' : '') .
            ($secondName ? StringHelper::getInitials($secondName) . ' ' : '') .
            ($lastName ? $lastName : '')
        );

        if (!empty($name) && empty($secondName) && empty($lastName)) {
            $shortName = rtrim($shortName, '.');
        }
        // $shortName = StringHelper::getInitials($name).' '. StringHelper::getInitials($secondName).' '.$lastName;
        $work_position = trim($res['WORK_POSITION']);

        $result = [
            'user_id'       => $res['ID'],
            'name'          => trim($res['NAME']),
            'last_name'     => trim($res['LAST_NAME']),
            'user_name'     => "{$name} {$lastName}",
            'short_name'    => $shortName,
            'work_position' => $work_position,
            'department'    => $res['UF_DEPARTMENT'][0],
            'department_name'    => $this->getDepartmentName($res['UF_DEPARTMENT'][0])
        ];

        return $result;
    }

    public function getUserList1($order = ['LAST_NAME' => 'asc'], $bitrixFilter = [], $bitrixParams = [], $customFilter = []): array
    {
        $tmp = [];
        $users = CUser::GetList($order, $tmp, $bitrixFilter, $bitrixParams);

        $result = [];


        while ($row = $users->Fetch()) {
            $name = trim($row['NAME']);
            $lastName = trim($row['LAST_NAME']);
            $secondName = trim($row['SECOND_NAME']);
            $shortName = StringHelper::getInitials($name).' '. StringHelper::getInitials($secondName).' '.$lastName;
            $work_position =  trim($row['WORK_POSITION']);

            $result[] = [
                'user_id'       => $row['ID'],
                'name'          => trim($row['NAME']),
                'last_name'     => trim($row['LAST_NAME']),
                'user_name'     => "{$name} {$lastName}",
                'short_name'    => $shortName,
                'work_position' => $work_position
            ];
//            if ( !empty($customFilter) ) {
//                $userData = CUser::GetByID($row['ID'])->Fetch();
//
//                if (
//                    (isset($customFilter['UF_DEPARTMENT'])
//                    && empty(array_intersect($customFilter['UF_DEPARTMENT'], $userData['UF_DEPARTMENT'])))
//                    && (isset($customFilter['ID'])
//                    && !in_array($row['ID'], $customFilter['ID']))
//                ) {
//                    continue;
//                }
//            }

//            $result[] = $row;
        }

        return $result;
    }

    public function getDepartmentsList()
    {
        $result = [];

        $res = $this->DB->Query("
            SELECT
                bl.id_dep AS ID,
                bl.ID AS ID_ULAB,
                bl.NAME AS NAME,
                IFNULL(
                    GROUP_CONCAT(CONCAT(SUBSTRING(bu.NAME, 1, 1), '. ', bu.LAST_NAME) ORDER BY bu.NAME ASC SEPARATOR ', '),
                    NULL
                ) AS FULL_NAME,
                IFNULL(bu.ID, NULL) AS ID_HEAD_USER
            FROM
                ba_laba bl
            LEFT JOIN b_user bu ON bl.HEAD_ID = bu.ID
            where bl.id_dep is not null
            GROUP BY
                bl.id_dep,
                bl.ID,
                bl.NAME");

        while ($row = $res->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function updateUserDepartment($userId, $departmentId)
    {
        $userId = (int)$userId;
        $departmentId = (int)$departmentId;

        $fieldsToUpdate = array(
            "UF_DEPARTMENT" => array($departmentId)
        );

        $user = new CUser;
        $updateResult = $user->Update($userId, $fieldsToUpdate);

        if ($updateResult) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateUser($id, $data)
    {
        $user = new CUser;
        $groupId = ($id == 1) ? [1, 12] : [12];

        $fields = [
            "NAME"              => $data['NAME'],
            "LAST_NAME"         => $data['LAST_NAME'],
            "SECOND_NAME"       => $data['SECOND_NAME'],
            "EMAIL"             => $data['EMAIL'],
            "LOGIN"             => $data['LOGIN'],
            "WORK_POSITION" => $data['WORK_POSITION'],
            "GROUP_ID"          => $groupId,
            "UF_ORG_ID" => App::getOrganizationId()
        ];

        if (!empty($data['NEW_PASSWORD']) && !empty($data['NEW_PASSWORD_CONFIRM'])) {
            $fields['PASSWORD'] = $data['NEW_PASSWORD'];
            $fields['CONFIRM_PASSWORD'] = $data['NEW_PASSWORD_CONFIRM'];
        }

        $user->Update($id, $fields);

        if ($user->LAST_ERROR) {
            return [
                'success' => false,
                'error' => [
                    'message' => $user->LAST_ERROR,
                ]
            ];
        } else {
            return [
                'success' => true,
            ];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function insertUser($data)
    {
        $user = new CUser;
        // $groupId = [12];
        $groupId = [1];

        $fields = [
            "NAME" => $data['NAME'],
            "LAST_NAME" => $data['LAST_NAME'],
            "SECOND_NAME" => $data['SECOND_NAME'],
            "EMAIL" => $data['EMAIL'],
            "LOGIN" => $data['LOGIN'],
            "WORK_POSITION" => $data['WORK_POSITION'],
            "LID" => "ru",
            "ACTIVE" => "Y",
            "GROUP_ID" => $groupId,
            "PASSWORD" => $data['NEW_PASSWORD'],
            "CONFIRM_PASSWORD" => $data['NEW_PASSWORD_CONFIRM'],
            "UF_ORG_ID" => App::getOrganizationId()
        ];

        $userId = $user->Add($fields);

        if (intval($userId) > 0) {
            return [
                'success' => true,
                'data' => $userId
            ];
        } else {
            return [
                'success' => false,
                'error' => [
                    'message' => $user->LAST_ERROR,
                ]
            ];
        }
    }

    /**
     * Получает список статусов пользователей
     * @return array
     */
    public function getStatusList(): array
    {
        $result = [];
        $data = $this->DB->Query("SELECT * FROM ulab_user_status_list");

        while ($row = $data->Fetch()) {
            $result[$row['id']] = $row['status'];
        }

        return $result;
    }

    public function getAllUsersList()
    {
        $data = $this->DB->Query("
                SELECT 
                    u.ID as ID, CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME) AS FULL_NAME,
                    CASE
                        WHEN u.WORK_POSITION IS NULL OR u.WORK_POSITION = '' THEN 'Должность не указана'
                        ELSE u.WORK_POSITION
                    END AS WORK_POSITION
                FROM
                    b_user u
                WHERE
                    u.ACTIVE = 'Y'
                ORDER BY FULL_NAME;"
        );

        while ($row = $data->Fetch()) {
            $users[] = $row;
        }
        return $users;
    }

    /**
     * @param array $filter
     * @return array
     * @hide true
     */
    public function getUsersForStatusJournal(array $filter = [])
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'FULL_NAME',
            'dir' => 'DESC'
        ];

        if ( !empty($filter['search']) ) {
            // ФИО
            if (isset($filter['search']['FULL_NAME'])) {
                $where .= "CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME) LIKE '%{$filter['search']['FULL_NAME']}%' AND ";
            }
            // СТАТУС
            if (isset($filter['search']['USER_STATUS'])) {
                if($filter['search']['USER_STATUS'] != -1)
                    $where .= "COALESCE(uus.user_status, 1) = {$filter['search']['USER_STATUS']} AND ";
            }
            // ЗАМЕНА
            if (isset($filter['search']['REPLACEMENT_USER_ID'])) {
                if($filter['search']['REPLACEMENT_USER_ID'] == 1)
                    $where .= "uus.replacement_user_id IS NOT NULL AND ";
                else if($filter['search']['REPLACEMENT_USER_ID'] == 2)
                    $where .= "uus.replacement_user_id IS NULL AND ";
            }
            // ДОЛЖНОСТЬ
            if (isset($filter['search']['JOB_TITLE'])) {
                $where .= "uus.job_title LIKE '%{$filter['search']['JOB_TITLE']}%' AND ";
            }
        }

        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }
            switch ($filter['order']['by']) {
                case 'FULL_NAME':
                    $order['by'] = "CASE WHEN FULL_NAME IS NULL OR FULL_NAME = '' THEN 1 ELSE 0 END, FULL_NAME";
                    break;
                case 'USER_STATUS':
                    $order['by'] = "COALESCE(uus.user_status, 1)";
                    break;
                case 'REPLACEMENT_USER_ID':
                    $order['by'] = "uus.replacement_user_id";
                    break;
                case 'JOB_TITLE':
                    $order['by'] = "uus.job_title";
                    break;
                default:
                    $order['by'] = 'FULL_NAME';
            }
        }

        if (isset($filter['paginate'])) {
            $offset = 0;
            // количество строк на страницу
            if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                $length = $filter['paginate']['length'];

                if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                    $offset = $filter['paginate']['start'];
                }
                $limit = "LIMIT {$offset}, {$length}";
            }
        }

        $where .= "1 ";

        $data = $this->DB->Query("
            SELECT
                u.ID as ID,
                CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME) AS FULL_NAME,
                COALESCE(uus.user_status, 1) as USER_STATUS,
                uus.replacement_user_id as REPLACEMENT_USER_ID,
                uus.replacement_date as REPLACEMENT_DATE,
                uus.replacement_note as REPLACEMENT_NOTE,
                uus.job_title as JOB_TITLE
            FROM
                b_user u
            LEFT JOIN
                ulab_user_status uus ON uus.user_id = u.ID
            WHERE
                u.ACTIVE = 'Y' AND {$where}
            ORDER BY
                {$order['by']} {$order['dir']} {$limit};
        ");

        $dataTotal = (int)$this->DB->Query("
                SELECT
                    count(*) as val
                FROM
                    b_user u
                WHERE
                    u.ACTIVE = 'Y'"
        )->Fetch()['val'];

        $dataFiltered = (int)$this->DB->Query("
            SELECT COUNT(*) AS val
                FROM (
                    SELECT
                        CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME) AS FULL_NAME,
                        COALESCE(uus.user_status, 1) as USER_STATUS,
                        uus.replacement_user_id as REPLACEMENT_USER_ID,
                        uus.replacement_date as REPLACEMENT_DATE,
                        uus.replacement_note as REPLACEMENT_NOTE,
                        uus.job_title as JOB_TITLE
                    FROM
                        b_user u
                    LEFT JOIN
                        ulab_user_status uus ON uus.user_id = u.ID
                    WHERE
                        u.ACTIVE = 'Y' AND {$where}
                    ORDER BY
                        {$order['by']} {$order['dir']}
             ) subquery;"
        )->Fetch()['val'];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * @param $userId
     * @param $statusId
     * @return void
     * @hide true
     */
    public function updateStatus($userId, $statusId)
    {
        $userId = (int)$userId;
        $statusId = $this->sanitize($statusId);

        $count = $this->DB->Query("SELECT COUNT(*) AS val FROM `ulab_user_status` WHERE `user_id` = {$userId}")->fetch()['val'];

        if ($count > 0) {
            $this->DB->Query("UPDATE `ulab_user_status` SET `user_status` = {$statusId} WHERE `user_id` = {$userId}");
        } else {
            $this->DB->Query("INSERT INTO `ulab_user_status` (`user_id`, `user_status`) VALUES ({$userId}, {$statusId})");
        }

        if ($statusId === 1) {
            $this->updateReplacement($userId, 'NULL');
            $this->updateJob($userId, NULL);
        }
    }

    /**
     * @param $userId
     * @param $replacementId
     * @return void
     * @hide true
     */
    public function updateReplacement($userId, $replacementId)
    {
        $count = $this->DB->Query("SELECT COUNT(*) AS val FROM `ulab_user_status` WHERE `user_id` = {$userId}")->fetch()['val'];
        $currentDate = date("Y-m-d H:i:s");

        if ($count > 0) {
            $this->DB->Query("UPDATE `ulab_user_status` SET `replacement_user_id` = {$replacementId}, `replacement_date` = '{$currentDate}' WHERE `user_id` = {$userId}");
        } else {
            $this->DB->Query("INSERT INTO `ulab_user_status` (`user_id`, `replacement_user_id`, `replacement_date`) VALUES ({$userId}, {$replacementId}, '{$currentDate}')");
        }
    }

    public function updateJob($userId, $text)
    {
        $userId = (int)$userId;
        $text = $this->sanitize($text);

        $count = $this->DB->Query("SELECT COUNT(*) AS val FROM `ulab_user_status` WHERE `user_id` = {$userId}")->fetch()['val'];

        if ($count > 0) {
            $this->DB->Query("UPDATE `ulab_user_status` SET `job_title` = '{$text}' WHERE `user_id` = {$userId}");
        } else {
            $this->DB->Query("INSERT INTO `ulab_user_status` (`user_id`, `job_title`) VALUES ({$userId}, '{$text}')");
        }
    }

    public function updateNote($userId, $text)
    {
        $userId = (int)$userId;
        $text = $this->sanitize($text);

        $count = $this->DB->Query("SELECT COUNT(*) AS val FROM `ulab_user_status` WHERE `user_id` = {$userId}")->fetch()['val'];

        if ($count > 0) {
            $this->DB->Query("UPDATE `ulab_user_status` SET `replacement_note` = '{$text}' WHERE `user_id` = {$userId}");
        } else {
            $this->DB->Query("INSERT INTO `ulab_user_status` (`user_id`, `replacement_note`) VALUES ({$userId}, '{$text}')");
        }
    }

    /**
     * @param int $id
     */
    public function deleteUser($id)
    {
        $this->DB->Query("UPDATE b_user SET ACTIVE = 'N' WHERE ID = '{$id}'");
    }

    public function checkUserHasRepalcement($userId) {
        $count = $this->DB->Query("SELECT COUNT(*) AS val FROM ulab_user_status WHERE user_id = {$userId} AND replacement_user_id IS NOT NULL")->fetch()['val'];

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param int $roleId
     * @return array
     */
    public function getUsersByRoleId(int $roleId): array
    {
        $permission = new Permission();
        $users = $this->getUsers();

        $data = [];

        foreach ($users as $us) {
            $userRole = $permission->getUserRole($us['ID']);
            if ($userRole != $roleId)
                continue;

            $data[] = [
                'id' => $us['ID'],
                'FIO' => $us['LAST_NAME'] . ' ' .
                    mb_strtoupper(mb_substr($us['NAME'], 0, 1)) . '. ' .
                    mb_strtoupper(mb_substr($us['SECOND_NAME'], 0, 1)) . '.',
                'department' => empty($us['WORK_DEPARTMENT'])? 'Отдел не указан' : $us['WORK_DEPARTMENT'],
                'position' => empty($us['WORK_POSITION'])? 'Должность не указана' : $us['WORK_POSITION'],
                'role' => $userRole,
            ];
        }

        return $data;
    }

    /**
     * @param string $email
     * @param string $login
     * @param int $userId
     * @return array
     */
    public function getUsersDataForCheck($email, $login, $userId = 0)
    {
        $result = [
            'email' => false,
            'login' => false
        ];
        
        $fieldsToCheck = [
            'email' => ['field' => 'EMAIL', 'value' => $email, 'result' => 'email'],
            'login' => ['field' => 'LOGIN', 'value' => $login, 'result' => 'login']
        ];
        
        foreach ($fieldsToCheck as $checkData) {
            if (empty($checkData['value'])) {
                continue;
            }
            
            $filter = [$checkData['field'] => $checkData['value']];
            $rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter);
            
            if ($rsUsers->SelectedRowsCount() > 0) {
                if ($userId > 0) {
                    $valueExists = true;
                    while ($user = $rsUsers->Fetch()) {
                        if ($user['ID'] == $userId) {
                            $valueExists = false;
                            break;
                        }
                    }
                    
                    $result[$checkData['result']] = $valueExists;
                } else {
                    $result[$checkData['result']] = true;
                }
            }
        }
        
        return $result;
    }
}