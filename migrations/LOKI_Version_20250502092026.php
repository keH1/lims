<?php

namespace Sprint\Migration;


class LOKI_Version_20250502092026 extends Version
{
    protected $author = "roman";

    protected $description = "Добавление поля ORGANIZATION_ID в таблицу INVOICE";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        $result = $DB->Query("SELECT column_name FROM information_schema.columns 
            WHERE table_name = 'INVOICE' AND column_name = 'ORGANIZATION_ID'");

        if (!$result->Fetch()) {
            $DB->Query("ALTER TABLE INVOICE 
                ADD COLUMN ORGANIZATION_ID INT(11) NULL COMMENT 'ИД организации'");
        }
    }

    public function down()
    {
        global $DB;
        
        $result = $DB->Query("SELECT column_name FROM information_schema.columns 
            WHERE table_name = 'INVOICE' AND column_name = 'ORGANIZATION_ID'");

        if ($result->Fetch()) {
            $DB->Query("ALTER TABLE INVOICE DROP COLUMN ORGANIZATION_ID");
        }
    }
}