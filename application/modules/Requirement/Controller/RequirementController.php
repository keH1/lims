<?php

/**
 * @desc Класс контроллер для Техзадания
 * Class RequirementController
 */
class RequirementController extends Controller
{
    const ALLOWED_MATERIAL_ID = [1, 2, 6, 7, 8, 11, 15, 94, 95, 101];
    const SAMPLES_ACCEPTANCE_ACT = 0;
    const ALLOWED_EDIT_BY_USERS = [1, 86, 7, 9, 17, 100, 11, 35, 33, 13, 15, 43, 69, 53, 115, 36, 56, 58, 62, 16, 61, 83, 68, 59, 45, 60, 82, 92, 28, 12, 67, 101, 105]; //TODO: Исправиль костыль прав
    const FORBIDDEN_CREATE_NEW_GOST = [56];
    const LABORATORY_HEAD = [11, 13, 15, 58, 43];

    /**
     * @desc Перенаправляет пользователя на страницу «Журнал заявок»
     */
    public function index()
    {
        $this->redirect('/request/list/');
    }

    /**
     * для связки со старыми заявками
     * route /requirement/card/{$tzId}
     * @param $tzId
     * @desc Страница технического задания [deprecated]
     */
    public function card_old($tzId)
    {
        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }

        $this->data['title'] = 'Формирование технического задания';


        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Material $material */
        $material = $this->model('Material');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Gost $gost */
        $gost = $this->model('Gost');
        /** @var User $user */
        $user = $this->model('User');


        $tz = $requirement->getTzByTzId((int)$tzId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД {$tzId} не существует");
            $this->redirect('/request/list/');
        }

        $deal = $request->getDealById($tz['ID_Z']);

        if ( empty($deal) ) {
            $this->showErrorMessage("Заявки с ИД {$tz['ID_Z']} не существует");
            $this->redirect('/request/list/');
        }


        $this->data['tz'] = $tz;
        $materialData = $requirement->getMaterialProbeGostToRequest($tz['ID_Z']);
        $this->data['test'] = $materialData;
        $this->data['assign_method'] = $gost->getAssignedByGostList($materialData['methods']);
        $this->data['gosts_group'] = $materialData['gosts_group'];
        $this->data['price'] = $materialData['price'];
        $gostList = $gost->getListAndPricesByDealId($tz['ID_Z']);
        $this->data['select_gosts'] = $gostList;
        $this->data['select_tu_gosts'] = $gost->getListAndPricesByDealId($tz['ID_Z'], true);

        $this->data['contract'] = $requirement->getContractByDealId($tz['ID_Z']);
        $this->data['objects'] = $requirement->getObjects();
        $this->data['quarry'] = $requirement->getQuarry();
        $this->data['select_materials'] = $material->getList();
        $actBase = $requirement->getActBase($tz['ID_Z']);
        $this->data['requests_to_company'] = $requirement->getRequestsToCompany($deal['ID'], $deal['COMPANY_ID']);
        $this->data['taken_request'] = $requirement->getTakenRequest($tz['TAKEN_ID_DEAL']);
        $currentUserId = $user->getCurrentUserId();

        $this->data['is_block_delete_material'] = !empty($tz['RESULTS']);

        // Не соответствие методики и руководителей
        $this->data['unconfirmed_tz'] = $requirement->getUnconfirmedTz((int)$tzId);
        $this->data['confirmed_tz'] = $requirement->getConfirmedTz((int)$tzId);
        $this->data['is_confirm'] = $requirement->isConfirm((int)$tzId);
        $this->data['is_confirmTz'] = $requirement->isConfirmTz((int)$tzId);

        $this->data['lab_leaders_tz'] = $requirement->getLabLeadersTZ((int)$tzId);
        $this->data['check_tz'] = $requirement->getCheckTzByIdTz((int)$tzId);
        $this->data['assigned'] = $user->getAssignedByDealId((int)$tz['ID_Z']);
        $this->data['laboratory_head'] = self::LABORATORY_HEAD;
        $this->data['assigned_id'] = array_merge(array_column($this->data['assigned'], 'user_id'), ['1', '62', '17']); //1, 62 TODO: исправить харкод


        $materialsId = array_column($materialData['material'], 'id');
        $requiredSelectQuarry = '';

        if (!empty($materialsId) && array_intersect($materialsId, self::ALLOWED_MATERIAL_ID)) {
            $requiredSelectQuarry = 'required';
        }

        $this->data['required_quarry'] = $requiredSelectQuarry;


