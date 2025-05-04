<?php

/**
 * Клас лаборатории
 * Class Lab
 */
class Lab extends Model
{
    const USERS_CAN_EDIT_CONDITIONS = [1, 7, 61, 75, 10, 33, 9, 58, 13, 11, 15, 100]; //пользователи могут редактировать условия

    /**
     * @param $labId
     * @return int
     */
    public function getDepartmentIdByLabId($labId)
    {
//        return $this->DB->Query("select * from ba_laba where ID = {$labId}")->Fetch()['id_dep'];
        $labDepartment = [
            1 => 54, // ЛФХИ
            4 => 55, // ДСЛ
            3 => 56, // ЛФМИ
            2 => 57, // ЛСМ
            5 => 58, // ОСК
            6 => 53, // Администрация ИЦ
        ];

        return $labDepartment[$labId];
    }


    /**
     * @param $userId
     * @return array|false
     */
    public function getLabByUserId($userId)
    {
//        return $this->DB->Query("select l.* from ba_laba as l, b_intranet_user2dep as dep where l.id_dep = dep.DEPARTMENT_ID and dep.USER_ID = {$userId}")->Fetch();
        $sql = $this->DB->Query(
            "select UF_DEPARTMENT from b_uts_user where VALUE_ID = {$userId}"
        )->Fetch();

        $deps = unserialize($sql['UF_DEPARTMENT']);

        return $this->DB->Query("select l.* from ba_laba as l where l.id_dep = {$deps[0]}")->Fetch();
    }


