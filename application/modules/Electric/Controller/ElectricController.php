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
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $this->addJs("/assets/js/electric-journal.js?v=2");

        $this->view('list', '', 'template_journal');
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

        $filter = $usedModel->postToFilter($_POST ?? []);

        $data = $usedModel->getList($filter);

		foreach ($data as $k => $item) {
			if ($k == 'recordsTotal' || $k == 'recordsFiltered' ) {
				continue;
			}

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
            "draw" => (int)$_POST['draw'],
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

        $validDate = $this->validateDate($newAdd['electric_control']['date'], 'Дата замера', true);
        if (!$validDate['success']) {
            $this->showErrorMessage($validDate['error']);
            $this->redirect($usedModel->getLocation());
        }
        $validIdRoom = $this->validateNumber($newAdd['electric_control']['id_room'], 'Помещение', true);
        if (!$validIdRoom['success']) {
            $this->showErrorMessage($validIdRoom['error']);
            $this->redirect($usedModel->getLocation());
        }

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
