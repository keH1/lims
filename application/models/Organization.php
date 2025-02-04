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
     * @param int $depId - ид департамента
     * @param int $divId - ид отдела
     * @param int $labId - ид лаборатории
     * @return bool
     */
    public function getIsAffiliationUser($userId, $orgId = 0, $depId = 0, $divId = 0, $labId = 0)
    {
        // если пользователь админ - то можно входить всегда
        if ( in_array($userId, USER_ADMIN) ) {
            return true;
        }

        return true;
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
     * @param $orgId
     * @param $data
     */
    public function setOrgInfo($orgId, $data)
    {
        $sqlData = $this->prepearTableData('ulab_organization', $data);

        $this->DB->Update("ulab_organization", $sqlData, "where id = {$orgId}");
    }


    /**
     * @desc Добавляет данные об организации
     * @param $data
     */
    public function addOrgInfo($data)
    {
        $sqlData = $this->prepearTableData('ulab_organization', $data);

        $this->DB->Insert('ulab_organization', $sqlData);
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
     * @param int $depId - ид департамента
     * @return array|mixed
     */
    public function getDepInfo($depId)
    {
        return $this->DB->Query("select * from ulab_department where id = {$depId}")->Fetch();
    }


    /**
     * @desc Обновляет данные о департаменте
     * @param $depId
     * @param $data
     */
    public function setDepInfo($depId, $data)
    {
        $sqlData = $this->prepearTableData('ulab_department', $data);

        $this->DB->Update("ulab_department", $sqlData, "where id = {$depId}");
    }


    /**
     * @desc Добавляет данные о департаменте
     * @param $data
     */
    public function addDepInfo($data)
    {
        $sqlData = $this->prepearTableData('ulab_department', $data);

        $this->DB->Insert('ulab_department', $sqlData);
    }


    /**
     * @desc Получает данные о департаменте для журнала
     * @param $filter
     * @return array
     */
    public function getDepJournal($filter)
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
            WHERE {$where}
            ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT id
            FROM ulab_department
            WHERE 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT id
            FROM ulab_department
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
}