        $this->data['contract_number'] = $this->data['contract']['NUMBER'] ?? '';
        $this->data['contract_date'] = $this->data['contract']['DATE'] ?? '';
        $this->data['contract_type'] = $this->data['contract']['CONTRACT_TYPE'] ?? 'Договор';
        $this->data['deal_title'] = $tz['REQUEST_TITLE'];
        $this->data['name_file_create'] = $this->data['tz']['CONTROL_LIST'] ? 'control_list' : 'probe';
        $this->data['title_file_create'] = $this->data['tz']['CONTROL_LIST'] ? 'Создать контрольный лист' : 'Создать акт приемки проб';
        $this->data['act_number'] = ! empty($actBase['ACT_NUM']) ? $actBase['ACT_NUM'] : '';
        $this->data['act_date'] = ! empty($actBase['ACT_DATE']) && $actBase['ACT_DATE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actBase['ACT_DATE'])) : '';
        $this->data['probe_place'] = ! empty($actBase['PLACE_PROBE']) ? $actBase['PLACE_PROBE'] : 'Пробы не поступили';
        $this->data['probe_made'] = ! empty($actBase['PROBE_PROIZV']) ? $actBase['PROBE_PROIZV'] : 'Пробы не поступили';
        $this->data['probe_date'] = ! empty($actBase['DATE_PROBE']) && $actBase['DATE_PROBE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actBase['DATE_PROBE'])) :
            'Пробы не поступили';
        $this->data['type_id'] = $tz['TYPE_ID'];
        $this->data['sum_price'] = $tz['PRICE'] ?? 0;
        $this->data['save'] = in_array($currentUserId, self::ALLOWED_EDIT_BY_USERS);
        //TODO: Временный костыль, блокировка редактирования ТЗ после внесения данных в результаты испытаний
        $this->data['is_may_change'] = true;//$deal['ID'] < 8846 || empty($requirement->getCountFilledResultData($deal['ID'])["count_umtr"]);


        if (isset($_SESSION['requirement_post'])) {
            $this->data['requirement'] = $_SESSION['requirement_post'];

            unset($_SESSION['requirement_post']);
        } else {
            $this->data['requirement'] = [];

            $this->data['requirement']['material'] = $materialData['material'];
            $this->data['requirement']['amount'] = $materialData['amount'];
            $this->data['requirement']['methods'] = $materialData['methods'];
            $this->data['requirement']['conditions'] = $materialData['conditions'];
            $this->data['requirement']['assign_method'] = $materialData['assigned'];
            $this->data['requirement']['price'] = $materialData['price'];
            $this->data['requirement']['tz_id'] = $tzId;
            $this->data['requirement']['deal_id'] = $tz['ID_Z'];
            $this->data['requirement']['DESCRIPTION'] = $tz['DESCRIPTION'];
            $this->data['requirement']['OBJECT'] = $tz['OBJECT'];
            $this->data['requirement']['QUARRY_ID'] = $tz['QUARRY_ID'];
            $this->data['requirement']['DEADLINE'] = $tz['DEADLINE'] ?? date('Y-m-d');
            $this->data['requirement']['DAY_TO_TEST'] = $tz['DAY_TO_TEST'];
            $this->data['requirement']['type_of_day'] = $tz['type_of_day'];
            $this->data['requirement']['COMMENT_KP'] = $tz['COMMENT_KP'];
            $this->data['requirement']['COMMENT_TZ'] = $tz['COMMENT_TZ'];
            $this->data['requirement']['DISCOUNT'] = !empty($tz['DISCOUNT']) ? $tz['DISCOUNT'] : '';
            $this->data['requirement']['taken_certificate'] = $tz['TAKEN_SERT_ISP'];
        }

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/assets/js/requirement.js?v=' . rand());

        $this->view('form-old');
    }


    /**
     * route /requirement/card/{$tzId}
     * @param $tzId
     * @desc Страница технического задания [deprecated]
     */
    public function card($tzId)
    {
        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }

        $this->data['title'] = 'Формирование технического задания';


        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Material $material */
        $material = $this->model('Material');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Gost $gost */
        $gost = $this->model('Gost');
        /** @var User $user */
        $user = $this->model('User');
        /** @var TechCondition $tcModel */
        $tcModel = $this->model('TechCondition');


        $tz = $requirement->getTzByTzId((int)$tzId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД {$tzId} не существует");
            $this->redirect('/request/list/');
        }

        $deal = $request->getDealById($tz['ID_Z']);

        if ( empty($deal) ) {
            $this->showErrorMessage("Заявки с ИД {$tz['ID_Z']} не существует");
            $this->redirect('/request/list/');
        }


        if ( $tz['ID_Z'] > DEAL_NEW_TZ && $deal['TYPE_ID'] != 7 && $tz['ID_Z'] != 9415 ) {
            $this->redirect("/requirement/card_new/{$tzId}");
        }


        $this->data['tz'] = $tz;
        $materialData = $requirement->getUlabMaterialProbeGostToRequest($tz['ID_Z']);
        $this->data['test'] = $materialData;
        $this->data['assign_method'] = $gost->getUlabAssignedByGostList($materialData['methods']);
        $this->data['gosts_group'] = $materialData['gosts_group'];
        $this->data['price'] = $materialData['price'];

        $this->data['select_gosts'] = $gost->getUlabGostAndPrice();
        $this->data['select_tu_gosts'] = $tcModel->getList();

        $this->data['contract'] = $requirement->getContractByDealId($tz['ID_Z']);
        $this->data['objects'] = $requirement->getObjects();
        $this->data['quarry'] = $requirement->getQuarry();
        $this->data['select_materials'] = $material->getList();
        $actBase = $requirement->getActBase($tz['ID_Z']);
        $this->data['requests_to_company'] = $requirement->getRequestsToCompany($deal['ID'], $deal['COMPANY_ID']);
        $this->data['taken_request'] = $requirement->getTakenRequest($tz['TAKEN_ID_DEAL']);
        $currentUserId = $user->getCurrentUserId();

        $this->data['is_block_delete_material'] = !empty($tz['RESULTS']);

        // Не соответствие методики и руководителей
        $this->data['unconfirmed_tz'] = $requirement->getUnconfirmedTz((int)$tzId);
        $this->data['confirmed_tz'] = $requirement->getConfirmedTz((int)$tzId);
        $this->data['is_confirm'] = $requirement->isConfirm((int)$tzId);
        $this->data['is_confirmTz'] = $requirement->isConfirmTz((int)$tzId);

        $this->data['lab_leaders_tz'] = $requirement->getLabLeadersTZ((int)$tzId);
        $this->data['check_tz'] = $requirement->getCheckTzByIdTz((int)$tzId);
        $this->data['assigned'] = $user->getAssignedByDealId((int)$tz['ID_Z']);
        $this->data['laboratory_head'] = self::LABORATORY_HEAD;
        $this->data['assigned_id'] = array_merge(array_column($this->data['assigned'], 'user_id'), ['1', '62', '17']); //1, 62 TODO: исправить харкод


        $materialsId = array_column($materialData['material'], 'id');
        $requiredSelectQuarry = '';

        if (!empty($materialsId) && array_intersect($materialsId, self::ALLOWED_MATERIAL_ID)) {
            $requiredSelectQuarry = 'required';
        }

        $this->data['required_quarry'] = $requiredSelectQuarry;


        $this->data['contract_number'] = $this->data['contract']['NUMBER'] ?? '';
        $this->data['contract_date'] = $this->data['contract']['DATE'] ?? '';
        $this->data['contract_type'] = $this->data['contract']['CONTRACT_TYPE'] ?? 'Договор';
        $this->data['deal_title'] = $tz['REQUEST_TITLE'];
        $this->data['name_file_create'] = $this->data['tz']['CONTROL_LIST'] ? 'control_list' : 'probe';
        $this->data['title_file_create'] = $this->data['tz']['CONTROL_LIST'] ? 'Создать контрольный лист' : 'Создать акт приемки проб';
        $this->data['act_number'] = ! empty($actBase['ACT_NUM']) ? $actBase['ACT_NUM'] : '';
        $this->data['act_date'] = ! empty($actBase['ACT_DATE']) && $actBase['ACT_DATE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actBase['ACT_DATE'])) : '';
        $this->data['probe_place'] = ! empty($actBase['PLACE_PROBE']) ? $actBase['PLACE_PROBE'] : 'Пробы не поступили';
        $this->data['probe_made'] = ! empty($actBase['PROBE_PROIZV']) ? $actBase['PROBE_PROIZV'] : 'Пробы не поступили';
        $this->data['probe_date'] = ! empty($actBase['DATE_PROBE']) && $actBase['DATE_PROBE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actBase['DATE_PROBE'])) :
            'Пробы не поступили';
        $this->data['type_id'] = $tz['TYPE_ID'];
        $this->data['sum_price'] = $tz['PRICE'] ?? 0;
        $this->data['save'] = in_array($currentUserId, self::ALLOWED_EDIT_BY_USERS);
        //TODO: Временный костыль, блокировка редактирования ТЗ после внесения данных в результаты испытаний
        $this->data['is_may_change'] = true;//$deal['ID'] < 8846 || empty($requirement->getCountFilledResultData($deal['ID'])["count_umtr"]);


        if (isset($_SESSION['requirement_post'])) {
            $this->data['requirement'] = $_SESSION['requirement_post'];

            unset($_SESSION['requirement_post']);
        } else {
            $this->data['requirement'] = [];

            $this->data['requirement']['material'] = $materialData['material'];
            $this->data['requirement']['amount'] = $materialData['amount'];
            $this->data['requirement']['methods'] = $materialData['methods'];
            $this->data['requirement']['conditions'] = $materialData['conditions'];
            $this->data['requirement']['assign_method'] = $materialData['assigned'];
            $this->data['requirement']['price'] = $materialData['price'];
            $this->data['requirement']['tz_id'] = $tzId;
            $this->data['requirement']['deal_id'] = $tz['ID_Z'];
            $this->data['requirement']['DESCRIPTION'] = $tz['DESCRIPTION'];
            $this->data['requirement']['OBJECT'] = $tz['OBJECT'];
            $this->data['requirement']['QUARRY_ID'] = $tz['QUARRY_ID'];
            $this->data['requirement']['DEADLINE'] = $tz['DEADLINE'] ?? date('Y-m-d');
            $this->data['requirement']['DAY_TO_TEST'] = $tz['DAY_TO_TEST'];
            $this->data['requirement']['type_of_day'] = $tz['type_of_day'];
            $this->data['requirement']['COMMENT_KP'] = $tz['COMMENT_KP'];
            $this->data['requirement']['COMMENT_TZ'] = $tz['COMMENT_TZ'];
            $this->data['requirement']['DISCOUNT'] = !empty($tz['DISCOUNT']) ? $tz['DISCOUNT'] : '';
            $this->data['requirement']['taken_certificate'] = $tz['TAKEN_SERT_ISP'];
        }

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/assets/js/requirement.js?v=' . rand());

        $this->view('form');
    }



    /**
     * route /requirement/card_new/{$tzId}
     * @param $tzId
     * @desc Страница технического задания
     */
    public function card_new($tzId)
    {
        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }

        $this->data['title'] = 'Формирование технического задания';

        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var TechCondition $tcModel */
        $tcModel = $this->model('TechCondition');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        //// получение данных
        $tzData = $requirementModel->getTzByTzId((int)$tzId);

        $dealId = (int) $tzData['ID_Z'];

        $this->data['scheme_id'] = $tzData['scheme_id'];

        if (empty($tzData)) {
            $this->showErrorMessage("Технического задания с ИД {$tzId} не существует");
            $this->redirect('/request/list/');
        }

        $dealData = $request->getDealById($dealId);

        if ( empty($dealData) ) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        $contractData = $requirementModel->getContractByDealId($dealId);
        $actData = $requirementModel->getActBase($dealId);

        //// заполнение селектов
        // Объект строительства
        $this->data['objects'] = $requirementModel->getObjects();
        // Методики
        $this->data['method_list'] = $methodsModel->getList();
        // ТУ
        $this->data['condition_list'] = $tcModel->getList();
        // Материалы (объект испытаний)
        $this->data['material_list'] = $materialModel->getList();
        // Заявка учтена
        $this->data['requests_to_company'] = $requirementModel->getRequestsToCompany($dealId, $dealData['COMPANY_ID']);

        // Работы
        if ($tzData['TYPE_ID'] == '9') {
            $this->data['work_list'] = $requirementModel->getWorksMaterialRequest($dealId);
        } else {
            $this->data['comm'] = '?type_request=commercial';
        }

        //// общая информация
        // Основание для проведения испытаний (договор)
        $this->data['contract_number'] = $contractData['NUMBER'] ?? '';
        $this->data['contract_date'] = $contractData['DATE'] ?? '';
        $this->data['contract_type'] = $contractData['CONTRACT_TYPE'] ?? 'Договор';
        $this->data['deal_id'] = $dealId;
        $this->data['tz_id'] = $tzId;
        $this->data['curr_user'] = App::getUserId();
        $this->data['deal_title'] = $tzData['REQUEST_TITLE'];

        // Основание для формирования протокола (акт приемки проб)
        $this->data['act_number'] = $actData['ACT_NUM'] ?? '';
        $this->data['act_date'] = ! empty($actData['ACT_DATE']) && $actData['ACT_DATE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actData['ACT_DATE'])) : '';

        // Описание объекта
        $this->data['tz'] = $tzData;

        //// доп информация
        $this->data['probe_place'] = ! empty($actData['PLACE_PROBE']) ? $actData['PLACE_PROBE'] : 'Пробы не поступили';
        $this->data['probe_made'] = ! empty($actData['PROBE_PROIZV']) ? $actData['PROBE_PROIZV'] : 'Пробы не поступили';
        $this->data['probe_date'] = ! empty($actData['DATE_PROBE']) && $actData['DATE_PROBE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actData['DATE_PROBE'])) :
            'Пробы не поступили';


        //// материалы
        $this->data['material_probe_list'] = $materialModel->getMaterialProbeToRequest($dealId);
        $this->data['tz_material_list'] = array_column($this->data['material_probe_list'], 'material_name', 'material_id');
        $this->data['lab_head'] = $requirementModel->getLabHead($dealId);

        // статус проверенности ТЗ
        $this->data['check_state'] = $requirementModel->getStateConfirm($dealId);

        if ( !isset($this->data['check_state']) || $this->data['check_state'] == CHECK_TZ_NOT_SENT ) {
//            $this->showWarningMessage("Техническое задание еще не передано для проверки руководителям лабораторий");
        } elseif ( $this->data['check_state'] == CHECK_TZ_NOT_APPROVE ) {
            $desc = $requirementModel->getDescConfirmTzNotApprove($tzId);
            $msg = '';
            foreach ($desc as $row) {
                $msg .= "<br><b>{$row['date_return']} {$row['user_name']}</b>: {$row['desc_return']}";
            }
            $this->showErrorMessage("Техническое задание было возвращено после проверки руководителем лаборатории.{$msg}");
        } elseif ( $this->data['check_state'] == CHECK_TZ_WAIT ) {
            $this->showWarningMessage("Техническое задание было передано для проверки руководителям лабораторий");
        } else {
            $this->showSuccessMessage("Техническое задание было одобрено руководителями лабораторий. Изменения сбросят все подтверждения.");
        }

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJS("/assets/plugins/sticksy/sticksy.min.js");

        $this->addJs('/assets/js/requirement_new_new.js?v=' . rand());
        $this->view('form_new_new', '', 'template_journal');
    }


    /**
     * @deprecated
     * @desc Сохраняет или обновляет данные тз [deprecated]
     */
    public function insertUpdate()
    {
        //TODO: Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
        //start
        $arrData = [];
        $probe = [];
        $key_p = 1;
        //end
        $tzId = (int)$_POST['tz_id'];
        $materialDataList = [];
        $methodsId = [];
        $sumPrice = 0;

        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }


        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        $tz = $requirement->getTzByTzId($tzId);

        if ( empty($tz) ) {
            $this->showErrorMessage("Технического задания с ИД {$tzId} не существует");
            $this->redirect('/request/list/');
        }

        $idZ = (int)$tz['ID_Z'];

        $_SESSION['requirement_post'] = $_POST;

        $location = $idZ >= DEAL_START_NEW_AREA? "/requirement/card/{$tzId}" : "/requirement/card_old/{$tzId}";

        $successMsg = 'Техническое задание успешно изменено';


        foreach ($_POST['material'] as $key => $material) {
            $valid = $this->validateField($material['name'], 'Материал', true, '65000');

            if (!$valid['success']) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }

            foreach ($_POST['methods'][$key] as $methods) {
                $valid = $this->validateField($methods['name'], 'Методика');

                if (!$valid['success']) {
                    $this->showErrorMessage($valid['error']);
                    $this->redirect($location);
                }
            }
        }

        foreach ($_POST['amount'] as $item) {
            $valid = $this->validateNumber($item, 'Количество проб/образцов', true);

            if (!$valid['success']) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }
        }


        /** @var Material $material */
        $material = $this->model('Material');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Gost $gost */
        $gost = $this->model('Gost');
        /** @var User $user */
        $user = $this->model('User');


        //TODO: Временное получение данных сделки, для сохранения данных в сериалезованном виде, для работы остальных скриптов до их рефакторинга
        $deal = $request->getDealById($idZ);
        if ( empty($deal) ) {
            $this->showErrorMessage("Заявки с ИД {$idZ} не существует");
            $this->redirect('/request/list/');
        }
        //start
        $actBase = $requirement->getActBaseByDealId($deal['ID']);
        //end

        //TODO: Временный костыль, блокировка редактирования ТЗ после внесения данных в результаты испытаний
        $this->data['is_may_change'] = $deal['ID'] < 8846 || empty($requirement->getCountFilledResultData($deal['ID'])["count_umtr"]);

        $currentUserId = $user->getCurrentUserId();
        $currentUser = $user->getCurrentUser();
        $lastHistory = $requirement->getLastHistory($tzId);


        $historyAssigned = $lastHistory['ASSIGNED'] ?? '';
        $historyDate = date("d.m.Y H:i:s", strtotime($lastHistory['DATE']));
        $historyDateNull = date("d.m.Y H:i:s", strtotime('2019-01-01 05:00:00'));
        if($historyDate < $historyDateNull) $historyDate = '';

        $newAssignedToRequest = [];

        foreach ($_POST['material'] as $key => $item) {

            if (empty($_POST['amount'][$key])) {
                continue;
            }

            if (empty($item['id'])) {
                $resultAddMaterial = $material->add($item['name']);

                if (empty($resultAddMaterial)) {
                    $this->showErrorMessage("Не удалось создать материал '{$item['name']}'");
                    $this->redirect($location);
                } else {
                    $item['id'] = $resultAddMaterial;
                }
            }

            foreach ($_POST['methods'][$key] as $k => $i) {

                if ( !empty($_POST['assign_method'][$key][$k]) ) {
                    $newAssignedToRequest[] = $_POST['assign_method'][$key][$k];
                }

                if (empty($i['id'])) {
                    if (true/*in_array($currentUserId, self::FORBIDDEN_CREATE_NEW_GOST)*/) {
//                        $this->showErrorMessage('Вам закрыт доступ на создание новых ГОСТов!');
                        $this->showErrorMessage('Не существует такого ГОСТа. Выбирайте из списка!');
                        $this->redirect($location);
                    }

                    $price = $_POST['price'][$key][$k] ?? '';
                    $lab = $_POST['labs'][$key][$k] ?? '';

                    $data = [
                        'GOST_TYPE' => 'metodic_otbor',
                        'SPECIFICATION' => $i['name'],
                        'PRICE' => $price,
                        'ED' => '-',
                        'NAME_GOST' => '-',
                        'NORM_COMMENT' => '-',
                        'ACCURACY' => 0,
                        'GOST' => '-',
                        'NORM1' => '-',
                        'NORM2' => '-',
                        'NON_ACTUAL' => 0,
                        'NUM_OA_NEW' => 1
                    ];

                    if (empty($lab)) {
                        $this->showErrorMessage("Не удалось создать гост '{$item['name']}' не указана лаборатория");
                        $this->redirect($location);
                    }

                    $resultAddGost = $gost->add($data);

                    if (empty($resultAddGost)) {
                        $this->showErrorMessage("Не удалось создать гост '{$item['name']}'");
                        $this->redirect($location);
                    } else {
                        $_POST['methods'][$key][$k]['id'] = $resultAddGost;
                    }

                    $dataUpdate = [
                        $lab => 1,
                    ];

                    $gost->update($resultAddGost, $dataUpdate);
                }

                $methodsId[] = $_POST['methods'][$key][$k]['id'];


                //TODO: Временное сохранение данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                //start
                $arrData[$key][$item['name']][$key]['gost'][] = $_POST['methods'][$key][$k]['id'];
                $arrData[$key][$item['name']][$key]['price'][] = $_POST['price'][$key][$k];
                $arrData[$key][$item['name']][$key]['gost_new'][] = $_POST['conditions'][$key][$k]['id'];
                $arrData[$key][$item['name']][$key]['obiem'] = $_POST['amount'][$key];
                //end
            }


            //TODO: Временное сохранение данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
            //start
            $probe[$key_p] = array(
                "zakaz_man" => $deal['COMPANY_ID'],
                "number_probe" => [],
                "probe_proizv" => $actBase['PROBE_PROIZV'],
                "number" => $actBase['ACT_NUM'],
                "dat" => '',
            );
            //TODO: Временно для корресктного вывода проб

            if (isset($actBase['ACT_NUM']) && $actBase['ACT_NUM'] != '0') {
                $requirement->savingShNumbersToActBase($deal['ID'], $actBase['ACT_NUM']);
                $sh = json_decode($actBase['PROBE']);

                $dateProbe = !empty($actBase['DATE_PROBE']) ? date("d.m.Y", strtotime($actBase['DATE_PROBE'])) : '-';
                $placeProbe = !empty($actBase['PLACE_PROBE']) ? $_POST['PLACE_PROBE'] : '-';
                $probe[$key_p]["mesto_data"] = $placeProbe . '; ' . $dateProbe;
            }

            for ($l = 0; $l < (int)$_POST['amount'][$key]; $l++) {

                $probe[$key_p]["number_probe"][$l] = $item['name'];
                if (!empty($actBase['ACT_NUM']) && $actBase['ACT_NUM'] != 0) {
                    $probe[$key_p]["sh_number"][$l] = $sh[$key][$l];
//                    $probe[$key_p]["sh_number"][$l] = $actBase['PROBE'][$key][$l];
                }
            }
            $key_p++;
            //end


            $item['gosts'] = [
                'gost_method' => $_POST['methods'][$key] ?? [],
                'gost_conditions' => $_POST['conditions'][$key] ?? [],
                'price' => $_POST['price'][$key] ?? [],
                'assigned' => $_POST['assign_method'][$key] ?? []
            ];

            $materialDataList[$key] = $item;

            $sumPrice += array_sum($_POST['price'][$key]) * $_POST['amount'][$key];
        }

        $newAssignedToRequest = array_unique($newAssignedToRequest);

        $request->addAssignedToRequest($idZ, $newAssignedToRequest);

        $methodsNotInOA = $requirement->getMethodsNotInOA($methodsId);

        $dataTz = [
            'DESCRIPTION' => $_POST['DESCRIPTION'] ?? '',
            'OBJECT' => $_POST['OBJECT'] ? htmlentities($_POST['OBJECT'], ENT_QUOTES, 'UTF-8') : '',
            'DEADLINE' => $_POST['DEADLINE'] ?? date('Y-m-d'),
            'DEADLINE_TABLE' => !empty($_POST['DEADLINE']) ? date('d.m.Y', strtotime($_POST['DEADLINE'])) : date('Y-m-d'),
            'DAY_TO_TEST' => $_POST['DAY_TO_TEST'] ?? '',
            'type_of_day' => $_POST['type_of_day'] ?? '',
            'COMMENT_KP' => $_POST['COMMENT_KP'] ?? '',
            'COMMENT_TZ' => $_POST['COMMENT_TZ'] ?? '',
            'PRICE' => $sumPrice,
            'ATTESTAT' => $methodsNotInOA ? 0 : 1,
            'CONTROL_LIST' => self::SAMPLES_ACCEPTANCE_ACT,
            'USER_HISTORY' => $historyAssigned . ' ' . $historyDate
        ];

        if (!empty($_POST['QUARRY_ID'])) {
            $dataTz['QUARRY_ID'] = $_POST['QUARRY_ID'];
        }

        if (!empty($_POST['taken_certificate'])) {
            $dataTz['TAKEN_SERT_ISP'] = $_POST['taken_certificate']? 1 : 0;
        }

        if (!empty($_POST['hidden_is_discount'])) {
            $dataTz['DISCOUNT'] = $_POST['DISCOUNT'];
        }

        $requirement->updateTzByIdTz($tzId, $dataTz);


        $historyData = [
            'DATE' => date('Y-m-d H:i:s'),
            'ASSIGNED' => "{$currentUser['NAME']} {$currentUser['LAST_NAME']}",
            'PROT_NUM' => $tz['NUM_P'] ?? 'номер не присвоен',
            'TZ_ID' => $tzId,
            'USER_ID' => $currentUser['ID'],
            'TYPE' => 'Сохранение технического задания',
            'REQUEST' => $tz['REQUEST_TITLE']
        ];

        //TODO: Доделать сохранение INSERT INTO `PODGOTOVKA`

        $materialProbeGost = $requirement->updateMaterialProbeGostToRequest($idZ, $materialDataList, $_POST['amount']);
        $invoice = $requirement->getInvoice((int)$tzId);
        $requirement->saveHistory($historyData);
        $request->updateStageDeal($idZ, 'PREPARATION');

        // собирает шифры для проб в заявке
        $material->fillCipher($idZ);
        //собирает шифры для проб в заявке, для таблицы ulab_material_to_request
        $material->addCipher($idZ);

        if (!empty($materialProbeGost['error'])) {
            $this->showErrorMessage($materialProbeGost['error']);
            $this->redirect($location);
        }



        //TODO: Временный метод, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
        $requirement->savingSerializedData($tzId, $arrData, $probe);


        if (!empty($invoice)) {
            $this->showWarningMessage("Внимание! По заявке уже выставлен счет! Не забудьте переформировать его, если стоимость работ изменилась!");
        }

        if (!empty($materialProbeGost['success'])) {
            unset($_SESSION['requirement_post']);
            $this->showSuccessMessage($successMsg);
            $this->redirect($location);
        }
    }


