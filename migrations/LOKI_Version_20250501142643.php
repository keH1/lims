<?php

namespace Sprint\Migration;


class LOKI_Version_20250501142643 extends Version
{
    protected $author = "r.sharipov";

    protected $description = "удаление поля organization_id из таблицы electric_norm";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $DB->Query("ALTER TABLE electric_norm DROP COLUMN organization_id");
    }

    public function down()
    {
        //your code ...
    }
}
