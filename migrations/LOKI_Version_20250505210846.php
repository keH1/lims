<?php

namespace Sprint\Migration;


class LOKI_Version_20250505210846 extends Version
{
    protected $author = "r.sharipov";

    protected $description = "проставление значения local_id всему транспорту";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        $counter = [];
        $conn = \Bitrix\Main\Application::getConnection();
        $sql = "SELECT * FROM transport ORDER BY organization_id,id;";
        $res = $conn->query($sql);
        while ($row = $res->fetch()) {
            if (isset($counter[$row["organization_id"]])) {
                $counter[$row["organization_id"]]++;
            } else {
                $counter[$row["organization_id"]] = 1;
            }
            $conn->queryExecute(
                "UPDATE `transport` SET `local_id` = {$counter[$row["organization_id"]]} WHERE `id` = {$row["id"]}"
            );
        }
    }

    public function down()
    {
        //your code ...
    }
}
