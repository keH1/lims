<?php

/**
 * @desc ГСО
 * Class GsoController
 */
class GsoController extends Controller
{
    private string $nameModel = 'Gso';


    /**
     * @desc Журнал ГСО
     */
    public function list()
    {
        $this->data['title'] = 'ГСО';

        $usedModel = $this->model($this->nameModel);

        foreach ($usedModel->getSelect() as $key => $item) {
            $this->data[$key] = $item;
        }

        $ver = "?=" . rand();
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

        $this->addJs("/assets/js/gso-journal.js" . $ver);

        $this->view('list');
    }


    /**
     * @desc Получает данные для журнала ГСО
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
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Добавляет производителя
     */
    public function addManufacturer()
    {
        $successMsg = 'Производитель успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить производителя';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd);

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->getLocation());
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Добавляет ГСО
     */
    public function addGsoAndSpecification()
    {
        $successMsg = 'ГСО успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить ГСО';
        $usedModel = $this->model($this->nameModel);
        $newAdd['gso'] = $_POST['gso'];
        $newAdd['gso_specification'] = $_POST['gso_specification'];

        $isAdd = $usedModel->addToSQL($newAdd, 'gsoAndSpecification');

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->getLocation());
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Проводит ГСО
     */
    public function addGsoReceive()
    {
        $successMsg = 'ГСО успешно проведено';
        $unsuccessfulMsg = 'Не удалось провести ГСО';
        $usedModel = $this->model($this->nameModel);
        $newAdd['gso_receive'] = $_POST['receive'];
        $newAdd['gso_receive_specification'] = $_POST['receive_specification'];
        $isAdd = $usedModel->addToSQL($newAdd, 'gsoReceive');

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->getLocation());
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Получает данные для редактирования ГСО
     */
    public function setGsoUpdate()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);
        $filters["id"]=(int)$_POST['which_select_id'];
        $type = $_POST['type'];

        $data = $usedModel->getUpdateData($type, $filters);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Обновляет данные ГСО
     */
    public function updateGso()
    {
        $successMsg = 'ГСО успешно изменен';
        $unsuccessfulMsg = 'Не удалось изменить ГСО';
        $usedModel = $this->model($this->nameModel);

        $newAddGso['gso'] = $_POST['gso'];
        $newAddGsoSpecification['gso_specification'] = $_POST['gso_specification'];

        $isAddFirst = $usedModel->newUpdateSQL($newAddGso);
        $isAddSecond = $usedModel->newUpdateSQL($newAddGsoSpecification);

        $this->checkAndShowSuccessOrErrorMessage($isAddFirst, $successMsg, $unsuccessfulMsg);
        $this->checkAndShowSuccessOrErrorMessage($isAddSecond, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Редактирует проводку ГСО
     */
    public function updateReceiveGso()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';
        $usedModel = $this->model($this->nameModel);

        $newAddGso['gso_receive'] = $_POST['receive'];
        $newAddGsoSpecification['gso_receive_specification'] = $_POST['receive_specification'];

        $isAddFirst = $usedModel->newUpdateSQL($newAddGso);
        $isAddSecond = $usedModel->newUpdateSQL($newAddGsoSpecification);

        $this->checkAndShowSuccessOrErrorMessage($isAddFirst, $successMsg, $unsuccessfulMsg);
        $this->checkAndShowSuccessOrErrorMessage($isAddSecond, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }
}
