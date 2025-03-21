<?php

/**
 * @desc Реактивы
 * Class ReactiveController
 */
class ReactiveController extends Controller
{
    private string $nameModel = 'Reactive';

    /**
     * @desc Журнал «Реактивы»
     */
    public function list()
    {
        $this->data['title'] = 'Реактивы';

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

        $this->addJS("/assets/plugins/DataTables/Responsive-2.4.0/dataTables.responsive.js");

        $this->addJs("/assets/js/reactive-journal.js");


        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала реактивы
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $filter = $usedModel->prepareFilter($_POST ?? []);

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
     * @desc Добавляет НД и квалификацию реактива
     */
    public function addReactive()
    {
        $successMsg = 'Реактив успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd['reactive'] = $_POST['reactive'];

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addReactive');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Проводит реактив
     */
    public function addReceive()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';

        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Добавляет реактив
     */
    public function addReactiveModel()
    {
        $successMsg = 'Реактив успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить реактив';

        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addReactiveModelNoUnitMeasurement');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    public function addReceiveReactive()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];
        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Получает данные для редактирования реактива
     */
    public function setReactiveUpdate()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $data = $usedModel->getUpdateData($_POST['which_select_id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Обновляет данные реактива
     */
    public function updateReactive()
    {
        $successMsg = 'Реактив успешно изменен';
        $unsuccessfulMsg = 'Не удалось изменить реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->newUpdateSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Редактирует проводку реактива
     */
    public function updateReceiveReactive()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];
        $isAdd = $usedModel->newUpdateSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }
}
