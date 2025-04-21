<?php

namespace Sprint\Migration;


class Version20250410141145 extends Version
{
    protected $author = "k.shagalin";

    protected $description = "UF_ORG_ID в сущности пользователя";

    protected $moduleVersion = "5.0.0";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'USER',
  'FIELD_NAME' => 'UF_ORG_ID',
  'USER_TYPE_ID' => 'integer',
  'XML_ID' => '',
  'SORT' => '1',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'MIN_VALUE' => 0,
    'MAX_VALUE' => 0,
    'DEFAULT_VALUE' => NULL,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'ID Организации из таблицы ulab_organizations',
    'ru' => 'ID Организации из таблицы ulab_organizations',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'ID Организации из таблицы ulab_organizations',
    'ru' => 'ID Организации из таблицы ulab_organizations',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'ID Организации из таблицы ulab_organizations',
    'ru' => 'ID Организации из таблицы ulab_organizations',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
    }

}
