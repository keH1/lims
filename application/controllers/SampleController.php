<?php

/**
 * Класс контроллер для Техзадания
 * Class SampleController
 */
class SampleController extends Controller
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
     * route /sample/card_new/{$tzId}
     * @param $tzId
     * страница технического задания
     */
    public function card_new($tzId)
    {
        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }

        $this->data['title'] = 'Прием и регистрация проб';

        /** @var User $user */
        $user = $this->model('User');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');
        /** @var Sample $sample */
        $sample = $this->model('Sample');
        /** @var Probe $probeModel */
		$probeModel = $this->model('Probe');

        //// получение данных
        $tzData = $sample->getTzByTzId((int)$tzId);


        $dealId = (int) $tzData['ID_Z'];

        if (empty($tzData)) {
            $this->showErrorMessage("Технического задания с ИД {$tzId} не существует");
            $this->redirect('/request/list/');
        }

        $dealData = $request->getDealById($dealId);

        if ( empty($dealData) ) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        $contractData = $sample->getContractByDealId($dealId);
        $actData = $sample->getActBase($dealId);

        //// заполнение селектов
        // Объект строительства
        $this->data['objects'] = $sample->getObjects();
        // Методики
		$materialData = $sample->getMaterialProbeGostToRequest($tzData['ID_Z']);
        // Материалы (объект испытаний)
        $this->data['material_list'] = $materialModel->getList();
        // Заявка учтена
        $this->data['requests_to_company'] = $sample->getRequestsToCompany($dealId, $dealData['COMPANY_ID']);

        //Акты приемки
		$this->data['probe_acts'] = $probeModel->getActBaseByDealId($dealId);

        //// общая информация
        // Основание для проведения испытаний (договор)
        $this->data['contract_number'] = $contractData['NUMBER'] ?? '';
        $this->data['contract_date'] = $contractData['DATE'] ?? '';
        $this->data['contract_type'] = $contractData['CONTRACT_TYPE'] ?? 'Договор';
        $this->data['deal_id'] = $dealId;
        $this->data['tz_id'] = $tzId;
        $this->data['curr_user'] = $_SESSION['SESS_AUTH']['USER_ID'];
        $this->data['deal_title'] = $tzData['REQUEST_TITLE'];

        // Основание для формирования протокола (акт приемки проб)
        $this->data['act_number'] = $actData['ACT_NUM'] ?? '';
        $this->data['act_date'] = ! empty($actData['ACT_DATE']) && $actData['ACT_DATE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actData['ACT_DATE'])) : '';

        // Описание объекта
        // $this->data['tz'] = $tzData;

        //// доп информация
        $this->data['probe_place'] = ! empty($actData['PLACE_PROBE']) ? $actData['PLACE_PROBE'] : 'Пробы не поступили';
        $this->data['probe_made'] = ! empty($actData['PROBE_PROIZV']) ? $actData['PROBE_PROIZV'] : 'Пробы не поступили';
        $this->data['probe_date'] = ! empty($actData['DATE_PROBE']) && $actData['DATE_PROBE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actData['DATE_PROBE'])) :
            'Пробы не поступили';

        //// материалы
        $this->data['material_probe_list'] = $materialModel->getMaterialProbeToRequest($dealId);
        $this->data['method_list'] = $gostModel->getGostRequest($dealId);

//        $this->data['users'] = $user->getUsers();

		 $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
		 $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/js/select2.min.js');

        $this->addJs('/assets/js/sample.js?v=' . rand());

        $this->view('form');
    }


    /**
     *
     */
    public function insertUpdate()
    {
        /** @var Sample $sample */
        $sample = $this->model('Sample');
        $tz = $sample->getTzByTzId($tzId);
      
        //TODO: Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
        //start
        $arrData = [];
        $probe = [];
        $key_p = 1;
        //end
        $tzId = (int)$_POST['tz_id'] ?? '';
        $materialDataList = [];
        $methodsId = [];
        $sumPrice = 0;

        if (empty($tzId)) {
            $this->redirect('/request/list/');
        }

        if ( empty($tz) ) {
            $this->showErrorMessage("Технического задания с ИД {$tzId} не существует");
            $this->redirect('/request/list/');
        }

        $_SESSION['requirement_post'] = $_POST;
        
        $location = $tz['ID_Z'] >= DEAL_START_NEW_AREA? "/requirement/card/{$tzId}" : "/requirement/card_old/{$tzId}";

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
        $deal = $request->getDealById($tz['ID_Z']);
        if ( empty($deal) ) {
            $this->showErrorMessage("Заявки с ИД {$tz['ID_Z']} не существует");
            $this->redirect('/request/list/');
        }
        //start
        $actBase = $sample->getActBaseByDealId($deal['ID']);
        //end

        //TODO: Временный костыль, блокировка редактирования ТЗ после внесения данных в результаты испытаний
        $this->data['is_may_change'] = $deal['ID'] < 8846 || empty($sample->getCountFilledResultData($deal['ID'])["count_umtr"]);

        $currentUserId = $user->getCurrentUserId();
        $currentUser = $user->getCurrentUser();
        $lastHistory = $sample->getLastHistory($tzId);


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
				$sample->savingShNumbersToActBase($deal['ID'], $actBase['ACT_NUM']);
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

        $request->addAssignedToRequest($tz['ID_Z'], $newAssignedToRequest);

        $methodsNotInOA = $sample->getMethodsNotInOA($methodsId);

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

        $sample->updateTzByIdTz($tzId, $dataTz);


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

        $materialProbeGost = $sample->updateMaterialProbeGostToRequest($tz['ID_Z'], $materialDataList, $_POST['amount']);
        $invoice = $sample->getInvoice((int)$tzId);
        $sample->saveHistory($historyData);
        $request->updateStageDeal($tz['ID_Z'], 'PREPARATION');

        // собирает шифры для проб в заявке
        $material->fillCipher($tz['ID_Z']);
        //собирает шифры для проб в заявке, для таблицы ulab_material_to_request
        $material->addCipher($tz['ID_Z']);

        if (!empty($materialProbeGost['error'])) {
            $this->showErrorMessage($materialProbeGost['error']);
            $this->redirect($location);
        }



        //TODO: Временный метод, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
        $sample->savingSerializedData($tzId, $arrData, $probe);


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
     * обновление формы. новое тз
     */
    public function updateTz()
    {
        /** @var Sample $sample */
        $sample = $this->model('Sample');
		/** @var Order $orderModel */
		$orderModel = $this->model('Order');

        $tzId = $_POST['tz_id'];
        $dealId = $_POST['deal_id'];
        $dataTz = $_POST['tz'];

        // $sample->updateTzByIdTz($tzId, $dataTz);

        if (!empty($_POST['tz']['TAKEN_ID_DEAL'])) {
			$orderModel->changeOrderByHeadRequest($_POST['tz']['TAKEN_ID_DEAL'], $dealId);
		}
        $sample->updateMaterial($dealId, $_POST['material_id']);
        $sample->updateProbeMethod($dealId, $_POST['material']);

//        $sample->updateAssigned($dealId);

        // $sample->confirmTzClear($tzId);

        $this->showSuccessMessage("Техническое задание успешно изменено");
        $this->redirect("/sample/card_new/{$tzId}");
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
 
    public function getMethodListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if (!empty($_POST['gost']) && !empty($_POST['deal_id'])) {
            /** @var Sample $sample */
            $sample = $this->model('Sample');

            $response = $sample->getGostsByName($_POST['gost'], $_POST['deal_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function getGostsGroupAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if (!empty($_POST['id'])) {
            /** @var Sample $sample */
            $sample = $this->model('Sample');

            $response = $sample->getGostsByMaterialId((int)$_POST['id']);
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function isConfirmMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if (!empty($_POST['id']) && !empty($_POST['methods_id'])) {
            /** @var Sample $sample */
            $sample = $this->model('Sample');
            /** @var User $user */
            $user = $this->model('User');


            $dealId = $sample->getDealIdByTzId((int)$_POST['id']);
            $labsToMethod = $sample->getLabsByMethodsId($_POST['methods_id']);
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
                    $usersByLab = $sample->getUserByLabId($value);

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

	public function getTuForGostAjax() 
    {
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Gost $gost */
		$gost = $this->model('Gost');

		$response = [];
		$idGost = $_POST['id'];

		$arrTU = $gost->getTuByGostID($idGost);

		$tu = json_decode($arrTU['ID_TU'], true);

		foreach ($tu as $item) {

			$tuForOption = $gost->getGostForOption($item);

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

	public function getAssignedByGostIdAjax()
	{
		/** @var Gost $gost */
		$gost = $this->model('Gost');

		$gostId = $_POST['id'];

		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		$assigned = $gost->getAssignedByGostID($gostId);

		echo json_encode($assigned, JSON_UNESCAPED_UNICODE);
	}

    public function getUlabAssignedByGostIdAjax()
    {
        /** @var Gost $gost */
        $gost = $this->model('Gost');

        $gostId = $_POST['id'];

        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $assigned = $gost->getUlabAssignedByGostID($gostId);

        echo json_encode($assigned, JSON_UNESCAPED_UNICODE);
    }


    public function deletePermanentMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Sample $sample */
        $sample = $this->model('Sample');

        $sample->deleteMaterial($_POST['deal_id'], $_POST['material_id'], $_POST['mtr_id'], $_POST['number']);
    }


    public function deletePermanentMaterialGostAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Sample $sample */
        $sample = $this->model('Sample');

        $sample->deleteMaterialGost($_POST['gtp_id'], $_POST['deal_id'], $_POST['material_id'], $_POST['numberGost'], $_POST['number']);
    }


    public function getMethodsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        // /** @var Methods $methodsModel */
        // $methodsModel = $this->model('Methods');

        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');

        $result = $gostModel->getList();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    public function getMethodDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var Sample $sampleModel */
        $sampleModel = $this->model('Sample');
        /** @var User $userModel */
        $userModel = $this->model('User');

        $result = $methodsModel->get($_POST['id']);

        $arrUserAssigned = [];
        foreach ($result['assigned_data'] as $key => $user) {
            $row = $userModel->getUserById($user);

            $arrUserAssigned[$key]['id'] = $row['ID'];
            $arrUserAssigned[$key]['data_name'] = $row['NAME'] . " " . $row['LAST_NAME'];
        }
        // $arrUserAssigned = $userModel->getUserList(['LAST_NAME' => 'asc'], [], ["ID" => $result['assigned_data']]);
        // $arrUserAssigned = $userModel->getUserList(['LAST_NAME' => 'asc'], [], $filter);

        $result = $sampleModel->supplementGroupMethod($result, 'assigned_data', $arrUserAssigned);
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    // public function getTechCondListAjax()
    // {
    //     global $APPLICATION;

    //     $APPLICATION->RestartBuffer();

    //     /** @var TechCondition $tcModel */
    //     $tcModel = $this->model('TechCondition');

    //     $result = $tcModel->getList();

    //     echo json_encode($result, JSON_UNESCAPED_UNICODE);
    // }


    /**
     *
     */
    public function deleteProbeMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Sample $sample */
        $sample = $this->model('Sample');

        $result = $sample->deleteProbeMethod($_POST['tz_id'], $_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     *
     */
    public function deleteProbeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Sample $sample */
        $sample = $this->model('Sample');

        $result = $sample->deleteProbe($_POST['deal_id'], $_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     *
     */
    public function deleteMaterial()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Sample $sample */
        $sample = $this->model('Sample');

        $result = $sample->deleteMaterialNew($_POST['deal_id'], $_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    public function getGostRequestAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');

        $methodList = $gostModel->getGostRequest($_POST['deal_id']);

        echo json_encode($methodList, JSON_UNESCAPED_UNICODE);
    }
}
