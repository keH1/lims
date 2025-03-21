<?php

/**
 * @desc Страница с ГОСТами
 * Class GostController
 */
class GostController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Журнал области аккредитации»
     */
    public function index()
    {
        $this->redirect('/gost/list/');
    }


    /**
     * @desc страница матрицы компетентности
     */
    public function matrix()
    {
        $this->data['title'] = 'Матрица компетентности';

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $this->data['lab_user_list'] = $labModel->getLabAndUser();
        $this->data['lab_list'] = $labModel->getList();


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

        $this->addJS("/assets/js/gost-matrix.js");

        $this->view('matrix');
    }


    /**
     * @desc Отчет об использовании области аккредитации
     */
    public function report()
    {
        $this->data['title'] = 'Отчет об использовании области аккредитации';

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $this->data['lab_list'] = $labModel->getList();


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

        $this->addJS("/assets/js/gost-report.js");

        $this->view('reportOA');
    }


    protected function form()
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $this->data['unit_list'] = $methodsModel->getUnitList();
        $this->data['test_method_list'] = $methodsModel->getTestMethodList();


        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
		$this->addCSS("/assets/plugins/DataTables/datatables.min.css");
		$this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
		$this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
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

        $this->addJS("/assets/js/gost-form.js");

        $this->view('form');
    }


    /**
     * @desc Страница создания ГОСТа
     */
    public function new()
    {
        $this->data['title'] = 'Создание ГОСТа';

        $this->form();
    }


    /**
     * @desc Страница редактирования ГОСТа
     * @param $id
     */
    public function edit($id)
    {
        $this->data['title'] = 'Редактирование ГОСТа';

        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $this->data['id'] = $id;
        $this->data['form'] = $gostModel->getGost($id);

        $this->data['methods_list'] = $methodsModel->getListByGostId($id);

        $this->form();
    }


    /**
     * @desc Страница редактирования Методики
     * @param $id
     */
    public function method($id)
    {
        $this->data['title'] = 'Редактирование Методики';

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        /** @var User $userModel */
        $userModel = $this->model('User');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');

        //// данные методики
        $this->data['lab'] = $methodsModel->getLab($id);
        $this->data['room'] = $methodsModel->getRoom($id);
        $this->data['assigned'] = $methodsModel->getAssigned($id);
        $this->data['uncertainty'] = $methodsModel->getUncertainty($id);
        $this->data['method_oborud_list'] = $methodsModel->getOborud($id);

        $this->data['oborud'] = $oborudModel->getOborutByRooms($this->data['room']);

        $this->data['form'] = $methodsModel->get($id);

        if ( !$this->data['form']['is_confirm'] ) {
            $this->showWarningMessage('Методика не проверена отделом метрологии');
        }
        if ( !$this->data['form']['is_actual'] ) {
            $this->showErrorMessage('Внимание! Методика отмечена как неактуальная');
        }

        ///\ данные методики

        //// заполнение формы
        $this->data['measurement_list'] = $methodsModel->getMeasurementList(); // листы измерений
        $this->data['measured_properties'] = $methodsModel->getMeasuredPropertiesList();
        $this->data['unit_list'] = $methodsModel->getUnitList();
        $this->data['test_method_list'] = $methodsModel->getTestMethodList();
        $this->data['lab_list'] = $labModel->getList();
        $this->data['room_list'] = $labModel->getRoomByLabId($this->data['lab']);
//        $this->data['assigned_list'] = $userModel->getAssignedUserListByLab($this->data['lab']);
//        $this->data['lab_user_list'] = $labModel->getLabAndUser();
        $this->data['user_list'] = $userModel->getUserList1();
        ///\ заполение формы
//        $userModel->pre($this->data['user_list']);

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addJS("/assets/js/methods-form.js");

        $this->view('method');
    }


    /**
     * @desc Сделать все методики в ГОСТе неактуальными
     * @param $id
     */
    public function nonActualGost($id)
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $methodsModel->deleteByGost($id);

        $this->showSuccessMessage("Методики теперь неактуальны");
        $this->redirect("/gost/edit/{$id}");
    }


    /**
     * @desc Создание Методики
     */
    public function insertMethod()
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $location   = "/gost/edit/{$_POST['form']['gost_id']}";

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        //// блок проверок

        ///  \блок проверок

        $result = $methodsModel->add($_POST['form']);

        if ( empty($result) ) {
            $this->showErrorMessage("ГОСТ не удалось сохранить");
            $this->redirect($location);
        } else {
            $this->showSuccessMessage("Методика добавлена");
            $this->redirect($location . '#methods-block');
        }
    }


    /**
     * @desc Редактирование Методики
     */
    public function updateMethod()
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $methodId = (int)$_POST['id'];
        $location   = "/gost/method/{$methodId}";

        $data = $_POST['form'];

        $data['is_confirm'] = 0;

        if ( in_array($_SESSION['SESS_AUTH']['USER_ID'], [214]) ) {
            $data['is_confirm'] = 1;
        }

        $data['in_field'] = $_POST['form']['in_field'] ?? 0;
        $data['is_extended_field'] = $_POST['form']['is_extended_field'] ?? 0;
        $data['is_selection'] = $_POST['form']['is_selection'] ?? 0;
        $data['is_not_cond_temp'] = $_POST['form']['is_not_cond_temp'] ?? 0;
        $data['is_not_cond_wet'] = $_POST['form']['is_not_cond_wet'] ?? 0;
        $data['is_not_cond_pressure'] = $_POST['form']['is_not_cond_pressure'] ?? 0;
        $data['is_two_results'] = $_POST['form']['is_two_results'] ?? 0;
        $data['is_text_norm'] = $_POST['form']['is_text_norm'] ?? 0;
        $data['is_text_fact'] = $_POST['form']['is_text_fact'] ?? 0;
        $data['is_range_text'] = $_POST['form']['is_range_text'] ?? 0;

        $methodsModel->updateLab($methodId, $data['lab']);
        $methodsModel->updateRoom($methodId, $data['room']);
        $methodsModel->updateAssigned($methodId, $data['assigned']);
        $methodsModel->updateUncertainty($methodId, $_POST['uncertainty']);
        $methodsModel->updateOborud($methodId, $_POST['oborud']);

        $result = $methodsModel->update($methodId, $data);

        if ( empty($result) ) {
            $this->showErrorMessage("Методику не удалось обновить");
        } else {
            $this->showSuccessMessage("Методика обновлена");
        }

        $this->redirect($location);
    }


    /**
     * @desc Копирует ГОСТ
     * @param $gostId
     */
    public function copyGost($gostId)
    {
        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');

        $newId = $gostModel->copy($gostId);

        if ( !is_numeric($newId) || empty($newId) ) {
            $this->showErrorMessage("ГОСТ не удалось скопировать");
            $this->redirect("/gost/edit/{$gostId}");
        } else {
            $this->showSuccessMessage("ГОСТ успешно скопирован");
            $this->redirect("/gost/edit/{$newId}");
        }

        $this->redirect("/gost/edit/{$gostId}");
    }


    /**
     * @desc Копирует Методику
     */
    public function copyMethod()
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $result = $methodsModel->copyMethod((int)$_POST['method_id']);

        if ( empty($result) ) {
            $this->showErrorMessage("Методику не удалось скопировать");
        } else {
            $this->showSuccessMessage("Методика скопирована");
        }

        $this->redirect("/gost/edit/{$_POST['gost_id']}#methods-block");
    }


    /**
     * @desc Удаляет СОВСЕМ Методику и все зависимые данные
     * @param $id - ид методики
     */
    public function deletePermanentlyMethod($id)
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $data = $methodsModel->get($id);

        $methodsModel->deletePermanentlyMethod($id);

        $this->showSuccessMessage("Методика удалена окончательно");

        $this->redirect("/gost/edit/{$data['gost_id']}#methods-block");
    }


    /**
     * @desc Удаляет СОВСЕМ ГОСТ, Методики и все зависимые данные
     * @param $id - ГОСТ ид
     */
    public function deletePermanentlyGost($id)
    {
        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');

        $gostModel->deletePermanentlyGost($id);

        $this->showSuccessMessage("ГОСТ удален окончательно");

        $this->redirect("/gost/list/");
    }


    /**
     * @desc Подтверждение Методики
     * @param $id
     */
    public function confirmMethod($id)
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $location   = "/gost/method/{$id}";

        $data['is_confirm'] = 1;

        $result = $methodsModel->update((int)$id, $data);

        if ( empty($result) ) {
            $this->showErrorMessage("Методику не удалось подтвердить");
        } else {
            $this->showSuccessMessage("Методика подтверждена");
        }

        $this->redirect($location);
    }


    /**
     * @desc Сделать методику не актуальной
     * @param $id
     */
    public function nonActualMethod($id)
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $location   = "/gost/method/{$id}";

        $methodsModel->delete($id);

        $this->showSuccessMessage("Методика теперь не актуальна");

        $this->redirect($location);
    }


    /**
     * @desc Создание/изменение ГОСТа с формы
     */
    public function insertUpdate()
    {
        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');

        $location   = empty($_POST['id'])? '/gost/new/' : "/gost/edit/{$_POST['id']}";
        $successMsg = empty($_POST['id'])? 'ГОСТ успешно создан' : "ГОСТ успешно изменен";

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        //// блок проверок

        ///  \блок проверок

        if ( !empty($_POST['id']) ) { // редактирование
            $idGost = $_POST['id'];
            $gostModel->updateGost((int)$_POST['id'], $_POST['form']);
        } else { // создание
            $idGost = $gostModel->addGost($_POST['form']);
        }

        if ( empty($idGost) ) {
            $this->showErrorMessage("ГОСТ не удалось сохранить");
            $this->redirect($location);
        } else {
            $this->showSuccessMessage($successMsg);
            $this->redirect("/gost/edit/{$idGost}");
        }
    }


    /**
     * @desc Страница журнала гостов и методик
     */
    public function list()
    {
        $this->data['title'] = 'Журнал области аккредитации';

        /** @var  Lab $lab*/
        $lab = $this->model('Lab');

        $this->data['lab'] = $lab->getLabaRoom();

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
        $this->addJs("/assets/js/gost-list.js?v={$r}");

        $this->view('list');
    }


    /**
     * @desc Страница Нормочасы и длительность испытаний
     */
    public function normtime()
    {
        $this->data['title'] = 'Нормочасы и длительность испытаний';

        /** @var  Lab $lab*/
        $lab = $this->model('Lab');

        $this->data['lab'] = $lab->getLabaRoom();

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

        $r = rand();
        $this->addJs("/assets/js/gost-normtime.js?v={$r}");

        $this->view('normtime');
    }


    /**
     * @desc Страница журнала прайса гостов и методик
     */
    public function listPrice()
    {
        $this->data['title'] = 'Формирование прайса';

        /** @var  Lab $lab*/
        $lab = $this->model('Lab');

        $this->data['lab'] = $lab->getLabaRoom();

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

        $r = rand();
        $this->addJs("/assets/js/gost-price-list.js?v={$r}");

        $this->view('price_list');
    }


    /**
     * @desc Получение помещений по выбранным лабораториям
     */
    public function getRoomByLabIdAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $response = $labModel->getLabaRoom($_POST['lab']);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение ответственных по выбранным лабораториям
     */
    public function getAssignedByLabIdAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $response = $userModel->getAssignedUserListByLab($_POST['lab']);

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение данных для журнала
     */
    public function getJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $filter = $methodsModel->prepareFilter($_POST ?? []);

        $data = $methodsModel->getJournalList($filter);

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
     * @desc Получение данных для журнала Отчет об использовании области аккредитации
     */
    public function getJournalReportAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $filter = $methodsModel->prepareFilter($_POST ?? []);

        $data = $methodsModel->getJournalReportList($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];
        $sql = $data['sql'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);
        unset($data['sql']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "sql" => $sql,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение данных для журнала матрицы компетенции
     */
    public function getJournalMatrixAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $filter = $methodsModel->prepareFilter($_POST ?? []);

        $data = $methodsModel->getJournalMatrixList($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];
        $columns = $data['columns'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);
        unset($data['columns']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            "columns" => $columns,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение информации об оборудовании по ид
     */
    public function getOborudAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');

        $result = $oborudModel->getOborudById($_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение оборудования по помещению
     */
    public function getOborudByRoomAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');

        $result = $oborudModel->getOborutByRooms($_POST['rooms']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Устанавливает новую цену у методики
     */
    public function setNewPriceAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodModel */
        $methodModel = $this->model('Methods');

        $result = $methodModel->setNewPrice((int)$_POST['method_id'], $_POST['new_price']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

	/**
	 * @desc Страница журнала гостов и методик
	 */
	public function stats()
	{
		$this->data['title'] = 'Журнал области аккредитации';

		/** @var  Lab $lab*/
		$lab = $this->model('Lab');

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
		$this->addJs("/assets/js/gost-stats.js?v={$r}");

		$this->view('stats');
	}


	/**
	 * @desc Получает данные для журнала статистики гостов и методик
	 */
    public function getStatsByMethodAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Methods $methodsModel */
		$methodsModel = $this->model('Methods');

        $filter = $methodsModel->prepareFilter($_POST ?? []);

		$data = $methodsModel->statsUsedTests($filter);

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
     * @desc Получает данные для таблицы, которая содержит информацию о методиках в соответствии с ГОСТом
     */
	public function getListMethodByGostAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Methods $methodsModel */
		$methodsModel = $this->model('Methods');

        $filter = $methodsModel->prepareFilter($_POST ?? []);

		$filter['search']['id'] = (int)$_POST['id'] ?? 0;

		$data = $methodsModel->methodsJournal($filter);

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
     * @desc Получает список лабораторий и пользователей для них.
     */
    public function getLabAndUserAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $result = $labModel->getLabAndUser();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Добавляет ответственных к методике с помощью Ajax-запроса.
     */
    public function setAssignedAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $methodsModel->toggleAssigned((int) $_POST['method_id'], (int) $_POST['user_id']);
    }


    /**
     * @desc Сохраняет нормочасы и длительность испытаний
     */
    public function setDurationAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $data = [
            'duration_gost' => (float) $_POST['duration_gost'],
            'duration_employee' => (float) $_POST['duration_employee'],
            'duration_equip' => (float) $_POST['duration_equip'],
            'duration_probe' => (float) $_POST['duration_probe'],
            'duration_total' => (float) $_POST['duration_total'],
            'count_employee' => (int) $_POST['count_employee'],
            'duration_work' => (float) $_POST['duration_work'],
        ];

        $methodsModel->update((int) $_POST['method_id'], $data);
    }
}
