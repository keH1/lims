<?php


/**
 * Модель для работы с материалами
 * Class LabPassport
 */
class LabPassport extends Model
{
    public function getUlabGostList($passportId)
    {
        $sql = "SELECT umtr.id umtr_id, umtr.deal_id, umtr.probe_number, umtr.cipher, umtr.protocol_id, 
                       m.ID m_id, m.NAME m_mame, 
                       ugtp.id ugtp_id, ugtp.method_id, ugtp.conditions_id, ugtp.gost_number, ugtp.actual_value AS actual_value_new, 
                       ug.reg_doc bgm_gost, um.name bgm_specification, bgm.ED bgm_ed, um.clause GOST_PUNKT,
                       utr.match, utr.actual_value, utr.normative_value, utr.average_value, p.NUMBER p_number,
                       DATE_FORMAT(ba.DATE_SOZD, '%d.%m.%Y') AS date,
                       ud.unit_rus AS unit_char, 
                       op.scheme_id, sg.range_from, sg.range_before,
                       JSON_EXTRACT(utr.actual_value, '$[0]') AS actual_value_text, JSON_EXTRACT(utr.actual_value, '$[0]') REGEXP '[а-Я]' AS actual_value_type
                FROM ulab_material_to_request umtr 
                LEFT JOIN MATERIALS m ON m.ID = umtr.material_id 
                LEFT JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id 
                LEFT JOIN ba_gost bgm ON bgm.ID = ugtp.method_id
                LEFT JOIN ulab_trial_results utr ON utr.gost_to_probe_id = ugtp.id 
                LEFT JOIN PROTOCOLS p ON p.ID = umtr.protocol_id 
                LEFT JOIN ba_tz ba ON ID_Z = umtr.deal_id 
                LEFT JOIN oz_passport op ON op.ba_tz_id = ba.ID
                
                LEFT JOIN ulab_methods um ON um.id = ugtp.new_method_id
                LEFT JOIN ulab_dimension ud ON ud.id = um.unit_id     
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                LEFT JOIN oz_scheme_gost sg ON sg.scheme_id = op.scheme_id AND sg.method_id = um.id
                WHERE op.id = {$passportId} GROUP BY ugtp_id";

        //AND bgm.GOST_TYPE <> 'metodic_otbor'

        $data = [];

        $stmt = $this->DB->query($sql);

        $index = 0;

        while ($row = $stmt->fetch()) {

            if (intval($row["deal_id"]) > 0) {
                $actualValue = $row["actual_value_new"];
            } else {
                if ($row["actual_value"] == "[\"\"]") {
                    $row["actual_value"] = NULL;
                }

                $actualValue = str_replace(['"', '[', ']'], '', $row["actual_value"]);
                $actualValue = str_replace([','], '.', $actualValue);
            }


            $data[$index]["title"] = $row["bgm_specification"];
            $data[$index]["value"] = $row["average_value"];
            $data[$index]["bgm_ed"] = $row["bgm_ed"];
            $data[$index]["unit_char"] = $row["unit_char"];
            $data[$index]["gost"] = $row["bgm_gost"];
            $data[$index]["gost_punkt"] = $row["GOST_PUNKT"];
            $data[$index]["range_from"] = $row["range_from"];
            $data[$index]["range_before"] = $row["range_before"];
            $data[$index]["scheme_id"] = $row["scheme_id"];

            $data[$index]["actual_value"] = $actualValue;

            $rangeFrom = $row["range_from"];
            $rangeBefore = $row["range_before"];

            if (is_null($rangeFrom)) {
                $rangeFrom = -INF;
            } else {
                $rangeFrom = floatval($rangeFrom);
            }

            if (is_null($rangeBefore)) {
                $rangeBefore = INF;
            } else {
                $rangeBefore = floatval($rangeBefore);
            }

            // if (is_null($avgValue) || $avgValue == 0) {
            if (is_null($actualValue) || empty($actualValue)) {
                $data[$index]["background"] = "";
            } else {
                $actualValue = floatval($actualValue);
                $data[$index]["background"] = $actualValue >= $rangeFrom && $actualValue <= $rangeBefore ? "bg-light-green-2" : "bg-light-red";
            }

            if ($row["actual_value_type"] == 1) {
                $data[$index]["background"] = "bg-orange-2";
                // $data[$index]["value"] = str_replace('"', '', $row["actual_value_text"]);
            }

            $data[$index]["test2"] = $actualValue;
            $data[$index]["test"] = "{$actualValue} >= {$rangeFrom} && {$actualValue} <= {$rangeBefore}";

            $index++;
        }

        return $data;
    }


    public function getOzGostList($passportId)
    {
        $sql = "SELECT opg.id AS oz_tz_gost_id, opg.value, sg.*, ug.reg_doc AS gost, um.name AS spec
                FROM oz_passport_gost AS opg
                LEFT JOIN oz_passport AS op ON opg.oz_passport_id = op.id
                LEFT JOIN oz_scheme_gost AS sg ON sg.id = opg.scheme_gost_id
                LEFT JOIN ulab_methods um ON um.id = sg.method_id
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                WHERE op.id = {$passportId}";

        $stmt = $this->DB->query($sql);
        $data = [];

        while ($row = $stmt->fetch()) {


            $avgValue = $row["value"];
            $rangeFrom = $row["range_from"];
            $rangeBefore = $row["range_before"];

            $valueStatus = "";

            if (is_null($rangeFrom)) {
                $rangeFrom = -INF;
            } else {
                $rangeFrom = floatval($rangeFrom);
            }

            if (is_null($rangeBefore)) {
                $rangeBefore = INF;
            } else {
                $rangeBefore = floatval($rangeBefore);
            }

            if (is_null($avgValue)) {
                $row["background"] = "";
            } else {
                $avgValue = floatval($avgValue);
                $row["background"] = $avgValue >= $rangeFrom && $avgValue <= $rangeBefore ? "bg-light-green-2" : "bg-light-red";
            }

            $row["test"] = "{$avgValue} >= {$rangeFrom} && {$avgValue} <= {$rangeBefore}";

            $data[] = $row;
        }

        return $data;
    }

    public function getResultCardInfo($passportId)
    {

        $sql = "SELECT op.*, m.NAME as material_name, om.manufacturer, 
                       bt.REQUEST_TITLE AS ulab_title, bt.ID_Z AS deal_id, bt.DAY_TO_TEST AS day_to_test, bt.STAGE_ID AS stage_id,
                       c.ID AS comment_id, c.TEXT AS comment_text 
                FROM oz_passport AS op
                LEFT JOIN oz_scheme AS os ON os.id = op.scheme_id
                LEFT JOIN MATERIALS AS m ON m.ID = os.material_id
                LEFT JOIN oz_materials AS om ON om.ulab_material_id = m.ID
                LEFT JOIN ba_tz AS bt ON bt.ID = op.ba_tz_id
                LEFT JOIN COMMENTS AS c ON c.ID_REQ = bt.ID_Z 
                WHERE op.id = {$passportId}";

        $stmt = $this->DB->query($sql);

        return $stmt->fetch();
    }

    public function updateRow($data, $id)
    {
        $where = "WHERE ID = {$id}";
        $sqlData = $this->prepearTableData('oz_passport', $data);
        return $this->DB->Update('oz_passport', $sqlData, $where);
    }


}