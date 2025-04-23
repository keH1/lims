<?php


/**
 * @desc Результаты испытаний
 * Class ResultController
 */
class ResultController extends Controller
{
    const USERS_ADDS_CERTIFICATE = [1, 10, 9, 61]; //пользователи могут добавить аттестат акредитации
    const PROTOCOL_TYPE_SIMPLIFIED = [33, 34, 35, 36, 37, 38, 39]; //тип протокола упрощенный
    const USERS_ACCESS_OLD_DESIGN = [1, 7, 61, 75, 58]; //пользователи у которых есть доступ к старому дизайну
    const USERS_CAN_UNLOCK = [1, 7, 61, 75, 58]; //пользователи могут разблокировать

    const ADMIN_PERMISSION_ID = 2; // id роли "Админ"
    const HEAD_IC_PERMISSION_ID = 3; // id роли "Руководитель ИЦ"

    /**
     * @desc Перенаправляет пользователя на страницу «Журнал заявок»
     * route /results/
     */
    public function index()
    {
        $this->redirect('/request/list/');
    }

    /**
     * @desc Карточка листа измерений
     * route /result/card_new/{$dealId}
     * @param $dealId - id сделки
     */
    public function card_new($dealId)
    {
        if (empty($dealId) || $dealId < 0) {
            $this->redirect('/request/list/');
        }

        $this->redirect("/result/card_oati/{$dealId}");
    }


    /**
     * @desc Карточка листа измерений для оати
     * route /result/card_oati/{$dealId}
     * @param $dealId - id сделки
     */
    public function card_oati($dealId)
    {
        if (empty($dealId) || $dealId < 0) {
            $this->redirect('/request/list/');
        }

        $this->data['title'] = 'Результаты испытаний';

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');
        /** @var Permission $permissionModel */
        $permissionModel = $this->model('Permission');
        /** @var Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');
        /** @var DocTemplate $docTemplateModel */
        $docTemplateModel = $this->model('DocTemplate');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');

        $deal = $requestModel->getDealById($dealId);
        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirementModel->getTzByDealId($dealId);
        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        if ( $tz['TYPE_ID'] != '9' ) {
            $this->data['comm'] = '?type_request=commercial';
        }

        $selectedProtocol = !empty($_GET['protocol_id']) && $_GET['protocol_id'] > 0 ? $_GET['protocol_id'] : null;
        $checkedProtocol = isset($_GET['selected']) ? $selectedProtocol : null;

        $this->data['deal_id'] = $dealId;
        $this->data['tz_id'] = $tz['ID'] ?: null;
        $this->data['selected_protocol'] = $selectedProtocol;
        $this->data['deal_title'] = $deal['TITLE'];
        $this->data['selected'] = isset($_GET['selected']) ?? '';
        $this->data['is_check'] = true; //$deal['TYPE_ID'] !== 'COMPLEX' && $deal['TYPE_ID'] !== TYPE_DEAL_NK;

        $this->data['contract'] = $requirementModel->getContractByDealId($dealId);
        $this->data['protocols'] = $protocolModel->getProtocolsByDealId($dealId);
        $this->data['template_list'] = $docTemplateModel->getList(3);

        $actBase = $requirementModel->getActBase($dealId);
        $permissionInfo = $permissionModel->getUserPermission(App::getUserId());
        $protocol = $resultModel->getProtocolById($selectedProtocol);

        if (!empty($selectedProtocol)) {
            $this->data['protocol_info'] = $protocol;

            $tzObConnect = $oborudModel->getTzObConnectByProtocolId($selectedProtocol);
            $oborudsToGosts = $oborudModel->oborudsByProtocolId($selectedProtocol);
            $this->data['protocol_equipment'] = !empty($tzObConnect) ? $tzObConnect : $oborudsToGosts;
        } else {
            $this->data['protocol_equipment'] = [];
        }

        // Проверка на доступ к разблокированию протокола
        $this->data['is_may_unlock'] = true;//in_array($permissionInfo['id'],  [ADMIN_PERMISSION, HEAD_IC_PERMISSION]);
        // Проверка на доступ к признанию протокола недействительным
        $this->data['is_may_invalid'] = true;//in_array($permissionInfo['id'],  [ADMIN_PERMISSION, HEAD_IC_PERMISSION]);
        // Проверка на доступ редактирования данных условий окружающей среды(помещения)
        $this->data['may_edit_conditions'] = true;//in_array($permissionInfo['id'],  [ADMIN_PERMISSION, HEAD_IC_PERMISSION]);
        // Проверка на доступ выдачи протокола с аттестатом аккредитации
        $this->data['is_adds_certificate'] = !empty($protocol['probe_count']) && !empty($protocol['IN_ATTESTAT_DIAPASON']) &&
            true;//in_array($permissionInfo['id'],  [ADMIN_PERMISSION, SMK_PERMISSION]);
        // Проверка на доступ подтверждения значения в ОА
        $this->data['may_confirm_oa'] = true;//in_array($permissionInfo['id'],  [ADMIN_PERMISSION, SMK_PERMISSION]);

        // Добавить доступ к функционалу созданных протоколов
        $this->data['protocols'] = $resultModel::addConditionsToProtocols(
            $this->data['protocols'],
            $deal,
            $selectedProtocol,
            $checkedProtocol
        );

        $this->data['contract_number'] = $this->data['contract']['NUMBER'] ?? '';
        $this->data['contract_date'] = $this->data['contract']['DATE'] ?? '';
        $this->data['contract_type'] = $this->data['contract']['CONTRACT_TYPE'] ?? 'Договор';
        $this->data['act_number'] = $actBase['ACT_NUM'] ?: '';
        $this->data['act_date'] = !empty($actBase['ACT_DATE']) && $actBase['ACT_DATE'] !== '0000-00-00' ?
            date('d.m.Y', strtotime($actBase['ACT_DATE'])) : '';


        $this->data['tz_material_list'] = $requirementModel->getMaterialFromTz($dealId);
        $this->data['tz_methods_list'] = $requirementModel->getMethodsFromTz($dealId);
        $this->data['tz_probe_list'] = $requirementModel->getProbeFromTz($dealId);


        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJS("/assets/plugins/sticksy/sticksy.min.js");

        $this->addJs('/assets/js/result_new.js?v=' . rand());

        $this->view('card_oati', '', 'template_journal');
    }


    /**
     * @deprecated
     * route /result/resultCard/{$dealId}
     * @desc Карточка листа измерений [deprecated]
     * @param $dealId - id сделки
     */
    public function resultCard($dealId)
    {
        if (empty($dealId) || $dealId < 0) {
            $this->redirect('/request/list/');
        }

        $this->redirect("/result/card_oati/{$dealId}");
    }


    /**
     * @deprecated
     * route /result/card/{$dealId}
     * @desc Карточка листа измерений [deprecated]
     * @param $dealId - id сделки
     */
    public function card($dealId)
    {
        if (empty($dealId) || $dealId < 0) {
            $this->redirect('/request/list/');
        }

        $this->redirect("/result/card_oati/{$dealId}");
    }

    /**
     * @deprecated
     * @desc Карточка листа измерений для сотрудников лабораторий [deprecated]
     * @param $dealId
     */
    public function card_tester($dealId)
    {
        if (empty($dealId) || $dealId < 0) {
            $this->redirect('/request/list/');
        }

        $this->redirect("/result/card_oati/{$dealId}");
    }


