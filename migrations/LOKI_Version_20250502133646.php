<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;

class LOKI_Version_20250502133646 extends Version
{
    protected $author = "k.shagalin";

    protected $description = "LOKI-3434 изменение кодировки для полей";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        $conn = Application::getConnection();
        $conn->queryExecute("ALTER TABLE ROOMS MODIFY NAME VARCHAR(255) COLLATE utf8mb3_unicode_ci;");
        $conn->queryExecute("ALTER TABLE ROOMS MODIFY NUMBER VARCHAR(255) COLLATE utf8mb3_unicode_ci;");
    }

    public function down()
    {
        //your code ...
    }
}
