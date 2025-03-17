<?php

/**
 * @desc Контроллер для заявок
 * Class RequestController
 */
class RequestController extends Controller
{
    //ID ТЗ с которого начинается рефакторинг ТЗ (TODO: Для новых лабораторий удалить, так же убрать из карточки card.php)
    //const TZ_REFACTORING_START_ID = 7433;


    private function secretCode( $id )
    {
        return md5("This deal is {$id}");
    }


    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     * route /request/
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }


    /**
     * route /request/new/
     * @desc Страница создания новой заявки
     */
    public function new()
    {
        $this->data['title'] = 'Формирование заявки на испытания';

        $this->data['is_edit'] = false;

        if ( isset($_SESSION['request_post']) ) {
            $_SESSION['request_post']['CompanyFullName'] = htmlspecialchars($_SESSION['request_post']['CompanyFullName']);

            $this->data['request'] = $_SESSION['request_post'];

            unset($_SESSION['request_post']);
        } else {
            $this->data['request'] = [];
        }

        /** @var User $user */
        $user = $this->model('User');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Material $material */
        $material = $this->model('Material');
        /** @var Company $company */
        $company = $this->model('Company');

        $this->data['clients'] = $user->getAssignedUserList();

        $this->data['clients_main'] = $user->getAssignedUserList(true);

        $this->data['materials'] = $material->getList();

        $this->data['companies'] = $company->getList();

        $this->data['contracts'] = [];

        $this->addCSS("/assets/plugins/popup/main.popup.bundle.css");
        $this->addJs('/assets/plugins/popup/main.popup.bundle.js');

        $this->view('form');
    }


    /**
     * route /request/edit/{$id}
     * @desc Страница редактирования заявки
     * @param $dealId - ид сделки (из битрикса)
     */
    public function edit( $dealId )
    {
        if ( empty($dealId) || $dealId < 0 ) {
            $this->redirect('/request/new/');
        }

        /** @var User $user */
        $user = $this->model('User');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Material $material */
        $material = $this->model('Material');
        /** @var Company $company */
        $company = $this->model('Company');

        $this->data['test'] = $_SESSION['request_post'];

        $deal = $request->getDealById($dealId);
        $requestData = $request->getTzByDealId($dealId);

        if ( empty($deal) ) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $this->data['title'] = "Редактирование заявки на испытания {$deal['TITLE']}";
        $this->data['deal_title'] = $deal['TITLE'];
        $this->data['stage'] = $request->getStage($requestData);

        $this->data['is_edit'] = true;

        if ( isset($_SESSION['request_post']) ) {
            $this->data['request'] = $_SESSION['request_post'];

            unset($_SESSION['request_post']);
        } else {
            // получаем новые данные
            $this->data['request'] = [];

            $this->data['request']['id'] = $dealId;

            $this->data['request']['save'] = $this->secretCode($dealId);

            $clientCompany = $company->getRequisiteByCompanyId($deal['COMPANY_ID']);
            $baTz = $request->getTzByDealId($dealId);

            //// блок заполения формы htmlentities($script, ENT_QUOTES, 'UTF-8');

            $this->data['request']['company']['ID']     = $deal['COMPANY_ID'];
            $this->data['request']['company']['TITLE']  = htmlentities($deal['COMPANY_TITLE'], ENT_QUOTES, 'UTF-8');
            $this->data['request']['REQ_TYPE']          = $deal['TYPE_ID'];

            $this->data['request']['INN']               = htmlentities($clientCompany['RQ_INN']);
            $this->data['request']['OGRNIP']            = htmlentities($clientCompany['RQ_OGRNIP']);
            $this->data['request']['OGRN']              = htmlentities($clientCompany['RQ_OGRN']);
            $this->data['request']['ADDR']              = htmlentities($clientCompany['RQ_ACCOUNTANT']);
            $this->data['request']['ACTUAL_ADDRESS']    = htmlentities($clientCompany['address'][1]['ADDRESS_1']);
            $this->data['request']['mailingAddress']    = htmlentities($clientCompany['RQ_COMPANY_NAME']);
            $this->data['request']['EMAIL']             = htmlentities($clientCompany['RQ_FIRST_NAME']);
            $this->data['request']['POST_MAIL']         = htmlentities($clientCompany['RQ_EMAIL']);
            $this->data['request']['PHONE']             = htmlentities($clientCompany['RQ_PHONE']);
            $this->data['request']['CONTACT']           = htmlentities($clientCompany['RQ_NAME']);
            $this->data['request']['KPP']               = htmlentities($clientCompany['RQ_KPP']);
            $this->data['request']['Position2']         = htmlentities($clientCompany['RQ_COMPANY_REG_DATE']);
            $this->data['request']['DirectorFIO']       = htmlentities($clientCompany['RQ_DIRECTOR']);
            $this->data['request']['RaschSchet']        = htmlentities($clientCompany['RQ_ACC_NUM']);
            $this->data['request']['KSchet']            = htmlentities($clientCompany['RQ_COR_ACC_NUM']);
            $this->data['request']['l_schet']           = htmlentities($clientCompany['COMMENTS']);
            $this->data['request']['BankName']          = htmlentities($clientCompany['RQ_BANK_NAME']);
            $this->data['request']['CompanyFullName']   = htmlentities($clientCompany['RQ_COMPANY_FULL_NAME']);
            $this->data['request']['BIK']               = htmlentities($clientCompany['RQ_BIK']);

            $this->data['request']['DOGOVOR_NUM']       = $baTz['DOGOVOR_NUM'];
            $this->data['request']['check_ip']          = $baTz['CHECK_IP'];
            $this->data['request']['order_type']          = (int)$baTz['order_type'];
            $this->data['request']['PositionGenitive']  = htmlentities($baTz['POSIT_LEADS']);

            $this->data['request']['addEmail']          = $baTz['addMail'];

            $this->data['request']['ACTS_BASIS']        = htmlentities($clientCompany['address'][1]['ADDRESS_2']);

            $this->data['request']['assign']            = $user->getAssignedByDealId($dealId);
            $this->data['request']['material']          = $material->getMaterialsToRequest($dealId);
            $this->data['request']['act_information']   = $requestData['act_information'];
            //// конец блока заполения формы
        }

        //// блок заполненя select и datalist

        $this->data['contracts'] = $request->getContractsByCompanyId($deal['COMPANY_ID']);

        $this->data['clients'] = $user->getAssignedUserList();

        $this->data['clients_main'] = $user->getAssignedUserList(true);

        $this->data['materials'] = $material->getList();

        $this->data['companies'] = $company->getList();

        //// конец блока заполнения select и datalist

        $this->view('form');
    }


    /**
     * @desc Сохраняет или обновляет данные заявки
     */
    public function insertUpdate()
    {
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Company $company */
        $company = $this->model('Company');
        /** @var Material $material */
        $material = $this->model('Material');
        /** @var User $user */
        $user = $this->model('User');
        /** @var Order $order */
        $order = $this->model('Order');

        $location   = empty($_POST['id'])? '/request/new/' : "/request/edit/{$_POST['id']}";
        $successMsg = empty($_POST['id'])? 'Заявка успешно создана' : "Заявка успешно изменена";

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        foreach ($_POST['ASSIGNED'] as $k => $ass) {
            $_SESSION['request_post']['assign'][$k]['user_name'] = $ass;
        }
        foreach ($_POST['id_assign'] as $k => $ass) {
            $_SESSION['request_post']['assign'][$k]['user_id'] = $ass;
        }

        $valid = $this->validateAssigned($_POST['ASSIGNED']);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        //// блок проверок

        // Клиент *
        $valid = $this->validateField($_POST['company'], "Клиент");
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Полное наименование компании
        $valid = $this->validateField($_POST['CompanyFullName'], "Полное наименование компании", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ИНН
        if ( strlen($_POST['INN']) !== 0 && strlen($_POST['INN']) !== 10 && strlen($_POST['INN']) !== 12 ) {
            $l = strlen($_POST['INN']);
            $this->showErrorMessage("В поле ИНН введено {$l} символов. Должно быть 10 или 12");
            $this->redirect($location);
        }
        $valid = $this->validateNumber($_POST['INN'], "ИНН", false, 12);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ОГРН
        $valid = $this->validateNumber($_POST['OGRN'], "ОГРН", false, 13);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Адрес
        $valid = $this->validateField($_POST['ADDR'], "Адрес",false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Фактический адрес
        $valid = $this->validateField($_POST['ACTUAL_ADDRESS'], "Фактический адрес", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Почтовый адрес
        $valid = $this->validateField($_POST['mailingAddress'], "Почтовый адрес", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // E-mail для договора
        $valid = $this->validateEmail($_POST['EMAIL'], false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // E-mail *
        $valid = $this->validateEmail($_POST['POST_MAIL']);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Дополнительный E-mail
        foreach ($_POST['addEmail'] as $item) {
            $valid = $this->validateEmail($item, false);
            if ( !$valid['success'] ) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }
        }

        // Телефон *
        $valid = $this->validateField($_POST['PHONE'], "Телефон");
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Контактное лицо
        $valid = $this->validateNumber($_POST['company_id'], "Контактное лицо", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // КПП
        $valid = $this->validateNumber($_POST['KPP'], "КПП", false, 9);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Должность руководителя
        $valid = $this->validateField($_POST['Position2'], "Должность руководителя", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Должность руководителя в родительном падеже
        $valid = $this->validateField($_POST['PositionGenitive'], "Должность руководителя в родительном падеже", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ФИО руководителя
        $valid = $this->validateField($_POST['DirectorFIO'], "ФИО руководителя", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Расчетный счет
        $valid = $this->validateField($_POST['RaschSchet'], "Расчетный счет", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Кор. счёт
        $valid = $this->validateField($_POST['KSchet'], "Кор. счёт", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Лицевой счёт
        $valid = $this->validateField($_POST['l_schet'], "Лицевой счёт", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // БИК
        $valid = $this->validateField($_POST['BIK'], "БИК", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Наименование банка
        $valid = $this->validateField($_POST['BankName'], "Наименование банка", false);
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Материал для исследования *
        foreach ($_POST['material'] as $item) {
            $valid = $this->validateField($item['name'], "Материал для исследования");
            if ( !$valid['success'] ) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }
            if ( (int) $item['count'] <= 0 ) {
                $this->showErrorMessage("Количество материала должно быть больше нуля");
                $this->redirect($location);
            }
        }

        // Главный Ответственный *
        foreach ($_POST['id_assign'] as $item) {
            $valid = $this->validateNumber($item, "Ответственный");
            if ( !$valid['success'] ) {
                $this->showErrorMessage("Не выбран ответственный");
                $this->redirect($location);
            }
        }

        //// конец блок проверок

        $resetId = 1;
        if ( empty($_POST['company_id']) ) {
            $companyId = $company->add($_POST['company']);

            if ( $companyId === false ) {
                $this->showErrorMessage("Не удалось создать нового Клиента");
                $this->redirect($location);
            }
        } else {
            $companyId = $_POST['company_id'];

            $requisite = $company->getRequisiteByCompanyId($companyId);
            //$resetId = $requisite['PRESET_ID'];
        }

        switch ($_POST['REQ_TYPE']) {
            case 'SALE':
                $type = "ИЦ";
                break;
            case 'COMPLEX':
                $type = "ОСК";
                break;
            case '1':
                $type = "ВЛК";
                break;
            case '2':
                $type = "МСИ";
                break;
            case '4':
                $type = "НК";
                break;
            case '5':
                $type = "АП";
                break;
            case '7':
                $type = "ПР";
                break;
            case '8':
                $type = "Н";
                break;
            default:
                $type = "ИЦ";
        }

        $arrAssigned['VALUE'] = [];
        for ( $i = 1; $i < count($_POST['id_assign']); $i++ ) {
            $arrAssigned['VALUE'][] = $_POST['id_assign'][$i];
        }

        $dataRequest = [
            'company_id' => $companyId,
            'type' => $_POST['REQ_TYPE'],
            'type_rus' => $type,
            'assigned' => $_POST['id_assign'][0],
            'arrAssigned' => $arrAssigned,
        ];

        $dataBank = [
            'ENTITY_ID'                 => $companyId,
            'ENTITY_TYPE_ID'            => CCrmOwnerType::Company,
            'PRESET_ID'                 => $resetId,
            'NAME'                      => $_POST['company'],
            'SORT'                      => 500,
            'ACTIVE'                    => 'Y',
            'RQ_INN'                    => $_POST['INN'],
            'RQ_ACCOUNTANT'             => $_POST['ADDR'],
            'RQ_OGRN'                   => $_POST['OGRN'],
            'RQ_OGRNIP'                 => $_POST['OGRNIP'],
            'RQ_EMAIL'                  => $_POST['POST_MAIL'],
            'RQ_PHONE'                  => $_POST['PHONE'],
            'RQ_NAME'                   => $_POST['CONTACT'],
            'RQ_KPP'                    => $_POST['KPP'],
            'RQ_COMPANY_REG_DATE'       => $_POST['Position2'],
            'RQ_COMPANY_FULL_NAME'      => $_POST['CompanyFullName'],
            'RQ_DIRECTOR'               => $_POST['DirectorFIO'],
            'RQ_ACC_NUM'                => $_POST['RaschSchet'],
            'RQ_COR_ACC_NUM'            => $_POST['KSchet'],
            'RQ_BIK'                    => $_POST['BIK'],
            'RQ_BANK_NAME'              => $_POST['BankName'],
            'RQ_FIRST_NAME'             => $_POST['EMAIL'],
            'RQ_COMPANY_NAME'           => $_POST['mailingAddress'],
            'RQ_ADDR'                   => [
                                            1 => [
                                                'ADDRESS_1' => $_POST["ACTUAL_ADDRESS"],
                                                'ADDRESS_2' => $_POST['ACTS_BASIS'],
                                                'REGION'    => $_POST['OGRNIP']
                                            ]
                                        ],
            'COMMENTS'                  => $_POST['l_schet'],
        ];

        $saveMail = serialize($_POST['addEmail']);
        $orderName = '';

        if ( !empty($_POST['NUM_DOGOVOR']) ) {
            $contract = $order->getContractById($_POST['NUM_DOGOVOR']);
            if (!empty($contract)) {
                $orderName = $contract['cont'];
            }
        }

        $actInformation = json_encode($_POST['information'], JSON_UNESCAPED_UNICODE);
        $dataTz = [
            'COMPANY_TITLE' => '"' . htmlspecialchars($_POST['company']) . '"', //TODO: надо убрать из таблицы это поле
            'COMPANY_ID' => $companyId,
            'TYPE_ID' => "'{$_POST['REQ_TYPE']}'",
            'CHECK_IP' => isset($_POST['check_ip'])? '1' : '0',
            'SAVE_MAIL' => "'{$saveMail}'",
            'DOGOVOR_NUM' => "'{$_POST['NUM_DOGOVOR']}'",
            'DOGOVOR_TABLE' => "'{$orderName}'",
            'POSIT_LEADS' => "'{$_POST['PositionGenitive']}'",
            'order_type' => (int)$_POST['order_type'],
            'act_information' => "'{$actInformation}'"
        ];


        $requisiteResult = $company->setRequisiteByCompanyId($companyId, $dataBank);
        if ( !$requisiteResult['success'] ) {
            $this->showErrorMessage($requisiteResult['error']);
            $this->redirect($location);
        }

        if ( !empty($_POST['id']) ) { // редактирование

//            if ( empty($_POST['save']) || $_POST['save'] !== $this->secretCode($_POST['id']) ) {
//                $this->showErrorMessage("Ошибка");
//                $this->redirect($location);
//            }

            $dealId = $dataRequest['ID'] = $_POST['id'];

            $currDeal = $request->getDealById($dealId);
            $arrTitle = explode(' ', $currDeal['TITLE']);
            $currTitle = "{$type} {$arrTitle[1]}";
            $dataRequest['title'] = $currTitle;

            $result = $request->update( $dataRequest );

            if ( !$result ) {
                $this->showErrorMessage("Не удалось обновить заявку в битриксе");
                $this->redirect($location);
            }

            $dataTz['REQUEST_TITLE'] = "'{$currTitle}'";
            $resultUpdateTz = $request->updateTz($dealId, $dataTz);
            
            if ( empty($resultUpdateTz) ) {
                $this->showErrorMessage("Не удалось обновить заявку");
                $this->redirect($location);
            }
        } else { // создание новой

            // создать материал, если такого нет
            $arrMaterialName = [];
            $materialDataList = [];
            foreach ($_POST['material'] as $item) {
                $arrMaterialName[] = $item['name'];
                if ( empty($item['id']) ) {
                    $resultAddMaterial = $material->add($item['name']);

                    if ( empty($resultAddMaterial) ) {
                        $this->showErrorMessage("Не удалось создать материал '{$item['name']}'");
                        $this->redirect($location);
                    } else {
                        $item['id'] = $resultAddMaterial;
                    }
                }
                $materialDataList[] = $item;
            }

            $dealId = $request->create( $dataRequest );

            if ( $dealId === false ) {
                $this->showErrorMessage("Не удалось создать заявку в битриксе");
                $this->redirect($location);
            }

            $newDeal = $request->getDealById($dealId);

            $strMaterial = implode(', ', $arrMaterialName);

            $dataTz['REQUEST_TITLE'] = "'{$newDeal['TITLE']}'";
            $dataTz['MATERIAL'] = "'{$strMaterial}'";
            $dataTz['STAGE_ID'] = "'NEW'";
            $resultAddTz = $request->addTz($dealId, $dataTz);

            if ( empty($resultAddTz) ) {
                $this->showErrorMessage("Не удалось обновить заявку");
                $this->redirect($location);
            }

            $material->setMaterialToRequest($dealId, $materialDataList);
        }

        $order->deleteContractFromRequest($dealId);
        if ( !empty($_POST['NUM_DOGOVOR']) ) {
            $order->setContractToRequest($dealId, $_POST['NUM_DOGOVOR']);
        }

        $resultSet = $user->setAssignedUserList($dealId, $_POST['id_assign']);

        if ( !$resultSet ) {
            $this->showErrorMessage("Не удалось обновить ответственных");
            $this->redirect($location);
        }

        // обновление лабораторий в заявке
        $assigned = $user->getAssignedByDealId($dealId);

        $labaId = [];
        foreach ($assigned as $item) {
            $labaId[] = $item['department'][0];
        }

        $labaIdStr = implode(',', array_unique($labaId));

        $updateData = [
            'LABA_ID' => "'{$labaIdStr}'"
        ];

        $request->updateTz($dealId, $updateData);
        //======================================


        $this->showSuccessMessage($successMsg);
        unset($_SESSION['request_post']);
        $this->redirect("/request/edit/{$dealId}");
    }


    /**
     * @desc Копирует заявку
     * @param $dealId
     */
    public function copy($dealId)
    {
        if (empty($dealId)) {
            $this->redirect('/request/list/');
        }

        /** @var Request $request */
        $request = $this->model('Request');
        /** @var User $user */
        $user = $this->model('User');
        /** @var Material $material */
        $material = $this->model('Material');
        /** @var Order $order */
        $order = $this->model('Order');

        // получаем заявку, которую копируем
        $currDeal = $request->getDealById($dealId);

        if ( empty($currDeal) ) {
            $this->showErrorMessage('Не удалось получить данные по заявке');
            $this->redirect('/request/list/');
        }

        // получаем ответственных из копируемой заявки
        $userList = $user->getAssignedByDealId($dealId);

        $arrAssigned = [];

        $arrAssigned['VALUE'] = [];
        foreach ($userList as $item) {
            $arrAssigned['VALUE'][] = $item['user_id'];
        }

        // получаем материалы из копируемой заявки
        $materialList = $material->getMaterialsToRequest($dealId);

        $oldMtrIdList = [];

        foreach ($materialList as $item) {
            $oldMtrIdList[] = $item['mtrId'];
        }

        $arrTitle = explode(' ', $currDeal['TITLE']);
        $currType = $arrTitle[0];

        $dataRequest = [
            'company_id' => $currDeal['COMPANY_ID'],
            'type' => $currDeal['TYPE_ID'],
            'type_rus' => $currType,
            'assigned' => $currDeal['ASSIGNED_BY_ID'],
            'arrAssigned' => $arrAssigned,
        ];

        // создаём новую заявку
        $newDeal = $request->create($dataRequest);

        if ( $newDeal === false ) {
            $this->showErrorMessage('Не удалось создать копию заявки');
            $this->redirect("/request/card/{$dealId}");
        }

        $originOrder = $order->getContractDealByDealId($dealId);

        if ( !empty($originOrder) ) {
            $order->setContractToRequest($newDeal, $originOrder['ID_CONTRACT']);
        }

        $user->setAssignedUserList($newDeal, $arrAssigned['VALUE']);
        $newMtrIdList = $material->setMaterialToRequest($newDeal, $materialList);

        $material->copyProbeAndGostByMtr($oldMtrIdList, $newMtrIdList);
        $request->copyTz($dealId, $newDeal);

        $this->showSuccessMessage("Копия успешно создана. Оригинал: <a href='/ulab/card/{$dealId}'>{$dealId}</a>");
        $this->redirect("/request/card/{$newDeal}");
    }


    /**
     * route /request/card/{$id}
     * @desc Карточка заявки
     * @param $dealId - ид сделки (из битрикса)
     */
    public function card( $dealId )
    {
        if (empty($dealId)) {
            $this->redirect('/request/list/');
        }

        $dealId = (int) $dealId;

        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Company $company */
        $company = $this->model('Company');
        /** @var User $user */
        $user = $this->model('User');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $this->data['title'] = "Карточка заявки";

        $tzId = $requirement->getTzIdByDealId($dealId);

        $deal                   = $request->getDealById($dealId);
        $companyDataRequisite   = $company->getRequisiteByCompanyId($deal['COMPANY_ID']);
        $companyData            = $company->getById($deal['COMPANY_ID']);
        $requestData            = $request->getTzByDealId($dealId);
        $orderData              = $requirement->getContracts($dealId);
        $dogovorData            = $requirement->getDogovor($dealId);
        $tzDoc                  = $requirement->getTzDoc($tzId);
        $protocolData           = $requirement->getProtocols($tzId);
        $invoiceData            = $request->getInvoice($tzId);
        $act                    = $requirement->getAct($tzId);
        $actVr                  = $requirement->getActVr($tzId);
        $userFields             = $request->getCrmUserFields($dealId);
        $proposalData           = $requirement->getKP($tzId);
        $materialData           = $requirement->getMaterialProbeGostToRequest($dealId);
        $quarry                 = $requirement->getQuarry();
        $actBase                = $requirement->getActBase($dealId);
        $countResults           = $resultModel->getCountTrialResults($dealId);
        $isResults              = $resultModel->isResultNotEmpty($dealId);
        $permissionInfo         = $permissionModel->getUserPermission($_SESSION['SESS_AUTH']['USER_ID']);


        $userFiles = $request->getFilesFromDir(UPLOAD_DIR . "/request/{$dealId}");

        $isExistTz = $requirement->isExistTz($dealId);

        $this->data['user_files'] = [];
        foreach ($userFiles as $file) {
            $imgLinc = URI.'/assets/images/unknown.png';
            $patternImg = "/\.(png|jpg|jpeg)$/i";
            if ( preg_match($patternImg, $file) ) {
                $imgLinc = URI."/upload/request/{$dealId}/{$file}";
            }
            $patternPdf = "/\.(pdf)$/i";
            if ( preg_match($patternPdf, $file) ) {
                $imgLinc = URI."/assets/images/pdf.png";
            }

            $this->data['user_files'][] = [
                'name' => $file,
                'img' => $imgLinc
            ];
        }

        $isConfirm = $request->isConfirmTz($tzId);

        $this->data['deal_id']  = $dealId;
        $this->data['tz_id']    = $tzId;

        $this->data['doc_id']  = $dogovorData['ID'] ?? '';
        $this->data['attach1'] = $dogovorData['ACTUAL_VER'] ?? '';
        $this->data['attach2'] = $invoiceData['ACTUAL_VER'] ?? '';
        $this->data['attach3'] = $tzDoc['ACTUAL_VER'] ?? '';

        $this->data['material_data'] = $materialData;
        //$this->data['tz_refactoring_start_id'] = self::TZ_REFACTORING_START_ID;
        //ID Сделки с которого начинается рефакторинг Результатов испытания (TODO: Для новых лабораторий удалить или добавить если производится рефакторинг результатов испытаний, так же убрать из карточки card.php)
        $this->data['result_refactoring_start_id'] = $request->getResultRefactoringStartId();

        $this->data['request'] = $requestData;
        $this->data['quarry']  = $quarry;

        $this->data['is_deal_nk'] = $deal['TYPE_ID'] == 4;
        $this->data['is_deal_pr'] = $deal['TYPE_ID'] == 7;
        $this->data['is_deal_sc'] = $deal['TYPE_ID'] == 8;
        $this->data['is_deal_osk'] = $deal['TYPE_ID'] == 'COMPLEX';

        $this->data['stage'] = $request->getStage($requestData);

        $this->data['deal_title'] = $deal['TITLE'];

        $this->data['is_complete'] = !($deal['STAGE_ID'] != '2' && $deal['STAGE_ID'] != '3' && $deal['STAGE_ID'] != '4' && $deal['STAGE_ID'] != 'WON');
        $this->data['is_may_change'] = in_array($_SESSION['SESS_AUTH']['USER_ID'], [1, 10, 35, 62, 9, 11, 58, 34, 43, 61, 13, 7, 15]);
        $this->data['is_end_test'] = $deal['STAGE_ID'] == 2 || $deal['STAGE_ID'] == 4 || $deal['STAGE_ID'] == 'WON';

        $this->data['user']['name'] = $_SESSION['SESS_AUTH']['NAME'];

        $this->data['company_title'] = $deal['COMPANY_TITLE'];
        $this->data['company_id'] = $deal['COMPANY_ID'];
        $this->data['is_managers'] = in_array($_SESSION['SESS_AUTH']['USER_ID'], [62, 83, 61, 17]);
        
        $this->data['is_good_company'] = $companyData[COMPANY_GOOD] == 1; // является ли компания добросовестным плательщиком

        $assignedsList = $user->getAssignedByDealId($dealId);

        $assignedNames = [];
        foreach ($assignedsList as $assigned) {
            $assignedNames[] = $assigned['short_name'];
        }
        $this->data['assigned'] = implode(', ', $assignedNames);
        $this->data['main_assigned'] = $assignedsList[0]['short_name'];

        $this->data['contract'] = $orderData;
        $this->data['dogovor'] = $dogovorData;

        $this->data['act_vr'] = $requirement->getActVr($tzId);

        $this->data['tz_doc'] = $tzDoc;
        $this->data['first_protocol'] = $protocolData[0]?? [];

        $this->data['date_tz_create'] = date('d.m.Y', strtotime($deal['DATE_CREATE']));

        $mailList = $requestData['addMail'] ?? [];
		$this->data['mail_list'] = $mailList;
		$this->data['acc_email'] = $requestData['acc_email'];
        array_unshift($mailList, $companyDataRequisite['RQ_EMAIL']);

        $strAddedMail = implode(', ', $mailList);

        $this->data['email'] = $companyDataRequisite['RQ_EMAIL'];
        $this->data['head_email'] = $strAddedMail;
        $this->data['list_email'] = $mailList;
        $this->data['phone'] = $companyDataRequisite['RQ_PHONE'];
        $this->data['contact'] = $companyDataRequisite['RQ_NAME'];

        $this->data['comment'] = $request->getComment($dealId);

        // ТЗ
        $this->data['tz']['tz_link'] = $dealId >= DEAL_START_NEW_AREA? URI."/requirement/card/{$requestData['ID']}" : URI."/requirement/card_old/{$requestData['ID']}";
        // $this->data['tz']['tz_link'] = "/requirement/card_new/{$requestData['ID']}";
        $this->data['tz']['check'] = !empty($requestData['ID']) && (!empty($requestData['TZ']) || $isExistTz);
        $this->data['tz']['number'] = $requestData['ID'] ?? '';
        $this->data['tz']['date'] = !empty($requestData['DATE_SOZD'])? StringHelper::dateRu($requestData['DATE_SOZD']) : '--';
        $this->data['tz']['date_send'] = $userFields['UF_CRM_1579541506559']['VALUE'] ? StringHelper::dateRu($userFields['UF_CRM_1579541506559']['VALUE']) : "Не отправлено";


        // Коммерческое предложение
        $this->data['proposal']['link'] = $dealId >= DEAL_START_NEW_AREA? "/ulab/generator/CommercialOffer/{$dealId}" : "/protocol_generator/kp.php?ID={$dealId}&TZ_ID={$requestData['ID']}";
        $this->data['proposal']['check'] = !empty($proposalData['ID']);
        $this->data['proposal']['number'] = $proposalData['ID'] ?? 'Не сформировано';
        $this->data['proposal']['date'] = !empty($proposalData['DATE'])? StringHelper::dateRu($proposalData['DATE']) : '--';
        $this->data['proposal']['date_send'] = !empty($proposalData['SEND_DATE'])? StringHelper::dateRu($proposalData['SEND_DATE']) : 'Не отправлено';
        $this->data['proposal']['is_disable_form'] = !$isExistTz;
		$this->data['proposal']['is_disable_mail'] = empty($proposalData) || !empty($act);
		$this->data['test'] = [$requestData['TZ'], $actVr, $requestData['dateEnd'], $requestData['TAKEN_ID_DEAL'], $isConfirm];
        $this->data['proposal']['attach'] = $proposalData['ACTUAL_VER'];
        $this->data['proposal']['test'][] = empty($requestData['TAKEN_ID_DEAL']) || 0;
        $this->data['proposal']['test'][] = empty($requestData['TZ']) || 0;
        $this->data['proposal']['test'][] = !empty($requestData['dateEnd']);


		// Договор
		if (!empty($orderData) && $dogovorData['IS_ACTION'] == 0) {
			$this->data['order']['check'] = 'table-red';
		}	elseif (!empty($orderData) && empty($dogovorData['PDF'])) {
			$this->data['order']['check'] = 'table-yellow';
		}	elseif (!empty($orderData) && !empty($dogovorData['PDF'])) {
			$this->data['order']['check'] = 'table-green';
		}

        if ( !empty($dogovorData) ) {
            $this->data['order']['is_generated'] = true;
            $this->data['order']['number'] = $dogovorData['NUMBER'];
            $this->data['order']['id'] = $dogovorData['ID'];
        } else {
            $this->data['order']['is_generated'] = false;
        }

        $this->data['order']['date'] = !empty($dogovorData['DATE'])? StringHelper::dateRu($dogovorData['DATE']) : '--';
//		$request->pre($dogovorData);
        $this->data['order']['attach'] = $dogovorData['ACTUAL_VER'];
        $this->data['order']['date_send'] = !empty($dogovorData['SEND_DATE'])? StringHelper::dateRu($dogovorData['SEND_DATE']) : 'Не отправлялся из ЛИС';
        $this->data['order']['is_disable_form'] =
			!empty($actVr) || !empty($requestData['dateEnd']) || !empty($requestData['DOGOVOR_NUM']);
        $this->data['order']['is_disable_mail'] = empty($dogovorData) || 0;


        // Приложение к договору
		if (!empty($tzDoc) && $dogovorData['IS_ACTION'] == 0) {
			$this->data['attach']['check'] = 'table-red';
		} 	elseif (!empty($tzDoc) && empty($tzDoc['pdf'])) {
			$this->data['attach']['check'] = 'table-yellow';
		}	elseif (!empty($tzDoc['pdf'])) {
			$this->data['attach']['check'] = 'table-green';
		}

        $this->data['attach']['link'] = $dealId >= DEAL_START_NEW_AREA? "/ulab/generator/TechnicalSpecification/{$dealId}" : "/protocol_generator/tz_doc.php?ID={$dealId}&TZ_ID={$requestData['ID']}";
//        $this->data['attach']['check'] = !empty($tzDoc);
        $this->data['attach']['number'] = $tzDoc['TZ_ID'] ?? 'Не сформировано';
        $this->data['attach']['date'] = !empty($tzDoc['DATE'])? StringHelper::dateRu($tzDoc['DATE']) : '--';
        $this->data['attach']['date_send'] = !empty($tzDoc['SEND_DATE'])? StringHelper::dateRu($tzDoc['SEND_DATE']) : 'Не отправлен';
        $this->data['attach']['actual_ver'] = $tzDoc['ACTUAL_VER'];
        $this->data['attach']['is_disable_form'] = !$this->data['is_deal_osk'] && (
			!$isExistTz || !empty($requestData['dateEnd']) || !$isConfirm || !empty($actVr));
		$this->data['attach']['is_disable_form_test'] = !$this->data['is_deal_osk'];
//            empty($requestData['TZ']) || !empty($requestData['dateEnd']) /*|| !$isConfirm*/ || !empty($actVr);
        $this->data['attach']['is_disable_mail'] = empty($tzDoc) || 0;


        // Счет
        $this->data['invoice']['link'] = $dealId >= DEAL_START_NEW_AREA? "/protocol_generator/account_new.php?ID={$dealId}&TZ_ID={$requestData['ID']}" : "/protocol_generator/account.php?ID={$dealId}&TZ_ID={$requestData['ID']}";
        $this->data['invoice']['check'] = !empty($invoiceData);
        $this->data['invoice']['number'] = !empty($invoiceData['ID']) ? $requestData['ACCOUNT'] : 'Не сформирован';
        $this->data['invoice']['date'] = !empty($invoiceData['DATE'])? StringHelper::dateRu($invoiceData['DATE']) : '--';
        $this->data['invoice']['date_send'] = !empty($invoiceData['SEND_DATE'])? StringHelper::dateRu($invoiceData['SEND_DATE']) : 'Не отправлен';
        $this->data['invoice']['is_disable_form'] = false && !$this->data['is_deal_osk'] && (
			!$isExistTz || empty($requestData['DOGOVOR_NUM']) || !empty($requestData['dateEnd']) || !empty($requestData['TAKEN_ID_DEAL']) || !$isConfirm);
//            empty($requestData) || empty($requestData['DOGOVOR_NUM']) || !empty($requestData['dateEnd']) || !empty($requestData['TAKEN_ID_DEAL']) /*|| !$isConfirm*/;
        $this->data['invoice']['is_disable_mail'] = empty($invoiceData) || 0;
        $this->data['invoice']['attach'] = $invoiceData['ACTUAL_VER'];

        $this->data['invoice']['test'] = [
            'is_disable_form' => $this->data['invoice']['is_disable_form'],
            'empty(requestData)' => !$isExistTz,
            'DOGOVOR_NUM' => empty($requestData['DOGOVOR_NUM']),
            'dateEnd' => $requestData['dateEnd'],
            'TAKEN_ID_DEAL' => $requestData['TAKEN_ID_DEAL'],
            'conf' => !$isConfirm,
        ];


        // Оплата
        $discount = 0;
        if ( !empty($requestData['DISCOUNT']) ) {
            $discount = $requestData['DISCOUNT'];
        }

        //TODO:Проверить оплату со скидкой
//        $this->data['payment']['surcharge'] = (float)$requestData['PRICE'] - (float)$requestData['PRICE'] * (float)$discount / 100 - (float)$requestData['OPLATA'];
        $this->data['payment']['surcharge'] = (float)$requestData['price_discount'] - (float)$requestData['OPLATA'];

		$this->data['payment']['check'] = !empty($requestData['price_discount']) && $isExistTz && $this->data['payment']['surcharge'] == 0;
		$this->data['payment']['is_disable_form'] = ((float)$requestData['OPLATA'] == (float)$requestData['price_discount']) && ($_SESSION['SESS_AUTH']['USER_ID'] != 25);
        $this->data['payment']['price'] = $requestData['price_discount'];
        $this->data['payment']['pay']   = $requestData['OPLATA'];
        $this->data['payment']['datePayment'] =
            !empty($requestData['DATE_OPLATA']) && $requestData['DATE_OPLATA'] !== '01.01.1970' ?
                StringHelper::dateRu($requestData['DATE_OPLATA']) : '--';



        if ( (float) $requestData['OPLATA'] > (float) $requestData['PRICE'] ) {
            $this->data['payment']['status'] = 'Переплата';
        } elseif ( !empty($requestData['PRICE']) && $this->data['payment']['surcharge'] == 0 ) {
            $this->data['payment']['status'] = 'Оплачено полностью';
        } elseif ( !empty($requestData['OPLATA']) && !empty($requestData['PRICE']) ) {
            $this->data['payment']['status'] = 'Оплачено частично';
        } else {
            $this->data['payment']['status'] = 'Не оплачено';
        }

        // Акт приемки проб
		$this->data['sample']['link'] = $dealId >= DEAL_START_NEW_AREA || $dealId == 9735 ? "/ulab/generator/actSampleDocument/{$dealId}" : "/protocol_generator/probe_all.php?ID={$dealId}";
		$this->data['sample']['check'] = !empty($requestData['ACT_NUM']);
		$this->data['sample']['number'] = $requestData['ACT_NUM'] . "/" . date("Y", strtotime($requestData['DATE_ACT']));
		$this->data['sample']['date'] = !empty($requestData['DATE_ACT']) ? StringHelper::dateRu($requestData['DATE_ACT']) : '';
		$this->data['sample']['date_act'] = !empty($requestData['DATE_ACT']) ? $requestData['DATE_ACT'] : '';
		$this->data['sample']['date_send'] = '--';
		$this->data['sample']['is_disable_form'] = !empty($requestData['ACT_NUM']) || !empty($requestData['dateEnd']) || !empty($actVr);
		$this->data['sample']['place_probe'] = $actBase['PLACE_PROBE'] ?? '';
		$this->data['sample']['date_probe'] = !empty($actBase['DATE_PROBE']) ? date('Y-m-d', strtotime($actBase['DATE_PROBE'])) : '';
		$this->data['sample']['selection_type'] = $requestData['SELECTION_TYPE'] ?? '';
		$this->data['sample']['PROBE_PROIZV'] = $actBase['PROBE_PROIZV'] ?? '';
		$this->data['sample']['deliveryman'] = $actBase['deliveryman'] ?? '';
		$this->data['sample']['act_id'] = $actBase['ID'] ?? '';
		$this->data['sample']['act_type'] = $actBase['act_type'] ?? '';
		$this->data['sample']['description'] = $requestData['DESCRIPTION'] ?? '';
        $this->data['sample']['quarry_id'] = $requestData['QUARRY_ID'] ?? '';

        // Протокол
        $this->data['protocol'] = [];
        foreach ($protocolData as $protocol) {
            $title = 'Не сформирован';
            if ( !empty($protocol['NUMBER']) ) {
                $date = strtotime($protocol['DATE']);
                $year = (int)date("Y", $date)%10 ? substr(date("Y", $date), -2) : date("Y", $date);
                $title = "{$protocol['NUMBER']}/{$year}";
            }

            $yearDir = date("Y", strtotime($protocol['DATE']));
            $dir  = PROTOCOL_PATH . "archive/{$requestData['ID']}{$yearDir}/{$protocol['ID']}/";
            $link = $dealId >= DEAL_START_NEW_AREA? "/ulab/generator/ProtocolDocument/{$protocol['ID']}" : "/protocol_generator/protocol_multiple_protocols.php?ID={$dealId}&TZ_ID={$requestData['ID']}&PROTOCOL_ID={$protocol['ID']}";
            $this->data['protocol'][] = [
                'id'        => $protocol['ID'],
                'check'     => !empty($protocol['NUMBER']),
                'number'    => $protocol['NUMBER'],
                'year'      => $yearDir,
                'title'     => $title,
                'sig'       => $protocolModel->getSigFile($dir),
                'pdf'       => $protocolModel->getPdfFile($dir),
                'date'      => !empty($protocol['DATE'])? StringHelper::dateRu($protocol['DATE']) : '--',
                'date_send' => !empty($protocol['PROTOCOL_SEND_DATETIME'])? StringHelper::dateRu($protocol['PROTOCOL_SEND_DATETIME']) : 'Не отправлен',
                'link'      => $link,
                'actual_version'  => $protocol['ACTUAL_VERSION']? unserialize($protocol['ACTUAL_VERSION']) : '',
                'is_disable_form' => /*$requestData['STAGE_ID'] == '4' ||*/ $requestData['STAGE_ID'] == 'WON' || empty($protocol['NUMBER']) /*|| !empty($actVr)*/,
                'is_disable_mail' =>
                    empty($protocol['NUMBER'])
                    || empty($protocol['ACTUAL_VERSION'])
                    || (
                        empty($requestData['OPLATA'])
                        && empty($requestData['TAKEN_ID_DEAL'])
                        && $_SESSION['SESS_AUTH']['USER_ID'] != 9
                    ),
                'is_enable_ecp'  =>
                    !empty($protocol['ID'])
                    && !empty($protocol['NUMBER'])
                    && !empty($protocol['PROTOCOL_TYPE'])  || $protocol['PROTOCOL_TYPE'] == '0'
                    && in_array($protocol['PROTOCOL_TYPE'], [1, 33, 34, 35, 36, 37, 38, 39])
                    && empty($protocol['PROTOCOL_OUTSIDE_LIS']),
//                'is_enable_ecp'  => false,
            ];
        }


        // Результаты испытаний
        //$this->data['results']['check'] = $dealId >= DEAL_START_NEW_AREA ? !empty($countResults['count_utr']) : !empty($requestData['RESULTS']);
        $this->data['results']['check'] = $dealId >= DEAL_START_NEW_AREA ?
            ($dealId >= DEAL_NEW_RESULT ? $isResults : !empty($countResults['count_utr'])) : !empty($requestData['RESULTS']);
		$this->data['results']['is_disabled'] = false;/*(!$this->data['is_deal_pr'] && !$requestData['order_type'] == 2 && !$this->data['is_deal_osk'] && !$this->data['is_deal_sc'] && empty($requestData['TAKEN_ID_DEAL'])) &&
			(empty($requestData) || empty($requestData['ACT_NUM']) || empty($invoiceData)) /*|| !$isConfirm
            || (!$this->data['payment']['check'] && !$this->data['is_good_company'])*/;

        if ( !empty($requestData['DATE_RESULTS']) ) {
            $this->data['results']['date'] = StringHelper::dateRu($requestData['DATE_RESULTS']);
        } elseif (!empty($requestData['DATE_P'])) {
            $this->data['results']['date'] = StringHelper::dateRu($requestData['DATE_P']);
        } else {
            $this->data['results']['date'] = '--';
        }
		$this->data['results']['test'] = (!$this->data['is_deal_pr'] && !$this->data['is_deal_osk'] && !$this->data['is_deal_sc'] && empty($requestData['TAKEN_ID_DEAL'])) &&
			(empty($requestData) || empty($requestData['ACT_NUM']) || empty($invoiceData));


        // Завершение испытаний
        $this->data['complete']['check'] = false;
        $this->data['complete']['date'] = $requestData['dateEnd'] ? StringHelper::dateRu($requestData['dateEnd']) : '--';
        $this->data['complete']['is_disabled'] = false && empty($this->data['protocol']) || 0;
        // Проверка на доступ к ручному завершению испытаний (может завершить Руководитель ИЦ и Админ)
        $this->data['complete']['may_return'] = true || in_array($permissionInfo['id'],  [ADMIN_PERMISSION, HEAD_IC_PERMISSION]);
        $this->data['complete']['may_complete'] = true || in_array($permissionInfo['id'],  [ADMIN_PERMISSION, HEAD_IC_PERMISSION]) &&
            ($deal['STAGE_ID'] == 'PREPARATION' || $deal['STAGE_ID'] == 'PREPAYMENT_INVOICE' ||
                $deal['STAGE_ID'] == 'EXECUTING'|| $deal['STAGE_ID'] == 'FINAL_INVOICE' || $deal['STAGE_ID'] == 1);


        // Акт выполненых работ
        $this->data['act_complete']['check'] = !empty($actVr);
        $this->data['act_complete']['email'] = !empty($requestData['acc_email']) ? $requestData['acc_email'] : $companyDataRequisite['RQ_EMAIL'];
        $this->data['act_complete']['number'] = $actVr['NUMBER'];
        $this->data['act_complete']['attach'] = $actVr['ACTUAL_VER']?? '';
        $this->data['act_complete']['date'] = !empty($actVr['DATE'])? StringHelper::dateRu($actVr['DATE']) : '--';
        $this->data['act_complete']['date_send'] = !empty($actVr['SEND_DATE'])? StringHelper::dateRu($actVr['SEND_DATE']) : 'Не отправлен';
        // Заполнить данные акта возможно только на стадии - Испытания завершины(Работы в лаборатории завершены), т.к могут завершить заявку и не завершить испытание
		$this->data['act_complete']['is_disable_form'] = false && !in_array($_SESSION['SESS_AUTH']['USER_ID'], [61, 88, 1, 25]) || 0;
//		$this->data['act_complete']['is_disable_form'] = !($deal['STAGE_ID'] == 2) || ;
        $this->data['act_complete']['is_disable_mail'] = empty($actVr) || 0;
        // 4 - Роль руководителя лаборатории
        $this->data['act_complete']['assigned_users'] = $user->getUsersByRoleId(4);



        //// Список версий
        // Протокол
        $this->data['file']['protocol'] = [];
        if ( !empty($protocolData) ) {
            foreach ($protocolData as $key => $protocol) {
//                if ( empty($protocol['NUMBER']) ) { continue; }

                if ( empty($protocol['PROTOCOL_OUTSIDE_LIS']) ) {
                    $year = date("Y", strtotime($protocol['DATE']));
                    $dir  = PROTOCOL_PATH . "archive/{$requestData['ID']}{$year}/{$protocol['ID']}/";
                    $path = "/protocol_generator/archive/{$requestData['ID']}{$year}/{$protocol['ID']}/";
                    $files = $request->getFilesFromDir($dir, ['signed.docx', 'forsign.docx', 'qrNEW.png']);
                } else {
                    $dir  = "/home/bitrix/www/pdf/{$protocol['ID']}/";
                    $path = "/pdf/{$protocol['ID']}/";
                    $files = $request->getFilesFromDir($dir);
                }

                $this->data['file']['protocol'][$key]['number'] = $protocol['NUMBER'];
                $this->data['file']['protocol'][$key]['dir'] = $path;

                foreach ($files as $file) {
                    $this->data['file']['protocol'][$key]['files'][] = $file;
                }
            }
        } else {
            $year = date("Y", strtotime($requestData['DATE_ACT']));
            $dir  = PROTOCOL_PATH . "archive/{$requestData['ID']}{$year}/";
            $path = "/protocol_generator/archive/{$requestData['ID']}{$year}/";
            $files = $request->getFilesFromDir($dir, ['signed.docx', 'forsign.docx', 'qrNEW.png']);
            $this->data['file']['protocol'][0]['number'] = $requestData['NUM_P'];
            $this->data['file']['protocol'][0]['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['protocol'][0]['files'][] = $file;
            }
        }

        // КП
        $this->data['file']['kp']['files'] = [];
        if ( !empty($proposalData) ) {
            $dir  = PROTOCOL_PATH . "archive_kp/{$dealId}/";
            $path = "/protocol_generator/archive_kp/{$dealId}/";
            $files = $request->getFilesFromDir($dir);

            $this->data['file']['kp']['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['kp']['files'][] = $file;
            }
        }

        // Договор
        $this->data['file']['order']['files'] = [];
        if ( !empty($dogovorData) ) {
            $dir  = PROTOCOL_PATH . "archive_dog/{$dogovorData['ID']}/";
            $path = "/protocol_generator/archive_dog/{$dogovorData['ID']}/";
            $files = $request->getFilesFromDir($dir);

            $this->data['file']['order']['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['order']['files'][] = $file;
            }
        }

        // Счет-оферта
        $this->data['file']['offer']['files'] = [];
        if ( !empty($dogovorData) ) {
            $dir  = PROTOCOL_PATH . "archive_dog/{$tzId}/";
            $path = "/protocol_generator/archive_dog/{$tzId}/";
            $files = $request->getFilesFromDir($dir);

            $this->data['file']['offer']['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['offer']['files'][] = $file;
            }
        }

        // Прил. к договору (тз)
        $this->data['file']['tz']['files'] = [];
//        if ( !empty($proposalData) ) {
            $dir  = PROTOCOL_PATH . "archive_tz/{$requestData['ID']}/";
            $path = "/protocol_generator/archive_tz/{$requestData['ID']}/";
            $files = $request->getFilesFromDir($dir);

            $this->data['file']['tz']['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['tz']['files'][] = $file;
            }
//        }

        // Счет
        $this->data['file']['invoice']['files'] = [];
        if ( !empty($invoiceData) ) {
            $dir  = PROTOCOL_PATH . "archive_acc/{$dealId}/";
            $path = "/protocol_generator/archive_acc/{$dealId}/";
            $files = $request->getFilesFromDir($dir);

            $this->data['file']['invoice']['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['invoice']['files'][] = $file;
            }
        }

        // Акт ВР
        $this->data['file']['act']['files'] = [];
        if ( !empty($actVr) ) {
            $dir  = PROTOCOL_PATH . "archive_aktvr/{$dealId}/";
            $path = "/protocol_generator/archive_aktvr/{$dealId}/";
            $files = $request->getFilesFromDir($dir);

            $this->data['file']['act']['dir'] = $path;
            foreach ($files as $file) {
                $this->data['file']['act']['files'][] = $file;
            }
        }

        // Фото
		$this->data['file']['photo']['files'] = [];
		$dir  = "/home/bitrix/www/photo/{$dealId}/";
		$path = "/photo/{$dealId}/";
		$files = $request->getFilesFromDir($dir);

		$this->data['file']['photo']['dir'] = $path;
		foreach ($files as $file) {
			$this->data['file']['photo']['files'][] = $file;
		}

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addCSS("/assets/plugins/dropzone/css/basic.css");
        $this->addCSS("/assets/plugins/dropzone/dropzone3.css");
        $this->addJS("/assets/plugins/dropzone/dropzone3.js");

        $r = rand();
        $this->addJs("/assets/js/request-card.js?v={$r}");
		if (!empty($requestData['TAKEN_ID_DEAL'])) {
			$this->view('card_taken');
		} elseif ($deal['TYPE_ID'] == 7) {
			$this->view('card_pr');
		} elseif ($requestData['order_type'] == 2) {
			$this->view('card_offerInvoice');
		} else {
			$this->view('card');
		}
    }


    /**
     * @desc Подставляет заголовок в ba_tz из битрикса
     * @param $dealId
     */
    public function changeTitle($dealId)
    {
        /** @var Request $request */
        $request = $this->model('Request');

        $newDeal = $request->getDealById($dealId);

        $dataTz['REQUEST_TITLE'] = "'{$newDeal['TITLE']}'";

        $request->updateTz($dealId, $dataTz);

        $this->showSuccessMessage('Заголовок изменен');
        $this->redirect("/request/card/{$dealId}");
    }

    /**
     * @desc Изменяет статус заявки на «Завершено»
     * @param $dealId
     */
    public function complete($dealId)
    {
        /** @var Request $request */
        $request = $this->model('Request');

        $request->updateStageDeal($dealId, 2);
        $today = date('Y-m-d');
        $request->updateTz($dealId, ['dateEnd' => "'{$today}'"]);

        $this->showSuccessMessage('Статус заявки изменен на "Завершено"');
        $this->redirect("/request/card/{$dealId}");
    }

    /**
     * @desc Изменяет статус
     * @param $dealId
     */
    public function setStage($dealId)
    {
        /** @var Request $request */
        $request = $this->model('Request');

        if ( isset($_GET['stage']) ) {
            if ( $_GET['stage'] == 0 ) {
                $request->updateStageDeal($dealId, 'PREPARATION');
            } elseif ( $_GET['stage'] == 1 ) {
                /** @var Requirement $requirement */
                $requirement = $this->model('Requirement');

                $tzId  = $requirement->getTzIdByDealId($dealId);
                $actVr = $requirement->getActVr($tzId);

                if (empty($actVr['SEND_DATE'])) {
                    $stageId = 2;
                    $stageNumber = 3;
                } else {
                    $stageId = 4;
                    $stageNumber = 4;
                }

                $request->updateStageDeal($dealId, $stageId, $stageNumber);
            }
        }

        $this->redirect("/request/card/{$dealId}");
    }

    /**
     * route /request/list/
     * @desc Список заявок
     */
    public function list()
    {
        $this->data['title'] = 'Журнал заявок';

        /** @var Lab $lab */
        $lab = $this->model('Lab');
        /** @var Request $request */
        $request = $this->model('Request');

        $this->data['lab'] = $lab->getList();
        $this->data['date_start'] = $request->getDateStart();

        $this->data['tz_under_consideration'] = [];
        $this->data['probe_in_lab'] = [];
        $this->data['confirm_not_account'] = [];

        if ( in_array($_SESSION['SESS_AUTH']['USER_ID'], [11, 13, 15, 58]) ) {
            $this->data['tz_under_consideration'] = $request->tzUnderConsideration($_SESSION['SESS_AUTH']['USER_ID']);
            $this->data['probe_in_lab'] = $request->probeInLab($_SESSION['SESS_AUTH']['USER_ID']);
            $this->data['probe_in_lab_payed'] = $request->probeInLabPayed($_SESSION['SESS_AUTH']['USER_ID']);
            $this->data['request_list_not_assigned'] = $request->getRequestListNoSetAssigned($_SESSION['SESS_AUTH']['USER_ID']);
        }
        if ( in_array($_SESSION['SESS_AUTH']['USER_ID'], [62, 83, 17]) ) {
            $this->data['confirm_not_account'] = $request->getConfirmNotAccountTz();
        }

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");

        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/ColReorder-1.5.5/js/dataTables.colReorder.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.colVis.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.print.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.html5.min.js");
        $this->addJS("/assets/plugins/DataTables/JSZip-2.5.0/jszip.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/intl.js");
        $this->addJS("/assets/plugins/DataTables/FixedHeader-3.2.0/js/dataTables.fixedHeader.min.js");

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $r = rand();
        $this->addJs("/assets/js/journal2.js?v={$r}");

        $this->view('list');
    }

    /**
     * @desc Удаляет документ клиента
     * @param $idDeal
     */
    public function deleteFile($idDeal)
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        if ( empty($idDeal) || empty($_GET['file'])) {
            $this->redirect("/request/card/{$idDeal}");
        }

        $file = $_GET['file'];

        /** @var Request $request */
        $request = $this->model('Request');

        $path = "request/{$idDeal}/{$file}";

        $request->deleteUploadedFile($path);

        $this->showSuccessMessage("Файл '{$file}' удален");

        $this->redirect("/request/card/{$idDeal}");
    }

    /**
     * @desc Добавляет комментарий к заявке
     * @param $idDeal
     */
    public function addComment($idDeal)
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Request $request */
        $request = $this->model('Request');

        $request->addComment($idDeal, $_POST['comment']);

        $this->redirect("/request/card/{$idDeal}");
    }

    /**
     * @desc Записывает данные оплаты
     */
    public function setOplata()
    {
        /** @var Request $request */
        $request = $this->model('Request');

        $idDeal = $_POST['deal_id'];

        if ( empty($idDeal) ) {
            $this->showErrorMessage('Не указан, или указан неверно ИД заявки');
            $this->redirect("/request/list/");
        }

		if ( !in_array($_SESSION['SESS_AUTH']['USER_ID'], [25, 88, 61]) && ($_POST['pay'] <= 0) ) {
            $this->showErrorMessage('Оплата не может быть меньше или равна нулю');
            $this->redirect("/request/card/{$idDeal}");
        }

        $request->addPay($idDeal, $_POST['pay'], "'" . date("d.m.Y", strtotime($_POST['payDate'])) . "'");
        $request->addMessage($idDeal, $_POST['pay']);

        $this->showSuccessMessage('Оплата прошла успешно');
        $this->redirect("/request/card/{$idDeal}");
    }

    /**
     * @desc Получает данные для «Журнала заявок» [deprecated]
     */
    public function getListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Request $request*/
        $request = $this->model('Request');
        /** @var User $user */
        $user = $this->model('User');

        $currentUser = $user->getCurrentUser();

        $userId = (int)$currentUser['ID'];

        $response = $request->getDataToJournalRequests($userId);

        unset($response['recordsTotal']);
        unset($response['recordsFiltered']);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает данные для «Журнала заявок»
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Request $request*/
        $request = $this->model('Request');
        /** @var User $user */
        $user = $this->model('User');

        $currentUser = $user->getCurrentUser();

        $userId = (int)$currentUser['ID'];

        $filter = [
            'paginate' => [
                'length'    => $_POST['length'],  // кол-во строк на страницу
                'start'      => $_POST['start'],  // текущая страница
            ],
            'search' => [],
            'order' => []
        ];

        foreach ($_POST['columns'] as $column) {
            if ( isset($column['search']['value']) && $column['search']['value'] != '' ) {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if ( isset($_POST['order']) && !empty($_POST['columns']) ) {
            $filter['order']['by']  = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

        if ( !empty($_POST['dateStart']) ) {
            $filter['search']['dateStart'] = date('Y-m-d', strtotime($_POST['dateStart'])) . ' 00:00:00';
            $filter['search']['dateEnd'] = date('Y-m-d', strtotime($_POST['dateEnd'])) . ' 23:59:59';
        }
        if ( !empty($_POST['stage']) ) {
            $filter['search']['stage'] = $_POST['stage'];
        }
        if ( !empty($_POST['lab']) ) {
            $filter['search']['lab'] = $_POST['lab'];
        }
        if ( !empty($_POST['everywhere']) ) {
            $filter['search']['everywhere'] = $_POST['everywhere'];
        }

        $data = $request->getDataToJournalRequests($userId, $filter);



        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает заявки отправленные на проверку
     *  возвращает json
     */
    public function getCheckTzAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Request $request*/
        $request = $this->model('Request');

        /** @var User $user */
        $user = $this->model('User');

        $userGroups = $user->getUserGroups();

        //TODO: Избавиться от хард кода
        if (in_array('23', $userGroups)) {
            $checkTz = $request->getCheckTz();

            echo json_encode($checkTz, JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * @desc Получает данные компании
     * передается ид компании через POST
     * возвращается json
     */
    public function getRequisiteAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if ( isset($_POST['company_id']) && !empty($_POST['company_id']) ) {
            /** @var Company $company */
            $company = $this->model('Company');
            $response = $company->getRequisiteByCompanyId($_POST['company_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные договоров
     * передается ид компании через POST
     * возвращается json
     */
    public function getContractsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if ( isset($_POST['company_id']) ) {
            /** @var Request $request */
            $request = $this->model('Request');
            $response = $request->getContractsByCompanyId($_POST['company_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Сохраняет картинку с росписью
     * получает POST с картинкой в base64
     * возвращает json с результатом операции
     * @param $dealId
     */
    public function saveSignAjax($dealId)
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        if ( !isset($_POST['src']) ) {
            $response = [
                'success' => false,
                'error' => "Не пришел POST"
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }

        if ( empty($dealId) ) {
            $response = [
                'success' => false,
                'error' => "Не пришел ид сделки"
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            return;
        }

        /** @var  Request $request*/
        $request = $this->model('Request');

        $response = $request->saveSign($dealId, $_POST['src']);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Сохраняет документы клиента
     * @param $dealId
     */
    public function uploadUserFileAjax($dealId)
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Request $request*/
        $request = $this->model('Request');

        if ( isset($_FILES['file']) ) {
            $response = $request->saveAnyFile("request/{$dealId}", $_FILES['file']);

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }


    public function fillassigned()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Request $request */
        $request = $this->model('Request');

        $request->fillAssigned();

        echo 'OK';
    }

    /**
     * @desc Сохраняет фото испытаний
     * @param $dealId
     */
    public function dwnImage($dealId)
	{
		/** @var  Request $request*/
		$request = $this->model('Request');

		if (isset($_FILES['photo']) && !empty($_FILES['photo'])) {
			$resultDwnPhoto = $request->savePhoto($_FILES['photo'], $dealId);
			if (!$resultDwnPhoto['success']) {
				$this->showErrorMessage($resultDwnPhoto['error']);
			} else {
				$this->showSuccessMessage("Файл успешно загружен");
			}
		} else {
			$this->showErrorMessage("Нет файла");
		}
		$this->redirect('/request/card/' . $dealId);
	}

    /**
     * @desc Выполняет проверку существования компании по ИНН
     */
	public function checkCompanyByInnAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Company $company */
        $company = $this->model('Company');

        if ( empty($_POST['INN']) ) {
            echo json_encode(false);
            return;
        }

        echo json_encode($company->getCompanyIdByInn(trim($_POST['INN'])));
    }

    /**
     * @desc Получает данные компании по ИНН
     */
	public function getCompanyByInnAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Company $company */
		$company = $this->model('Company');

		if ( empty($_POST['INN']) ) {
			echo json_encode(false);
			return;
		}

		echo json_encode($company->getCompanyByInn(trim($_POST['INN'])), JSON_UNESCAPED_UNICODE);
	}

    /**
     * @desc Получает все данные компании по ИНН из «Битрикс»
     */
    public function getCompanyByInnFromBxAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Company $company */
        $company = $this->model('Company');

        if ( empty($_POST['INN']) ) {
            echo json_encode(false);
            return;
        }

        echo json_encode($company->getByInnFromBx(trim($_POST['INN'])), JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @desc Получает дополнительных пользователей для выбора ответственного
     * в request/new и request/edit
     */
    public function getAssignedUserListAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var User $user */
        $user = $this->model('User');

        $data = $user->getAssignedUserList();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
