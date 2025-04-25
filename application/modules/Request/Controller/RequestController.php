<?php

/**
 * @desc Контроллер для заявок
 * Class RequestController
 */
class RequestController extends Controller
{
    //ID ТЗ с которого начинается рефакторинг ТЗ (TODO: Для новых лабораторий удалить, так же убрать из карточки card.php)
    //const TZ_REFACTORING_START_ID = 7433;

    private $requestTypeConfig = [
        '9' => [
            'blocks' => [
                'tz' => true,
                'proposal' => false,
                'order' => false,
                'invoice' => false,
                'payment' => false,
                'sample' => true,
                'protocol' => true,
                'results' => true,
                'complete' => true,
                'act_complete' => true,
                'files' => true
            ],
            'template' => 'card_government'
        ],
        'default' => [
            'blocks' => [
                'tz' => true,
                'proposal' => true,
                'order' => true,
                'invoice' => true,
                'payment' => true,
                'sample' => true,
                'protocol' => true,
                'results' => true,
                'complete' => true,
                'act_complete' => true,
                'files' => true
            ],
            'template' => 'card'
        ]
    ];


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
        /** @var Lab $lab */
        $lab = $this->model('Lab');

        $this->data['clients'] = $user->getAssignedUserList();

        $this->data['clients_main'] = $user->getAssignedUserList(true);

        $this->data['materials'] = $material->getList();

        $this->data['companies'] = $company->getList();

        $this->data['laboratories'] = $lab->getLabList();

        $this->data['type_list'] = $request->getTypeRequestList();


        $this->data['contracts'] = [];

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs('/assets/js/request_new.js?v=2');

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
        /** @var Lab $lab */
        $lab = $this->model('Lab');

        $this->data['test'] = $_SESSION['request_post'];

        $deal = $request->getDealById($dealId);
        $requestData = $request->getTzByDealId($dealId);

        if ( $requestData['TYPE_ID'] != '9' ) {
            $this->data['comm'] = '?type_request=commercial';
        }

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

            $this->data['request']['assign']            = $user->getAssignedByDealId($dealId, true);
            // $this->data['request']['material']          = $material->getMaterialsToRequest($dealId);
            $this->data['request']['act_information']   = $requestData['act_information'];
            $this->data['request']['object']            = $requestData['OBJECT'];
            $this->data['request']['deadline']          = $requestData['DEADLINE'];

