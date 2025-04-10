<?php

/**
 * @desc Контроль воды
 * Class WaterController
 */
class WaterController extends Controller
{
    private string $nameModel = 'Water';

    /**
     * @desc Журнал контроля дистиллированной воды
     */
    public function list()
    {
        $this->data['title'] = 'Журнал контроля дистиллированной воды';

        $usedModel = $this->model($this->nameModel);

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


        $this->addJs("/assets/js/water-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для «Журнала контроля дистиллированной воды»
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $filter = $usedModel->postToFilter($_POST ?? []);

        $filter['dateStart'] = $usedModel->validateYearMonth($filter['dateStart']);
        $filter['dateEnd'] = $usedModel->validateYearMonth($filter['dateEnd']);

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

    /**
     * @desc Добавляет измерения
     */
    public function addAnalysis()
    {
        $successMsg = 'Анализ успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить анализ';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        foreach ($newAdd['water'] as $key => $item) {
            if (empty($item)) {
                unset($newAdd['water'][$key]);
            }
        }

        $isAdd = false;
        if ( !empty($newAdd) ) {
            $isAdd = $usedModel->addToSQL($newAdd,'addAnalysis');
        }

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Автозаполнение «Журнала контроля дистиллированной воды»
     */
    public function autoFill()
    {
        /** @var Water $waterModel */
        $waterModel = $this->model('Water');

        if ( empty($_POST['formAutoFill']['dateFrom']) || empty($_POST['formAutoFill']['dateTo']) ) {
            $this->showErrorMessage("Дата начала и дата конца не должны быть пустыми");
            $this->redirect("/water/list/");
        }

        $result = $waterModel->autoFill($_POST['formAutoFill']['dateFrom'], $_POST['formAutoFill']['dateTo']);

        $this->showSuccessMessage('Данные условий обновлены успешно. Было добавлено: ' . $result);
        $this->redirect("/water/list/");
    }
}
