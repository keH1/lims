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
        if ( empty($userId) ) {
            return false;
        }

        if ( !empty($data['lab_id']) ) {
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

        $current = $this->DB->Query("select * from ulab_user_affiliation where user_id = {$userId}")->Fetch();

        if ( !empty($current['user_id']) ) {
            return $this->DB->Update("ulab_user_affiliation", $sqlData, "where user_id = {$userId}");
        } else {
            $sqlData['user_id'] = $userId;

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

        return $this->DB->Query("delete from ulab_user_affiliation where user_id = {$userId}")->Fetch();
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
        $sqlData = $this->prepearTableData('ba_laba', $data);

        $this->DB->Update("ba_laba", $sqlData, "where ID = {$labId}");
    }


    /**
     * @desc Обновляет данные о лабе
     * @param $data
     */
    public function addLabInfo($data)
    {
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
            "select ID, `NAME`, `LAST_NAME`, `SECOND_NAME`, `WORK_POSITION` 
                from b_user where ID not in (select user_id from ulab_user_affiliation) and ACTIVE = 'Y' and BLOCKED = 'N'"
        );

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
            FROM b_user as u
            join ulab_user_affiliation as ua on u.ID = ua.user_id
            WHERE ua.lab_id = {$labId} and {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT u.ID
            FROM b_user as u
            join ulab_user_affiliation as ua on u.ID = ua.user_id
            WHERE ua.lab_id = {$labId} and 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT u.ID
            FROM b_user as u
            join ulab_user_affiliation as ua on u.ID = ua.user_id
            WHERE ua.lab_id = {$labId} and {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }
}