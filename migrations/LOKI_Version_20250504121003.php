<?php

namespace Sprint\Migration;


class LOKI_Version_20250504121003 extends Version
{
    protected $author = "roman";

    protected $description = "Создание пользовательского поля Компании Должность руководителя в родительном падеже";

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
  'FIELD_NAME' => 'UF_CRM_1746347570098',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => NULL,
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'E',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Должность руководителя в родительном падеже',
    'ru' => 'Должность руководителя в родительном падеже',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Должность руководителя в родительном падеже',
    'ru' => 'Должность руководителя в родительном падеже',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Должность руководителя в родительном падеже',
    'ru' => 'Должность руководителя в родительном падеже',
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
