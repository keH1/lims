<?php

/**
 * @desc Нормативная документация
 * Страница с ГОСТами
 * Class NormDocGostController
 */
class NormDocGostController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Журнал нормативной документации»
     */
    public function index()
    {
        $this->redirect('/normDocGost/list/');
    }

    protected function form()
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

//        $this->data['unit_list'] = $methodsModel->getUnitList();
//        $this->data['test_method_list'] = $methodsModel->getTestMethodList();


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

        $this->addJS("/assets/js/norm-doc-gost-form.js?v=". rand());

        $this->view('form');
    }


    /**
     * @desc Страница создания ГОСТа
     */
    public function new()
    {
        $this->data['title'] = 'Создание нормативной документации';

        $this->form();
    }


    /**
     * @desc Страница редактирования ГОСТа
     * @param $id
     */
    public function edit($id)
    {
        $this->data['title'] = 'Редактирование нормативной документации';

        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $this->data['id'] = $id;
        $this->data['form'] = $normDocGost->getGost($id);

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

        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        //// данные методики
        $this->data['form'] = $normDocGost->getMethod($id);

//        if ( !$this->data['form']['is_confirm'] ) {
//            $this->showWarningMessage('Методика не проверена отделом метрологии');
//        }
//        if ( !$this->data['form']['is_actual'] ) {
//            $this->showErrorMessage('Внимание! Методика отмечена как неактуальная');
//        }

        ///\ данные методики

        $this->data['unit_list'] = $methodsModel->getUnitList();

        $this->data['group_material_list'] = $materialModel->getGroupMaterialByNormDoc($id);
        $this->data['group_list'] = $materialModel->getGroupMaterialList();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addJS("/assets/js/norm-doc-methods-form.js?v=". rand());

        $this->view('method');
    }


    /**
     * @desc Добавляет материал - группу
     */
    public function addMaterialGroupNormDoc()
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $normDocGost->addMaterialGroupNormDoc($_POST['form']);

        $this->showSuccessMessage("Группа добавлена");
        $this->redirect("/normDocGost/method/{$_POST['form']['norm_doc_method_id']}");
    }


    /**
     * @desc Сделать все методики в ГОСТе неактуальными
     * @param $id
     */
    public function nonActualGost($id)
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $methodsModel->deleteByGost($id);

        $this->showSuccessMessage("Методики теперь неактуальны");
        $this->redirect("/normDocGost/edit/{$id}");
    }


    /**
     * @desc Создание Методики
     */
    public function insertMethod()
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $location   = "/normDocGost/edit/{$_POST['form']['gost_id']}";

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        //// блок проверок

        ///  \блок проверок

        $result = $normDocGost->addMethod($_POST['form']);

        if ( empty($result) ) {
            $this->showErrorMessage("Методику не удалось сохранить");
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
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $location   = "/normDocGost/method/{$_POST['id']}";

        $data = $_POST['form'];

        $data['is_confirm'] = 0;

        if ( in_array($_SESSION['SESS_AUTH']['USER_ID'], [1]) ) {
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
        $data['is_output'] = $_POST['form']['is_output'] ?? 0;
        $data['is_manual'] = $_POST['form']['is_manual'] ?? 0;

        $result = $normDocGost->updateMethod((int)$_POST['id'], $data);

        $normDocGost->updateMethodGroup($_POST['group']);

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
        /** @var Gost $normDocGost */
        $normDocGost = $this->model('Gost');

        $newId = $normDocGost->copy($gostId);

        if ( !is_numeric($newId) || empty($newId) ) {
            $this->showErrorMessage("ГОСТ не удалось скопировать");
            $this->redirect("/normDocGost/edit/{$gostId}");
        } else {
            $this->showSuccessMessage("ГОСТ успешно скопирован");
            $this->redirect("/normDocGost/edit/{$newId}");
        }

        $this->redirect("/normDocGost/edit/{$gostId}");
    }


    /**
     * @desc Копирует Методику
     */
    public function copyMethod()
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $result = $normDocGost->copyMethod((int)$_POST['method_id']);

        if ( empty($result) ) {
            $this->showErrorMessage("Методику не удалось скопировать");
        } else {
            $this->showSuccessMessage("Методика скопирована");
        }

        $this->redirect("/normDocGost/edit/{$_POST['gost_id']}#methods-block");
    }


    /**
     * @desc Удаляет СОВСЕМ Методику и все зависимые данные
     * @param $id - ид методики
     */
    public function deletePermanentlyMethod($id)
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $data = $normDocGost->getMethod($id);

        $this->deletePermanentlyMethod($id);

        $this->showSuccessMessage("Методика удалена окончательно");

        $this->redirect("/normDocGost/edit/{$data['gost_id']}#methods-block");
    }


    /**
     * @desc Удаляет СОВСЕМ ГОСТ, Методики и все зависимые данные
     * @param $id - ГОСТ ид
     */
    public function deletePermanentlyGost($id)
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $normDocGost->deletePermanentlyGost($id);

        $this->showSuccessMessage("ГОСТ удален окончательно");

        $this->redirect("/normDocGost/list/");
    }


    /**
     * @desc Подтверждение Методики
     * @param $id
     */
    public function confirmMethod($id)
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $location   = "/normDocGost/method/{$id}";

        $data['is_confirm'] = 1;

        $result = $normDocGost->updateMethod($id, $data);

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
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $location   = "/normDocGost/method/{$id}";

        $normDocGost->deleteMethod($id);

        $this->showSuccessMessage("Методика теперь не актуальна");

        $this->redirect($location);
    }


    /**
     * @desc Создание/изменение ГОСТа с формы
     */
    public function insertUpdate()
    {
        /** @var NormDocGost $normDocGost */
        $normDocGost = $this->model('NormDocGost');

        $location   = empty($_POST['id'])? '/normDocGost/new/' : "/normDocGost/edit/{$_POST['id']}";
        $successMsg = empty($_POST['id'])? 'Нормативная документация успешно создана' : "Нормативная документация успешно изменена";

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        //// блок проверок

        ///  \блок проверок

        if ( !empty($_POST['id']) ) { // редактирование
            $idGost = $_POST['id'];
            $normDocGost->updateGost((int)$_POST['id'], $_POST['form']);
        } else { // создание
            $idGost = $normDocGost->addGost($_POST['form']);
        }

        if ( empty($idGost) ) {
            $this->showErrorMessage("Нормативную документацию не удалось сохранить");
            $this->redirect($location);
        } else {
            $this->showSuccessMessage($successMsg);
            $this->redirect("/normDocGost/edit/{$idGost}");
        }
    }


    /**
     * @desc Страница журнала гостов и методик
     */
    public function list()
    {
        $this->data['title'] = 'Журнал нормативной документации';

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
        $this->addJs("/assets/js/norm-doc-gost-list.js?v={$r}");

        $this->view('list');
    }


    /**
     * @desc Получение данных для журнала
     */
    public function getJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var NormDocGost $normDocGostModel */
        $normDocGostModel = $this->model('NormDocGost');

        $filter = $normDocGostModel->prepareFilter($_POST ?? []);

        $data = $normDocGostModel->getJournalList($filter);

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
     * @desc Получает данные для таблицы методик в карточке ГОСТа нормативной документации
     */
	public function getListMethodByGostAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

        /** @var NormDocGost $normDocGostModel */
        $normDocGostModel = $this->model('NormDocGost');

        $filter = $normDocGostModel->prepareFilter($_POST ?? []);

		$filter['search']['id'] = (int)$_POST['id'] ?? 0;

		$data = $normDocGostModel->methodsJournal($filter);

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
     * @desc Получает список методик нормативной документации
     */
    public function getNormDocListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var NormDocGost $normDocGostModel */
        $normDocGostModel = $this->model('NormDocGost');

        $result = $normDocGostModel->getMethodList();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет связь группы материала с методикой
     */
    public function deleteMethodGroupAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var NormDocGost $normDocGostModel */
        $normDocGostModel = $this->model('NormDocGost');

        $normDocGostModel->deleteMethodGroup((int)$_POST['material_group_id']);

        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }
}
