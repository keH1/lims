<?php

namespace Sprint\Migration;


class LOKI_Version_20250513084835 extends Version
{
    protected $author = "a.koval";

    protected $description = "Обновление полей is_method_match и is_oborud_match в таблице ulab_conditions.";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        global $DB;
        $helper = $this->getHelperManager();

        // Обновляем существующие NULL значения
        $DB->Query("
            UPDATE ulab_conditions 
            SET is_method_match = 0 
            WHERE is_method_match IS NULL
        ");

        $DB->Query("
            UPDATE ulab_conditions 
            SET is_oborud_match = 0 
            WHERE is_oborud_match IS NULL
        ");

        // Модифицируем столбцы
        $DB->Query("
            ALTER TABLE ulab_conditions 
            MODIFY is_method_match TINYINT(1) NOT NULL DEFAULT 0 
            COMMENT 'Соответствие методикам: 0-нет, 1-да'
        ");

        $DB->Query("
            ALTER TABLE ulab_conditions 
            MODIFY is_oborud_match TINYINT(1) NOT NULL DEFAULT 0 
            COMMENT 'Соответствие оборудованию: 0-нет, 1-да'
        ");
    }

    public function down()
    {
        global $DB;

        // Возвращаем NULL и DEFAULT NULL
        $DB->Query("
            ALTER TABLE ulab_conditions 
            MODIFY is_method_match TINYINT(1) NULL DEFAULT NULL
        ");

        $DB->Query("
            ALTER TABLE ulab_conditions 
            MODIFY is_oborud_match TINYINT(1) NULL DEFAULT NULL
        ");
    }
}
