<?php

/**
 * @desc Контроль градуировки pH-метров
 * Class GraduationphmetrController
 */
class GraduationphmetrController extends Controller
{
    private string $nameModel = 'Graduationphmetr';


    /**
     * @desc Журнал контроля градуировки pH-метров
     */
    public function list()
    {

        $this->data['title'] = 'Журнал контроля градуировки pH-метров';

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
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.colVis.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.print.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.html5.min.js");
        $this->addJS("/assets/plugins/DataTables/JSZip-2.5.0/jszip.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/intl.js");
        $this->addJS("/assets/plugins/DataTables/FixedHeader-3.2.0/js/dataTables.fixedHeader.min.js");

        $this->addJs("/assets/js/graduationphmetr-journal.js");

        $this->view('list');
    }


    /**
     * @desc Получает данные для журнала контроля градуировки pH-метров
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $filter = $usedModel->postToFilter($_POST ?? []);

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
     * @desc Добавляет замер
     */
    public function addMeasurement()
    {
        $successMsg = 'Замер успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить замер';

        $usedModel = $this->model($this->nameModel);

        $newAdd = [
            'ph_metr_graduation' => $_POST['ph_metr_graduation'],
            'measurements' => $_POST['measurements']
        ];

        $isAdd = $usedModel->addToSQL($newAdd, 'addMeasurement');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Автозаполнение
     */
    public function autoFill()
    {
        /** @var Graduationphmetr $graduationphmetrModel */
        $graduationphmetrModel = $this->model('Graduationphmetr');

        if ( empty($_POST['formAutoFill']['dateFrom']) || empty($_POST['formAutoFill']['dateTo']) ) {
            $this->showErrorMessage("Дата начала и дата конца не должны быть пустыми");
            $this->redirect("/graduationphmetr/list/");
        }

        $result = $graduationphmetrModel->autoFill($_POST['formAutoFill']['dateFrom'], $_POST['formAutoFill']['dateTo']);

        $this->showSuccessMessage('Данные условий обновлены успешно. Было добавлено: ' . $result);
        $this->redirect("/graduationphmetr/list/");
    }
}
