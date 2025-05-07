<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;

class LOKI_Version_20250422121718 extends Version
{
    protected $author = "roman";

    protected $description = "Изменение типа поля date_act в таблице CompletedAct на тип date";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        $result = $DB->Query("
            SELECT DATA_TYPE 
            FROM information_schema.columns 
            WHERE table_name = 'CompletedAct' 
            AND column_name = 'date_act'
        ");

        $row = $result->Fetch();
        
        if ($row) {
            $DB->Query("
                ALTER TABLE CompletedAct 
                ADD COLUMN temp_date_act DATE NULL
            ");
            
            $DB->Query("
                UPDATE CompletedAct 
                SET temp_date_act = STR_TO_DATE(date_act, '%d.%m.%Y')
                WHERE date_act IS NOT NULL AND date_act != ''
            ");
            
            $DB->Query("
                ALTER TABLE CompletedAct 
                DROP COLUMN date_act
            ");
            
            $DB->Query("
                ALTER TABLE CompletedAct 
                CHANGE COLUMN temp_date_act date_act DATE NULL
            ");
        }
    }

    public function down()
    {
        global $DB;
        
        $result = $DB->Query("
            SELECT DATA_TYPE 
            FROM information_schema.columns 
            WHERE table_name = 'CompletedAct' 
            AND column_name = 'date_act'
        ");

        $row = $result->Fetch();

        if ($row && $row['DATA_TYPE'] == 'date') {
            $DB->Query("
                ALTER TABLE CompletedAct 
                ADD COLUMN temp_date_act VARCHAR(10) NULL
            ");
            
            $DB->Query("
                UPDATE CompletedAct 
                SET temp_date_act = DATE_FORMAT(date_act, '%d.%m.%Y')
                WHERE date_act IS NOT NULL
            ");
            
            $DB->Query("
                ALTER TABLE CompletedAct 
                DROP COLUMN date_act
            ");
            
            $DB->Query("
                ALTER TABLE CompletedAct 
                CHANGE COLUMN temp_date_act date_act VARCHAR(10) NULL
            ");
        }
    }
}