    /**
     * @desc Обновление формы. новое тз
     */
    public function updateTz()
    {
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        $tzId = (int)$_POST['tz_id'];
        $dealId = (int)$_POST['deal_id'];
        $dataTz = $_POST['tz'];

        $requirementModel->updateTzByIdTz($tzId, $dataTz);

        if (!empty($_POST['tz']['TAKEN_ID_DEAL'])) {
            $orderModel->changeOrderByHeadRequest((int)$_POST['tz']['TAKEN_ID_DEAL'], $dealId);
        }

        $requirementModel->updateMaterial($dealId, $_POST['material_id']);
        $requirementModel->updateProbeMethod($dealId, $_POST['material']);

        $requirementModel->updateAssigned($dealId);

        if ($_POST['clear_confirm']) {
            $requirementModel->confirmTzClear($tzId);
        }


        $this->showSuccessMessage("Техническое задание успешно изменено");
        $this->redirect("/requirement/card_new/{$tzId}");
    }


    /**
     * route /requirement/show/{tzId}
     * @param $tzId
     */
    public function show($tzId)
    {
        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }

        $this->data['title'] = 'Техническое задание';

        $this->view('show');
    }


    /**
     * @desc добавление материала в новом тз
     */
    public function addMaterialToTz()
    {
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->addMaterialToTz((int)$_POST['deal_id'], (int)$_POST['material_id'], (int)$_POST['number']);

        $this->showSuccessMessage("Добавлен объект испытаний");
        $this->redirect("/requirement/card_new/{$_POST['tz_id']}");
    }


    /**
     * @desc добавление материала в новом тз
     */
    public function addProbeToMaterial()
    {
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->addProbeToMaterial((int)$_POST['deal_id'], (array)$_POST['material_id'], (int)$_POST['number']);

        $this->showSuccessMessage("Добавлены пробы");
        $this->redirect("/requirement/card_new/{$_POST['tz_id']}");
    }


    /**
     * @desc добавление методик выбранным пробам в новом тз
     */
    public function addMethodsToProbe()
    {
        if ( empty($_POST['probe_id_list']) ) {
            $this->showErrorMessage("Не выбраны пробы");
            $this->redirect("/requirement/card_new/{$_POST['tz_id']}");
        }

        if ( empty($_POST['form']) ) {
            $this->showErrorMessage("Нет методик для добавления");
            $this->redirect("/requirement/card_new/{$_POST['tz_id']}");
        }

        $dealId = (int)$_POST['deal_id'];
        $probeIdList = explode(',', $_POST['probe_id_list']);

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        // обновляем схему у заявки (позже обновить)
        $requestModel->updateDealScheme($dealId, (int)$_POST['scheme_id']);

        // добавляем методики
        $requirementModel->addMethodsToProbe($probeIdList, $_POST['form']);

        // обновляем цены у заявки
        $requirementModel->updatePrice($dealId);

        $this->showSuccessMessage("Добавлены методики");
        $this->redirect("/requirement/card_new/{$_POST['tz_id']}");
    }


    /**
     * @desc редактирует или удаляет пробу
     */
    public function editProbe()
    {
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        if ( $_POST['button'] === 'save' ) {
            $this->showSuccessMessage("Проба обновлена");

            $requirementModel->updateProbeInfo((int)$_POST['probe_id'], $_POST['form']);
        } else if ( $_POST['button'] === 'delete' ) {
            $result = $requirementModel->deleteProbe((int)$_POST['deal_id'], (int)$_POST['probe_id']);

            if ( $result['success'] ) {
                $this->showSuccessMessage("Проба удалена");
            } else {
                $this->showErrorMessage($result['error']);
            }
        }

        $this->redirect("/requirement/card_new/{$_POST['tz_id']}");
    }


    /**
     * @desc Отправляет ТЗ на проверку
     */
    public function confirmTzSent()
    {
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $tzId = (int)$_POST['tz_id'];
        $dealId = (int)$_POST['deal_id'];

        if ( empty($_POST['users']) ) {
            $this->showErrorMessage("Техническое задание не отправлено на проверку. Не выбраны руководители.");
            $this->redirect("/requirement/card_new/{$tzId}");
        }

        $requirementModel->confirmTzSentUsers($dealId, $_POST['users']);

        $this->showSuccessMessage("Техническое задание отправлено на проверку");
        $this->redirect("/requirement/card_new/{$tzId}");
    }

    /**
     * @desc Отправляет ТЗ на проверку с помощью Ajax-запроса
     */
    public function confirmTzSentAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->confirmTzSent((int)$_POST['deal_id']);
    }

    /**
     * @desc Утверждает ТЗ
     */
    public function confirmTzApproveAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->confirmTzApprove((int)$_POST['tz_id'], App::getUserId());
    }

    /**
     * @desc Отправляет на проверку и утверждает ТЗ
     */
    public function confirmTzSentApproveAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->confirmTzSent((int)$_POST['deal_id']);

        $requirementModel->confirmTzApprove((int)$_POST['tz_id'], App::getUserId());
    }

    /**
     * @desc Не утверждает ТЗ
     */
    public function confirmTzNotApproveAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->confirmTzNotApprove((int)$_POST['tz_id'], App::getUserId(), $_POST['desc']);
    }

    /**
     * @desc Не одобряет ТЗ
     */
    public function confirmTzClearAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->confirmTzNotApprove((int)$_POST['tz_id'], App::getUserId());
    }

    /**
     * @desc Получает данные методов для ГОСТа
     */
    public function getMethodListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if (!empty($_POST['gost']) && !empty($_POST['deal_id'])) {
            /** @var Requirement $requirement */
            $requirement = $this->model('Requirement');

            $response = $requirement->getGostsByName($_POST['gost'], (int)$_POST['deal_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает ГОСТы по id материала
     */
    public function getGostsGroupAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if (!empty($_POST['id'])) {
            /** @var Requirement $requirement */
            $requirement = $this->model('Requirement');

            $response = $requirement->getGostsByMaterialId((int)$_POST['id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Проверяет соответствие методики и ответсвенных
     */
    public function isConfirmMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if (!empty($_POST['id']) && !empty($_POST['methods_id'])) {
            /** @var Requirement $requirement */
            $requirement = $this->model('Requirement');
            /** @var User $user */
            $user = $this->model('User');

            $methodsId = array_map('intval', $_POST['methods_id']);

            $dealId = $requirement->getDealIdByTzId((int)$_POST['id']);
            $labsToMethod = $requirement->getLabsByMethodsId($methodsId);
            $assigned = $user->getAssignedByDealId($dealId);

            $department = array_column($assigned, 'department');

            $labsToUser = array_map(function ($item) {
                foreach ($item as $value) {
                    return $value;
                }
            }, $department);

            foreach ($labsToMethod as $key => $value) {
                if (empty($value)) {
                    $response['not_match'][$key] = 'Методика не привязана ни к одной лаборатории';
                }

                //is $labsToUser empty ??? -> !empty($value)
                $isConfirmLabs = array_intersect($labsToUser, $value);

                if (empty($isConfirmLabs) && !empty($value)) {
                    $usersByLab = $requirement->getUserByLabId($value);

                    $notMatch = array_keys($usersByLab);

                    $response['not_match'][$key] = $notMatch;

                    foreach ($usersByLab as $k => $v) {
                        $response['users_by_lab'][$k] = $v;
                    }
                }
            }
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает данные ТУ по id ГОСТа
     */
    public function getTuForGostAjax() {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Gost $gost */
        $gost = $this->model('Gost');

        $response = [];
        $idGost = (int)$_POST['id'];

        $arrTU = $gost->getTuByGostID($idGost);

        $tu = json_decode($arrTU['ID_TU'], true);

        foreach ($tu as $item) {

            $tuForOption = $gost->getGostForOption((int)$item);

            $response[] = [
                'ID' => $tuForOption['ID'],
                'NORM_TEXT' => $tuForOption['NORM_TEXT'],
                'GOST' => $tuForOption['GOST'],
                'SPECIFICATION' => $tuForOption['SPECIFICATION'],
                'view_gost' => $tuForOption['view_gost']
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает ответвенных по id ГОСТа
     */
    public function getAssignedByGostIdAjax()
    {
        /** @var Gost $gost */
        $gost = $this->model('Gost');

        $gostId = (int)$_POST['id'];

        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $assigned = $gost->getAssignedByGostID($gostId);

        echo json_encode($assigned, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает ответственных для методики
     */
    public function getUlabAssignedByGostIdAjax()
    {
        /** @var Gost $gost */
        $gost = $this->model('Gost');

        $gostId = (int)$_POST['id'];

        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $assigned = $gost->getUlabAssignedByGostID($gostId);

        echo json_encode($assigned, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Удаляет материал из заявки
     */
    public function deletePermanentMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->deleteMaterial((int)$_POST['deal_id'], (int)$_POST['material_id'], (int)$_POST['mtr_id'], (int)$_POST['number']);
    }

    /**
     * @desc Удаляет методику из материала
     */
    public function deletePermanentMaterialGostAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->deleteMaterialGost((int)$_POST['gtp_id'], (int)$_POST['deal_id'], (int)$_POST['material_id'], (int)$_POST['numberGost'], (int)$_POST['number']);
    }

    /**
     * @desc Получает список методик
     */
    public function getMethodsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $result = $methodsModel->getList();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает данные методики по id
     */
    public function getMethodDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $result = $methodsModel->get((int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает список ТУ
     */
    public function getTechCondListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var TechCondition $tcModel */
        $tcModel = $this->model('TechCondition');

        $result = $tcModel->getList();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает список карьеров
     */
    public function getQuarryAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Quarry $quarryModel */
        $quarryModel = $this->model('Quarry');

        $result = $quarryModel->getList();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет испытание из пробы по id
     */
    public function deleteProbeMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $result = $requirementModel->deleteProbeMethod((int)$_POST['tz_id'], (int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет пробу и испытания
     */
    public function deleteProbeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $result = $requirementModel->deleteProbe((int)$_POST['deal_id'], (int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет материал
     */
    public function deleteMaterial()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $result = $requirementModel->deleteMaterialNew((int)$_POST['deal_id'], (int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные проб для заявки
     */
    public function getProbeMethodsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $result = $materialModel->getMaterialProbeToRequest((int)$_POST['deal_id'], '', (int)$_POST['probe_id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные материала для заявки
     */
    public function getMaterialProbesAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $result = $materialModel->getMaterialProbeToRequest((int)$_POST['deal_id'], (int)$_POST['material_id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные материала и проб для журнала
     */
    public function getMaterialProbeJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $filter = $requirementModel->prepareFilter($_POST ?? []);

        if ( $_POST['cipher'] !== '' ) {
            $filter['search']['cipher'] = $_POST['cipher'];
        }
        if ( !is_null($_POST['work_id']) && $_POST['work_id'] !== '' ) {
            $filter['search']['work_id'] = intval($_POST['work_id']);
        }

        $data = $requirementModel->getMaterialProbeJournal((int)$_POST['deal_id'], $filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные методик для отображения журнала в дочерней строке пробы
     */
    public function getMethodJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $filter = $requirementModel->prepareFilter($_POST ?? []);

        if ( !empty($_POST['probe_id']) ) {
            $filter['search']['probe_id'] =
                is_array($_POST['probe_id']) ? array_map('intval', $_POST['probe_id']) : [];
        }

        $data = $requirementModel->getMethodJournal((int)$_POST['deal_id'], $filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет метод
     */
    public function deleteMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $result = $requirementModel->deleteProbeMethod((int)$_POST['tz_id'], (int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Передвигает строки методик
     */
    public function changeGostNumberAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->changeGostNumber($_POST['data']);

        echo json_encode([], JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Сохраняет изменения в ячейке методики, НД, исполнитель, цена
     */
    public function updateMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $arr = [
            'assigned' => [
                'assigned_id' => $_POST['val'],
            ],
            'tu' => [
                'norm_doc_method_id' => $_POST['val'],
            ],
            'method' => [
                'new_method_id' => $_POST['val'],
                'method_id' => $_POST['val'],
            ],
            'price' => [
                'price' => $_POST['val'],
            ],
        ];

        $result = $requirementModel->updateProbeGost((int)$_POST['tz_id'], (int)$_POST['id'], $arr[$_POST['field']]);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc добавление материала в новом тз аяксом
     */
    public function addMaterialToTzAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->addMaterialToTz((int)$_POST['deal_id'], (int)$_POST['material_id'], (int)$_POST['number']);
    }

    /**
     * @desc добавление материала в новом тз аяксом
     */
    public function addProbeToMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $requirementModel->addProbeToMaterial((int)$_POST['deal_id'], (array)$_POST['material_id'], (int)$_POST['number']);
    }

    /**
     * @desc добавление методик выбранным пробам в новом тз аяксом
     */
    public function addMethodsToProbeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        if ( empty($_POST['probe_id_list']) ) {
            return false;
        }

        if ( empty($_POST['form']) ) {
            return false;
        }

        $probeIdList = explode(',', $_POST['probe_id_list']);

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        // обновляем схему у заявки (позже обновить)
        $requestModel->updateDealScheme((int)$_POST['deal_id'], (int)$_POST['scheme_id']);

        // добавляем методики
        $requirementModel->addMethodsToProbe($probeIdList, $_POST['form']);

        // обновляем цены у заявки
        $priceData = $requirementModel->updatePrice($_POST['deal_id']);

        echo json_encode([
            'success' => true, 
            'priceData' => $priceData
        ], JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc добавление работы в тз аяксом
     */
    public function addWorkAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $result = $requirementModel->addWork($_POST['form'], $_FILES);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * загружает аяксом файл, обновляет запись в таблице
     * @return void
     */
    public function addFileWorkAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $result = $requirementModel->addFilesWork((int)$_POST['work_id'], (int)$_POST['deal_id'], $_FILES);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc редактирует или удаляет пробу через аякс
     */
    public function editProbeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        if ( $_POST['button'] === 'save' ) {
            $requirementModel->updateProbeInfo((int)$_POST['probe_id'], $_POST['form']);
            $result = [
                'success' => true,
            ];
        } else if ( $_POST['button'] === 'delete' ) {
            $result = $requirementModel->deleteProbe((int)$_POST['deal_id'], (int)$_POST['probe_id']);
            $result['type'] = 'delete';
        } else {
            $result = [
                'success' => false,
                'error' => 'Неизвестная команда'
            ];
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
