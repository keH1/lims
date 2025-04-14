<?php

/**
 * @desc Модель Профиль организации
 * Class Organization
 */
class Organization extends Model
{
    /**
     * @desc функция возвращающая принадлежность пользователя организации, департаменту, отделу или лаборатории
     * @param int $userId - ид пользователя
     * @param int $orgId - ид организации
     * @param int $branchId - ид департамента
     * @param int $depId - ид отдела
     * @param int $labId - ид лаборатории
     * @return bool
     */
    public function getIsAffiliationUser(int $userId, $orgId = 0, $branchId = 0, $depId = 0, $labId = 0)
    {
        // если пользователь админ - то можно входить всегда
        if ( in_array($userId, USER_ADMIN) ) {
            return true;
        }

        return true;
    }


    /**
     * @desc получает информацию о принадлежности пользователя организации, департаменту, отделу или лаборатории
     * @param int $userId
     * @return array
     */
    public function getAffiliationUserInfo(int $userId)
    {
        if ( empty($userId) ) {
            return [];
        }

        return $this->DB->Query("select * from ulab_user_affiliation where user_id = {$userId}")->Fetch();
    }


    /**
     * @desc добавляет/обновляет информацию о принадлежности пользователя организации, департаменту, отделу или лаборатории
     * @param int $userId
     * @param array $data
     * @return false|mixed
     */
    public function setAffiliationUserInfo(int $userId, array &$data)
    {
        if (empty($userId)) {
            return false;
        }

        if (!empty($data['lab_id'])) {
            $data['dep_id'] = $this->getDepIdByLab($data['lab_id']);
            $data['branch_id'] = $this->getBranchIdByDep($data['dep_id']);
            $data['org_id'] = $this->getOrgIdByBranch($data['branch_id']);
        } else if ( !empty($data['dep_id']) ) {
            $data['branch_id'] = $this->getBranchIdByDep($data['dep_id']);
            $data['org_id'] = $this->getOrgIdByBranch($data['branch_id']);
        } else if ( !empty($data['branch_id']) ) {
            $data['org_id'] = $this->getOrgIdByBranch($data['branch_id']);
        }

        $sqlData = $this->prepearTableData('ulab_user_affiliation', $data);

        $dataStatus = [
            'user_id' => $userId,
            'user_status' => $data['status'],
            'replacement_user_id' => (
                !isset($data['replacement_user_id']) || 
                $data['replacement_user_id'] === '' || 
                $data['replacement_user_id'] === 0
            ) 
                ? "NULL" 
                : (int)$data['replacement_user_id'],
            'replacement_date' => date('Y-m-d H:i:s')
        ];
        
        $sqlDataStatus = $this->prepearTableData('ulab_user_status', $dataStatus);

        $current = $this->DB->Query("select * from ulab_user_affiliation where user_id = {$userId}")->Fetch();

        if (!empty($current['user_id'])) {
            unset($sqlDataStatus['user_id']);
            $this->DB->Update("ulab_user_status", $sqlDataStatus, "where user_id = {$userId}");
            return $this->DB->Update("ulab_user_affiliation", $sqlData, "where user_id = {$userId}");
        } else {
            $sqlData['user_id'] = $userId;
            $this->DB->Insert("ulab_user_status", $sqlDataStatus);
            return $this->DB->Insert("ulab_user_affiliation", $sqlData);
        }
    }


    /**
     * @desc удаляет связь пользователя организации, департаменту, отделу или лаборатории
     * @param int $userId
     * @return false|mixed
     */
    public function deleteAffiliationUser(int $userId)
    {
        if ( empty($userId) ) {
            return false;
        }
        
        $this->DB->Query("DELETE FROM ulab_user_status WHERE user_id = {$userId}")->Fetch();
        $this->DB->Query("DELETE FROM ulab_user_affiliation WHERE user_id = {$userId}")->Fetch();

        return (int)$userId;
    }


