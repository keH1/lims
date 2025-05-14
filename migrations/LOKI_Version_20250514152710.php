<?php

namespace Sprint\Migration;


use Bitrix\Main\UserTable;

class LOKI_Version_20250514152710 extends Version
{
    protected $author = "r.sharipov";

    protected $description = "Установка организации для админа";

    protected $moduleVersion = "5.0.0";

    public function up()
    {
        $arUser = UserTable::getRow(['filter' => ['=LOGIN' => "admin"],'select'=>['ID']]);
        if ($arUser) {
            $user = new \CUser;
            $user->Update($arUser['ID'], ['UF_ORG_ID' => 1]);
        }
    }

    public function down()
    {
        //your code ...
    }
}
