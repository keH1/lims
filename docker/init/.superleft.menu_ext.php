<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$aMenuLinks = array(
    array(
        "Профиль организации", 
        "/ulab/import/organization/1", 
        array(), 
        array(
            "menu_item_id" => "menu_organization",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Профиль департамента", 
        "/ulab/import/branch/1", 
        array(), 
        array(
            "menu_item_id" => "menu_branch",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Профиль отдела", 
        "/ulab/import/dep/1", 
        array(), 
        array(
            "menu_item_id" => "menu_dep",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Профиль лаборатории", 
        "/ulab/import/labProfile/4", 
        array(), 
        array(
            "menu_item_id" => "menu_labProfile",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Новая заявка", 
        "/ulab/request/new/", 
        array(), 
        array(
            "menu_item_id" => "menu_request_new",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал заявок", 
        "/ulab/request/list", 
        array(), 
        array(
            "menu_item_id" => "menu_request_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал договоров", 
        "/ulab/order/list", 
        array(), 
        array(
            "menu_item_id" => "menu_order_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал листов измерения", 
        "/ulab/nk/graduationList/", 
        array(), 
        array(
            "menu_item_id" => "menu_graduationList",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал протоколов", 
        "/ulab/protocol/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_protocol_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал приемки проб", 
        "/ulab/probe/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_probe_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал нормативной документации", 
        "/ulab/normDocGost/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_normDocGost_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал области аккредитации", 
        "/ulab/gost/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_gost_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Отчет по области аккредитации", 
        "/ulab/gost/report", 
        array(), 
        array(
            "menu_item_id" => "menu_gost_report",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал оборудования", 
        "/ulab/oborud/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_oborud_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал помещений", 
        "/ulab/import/rooms/4", 
        array(), 
        array(
            "menu_item_id" => "menu_rooms",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал материалов", 
        "/ulab/material/list", 
        array(), 
        array(
            "menu_item_id" => "menu_material_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал объектов строительства", 
        "/objects.php", 
        array(), 
        array(
            "menu_item_id" => "menu_objects",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал автотранспорта", 
        "/ulab/transport/list", 
        array(), 
        array(
            "menu_item_id" => "menu_transport_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал условий", 
        "/ulab/lab/conditionList/", 
        array(), 
        array(
            "menu_item_id" => "menu_condition_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал сверки", 
        "/ulab/order/reviseList", 
        array(), 
        array(
            "menu_item_id" => "menu_revise_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал учета реактивов", 
        "/ulab/reactive/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_reactive_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал приготовления растворов и реактивов", 
        "/ulab/solution/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_solution_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал метролога", 
        "/metrolog.php", 
        array(), 
        array(
            "menu_item_id" => "menu_metrolog",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал стандартных образцов", 
        "/ulab/oborud/sampleList/", 
        array(), 
        array(
            "menu_item_id" => "menu_sample_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал контроля частоты и напряжения электрической сети", 
        "/ulab/electric/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_electric_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал рецептов", 
        "/ulab/recipe/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_recipe_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал расхода реактивов",
        "/ulab/reactiveconsumption/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_reactiveconsumption_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал учета работ по очистки и дезинфекции кондиционеров", 
        "/ulab/disinfectionConditioners/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_disinfectionConditioners_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал учета прекурсоров", 
        "/ulab/precursor/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_precursor_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал контроля дистиллированной воды", 
        "/ulab/water/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_water_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Справочник сит зерновых составов", 
        "/ulab/grain/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_grain_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Стандарт-титры", 
        "/ulab/standarttitr/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_standarttitr_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал пожарной безопасности", 
        "/ulab/fireSafety/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_fireSafety_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал охраны труда", 
        "/ulab/safetyTraining/list", 
        array(), 
        array(
            "menu_item_id" => "menu_safetyTraining_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал счетов", 
        "/ulab/invoice/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_invoice_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал расчета трудозатрат", 
        "/ulab/gost/normtime/", 
        array(), 
        array(
            "menu_item_id" => "menu_gost_normtime",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал командировок", 
        "/ulab/secondment/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_secondment_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Сотрудники", 
        "/ulab/user/list/", 
        array(), 
        array(
            "menu_item_id" => "menu_user_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Матрица компетентности", 
        "/ulab/gost/matrix", 
        array(), 
        array(
            "menu_item_id" => "menu_gost_matrix",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал истории изменений", 
        "/history.php", 
        array(), 
        array(
            "menu_item_id" => "menu_history",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Конструктор статистики", 
        "/ulab/statistic/reportConstructor", 
        array(), 
        array(
            "menu_item_id" => "menu_statistic_reportConstructor",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Отчеты руководителей", 
        "/ulab/statistic/headerReport", 
        array(), 
        array(
            "menu_item_id" => "menu_statistic_headerReport",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал единиц измерений", 
        "/ulab/reference/unitList", 
        array(), 
        array(
            "menu_item_id" => "menu_reference_unitList",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал определяемых характеристик", 
        "/ulab/reference/measuredPropertiesList", 
        array(), 
        array(
            "menu_item_id" => "menu_reference_measuredPropertiesList",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал перемещения оборудования", 
        "/ulab/oborud/movingJournal", 
        array(), 
        array(
            "menu_item_id" => "menu_oborud_movingJournal",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал юстировки весов",
        "/ulab/scale/list",
        array(), 
        array(
            "menu_item_id" => "menu_scale_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
    array(
        "Журнал контроля качества регенерации активированного угля", 
        "/ulab/coal/list", 
        array(), 
        array(
            "menu_item_id" => "menu_coal_list",
            "counter_id" => "",
            "color" => ""
        ),
        ""
    ),
);