<?php

namespace Sprint\Migration;


class LOKI_Version_20250429085444 extends Version
{
    protected $author = "admin";

    protected $description = "Добавляет поле number в таблицу ulab_graduation";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        $result = $DB->Query("
            SELECT column_name 
                FROM information_schema.columns 
            WHERE table_name = 'ulab_graduation' 
                AND column_name = 'number'
        ");

        if (!$result->Fetch()) {
            // Добавляем колонку
            $DB->Query("
                ALTER TABLE ulab_graduation
                  ADD COLUMN `number` INT NULL COMMENT 'Номер записи в рамках организации' AFTER `id`
            ");

            // Проставляем номера
            $DB->Query("SET @current_org := NULL, @seq := 0");
            $DB->Query("
                UPDATE ulab_graduation AS ug
                JOIN (
                  SELECT
                    id,
                    organization_id,
                    (@seq := IF(@current_org = organization_id, @seq + 1, 1)) AS new_number,
                    (@current_org := organization_id) AS dummy
                  FROM ulab_graduation
                  ORDER BY organization_id, id
                ) AS seqs
                  ON ug.id = seqs.id
                SET ug.`number` = seqs.new_number
            ");

            // Делаем NOT NULL и добавляем индекс
            $DB->Query("
                ALTER TABLE ulab_graduation
                  MODIFY COLUMN `number` INT NOT NULL,
                  ADD UNIQUE KEY ux_org_number (organization_id, `number`)
            ");
        }
    }

    public function down()
    {
        //your code ...
    }
}
