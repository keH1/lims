<?php

namespace Sprint\Migration;


class LOKI_Version_20250505204838 extends Version
{
    protected $author = "r.sharipov";

    protected $description = "добавление поля local_id в таблицу transport";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        $conn = \Bitrix\Main\Application::getConnection();
        $conn->queryExecute("ALTER TABLE transport ADD COLUMN local_id INT NULL COMMENT 'ID внутри организации';");
    }

    public function down()
    {
        //your code ...
    }
}
