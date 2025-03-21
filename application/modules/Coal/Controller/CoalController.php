<?php

/**
 * @desc Контроль качества регенерации активированного угля
 * Class CoalController
 */
class CoalController extends Controller
{
    private string $nameModel = 'Coal';

    /**
     * @desc Журнал контроля качества регенерации активированного угля
     */
    public function list()
    {
        $this->data['title'] = 'Журнал контроля качества регенерации активированного угля';

		/** @var  Coal $usedModel*/
        $usedModel = $this->model($this->nameModel);

        $coal = $usedModel->getFromSQL('CoalRegeneration');
        foreach ($coal as $val) {
			if (empty($val['e_id'])) {
				$this->data['coal_empty'][] = ['id' => $val['id'], 'date_regeneration_end' => $val['date_regeneration_end']];
			}

			if (!empty($val['e_id']) && empty($val['f_id'])) {
				$this->data['coal_full'][] = ['id' => $val['id'], 'date_regeneration_end' => $val['date_regeneration_end']];
			}
		}

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

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


        $this->addJs("/assets/js/coalRegeneration-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала контроля качества регенерации активированного угля
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

		/** @var  Coal $usedModel*/
        $usedModel = $this->model($this->nameModel);

        $filter = $usedModel->prepareFilter($_POST ?? []);

        $filter['idCoal'] = (int)$_POST['idCoal'];

        $data = $usedModel->getList($filter);

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

//    public function addCoalCalibration()
//    {
//        $successMsg = 'Запись успешно добавлена';
//        $unsuccessfulMsg = 'Не удалось добавить запись';
//
//		/** @var  Coal $usedModel*/
//        $usedModel = $this->model($this->nameModel);
//
//        $newAdd = $_POST['toSQL'];
//        $isAdd = $usedModel->addToSQL($newAdd);
//
//        if (!$isAdd) {
//            $this->showErrorMessage($unsuccessfulMsg);
//            $this->redirect($usedModel->location);
//        }
//
//        $this->showSuccessMessage($successMsg);
//        $this->redirect($usedModel->location);
//    }

	/**
	 * @desc Добавляет запись
	 */
    public function addCoalEndRegeneration()
	{
		$successMsg = 'Запись успешно добавлена';
		$unsuccessfulMsg = 'Не удалось добавить запись';

		/** @var Coal $usedModel*/
		$usedModel = $this->model($this->nameModel);

		$newAdd = $_POST['toSQL'];
		$isAdd = $usedModel->addToSQL($newAdd);

		if (!$isAdd) {
			$this->showErrorMessage($unsuccessfulMsg);
			$this->redirect($usedModel->location);
		}

		$this->showSuccessMessage($successMsg);
		$this->redirect($usedModel->location);
	}

	/**
	 * @desc Добавляет измерения с пустыми БДБ-13
	 */
    public function addEmptyBdb()
	{
		$successMsg = 'Запись успешно добавлена';
		$unsuccessfulMsg = 'Не удалось добавить запись';

		/** @var Coal $usedModel*/
		$usedModel = $this->model($this->nameModel);

		$newAdd = $_POST['toSQL'];
		$isAdd = $usedModel->addToSQL($newAdd);

		if (!$isAdd) {
			$this->showErrorMessage($unsuccessfulMsg);
			$this->redirect($usedModel->location);
		}

		$this->showSuccessMessage($successMsg);
		$this->redirect($usedModel->location);
	}

    /**
     * @desc Добавляет измерения с загруженным БДБ-13
     */
    public function addFullBdb()
	{
		$successMsg = 'Запись успешно добавлена';
		$unsuccessfulMsg = 'Не удалось добавить запись';

		/** @var Coal $usedModel*/
		$usedModel = $this->model($this->nameModel);

		$newAdd = $_POST['toSQL'];
		$isAdd = $usedModel->addToSQL($newAdd);

		if (!$isAdd) {
			$this->showErrorMessage($unsuccessfulMsg);
			$this->redirect($usedModel->location);
		}

		$this->showSuccessMessage($successMsg);
		$this->redirect($usedModel->location);
	}
}
