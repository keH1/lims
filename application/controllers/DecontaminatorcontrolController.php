<?php

/**
 * @desc Контроль работы ультрафиолетовой бактерицидной установки
 * Class DecontaminatorcontrolController
 */
class DecontaminatorcontrolController extends Controller
{
    private string $nameModel = 'Decontaminatorcontrol';

    /**
     * @desc Журнал контроля работы ультрафиолетовой бактерицидной установки
     */
    public function list()
    {
        $this->data['title'] = 'Журнал контроля работы ультрафиолетовой бактерицидной установки';

        $usedModel = $this->model($this->nameModel);

        foreach ($usedModel->getSelects() as $key => $item) {
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
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.colVis.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.print.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.html5.min.js");
        $this->addJS("/assets/plugins/DataTables/JSZip-2.5.0/jszip.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/intl.js");
        $this->addJS("/assets/plugins/DataTables/FixedHeader-3.2.0/js/dataTables.fixedHeader.min.js");

        $this->addJs("/assets/js/decontaminatorcontrol-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала контроля работы ультрафиолетовой бактерицидной установки
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $data = $usedModel->getList($this->postToFilter($_POST));

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
     * @desc Включает или выключает бактерицидную установку
     */
    public function addSwitch()
    {
        $usedModel = $this->model($this->nameModel);
        $newAdd = $_POST['toSQL'];
        $type = $_POST['type'];
        if ($type == 'on') {
            $successMsg = 'Бактерицидная установка успешно включена';
            $unsuccessfulMsg = 'Бактерицидная установка успешно  не включена';
        } else {
            $successMsg = 'Бактерицидная установка успешно выключена';
            $unsuccessfulMsg = 'Бактерицидная установка успешно  не выключена';
        }

        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }
}