    /**
     * получает лаборатории и юзеров в них
     * @param $labId
     * @return array
     */
    public function getLabAndUser($labId = 0)
    {
        $organizationId = App::getOrganizationId();
        $userModel = new User();

        $where = "organization_id = {$organizationId}";

        if ($labId > 0) {
            $where .= " AND l.id = {$labId}";
        }

        $sql = $this->DB->Query(
           "SELECT l.*
            FROM ba_laba AS l
            WHERE {$where}
        ");

        $result = [];
        while ($row = $sql->Fetch()) {
            $depUserSql = $this->DB->Query(
                "select utsu.VALUE_ID 
                        from b_uts_user as utsu
                        inner join b_user as u on u.ID = utsu.VALUE_ID
                        where utsu.UF_DEPARTMENT like '%:{$row['id_dep']};%' and u.ACTIVE = 'Y'
                        and utsu.UF_ORG_ID = {$organizationId}
            ");

            $result[$row['id_dep']]['short_name'] = $row['short_name'];
            $result[$row['id_dep']]['lab_id'] = $row['ID'];

            while ($rowUser = $depUserSql->Fetch()) {
                if ( !empty($rowUser['VALUE_ID']) ) {
                    $user = $userModel->getUserData($rowUser['VALUE_ID']);
                    if ( empty($user) ) { continue; }
                    $userData = [
                        'id' => $user['ID'],
                        'name' => $user['NAME'],
                        'last_name' => $user['LAST_NAME'],
                        'user_name' => $user['user_name'],
                        'short_name' => $user['short_name'],
                    ];

                    $result[$row['id_dep']]['users'][$rowUser['VALUE_ID']] = $userData;
                }
            }
        }
        return $result;
    }


    /**
     * @return array
     */
    public function getList(): array
    {
        $organizationId = App::getOrganizationId();
        $results = [];
        $laboratories = $this->DB->Query("SELECT * FROM `ba_laba` where organization_id = {$organizationId} and id_dep is not null");

        while ($row = $laboratories->Fetch()) {
            $row['DEPARTMENT'] = $row['id_dep'];
            $results[] = $row;
        }

        return $results;
    }


    /**
     * @param $labId
     * @return array
     */
    public function get($labId)
    {
        $organizationId = App::getOrganizationId();
        if (empty($labId)) { return []; }

        return $this->DB->Query("select * from ba_laba where organization_id = {$organizationId} and ID = {$labId}")->Fetch();
    }


    /**
     * получает лабораторию по ид битриксового департамента
     * @param $bitrixDepartmentId
     * @return array
     * @throws Exception
     */
    public function getLabByBitrixDepartmentId($bitrixDepartmentId)
    {
        $organizationId = App::getOrganizationId();
        if (empty($bitrixDepartmentId)) { return []; }

        $sqlLab = $this->DB->Query("select * from ba_laba where organization_id = {$organizationId} and id_dep = {$bitrixDepartmentId}")->Fetch();

        if ( empty($sqlLab) ) {
            return [];
        }

        return $sqlLab;
    }


	/**
	 * @param $idMethod
	 * @return array
	 */
	public function getLabaById($id)
	{
        $organizationId = App::getOrganizationId();
		if (empty($id)) { return []; }

		$result = $this->DB->Query("select * from ba_laba where organization_id = {$organizationId} and ID = {$id}")->Fetch();

		return $result['short_name'];
	}


    /**
     * @param $departmentId
     * @return array
     */
    public function getLabByDepartment($departmentId)
    {
        $organizationId = App::getOrganizationId();
        if (empty($departmentId)) { return []; }

        return $this->DB->Query("select * from ba_laba where organization_id = {$organizationId} and `id_dep` = {$departmentId}")->Fetch();
    }


    /**
     * @return array
     */
    public function getLabaRoom($labIdList = [])
    {
        $organizationId = App::getOrganizationId();
        $where = 'l.ID = r.LAB_ID AND l.organization_id = ' . $organizationId;

        if ( !empty($labIdList) ) {
            $labIdList = array_map('intval', $labIdList);
            $str = implode(',', $labIdList);
            $where .= " AND l.ID IN ({$str})";
        }

        $laboratories = $this->DB->Query(
            "SELECT l.NAME laba_name, r.LAB_ID, r.NUMBER, r.ID room_id, r.NAME room_name, r.PLACEMENT 
                FROM `ba_laba` as l, ROOMS as r 
                WHERE {$where}
                ORDER BY r.LAB_ID"
        );

        $result = [];

        $lastId = 0;
        while ($row = $laboratories->Fetch()) {
            if ( $lastId != $row['LAB_ID'] ) {
                $result[] = [
                    'id' => $row['LAB_ID'],
                    'name' => $row['laba_name'],
                ];

                $result[] = [
                    'id' => $row['room_id'] + 100,
                    'name' => trim($row['room_name']) . ' ' . trim($row['NUMBER']),
                ];

                $lastId = $row['LAB_ID'];
            } else {
                $result[] = [
                    'id' => $row['room_id'] + 100,
                    'name' => trim($row['room_name']) . ' ' . trim($row['NUMBER']),
                ];
            }
        }

        return $result;
    }


    /**
     * @return array
     */
    public function getRooms()
    {
        $organizationId = App::getOrganizationId();

        $smtp = $this->DB->Query("
            SELECT r.* FROM `ROOMS` as r
            INNER JOIN ba_laba AS l
             ON r.LAB_ID = l.ID
            WHERE l.organization_id = {$organizationId}
        ");

        $result = [];

        while ($row = $smtp->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int|array $labID
     * @return array
     */
    public function getRoomByLabId($labID)
    {
        $organizationId = App::getOrganizationId();

        if ( empty($labID) ) {
            return [];
        }
        if ( is_array($labID) ) {
            $labID = implode(', ', $labID);
        }

        $smtp = $this->DB->Query(
            "SELECT r.* 
                FROM ROOMS as r 
                INNER JOIN ba_laba AS l ON r.LAB_ID = l.ID
                WHERE LAB_ID IN ({$labID}) AND l.organization_id = {$organizationId}
                ORDER BY LAB_ID"
        );

        $result = [];

        while ($row = $smtp->Fetch()) {
            $row['name'] = trim($row['NAME']) . ' ' . trim($row['NUMBER']);
            $result[] = $row;
        }

        return $result;
    }


    /**
     * журнал условий
     * @param array $filter
     * @return array
     */
    public function getJournalCondition($filter = [])
    {
        $organizationId = App::getOrganizationId();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'u_c.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            if (!empty($filter['search'])) {
                // соответствие
                if (isset($filter['search']['is_match']) && $filter['search']['is_match'] === '0') {
                    $where .= "(u_c.is_method_match = '{$filter['search']['is_match']}' OR u_c.is_oborud_match = '{$filter['search']['is_match']}') AND ";
                }
                if (isset($filter['search']['is_match']) && $filter['search']['is_match'] === '1') {
                    $where .= "(u_c.is_method_match = '{$filter['search']['is_match']}' AND u_c.is_oborud_match = '{$filter['search']['is_match']}') AND ";
                }
                // дата
                if (isset($filter['search']['created_at'])) {
                    $where .= "LOCATE('{$filter['search']['created_at']}', DATE_FORMAT(u_c.created_at, '%d.%m.%Y %H:%i:%s')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(u_c.created_at >= '{$filter['search']['dateStart']}' AND u_c.created_at <= '{$filter['search']['dateEnd']}') AND ";
                }
                // температура
                if (isset($filter['search']['temp'])) {
                    $where .= "u_c.temp LIKE '%{$filter['search']['temp']}%' AND ";
                }
                // влажность
                if (isset($filter['search']['humidity'])) {
                    $where .= "u_c.humidity LIKE '%{$filter['search']['humidity']}%' AND ";
                }
                // давление
                if (isset($filter['search']['pressure'])) {
                    $where .= "u_c.pressure LIKE '%{$filter['search']['pressure']}%' AND ";
                }
                // помещение
                if (isset($filter['search']['room_name'])) {
                    $where .= "u_c.room_id IN (SELECT r.ID FROM ROOMS r WHERE CONCAT(r.NAME, ' ', r.NUMBER) COLLATE utf8mb3_unicode_ci LIKE '%{$filter['search']['room_name']}%') AND ";
                }
                // помещение (верхний фильтр)
                if (isset($filter['search']['room'])) {
                    $roomId = intval($filter['search']['room']) - 100;
                    $where .= "u_c.room_id = {$roomId} AND ";
                }
            }
        }

        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'created_at':
                    $order['by'] = 'u_c.created_at';
                    break;
                case 'temp':
                    $order['by'] = 'u_c.temp';
                    break;
                case 'humidity':
                    $order['by'] = 'u_c.humidity';
                    break;
                case 'pressure':
                    $order['by'] = 'u_c.pressure';
                    break;
                default:
                    $order['by'] = 'u_c.id';
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

        $where .= "u_c.organization_id = {$organizationId}";

        $result = [];

        $data = $this->DB->Query(
            "SELECT u_c.*, u_c.id u_c_id, CONCAT(r.NAME, ' ', r.NUMBER) room_name  
             FROM ulab_conditions u_c 
             LEFT JOIN ROOMS AS r
             ON r.ID = u_c.room_id 
             WHERE {$where}
             ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT u_c.*, u_c.id u_c_id
                    FROM ulab_conditions u_c 
                    LEFT JOIN ROOMS AS r ON r.ID = u_c.room_id 
                    WHERE u_c.organization_id = {$organizationId}"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT u_c.*, u_c.id u_c_id
                    FROM ulab_conditions u_c 
                    LEFT JOIN ROOMS AS r ON r.ID = u_c.room_id 
                    WHERE {$where}"
        )->SelectedRowsCount();

        //проверка на допуск к редактированию
        $isCanEdit = in_array(App::getUserId(), self::USERS_CAN_EDIT_CONDITIONS);

        while ($row = $data->Fetch()) {
            $row['ru_created_at'] = date('d.m.Y H:i:s', strtotime($row['created_at']));
            $row['is_can_edit'] = $isCanEdit;

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * @deprecated
     * подготовить данные условий
     * @param $data
     * @return array
     */
    private function prepareConditionsData($data)
    {
        $columns = $this->getColumnsByTable('ulab_conditions');

        $sqlData = [];

        foreach ($columns as $column) {
            if (isset($data[$column])) {
                $sqlData[$column] = $this->quoteStr($this->DB->ForSql($data[$column]));
            }
        }

        return $sqlData;
    }

    /**
     * получить данные условий
     * @param int $id
     * @return array
     */
    public function getConditionById(int $id): array
    {
        $response = [];

        if (empty($id) || $id < 0) {
            return $response;
        }

        $organizationId = App::getOrganizationId();

        $result = $this->DB->Query(
            "SELECT * FROM ulab_conditions WHERE id = {$id} AND organization_id = {$organizationId}")->Fetch();

        if (!empty($result)) {
            $result['room_id'] = $result['room_id'] + 100;
            //$result['date'] = date('Y-m-d', strtotime($result['updated_at']));
            $result['date'] = date('Y-m-d H:i', strtotime($result['updated_at']));
            $response = $result;
        }

        return $response;
    }

    /**
     * добавить данные условий
     * @param array $data
     * @return int
     */
    public function addConditions(array $data): int
    {
        $data['organization_id'] = App::getOrganizationId();
        $sqlData = $this->prepearTableData('ulab_conditions', $data);

        $result = $this->DB->Insert('ulab_conditions', $sqlData);

        return intval($result);
    }

    /**
     * обновить данные условий
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateConditions(int $id, array $data)
    {
        $organizationId = App::getOrganizationId();

        $sqlData = $this->prepearTableData('ulab_conditions', $data);

        $where = "WHERE id = {$id} AND organization_id = {$organizationId}";
        return $this->DB->Update('ulab_conditions', $sqlData, $where);
    }

    /**
     * удалить данные условий по id
     * @param int $id
     */
    public function removeConditionById(int $id)
    {
        $organizationId = App::getOrganizationId();
        $this->DB->Query("DELETE FROM ulab_conditions WHERE id = {$id} AND organization_id = {$organizationId}");
    }


	public function getLabList()
	{
        $organizationId = App::getOrganizationId();
		$result = [];
		$res = $this->DB->Query("SELECT * FROM ba_laba WHERE organization_id = {$organizationId}");

		while ($row = $res->Fetch()) {
			$result[] = $row;
		}

		return $result;
	}

    /**
     * @param int|null $protocolId
     * @return array
     */
    public function getConditionByProtocol(?int $protocolId): array
    {
        $response = [];

        if ( empty($protocolId) || $protocolId < 0) {
            return $response;
        }

        $organizationId = App::getOrganizationId();

        $ustSql = $this->DB->Query(
            "SELECT ust.* 
            FROM ulab_gost_to_probe as ugtp  
            INNER JOIN ulab_start_trials ust on ust.ugtp_id = ugtp.id 
            WHERE ugtp.protocol_id = {$protocolId} AND ust.is_actual = 1 ORDER BY ust.ugtp_id ASC, ust.created_at ASC"
        );

        $arrUst = [];
        while ($row = $ustSql->Fetch()) {
            $arrUst[] = $row;
        }

        $where = '1';
        $periods = [];
        foreach ($arrUst as $ust) {
            $date = date('Y-m-d', strtotime($ust['created_at']));

            if ($ust['state'] == 'start') {
                $ugtpId = $ust['ugtp_id'];
                $range = "(DATE(uc.created_at) >= '{$date}'";
            }

            if (!empty($range) && !empty($ugtpId) && $ugtpId == $ust['ugtp_id'] &&
                ($ust['state'] == 'pause' || $ust['state'] == 'complete')) {
                $range .= " AND DATE(uc.created_at) <= '{$date}'";
                $range .= " AND ugtp.id = {$ugtpId})";

                $periods[] = $range;
                $ugtpId = 0;
                $range = '';
            }
        }

        if ( !empty($periods) ) {
            $where .= ' AND ' . implode(' OR ', $periods);
        } else {
            return $response;
        }

        $conditionsSql = $this->DB->Query(
            "SELECT MAX(uc.temp) max_temp, MIN(uc.temp) min_temp, MAX(uc.humidity) max_humidity, MIN(uc.humidity) min_humidity  
                FROM ulab_gost_to_probe ugtp 
                    INNER JOIN ulab_gost_room ugr on ugr.ugtp_id = ugtp.id 
                    INNER JOIN ulab_conditions uc on uc.room_id = ugr.room_id 
                    WHERE {$where} AND uc.organization_id = {$organizationId}"
        )->Fetch();

        if ( !empty($conditionsSql) ) {
            $response = $conditionsSql;
        }

        return $response;
    }

    /**
     * Получить диапазоны испытаний
     * @param int $protocolId
     * @return array
     */
    public function getTrialsRange(int $protocolId): array
    {
        $response = [];

        if ( empty($protocolId) || $protocolId < 0) {
            return $response;
        }

        $ustSql = $this->DB->Query(
            "SELECT ust.*  
            FROM ulab_gost_to_probe as ugtp 
            inner JOIN ulab_start_trials ust on ust.ugtp_id = ugtp.id 
            WHERE ugtp.protocol_id = {$protocolId} AND ust.is_actual = 1 ORDER BY ust.ugtp_id ASC, ust.created_at ASC"
        );

        $arrUst = [];
        while ($row = $ustSql->Fetch()) {
            $arrUst[] = $row;
        }

        $periods = [];
        foreach ($arrUst as $ust) {
            //$date = date('Y-m-d', strtotime($ust['created_at']));
            $date = date('Y-m-d', strtotime($ust['date']));

            if ($ust['state'] == 'start') {
                $ugtpId = $ust['ugtp_id'];
                $range['date_start'] = $date;
            }

            if (!empty($range) && !empty($ugtpId) && $ugtpId == $ust['ugtp_id'] &&
                ($ust['state'] == 'pause' || $ust['state'] == 'complete')) {
                $range['date_end'] = $date;
                $range['ugtp_id']= $ugtpId;

                $periods[] = $range;
                $ugtpId = 0;
                $range = [];
            }
        }

        return $periods;
    }

    /**
     * Получить выбранные помещения для проведения испытаний
     * @param int $ugtpId
     * @return array
     */
    public function getGostRoom(int $ugtpId): array
    {
        $result = [];

        if ( empty($ugtpId) || $ugtpId < 0 ) {
            return $result;
        }

        $sql = $this->DB->Query(
            "SELECT r.* FROM ulab_gost_room ugr 
                INNER JOIN ROOMS AS r ON r.ID = ugr.room_id 
                WHERE ugtp_id = {$ugtpId}"
        );

        while ($row = $sql->Fetch()) {
            $row['name'] = trim($row['NAME']) . ' ' . trim($row['NUMBER']);
            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param array $ugtpIds
     * @return array
     */
    public function checkNotSelectedRooms($ugtpIds)
    {
        $methodsModel = new Methods();

        $result = [];
        $errors = [];
        $isSuccess = true;
        $strUgtpIds = implode(',', $ugtpIds);

        if ( empty($ugtpIds) || empty($strUgtpIds) ) {
            return $result;
        }

        $sql = $this->DB->Query(
            "SELECT 
                umtr.*,
                m.NAME material_name, 
                ugtp.id ugtp_id, ugtp.method_id  
                    FROM ulab_material_to_request AS umtr 
                    INNER JOIN ulab_gost_to_probe AS ugtp ON ugtp.material_to_request_id = umtr.id 
                    LEFT JOIN MATERIALS AS m ON m.ID = umtr.material_id
                    WHERE ugtp.id IN ({$strUgtpIds})"
        );

        while ($row = $sql->Fetch()) {
            $rooms = $this->getRoomsByMethod($row['method_id']);
            $methodData = $methodsModel->get($row['method_id']);
            $gostRoom = $this->getGostRoom($row['ugtp_id']);

            $row['selected_room'] = $gostRoom;
            $row['rooms'] = $rooms;
            $countRooms = count($rooms);

            // если помещений у методики выбрано, пропускаем
            if ( !empty($gostRoom) ) {
                continue;
            }

            $anchor = "<a href='".URI."/gost/method/{$row['method_id']}'>{$methodData['view_gost_for_protocol']}</a>";

            //если помещение не выбрано и более 1
            $isNotOne = $countRooms > 1 && empty($gostRoom);
            if ( $isNotOne ) {
                $isSuccess = false;
                $errors[] = "У Материала: {$row['material_name']} {$row['cipher']}, Методики: {$anchor} не выбрано помещение для испытания";
                continue;
            }

            //если к методике не привязано помещение
            if ( !$countRooms ) {
                $isSuccess = false;
                $errors[] = "Методика {$anchor} не привязана к помещению";
                continue;
            }

            $result[] = $row;
        }

        return $response = [
            'success' => $isSuccess,
            'errors' => $errors,
            'data' => $result,
        ];
    }

    /**
     * @param int $roomId
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     */
    public function getDatesByPeriodsForRoom(int $roomId, string $dateStart, string $dateEnd): array
    {
        $response = [];
        $where = "";
        $organizationId = App::getOrganizationId();

        if ( empty($roomId) || $roomId < 0 || (empty($dateStart) && empty($dateEnd)) )  {
            return $response;
        }

        if ( empty($dateEnd) ) {
            $where = " AND DATE(created_at) = '{$dateStart}'";
        }

        if ( empty($dateStart) ) {
            $where = " AND DATE(created_at) = '{$dateEnd}'";
        }

        if ( !empty($dateStart) && !empty($dateEnd) ) {
            $where = " AND DATE(created_at) >= '{$dateStart}' AND DATE(created_at) <= '{$dateEnd}'";
        }

        $result = $this->DB->Query(
            "SELECT DATE_FORMAT(DATE(created_at), '%d.%m.%Y') grouped_date FROM ulab_conditions
                WHERE room_id = {$roomId} AND organization_id = {$organizationId} {$where} GROUP BY DATE(created_at)"
        );

        while ($row = $result->Fetch()) {
            $response[] = $row['grouped_date'];
        }

        return $response;
    }

    /**
     * получить данные атмосферного давления по дате
     * @param string $date
     * @return array
     */
    public function getPressureByDate(string $date): array
    {
        $response = [];

        if ( empty($date) ) {
            return $response;
        }

        $result = $this->DB->Query(
            "SELECT 
                    *, DATE_FORMAT(date, '%Y-%m-%d %H:%i') datetime_hi, DATE_FORMAT(date, '%d.%m.%Y %H:%i') datetime_ru 
                        FROM ulab_pressure WHERE DATE_FORMAT(date, '%Y-%m-%d') = '{$date}'
            ");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    /**
     * Добавить данные атмосферного давления
     * @param array $data
     * @return int
     */
    public function addPressure(array $data): int
    {
        $sqlData = $this->prepearTableData('ulab_pressure', $data);
        $result = $this->DB->Insert('ulab_pressure', $sqlData);

        return intval($result);
    }

    /**
     * обновить данные атмосферного давления
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updatePressure(int $id, array $data)
    {
        $sqlData = $this->prepearTableData('ulab_pressure', $data);

        $where = "WHERE id = {$id}";
        return $this->DB->Update('ulab_pressure', $sqlData, $where);
    }

    /**
     * @param int $roomId
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     */
    public function getConditionsByRoom(int $roomId, string $dateStart, string $dateEnd): array
    {
        $response = [];
        $where = "";
        $organizationId = App::getOrganizationId();

        if ( empty($roomId) || $roomId < 0 || (empty($dateStart) && empty($dateEnd)) )  {
            return $response;
        }

        if ( empty($dateEnd) ) {
            $where = " AND DATE(created_at) = '{$dateStart}'";
        }

        if ( empty($dateStart) ) {
            $where = " AND DATE(created_at) = '{$dateEnd}'";
        }

        if ( !empty($dateStart) && !empty($dateEnd) ) {
            $where = " AND DATE(created_at) >= '{$dateStart}' AND DATE(created_at) <= '{$dateEnd}'";
        }

        $result = $this->DB->Query(
            "SELECT COUNT(*) amount, MIN(temp) min_temp, MAX(temp) max_temp, MIN(humidity) min_humidity, MAX(humidity) max_humidity FROM ulab_conditions 
                WHERE room_id = {$roomId} AND organization_id = {$organizationId} {$where} "
        )->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * @param $roomId
     * @return array
     */
    public function getRoomById($roomId)
    {
        if ( empty($roomId) ) {
            return [];
        }

        $room = $this->DB->Query("SELECT * FROM ROOMS WHERE ID = {$roomId}")->Fetch();

        $result = [];

        if (!empty($room)) {
            $room['name'] = trim($room['NAME']) . ' ' . trim($room['NUMBER']);
            $result = $room;
        }

        return $result;
    }

    /**
     * @param array $ugtpIds
     * @return array
     */
    public function checkCountRooms($ugtpIds)
    {
        $methodsModel = new Methods();
        $resultModel = new Result();

        $result = [];
        $errors = [];
        $isSuccess = true;
        $strUgtpIds = implode(',', $ugtpIds);

        if ( empty($ugtpIds) || empty($strUgtpIds) ) {
            return $result;
        }

        $sql = $this->DB->Query(
            "SELECT 
                umtr.*,
                m.NAME material_name, 
                ugtp.id ugtp_id, ugtp.method_id  
                    FROM ulab_material_to_request AS umtr 
                    INNER JOIN ulab_gost_to_probe AS ugtp ON ugtp.material_to_request_id = umtr.id 
                    LEFT JOIN MATERIALS AS m ON m.ID = umtr.material_id
                    WHERE ugtp.id IN ({$strUgtpIds})"
        );

        while ($row = $sql->Fetch()) {
            $rooms = $this->getRoomsByMethod($row['method_id']);
            $gostRoom = $this->getGostRoom($row['ugtp_id']);
            $startTrials = $resultModel->getStartTrials($row['ugtp_id']);
            $methodData = $methodsModel->get($row['method_id']);

            $countRooms = count($rooms);

            if ( !$countRooms ) {
                $isSuccess = false;
                $anchor = "<a href='".URI."/gost/method/{$row['method_id']}'>{$methodData['view_gost_for_protocol']}</a>";
                $errors[] = "Не удалось начать испытание, методика {$anchor} не привязана к помещению";
            }

            // если помещений у методики более 1 и помещения небыли выбраны для испытания, то false показываем список помещений для выбора где будет проходить испытание
            $isNotOne = $countRooms > 1 && empty($gostRoom);
            if (!$isNotOne) {
                continue;
            }

            $isSuccess = false;

            $result[$row['material_id']]['material_id'] = $row['material_id'];
            $result[$row['material_id']]['deal_id'] = $row['deal_id'];

            $result[$row['material_id']]['material_name'] = $row['material_name'];
            $result[$row['material_id']]['probe'][$row['id']]['cipher'] = $row['cipher'] ?: 'Не присвоен шифр';
            $result[$row['material_id']]['probe'][$row['id']]['probe_number'] = $row['probe_number'];
            $result[$row['material_id']]['probe'][$row['id']]['probe_id'] = $row['id']; // id - ulab_material_to_request

            $methodData['ugtp_id'] = $row['ugtp_id'];
            $methodData['is_not_one'] = $isNotOne;
            $methodData['count_room'] = $countRooms;

            $result[$row['material_id']]['probe'][$row['id']]['method'][$row['ugtp_id']] = $methodData;
            $result[$row['material_id']]['probe'][$row['id']]['start_trials'][$row['ugtp_id']] = $startTrials;
            $result[$row['material_id']]['probe'][$row['id']]['rooms'][$row['ugtp_id']] = $rooms;
        }

        return $response = [
            'success' => $isSuccess,
            'errors' => $errors,
            'data' => $result,
        ];
    }

    /**
     * @param int $idMethod
     * @return array
     */
    public function getRoomsByMethod(int $idMethod): array
    {
        $result = [];

        if ( empty($idMethod) || $idMethod < 0 ) {
            return $result;
        }

        $sql = $this->DB->Query(
            "SELECT r.*  
                FROM ulab_methods_room AS umr 
                INNER JOIN ROOMS AS r ON r.ID = umr.room_id
                    where umr.method_id = {$idMethod}"
        );

        while ($row = $sql->Fetch()) {
            $row['name'] = trim($row['NAME']) . ' ' . trim($row['NUMBER']);

            $result[] = $row;
        }

        return $result;
    }

	/**
	 * @param bool $id_bitrix
	 * @param false $is_short
	 * @return array
	 */
	public function getListAlt($id_bitrix = true, $is_short = false): array
	{
		$laboratories = $this->DB->Query("
            SELECT BL.*, BL.id_dep as DEPARTMENT
            FROM ba_laba as BL 
        ");

		while ($row = $laboratories->Fetch()) {
			if ($id_bitrix)
				$results[$row['DEPARTMENT']] = $row['NAME'];
			else
				$results[$row['ID']] = $row['NAME'];

		}
		if ($is_short) {
			foreach ($results as $id => $name) {
				$name = str_replace("Отдел ", "", $name);
				$name = str_replace("-", " ", $name);
				$name = preg_replace("\([^\)]+\)", "", $name);
				$name = preg_replace("`\s\w{1,2}\s`u", " ", $name);
				$words = explode(" ", $name);
				foreach ($words as $key => $word) {
					$words[$key] = mb_strtoupper(mb_substr($word, 0, 1));
				}
				$name = implode("", $words);
				$results[$id] = $name;
			}
		}

		return $results ?? [];
	}


    /**
     * @param $roomId
     * @return array
     */
    public function getConditionsRoomToday($roomId)
    {
        $roomId = (int)$roomId;
        $response = [];

        if ($roomId <= 0) {
            return $response;
        }

        $organizationId = App::getOrganizationId();

        $result = $this->DB->Query("
            SELECT *
            FROM ulab_conditions
            WHERE room_id = {$roomId}
                AND organization_id = {$organizationId}
                AND created_at >= CURRENT_DATE()
                AND created_at <  CURRENT_DATE() + INTERVAL 1 DAY
            ORDER BY id DESC
        ")->Fetch();

        if (!empty($result) && is_array($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * журнал отделов
     * @param array $filter
     * @return array
     */
    public function getJournalList($filter = [])
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'bl.ID',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {
                foreach ($filter['search'] as $key => $value) {
                    $filter['search'][$key] = $this->DB->ForSql(trim($value));
                }

                // Наименование
                if ( isset($filter['search']['NAME']) ) {
                    $where .= "bl.NAME LIKE '%{$filter['search']['NAME']}%' AND ";
                }
                // Начальник
                if ( isset($filter['search']['FULL_NAME']) ) {
                    $id = $filter['search']['FULL_NAME'];

                    if ($id == -1) {
                        $where .= "";
                    }
                    else if ($id == -2) {
                        $where .= "bl.HEAD_ID IS NULL AND ";
                    }
                    else if ($id == -3) {
                        $where .= "bl.HEAD_ID IS NOT NULL AND ";
                    }
                    else {
                        $where .= "bl.HEAD_ID = $id AND bl.HEAD_ID IS NOT NULL AND ";
                    }
                }
            }
            // везде
            if (isset($filter['search']['everywhere'])) {
                $where .=
                    "";
            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'NAME':
                    $order['by'] = 'bl.NAME';
                    break;
            }
        }

        // работа с пагинацией
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

        $result = [];

        $data = $this->DB->Query(
            "SELECT bl.ID, bl.NAME, bl.HEAD_ID, bl.dep_id, 
                    IFNULL(
                        GROUP_CONCAT(CONCAT(SUBSTRING(bu.NAME, 1, 1), '. ', bu.LAST_NAME) ORDER BY bu.NAME ASC SEPARATOR ', '),
                        '-'
                    ) as FULL_NAME
                    FROM ba_laba bl
                    LEFT JOIN b_user bu ON bl.HEAD_ID = bu.ID
                    WHERE {$where} 
                    GROUP BY bl.ID, bl.NAME
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT bl.ID, bl.NAME,
                    IFNULL(
                        GROUP_CONCAT(CONCAT(SUBSTRING(bu.NAME, 1, 1), '. ', bu.LAST_NAME) ORDER BY bu.NAME ASC SEPARATOR ', '),
                        '-'
                    ) as FULL_NAME
                    FROM ba_laba bl
                    LEFT JOIN b_user bu ON bl.HEAD_ID = bu.ID
                    GROUP BY bl.ID, bl.NAME"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT bl.ID, bl.NAME,
                    IFNULL(
                        GROUP_CONCAT(CONCAT(SUBSTRING(bu.NAME, 1, 1), '. ', bu.LAST_NAME) ORDER BY bu.NAME ASC SEPARATOR ', '),
                        '-'
                    ) as FULL_NAME
                    FROM ba_laba bl
                    LEFT JOIN b_user bu ON bl.HEAD_ID = bu.ID
                    WHERE {$where} 
                    GROUP BY bl.ID, bl.NAME
                    ORDER BY {$order['by']} {$order['dir']}"
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * @param $labId
     * @return array|false
     */
    public function getLab($labId)
    {
        if (empty($labId) || $labId < 0) {
            return [];
        }

        return $this->DB->Query("
            select `BL`.*, `BL`.id_dep as id_dep, `BL`.HEAD_ID, uup.permission_id as ROLE_USER_ID
            from `ba_laba` as `BL`
            left join ulab_user_permission as uup on uup.user_id = BL.HEAD_ID
            where `BL`.ID = {$labId}
        ")->Fetch();
    }

    public function createDeptBitrix($name)
    {
        $fieldsToCreate = array(
            "NAME" => $name,
            "IBLOCK_ID" => 5,
            "IBLOCK_SECTION_ID" => 53
        );

        $department = new CIBlockSection;
        $createResult = $department->Add($fieldsToCreate);

        if ($createResult) {
            return $createResult; // Возвращает ID нового подразделения
        } else {
            return $department->LAST_ERROR;
            //echo "Ошибка при создании нового подразделения: " . $department->LAST_ERROR;
        }
    }

    public function addDept($data)
    {
        $permissionModel = new Permission();

        $sqlData = $this->prepareDeptData($data);
        unset($sqlData["ID"]);

        if ($data['HEAD_ID'] == -1)
            $data['HEAD_ID'] = NULL;

        if ($data['HEAD_ID'] && $data['HEAD_ROLE_ID']) {
            $permissionModel->updateUser($data['HEAD_ID'], $data['HEAD_ROLE_ID']);
        }

        return $this->DB->Insert('ba_laba', $sqlData);
    }

    /**
     * @param $data
     * @return array
     */
    private function prepareDeptData($data, $table = 'ba_laba')
    {
        $columns = $this->getColumnsByTable($table);

        $sqlData = [];

        foreach ($columns as $column) {
            if ( isset($data[$column]) ) {
                $sqlData[$column] = $this->quoteStr($this->DB->ForSql(trim($data[$column])));
            }
        }
        return $sqlData;
    }

    public function connectionBitrixAndUlab($ulabId, $bitrixId)
    {
        $this->DB->Query("UPDATE ba_laba SET id_dep = '{$bitrixId}' WHERE ID = '{$ulabId}'");
    }

    public function getRoom($roomId)
    {
        if (empty($roomId) || $roomId < 0) {
            return [];
        }

        $oborudModel = new Oborud();

        $room = $this->DB->Query("
            SELECT R.*, rtl.id_lab as LAB_ID
            FROM `ROOMS` as R
            LEFT JOIN `rooms_to_labs` AS rtl ON rtl.`id_room` = R.`id`
            WHERE R.`id` = {$roomId}
        ")->Fetch();

        $equipment_storaged = $oborudModel->getOborudByStorageRoom($roomId);
        $equipment_operating = $oborudModel->getOborudByOperatingRoom($roomId);

        $room['equipment_storaged'] = $equipment_storaged ?? [];
        $room['equipment_operating'] = $equipment_operating ?? [];

        return $room ?? [];
    }

    public function getOborudByOperatingRoom(string $roomId = ''): array
    {
        $result = [];

        $sql = $this->DB->Query("
            SELECT eg.id, eg.name, eg.id_operating_room
            FROM `equipment_general` AS eg
            WHERE eg.`id_operating_room` = '{$roomId}'
                OR eg.`id_operating_room` IS NULL
        ");

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function updateRoom($id, $data)
    {
        $sqlData = $this->prepareRoomData($data);

        $where = "WHERE ID = {$id}";
        return $this->DB->Update('ROOMS', $sqlData, $where);
    }

    private function prepareRoomData($data)
    {
        $columns = $this->getColumnsByTable('ROOMS');

        $sqlData = [];

        foreach ($columns as $column) {
            if ( isset($data[$column]) ) {
                $sqlData[$column] = $this->quoteStr($this->DB->ForSql(trim($data[$column])));
            }
        }

        $sqlData['SPEC'] = !empty($data['SPEC']) ? 1 : 0;

        return $sqlData;
    }

    public function updateDept($id, $data)
    {
        $permissionModel = new Permission();
        $sqlData = $this->prepareDeptData($data);
        $where = "WHERE ID = {$id}";

        $bitrixId = $this->DB->Query("
            SELECT `BL`.*, `BL`.id_dep as id_dep
            FROM `ba_laba` as `BL`
            WHERE `BL`.ID = $id")
            ->Fetch()['id_dep'];

        if (isset($bitrixId)) {
            $fieldsToUpdate = array(
                "NAME" => $data['NAME'],
            );

            $department = new CIBlockSection;
            $department->Update($bitrixId, $fieldsToUpdate);
        }

        if ($data['HEAD_ID'] == -1)
            $data['HEAD_ID'] = NULL;

        if ($data['HEAD_ID'] && $data['HEAD_ROLE_ID']) {
            $permissionModel->updateUser($data['HEAD_ID'], $data['HEAD_ROLE_ID']);
        }

        return $this->DB->Update('ba_laba', $sqlData, $where);
    }

    /**
     * @param $data
     * @return false|mixed|string
     */
    public function addRoom($data)
    {
        $sqlData = $this->prepareRoomData($data);

        return $this->DB->Insert('ROOMS', $sqlData);
    }

    public function assignRoomToLab($roomId, $deptId) {
        $sqlData = $this->prepearTableData('rooms_to_labs', ['id_lab' => $deptId, 'id_room' => $roomId]);

        return $this->DB->Insert('rooms_to_labs', $sqlData);
    }

    /**
     * @param int $id
     */
    public function deleteRoom($id)
    {
        $this->DB->Query("DELETE FROM rooms_to_labs WHERE id_room = {$id}");
        $this->DB->Query("DELETE FROM ROOMS WHERE id = {$id}");
    }

    /**
     * @param $labId
     * @return mixed
     */
    public function deleteLab($labId)
    {
        $bitrixId = $this->DB->Query("
            SELECT `BL`.*, `BL`.id_dep as id_dep
            FROM `ba_laba` as `BL`
            WHERE `BL`.ID = $labId")
            ->Fetch()['id_dep'];

        $result2 = $this->DB->Query("delete from ba_laba where id = {$labId}");

        $deleteDone = false;
        if (isset($bitrixId) && isset($result2)) {
            $department = new CIBlockSection;
            $result = $department->Delete($bitrixId);

            if ($result) {
                $deleteDone = true;
            } else {
                $deleteDone = false;
            }
        }

        return $deleteDone;
    }


    /**
     * @param array $filter
     * @return array
     */
    public function getRoomsListForLab($filter = [])
    {
        $organizationId = App::getOrganizationId();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'r.ID',
            'dir' => 'DESC'
        ];


        // из $filter собирать строку $where тут
        // формат такой: $where .= "что-то = чему-то AND ";
        // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
        // слева без пробела, справа всегда AND пробел

        // работа с фильтрами
        if (!empty($filter['search'])) {
            // Лаборатория
            if ( isset($filter['search']['laboratory']) ) {
                $where .= "r.LAB_ID = '{$filter['search']['laboratory']}' AND ";
            }
            // Номер
            if ( isset($filter['search']['NUMBER']) ) {
                $where .= "r.NUMBER = '{$filter['search']['NUMBER']}' AND ";
            }
            // Наименование
            if ( isset($filter['search']['NAME']) ) {
                $where .= "r.NAME LIKE '%{$filter['search']['NAME']}%' AND ";
            }
            // Тип
            if ( isset($filter['search']['SPEC']) ) {
                $searchValue = mb_strtolower($filter['search']['SPEC']);

                if (mb_strpos('специальное', $searchValue) !== false) {
                    $where .= "r.SPEC = 0 AND ";
                } else if (mb_strpos('приспособленное', $searchValue) !== false) {
                    $where .= "r.SPEC = 1 AND ";
                } else {
                    $where .= "0 AND ";
                }
            }
            // Назначение
            if ( isset($filter['search']['PURPOSE']) ) {
                $where .= "r.PURPOSE LIKE '%{$filter['search']['PURPOSE']}%' AND ";
            }
            // Площадь
            if ( isset($filter['search']['AREA']) ) {
                $where .= "r.AREA LIKE '%{$filter['search']['AREA']}%' AND ";
            }
            // Контролируемые параметры
            if ( isset($filter['search']['PARAMS']) ) {
                $where .= "r.PARAMS LIKE '%{$filter['search']['PARAMS']}%' AND ";
            }
            // Специальное оборудование
            if ( isset($filter['search']['SPEC_EQUIP']) ) {
                $where .= "r.SPEC_EQUIP LIKE '%{$filter['search']['SPEC_EQUIP']}%' AND ";
            }
            // Право собственности
            if ( isset($filter['search']['DOCS']) ) {
                $where .= "r.DOCS LIKE '%{$filter['search']['DOCS']}%' AND ";
            }
            // Местонахождение
            if ( isset($filter['search']['PLACEMENT']) ) {
                $where .= "r.PLACEMENT LIKE '%{$filter['search']['PLACEMENT']}%' AND ";
            }
            // Примечание
            if ( isset($filter['search']['COMMENT']) ) {
                $where .= "r.COMMENT LIKE '%{$filter['search']['COMMENT']}%' AND ";
            }
        }

        $where .= "lab.organization_id = {$organizationId} ";

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'NUMBER':
                    $order['by'] = 'r.NUMBER';
                    break;
                case 'NAME':
                    $order['by'] = 'r.NAME';
                    break;
                case 'PURPOSE':
                    $order['by'] = 'r.PURPOSE';
                    break;
                case 'AREA':
                    $order['by'] = 'r.AREA';
                    break;
                case 'PARAMS':
                    $order['by'] = 'r.PARAMS';
                    break;
                case 'SPEC_EQUIP':
                    $order['by'] = 'r.SPEC_EQUIP';
                    break;
                case 'DOCS':
                    $order['by'] = 'r.DOCS';
                    break;
                case 'PLACEMENT':
                    $order['by'] = 'r.PLACEMENT';
                    break;
            }
        }

        // работа с пагинацией
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

        $result = [];

        $data = $this->DB->Query(
            "SELECT r.* 
             FROM ROOMS AS r
             join ba_laba as lab on lab.ID = r.LAB_ID
             WHERE {$where}
             ORDER BY {$order['by']} {$order['dir']} {$limit}
        ");

        $dataTotal = $this->DB->Query(
            "SELECT r.ID 
            FROM ROOMS AS r
            join ba_laba as lab on lab.ID = r.LAB_ID
            where lab.organization_id = {$organizationId}
        ")->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT r.ID 
             FROM ROOMS AS r
             join ba_laba as lab on lab.ID = r.LAB_ID
             WHERE {$where}
        ")->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }
}