    /**
     * @desc Сохраняем, обновляем данные результов испытаний
     * route /result/updateResult/
     */
    public function updateResult()
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0) {
            $this->redirect('/request/list/');
        }

        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');

        $dealId = (int)$_POST['deal_id'];
        $protocolId = (int)$_POST['protocol_id'];
        $successMsg = 'Данные результатов испытаний успешно сохранены';
        $selected = $_POST['selected'] ? '&selected' : '';

        $location = $protocolId ? "/result/card_oati/{$_POST['deal_id']}?protocol_id={$protocolId}{$selected}" : "/result/card_oati/{$_POST['deal_id']}";

        $deal = $request->getDealById($dealId);
        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);
        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        // Если у протокола есть номер и протокол не разблокирован, то изменить данные нельзя
        $protocol = $resultModel->getProtocolById($protocolId);
        if (!empty($protocolId) && !empty($protocol['NUMBER']) && empty($protocol['EDIT_RESULTS'])) {
            $this->showErrorMessage("Ошибка сохранения данных, нельзя изменить данные у протокола с присвоенным номером");
            $this->redirect($location);
        }

        if (!empty($protocolId) && !empty($protocol['INVALID'])) {
            $this->showErrorMessage("Ошибка сохранения данных, нельзя изменить данные у протокола признанным недействительным");
            $this->redirect($location);
        }

        // Прикрепляем пробы к протоколу
        $resultModel->attachProbe($dealId, $protocolId, $_POST);
        if ($deal['TYPE_ID'] != TYPE_DEAL_NK) {
            //Сохранение и проверка данных результатов испытаний
            $resultModel->updateProbeMethod($dealId, $_POST);
        }

        // Стадия заявки
        if (!in_array($deal['STAGE_ID'], ['2', '3', '4', 'WON']) && $deal['TYPE_ID'] !== 'COMPLEX') {
            $request->updateStageDeal($dealId, 1);
        }

        unset($_SESSION['result_post']);
        $this->showSuccessMessage($successMsg);
        $this->redirect($location);
    }


    /**
     * @deprecated
     * route /result/insertUpdate/
     * @desc Сохраняем, обновляем данные результов испытаний [deprecated]
     */
    public function insertUpdate()
    {
        //TODO: Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
        //start
        $arrSampleChecked = [];
        $arrProbe = [];
        $arrActualValue = [];
        $arrAverageValue = [];
        $arrMatch = [];
        $arrNormDescription = [];

        $anchorSample = 0;
        //end

        $noOA = [];


        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0) {
            $this->redirect('/request/list/');
        }

        $successMsg = 'Данные результатов испытаний успешно сохранены';

        $_SESSION['result_post'] = $_POST;

        $dealId = (int)$_POST['deal_id'] ?: null;
        $protocolId = (int)$_POST['protocol_id'] ?: null;

        /*if ($_SESSION['SESS_AUTH']['ROLE'] == 6) {
            $location = "/result/resultCard_tester/{$_POST['deal_id']}";
        } else {
            $location = $protocolId ? "/result/card/{$_POST['deal_id']}?protocol_id={$protocolId}" : "/result/card/{$_POST['deal_id']}";
        }*/
        $location = $protocolId ? "/result/card/{$_POST['deal_id']}?protocol_id={$protocolId}" : "/result/card/{$_POST['deal_id']}";


        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var User $user */
        $user = $this->model('User');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');


        $deal = $request->getDealById($dealId);

        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        //Валидация
        foreach ($_POST['conformity'] as $umtr_id => $value) {
            foreach ($value as $ugtp_id => $conformity) {
                $valid = $this->validateNumber($conformity, 'Среднее значение', true);

                if (!$valid['success']) {
                    $this->showErrorMessage($valid['error']);
                    $this->redirect($location);
                }
            }
        }


        $tzId = (int)$tz['ID'] ?: null;

        $protocol = $result->getProtocolById($protocolId);
        $currentUserId = $user->getCurrentUserId();
        $currentUser = $user->getCurrentUser();
        $countMaterials = $requirement->getCountMaterials($dealId);
        $frost = $result->getFrostByProtocolId($protocolId);


        if (!empty($protocolId) && !empty($protocol['NUMBER']) &&
            !in_array(App::getUserId(),self::USERS_CAN_UNLOCK)) {
            $this->showErrorMessage("Ошибка сохранения данных, нельзя изменить данные у протокола с присвоенным номером");
            $this->redirect($location);
        }

        if (!empty($protocolId) && !empty($protocol['upi_action'])) {
            $this->showErrorMessage("Ошибка сохранения данных, нельзя изменить данные у протокола признанным недействительным");
            $this->redirect($location);
        }


        $dateProbe = $_POST['DATE_PROBE'] ?: null;
        $dateProbeRu = !empty($dateProbe) ? date("d.m.Y", strtotime($_POST['DATE_PROBE'])) : '-';
        $placeProbe = !empty($_POST['PLACE_PROBE']) ? $_POST['PLACE_PROBE'] : '-';


        //открепляем пробы от протокола (если протоколу присвоен номер то окрепить нельзя)
        if (!empty($protocolId) && empty($protocol['NUMBER']) || !empty($protocolId) && !empty($protocol['NUMBER']) &&
            in_array(App::getUserId(),self::USERS_CAN_UNLOCK)) {
            $umtr = $result->getMaterialToRequestByProtocolId($protocolId);

            $mtrData = [
                'protocol_id' => null
            ];

            foreach ($umtr as $key => $val) {
                if (!empty($val['protocol_id']) && empty($_POST['probe_checkbox'][$val['id']])) {
                    $umtr_result = $result->updateMaterialToRequest((int)$val['id'], $mtrData);

                    if ($umtr_result !== 1) {
                        $this->showErrorMessage("Не удалось открепить пробы от протокола");
                        $this->redirect($location);
                    }
                }
            }
        }


        //прикрепляем пробу к протоколу (если протоколу присвоен номер то прикрепить пробы нельзя)
        if (!empty($_POST['probe_checkbox']) && !empty($protocolId) && empty($protocol['NUMBER']) ||
            !empty($_POST['probe_checkbox']) && !empty($protocolId) && !empty($protocol['NUMBER']) &&
            in_array(App::getUserId(),self::USERS_CAN_UNLOCK)) {
            $mtrData = [
                'protocol_id' => $protocolId
            ];

            foreach ($_POST['probe_checkbox'] as $umtr_id => $val) {
                $umtr_id = (int)$umtr_id;
                $umtr = $result->getMaterialToRequestData($umtr_id);

                if (!empty($umtr['protocol_id']) && (int)$umtr['protocol_id'] !== $protocolId) {
                    continue;
                }

                $materialToRequest = $result->updateMaterialToRequest($umtr_id, $mtrData);

                if ($materialToRequest !== 1) {
                    $this->showErrorMessage("Не удалось прикрепить пробы к протоколу");
                    $this->redirect($location);
                }


                //TODO: (Выбор проб) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                //start
                $arrNumberProbe[$umtr['probe_number'] - 1] = $tz['PROBE'][$umtr['material_number']]['number_probe'][$umtr['probe_number'] - 1] ?: [];
                $arrayShNumber[$umtr['probe_number'] - 1] = $tz['PROBE'][$umtr['material_number']]['sh_number'][$umtr['probe_number'] - 1] ?: [];
                $arrProbe[$umtr['material_number']] = $tz['PROBE'][$umtr['material_number']];
                $arrProbe[$umtr['material_number']]['number_probe'] = $arrNumberProbe;
                $arrProbe[$umtr['material_number']]['sh_number'] = $arrayShNumber;
                $arrProbe[$umtr['material_number']]['mesto_data'] = $placeProbe . '; ' . $dateProbeRu;

                $arrSampleChecked[$umtr['material_number'] - 1][0][$umtr['probe_number']][0] = $val;
                //end
            }
        }


        //Сохранение данных результатов испытаний
        foreach ($_POST['actual_value'] as $umtr_id => $value) {
            //TODO: Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
            //start
            //$gostNumber = 0;
            //if ($dealId == 8860) {
            //    $gostNumber = 2; //Временный костыль
            //}
            //end

            foreach ($value as $ugtp_id => $actualValue) {
                $ugtp_id = (int)$ugtp_id;
                $ugtp = $result->getUlabGostToProbeById($ugtp_id);

                //TODO: Временно, для работы остальных скриптов до их рефакторинга
                //start
                $gostNumber = $ugtp['gost_number'] - 1;
                //end

                //Если к пробе прикреплен протокол и присвоен номер протокола и пользователи не входят в состав разрешенных для редактирования, то данные не изменяем
                if (!empty($ugtp['p_number']) && !in_array(App::getUserId(),self::USERS_CAN_UNLOCK)) {
                    //continue; //TODO: Временно закоментировал прореку пока не уйдем от сериализованных данных в ba_tz (не дает сохранять полностью структуру "Фактических заначений" и т.д для сериализованных данных)
                }


                //Нормативное значение
                $normativeValue = $_POST['normative_value'][$umtr_id][$ugtp_id] ?? '';

                //TODO: (Нормативное значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                //start
                if ($ugtp['bgc_gost_type'] === 'TU_group') { //Методика определения группы материала
                    $arrNormDescription[$gostNumber] = $_POST['normative_value'][$umtr_id][$ugtp_id] ?? '';
                } elseif ($ugtp['bgc_gost_type'] === 'TU_research') { //Методика исследования по группе материала
                    if (!empty($countMaterials['count_material']) && $countMaterials['count_material'] > 1) {
                        $arrNormDescription[$ugtp['material_number'] - 1][$ugtp['probe_number']][$gostNumber] = $_POST['normative_value'][$umtr_id][$ugtp_id] ?? '';
                    } else {
                        $arrNormDescription[$ugtp['probe_number']][$gostNumber] = $_POST['normative_value'][$umtr_id][$ugtp_id] ?? '';
                    }

                } else {
                    if (!empty($countMaterials['count_material']) && $countMaterials['count_material'] > 1) {
                        $arrNormDescription[$ugtp['material_number'] - 1][$ugtp['probe_number']][$gostNumber] = $_POST['normative_value'][$umtr_id][$ugtp_id] ?? '';
                    } else {
                        $arrNormDescription[$ugtp['probe_number']][$gostNumber] = $_POST['normative_value'][$umtr_id][$ugtp_id] ?? '';
                    }
                }
                //end


                //Фактическое значение
                //TODO: (Фактическое значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                //start
                $arrActualValue[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $actualValue;
                //end

                $actualValueJson = json_encode($actualValue, JSON_UNESCAPED_UNICODE);


                //Среднее значение
                //TODO: А если какоенибудь из полей будет текст? Или в тестовом поле будет запятая 1,1 то не будет учитываться
                $sumActualValue = array_sum($actualValue);


                if ($ugtp['bgm_gost_type'] !== 'metodic_otbor') {
                    if (in_array($ugtp['bgm_gost_type'], ['TU_sred2', 'TU_sred3'])) {
                        $averageValue = count($actualValue) > 2 ?
                            round((($sumActualValue - min($actualValue)) / (count($actualValue) - 1)), $ugtp['bgm_accuracy']) :
                            round(($sumActualValue / count($actualValue)), $ugtp['bgm_accuracy']);

                        //TODO: (Среднее значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                        //start
                        $arrAverageValue[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $averageValue;
                        //end
                    } elseif ($ugtp['bgm_gost_type'] === "TU_sred4") {

                        $minActualValue = min($actualValue);

                        unset($actualValue[array_search($minActualValue, $actualValue)]);

                        $averageValue =
                            round((($sumActualValue - $minActualValue - min($actualValue)) / (count($actualValue) - 1)), $ugtp['bgm_accuracy']);

                        //TODO: (Среднее значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                        //start
                        $arrAverageValue[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $averageValue;
                        //end
                    } elseif ($ugtp['bgm_gost_type'] == "TU_sred5") {

                        sort($actualValue);

                        $res1 = $actualValue[round(count($actualValue) / 2, 0) - 1];
                        $res2 = $actualValue[round(count($actualValue) / 2, 0)];
                        $averageValue = round(($res1 + $res2) / 2, $ugtp['bgm_accuracy']);

                        //TODO: (Среднее значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                        //start
                        $arrAverageValue[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $averageValue;
                        //end
                    }  else {
                        $averageValue = round(($sumActualValue / count($actualValue)), $ugtp['bgm_accuracy']);

                        //TODO: (Среднее значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                        //start
                        $arrAverageValue[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $averageValue;
                        //end
                    }
                } else {
                    $averageValue = null;

                    //TODO: (Среднее значение) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                    //start
                    $arrAverageValue[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $averageValue;
                    //end
                }


                //Соответствие требованиям
                if ($ugtp['bgc_gost_type'] === 'TU_group') {
                    $numGroupMat = explode('-', $ugtp['p_group_mat'])[1] ?? null;
                    $materialGroup = $ugtp['bgc_value_dop'][$numGroupMat] ?? '';

                    $normFrom = $ugtp['bgc_norm_dop'][$numGroupMat][0];
                    $normBefore = $ugtp['bgc_norm_dop'][$numGroupMat][1];
                } else {
                    $materialGroup = '';

                    $normFrom = 0;
                    $normBefore = 0;
                }


                if ($ugtp['bgc_match_manual']) {
                    $match = $_POST['match'][$umtr_id][$ugtp_id] ?? null;

                    //TODO: (Соответствие требованиям) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                    //start
                    $arrMatch[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $match;
                    //end
                } else {
                    if ($_POST['NO_COMPLIANCE']) {
                        $match = 2;

                        //TODO: (Соответствие требованиям) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                        //start
                        $arrMatch[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $match;
                        //end
                    } else {
                        if ($materialGroup !== '') {
                            if ($averageValue !== null && ($normFrom <= $averageValue) && ($normBefore >= $averageValue)) {
                                $match = 1;

                                //TODO: (Соответствие требованиям) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                                //start
                                $arrMatch[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $match;
                                //end
                            } else {
                                $match = 0;

                                //TODO: (Соответствие требованиям) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                                //start
                                $arrMatch[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $match;
                                //end
                            }
                        } else {
                            if ($averageValue !== null && ($ugtp['bgc_norm1'] <= $averageValue) && ($ugtp['bgc_norm2'] >= $averageValue)) {
                                $match = 1;

                                //TODO: (Соответствие требованиям) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                                //start
                                $arrMatch[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $match;
                                //end
                            } else {
                                $match = 0;

                                //TODO: (Соответствие требованиям) Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                                //start
                                $arrMatch[$ugtp['material_number'] - 1][$gostNumber][$ugtp['probe_number']] = $match;
                                //end
                            }
                        }
                    }
                }


                if (empty($ugtp['bgm_in_oa']) && $ugtp['bgm_gost_type'] !== 'metodic_otbor' &&
                    !in_array($ugtp['method_id'], [2875, 3376]) && !empty($ugtp['protocol_id'])) {
                    $noOA[$ugtp['protocol_id']] = 1;
                }

//                if ($ugtp['bgm_res_text']) {
//                    $actualValue = preg_replace("/[^,.0-9]/", '', $actualValue[0]);
//
//                    if (($actualValue < $ugtp['bgm_norm1'] || $actualValue > $ugtp['bgm_norm2']) && $ugtp['bgm_in_out']) {
//                        if (!empty($ugtp['bgm_in_oa']) && !empty($ugtp['protocol_id'])) {
//                            $noOA[$ugtp['protocol_id']] = 1;
//                        }
//                    } elseif (($actualValue > $ugtp['bgm_norm1'] || $actualValue < $ugtp['bgm_norm2']) && empty($ugtp['bgm_in_out'])) {
//                        if (!empty($ugtp['bgm_in_oa']) && !empty($ugtp['protocol_id'])) {
//                            $noOA[$ugtp['protocol_id']] = 1;
//                        }
//                    }
//                } else {
                if (empty($ugtp['bgm_norm_text']) && $averageValue !== null) {
                    if ($ugtp['method_id'] === 6402) {
                        //TODO: а если несколько фактических значений ? (Убрать костыль)
                        //$averageValue = explode('F', $ugtp['actual_value'][0])[1];
                    }

                    if (($averageValue < $ugtp['bgm_norm1'] || $averageValue > $ugtp['bgm_norm2'])
                        && $ugtp['bgm_in_out']) {
                        if (!empty($ugtp['bgm_in_oa']) && !empty($ugtp['protocol_id'])) {
                            $noOA[$ugtp['protocol_id']] = 1;
                        }
                    } elseif (($averageValue > $ugtp['bgm_norm1'] || $averageValue < $ugtp['bgm_norm2']) &&
                        empty($ugtp['bgm_in_out'])) {
                        if (!empty($ugtp['bgm_in_oa']) && !empty($ugtp['protocol_id'])) {
                            $noOA[$ugtp['protocol_id']] = 1;
                        }
                    }
                }
//                }

                if (!empty($ugtp['bgm_in_out']) && empty($ugtp['bgm_in_oa']) && !empty($ugtp['protocol_id'])) {
                    $noOA[$ugtp['protocol_id']] = 1;
                }



                $trialResult = $result->getTrialResult($ugtp_id);

                $data = [
                    'gost_to_probe_id' => $ugtp_id,
                    'normative_value' => $normativeValue,
                    'actual_value' => $actualValueJson,
                    'average_value' => $averageValue,
                    'match' => $match
                ];

                if (!empty($trialResult)) { //редактирование
                    $trialResultsUpdate = $result->updateTrialResults($ugtp_id, $data);

                    if ($trialResultsUpdate !== 1) {
                        $this->showErrorMessage("Не удалось обновить данные таблицы результов испытаний");
                        $this->redirect($location);
                    }
                } else {
                    $trialResultsId = $result->addTrialResults($data);

                    if (empty($trialResultsId)) {
                        $this->showErrorMessage("Не удалось сохранить данные таблицы результов испытаний");
                        $this->redirect($location);
                    }
                }

                //TODO: Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
                //start
                $gostNumber++;
                //end
            }
        }


        //TODO: Временные массивы данных, сохраняет сериализованные данные, для работы остальных скриптов до их рефакторинга
        //start
        $tzData = [
            'NORM_DESC' => serialize($arrNormDescription),
            'RESULTS' => serialize($arrActualValue),
            'SRED' => serialize($arrAverageValue),
            'MATCH_MANUAL' => serialize($arrMatch)
        ];

        $result->updateTzByDealId($dealId, $tzData);
        //end


        //сохранение информации по протоколу
        switch ($_POST['protocol_type']) {
            default:
            case 'simple':
                $TYPE_TZ = 0;
                break;
            case 'simpleEcp':
                $TYPE_TZ = 1;
                break;
            case '2max':
                $TYPE_TZ = 2;
                break;
            case '3max':
                $TYPE_TZ = 3;
                break;
            case '4max':
                $TYPE_TZ = 4;
                break;
            case 'zern':
                $TYPE_TZ = 5;
                $ostatki = !empty($_POST['ostatki']) ? serialize($_POST['ostatki']) : '';
                break;
            case 'grunt':
                $TYPE_TZ = 6;
                $ostatki = !empty($_POST['ostatki3']) ? serialize($_POST['ostatki3']) : '';
                break;
            case 'prirod':
                $TYPE_TZ = 7;
                $ostatki = !empty($_POST['ostatki4']) ? serialize($_POST['ostatki4']) : '';
                break;
            case 'tu_12801':
                $TYPE_TZ = 8;
                $ostatki = !empty($_POST['ostatki2']) ? serialize($_POST['ostatki2']) : '';
                break;
            case 'tu_183':
                $TYPE_TZ = 9;
                $ostatki = !empty($_POST['ostatki5']) ? serialize($_POST['ostatki5']) : '';
                break;
            case 'osk1':
                $TYPE_TZ = 10;
                break;
            case 'osk2':
                $TYPE_TZ = 11;
                break;
            case 'osk3':
                $TYPE_TZ = 12;
                $ostatki = !empty($_POST['ostatki5']) ? serialize($_POST['ostatki5']) : '';
                break;
            case 'osk4':
                $TYPE_TZ = 13;
                //TODO: уточнить таблицу зернового состава
                //$ostatki = !empty($_POST['ostatki4']) ? serialize($_POST['ostatki4']) : '';
                break;
            case 'osk_sred':
                $TYPE_TZ = 14;
                break;
            case 'shps':
                $TYPE_TZ = 15;
                $ostatki = !empty($_POST['ostatki6']) ? serialize($_POST['ostatki6']) : '';
                break;
            case 'tu_183_2':
                $TYPE_TZ = 16;
                $ostatki = !empty($_POST['ostatki5']) ? serialize($_POST['ostatki5']) : '';
                break;
            case 'sheb':
                $TYPE_TZ = 17;
                $ostatki = !empty($_POST['ostatki7']) ? serialize($_POST['ostatki7']) : '';
                break;
            case 'sheb_shlak':
                $TYPE_TZ = 18;
                $ostatki = !empty($_POST['ostatki8']) ? serialize($_POST['ostatki8']) : '';
                break;
            case 'zern_sheb':
                $TYPE_TZ = 19;
                $ostatki = !empty($_POST['ostatki7']) ? serialize($_POST['ostatki7']) : '';
                break;
            case 'density_grunt':
                $TYPE_TZ = 20;
                break;
            case 'zern_№2':
                $TYPE_TZ = 21;
                $ostatki = !empty($_POST['ostatki']) ? serialize($_POST['ostatki']) : '';
                break;
            case 'grunt_№2':
                $TYPE_TZ = 22;
                $ostatki = !empty($_POST['ostatki3']) ? serialize($_POST['ostatki3']) : '';
                break;
            case 'prirod_№2':
                $TYPE_TZ = 23;
                $ostatki = !empty($_POST['ostatki4']) ? serialize($_POST['ostatki4']) : '';
                break;
            case 'tu_12801_№2':
                $TYPE_TZ = 24;
                $ostatki = !empty($_POST['ostatki2']) ? serialize($_POST['ostatki2']) : '';
                break;
            case 'tu_183_2_№2':
                $TYPE_TZ = 25;
                $ostatki = !empty($_POST['ostatki5']) ? serialize($_POST['ostatki5']) : '';
                break;
            case 'tu_183_№2':
                $TYPE_TZ = 26;
                $ostatki = !empty($_POST['ostatki5']) ? serialize($_POST['ostatki5']) : '';
                break;
            case 'zern_sheb_№2':
                $TYPE_TZ = 27;
                $ostatki = !empty($_POST['ostatki7']) ? serialize($_POST['ostatki7']) : '';
                break;
            case 'shps_№2':
                $TYPE_TZ = 28;
                $ostatki = !empty($_POST['ostatki6']) ? serialize($_POST['ostatki6']) : '';
                break;
            case 'sheb_shlak_№2':
                $TYPE_TZ = 29;
                $ostatki = !empty($_POST['ostatki8']) ? serialize($_POST['ostatki8']) : '';
                break;
            case 'frost_resistance':
                $TYPE_TZ = 30;
                break;
            case 'metric_method':
                $TYPE_TZ = 31;
                $ostatki = !empty($_POST['ostatki9']) ? serialize($_POST['ostatki9']) : '';
                break;
            case 'gost31015':
                $TYPE_TZ = 32;
                $ostatki = !empty($_POST['ostatki2']) ? serialize($_POST['ostatki2']) : '';
                break;
            case 'shortcut':
                $TYPE_TZ = 33;
                break;
            case 'shortcut_mean':
                $TYPE_TZ = 34;
                break;
            case 'zern_short_simple':
                $TYPE_TZ = 35;
                $ostatki = !empty($_POST['ostatki']) ? serialize($_POST['ostatki']) : '';
                break;
            case 'prirod_short_simple':
                $TYPE_TZ = 36;
                $ostatki = !empty($_POST['ostatki4']) ? serialize($_POST['ostatki4']) : '';
                break;
            case 'thermal_conductivity':
                $TYPE_TZ = 37;
                break;
            case 'sheb_shlak_№2_simple':
                $TYPE_TZ = 38;
                $ostatki = !empty($_POST['ostatki8']) ? serialize($_POST['ostatki8']) : '';
                break;
            case 'sheb_shlak_simple':
                $TYPE_TZ = 39;
                $ostatki = !empty($_POST['ostatki8']) ? serialize($_POST['ostatki8']) : '';
                break;
            case 'new_25607':
                $TYPE_TZ = 40;
                $ostatki = !empty($_POST['ostatki6']) ? serialize($_POST['ostatki6']) : '';
                break;
            case 'zern_sheb_smes':
                $TYPE_TZ = 42;
                $ostatki = !empty($_POST['ostatki7']) ? serialize($_POST['ostatki7']) : '';
                break;
        }

        $noCompliance = !empty($_POST['NO_COMPLIANCE']) ? 1 : 0;
        $protocolOutsideLis = !empty($_POST['PROTOCOL_OUTSIDE_LIS']) ? 1 : 0;
        $inAttestatDiapason = empty($noOA[$protocolId]) ? 1 : 0;
        $attestatInProtocol = $inAttestatDiapason && !empty($_POST['ATTESTAT_IN_PROTOCOL']) ? 1 : 0;


        //TODO: Добавить новое место хранения протокола после рефакторинга формирования протоколов
        //...

        //TODO: Временно, место хранения файлов протокола до рефакторинга
        //start
        $pathToPdfFile = './protocol_generator/archive/' . $tzId . date('Y', strtotime($protocol['DATE'])) . '/' . $protocolId . '/Протокол №' . $protocol['NUMBER'] . ' от ' . date('d.m.Y', strtotime($protocol['DATE'])) . '.pdf';
        $pathToSigFile = './protocol_generator/archive/' . $tzId . date('Y', strtotime($protocol['DATE'])) . '/' . $protocolId . '/' . $protocol['NUMBER'] . '.sig';
        $pathToForsignFile = './protocol_generator/archive/' . $tzId . date('Y', strtotime($protocol['DATE'])) . '/' . $protocolId . '/forsign.docx';
        //end

        //Если протокол выдан вне ЛИС удаляем файлы сформированного протокола
        if (!empty($protocolOutsideLis)) {
            unlink($pathToSigFile);
            unlink($pathToForsignFile);
            unlink($pathToPdfFile);
        } else {
            //Если тип протокола НЕ упрощенный, проверяем тип протокола который был раньше.Если тип заявки был раньше до сохранения результатов испытаний упрощенный, то удаляем файл sig и forsign.docx
            if (!in_array($TYPE_TZ, self::PROTOCOL_TYPE_SIMPLIFIED) && in_array($protocol['PROTOCOL_TYPE'], self::PROTOCOL_TYPE_SIMPLIFIED)) {
                unlink($pathToSigFile);
                unlink($pathToForsignFile);
            }

            if ($TYPE_TZ !== $protocol['PROTOCOL_TYPE']) {
                unlink($pathToPdfFile);
            }
        }


        if (!empty($protocolId) && empty($protocol['NUMBER']) || !empty($protocolId) && !empty($protocol['NUMBER']) &&
            in_array(App::getUserId(),self::USERS_CAN_UNLOCK)) {
            $protocolData = [
                'PROTOCOL_TYPE' => $TYPE_TZ,
                'id_template' => $_POST['id_template'],
                'ostatki' => $ostatki,
                'SOSTAV' => serialize($_POST['sostav']),
                'PROTOCOL_OUTSIDE_LIS' => $protocolOutsideLis,
                'DATE_BEGIN' => $_POST['DATE_BEGIN'] ?: null,
                'DATE_END' => $_POST['DATE_END'] ?: null,
                'SAMPLE_CHECKED' => serialize($arrSampleChecked),
                'PROBE' => serialize($arrProbe),
                'GROUP_MAT' => $_POST['GROUP_MAT'] ?? '',
                'VERIFY' => serialize($_POST['VERIFY']),
                'NO_COMPLIANCE' => $noCompliance,
                'TEMP_O' => $_POST['TEMP_O'] ?? null,
                'TEMP_TO_O' => $_POST['TEMP_TO_O'] ?? null,
                'VLAG_O' => $_POST['VLAG_O'] ?? null,
                'VLAG_TO_O' => $_POST['VLAG_TO_O'] ?? null,
                'DESCRIPTION' => $_POST['DESCRIPTION'] ?? '',
                'OBJECT' => $_POST['OBJECT'] ?? '',
                'PLACE_PROBE' => $placeProbe,
                'DATE_PROBE' => $dateProbe,
                'DOP_INFO' => $_POST['DOP_INFO'] ?? '',
                'ATTESTAT_IN_PROTOCOL' => $attestatInProtocol,
                'IN_ATTESTAT_DIAPASON' => $inAttestatDiapason,
                'ANCHOR_SAMPLE' => 1
            ];

            $updateProtocol = $result->updateProtocolById($protocolId, $protocolData);

            if ($updateProtocol !== 1) {
                $this->showErrorMessage("Не удалось обновить протокол");
                $this->redirect($location);
            }
        }


        //Морозостойкость
        if (!empty($protocolId) && empty($protocol['NUMBER']) && $_POST['protocol_type'] === 'frost_resistance' ||
            !empty($protocolId) && !empty($protocol['NUMBER']) && $_POST['protocol_type'] === 'frost_resistance' &&
            in_array(App::getUserId(),self::USERS_CAN_UNLOCK)) {
            $frostData = [
                'control_damage' => $_POST['control_damage'] ?? '',
                'main_damage' => $_POST['main_damage'] ?? '',
                'control_mass' => $_POST['control_mass'] ?? '',
                'main_mass' => $_POST['main_mass'] ?? null,
                'control_strength' => serialize($_POST['control_strength']),
                'main_strength' => serialize($_POST['main_strength']),
                'control_medium' => $_POST['control_medium'] ?? null,
                'main_medium' => $_POST['main_medium'] ?? null,
                'control_bottom_line' => $_POST['control_bottom_line'] ?? null,
                'main_bottom_line' => $_POST['main_bottom_line'] ?? null,
                'ratio' => $_POST['ratio'] ?? '',
                'tz_id' => $tzId,
                'protocol_id' => $protocolId,
            ];


            if (empty($frost)) {
                $insertFrost = $result->create($frostData, 'b_frost');

                if (empty($insertFrost)) {
                    $this->showErrorMessage("Не удалось сохранить данные морозостойкости");
                    $this->redirect($location);
                }
            } else {
                $updateFrost = $result->updateFrost($protocolId, $frostData);

                if ($updateFrost !== 1) {
                    $this->showErrorMessage("Не удалось обновить данные морозостойкости");
                    $this->redirect($location);
                }
            }
        }


        //Оборудование
        $oborudIds = !empty($_POST['equipment_ids']) ? json_decode($_POST['equipment_ids']) : [];
        if (!empty($protocolId) && !empty($oborudIds)) {
            $delTzObConnect = $oborudModel->delTzObConnectByProtocolId($protocolId);

            if (empty($delTzObConnect['success']) && !empty($delTzObConnect['error'])) {
                $this->showErrorMessage($delTzObConnect['error']);
                $this->redirect($location);
            }

            foreach ($oborudIds as $id) {
                if (empty($dealId) || empty($id)) {
                    $this->showErrorMessage("Не указан, или указан неверно ИД сделки или оборудования");
                    $this->redirect($location);
                }

                $tzObConnectData = [
                    'ID_TZ' => $dealId,
                    'ID_OB' => $id,
                    'PROTOCOL_ID' => $protocolId
                ];

                $insertTzObConnect = $oborudModel->create($tzObConnectData, 'TZ_OB_CONNECT');

                if (empty($insertTzObConnect)) {
                    $this->showErrorMessage("Не удалось привязать оборудование к протоколу");
                    $this->redirect($location);
                }
            }
        }


        //История (TODO: Сделать рефакторинг истоирии)
        $historyType[] = 'Сохранение результатов испытаний';
        if (isset($protocol['PROTOCOL_OUTSIDE_LIS']) && $protocol['PROTOCOL_OUTSIDE_LIS'] !== $protocolOutsideLis) {
            $historyType[] = 'Изменили "Протокол выдается вне ЛИС"';
        }

        if (isset($protocol['TEMP_O']) && isset($_POST['TEMP_O']) && $protocol['TEMP_O'] !== $_POST['TEMP_O'] ||
            isset($protocol['TEMP_TO_O']) && isset($_POST['TEMP_TO_O']) && $protocol['TEMP_TO_O'] !== $_POST['TEMP_TO_O']) {
            $historyType[] = 'Изменили значение температуры';
        }


        if (isset($protocol['VLAG_O']) && isset($_POST['VLAG_O']) && $protocol['VLAG_O'] !== $_POST['VLAG_O'] ||
            isset($protocol['VLAG_TO_O']) && isset($_POST['VLAG_TO_O']) && $protocol['VLAG_TO_O'] !== $_POST['VLAG_TO_O']) {
            $historyType[] = 'Изменили значение влажности';
        }


        if (isset($protocol['DATE_BEGIN']) && isset($_POST['DATE_BEGIN']) && $protocol['DATE_BEGIN'] !== $_POST['DATE_BEGIN']) {
            $historyType[] = 'Изменили дату начала испытаний';
        }


        if (isset($protocol['DATE_END']) && isset($_POST['DATE_END']) && $protocol['DATE_END'] !== $_POST['DATE_END']) {
            $historyType[] = 'Изменили дату окончания испытаний. ';
        }

        $strType = implode('. ', $historyType);

        $historyData = [
            'DATE' => date('Y-m-d H:i:s'),
            'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
            'PROT_NUM' => $protocol['NUMBER'],
            'TZ_ID' => $tzId,
            'USER_ID' => $currentUserId,
            'TYPE' => $strType,
            'REQUEST' => $deal['TITLE'],
            'PROTOCOL_ID' => $protocol['ID']
        ];

        $historyId = $result->addHistory($historyData);

        if (empty($historyId)) {
            $this->showErrorMessage("Не удалось сохранить данные истории");
            $this->redirect($location);
        }


        //Стадия заявки
        if (!in_array($deal['STAGE_ID'], ['2', '3', '4', 'WON']) && $deal['TYPE_ID'] !== 'COMPLEX') {
            $request->updateStageDeal($dealId, 1);
        }

        unset($_SESSION['result_post']);
        $this->showSuccessMessage($successMsg);
        $this->redirect($location);
    }

    /**
     * Открепляет пробы от протокола
     */
    public function unboundProtocol()
    {
        /** @var Result $result */
        $result = $this->model('Result');

        $protocolId = (int)$_POST['protocol_id'];
        $dealId = (int)$_POST['deal_id'];
        $location = $protocolId ? "/result/card_oati/{$dealId}?protocol_id={$protocolId}" : "/result/card_oati/{$dealId}";

        $probeIdList = explode(',', $_POST['probe_id_list']);

        foreach ($probeIdList as $ugtpId) {
            $ugtpId = (int)$ugtpId;
            $result->unboundProtocol($ugtpId);
        }

        $this->showSuccessMessage("Пробы отвязаны от протокола");
        $this->redirect($location);
    }

    /**
     * route /result/createProtocol/
     * @desc Создаёт протокол
     */
    public function createProtocol()
    {
        //if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0 || !isset($_POST['btn_create_protocol'])) {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0) {
            $this->redirect('/request/list/');
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');


        $dealId = (int)$_POST['deal_id'];
        $location = "/result/card_oati/{$dealId}";

        $deal = $request->getDealById($dealId);

        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tzId = $tz['ID'] ?: null;


        $protocolData = [
            'ID_TZ' => $tzId,
            'DEAL_ID' => $dealId,
            'DATE' => date('Y-m-d'), //Записывается при присвоении номера TODO: Записывать 2 раза?
            'DATE_END' => date('Y-m-d')
        ];

        $protocolId = $result->addProtocols($protocolData);


        if (empty($protocolId)) {
            $this->showErrorMessage("Не удалось создать протокол");
            $this->redirect($location);
        } else {
            $this->showSuccessMessage("Протокол создан успешно");
            $this->redirect($location . "?protocol_id={$protocolId}");
        }
    }

    /**
     * route /result/updateProtocol/
     * @desc Обновляет информацию по протоколу
     */
    public function updateProtocol()
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0) {
            $this->redirect('/request/list/');
        }

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');
        /** @var Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $dealId = (int)$_POST['deal_id'];
        $protocolId = (int)$_POST['protocol_id'];
        $selected = $_POST['selected'] ? '&selected' : '';

        $location = $protocolId ? "/result/card_oati/{$dealId}?protocol_id={$protocolId}{$selected}" : "/result/card_oati/{$dealId}";

        $deal = $requestModel->getDealById($dealId);
        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirementModel->getTzByDealId($dealId);
        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        if (empty($protocolId)) {
            $this->showErrorMessage("Не удалось обновить данные протокола, отсутствует или неверно указан ИД протокола");
            $this->redirect($location);
        }

        $protocol = $resultModel->updateProtocol($protocolId, $_POST['protocol']);
        $protocolModel->unlinkProtocolPdf($protocolId, $tz['ID']);

        //Обновление данных "начало/окончание" испытаний
        $ugtpToProtocol = $resultModel->getUGTPByProtocolId($protocolId);
        $ugtpIds = array_column($ugtpToProtocol, 'id');

        if (!empty($ugtpIds) && (!empty($_POST['protocol']['CHANGE_TRIALS_DATE']) || $deal['TYPE_ID'] == TYPE_DEAL_NK)) {
            $resultModel->changeStartTrials($ugtpIds, $_POST['protocol']);
        }

        //Оборудование
        $_POST['oborud']['deal_id'] = $dealId;
        $protocolModel->saveOborud($protocolId, $_POST['oborud']);

        if ($protocol !== 1) {
            $this->showErrorMessage("Не удалось обновить данные протокола");
            $this->redirect($location);
        } else {
            $this->showSuccessMessage("Данные протокола сохранены успешно");
            $this->redirect($location);
        }
    }

    /**
     * @desc Отмечает протокол недействительным, если соответствующий чекбокс активен
     * route /result/protocolIsInvalid/{$protocolId}
     * @param $protocolId
     */
    public function protocolIsInvalid($protocolId)
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0 || !isset($_POST['protocol_is_invalid'])) {
            $this->redirect('/ulab/request/list/');
        }

        $dealId = (int)$_POST['deal_id'];
        $location = "/result/card_oati/{$dealId}?protocol_id={$protocolId}";

        if (empty($protocolId) || $protocolId < 0) {
            $this->showErrorMessage("Ошибка отсутствия выбранного протокола");
            $this->redirect($location);
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Urer $user */
        $user = $this->model('User');
        /** @var Protocol $protocol */
        $protocolModel = $this->model('Protocol');
        /** @var History $historyModel */
        $historyModel = $this->model('History');
        /** @var Permission $permissionModel */
        $permissionModel = $this->model('Permission');


        $deal = $request->getDealById($dealId);
        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);
        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tzId = $tz['ID'] ?: null;


        $permissionInfo = $permissionModel->getUserPermission(App::getUserId());
        $protocolData = $result->getProtocolById($protocolId);
        $currentUserId = $user->getCurrentUserId();
        $currentUser = $user->getCurrentUser();

        if ( !empty($protocolData['INVALID']) ) {
            $this->showErrorMessage("Ошибка, протокол является недействительным");
            $this->redirect($location);
        }

        // Проверка на доступ к признанию протокола недействительным
        $isMayInvalid = in_array($permissionInfo['id'],  [ADMIN_PERMISSION, HEAD_IC_PERMISSION]);
        if (!$isMayInvalid) {
            $this->showErrorMessage("Ошибка, отсутствует доступ признать протокол недействительным");
            $this->redirect($location);
        }

        $action = !empty($_POST['protocol_is_invalid']) ? 1 : 0;
        $data = [
            'protocol_id' => null
        ];
        $umtrUpdate = $result->updateMaterialToRequestByProtocolId($protocolId, $data);


        if (!$umtrUpdate) {
            $this->showErrorMessage('Протокол не удалось открепить от проб');
            $this->redirect($location);
        } else {
            $protocolData = [
                'INVALID_USER' => $currentUserId,
                'INVALID' => $action,
                'INVALID_DATE' => date('Y-m-d'),
            ];
            $protocolModel->update($protocolId, $protocolData);

            $historyData = [
                'DATE' => date('Y-m-d H:i:s'),
                'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
                'PROT_NUM' => $protocolData['NUMBER'] ?: null,
                'TZ_ID' => $tzId,
                'USER_ID' => $currentUserId,
                'TYPE' => 'Протокол откреплен от проб(протокол недействителен)',
                'REQUEST' => $deal['TITLE'],
                'PROTOCOL_ID' => $protocolId
            ];
            $historyModel->addHistory($historyData);


            $this->showSuccessMessage('Протокол успешно откреплен от проб');
            $this->redirect($location);
        }
    }

    /**
     * route /result/editResults/{$protocolId}
     * @desc Делает данные протокола доступными для редактирования
     * @param $protocolId
     */
    public function editResults($protocolId)
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0) {
            $this->redirect('/request/list/');
        }

        $dealId = (int)$_POST['deal_id'];
        $selected = $_POST['selected'] ? '&selected' : '';
        $location = $protocolId ? "/result/card_oati/{$_POST['deal_id']}?protocol_id={$protocolId}{$selected}" : "/result/card_oati/{$_POST['deal_id']}";
        $successMessage = isset($_POST['edit_results']) ?
            'Данные протокола успешно разблокированы' : 'Данные протокола успешно заблокированы для редактирования';
        $errorMessage = isset($_POST['edit_results']) ?
            'Не удалось разблокировать данные протокола' : 'Не удалось заблокировать данные протокола для редактирования';

        if (empty($protocolId) || $protocolId < 0) {
            $this->showErrorMessage("Ошибка отсутствия выбранного протокола");
            $this->redirect($location);
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');


        $deal = $request->getDealById($dealId);
        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);
        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        $editResults = !empty($_POST['edit_results']) ? 1 : 0;

        // TODO: Добавить проверку на пользователя


        $data = [
            'EDIT_RESULTS' => $editResults
        ];

        $updateProtocol = $result->updateProtocolById($protocolId, $data);

        if ($updateProtocol !== 1) {
            $this->showErrorMessage($errorMessage);
            $this->redirect($location);
        } else {
            $this->showSuccessMessage($successMessage);
            $this->redirect($location);
        }
    }

    /**
     * route /result/deleteProtocol/{$protocolId}
     * @desc Удаляет протокол
     * @param $protocolId
     */
    public function deleteProtocol($protocolId)
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0 || !isset($_POST['delete_protocol'])) {
            $this->redirect('/request/list/');
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');


        $dealId = (int)$_POST['deal_id'];
        $location = "/result/card_oati/{$dealId}";

        $deal = $request->getDealById($dealId);

        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        $umtr = $result->getMaterialToRequestByProtocolId($protocolId);
        $protocolData = $result->getProtocolById($protocolId);


        if (!empty($protocolData['NUMBER'])) {
            $this->showErrorMessage("Ошибка удаления протокола, у текущего протокола есть присвоенный номер");
            $this->redirect($location . "?protocol_id={$protocolId}");
        }

        if (!empty($umtr)) {
            $this->showErrorMessage("Не удалось удалить протокол, к протоколу привязаны пробы");
            $this->redirect($location . "?protocol_id={$protocolId}");
            die();
        }


        $delTzObConnect = $oborudModel->delTzObConnectByProtocolId($protocolId);

        if (empty($delTzObConnect['success']) && !empty($delTzObConnect['error'])) {
            $this->showErrorMessage($delTzObConnect['error']);
            $this->redirect($location);
        }


        $result->deleteProtocolById($protocolId);


        $this->showSuccessMessage("Протокол удалён");
        $this->redirect($location);
    }

    /**
     * @desc Загружает PDF версию протокола
     * route /result/uploadPdf/{$protocolId}
     * @param $protocolId
     */
    public function uploadPdf($protocolId)
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0 || !isset($_FILES['upload_pdf'])) {
            $this->redirect('/request/list/');
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');


        $dealId = (int)$_POST['deal_id'];
        $selected = $_POST['selected'] ? '&selected' : '';
        $location = $protocolId ? "/result/card_oati/{$_POST['deal_id']}?protocol_id={$protocolId}{$selected}" : "/result/card_oati/{$_POST['deal_id']}";

        $deal = $request->getDealById($dealId);

        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        if (!empty($_FILES['upload_pdf']['tmp_name'])) {
            $time = date('d-m-Y') . '-' . time();
            $fileName = $time . '-' . $_FILES['upload_pdf']['name'];
            $savePdfFile = $result->savePdfFile("result/pdf/{$protocolId}", $_FILES['upload_pdf'], $fileName);


            //TODO: Временно сохраняет pdf файлы, для работы остальных скриптов до их рефакторинга
            //start
            if ( !is_dir($_SERVER['DOCUMENT_ROOT'] . "/pdf/{$protocolId}") ) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . "/pdf/{$protocolId}", 0766, true);
            }

            copy(UPLOAD_DIR . "/result/pdf/{$protocolId}/" . $fileName, $_SERVER['DOCUMENT_ROOT'] . "/pdf/{$protocolId}/" . $fileName);

            $protocolData = [
                'PDF' => $fileName,
            ];

            $result->updateProtocolById($protocolId, $protocolData);
            //end


            if (!empty($savePdfFile['success'])) {
                $this->showSuccessMessage("PDF-версия успешно загружена");
                $this->redirect($location);
            }

            if (!empty($savePdfFile['error'])) {
                $this->showErrorMessage($savePdfFile['error']);
                $this->redirect($location);
            }
        }
    }

    /**
     * @desc Удаляет PDF версию протокола
     * route /result/deletePdf/{$protocolId}
     * @param $protocolId
     */
    public function deletePdf($protocolId)
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0 || empty($protocolId) || !isset($_POST['delete_pdf']) ||
            empty($_POST['file'])) {
            $this->redirect('/request/list/');
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');


        $dealId = (int)$_POST['deal_id'];
        $selected = $_POST['selected'] ? '&selected' : '';
        $location = $protocolId ? "/result/card_oati/{$_POST['deal_id']}?protocol_id={$protocolId}{$selected}" : "/result/card_oati/{$_POST['deal_id']}";

        $deal = $request->getDealById($dealId);

        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        if (!unlink(UPLOAD_DIR . "/result/pdf/{$protocolId}/" . $_POST['file'])) {
            $this->showErrorMessage("PDF-версия {$_POST['file']} не может быть удалена из-за ошибки");
            $this->redirect($location);
        } else {
            //TODO: Временно, удаляет pdf файлы, для работы остальных скриптов до их рефакторинга
            //start
            if (unlink($_SERVER['DOCUMENT_ROOT'] . "/pdf/{$protocolId}/" . $_POST['file'])) {
                $protocolData = [
                    'PDF' => '',
                ];

                $result->updateProtocolById($protocolId, $protocolData);
            }
            //end

            $this->showSuccessMessage("PDF-версия {$_POST['file']} успешно удалена");
            $this->redirect($location);
        }
    }

    /**
     * route /result/addProtocolNumber/{$protocolId}
     * @desc Присваивает номер протоколу
     * @param $protocolId
     */
    public function addProtocolNumber($protocolId)
    {
        if (empty($_POST['deal_id']) || $_POST['deal_id'] < 0 || !isset($_POST['add_protocol_number'])) {
            $this->redirect('/request/list/');
        }

        $dealId = (int)$_POST['deal_id'];
        $selected = $_POST['selected'] ? '&selected' : '';
        $location = $protocolId ? "/result/card_oati/{$_POST['deal_id']}?protocol_id={$protocolId}{$selected}" : "/result/card_oati/{$_POST['deal_id']}";

        if ( $dealId >= DEAL_NEW_RESULT ) {
            if (empty($_POST['selected_protocol']) || $protocolId !== $_POST['selected_protocol']) {
                $this->showErrorMessage("Внимание для присвоения номера выберите протокол");
                $this->redirect($location);
            }
        } else {
            if (empty($_POST['selected_protocol_id']) || $protocolId !== $_POST['selected_protocol_id']) {
                $this->showErrorMessage("Внимание для присвоения номера выберите протокол");
                $this->redirect($location);
            }
        }


        /** @var Result $result */
        $result = $this->model('Result');
        /** @var Requirement $requirement */
        $requirement = $this->model('Requirement');
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Protocol $protocol */
        $protocol = $this->model('Protocol');
        /** @var Urer $user */
        $user = $this->model('User');
        /** @var History $historyModel */
        $historyModel = $this->model('History');


        $deal = $request->getDealById($dealId);

        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $tz = $requirement->getTzByDealId($dealId);

        if (empty($tz)) {
            $this->showErrorMessage("Технического задания с ИД сделки {$dealId} не существует");
            $this->redirect('/request/list/');
        }


        if ( $dealId >= DEAL_NEW_RESULT ) {
            $umtr = $result->getMaterialToRequestByProtocolId((int)$_POST['selected_protocol']);
        } else {
            $umtr = $result->getMaterialToRequestByProtocolId((int)$_POST['selected_protocol_id']);
        }
        $protocolData = $result->getProtocolById($protocolId);
        $currentUserId = $user->getCurrentUserId();
        $currentUser = $user->getCurrentUser();
        $protocolsCount = $protocol->getProtocolsCount();


        $tzId = $tz['ID'] ?: null;
        $currentDate = date('Y-m-d');
        $year = (int)date("Y") % 10 ? substr(date("Y"), -2) : date("Y");
        $protocolNumber = !empty($protocolsCount['count_protocols']) ? $protocolsCount['count_protocols'] + 1 : 1;
        $protocolNumberAndYear = "{$protocolNumber}/{$year}";


        if (!empty($protocolData['NUMBER'])) {
            $this->showErrorMessage("Ошибка присвоения номера протоколу, у текущего протокола уже есть присвоенный номер");
            $this->redirect($location);
        }

        if (empty($umtr)) {
            $this->showErrorMessage("Внимание для присвоения номера выберите и сохраните пробы для протокола");
            $this->redirect($location);
        }

        if (empty($protocolData['DATE_BEGIN']) || $protocolData['DATE_BEGIN'] === '0000-00-00') {
            $this->showErrorMessage("Не сохранена дата начала испытаний, заполните данные и сохраните изменения");
            $this->redirect($location);
        }

        if (empty($protocolData['DATE_END']) || $protocolData['DATE_END'] === '0000-00-00') {
            $this->showErrorMessage("Не сохранена дата окончания испытаний, заполните данные и сохраните изменения");
            $this->redirect($location);
        }

        if (empty($protocolData['ACTUAL_VERSION'])) {
            $this->showErrorMessage("Протокол не сформирован, для присвоения номера сформируйте протокол");
            $this->redirect($location);
        }


        $data = [
            'NUMBER' => $protocolNumber,
            'NUMBER_AND_YEAR' => $protocolNumberAndYear,
            'DATE' => $currentDate,
            'ACTUAL_VERSION' => null
        ];

        $updateProtocol = $result->updateProtocolById($protocolId, $data);

        if ($updateProtocol !== 1) {
            $this->showErrorMessage("Не удалось присвоить номер протоколу");
            $this->redirect($location);
        } else {
            //Стадия заявки
            $probsWithoutProtocolNum = $result->getProbsWithoutProtocolNum($dealId);

            if (empty($probsWithoutProtocolNum)) {
                $request->updateStageDeal($dealId, 2);
            }


            //TODO: Временное сохранение данных, для работы остальных скриптов до их рефакторинга
            //start
            $tzData = [
                'NUM_P_TABLE' => $protocolNumberAndYear,
                'NUM_P' => $protocolNumber,
                'DATE_P_CORR' => $currentDate,
            ];

            $result->updateTzByDealId($dealId, $tzData);
            //end


            $historyType[] = 'Присвоен номер протоколу';
            if (!empty($protocolData['NUMBER']) && !empty($protocolNumber)  && $protocolData['NUMBER'] !== $protocolNumber) {
                $historyType[] = 'Изменён номер протокола';
            }

            $strType = implode('. ', $historyType);

//            $request->getStatusProtocols($dealId, App::getUserId());

            $historyData = [
                'DATE' => date('Y-m-d H:i:s'),
                'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
                'PROT_NUM' => $protocolNumber,
                'TZ_ID' => $tzId,
                'USER_ID' => $currentUserId,
                'TYPE' => $strType,
                'REQUEST' => $deal['TITLE'],
                'PROTOCOL_ID' => $protocolId
            ];

            $historyModel->addHistory($historyData);


            $this->showSuccessMessage("Номер протоколу успешно присвоен");
            $this->redirect($location);
        }
    }


    /**
     * @desc Сохраняет комнату температуру влажность и стартует испытание
     */
    public function saveRoomStart()
    {
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $resultModel->saveRoomStart($_POST['form']);

        $this->showSuccessMessage("Данные записаны");

        $selectedProtocol = empty($_POST['selected_protocol_id'])? '' : "?protocol_id={$_POST['selected_protocol_id']}";

        $this->redirect("/result/card_oati/" . (int)$_POST['deal_id'] . $selectedProtocol);
    }


    /**
     * @desc сохраняем листы измерения
     */
    public function saveMeasurementData()
    {
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        foreach ($_POST['form_data'] as $ugtpId => $data) {
            $resultModel->saveMeasurementDataNew($data, (int)$ugtpId);
        }

        $this->showSuccessMessage("Листы измерения сохранены");
        $this->redirect("/result/card_oati/{$_POST['deal_id']}");
    }


    /**
     * @desc Проверка на соответствие выбранных и сохраненных проб
     * return json
     */
    public function checkingTrialResultsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $selectedProtocolId = (int)$_POST['selected_protocol_id'];

        if (!empty($selectedProtocolId) && $selectedProtocolId > 0) {
            /** @var Result $result */
            $result = $this->model('Result');

            $umtr = $result->getMaterialToRequestByProtocolId($selectedProtocolId);

            $response = [
                'success' => true
            ];
        } else {
            $response = [
                'success' => false,
                'error' => [
                    'message' => 'Внимание для присвоения номера выберите протокол'
                ]
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Возвращает оборудование по умолчанию
     * return json
     */
    public function revertDefaultAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $response = [
            'success' => false,
            'error' => [
                'message' => "Не удалось вернуть оборудование по умолчанию, не указан или указан неверно ИД протокола",
            ]
        ];

        $protocolId = (int)$_POST['protocol_id'];

        if (!empty($protocolId) && $protocolId > 0) {
            /** @var Oborud $oborudModel */
            $oborudModel = $this->model('Oborud');

            $response = $oborudModel->delTzObConnectByProtocolId($protocolId);

            if ($response['success']) {
                $defaultEquipment = $oborudModel->oborudsByProtocolId($protocolId);

                $equipmentIds = !empty($defaultEquipment) ? array_keys($defaultEquipment) : [];
                sort($equipmentIds);

                $response['default_equipment'] = $defaultEquipment;
                $response['default_equipment_ids'] = $equipmentIds;

                $this->showSuccessMessage('Данные успешно обновлены');
            } else {
                $this->showErrorMessage($response['error']['message'] ?: '');
            }
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает лист измерения
     * return json
     */
    public function getMeasurementSheetAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');

        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        if ( isset($_POST['measurement_object']) ) {
            $tmpName = '';
            foreach ($_POST['measurement_object'] as $item) {
                $measurementId = (int)$item['measurement_id'];
                $ugtpId = (int)$item['ugtpId'];
                $methodId = (int)$item['methodId'];

                $sheet = $resultModel->getMeasurement($measurementId);
                $ugtp = $requirementModel->getGostToProbe($ugtpId);
                $probe = $resultModel->getProbeByUgtpId($ugtpId);
                if ( method_exists($resultModel, $sheet['name']) ) {
                    $this->data['measuring_property'] = $resultModel->{$sheet['name']}($ugtp, $methodId);
                }

                $this->data['measuring'] = $ugtp['measuring_sheet'] ?? [];
                $this->data['sheet'] = $sheet;
                $this->data['ugtp_id'] = $ugtpId;
                $this->data['probe'] = $probe;

                if ( $tmpName !== $sheet['name'] ) {
                    $this->addJs("/assets/js/measuring-sheet/{$sheet['name']}.js?v=" . rand());
                    $tmpName = $sheet['name'];
                }

                $this->viewEmpty("template/{$sheet['name']}");
            }
        }
    }

    /**
     * @desc Сохраняет данные листа измерения
     */
    public function saveMeasurementDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $ugtpId = (int)$_POST['ugtp_id'];
        if (!empty($ugtpId) && $ugtpId > 0) {
            $result = $resultModel->saveMeasurementData($_POST['form_data'], $ugtpId);

            echo $result;
        }
    }

    /**
     * @desc Начать испытание
     */
    public function startTrialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $ugtpId = (int)$_POST['ugtp_id'];
        $ugtpIds = [$ugtpId];
        $protocolId = (int)$_POST['protocol_id'];
        $dateStart = date('Y-m-d');
        $dateEnd = '';


        // проверяем есть ли гост методики по которой начали испытание
        if ( empty($ugtpId) ) {
            $response = [
                'success' => false,
                'errors' => ["Не удалось начать испытание, не указан или указан неверно ИД привязки госта"],
                'data' => '',
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            die();
        }


        // Проверяем на кол-во помещений у методик перед началом испытаний, если более 1 то список для выбора помещений, если нет помещений то ошибка - сообщение
        $checkCountRooms = $labModel->checkCountRooms($ugtpIds);
        if ( !$checkCountRooms['success'] ) {
            echo json_encode($checkCountRooms, JSON_UNESCAPED_UNICODE);
            die();
        }


        // Проверяем есть ли невыбранные помещения, если ни одного помещения у методики то ошибка, если не выбрано то записываем первое
        $checkNotSelectedRooms = $labModel->checkNotSelectedRooms($ugtpIds);
        if ( !empty($checkNotSelectedRooms['errors']) ) {
            echo json_encode($checkNotSelectedRooms, JSON_UNESCAPED_UNICODE);
            die();
        }
        foreach ($checkNotSelectedRooms['data'] as $key => $val) {
            $rooms = [];
            if ( empty($val['selected_room']) ) {
                $rooms[$val['ugtp_id']][] = (int)$val['rooms'][0]['ID'];
                $resultModel->saveSelectedRooms($rooms);
            }
        }


        // Валидация
        /*$validate = $methodsModel->validateMethods($ugtpIds, $dateStart, $dateEnd, $protocolId);
        if (!$validate['success']) {
            echo json_encode($validate, JSON_UNESCAPED_UNICODE);
            die();
        }*/


        // Старт
        if ( !empty($ugtpId) ) {
            $response = $resultModel->startTrial($ugtpId);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Приостановить испытание
     */
    public function pauseTrialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $ugtpId = (int)$_POST['ugtp_id'];
        $ugtpIds = [$ugtpId];
        $protocolId = (int)$_POST['protocol_id'];
        $dateEnd = date('Y-m-d');

        if ( empty($ugtpId) ) {
            $response = [
                'success' => false,
                'errors' => ["Не удалось приостановить испытание, не указан или указан неверно ИД привязки госта"],
                'data' => '',
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            die();
        }

        $startTrials = $resultModel->getLastStartTrials($ugtpId);
        $dateStart = $startTrials['date'] ?? '';

        // Валидация
        /*$validate = $methodsModel->validateMethods($ugtpIds, $dateStart, $dateEnd, $protocolId);
        if (!$validate['success']) {
            echo json_encode($validate, JSON_UNESCAPED_UNICODE);
            die();
        }*/

        // Приостановить
        if ( !empty($ugtpId) ) {
            $response = $resultModel->pauseTrial($ugtpId);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Остановить испытание
     */
    public function stopTrialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $ugtpId = (int)$_POST['ugtp_id'];
        $ugtpIds = [$ugtpId];
        $protocolId = (int)$_POST['protocol_id'];
        $dateEnd = date('Y-m-d');

        if ( empty($ugtpId) ) {
            $response = [
                'success' => false,
                'errors' => ["Не удалось остановить испытание, не указан или указан неверно ИД привязки госта"],
                'data' => '',
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            die();
        }

        $startTrials = $resultModel->getLastStartTrials($ugtpId);
        $dateStart = $startTrials['date'] ?? '';

        // Валидация
        //$validate = $methodsModel->validateMethods($ugtpIds, $dateStart, $dateEnd, $protocolId);

        // остановить испытание
        if ( !empty($ugtpId) ) {
            $response = $resultModel->stopTrial($ugtpId);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Проверить данные для проведения испытаний
     */
    public function checkTrialsDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $protocolId = (int)$_POST['protocol_id'];
        $dateStart = $_POST['date_start'] ?: '';
        $dateEnd = $_POST['date_end'] ?: '';

        $ugtp = $resultModel->getUGTPNotSelection($protocolId);

        $ugtpIds = array_column($ugtp, 'id');
        $ugtpIds = array_map('intval', $ugtpIds);

        $response = [
            'success' => true,
            'errors' => [],
            'data' => '',
        ];

        if ( empty($ugtpIds) ) {
            $response = [
                'success' => false,
                'errors' => ["Ошибка, не указаны или указаны неверно ИД привязки гостов"],
                'data' => '',
            ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            die();
        }

        // Проверяем на кол-во помещений у методики перед началом испытаний, если более 1 то список для выбора помещений
        $checkCountRooms = $labModel->checkCountRooms($ugtpIds);
        if ( !$checkCountRooms['success'] ) {
            echo json_encode($checkCountRooms, JSON_UNESCAPED_UNICODE);
            die();
        }

        // Проверяем есть ли невыбранные помещения, если ни одного помещения у методики то ошибка, если не выбрано то записываем первое
        $checkNotSelectedRooms = $labModel->checkNotSelectedRooms($ugtpIds);
        if ( !empty($checkNotSelectedRooms['errors']) ) {
            echo json_encode($checkCountRooms, JSON_UNESCAPED_UNICODE);
            die();
        }
        foreach ($checkNotSelectedRooms['data'] as $key => $val) {
            $rooms = [];
            if ( empty($val['selected_room']) ) {
                $rooms[$val['ugtp_id']][] = (int)$val['rooms'][0]['ID'];
                $resultModel->saveSelectedRooms($rooms);
            }
        }

        // Валидация
        $validate = $methodsModel->validateMethods($ugtpIds, $dateStart, $dateEnd, $protocolId);
        if (!$validate['success']) {
            echo json_encode($validate, JSON_UNESCAPED_UNICODE);
            die();
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Сохранить выбранные помещения для испытания
     */
    public function saveSelectedRoomsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        //Сохраняем выбранные помещения для проведений испытаний, если помещений у методики было более 1
        if ( !empty($_POST['rooms']) ) {
            $selectedRooms = [];
            parse_str($_POST['rooms'], $selectedRooms);

            $resultModel->saveSelectedRooms($selectedRooms['rooms']);
        }

        $response = [
            'success' => true,
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получить данные условий Ajax запросом
     */
    public function getConditionsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $protocolId = (int)$_POST['protocol_id'];

        if (!empty($protocolId) && $protocolId > 0) {
            $conditions = $labModel->getConditionByProtocol($protocolId);

            $response = [
                'success' => true,
                'errors' => [],
                'data' => $conditions,
            ];
        } else {
            $response = [
                'success' => false,
                'errors' => ["Ошибка, не указан или указан неверно ИД протокола"],
                'data' => '',
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получить данные протокола Ajax запросом
     */
    public function getProtocolAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $protocolId = (int)$_POST['protocol_id'];

        if (!empty($protocolId) && $protocolId > 0) {
            $protocol = $resultModel->getProtocolById($protocolId);

            $response = [
                'success' => true,
                'errors' => [],
                'data' => $protocol,
            ];
        } else {
            $response = [
                'success' => false,
                'errors' => ["Ошибка, не указан или указан неверно ИД протокола"],
                'data' => '',
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Валидация протокола по условиям Ajax запросом
     */
    public function validateProtocolAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $protocolId = (int)$_POST['protocol_id'];

        $response = [
            'success' => true,
            'errors' => [],
            'data' => '',
        ];

        $trialsRange = $labModel->getTrialsRange($protocolId);
        $ugtp = $resultModel->getUGTPNotSelection($protocolId);

        foreach ($ugtp as $val) {
            $ugtpId = (int)$val['id'];

            $stateLastAction = $resultModel->getStateLastAction($ugtpId);
            $method = $methodsModel->getMethodByUgtpId($ugtpId);

            $anchor = "<a href='".URI."/gost/method/{$method['id']}'>{$method['view_gost_for_protocol']}</a>";

            if ( empty($stateLastAction) ) {
                $response = [
                    'success' => false,
                    'errors' => ["Отсутствуют данные, начала и окончания испытаний, методика {$anchor}"],
                    'data' => '',
                ];
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                die();
            }

            if ( $stateLastAction['state'] !== 'complete' ) {
                $response = [
                    'success' => false,
                    'errors' => ["Не завершены испытания по методике {$anchor}"],
                    'data' => '',
                ];
                echo json_encode($response, JSON_UNESCAPED_UNICODE);
                die();
            }
        }

        foreach ($trialsRange as $val) {
            $ugtpIds = [$val['ugtp_id']];
            $dateStart = $val['date_start'];
            $dateEnd = $val['date_end'];

            // Валидация
            $validate = $methodsModel->validateMethods($ugtpIds, $dateStart, $dateEnd, $protocolId);
            if (!$validate['success']) {
                echo json_encode($validate, JSON_UNESCAPED_UNICODE);
                die();
            }
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Страница журнала статискики испытаний
     */
    public function trialStatistics()
    {
        $this->data['title'] = 'Журнал статискики испытаний';

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");

        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.colVis.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.print.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.html5.min.js");
        $this->addJS("/assets/plugins/DataTables/JSZip-2.5.0/jszip.min.js");

        $r = rand();
        $this->addJs("/assets/js/statistics-start-stop.js?v={$r}");

        $this->view('statistics');
    }

    /**
     * @desc Получение данных статистики испытаний для журнала
     */
    public function getTrialStatisticsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $filter = $resultModel->prepareFilter($_POST ?? []);

        $data = $resultModel->getTrialStatisticsList($filter);

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
     * @desc Получение данные о начале и окончании испытания
     */
    public function getStartStopTrialsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $filter = $resultModel->prepareFilter($_POST ?? []);

        if ( !empty($_POST['method_id']) ) {
            $filter['search']['method_id'] = (int)$_POST['method_id'];
        }

        $data = $resultModel->getStartStopTrials($filter);

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
     * @desc Получить информацию по протоколу Ajax запросом
     */
    public function getProtocolInfoAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');
        /** @var User $userModel */
        $userModel = $this->model('User');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');
        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $response = [];
        $dealId = (int)$_POST['deal_id'];
        $protocolId = (int)$_POST['protocol_id'];

        if ( !empty($protocolId) && $protocolId > 0 ) {
            $response['protocol'] = $resultModel->getProtocolById($protocolId);
            $response['assigned'] = !empty($dealId) ? $userModel->getAssignedByDealId($dealId) : [];
            $response['dates_trials'] = $resultModel->getStartTrialsByProtocol($protocolId);
            $response['oboruds'] = $oborudModel->getOboruds();
            $oborudsToGosts = $oborudModel->oborudsByProtocolId($protocolId);
            $conditions = $labModel->getConditionByProtocol($protocolId);
            $tzObConnect = $oborudModel->getTzObConnectByProtocolId($protocolId);
            $tz = !empty($dealId) ? $requirementModel->getTzByDealId($dealId) : [];
            $umtr = $resultModel->getMaterialToRequestByProtocolId($protocolId);
            $deal = $requestModel->getDealById($dealId);

            if ( !empty($response['protocol']['DATE_BEGIN']) ) {
                $response['dates_trials']['date_begin'] = $response['protocol']['DATE_BEGIN'];
            }
            if ( !empty($response['protocol']['DATE_END']) ) {
                $response['dates_trials']['date_end'] = $response['protocol']['DATE_END'];
            }

            // Если checkbox "Изменить условия испытаний" не отмечен и тип заявки не НК, то берём данные из "Журнала условий"
            if ( empty($response['protocol']['CHANGE_TRIALS_CONDITIONS']) && $deal['TYPE_ID'] != TYPE_DEAL_NK) {
                $response['conditions']['TEMP_O'] = $conditions['min_temp'] ? round($conditions['min_temp'], 1) : null;
                $response['conditions']['TEMP_TO_O'] = $conditions['max_temp'] ? round($conditions['max_temp'], 1) : null;
                $response['conditions']['VLAG_O'] = $conditions['min_humidity'] ? round($conditions['min_humidity'], 1) : null;
                $response['conditions']['VLAG_TO_O'] = $conditions['max_humidity'] ? round($conditions['max_humidity'], 1) : null;
            } else {
                $response['conditions']['TEMP_O'] = $response['protocol']['TEMP_O'] ?? null;
                $response['conditions']['TEMP_TO_O'] = $response['protocol']['TEMP_TO_O'] ?? null;
                $response['conditions']['VLAG_O'] = $response['protocol']['VLAG_O'] ?? null;
                $response['conditions']['VLAG_TO_O'] = $response['protocol']['VLAG_TO_O'] ?? null;
            }

            // Выбранное оборудование для протокола
            $protocolEquipment = !empty($tzObConnect) ? $tzObConnect : $oborudsToGosts;

            // ИД выбранного оборудования для протокола
            $equipmentIds = !empty($protocolEquipment) ? array_keys($protocolEquipment) : [];
            sort($equipmentIds);

            $response['protocol_equipment'] = $protocolEquipment;
            $response['equipment_ids_json'] = json_encode($equipmentIds);
            $response['is_deal_osk'] = $deal['TYPE_ID'] == 'COMPLEX';
            $response['is_deal_nk'] = $deal['TYPE_ID'] == TYPE_DEAL_NK;

            //Данные объекта испытаний
            $response['object_data']['DESCRIPTION'] = $response['protocol']['DESCRIPTION'] ?: ($tz['DESCRIPTION'] ?? '');
            $response['object_data']['OBJECT'] = $response['protocol']['OBJECT'] ?: ($tz['OBJECT'] ?: '');
            $response['object_data']['PLACE_PROBE'] = $response['protocol']['PLACE_PROBE'] ?: ($umtr[0]['place'] ?? '');
            $response['object_data']['DATE_PROBE'] = $response['protocol']['DATE_PROBE'] ?:
                (!empty($umtr[0]['date_probe']) && $umtr[0]['date_probe'] !== '0000-00-00' ? $umtr[0]['date_probe'] : '');
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает данные проб и град. зависимости для НК
     */
    public function getUgtpAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Requirement $requirementModel */
        $requirementModel = $this->model('Requirement');
        /** @var Nk $nkModel */
        $nkModel = $this->model('Nk');

        $ugtpId = (int)$_POST['ugtp_id'];

        $response = $requirementModel->getGostToProbe($ugtpId);

        if ($response['measuring_sheet']['scheme'] === 'v') {
            $response['gradation'] = $nkModel->getGraduationList();
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные проб и методик для результатов испытаний
     */
    public function getMethodsProbeJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $filter = $resultModel->prepareFilter($_POST ?? []);

        $data = $resultModel->getMethodProbeJournal((int)$_POST['deal_id'], $filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Начинает испытание для пробы
     */
    public function newStartTrialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        foreach ($_POST['probe_id_list'] as $ugtpId) {
            $resultModel->startTrial((int)$ugtpId);
        }
    }


    /**
     * @desc Приостанавливает испытание для пробы
     */
    public function newPauseTrialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        foreach ($_POST['probe_id_list'] as $ugtpId) {
            $resultModel->pauseTrial((int)$ugtpId);
        }
    }


    /**
     * @desc Останавливает испытание для пробы
     */
    public function newStopTrialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        foreach ($_POST['probe_id_list'] as $ugtpId) {
            $resultModel->stopTrial((int)$ugtpId);
        }
    }


    /**
     * @desc Получает данные о помещениях и условий окружающей среды для проб
     */
    public function getRoomsConditionsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $result = [];
        foreach ($_POST['probe_id_list'] as $ugtpId) {

            if ( empty($ugtpId) ) { continue; }

            $startInfo = $resultModel->getStateLastAction((int)$ugtpId);

            if ( empty($startInfo) ) {
                $info = $resultModel->getConditionRoomMethod((int)$ugtpId);
                $result[$info['umtr_id']][] = $info;
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }



    public function getSaveCellDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Result $resultModel */
        $resultModel = $this->model('Result');

        $data[$_POST['cell']] = $_POST['val'];

        $resultModel->updateUlabGostToProbe((int)$_POST['ugtp_id'], $data);

        echo json_encode([], JSON_UNESCAPED_UNICODE);
    }
}
