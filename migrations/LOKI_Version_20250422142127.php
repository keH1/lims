<?php

namespace Sprint\Migration;


class LOKI_Version_20250422142127 extends Version
{
    protected $author = "k.shagalin";

    protected $description = "LOKI-2589 [back] Доработать модели получения и записи данных в которых требуется разграничение по организации вспомогательных журналов часть 1";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        $columnName = "organization_id";

        // Список таблиц, где нужно добавить поле
        $tables = [
            'disinfection_conditioners',
            'reactive',
            'reactive_consume',
            'recipe_model',
            'library_reactive',
            'electric_control',
            'electric_norm',
            'ST_SAMPLE',
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