    /**
     * @desc получает ид отдела из ид лаборатории
     * @param int $labId
     * @return false|int
     */
    public function getDepIdByLab(int $labId)
    {
        if ( empty($labId) ) {
            return 0;
        }

        $sql = $this->DB->Query("select dep_id from ba_laba where ID = {$labId}")->Fetch();

        if ( empty($sql['dep_id']) ) {
            return 0;
        }

        return (int) $sql['dep_id'];
    }


    /**
     * @desc получает ид департамента из ид отдела
     * @param int $depId
     * @return false|int
     */
    public function getBranchIdByDep(int $depId)
    {
        if ( empty($depId) ) {
            return 0;
        }

        $sql = $this->DB->Query("select branch_id from ulab_department where id = {$depId}")->Fetch();

        if ( empty($sql['branch_id']) ) {
            return 0;
        }

        return (int) $sql['branch_id'];
    }


    /**
     * @desc получает ид организации из ид департамента
     * @param int $branchId
     * @return false|int
     */
    public function getOrgIdByBranch(int $branchId)
    {
        if ( empty($branchId) ) {
            return 0;
        }

        $sql = $this->DB->Query("select organization_id from ulab_branch where id = {$branchId}")->Fetch();

        if ( empty($sql['organization_id']) ) {
            return 0;
        }

        return (int) $sql['organization_id'];
    }


    /**
     * @desc Получает данные об организации
     * @param int $orgId - ид организации
     * @return array|mixed
     */
    public function getOrgInfo($orgId)
    {
        return $this->DB->Query("select * from ulab_organization where id = {$orgId}")->Fetch();
    }


    /**
     * @desc Обновляет данные об организации
     * @param int $orgId
     * @param $data
     * @return mixed|false
     */
    public function setOrgInfo(int $orgId, $data)
    {
        if ( empty($orgId) ) {
            return false;
        }

        if ( isset($data['ip']) ) {
            $data['ip'] = 1;
        } else {
            $data['ip'] = 0;
        }

        $sqlData = $this->prepearTableData('ulab_organization', $data);

        return $this->DB->Update("ulab_organization", $sqlData, "where id = {$orgId}");
    }


    /**
     * @desc Добавляет данные об организации
     * @param $data
     * @return mixed|int
     */
    public function addOrgInfo($data)
    {
        $sqlData = $this->prepearTableData('ulab_organization', $data);

        return $this->DB->Insert('ulab_organization', $sqlData);
    }


