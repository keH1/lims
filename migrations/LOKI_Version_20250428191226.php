<?php

namespace Sprint\Migration;


class LOKI_Version_20250428191226 extends Version
{
    protected $author = "roman";

    protected $description = "Добавление org_id к таблицам: KP, ACT_BASE";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();
        
        $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'KP' 
            AND column_name = 'NUMBER'
        ");

        if (!$result->Fetch()) {
            $DB->Query("
                ALTER TABLE KP 
                ADD COLUMN NUMBER INT(11) NULL AFTER ID
            ");
        }
        
        $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'KP' 
            AND column_name = 'ORGANIZATION_ID'
        ");

        if (!$result->Fetch()) {
            $DB->Query("
                ALTER TABLE KP 
                ADD COLUMN ORGANIZATION_ID INT(11) NULL
            ");
        }
        
        $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'ACT_BASE' 
            AND column_name = 'ORGANIZATION_ID'
        ");

        if (!$result->Fetch()) {
            $DB->Query("
                ALTER TABLE ACT_BASE 
                ADD COLUMN ORGANIZATION_ID INT(11) NULL
            ");
        }
    }

    public function down()
    {
        global $DB;
        
        $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'KP' 
            AND column_name = 'NUMBER'
        ");

        if ($result->Fetch()) {
            $DB->Query("
                ALTER TABLE KP 
                DROP COLUMN NUMBER
            ");
        }
        
        $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'KP' 
            AND column_name = 'ORGANIZATION_ID'
        ");

        if ($result->Fetch()) {
            $DB->Query("
                ALTER TABLE KP 
                DROP COLUMN ORGANIZATION_ID
            ");
        }
        
        $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = 'ACT_BASE' 
            AND column_name = 'ORGANIZATION_ID'
        ");

        if ($result->Fetch()) {
            $DB->Query("
                ALTER TABLE ACT_BASE 
                DROP COLUMN ORGANIZATION_ID
            ");
        }
    }
}