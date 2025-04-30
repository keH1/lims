<?php

namespace Sprint\Migration;


class LOKI_Version_20250429170725 extends Version
{
    protected $author = "r.sharipov";

    protected $description = "добавление organization_id в таблицы";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        // Список таблиц, где нужно добавить поле
        $tables = [
            'reactive_remain',
            'scale_calibration',
        ];

        foreach ($tables as $tableName) {
            // Проверяем, существует ли колонка
            $result = $DB->Query("SELECT column_name FROM information_schema.columns 
                WHERE table_name = '" . $DB->ForSql($tableName) . "' AND column_name = 'organization_id'");

            if (!$result->Fetch()) {
                // Если колонки нет, добавляем её
                $DB->Query("ALTER TABLE " . $DB->ForSql($tableName) . " 
                    ADD COLUMN organization_id INT DEFAULT 1 COMMENT 'ИД организации'");
            } else {
                // Если колонка уже существует, заполняем её значением 1
                $DB->Query("UPDATE " . $DB->ForSql($tableName) . " 
                    SET organization_id = 1 WHERE organization_id IS NULL");
            }
        }
    }

    public function down()
    {
        //your code ...
    }
}
