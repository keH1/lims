<?php

namespace Sprint\Migration;


class LOKI_Version_20250428141834 extends Version
{
    protected $author = "roman";

    protected $description = "Привязка сделки к организации";

    protected $moduleVersion = "5.0.0";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'CRM_DEAL',
  'FIELD_NAME' => 'UF_CRM_1745839051',
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
    'en' => 'Привязка сделки к организации',
    'ru' => 'Привязка сделки к организации',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Привязка сделки к организации',
    'ru' => 'Привязка сделки к организации',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Привязка сделки к организации',
    'ru' => 'Привязка сделки к организации',
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
