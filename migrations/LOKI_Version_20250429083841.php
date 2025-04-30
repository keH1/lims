<?php

namespace Sprint\Migration;


class LOKI_Version_20250429083841 extends Version
{
    protected $author = "y.o.lobanov";

    protected $description = "удаление organization_id из справочников";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;

        // Список таблиц, где нужно удалить поле
        $tables = [
            'reactive_pure',
            'aggregate_state',
        ];

        foreach ($tables as $tableName) {
            // Проверяем, существует ли колонка
            $result = $DB->Query(
                "SELECT column_name 
                FROM information_schema.columns 
                WHERE table_name = '" . $DB->ForSql($tableName) . "' 
                AND column_name = 'organization_id'"
            );

            if ($result->Fetch()) {
                // Если колонка есть, удаляем её
                $DB->Query("
                    ALTER TABLE `" . $DB->ForSql($tableName) . "`
                    DROP `organization_id`;
                ");
            }
        }
    }

    public function down()
    {
        //your code ...
    }
}
