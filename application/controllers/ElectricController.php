<?php

/**
 * @desc Контроль частоты и напряжения электрической сети
 * Class ElectricController
 */
class ElectricController extends Controller
{
    private string $nameModel = 'Electric';

    /**
     * @desc Журнал контроля частоты и напряжения электрической сети
     */
    public function list()
    {

        $this->data['title'] = 'Журнал контроля частоты и напряжения электрической сети';

        $usedModel = $this->model($this->nameModel);

        foreach ($usedModel->getSelect() as $key => $item) {
            $this->data[$key] = $item;
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
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.colVis.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.print.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.html5.min.js");
        $this->addJS("/assets/plugins/DataTables/JSZip-2.5.0/jszip.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/intl.js");
        $this->addJS("/assets/plugins/DataTables/FixedHeader-3.2.0/js/dataTables.fixedHeader.min.js");

        $this->addJs("/assets/js/electric-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала контроля частоты и напряжения электрической сети
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        /** @var Electric $usedModel */
        $usedModel = $this->model('Electric');

        $data = $usedModel->getList($this->postToFilter($_POST));

		foreach ($data as $k => $item) {
			if ($k == 'recordsTotal' || $k == 'recordsFiltered' ) {
				continue;
			}
//			$usedModel->pre($item, false);
			$item['voltage_UA'] = number_format((float)$item['voltage_UA'], 1, '.');
			$item['voltage_UB'] = number_format((float)$item['voltage_UB'], 1, '.');
			$item['voltage_UC'] = number_format((float)$item['voltage_UC'], 1, '.');
			$item['frequency'] = number_format((float)$item['frequency'], 1, '.');

			$data[$k] = $item;
		}

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
     * @desc Добавляет замер
     */
    public function addMeasurement()
    {
        $successMsg = 'Замер успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить замер';

        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addMeasurement');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Автозаполнение данных
     */
    public function autoFill()
    {
        /** @var Electric $electricModel */
        $electricModel = $this->model('Electric');

        if ( empty($_POST['formAutoFill']['dateFrom']) || empty($_POST['formAutoFill']['dateTo']) ) {
            $this->showErrorMessage("Дата начала и дата конца не должны быть пустыми");
            $this->redirect("/electric/list/");
        }
		$holiday = $_POST['formAutoFill']['holyday'];

        $result = $electricModel->autoFill($_POST['formAutoFill']['dateFrom'], $_POST['formAutoFill']['dateTo'], $_POST['formAutoFill']['autoFrom'], $_POST['formAutoFill']['autoTo'], $holiday);

        $this->showSuccessMessage('Данные обновлены успешно. Было добавлено: ' . $result);
        $this->redirect("/electric/list/");
    }
}
