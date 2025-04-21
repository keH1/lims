<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;

class Version_20250414191158 extends Version
{
    protected $author = "k.shagalin";

    protected $description = "добавление поля organization_id в таблицы";

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
        ];

        foreach ($tables as $tableName) {
            // Проверяем, существует ли колонка
            $result = $DB->Query("
            SELECT column_name 
            FROM information_schema.columns 
            WHERE table_name = '" . $DB->ForSql($tableName) . "' 
            AND column_name = 'organization_id'
        ");

            if (!$result->Fetch()) {
                // Если колонки нет, добавляем её
                $DB->Query("
                ALTER TABLE " . $DB->ForSql($tableName) . " 
                ADD COLUMN organization_id INT DEFAULT 1
            ");
            } else {
                // Если колонка уже существует, заполняем её значением 1
                $DB->Query("
                UPDATE " . $DB->ForSql($tableName) . " 
                SET organization_id = 1 
                WHERE organization_id IS NULL
            ");
            }
        }
    }

    public function down()
    {
        //your code ...
    }
}