            $this->data['request']['application_type']  = $request->getApplicationTypeData($dealId, 'government_work');
            //// конец блока заполения формы
        }

        $this->data['contracts'] = $request->getContractsByCompanyId($deal['COMPANY_ID']);
        $this->data['clients'] = $user->getAssignedUserList();
        $this->data['clients_main'] = $user->getAssignedUserList(true);
        $this->data['materials'] = $material->getList();
        $this->data['companies'] = $company->getList();
        $this->data['laboratories'] = $lab->getLabList();
        $this->data['display'] = $this->getDisplayClass();
        $this->data['type_list'] = $request->getTypeRequestList();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs('/assets/js/request_new.js?v=2');

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

        $companyId = null;

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        $this->validationForAll($_POST, $location);

        if ( empty($_POST['company_id']) ) {
            $companyId = $company->add($_POST['company']);

            if ( $companyId === false ) {
                $this->showErrorMessage("Не удалось создать нового Клиента");
                $this->redirect($location);
            }
        } else {
            $companyId = (int)$_POST['company_id'];
        }

        if ($_POST['REQ_TYPE'] === 'SALE') {
            $this->validationSale($_POST, $location);

            $resetId = 1;
            
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

            $requisiteResult = $company->setRequisiteByCompanyId((int)$companyId, $dataBank);
            if ( !$requisiteResult['success'] ) {
                $this->showErrorMessage($requisiteResult['error']);
                $this->redirect($location);
            }

            $saveMail = serialize($_POST['addEmail']);
            $orderName = '';
        } else if ($_POST['REQ_TYPE'] === '9') {
            $this->validationGovernment($_POST, $location);

            $additionalData = [];
            $workCount = count($_POST['gov_works']['name'] ?? []);

            for ($i = 0; $i < $workCount; $i++) {
                $work = [
                    'id' => $_POST['gov_works']['work_id'][$i] ?? '',
                    'name' => $_POST['gov_works']['name'][$i],
                    'object' => $_POST['gov_works']['object'][$i] ?? '',
                    'deadline' => $_POST['gov_works']['deadline'][$i] ?? '',
                    'assigned_id' => $_POST['gov_works']['assigned_id'][$i] ?? '',
                    'departure_date' => $_POST['gov_works']['departure_date'][$i] ?? '',
                    'lab_id' => $_POST['gov_works']['lab_id'][$i] ?? ''
                ];

                $_POST['material'][$i]['id'] = $_POST['gov_works']['material'][$i];
                $_POST['material'][$i]['name'] = $material->getById($_POST['gov_works']['material'][$i])['NAME'];
                $_POST['material'][$i]['count'] = $_POST['gov_works']['quantity'][$i];

                $additionalData[] = $work;
            }

            $saveMail = 'N;';
            $orderName = '';
        }

        $type = $request->getTypeRequest($_POST['REQ_TYPE']);

        $arrAssigned['VALUE'] = array_filter(
            array_slice($_POST['id_assign'], 1),
            fn($value) => !empty($value)
        );

        $dataRequest = [
            'company_id' => $companyId,
            'type' => $_POST['REQ_TYPE'],
            'type_rus' => $type,
            'assigned' => $_POST['id_assign'][0],
            'arrAssigned' => $arrAssigned,
        ];

        if ( !empty($_POST['NUM_DOGOVOR']) ) {
            $contract = $order->getContractById((int)$_POST['NUM_DOGOVOR']);
            if (!empty($contract)) {
                $orderName = $contract['cont'];
            }
        }

        $dataTz = [
            'COMPANY_TITLE' => htmlspecialchars($_POST['company']), //TODO: надо убрать из таблицы это поле
            'COMPANY_ID' => $companyId,
            'TYPE_ID' => $_POST['REQ_TYPE'],
            'CHECK_IP' => isset($_POST['check_ip'])? '1' : '0',
            'SAVE_MAIL' => $saveMail,
            'DOGOVOR_NUM' => $_POST['NUM_DOGOVOR'],
            'DOGOVOR_TABLE' => $orderName,
            'POSIT_LEADS' => $_POST['PositionGenitive'],
            'order_type' => (int)$_POST['order_type'],
            'OBJECT' => $_POST['object'] ?? '',
            'DEADLINE' => $_POST['gov_deadline'] ?? '',
            'ASSIGNED' => $_POST['id_assign'][0] ?? '',
            'organization_id'=>App::getOrganizationId()

        ];


        if ( !empty($_POST['id']) ) { // редактирование
            $dealId = $dataRequest['ID'] = (int)$_POST['id'];

            $currDeal = $request->getDealById($dealId);
            $arrTitle = explode(' ', $currDeal['TITLE']);
            $currTitle = "{$type} {$arrTitle[1]}";
            $dataRequest['title'] = $currTitle;

            $result = $request->update( $dataRequest );

            if ( !$result ) {
                $this->showErrorMessage("Не удалось обновить заявку в битриксе");
                $this->redirect($location);
            }

            if (isset($_POST['material']) && !empty($_POST['material'])) {
                $materialData = $this->processMaterials($_POST['material'], $material, $location);
                $material->setMaterialToRequest($dealId, $materialData['materialDataList'], $additionalData);

                if (!isset($dataTz['MATERIAL']) || empty($dataTz['MATERIAL'])) {
                    $dataTz['MATERIAL'] = implode(', ', $materialData['arrMaterialName']);
                }
            }

            $dataTz['REQUEST_TITLE'] = $currTitle;
            $resultUpdateTz = $request->updateTz($dealId, $dataTz);
            
            if ( empty($resultUpdateTz) ) {
                $this->showErrorMessage("Не удалось обновить заявку");
                $this->redirect($location);
            }
        } else { // создание новой
            $materialData = $this->processMaterials($_POST['material'], $material, $location);
            $materialDataList = $materialData['materialDataList'];
            $arrMaterialName = $materialData['arrMaterialName'];

            $dealId = $request->create( $dataRequest );

            if ( $dealId === false ) {
                $this->showErrorMessage("Не удалось создать заявку в битриксе");
                $this->redirect($location);
            }

            $dealId = (int)$dealId;

            $newDeal = $request->getDealById($dealId);

            $strMaterial = implode(', ', $arrMaterialName);

            $dataTz['REQUEST_TITLE'] = $newDeal['TITLE'];
            $dataTz['MATERIAL'] = $strMaterial;
            $dataTz['STAGE_ID'] = 'NEW';
            $resultAddTz = $request->addTz($dealId, $dataTz);

            if ( empty($resultAddTz) ) {
                $this->showErrorMessage("Не удалось обновить заявку");
                $this->redirect($location);
            }

            $material->setMaterialToRequest($dealId, $materialDataList, $additionalData);
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
            'LABA_ID' => $labaIdStr
        ];

        $request->updateTz($dealId, $updateData);

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
    public function card($dealId)
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
        /** @var Organization $organizationModel */
        $organizationModel = $this->model('Organization');
        
        $tzId = $requirement->getTzIdByDealId($dealId);
        $deal = $request->getDealById($dealId);
        $companyData = $company->getById($deal['COMPANY_ID']);
        $requestData = $request->getTzByDealId($dealId);
        $isExistTz = $requirement->isExistTz($dealId);
        
        $typeId = $requestData['TYPE_ID'];
        $config = isset($this->requestTypeConfig[$typeId]) ? $this->requestTypeConfig[$typeId] : $this->requestTypeConfig['default'];
        
        $this->prepareBaseData($request, $company, $user, $requirement, $dealId, $deal, $companyData, $requestData, $tzId);
        
        $actVr = $requirement->getActVr($tzId);
        $userFields = $request->getCrmUserFields($dealId);
        $isConfirm = $request->isConfirmTz($tzId);
        $protocolData = $requirement->getProtocols($tzId);
        $isResults = $resultModel->isResultNotEmpty($dealId);
        $permissionInfo = $permissionModel->getUserPermission($_SESSION['SESS_AUTH']['USER_ID']);
        $actBase = $requirement->getActBase($dealId);
        
        $orderData = $config['blocks']['order'] ? $requirement->getContracts($dealId) : [];
        $dogovorData = $config['blocks']['order'] ? $requirement->getDogovor($dealId) : [];
        $invoiceData = $config['blocks']['invoice'] ? $request->getInvoice($tzId) : [];
        $proposalData = $config['blocks']['proposal'] ? $requirement->getKP($tzId) : [];
        $tzDoc = $config['blocks']['order'] ? $requirement->getTzDoc($tzId) : [];
        
        $this->data['request'] = $requestData;
        $this->data['doc_id'] = $dogovorData['ID'] ?? '';
        $this->data['attach1'] = $dogovorData['ACTUAL_VER'] ?? '';
        $this->data['attach2'] = $invoiceData['ACTUAL_VER'] ?? '';
        $this->data['attach3'] = $tzDoc['ACTUAL_VER'] ?? '';
        $this->data['material_data'] = $requirement->getMaterialProbeGostToRequest($dealId);
        $this->data['result_refactoring_start_id'] = $request->getResultRefactoringStartId();
        $this->data['contract'] = $orderData;
        $this->data['dogovor'] = $dogovorData;
        $this->data['act_vr'] = $actVr;
        $this->data['tz_doc'] = $tzDoc;
        $this->data['first_protocol'] = $protocolData[0] ?? [];
        $this->data['date_tz_create'] = date('d.m.Y', strtotime($deal['DATE_CREATE']));

        $this->data['method_list'] = $resultModel->getMaterialsGostsByDelaId($dealId);
        
        if ($config['blocks']['tz']) {
            $this->prepareTzData($request, $requestData, $tzId, $userFields, $isExistTz);
        }
        
        if ($config['blocks']['proposal']) {
            $this->prepareProposalData($requirement, $proposalData, $requestData, $tzId, $dealId, $isExistTz, $actVr);
        }
        
        if ($config['blocks']['order']) {
            $this->prepareOrderData($dealId, $orderData, $dogovorData, $requestData, $actVr, $tzId, $tzDoc, $isExistTz, $isConfirm);
        }
        
        if ($config['blocks']['invoice']) {
            $this->prepareInvoiceData($invoiceData, $requestData, $dealId, $tzId, $isExistTz, $isConfirm);
        }
        
        if ($config['blocks']['payment']) {
            $this->preparePaymentData($requestData, $isExistTz);
        }
        
        if ($config['blocks']['sample']) {
            $this->prepareSampleData($requestData, $actBase, $actVr);
        }
        
        if ($config['blocks']['protocol']) {
            $this->prepareProtocolData($protocolData, $protocolModel, $requirement, $requestData);
        }
        
        if ($config['blocks']['results']) {
            $this->prepareResultsData($resultModel, $requestData, $dealId, $isResults);
        }
        
        if ($config['blocks']['complete']) {
            $this->prepareCompleteData($permissionInfo, $deal, $requestData);
        }
        
        if ($config['blocks']['act_complete']) {
            $this->prepareActCompleteData($actVr, $requestData, $company->getRequisiteByCompanyId($deal['COMPANY_ID']), $organizationModel, $deal);
        }
        
        if ($config['blocks']['files']) {
            $this->prepareFilesData($request, $protocolData, $requestData, $dealId, $tzId, $dogovorData, $actVr, $proposalData, $invoiceData);
        }

        $this->addCSS("/assets/plugins/dropzone/css/basic.css");
        $this->addCSS("/assets/plugins/dropzone/dropzone3.css");
        $this->addJS("/assets/plugins/dropzone/dropzone3.js");
        $r = rand();
        $this->addJs("/assets/js/request-card.js?v={$r}");
        
        if (!empty($requestData['TAKEN_ID_DEAL'])) {
            $this->view('card_taken');
        } else {
            $this->view($config['template']);
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

        $dataTz['REQUEST_TITLE'] = $newDeal['TITLE'];

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
        $request->updateTz($dealId, ['dateEnd' => $today]);

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

        $this->data['type_request'] = 'gov';
        if ((isset($_GET['type_request']) && $_GET['type_request'] == 'commercial')) {
            $this->data['type_request'] = 'comm';
        }

//        $this->data['tz_under_consideration'] = [];
//        $this->data['probe_in_lab'] = [];
//        $this->data['confirm_not_account'] = [];
//
//        if ( in_array($_SESSION['SESS_AUTH']['USER_ID'], [11, 13, 15, 58]) ) {
//            $this->data['tz_under_consideration'] = $request->tzUnderConsideration($_SESSION['SESS_AUTH']['USER_ID']);
//            $this->data['probe_in_lab'] = $request->probeInLab($_SESSION['SESS_AUTH']['USER_ID']);
//            $this->data['probe_in_lab_payed'] = $request->probeInLabPayed($_SESSION['SESS_AUTH']['USER_ID']);
//            $this->data['request_list_not_assigned'] = $request->getRequestListNoSetAssigned($_SESSION['SESS_AUTH']['USER_ID']);
//        }
//        if ( in_array($_SESSION['SESS_AUTH']['USER_ID'], [62, 83, 17]) ) {
//            $this->data['confirm_not_account'] = $request->getConfirmNotAccountTz();
//        }

        $r = rand();
        $this->addJs("/assets/js/journal2.js?v={$r}");

        $this->view('list', '', 'template_journal');
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

        $idDeal = (int)$idDeal;

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

        $idDeal = (int)$_POST['deal_id'];
        $pay = (float)$_POST['pay'];

        if ( empty($idDeal) ) {
            $this->showErrorMessage('Не указан, или указан неверно ИД заявки');
            $this->redirect("/request/list/");
        }

		if ( !in_array(App::getUserId(), [25, 88, 61]) && ($pay <= 0) ) {
            $this->showErrorMessage('Оплата не может быть меньше или равна нулю');
            $this->redirect("/request/card/{$idDeal}");
        }

        $request->addPay($idDeal, $pay, "'" . date("d.m.Y", strtotime($_POST['payDate'])) . "'");
        $request->addMessage($idDeal, $pay);

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

        $filter = $request->prepareFilter($_POST ?? []);

        if ( $_POST['type_journal'] === 'gov' ) {
            $filter['search']['TYPE_ID'] = 9; // 9 - ид типа заявки Гос работы
        }

        $data = $request->getDataToJournalRequests($userId, $filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
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

        $companyId = (int)$_POST['company_id'];

        if ( isset($companyId) && !empty($companyId) ) {
            /** @var Company $company */
            $company = $this->model('Company');
            $response = $company->getRequisiteByCompanyId($companyId);
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

        $companyId = (int)$_POST['company_id'];

        if ( isset($companyId) ) {
            /** @var Request $request */
            $request = $this->model('Request');
            $response = $request->getContractsByCompanyId($companyId);
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

        $dealId = (int)$dealId;

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

        echo json_encode($company->getCompanyIdByInn($_POST['INN']));
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

		echo json_encode($company->getCompanyByInn($_POST['INN']), JSON_UNESCAPED_UNICODE);
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

        echo json_encode($company->getByInnFromBx($_POST['INN']), JSON_UNESCAPED_UNICODE);
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

    /**
     * @desc Обрабатывает материалы
     * @param $materialPost
     * @param $material
     * @param $location
     * @return array
     */
    private function processMaterials(array $materialPost, $material, string $location): array
    {
        $arrMaterialName = [];
        $materialDataList = [];

        foreach ($materialPost as $item) {
            $arrMaterialName[] = $item['name'];
            if (empty($item['id'])) {
                $resultAddMaterial = $material->add($item['name']);

                if (empty($resultAddMaterial)) {
                    $this->showErrorMessage("Не удалось создать материал '{$item['name']}'");
                    $this->redirect($location);
                } else {
                    $item['id'] = $resultAddMaterial;
                }
            }
            $materialDataList[] = $item;
        }

        return [
            'arrMaterialName' => $arrMaterialName,
            'materialDataList' => $materialDataList
        ];
    }

    /**
     * @desc Валидация общих полей для всех заявок
     * @param $post
     * @param $location
     */
    private function validationForAll(array $post, string $location): void
    {
        // Проверка поля организации/компании (общая для всех типов)
        $valid = $this->validateField($post['company'], "Клиент");
        if ( !$valid['success'] ) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Назначенные ответственные
        if (isset($post['ASSIGNED'])) {
            foreach ($post['ASSIGNED'] as $k => $ass) {
                $_SESSION['request_post']['assign'][$k]['user_name'] = $ass;
            }
        }

        if (isset($post['id_assign'])) {
            foreach ($post['id_assign'] as $k => $ass) {
                if (empty($ass)) { continue; }
                $_SESSION['request_post']['assign'][$k]['user_id'] = $ass;
            }
        }

        $valid = $this->validateAssigned($post['ASSIGNED'] ?? []);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }
    }

    /**
     * @desc Валидация для заявок SALE
     * @param $post
     * @param $location
     */
    private function validationSale(array $post, string $location): void
    {
        // Материал для исследования
        if (isset($post['material'])) {
            foreach ($post['material'] as $item) {
                $valid = $this->validateField($item['name'], "Материал для исследования");
                if (!$valid['success']) {
                    $this->showErrorMessage($valid['error']);
                    $this->redirect($location);
                }
            }
        }

        // E-mail *
        $valid = $this->validateEmail($post['POST_MAIL']);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Телефон *
        $valid = $this->validateField($post['PHONE'], "Телефон");
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Полное наименование компании
        $valid = $this->validateField($post['CompanyFullName'], "Полное наименование компании", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ИНН
        if (strlen($post['INN']) !== 0 && strlen($post['INN']) !== 10 && strlen($post['INN']) !== 12) {
            $l = strlen($post['INN']);
            $this->showErrorMessage("В поле ИНН введено {$l} символов. Должно быть 10 или 12");
            $this->redirect($location);
        }
        $valid = $this->validateNumber($post['INN'], "ИНН", false, 12);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ОГРН
        $valid = $this->validateNumber($post['OGRN'], "ОГРН", false, 13);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['ADDR'], "Адрес", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['ACTUAL_ADDRESS'], "Фактический адрес", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['mailingAddress'], "Почтовый адрес", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateEmail($post['EMAIL'], false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Дополнительный E-mail
        if (isset($post['addEmail'])) {
            foreach ($post['addEmail'] as $item) {
                $valid = $this->validateEmail($item, false);
                if (!$valid['success']) {
                    $this->showErrorMessage($valid['error']);
                    $this->redirect($location);
                }
            }
        }

        // Контактное лицо
        $valid = $this->validateNumber($post['company_id'], "Контактное лицо", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // КПП
        $valid = $this->validateNumber($post['KPP'], "КПП", false, 9);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['Position2'], "Должность руководителя", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['PositionGenitive'], "Должность руководителя в родительном падеже", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['DirectorFIO'], "ФИО руководителя", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['RaschSchet'], "Расчетный счет", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['KSchet'], "Кор. счёт", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['l_schet'], "Лицевой счёт", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['BIK'], "БИК", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['BankName'], "Наименование банка", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }
    }

    /**
     * @desc Валидация для заявок гос. работа
     * @param $post
     * @param $location
     */
    private function validationGovernment(array $post, string $location): void
    {
        $valid = $this->validateField($post['object'], "Объект", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        $valid = $this->validateField($post['gov_deadline'], "Срок заявки", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Проверка наличия хотя бы одной работы
        if (empty($post['gov_works']) || !isset($post['gov_works']['name']) || count($post['gov_works']['name']) === 0) {
            $this->showErrorMessage("Необходимо добавить хотя бы одну работу");
            $this->redirect($location);
        }

        // Проверка заполненности обязательных полей в работах
        foreach ($post['gov_works']['name'] as $key => $name) {
            if (empty($name)) {
                $this->showErrorMessage("Поле 'Наименование работы' обязательно для заполнения");
                $this->redirect($location);
            }

            if (empty($post['gov_works']['material'][$key])) {
                $this->showErrorMessage("Поле 'Материал' обязательно для заполнения");
                $this->redirect($location);
            }

            if (empty($post['gov_works']['quantity'][$key])) {
                $this->showErrorMessage("Поле 'Кол-во' обязательно для заполнения");
                $this->redirect($location);
            }

            if (empty($post['gov_works']['deadline'][$key])) {
                $this->showErrorMessage("Поле 'Сроки' обязательно для заполнения");
                $this->redirect($location);
            }

            if (empty($post['gov_works']['assigned_id'][$key])) {
                $this->showErrorMessage("Поле 'Ответственный' обязательно для заполнения");
                $this->redirect($location);
            }

            if (empty($post['gov_works']['lab_id'][$key])) {
                $this->showErrorMessage("Поле 'Испытания в лаборатории' обязательно для заполнения");
                $this->redirect($location);
            }
        }
    }

    /**
     * @desc Возвращает классы для отображения блоков в зависимости от типа заявки
     * @return array Массив с классами для каждого типа блока
     */
    private function getDisplayClass(): array
    {
        $displayClass = [
            'gov' => 'visually-hidden',
            'sale' => 'visually-hidden',
            'sale_materials' => 'visually-hidden'
        ];

        if (isset($this->data['request']['REQ_TYPE'])) {
            $reqType = $this->data['request']['REQ_TYPE'];

            if ($reqType === '9') {
                $displayClass['gov'] = '';
                $displayClass['sale'] = 'visually-hidden';
                $displayClass['sale_materials'] = 'visually-hidden';
            } elseif ($reqType === 'SALE') {
                $displayClass['gov'] = 'visually-hidden';
                $displayClass['sale'] = '';

                if (isset($this->data['request']['id'])) {
                    $displayClass['sale_materials'] = 'visually-hidden';
                } else {
                    $displayClass['sale_materials'] = '';
                }
            }
        }

        return $displayClass;
    }

    /**
     * Подготовка базовых данных заявки
     */
    private function prepareBaseData($request, $company, $user, $requirement, $dealId, $deal, $companyData, $requestData, $tzId)
    {
        $this->data['title'] = "Карточка заявки";
        $this->data['deal_id'] = $dealId;
        $this->data['tz_id'] = $tzId;
        
        $companyDataRequisite = $company->getRequisiteByCompanyId($deal['COMPANY_ID']);
        $userFields = $request->getCrmUserFields($dealId);
        
        if ($requestData['TYPE_ID'] != '9') {
            $this->data['comm'] = '?type_request=commercial';
        }
        
        $this->data['is_deal_nk'] = $deal['TYPE_ID'] == 4;
        $this->data['is_deal_pr'] = $deal['TYPE_ID'] == 7;
        $this->data['is_deal_sc'] = $deal['TYPE_ID'] == 8;
        $this->data['is_deal_osk'] = $deal['TYPE_ID'] == 'COMPLEX';
        $this->data['is_government'] = $deal['TYPE_ID'] == '9';
        
        $this->data['stage'] = $request->getStage($requestData);
        $this->data['deal_title'] = $deal['TITLE'];
        
        $this->data['is_complete'] = !($deal['STAGE_ID'] != '2' && $deal['STAGE_ID'] != '3' && $deal['STAGE_ID'] != '4' && $deal['STAGE_ID'] != 'WON');
        $this->data['is_may_change'] = in_array($_SESSION['SESS_AUTH']['USER_ID'], [1, 10, 35, 62, 9, 11, 58, 34, 43, 61, 13, 7, 15]);
        $this->data['is_end_test'] = $deal['STAGE_ID'] == 2 || $deal['STAGE_ID'] == 4 || $deal['STAGE_ID'] == 'WON';
        
        $this->data['user']['name'] = $_SESSION['SESS_AUTH']['NAME'];
        
        $this->data['company_title'] = $deal['COMPANY_TITLE'];
        $this->data['company_id'] = $deal['COMPANY_ID'];
        $this->data['is_managers'] = in_array($_SESSION['SESS_AUTH']['USER_ID'], [62, 83, 61, 17]);
        
        $this->data['is_good_company'] = $companyData[COMPANY_GOOD] == 1;
        
        $assignedsList = $user->getAssignedByDealId($dealId, true);
        
        $assignedNames = [];
        foreach ($assignedsList as $assigned) {
            $assignedNames[] = $assigned['short_name'];
        }
        $this->data['assigned'] = implode(', ', $assignedNames);
        $this->data['main_assigned'] = $assignedsList[0]['short_name'];

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
        
        $userFiles = $request->getFilesFromDir(UPLOAD_DIR . "/request/{$dealId}");
        $this->data['user_files'] = [];
        foreach ($userFiles as $file) {
            $imgLinc = URI.'/assets/images/unknown.png';
            $patternImg = "/\.(png|jpg|jpeg)$/i";
            if (preg_match($patternImg, $file)) {
                $imgLinc = URI."/upload/request/{$dealId}/{$file}";
            }
            $patternPdf = "/\.(pdf)$/i";
            if (preg_match($patternPdf, $file)) {
                $imgLinc = URI."/assets/images/pdf.png";
            }
            
            $this->data['user_files'][] = [
                'name' => $file,
                'img' => $imgLinc
            ];
        }
    }
    
    /**
     * Подготовка данных ТЗ
     */
    private function prepareTzData($request, $requestData, $tzId, $userFields, $isExistTz)
    {
        // ТЗ
        $this->data['tz']['tz_link'] = URI."/requirement/card_new/{$requestData['ID']}";
        $this->data['tz']['check'] = !empty($requestData['ID']) && (!empty($requestData['TZ']) || $isExistTz);
        $this->data['tz']['number'] = $requestData['ID'] ?? '';
        $this->data['tz']['date'] = !empty($requestData['DATE_SOZD'])? StringHelper::dateRu($requestData['DATE_SOZD']) : '--';
        $this->data['tz']['date_send'] = $userFields['UF_CRM_1579541506559']['VALUE'] ? StringHelper::dateRu($userFields['UF_CRM_1579541506559']['VALUE']) : "Не отправлено";
    }
    
    /**
     * Подготовка данных коммерческого предложения
     */
    private function prepareProposalData($requirement, $proposalData, $requestData, $tzId, $dealId, $isExistTz, $actVr)
    {
        // Коммерческое предложение
        $this->data['proposal']['why_disable_mail'] = '';
        if ( empty($proposalData) ) {
            $this->data['proposal']['why_disable_mail'] .= 'Не сформировано коммерческое предложение. ';
        }
        if ( !empty($actVr) ) {
            $this->data['proposal']['why_disable_mail'] .= 'Создан акт выполненных работ.';
        }

        $this->data['proposal']['link'] = "/ulab/generator/CommercialOffer/{$dealId}";
        $this->data['proposal']['check'] = !empty($proposalData['ID']);
        $this->data['proposal']['number'] = $proposalData['ID'] ?? 'Не сформировано';
        $this->data['proposal']['date'] = !empty($proposalData['DATE'])? StringHelper::dateRu($proposalData['DATE']) : '--';
        $this->data['proposal']['date_send'] = !empty($proposalData['SEND_DATE'])? StringHelper::dateRu($proposalData['SEND_DATE']) : 'Не отправлено';
        $this->data['proposal']['is_disable_form'] = !$isExistTz;
        $this->data['proposal']['is_disable_mail'] = empty($proposalData) || !empty($actVr);
        $this->data['proposal']['attach'] = $proposalData['ACTUAL_VER'];
        $this->data['proposal']['test'][] = empty($requestData['TAKEN_ID_DEAL']) || 0;
        $this->data['proposal']['test'][] = empty($requestData['TZ']) || 0;
        $this->data['proposal']['test'][] = !empty($requestData['dateEnd']);
    }
    
    /**
     * Подготовка данных договора и приложения к договору
     */
    private function prepareOrderData($dealId, $orderData, $dogovorData, $requestData, $actVr, $tzId, $tzDoc, $isExistTz, $isConfirm)
    {
        // Договор
        if (!empty($orderData) && $dogovorData['IS_ACTION'] == 0) {
            $this->data['order']['check'] = 'table-red';
        } elseif (!empty($orderData) && empty($dogovorData['PDF'])) {
            $this->data['order']['check'] = 'table-yellow';
        } elseif (!empty($orderData) && !empty($dogovorData['PDF'])) {
            $this->data['order']['check'] = 'table-green';
        }
        
        if (!empty($dogovorData)) {
            $this->data['order']['is_generated'] = true;
            $this->data['order']['number'] = $dogovorData['NUMBER'];
            $this->data['order']['id'] = $dogovorData['ID'];
        } else {
            $this->data['order']['is_generated'] = false;
        }
        
        $this->data['order']['date'] = !empty($dogovorData['DATE'])? StringHelper::dateRu($dogovorData['DATE']) : '--';
        $this->data['order']['attach'] = $dogovorData['ACTUAL_VER'];
        $this->data['order']['date_send'] = !empty($dogovorData['SEND_DATE'])? StringHelper::dateRu($dogovorData['SEND_DATE']) : 'Не отправлялся из ЛИС';
        $this->data['order']['is_disable_form'] =
            !empty($actVr) || !empty($requestData['dateEnd']) || !empty($requestData['DOGOVOR_NUM']);
        $this->data['order']['is_disable_mail'] = empty($dogovorData) || 0;
        
        // Приложение к договору
        $this->data['attach']['link'] = "/ulab/generator/TechnicalSpecification/{$dealId}";
        $this->data['attach']['number'] = $tzDoc['TZ_ID'] ?? 'Не сформировано';
        $this->data['attach']['date'] = !empty($tzDoc['DATE'])? StringHelper::dateRu($tzDoc['DATE']) : '--';
        $this->data['attach']['date_send'] = !empty($tzDoc['SEND_DATE'])? StringHelper::dateRu($tzDoc['SEND_DATE']) : 'Не отправлен';
        $this->data['attach']['actual_ver'] = $tzDoc['ACTUAL_VER'];
        $this->data['attach']['is_disable_form'] = !$this->data['is_deal_osk'] && (
			!$isExistTz || !empty($requestData['dateEnd']) || !$isConfirm || !empty($actVr));
		$this->data['attach']['is_disable_form_test'] = !$this->data['is_deal_osk'];
        $this->data['attach']['is_disable_mail'] = empty($tzDoc) || 0;

        if (!empty($tzDoc) && $dogovorData['IS_ACTION'] == 0) {
            $this->data['attach']['check'] = 'table-red';
        } elseif (!empty($tzDoc) && empty($tzDoc['pdf'])) {
            $this->data['attach']['check'] = 'table-yellow';
        } elseif (!empty($tzDoc['pdf'])) {
            $this->data['attach']['check'] = 'table-green';
        }
    }
    
    /**
     * Подготовка данных счета
     */
    private function prepareInvoiceData($invoiceData, $requestData, $dealId, $tzId, $isExistTz, $isConfirm)
    {
        $this->data['invoice']['link'] = "/protocol_generator/account_new.php?ID={$dealId}&TZ_ID={$requestData['ID']}";
        $this->data['invoice']['check'] = !empty($invoiceData);
        $this->data['invoice']['number'] = !empty($invoiceData['ID']) ? $requestData['ACCOUNT'] : 'Не сформирован';
        $this->data['invoice']['date'] = !empty($invoiceData['DATE'])? StringHelper::dateRu($invoiceData['DATE']) : '--';
        $this->data['invoice']['date_send'] = !empty($invoiceData['SEND_DATE'])? StringHelper::dateRu($invoiceData['SEND_DATE']) : 'Не отправлен';
        $this->data['invoice']['is_disable_form'] = false && !$this->data['is_deal_osk'] && (
            !$isExistTz || empty($requestData['DOGOVOR_NUM']) || !empty($requestData['dateEnd']) || !empty($requestData['TAKEN_ID_DEAL']) || !$isConfirm);
        $this->data['invoice']['is_disable_mail'] = empty($invoiceData) || 0;
        $this->data['invoice']['attach'] = $invoiceData['ACTUAL_VER'];
    }
    
    /**
     * Подготовка данных оплаты
     */
    private function preparePaymentData($requestData, $isExistTz)
    {
        $this->data['payment']['surcharge'] = (float)$requestData['price_discount'] - (float)$requestData['OPLATA'];
        
        $this->data['payment']['check'] = !empty($requestData['price_discount']) && $isExistTz && $this->data['payment']['surcharge'] == 0;
        $this->data['payment']['is_disable_form'] = ((float)$requestData['OPLATA'] == (float)$requestData['price_discount']) && ($_SESSION['SESS_AUTH']['USER_ID'] != 25);
        $this->data['payment']['price'] = $requestData['price_discount'];
        $this->data['payment']['pay']   = $requestData['OPLATA'];
        $this->data['payment']['datePayment'] =
            !empty($requestData['DATE_OPLATA']) && $requestData['DATE_OPLATA'] !== '01.01.1970' ?
                StringHelper::dateRu($requestData['DATE_OPLATA']) : '--';
        
        if ((float) $requestData['OPLATA'] > (float) $requestData['PRICE']) {
            $this->data['payment']['status'] = 'Переплата';
        } elseif (!empty($requestData['PRICE']) && $this->data['payment']['surcharge'] == 0) {
            $this->data['payment']['status'] = 'Оплачено полностью';
        } elseif (!empty($requestData['OPLATA']) && !empty($requestData['PRICE'])) {
            $this->data['payment']['status'] = 'Оплачено частично';
        } else {
            $this->data['payment']['status'] = 'Не оплачено';
        }
    }
    
    /**
     * Подготовка данных акта приемки проб
     */
    private function prepareSampleData($requestData, $actBase, $actVr)
    {
        $this->data['sample']['link'] = "/ulab/generator/actSampleDocument/{$this->data['deal_id']}";
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
        $this->data['sample']['file_dir'] = "/protocol_generator/archive_sample/{$this->data['deal_id']}/";
        
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive_sample/{$this->data['deal_id']}/";
        if (is_dir($uploadDir)) {
            $files = glob($uploadDir . "*.pdf");
            if (!empty($files)) {
                $fileName = basename($files[0]);
                $this->data['sample']['file_name'] = $this->truncateFileName($fileName, 25);
                $this->data['sample']['file_url'] = $this->data['sample']['file_dir'] . $fileName;
                $this->data['sample']['has_file'] = true;
            } else {
                $this->data['sample']['has_file'] = false;
            }
        } else {
            $this->data['sample']['has_file'] = false;
        }
    }
    
    /**
     * Подготовка данных протоколов
     */
    private function prepareProtocolData($protocolData, $protocolModel, $requirement, $requestData)
    {
        $this->data['protocol'] = [];
        foreach ($protocolData as $protocol) {
            $title = 'Не сформирован';
            if (!empty($protocol['NUMBER'])) {
                $date = strtotime($protocol['DATE']);
                $year = (int)date("Y", $date)%10 ? substr(date("Y", $date), -2) : date("Y", $date);
                $title = "{$protocol['NUMBER']}/{$year}";
            }
            
            $yearDir = date("Y", strtotime($protocol['DATE']));
            $dir  = PROTOCOL_PATH . "archive/{$requestData['ID']}{$yearDir}/{$protocol['ID']}/";
            $link = "/ulab/generator/ProtocolDocument/{$protocol['ID']}";
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
            ];
        }

        $this->data['protocol_modal'] = $requirement->getWorkProtocolFiles($requestData['ID_Z']);

        $this->data['empty_protocol_files'] = false;
        foreach ($this->data['protocol_modal'] as $protocol) {
            if (!empty($protocol['protocol_file_path'])) {
                $this->data['empty_protocol_files'] = true;
                break;
            }
        }

        $this->data['protocol_modal_check'] = !empty($requestData['PROTOCOL_SEND_DATE']);
    }
    
    /**
     * Подготовка данных результатов испытаний
     */
    private function prepareResultsData($resultModel, $requestData, $dealId, $isResults)
    {
        $this->data['results']['check'] = $isResults;
        $this->data['results']['is_disabled'] = false;
        
        if (!empty($requestData['DATE_RESULTS'])) {
            $this->data['results']['date'] = StringHelper::dateRu($requestData['DATE_RESULTS']);
        } elseif (!empty($requestData['DATE_P'])) {
            $this->data['results']['date'] = StringHelper::dateRu($requestData['DATE_P']);
        } else {
            $this->data['results']['date'] = '--';
        }
        $this->data['results']['test'] = (!$this->data['is_deal_pr'] && !$this->data['is_deal_osk'] && !$this->data['is_deal_sc'] && empty($requestData['TAKEN_ID_DEAL'])) &&
            (empty($requestData) || empty($requestData['ACT_NUM']) || empty($invoiceData));
    }
    
    /**
     * Подготовка данных завершения испытаний
     */
    private function prepareCompleteData($permissionInfo, $deal, $requestData)
    {
        $this->data['complete']['check'] = false;
        $this->data['complete']['date'] = $requestData['dateEnd'] ? StringHelper::dateRu($requestData['dateEnd']) : '--';
        $this->data['complete']['is_disabled'] = false && empty($this->data['protocol']) || 0;
        // Проверка на доступ к ручному завершению испытаний (может завершить Руководитель ИЦ и Админ)
        $this->data['complete']['may_return'] = true || in_array($permissionInfo['id'], [ADMIN_PERMISSION, HEAD_IC_PERMISSION]);
        $this->data['complete']['may_complete'] = true || in_array($permissionInfo['id'], [ADMIN_PERMISSION, HEAD_IC_PERMISSION]) &&
            ($deal['STAGE_ID'] == 'PREPARATION' || $deal['STAGE_ID'] == 'PREPAYMENT_INVOICE' ||
                $deal['STAGE_ID'] == 'EXECUTING'|| $deal['STAGE_ID'] == 'FINAL_INVOICE' || $deal['STAGE_ID'] == 1);
    }
    
    /**
     * Подготовка данных акта выполненных работ
     */
    private function prepareActCompleteData($actVr, $requestData, $companyDataRequisite, $organizationModel, $deal)
    {
        $this->data['act_complete']['check'] = !empty($actVr);
        $this->data['act_complete']['email'] = !empty($requestData['acc_email']) ? $requestData['acc_email'] : $companyDataRequisite['RQ_EMAIL'];
        $this->data['act_complete']['number'] = $actVr['NUMBER'];
        $this->data['act_complete']['attach'] = $actVr['ACTUAL_VER']?? '';
        $this->data['act_complete']['date'] = !empty($actVr['DATE'])? StringHelper::dateRu($actVr['DATE']) : '--';
        $this->data['act_complete']['date_send'] = !empty($actVr['SEND_DATE'])? StringHelper::dateRu($actVr['SEND_DATE']) : 'Не отправлен';
        $this->data['act_complete']['is_disable_form'] = false || 0;
        $this->data['act_complete']['is_disable_mail'] = empty($actVr) || 0;
        //TODO: пока ид организации задано жестко 1. потом переделать на получение к какой организации принадлежит заявка
        $this->data['act_complete']['assigned_users'] = $organizationModel->getAllLeaders(1);
    }


    /**
     * Подготовка данных списка версий файлов
     */
    private function prepareFilesData($request, $protocolData, $requestData, $dealId, $tzId, $dogovorData, $actVr, $proposalData, $invoiceData)
    {
        //// Список версий
        // Протокол
        $this->data['file']['protocol'] = [];
        if (!empty($protocolData)) {
            foreach ($protocolData as $key => $protocol) {
                if (empty($protocol['PROTOCOL_OUTSIDE_LIS'])) {
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
        if (!empty($proposalData)) {
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
        if (!empty($dogovorData)) {
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
        if (!empty($dogovorData)) {
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
        $dir  = PROTOCOL_PATH . "archive_tz/{$requestData['ID']}/";
        $path = "/protocol_generator/archive_tz/{$requestData['ID']}/";
        $files = $request->getFilesFromDir($dir);
        
        $this->data['file']['tz']['dir'] = $path;
        foreach ($files as $file) {
            $this->data['file']['tz']['files'][] = $file;
        }
        
        // Счет
        $this->data['file']['invoice']['files'] = [];
        if (!empty($invoiceData)) {
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
        if (!empty($actVr)) {
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
    }

    public function uploadFileAjax(int $dealId)
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $fileType = $_POST['fileType'];
        $file = $_FILES['file'];

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive_" . $fileType . "/" . $dealId . "/";
        $webPath = "/protocol_generator/archive_" . $fileType . "/" . $dealId . "/";
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $existingFiles = glob($uploadDir . '*');
        foreach ($existingFiles as $existingFile) {
            if (is_file($existingFile)) {
                unlink($existingFile);
            }
        }

        $result = $requestModel->saveFile($uploadDir, $file['name'], $file['tmp_name']);

        if ($result['success']) {
            $response = [
                'success' => true,
                'fileName' => $file['name'],
                'fileUrl' => $webPath . $file['name'],
            ];
        } else {
            $response = [
                'success' => false,
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param int $dealId ID сделки
     */
    public function deleteFileAjax(int $dealId)
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $fileType = $_POST['fileType'] ?? '';
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive_{$fileType}/{$dealId}/";
        $files = glob($uploadDir . "*.pdf");
        
        if (!empty($files) && isset($files[0]) && is_file($files[0])) {
            if (unlink($files[0])) {
                echo json_encode([
                    'success' => true,
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => false,
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode([
                'success' => false,
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Обрезает имя файла до заданной длины, сохраняя расширение
     * @param string $fileName Имя файла
     * @param int $maxLength Максимальная длина
     * @return string Обрезанное имя файла
     */
    private function truncateFileName($fileName, $maxLength = 25) 
    {
        if (mb_strlen($fileName) <= $maxLength) {
            return $fileName;
        }
        
        $lastDotIndex = mb_strrpos($fileName, '.');
        $extension = $lastDotIndex !== false ? mb_substr($fileName, $lastDotIndex) : '';
        
        $nameLength = $maxLength - 3 - mb_strlen($extension);
        if ($nameLength <= 0) {
            return mb_substr($fileName, 0, $maxLength - 3) . '...';
        }
        
        $name = mb_substr($fileName, 0, $lastDotIndex !== false ? $lastDotIndex : mb_strlen($fileName));
        return mb_substr($name, 0, $nameLength) . '...' . $extension;
    }

    /**
     * Создает архив протоколов
     */
    public function createProtocolsArchive()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $data = [
            'filePaths' => $_POST['files'],
            'title' => $_POST['title']
        ];

        $requestModel->createProtocolsArchive($data);
    }

    /**
     * Обновляет статус заявки
     */
    public function updateApplicationStageAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $stageId = !empty($_POST['stage_id']) ? $_POST['stage_id'] : '';
        $tzId = !empty($_POST['tz_id']) ? (int)$_POST['tz_id'] : 0;

        $result = $requestModel->updateApplicationStage($stageId, $tzId);
        
        if ($result) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Не удалось обновить статус сделки'
            ]);
        }
    }

    /**
     * Обновляет статус протокола
     */
    function updateProtocolStatusAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $requestModel->updateProtocolStatus($_POST['deal_id']);
    }
}