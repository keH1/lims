<?php

namespace Sprint\Migration;


class LOKI_Version_20250514152540 extends Version
{
    protected $author = "r.sharipov";

    protected $description = "Миграция Deparments";

    protected $moduleVersion = "5.0.0";

    /**
     * @return bool|void
     * @throws Exceptions\HelperException
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Iblock()->saveIblockType([
            'ID' => 'structure',
            'SECTIONS' => 'Y',
            'EDIT_FILE_BEFORE' => null,
            'EDIT_FILE_AFTER' => null,
            'IN_RSS' => 'N',
            'SORT' => '20',
            'LANG' =>
                [
                    'ru' =>
                        [
                            'NAME' => 'Оргструктура',
                            'SECTION_NAME' => 'Разделы',
                            'ELEMENT_NAME' => 'Элементы',
                        ],
                    'en' =>
                        [
                            'NAME' => 'Company structure',
                            'SECTION_NAME' => 'Sections',
                            'ELEMENT_NAME' => 'Elements',
                        ],
                ],
        ]);
        $iblockId = $helper->Iblock()->saveIblock([
            'IBLOCK_TYPE_ID' => 'structure',
            'LID' =>
                [
                    0 => 's1',
                ],
            'CODE' => 'departments',
            'API_CODE' => 'Departments',
            'NAME' => 'Подразделения',
            'ACTIVE' => 'Y',
            'SORT' => '500',
            'LIST_PAGE_URL' => '#SITE_DIR#company/structure.php',
            'DETAIL_PAGE_URL' => '',
            'SECTION_PAGE_URL' => '#SITE_DIR#company/structure.php?set_filter_structure=Y&structure_UF_DEPARTMENT=#ID#',
            'CANONICAL_PAGE_URL' => '',
            'PICTURE' => null,
            'DESCRIPTION' => '',
            'DESCRIPTION_TYPE' => 'text',
            'RSS_TTL' => '24',
            'RSS_ACTIVE' => 'Y',
            'RSS_FILE_ACTIVE' => 'N',
            'RSS_FILE_LIMIT' => null,
            'RSS_FILE_DAYS' => null,
            'RSS_YANDEX_ACTIVE' => 'N',
            'XML_ID' => 'departments',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'Y',
            'WORKFLOW' => 'N',
            'BIZPROC' => 'N',
            'SECTION_CHOOSER' => 'L',
            'LIST_MODE' => '',
            'RIGHTS_MODE' => 'S',
            'SECTION_PROPERTY' => 'N',
            'PROPERTY_INDEX' => 'N',
            'VERSION' => '1',
            'LAST_CONV_ELEMENT' => '0',
            'SOCNET_GROUP_ID' => null,
            'EDIT_FILE_BEFORE' => '',
            'EDIT_FILE_AFTER' => '',
            'SECTIONS_NAME' => 'Подразделения',
            'SECTION_NAME' => 'Подразделение',
            'ELEMENTS_NAME' => 'Элементы',
            'ELEMENT_NAME' => 'Элемент',
            'REST_ON' => 'N',
            'FULLTEXT_INDEX' => 'N',
            'EXTERNAL_ID' => 'departments',
            'LANG_DIR' => '/',
            'IPROPERTY_TEMPLATES' =>
                [
                ],
            'ELEMENT_ADD' => 'Добавить элемент',
            'ELEMENT_EDIT' => 'Изменить элемент',
            'ELEMENT_DELETE' => 'Удалить элемент',
            'SECTION_ADD' => 'Добавить подразделение',
            'SECTION_EDIT' => 'Изменить подразделение',
            'SECTION_DELETE' => 'Удалить подразделение',
        ]);
    }

    public function down()
    {
        //your code ...
    }
}