    /**
     * @desc Получает данные об организации для журнала
     * @param $filter
     * @return array
     */
    public function getOrgJournal($filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {

            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'name':
                    $order['by'] = "`name`";
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

        $data = $this->DB->Query(
            "SELECT *
            FROM ulab_organization
            WHERE {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT id
            FROM ulab_organization
            WHERE 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT id
            FROM ulab_organization
            WHERE {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @desc Получает данные о департаменте
     * @param int $branchId - ид департамента
     * @return array|mixed
     */
    public function getBranchInfo($branchId)
    {
        return $this->DB->Query("select * from ulab_branch where id = {$branchId}")->Fetch();
    }


    /**
     * @desc Обновляет данные о департаменте
     * @param $branchId
     * @param $data
     */
    public function setBranchInfo($branchId, $data)
    {
        $sqlData = $this->prepearTableData('ulab_department', $data);

        $this->DB->Update("ulab_branch", $sqlData, "where id = {$branchId}");
    }


    /**
     * @desc Добавляет данные о департаменте
     * @param $data
     */
    public function addBranchInfo($data)
    {
        $sqlData = $this->prepearTableData('ulab_branch', $data);

        $this->DB->Insert('ulab_branch', $sqlData);
    }


    /**
     * @desc Получает данные о департаменте для журнала
     * @param int $orgId
     * @param array $filter
     * @return array
     */
    public function getBranchJournal(int $orgId, array $filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {

            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'name':
                    $order['by'] = "`name`";
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

        $data = $this->DB->Query(
            "SELECT *
            FROM ulab_branch
            WHERE organization_id = {$orgId} and {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT id
            FROM ulab_branch
            WHERE organization_id = {$orgId} and 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT id
            FROM ulab_branch
            WHERE organization_id = {$orgId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @desc Получает данные об отделе
     * @param int $depId - ид отдела
     * @return array|mixed
     */
    public function getDepInfo($depId)
    {
        return $this->DB->Query("select * from ulab_department where id = {$depId}")->Fetch();
    }


    /**
     * @desc Добавляет данные об отделе
     * @param $data
     */
    public function addDepInfo($data)
    {
        $sqlData = $this->prepearTableData('ulab_department', $data);

        $this->DB->Insert('ulab_department', $sqlData);
    }


    /**
     * @desc Обновляет данные об отделе
     * @param $depId
     * @param $data
     */
    public function setDepInfo($depId, $data)
    {
        $sqlData = $this->prepearTableData('ulab_department', $data);

        $this->DB->Update("ulab_department", $sqlData, "where id = {$depId}");
    }


    /**
     * @desc Получает данные о лаборатории
     * @param int $labId - ид лаборатории
     * @return array|mixed
     */
    public function getLabInfo(int $labId)
    {
        return $this->DB->Query("select * from ba_laba where ID = {$labId}")->Fetch();
    }


    /**
     * @desc Обновляет данные о лабе
     * @param $labId
     * @param $data
     */
    public function setLabInfo($labId, $data)
    {
        if ( !empty($data['bitrix_dep_id']) ) {
            $this->editBitrixDepartment($data['bitrix_dep_id'], $data['NAME']);
        }

        $sqlData = $this->prepearTableData('ba_laba', $data);

        $this->DB->Update("ba_laba", $sqlData, "where ID = {$labId}");
    }


    /**
     * @desc Обновляет данные о лабе
     * @param $data
     */
    public function addLabInfo($data)
    {
        $idDep = $this->createBitrixDepartment($data['NAME']);

        if ( $idDep > 0 ) {
            $data['id_dep'] = $idDep;
        }

        $sqlData = $this->prepearTableData('ba_laba', $data);

        $this->DB->Insert("ba_laba", $sqlData);
    }


    /**
     * @desc Получает пользователей, не привязанных к лабораториям
     * @return array
     */
    public function getNotAffiliationUser()
    {
        $sql = $this->DB->Query(
            "SELECT ID, `NAME`, `LAST_NAME`, `SECOND_NAME`, `WORK_POSITION` 
             FROM b_user
             WHERE ID NOT IN
              (SELECT user_id FROM ulab_user_affiliation) AND ACTIVE = 'Y' AND BLOCKED = 'N'
             ORDER BY `NAME`, `LAST_NAME`
        ");

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @desc Получает данные об отделе для журнала
     * @param int $branchId
     * @param $filter
     * @return array
     */
    public function getDepJournal(int $branchId, $filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {

            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'name':
                    $order['by'] = "`name`";
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

        $data = $this->DB->Query(
            "SELECT *
            FROM ulab_department
            WHERE branch_id = {$branchId} and {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT id
            FROM ulab_department
            WHERE branch_id = {$branchId} and 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT id
            FROM ulab_department
            WHERE branch_id = {$branchId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @desc Получает данные о лаборатории для журнала
     * @param int $depId
     * @param $filter
     * @return array
     */
    public function getLabJournal(int $depId, $filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {

            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'NAME':
                    $order['by'] = "`NAME`";
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

        $data = $this->DB->Query(
            "SELECT *
            FROM ba_laba
            WHERE dep_id = {$depId} and {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT ID
            FROM ba_laba
            WHERE dep_id = {$depId} and 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT ID
            FROM ba_laba
            WHERE dep_id = {$depId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @desc Получает данные о помещениях для журнала
     * @param int $labId
     * @param $filter
     * @return array
     */
    public function getLabRoomsJournal(int $labId, $filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'ID',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {

            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'NAME':
                    $order['by'] = "`NAME`";
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

        $data = $this->DB->Query(
            "SELECT *
            FROM ROOMS
            WHERE LAB_ID = {$labId} and {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT ID
            FROM ROOMS
            WHERE LAB_ID = {$labId} and 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT ID
            FROM ROOMS
            WHERE LAB_ID = {$labId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @desc Получает данные о помещениях для журнала
     * @param int $labId
     * @param $filter
     * @return array
     */
    public function getLabUsersJournal(int $labId, $filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'u.ID',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {

            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'NAME':
                    $order['by'] = "u.`LAST_NAME`";
                    break;
                case 'WORK_POSITION':
                    $order['by'] = "u.`WORK_POSITION`";
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
        

        $data = $this->DB->Query(
           "SELECT u.*, ua.*, uus.replacement_user_id, 
            CONCAT(ru.LAST_NAME, ' ', LEFT(ru.NAME, 1), '.') AS replace_user,
            uusl.status, uusl.id AS status_id
            FROM b_user AS u

            JOIN ulab_user_affiliation AS ua
            ON u.ID = ua.user_id

            LEFT JOIN ulab_user_status AS uus
            ON u.ID = uus.user_id

            LEFT JOIN ulab_user_status_list AS uusl
            ON uus.user_status = uusl.id
            
            LEFT JOIN b_user AS ru
            ON uus.replacement_user_id = ru.ID

            WHERE ua.lab_id = {$labId} AND {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}
        ");

        $dataTotal = $this->DB->Query(
           "SELECT u.ID
            FROM b_user AS u
            JOIN ulab_user_affiliation AS ua ON u.ID = ua.user_id
            WHERE ua.lab_id = {$labId} AND 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
           "SELECT u.ID
            FROM b_user AS u
            JOIN ulab_user_affiliation AS ua ON u.ID = ua.user_id
            WHERE ua.lab_id = {$labId} AND {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * получает всех руководителей от организации и дальше по цепочке
     * @param $orgId
     * @return array
     */
    public function getAllLeaders($orgId)
    {
        $userModel = new User();

        $result = [];

        $sql = $this->DB->Query(
            "select org.head_user_id as org_uid, brnch.head_user_id as brn_uid, dep.head_user_id as dep_uid, lab.HEAD_ID as lab_uid
            from ulab_organization as org
            join ulab_branch as brnch on org.id = brnch.organization_id
            join ulab_department as dep on brnch.id = dep.branch_id
            join ba_laba as lab on lab.dep_id = dep.id
            where org.id = {$orgId}"
        );

        $columns = ['org_uid', 'brn_uid', 'dep_uid', 'lab_uid'];

        while ($row = $sql->Fetch()) {
            foreach ($columns as $column) {
                if ( $row[$column] > 0 && !isset($result[$row[$column]])) {
                    $result[$row[$column]] = $userModel->getUserData($row[$column]);
                }
            }
        }

        return $result;
    }


    /**
     * создает структуру компании в битриксе (департамент)
     * @param string $name
     * @param int $parentId - ид родительского подразделения (не обязательно)
     * @return mixed
     */
    public function createBitrixDepartment(string $name, int $parentId = 0)
    {
        $data = [
            'NAME' => $name,
            'SEARCHABLE_CONTENT' => $name,
            'IBLOCK_SECTION_ID' => $parentId > 0?  $parentId : null,
            'CREATED_BY' => $_SESSION['SESS_AUTH']['USER_ID'],
            'MODIFIED_BY' => $_SESSION['SESS_AUTH']['USER_ID'],
            'DATE_CREATE' => date('Y-m-d H:i:s'),
            'IBLOCK_ID' => 5,
            'DEPTH_LEVEL' => 2,
        ];

        $sqlData = $this->prepearTableData('b_iblock_section', $data);

        return $this->DB->Insert('b_iblock_section', $sqlData);
    }


    /**
     * редактирует название структуры компании в битриксе (департамент)
     * @param int $id
     * @param string $name
     * @return mixed
     */
    public function editBitrixDepartment(int $id, string $name)
    {
        $data = [
            'NAME' => $name,
            'SEARCHABLE_CONTENT' => $name,
            'MODIFIED_BY' => $_SESSION['SESS_AUTH']['USER_ID'],
        ];

        $sqlData = $this->prepearTableData('b_iblock_section', $data);

        return $this->DB->Update('b_iblock_section', $sqlData, "where ID = {$id}");
    }
}