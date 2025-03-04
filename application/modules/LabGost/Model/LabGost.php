<?php


class LabGost extends Model
{
    public function getGostBySchemeId($schemeId)
    {
        $result = [];

        $sql = "SELECT um.id AS id, ug.reg_doc AS title, um.name AS spec, um.clause AS item, sg.id AS scheme_gost_id, sg.laboratory_status, um.price AS price  
                FROM oz_scheme_gost AS sg 
                LEFT JOIN ba_gost AS bg ON bg.ID = sg.gost_id 
                LEFT JOIN ulab_methods um ON um.id = sg.method_id
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id 
                WHERE sg.scheme_id = {$schemeId} AND sg.del <> 1";

        $stmt = $this->DB->query($sql);

        while ($row = $stmt->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getMethodList()
    {
        $result = [];

        $sql = "SELECT distinct um.id, um.name, um.clause, ug.reg_doc 
                FROM ulab_methods AS um
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                WHERE um.is_actual = 1";

        $stmt = $this->DB->Query($sql);

        while ($row = $stmt->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getList()
    {
        $result = [];

        $sql = "SELECT distinct ID, GOST, SPECIFICATION, GOST_PUNKT 
                FROM ba_gost 
                WHERE NON_ACTUAL <> 1 
                  AND ((IN_OA <> '0' AND ulab_method_id is not null) 
                           OR (IN_OA = '0' AND ulab_method_id is null and NUM_OA_NEW <> 0))";

//        $sql = "SELECT ID, GOST, SPECIFICATION
//            FROM ba_gost";

        $baGost = $this->DB->Query($sql);

        while ($row = $baGost->fetch_assoc()) {
            $result[] = $row;
        }

        return $result;
    }

    public function addGostToProbe($probeId, $gostId, $price = 0)
    {
        $sql = "INSERT INTO gost_to_probe (probe_id, gost_method, new_method_id, gost_conditions, price)
                VALUES ({$probeId}, {$gostId}, {$gostId}, 2522, {$price})";

        $this->DB->query($sql);

        return $this->DB->LastID();
    }

    public function addUlabGostToProbe($gostId, $materialToRequestId, $number, $price = 0)
    {
        $sql = "INSERT INTO ulab_gost_to_probe (method_id, new_method_id, conditions_id, price, material_to_request_id, gost_number)
                VALUES ({$gostId}, {$gostId}, 2522, {$price}, {$materialToRequestId}, {$number})";

        $this->DB->query($sql);

        return $this->DB->LastID();
       // return $sql;
    }

    public function updatePassportGost($passportGostId, $data)
    {
        $where = "WHERE ID = {$passportGostId}";
        $sqlData = $this->prepearTableData('oz_passport_gost', $data);
        return $this->DB->Update('oz_passport_gost', $sqlData, $where);
    }
}