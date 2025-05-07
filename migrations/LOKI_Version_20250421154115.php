<?php

namespace Sprint\Migration;


class LOKI_Version_20250421154115 extends Version
{
    protected $author = "k.shagalin";

    protected $description = "LOKI-3050 добавление поля organization_id в таблицы";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        $columnName = "organization_id";

        // Список таблиц, где нужно добавить поле
        $tables = [
            'ba_laba',
            'ba_oborud',
            'ba_tz',
            'ulab_gost',
            'ba_gost',
            'water',
            'ZERN',
            'standart_titr',
            'standart_titr_receive',
            'standart_titr_manufacturer',
            'ulab_measured_properties',
            'fire_safety_log',
            'safety_training_log',
            'HISTORY',
            'MATERIALS',
            'secondment',
            'coal_regeneration',
            'full_bdb',
            'empty_bdb',
            'ulab_dimension',
            'PROTOCOLS',
            'ulab_start_trials',
        ];

        foreach ($tables as $tableName) {
            // Проверяем, существует ли колонка
            $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = '" . $DB->ForSql($tableName) . "' 
            AND column_name = '{$columnName}'
        ");

            if (!$result->Fetch()) {
                // Если колонки нет, добавляем её
                $DB->Query("
                ALTER TABLE " . $DB->ForSql($tableName) . " 
                ADD COLUMN {$columnName} INT DEFAULT 1
            ");
            } else {
                // Если колонка уже существует, заполняем её значением 1
                $DB->Query("
                UPDATE " . $DB->ForSql($tableName) . " 
                SET {$columnName} = 1 
                WHERE {$columnName} IS NULL
            ");
            }
        }
    }

    public function down()
    {
        //your code ...
    }
}
