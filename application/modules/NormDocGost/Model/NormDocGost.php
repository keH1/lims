<?php

/**
 * Модель для работы с ГОСТами
 * Class NormDocGost
 */
class NormDocGost extends Model
{
    /**
     * журнал ОА
     * @param array $filter
     * @return array
     */
    public function getJournalList($filter = [])
    {
        $organizationId = App::getOrganizationId();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'm.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {
                // Номер документа
                if ( isset($filter['search']['reg_doc']) ) {
                    $where .= "g.reg_doc LIKE '%{$filter['search']['reg_doc']}%' AND ";
                }
                // Наименование документа
                if ( isset($filter['search']['description']) ) {
                    $where .= "g.description LIKE '%{$filter['search']['description']}%' AND ";
                }
                // Год
                if ( isset($filter['search']['year']) ) {
                    $where .= "g.year LIKE '%{$filter['search']['year']}%' AND ";
                }
                // Наименование объекта
                if ( isset($filter['search']['materials']) ) {
                    $where .= "g.materials LIKE '%{$filter['search']['materials']}%' AND ";
                }
                // Определяемая характеристика / показатель
                if ( isset($filter['search']['name']) ) {
                    $where .= "m.name LIKE '%{$filter['search']['name']}%' AND ";
                }
                // Пункт документа
                if ( isset($filter['search']['clause']) ) {
                    $where .= "m.clause LIKE '%{$filter['search']['clause']}%' AND ";
                }
                // Метод
                if ( isset($filter['search']['test_method']) ) {
                    $where .= "tm.name LIKE '%{$filter['search']['test_method']}%' AND ";
                }
                // Единица измерения
                if ( isset($filter['search']['unit_rus']) ) {
                    $where .= "d.unit_rus LIKE '%{$filter['search']['unit_rus']}%' AND ";
                }
                // Единица измерения
                if ( isset($filter['search']['in_field']) ) {
                    $where .= "m.in_field = '%{$filter['search']['in_field']}%' AND ";
                }
                // Единица измерения
                if ( isset($filter['search']['is_extended_field']) ) {
                    $where .= "m.is_extended_field = '%{$filter['search']['is_extended_field']}%' AND ";
                }
                // Цена
                if ( isset($filter['search']['price']) ) {
                    $where .= "m.price = '{$filter['search']['price']}' AND ";
                }
            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'reg_doc':
                    $order['by'] = 'g.reg_doc';
                    break;
                case 'description':
                    $order['by'] = 'g.description';
                    break;
                case 'year':
                    $order['by'] = 'g.year';
                    break;
                case 'materials':
                    $order['by'] = 'g.materials';
                    break;
                case 'name':
                    $order['by'] = 'm.name';
                    break;
                case 'clause':
                    $order['by'] = 'm.clause';
                    break;
                default:
                    $order['by'] = 'm.num_oa';
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

        $where .= "g.organization_id = {$organizationId}";

        $result = [];

        $data = $this->DB->Query(
            "SELECT distinct 
                        m.*, m.id method_id,
                        g.*, g.id gost_id,
                        d.unit_rus, d.name as unit_name, d.fsa_id as unit_fsa_id
                    FROM ulab_norm_doc_gost g
                    LEFT JOIN ulab_norm_doc_methods as m ON g.id = m.gost_id 
                    left join `ulab_dimension` as d on d.id = m.unit_id
                    WHERE {$where}
                    ORDER BY  {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT distinct *
                    FROM ulab_norm_doc_gost g
                    LEFT JOIN ulab_norm_doc_methods m ON g.id = m.gost_id 
                    WHERE g.organization_id = {$organizationId}"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT distinct *
                    FROM ulab_norm_doc_gost g
                    LEFT JOIN ulab_norm_doc_methods m ON g.id = m.gost_id
                    left join `ulab_dimension` as d on d.id = m.unit_id
                    WHERE {$where}"
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    public function methodsJournal($filter)
    {
        $organizationId = App::getOrganizationId();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'm.id',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                // ид
                if ( isset($filter['search']['id']) ) {
                    $where .= "m.`gost_id` =  '{$filter['search']['id']}' AND ";
                }
                // Определяемая характеристика
                if ( isset($filter['search']['name']) ) {
                    $where .= "m.name LIKE '%{$filter['search']['name']}%' AND ";
                }
                // Пункт
                if ( isset($filter['search']['clause']) ) {
                    $where .= "m.clause LIKE '%{$filter['search']['clause']}%' AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'is_confirm':
                        $order['by'] = 'm.is_confirm';
                        break;
                    case 'in_field':
                        $order['by'] = 'm.in_field';
                        break;
                    case 'is_extended_field':
                        $order['by'] = 'm.is_extended_field';
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

        $where .= "g.organization_id = {$organizationId}";

        $data = $this->DB->Query(
            "select m.*, d.unit_rus, d.name as unit_name, d.fsa_id as unit_fsa_id
                    from `ulab_norm_doc_methods` as m 
                    inner join `ulab_norm_doc_gost` as g on g.id = m.gost_id 
                    left join `ulab_dimension` as d on d.id = m.unit_id 
                    where {$where} 
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "select m.id
                    from `ulab_norm_doc_methods` as m 
                    inner join `ulab_norm_doc_gost` as g on g.id = m.gost_id 
                    where m.`gost_id` =  {$filter['search']['id']} AND g.organization_id = {$organizationId}"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "select m.id
                    from `ulab_norm_doc_methods` as m 
                    inner join `ulab_norm_doc_gost` as g on g.id = m.gost_id 
                    left join `ulab_dimension` as d on d.id = m.unit_id
                    where {$where} "
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {

            $strYear = !empty($row['year']) ? "-{$row['year']}" : '';
            $strClause = !empty($row['clause']) ? " {$row['clause']}" : '';

            $row['view_gost'] = "{$row['reg_doc']}{$strYear}{$strClause} | {$row['name']}";
            $row['view_gost_for_protocol'] = "{$row['reg_doc']}{$strYear}{$strClause}";
            $row['name_gost'] = $row['view_gost_for_protocol'];
            $row['specification'] = $row['name'];

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @param $data
     * @return false|mixed|string
     */
    public function addGost($data)
    {
        $data['organization_id'] = App::getOrganizationId();
        $sqlData = $this->prepearTableData('ulab_norm_doc_gost', $data);

        return $this->DB->Insert('ulab_norm_doc_gost', $sqlData);
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
            $methodList = $this->getListMethodByGostId($id);

            foreach ($methodList as $method) {
                $dataSource = $this->getMethod($id);

                $dataSource['gost_id'] = $newId;

                $dataSource['is_confirm'] = 0;

                $this->addMethod($dataSource);
            }
        }

        return $newId;
    }


    /**
     * @param $idGost
     * @return array|false
     */
    public function getGost($idGost)
    {
        $organizationId = App::getOrganizationId();
        return $this->DB->Query(
            "select * from `ulab_norm_doc_gost` where id = {$idGost} AND `organization_id` = {$organizationId}"
        )->Fetch();
    }


    /**
     * @param $id
     */
    public function deletePermanentlyGost($id)
    {
        $organizationId = App::getOrganizationId();
        $methodList = $this->getListMethodByGostId($id);

        foreach ($methodList as $method) {
            $this->deletePermanentlyMethod($method['id']);
        }

        $this->DB->Query("delete from ulab_norm_doc_gost where id = {$id} AND organization_id = {$organizationId}");
    }


    /**
     * @param $methodId
     */
    public function deletePermanentlyMethod($methodId)
    {
        $this->DB->Query("delete from ulab_norm_doc_methods where id = {$methodId}");
    }


    /**
     * делает методику неактуальной
     * @param $id
     */
    public function deleteMethod($id)
    {
        $this->DB->Query("update ulab_norm_doc_methods set is_actual = 0 where id = {$id}");
    }


    public function deleteMethodByGost($gostId)
    {
        $this->DB->Update('ulab_norm_doc_methods', ['is_actual' => 0], "where gost_id = {$gostId}");
    }


    /**
     * @param $id
     * @param $data
     * @return false|mixed|string
     */
    public function updateGost($id, $data)
    {
        $organizationId = App::getOrganizationId();

        $sqlData = $this->prepearTableData('ulab_norm_doc_gost', $data);

        $where = "WHERE id = {$id} AND organization_id = {$organizationId}";

        return $this->DB->Update('ulab_norm_doc_gost', $sqlData, $where);
    }


    /**
     * @param $data
     * @return false|mixed|string
     */
    public function addMethod($data)
    {
        $sqlData = $this->prepearTableData('ulab_norm_doc_methods', $data);

        $idMethod = $this->DB->Insert('ulab_norm_doc_methods', $sqlData);

        return $idMethod;
    }


    /**
     * @param $id
     * @param $data
     * @return bool|int|string
     */
    public function updateMethod($id, $data)
    {
        $sqlData = $this->prepearTableData('ulab_norm_doc_methods', $data);

        $where = "WHERE id = {$id}";
        return $this->DB->Update('ulab_norm_doc_methods', $sqlData, $where);
    }


    /**
     * @param $id
     * @return array|false
     */
    public function getMethod($id)
    {
        if ( empty($id) ) {
            return [];
        }

        $organizationId = App::getOrganizationId();

        $result = $this->DB->Query(
            "select 
                    m.*, 
                    g.reg_doc, g.year, g.description, g.materials,
                    d.unit_rus, d.name as unit_name, d.fsa_id as unit_fsa_id
                from `ulab_norm_doc_methods` as m
                left join `ulab_dimension` as d on d.id = m.unit_id
                inner join `ulab_norm_doc_gost` as g on g.id = m.gost_id 
                where m.id = {$id} AND g.organization_id = {$organizationId}"
        )->Fetch();

        if ( !empty($result) ) {
            $strYear = !empty($result['year']) ? "-{$result['year']}" : '';
            $strClause = !empty($result['clause']) ? " {$result['clause']}" : '';

            $result['view_gost'] = "{$result['reg_doc']}{$strYear}{$strClause} | {$result['name']}";
            $result['view_name'] = "{$result['reg_doc']}{$strYear}{$strClause} {$result['name']}";
            $result['view_name_year'] = "{$result['reg_doc']}{$strYear}";
            $result['view_gost_for_protocol'] = "{$result['reg_doc']}{$strYear}{$strClause}";
        } else {
            return [];
        }

        return $result;
    }


    /**
     * @param $methodId
     * @return false|mixed|string
     */
    public function copyMethod($methodId, $newGostId = '')
    {
        $dataSource = $this->getMethod($methodId);

        if ( empty($dataSource) ) {
            return false;
        }

        if ( !empty($newGostId) ) {
            $dataSource['gost_id'] = $newGostId;
        }

        unset($dataSource['id']);
//        $dataSource['name'] = 'КОПИЯ ' . $dataSource['name'];
        $dataSource['is_confirm'] = 0;

        $idNewMethod = $this->addMethod($dataSource);

        if ( empty($idNewMethod) ) {
            return false;
        }

        return $idNewMethod;
    }


    /**
     * @param $gostId
     * @return array
     */
    public function getListMethodByGostId($gostId)
    {
        $organizationId = App::getOrganizationId();

        $sql = $this->DB->Query(
            "select m.*,
                        d.id unit_id, d.unit_rus, d.name unit_name
                    from `ulab_methods` as m
                    inner join ulab_gost as g on g.id = m.gost_id 
                    left join `ulab_dimension` as d on d.id = m.unit_id
                    where m.`gost_id` = {$gostId} AND g.organization_id = {$organizationId} 
                    order by m.id desc"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $strMpName = StringHelper::removeSpace($row['method_name']);
            $clause = StringHelper::removeSpace($row['clause']);
            $strYear = !empty($row['year']) ? "-{$row['year']}" : '';
            $strClause = !empty($clause) ? " {$clause}" : '';
            $row['view_gost'] = "{$row['reg_doc']}{$strYear}{$strClause} | {$strMpName}";

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @return array|false
     */
    public function getMethodList()
    {
        $organizationId = App::getOrganizationId();

        $sql = $this->DB->Query(
            "select 
                    m.*, 
                    g.reg_doc, g.year, g.description, g.materials,
                    d.unit_rus, d.name as unit_name, d.fsa_id as unit_fsa_id
                from `ulab_norm_doc_methods` as m
                left join `ulab_dimension` as d on d.id = m.unit_id
                inner join `ulab_norm_doc_gost` as g on g.id = m.gost_id 
                where `organization_id` = {$organizationId}"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $strYear = !empty($row['year']) ? "-{$row['year']}" : '';
            $strClause = !empty($row['clause']) ? " {$row['clause']}" : '';

            $row['view_name'] = $row['view_gost'] = "{$row['reg_doc']}{$strYear}{$strClause} | {$row['name']}";
            $row['view_gost_for_protocol'] = "{$row['reg_doc']}{$strYear}{$strClause}";

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param $data
     */
    public function addMaterialGroupNormDoc($data)
    {

        if ( $data['val_1'] == '' ) {
            $data['no_val_1'] = 1;
        }
        if ( $data['val_2'] == '' ) {
            $data['no_val_2'] = 1;
        }


        $sqlData = $this->prepearTableData('materials_groups_tu', $data);

        $this->DB->Insert('materials_groups_tu', $sqlData);
    }


    public function deleteMethodGroup($mgtId)
    {
        $this->DB->Query("delete from materials_groups_tu where id = {$mgtId}");
    }


    public function updateMethodGroup($data)
    {
        foreach ($data as $id => $row) {
            if ( $row['val_1'] == '' ) {
                $row['no_val_1'] = 1;
            } else {
                $row['no_val_1'] = 0;
            }
            if ( $row['val_2'] == '' ) {
                $row['no_val_2'] = 1;
            } else {
                $row['no_val_2'] = 0;
            }

            $sqlData = $this->prepearTableData('materials_groups_tu', $row);

            $this->DB->Update('materials_groups_tu', $sqlData, "where id = {$id}");
        }
    }
}
