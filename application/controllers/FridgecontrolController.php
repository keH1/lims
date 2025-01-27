<?php

/**
 * @desc Контроль температурного режима в холодильниках
 * Class FridgecontrolController
 */
class FridgecontrolController extends Controller
{
    private string $nameModel = 'Fridgecontrol';

    /**
     * @desc Журнал контроля температурного режима в холодильниках
     */
    public function list()
    {
        $this->data['title'] = 'Журнал контроля температурного режима в холодильниках';


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


        $this->addJs("/assets/js/fridgecontrol-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала контроля температурного режима в холодильниках
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $thisModel = $this->model($this->nameModel);

        $data = $thisModel->getList($this->postToFilter($_POST));

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
     * @desc Добавляет запись температуры
     */
    public function addFridgeControl()
    {
        $successMsg = 'Запись успешно добавлена';
        $unsuccessfulMsg = 'Не удалось добавить запись';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];
        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);
        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Автозаполнение
     */
    public function autoFill()
    {
        /** @var Fridgecontrol $fridgecontrolModel */
        $fridgecontrolModel = $this->model('Fridgecontrol');

        if (empty($_POST['formAutoFill']['dateFrom']) || empty($_POST['formAutoFill']['dateTo'])) {
            $this->showErrorMessage("Дата начала и дата конца не должны быть пустыми");
            $this->redirect("/fridgecontrol/list/");
        }

        $result = $fridgecontrolModel->autoFill($_POST['formAutoFill']['dateFrom'], $_POST['formAutoFill']['dateTo']);

        $this->showSuccessMessage('Данные условий обновлены успешно. Было добавлено: ' . $result);
        $this->redirect("/fridgecontrol/list/");
    }
}
