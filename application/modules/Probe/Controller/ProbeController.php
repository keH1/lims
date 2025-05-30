<?php

/**
 * @desc Пробы
 * Class ProbeController
 */
class ProbeController extends Controller
{
    /**
     *  Перенаправляет пользователя на страницу «Журнал заявок»
     */
    public function index()
    {
        $this->redirect('/request/list/');
    }


    /**
     *  редактирует данные пробы
     */
    public function editProbeInfo()
    {
        /** @var Probe $probeModel */
        $probeModel = $this->model('Probe');

        $dealId = $probeModel->getDealIdByProbe((int)$_POST['id']);

        $probeModel->edit((int)$_POST['id'], $_POST['form']);

        $this->showSuccessMessage("Данные пробы изменены");

        $this->redirect("/probe/card/{$dealId}");
    }


    /**
     *  копирует информацию из пробы в пробу
     */
    public function copyProbeInfo()
    {
        /** @var Probe $probeModel */
        $probeModel = $this->model('Probe');

        $probeModel->copyProbeInfo((int)$_POST['probe_id'], (int)$_POST['source_probe_id']);

        $dealId = $probeModel->getDealIdByProbe((int)$_POST['probe_id']);

        $this->showSuccessMessage("Данные пробы скопированы");

        $this->redirect("/probe/card/{$dealId}");
    }


    /**
     *  передать пробу
     * @param $umtrId
     */
    public function transferProbe($umtrId)
    {
        /** @var Probe $probeModel */
        $probeModel = $this->model('Probe');

        $probeModel->transferProbe((int)$umtrId);

        $dealId = $probeModel->getDealIdByProbe((int)$umtrId);

        $this->showSuccessMessage("Проба передана");

        $this->redirect("/probe/card/{$dealId}");
    }


    /**
     *  принять пробу
     * @param $umtrId
     */
    public function takeProbe($umtrId)
    {
        /** @var Probe $probeModel */
        $probeModel = $this->model('Probe');

        $probeModel->takeProbe((int)$umtrId, App::getUserId());

        $dealId = $probeModel->getDealIdByProbe((int)$umtrId);

        $this->showSuccessMessage("Проба принята");

        $this->redirect("/probe/card/{$dealId}");
    }


    /**
     * route /probe/list/
     * @desc список актов приёмки проб
     */
    public function list()
    {
        $this->data['title'] = 'Журнал приёмки проб';

        /** @var Lab $lab */
        $lab = $this->model('Lab');
        /** @var Request $request */
        $request = $this->model('Request');

        $this->data['date_start'] = $request->getDateStart();

        $this->data['lab'] = $lab->getList();

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
        $this->addJs("/assets/js/probe-list.js?v={$r}");

        $this->view('list');
    }

	/**
	 * route /probe/listResearcher/
	 *  Список актов приёмки проб. для лаборантов
	 */
	public function listResearcher()
	{
		$this->data['title'] = 'Журнал приёмки проб';

		/** @var Lab $lab */
		$lab = $this->model('Lab');
		/** @var Request $request */
		$request = $this->model('Request');

		$this->data['date_start'] = $request->getDateStart();

		$this->data['lab'] = $lab->getList();

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
		$this->addJs("/assets/js/probe-list-lab.js?v={$r}");

        $this->view('list_lab');
	}


	/**
     * @desc Карточка пробы
	 * @param $dealId
	 */
	public function card( $dealId )
	{
		$dealId = (int) $dealId;

		/** @var User $user */
		$user = $this->model('User');
		/** @var Requirement $requirement */
		$requirement = $this->model('Requirement');
		/** @var Probe $probeModel */
		$probeModel = $this->model('Probe');
		/** @var Material $materialModel */
		$materialModel = $this->model('Material');

		$this->data['title'] = "Карточка пробы";

		$this->data['material_probe'] = $materialModel->getMaterialProbeToRequest($dealId);
        $this->data['probe_in_act'] = $probeModel->getProbeInAct($dealId);

		$currUser = $user->getCurrentUser();

		$tzId = $requirement->getTzIdByDealId($dealId);
		$tzData = $requirement->getTzByTzId((int)$tzId);
        $actBase = $requirement->getActBase($dealId);

        if ( $tzData['TYPE_ID'] != '9' ) {
            $this->data['comm'] = '?type_request=commercial';
        }

		$this->data['deal_id']  = $dealId;
		$this->data['tz_id']    = $tzId;
		$this->data['deal_title'] = $tzData['REQUEST_TITLE'];
		$this->data['selection_type'] = $tzData['SELECTION_TYPE'];
        $this->data['act_number'] = $actBase['ACT_NUM'];
        $this->data['act_date'] = $actBase['date_ru'];

		$probe = $probeModel->getProbeByDealId($dealId);

		// отображаем кнопку Передать если менеджер
        $this->data['is_hand_over'] = in_array(GROUP_MANAGER_ID, $currUser['groups']);

		$this->data['probe'] = $probe;
		$this->data['test'] = $probe;

		$this->addJs('/assets/js/probe-card.js');

		$this->view('card');
	}


