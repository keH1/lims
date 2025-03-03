<?php


class LabScheme extends Model
{
    public function getSchemeEditorData($filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'm.ID',
            'dir' => 'DESC'
        ];

        $schemeListSubSql = "(SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', s.id, 'name', s.name) SEPARATOR ', '), ']') AS oz_scheme_name FROM oz_scheme AS s WHERE s.material_id = m.ID AND s.del != 1)";

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел


            // работа с фильтрами
            if (!empty($filter['search'])) {
                // Заявка
                if (isset($filter['search']['material_name'])) {
                    $where .= "m.NAME LIKE '%{$filter['search']['material_name']}%' AND ";
                }

                if (isset($filter['search']['scheme_list'])) {
                    $where .= "{$schemeListSubSql} LIKE '%{$filter['search']['scheme_list']}%' AND ";
                }

                if (isset($filter['search']['manufacturer'])) {
                    $where .= "oz_m.manufacturer LIKE '%{$filter['search']['manufacturer']}%' AND ";
                }

                if (isset($filter['search']['type'])) {
                    $where .= "oz_m.type = {$filter['search']['type']} AND ";
                }


            }

            // работа с сортировкой
            if (!empty($filter['order'])) {
                if ($filter['order']['dir'] === 'asc') {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {

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
        }

        $where .= "1 ";

        $result = [];



        $sql = "SELECT m.ID AS material_id, m.NAME AS material_name, oz_m.manufacturer, {$schemeListSubSql} AS scheme_list FROM oz_materials AS oz_m LEFT JOIN MATERIALS AS m ON m.ID = oz_m.ulab_material_id WHERE oz_m.del != 1 AND {$where} GROUP BY m.ID ORDER BY {$order['by']} {$order['dir']} {$limit}";

        $stmt = $this->DB->Query($sql);

        while ($row = $stmt->Fetch()) {
            $result[] = $row;
        }

        $dataTotal = $this->DB->Query(
            "SELECT m.ID AS material_id, m.NAME AS material_name
                FROM oz_materials AS oz_m
                LEFT JOIN MATERIALS AS m ON m.ID = oz_m.ulab_material_id
                WHERE oz_m.del != 1"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT m.ID AS material_id, m.NAME AS material_name
                FROM oz_materials AS oz_m
                LEFT JOIN MATERIALS AS m ON m.ID = oz_m.ulab_material_id
                WHERE oz_m.del != 1 AND {$where}"
        )->SelectedRowsCount();

        // $result = $data;
        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;
       // $result['test'] = $sql;

        return $result;
    }

    public function update($materialId, $materialName)
    {
        $sql = "UPDATE MATERIALS SET NAME = '$materialName' WHERE ID = {$materialId}";
        $this->DB->Query($sql);
    }

    public function updateOz($materialId, $manufacturer)
    {
        $sql = "UPDATE oz_materials SET manufacturer = '$manufacturer' WHERE ulab_material_id = {$materialId}";
        $this->DB->Query($sql);
    }

    public function add($name)
    {

        $sql = "INSERT INTO MATERIALS (NAME)
                VALUES ('{$name}')";
        $this->DB->Query($sql);

        return $this->DB->LastID();
    }

    public function addToOz($ulabMaterialId, $manufacturer, $type)
    {
        $sql = "INSERT INTO oz_materials (ulab_material_id, manufacturer, type)
                VALUES ({$ulabMaterialId}, '{$manufacturer}', '{$type}')";

        $this->DB->query($sql);

        return $this->DB->LastID();
    }

    public function delete($ulabMaterialId)
    {
        $sql = "UPDATE oz_materials SET del = 1 WHERE ulab_material_id = {$ulabMaterialId}";
        $this->DB->Query($sql);
    }

    public function addScheme($materialId, $schemeName, $gostArr = [])
    {
        $sql = "INSERT INTO oz_scheme (material_id, name) 
                VALUES ({$materialId}, '{$schemeName}')";

        $this->DB->query($sql);

        $schemeId = $this->DB->LastID();

        if (!empty($gostArr)) {
            foreach ($gostArr as $gostId) {
                $sql = "INSERT INTO oz_scheme_gost (scheme_id, gost_id)
                VALUES ({$schemeId}, '{$gostId}')";

                $this->DB->query($sql);
            }
        }


        return $schemeId;
    }

    public function getSchemeCardData($filter)
    {
        $where = "sg.del <> 1 AND ";
        $limit = "";
        $order = [
            'by' => 'sg.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {
                // Заявка
                if (isset($filter['search']['scheme_id'])) {
                    $where .= "sg.scheme_id = {$filter['search']['scheme_id']} AND ";
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
        }


        $where .= "1 ";

        $result = [];

        $sql = "SELECT um.id AS id, ug.reg_doc AS title, um.name AS spec, um.clause AS item, sg.param, 
                sg.range_from, sg.range_before, sg.laboratory_status, sg.id AS scheme_gost_id
                FROM oz_scheme_gost AS sg 
                LEFT JOIN ba_gost AS bg ON bg.ID = sg.gost_id
                LEFT JOIN ulab_methods um ON um.id = sg.method_id
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                WHERE {$where} 
               
                ORDER BY {$order['by']} {$order['dir']} {$limit}
               ";

        $stmt = $this->DB->query($sql);

        while ($row = $stmt->Fetch()) {
            $result[] = $row;
        }

        $dataTotal = $this->DB->Query(
            "SELECT bg.ID AS id, bg.GOST AS title, bg.SPECIFICATION AS spec, bg.GOST_PUNKT AS item 
                FROM oz_scheme_gost AS sg 
                LEFT JOIN ba_gost AS bg ON bg.ID = sg.gost_id
                WHERE {$where} 
                GROUP BY bg.ID "
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT bg.ID AS id, bg.GOST AS title, bg.SPECIFICATION AS spec, bg.GOST_PUNKT AS item 
                FROM oz_scheme_gost AS sg 
                LEFT JOIN ba_gost AS bg ON bg.ID = sg.gost_id
                WHERE {$where} 
                GROUP BY bg.ID"
        )->SelectedRowsCount();

        // $result = $data;
        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;
        $result['test'] = $filter;

        return $result;
    }

    public function getSchemeById($id)
    {
        $sql = "SELECT s.*, m.NAME AS material_name, om.type AS material_type 
                FROM oz_scheme AS s
                LEFT JOIN MATERIALS AS m ON m.ID = s.material_id 
                LEFT JOIN oz_materials AS om ON om.ulab_material_id = s.material_id 
                WHERE s.id = {$id}";

        $stmt = $this->DB->query($sql);

        return $stmt->Fetch();
    }

    public function getOzMaterials($type = "")
    {
        $result = [];

        $where = "";

        if ($type !== "") {
            if ($type == 0) {
                $where .= " AND oz_m.type = 0";
            } elseif ($type == 1) {
                $where .= " AND oz_m.type = 1";
            }
        }

        $sql = "SELECT m.ID AS id, m.NAME AS name 
                FROM oz_materials AS oz_m
                LEFT JOIN MATERIALS AS m ON m.ID = oz_m.ulab_material_id
                WHERE oz_m.del != 1 {$where}
                GROUP BY m.ID 
                ORDER BY oz_m.id DESC
               ";

        $stmt = $this->DB->query($sql);

        while ($row = $stmt->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function addGostToScheme($schemeId, $gostId, $rangeFrom, $rangeBefore, $laboratoryStatus, $param)
    {
        $sql = "INSERT INTO oz_scheme_gost (scheme_id, method_id, range_from, range_before, laboratory_status, param)
                VALUES ({$schemeId}, {$gostId}, {$rangeFrom}, {$rangeBefore}, {$laboratoryStatus}, '{$param}')";

        $this->DB->query($sql);

        return $this->DB->LastID();
    }

    public function updateGostToScheme($schemeGostId, $data)
    {
        $where = "WHERE id = {$schemeGostId}";
        $sqlData = $this->prepearTableData('oz_scheme_gost', $data);
        return $this->DB->Update('oz_scheme_gost', $sqlData, $where);

//        [
//            "range_from" => $rangeFrom,
//            "range_before" => $rangeBefore,
//            "laboratory_status" => $laboratoryStatus,
//            "param" => $param
//        ] = $data;
//
//        $sql = "UPDATE oz_scheme_gost
//                SET range_from = {$rangeFrom},
//                    range_before = {$rangeBefore},
//                    laboratory_status = {$laboratoryStatus},
//                    param = '{$param}'
//
//                WHERE id = {$schemeGostId}";
//        $this->DB->query($sql);
    }

    public function getList($materialId)
    {
        $result = [];

        $where = "";

        if (!empty($materialId)) {
            $where .= "s.material_id = {$materialId} AND ";
        }

        $where .= "1";

//        $sql = "SELECT s.id AS scheme_id, s.name AS scheme_name,
//                sg.gost_id AS gost_id,
//                bg.gost, bg.gost_punkt
//                FROM scheme s
//                LEFT JOIN scheme_gost sg ON sg.scheme_id= s.id
//                LEFT JOIN ba_gost as bg ON  bg.id = sg.gost_id
//                WHERE {$where}";

        $sql = "SELECT s.id AS scheme_id, s.name AS scheme_name,
                    (SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('gost', ug.reg_doc, 'spec', um.name, 'status', sg.laboratory_status) SEPARATOR ', '), ']')
                    FROM oz_scheme_gost sg
                    LEFT JOIN oz_scheme ss ON sg.scheme_id= ss.id
                    LEFT JOIN ba_gost as bg ON  bg.id = sg.gost_id
                    LEFT JOIN ulab_methods um ON um.id = sg.method_id
                    LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                    WHERE s.material_id = {$materialId} AND ss.id = s.id AND sg.del <> 1) AS gost_list
                FROM oz_scheme s
                WHERE material_id = {$materialId} AND s.del != 1";

        $stmt = $this->DB->query($sql);

        while ($row = $stmt->fetch()) {
            $row["gost_list"] = htmlspecialchars($row["gost_list"]);
            $result[] = $row;
        }

        return $result;
        // return $materialId;
    }

    public function getMaterialById($id)
    {
        $sql = "SELECT * 
                FROM MATERIALS
                WHERE id = {$id}";

        $stmt = $this->DB->query($sql);

        return $stmt->fetch();
    }

    public function deleteScheme($schemeId)
    {
        $sql = "UPDATE oz_scheme SET del = 1 WHERE id = {$schemeId}";
        $this->DB->query($sql);
    }

    public function deleteGost($id)
    {
        $sql = "UPDATE oz_scheme_gost SET del = 1 WHERE id = {$id}";
        $this->DB->query($sql);
    }

    public function setMaterialToRequest($dealId, $dataList)
    {
        // TODO: не помню зачем, возможно не надо
//        $this->DB->Query("DELETE FROM `MATERIALS_TO_REQUESTS` WHERE ID_DEAL = {$dealId}");

        $mtrArrayId = [];

        $m_number = 1;
        foreach ($dataList as $material) {
            $k = 1;

//            $data = [
//                'ID_DEAL' => $dealId,
//                'ID_MATERIAL' => $material['id'],
//                'OBIEM' => $material['count'],
//                'NAME_MATERIAL' => $material['name'],
//            ];
//
//            // TODO: устарело
//            $columns = implode(", ",array_keys($data));
//            $escaped_values = array_map(array('real_escape_string'), array_values($data));
//            $values  = implode("', '", $escaped_values);
//            $sql = "INSERT INTO `MATERIALS_TO_REQUESTS`({$columns}) VALUES ('{$values}')";

            $materialId = $material['id'];
            $count = $material['count'];
            $materialName = $material['name'];

            $sql = "INSERT INTO MATERIALS_TO_REQUESTS (ID_DEAL, ID_MATERIAL, OBIEM, NAME_MATERIAL) 
                    VALUES ({$dealId}, {$materialId}, {$count}, '{$materialName}')";

            $this->DB->query($sql);

            $mtrId = $this->DB->LastID();

            $sql = "INSERT INTO ulab_material_to_request (mtr_id, deal_id, material_id, material_number)  
                    VALUES ({$mtrId}, {$dealId}, {$materialId}, {$m_number})";

            for ($i = 0; $i < $material['count']; $i++) {
                $dataProbe['probe_number'] = $k;
                $this->DB->query($sql);
                $k++;
                $umtrId = $this->DB->LastID();
                $mtrArrayId[] = $umtrId;

            }

            $m_number++;
        }

        return ["mtr_id" => $mtrId, "umtr_id" => $umtrId];
    }
}