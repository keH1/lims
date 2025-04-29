<?php

namespace Sprint\Migration;


class LOKI_Version_20250428121703 extends Version
{
    protected $author = "roman";

    protected $description = "Добавление поля для привязки компаний к организации";

    protected $moduleVersion = "5.0.0";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'CRM_COMPANY',
  'FIELD_NAME' => 'UF_CRM_1745830382',
  'USER_TYPE_ID' => 'double',
  'XML_ID' => NULL,
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'E',
  'SHOW_IN_LIST' => 'N',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'PRECISION' => 2,
    'SIZE' => 20,
    'MIN_VALUE' => 0.0,
    'MAX_VALUE' => 0.0,
    'DEFAULT_VALUE' => NULL,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Поле для привязки к организации',
    'ru' => 'Поле для привязки к организации',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Поле для привязки к организации',
    'ru' => 'Поле для привязки к организации',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Поле для привязки к организации',
    'ru' => 'Поле для привязки к организации',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => NULL,
    'ru' => NULL,
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => NULL,
    'ru' => NULL,
  ),
));
    }

}