	/**
     * @desc Создание акта приемка-передачи (образцов)
	 * @param $dealId
	 */
	public function new( $dealId )
	{
		$dealId = (int) $dealId;

		/** @var Request $request */
		$request = $this->model('Request');
		/** @var User $user */
		$user = $this->model('User');
		/** @var Requirement $requirement */
		$requirement = $this->model('Requirement');
		/** @var Probe $probeModel */
		$probeModel = $this->model('Probe');
		/** @var Material $materialModel */
		$materialModel = $this->model('Material');
		/** @var Quarry $quarryModel */
		$quarryModel = $this->model('Quarry');

		$this->data['title'] = "Создание акта приемка-передачи (образцов)";

		$this->data['material_probe'] = $materialModel->getMaterialProbeToRequest($dealId);


		$tzId = $requirement->getTzIdByDealId($dealId);
		$deal = $request->getDealById($dealId);
		$tzData = $requirement->getTzByTzId((int)$tzId);

		$this->data['deal_id']  = $dealId;
		$this->data['tz_id']    = $tzId;
		$this->data['deal_title'] = $tzData['REQUEST_TITLE'];
		$this->data['new_act_number'] = $probeModel->getNewActNumber();
		$this->data['company_name'] = $deal['COMPANY_TITLE'];

		//Пробу принял
		$this->data['current_user'] = $user->getUserShortById(App::getUserId());

		//Договор
		$contractData = $requirement->getContractByDealId($dealId);
		$this->data['contract_number'] = $contractData['NUMBER'] ?? '';
		$this->data['contract_date'] = $contractData['DATE'] ?? '';
		$this->data['contract_type'] = $contractData['CONTRACT_TYPE'] ?? 'Договор';

        if ( $tzData['TYPE_ID'] != '9' ) {
            $this->data['comm'] = '?type_request=commercial';
        }

		$probe = $probeModel->getProbeByDealId($dealId);

		$this->data['probe'] = $probe;

		$this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
		$this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
		$this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

		$this->addJs('/assets/js/probe_form.js');

		$this->view('form');
	}


    /**
     * @desc Получает данные для журнала приёмки проб
     */
    public function getListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Request $request*/
        $request = $this->model('Request');

        $filter = $request->prepareFilter($_POST ?? []);

        $data = $request->getDatatoJournalActProbe($filter);

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
     *  Создание нового акта приёмки проб
     */
    public function insertUpdateActProbe($dealId)
    {
        /** @var  Probe $probeModel */
        $probeModel = $this->model('Probe');

        $probeModel->insertUpdateActProbe($_POST['act']);

        $this->showSuccessMessage('Акт сформирован');
        $this->redirect("/request/card/{$dealId}");
    }

	/**
	 * @desc Создание нового акта приёмки проб
	 */
	public function insertUpdateActProbeNew($dealId)
	{
		/** @var  Probe $probeModel */
		$probeModel = $this->model('Probe');

        $validActDate = $this->validateDate($_POST['act']['ACT_DATE'], 'Дата поступления проб', true);
        if (!$validActDate['success']) {
            $this->showErrorMessage($validActDate['error']);
            $this->redirect("/probe/new/{$dealId}");
        }

		$probeModel->insertUpdateActProbeNew($_POST['act']);

		$this->showSuccessMessage('Акт сформирован');
		$this->redirect("/request/card/{$dealId}");
	}

    /**
     *  Отмечает факт принятия проб
     */
	public function acceptProbeAjax()
	{

		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var  Probe $probeModel */
		$probeModel = $this->model('Probe');

		$idReq = (int)$_POST['id'];

		if ($_POST['arr']) {
			foreach ($_POST['arr'] as $idUmtr) {
                $idUmtr = (int)$idUmtr;
				$probeModel->acceptProbe($idUmtr);
			}
		} else {
			$probeModel->acceptProbe($idReq);
		}

		echo json_encode(['accept'=>'true'], JSON_UNESCAPED_UNICODE);
	}

    /**
     *  Отменяет факт принятия проб
     */
	public function removeAcceptProbeAjax()
	{

		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var  Probe $probeModel */
		$probeModel = $this->model('Probe');

		$idReq = (int)$_POST['id'];

		if ($_POST['arr']) {
			foreach ($_POST['arr'] as $idUmtr) {
                $idUmtr = (int)$idUmtr;
				$probeModel->removeAcceptProbe($idUmtr);
			}
		} else {
			$probeModel->removeAcceptProbe($idReq);
		}


		echo json_encode(['accept'=>'true'], JSON_UNESCAPED_UNICODE);
	}


    /**
     * @desc Получает данные пробы
     */
	public function getAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Probe $probeModel */
        $probeModel = $this->model('Probe');

        $data = $probeModel->get((int)$_POST['id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     *  Получает историю пробы
     */
    public function getHistoryAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Probe $probeModel */
        $probeModel = $this->model('Probe');

        $id = (int)$_POST['id'];

        $data['info'] = $probeModel->get($id);
        $data['history'] = $probeModel->getHistory($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Сохраняет информацию о том, что проба отобрана не заказчиком
     */
    public function changeSelectionTypeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Probe $probeModel */
        $probeModel = $this->model('Probe');

        $id = (int)$_POST['id'];
        $prop = $_POST['checked'];

        $probeModel->setSelectionType($id, $prop);
    }
}
