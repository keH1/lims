<?php

/**
 * @desc Стандарт-титры
 * Class StandarttitrController
 */
class StandarttitrController extends Controller
{
    private string $nameModel = 'Standarttitr';

    /**
     * @desc Журнал «Стандарт-титры»
     */
    public function list()
    {
        $this->data['title'] = 'Стандарт-титры';

        /** @var  Recipe $usedModel */
        $usedModel = $this->model($this->nameModel);

        foreach ($usedModel->getSelect() as $key => $item) {
            $this->data[$key] = $item;
        }
        $version = "?=" . rand();


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

        $this->addJs("/assets/js/standarttitr-journal.js" . $version);

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала «Стандарт-титры»
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
     * @desc Добавляет производителя в «Стандарт-титры»
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
     * @desc Добавляет Стандарт-титр
     */
    public function addStandartTitr()
    {
        $successMsg = 'Стандарт-титр успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить Стандарт-титр';
        $usedModel = $this->model($this->nameModel);

        $newAdd['standart_titr'] = $_POST['standart_titr'];

        $isAdd = $usedModel->addToSQL($newAdd, 'standartTitr');

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->getLocation());
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Проводит стандарт-титр
     */
    public function addReceive()
    {
        $successMsg = 'Стандарт-титр успешно проведено';
        $unsuccessfulMsg = 'Не удалось провести Стандарт-титр';
        $usedModel = $this->model($this->nameModel);

        $newAdd['standart_titr_receive'] = $_POST['receive'];
        $isAdd = $usedModel->addToSQL($newAdd);

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->getLocation());
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Получить данные для редактирования стандарт-титр
     */
    public function getStandarttitrUpdate()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $filter['id'] = $_POST['which_select_id'];
        $filter['type'] = $_POST['type'];

        $data = $usedModel->getUpdateData($filter);


        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Редактирует стандарт-титр
     */
    public function updateStandartTitr()
    {
        $successMsg = 'Реактив успешно изменен';
        $unsuccessfulMsg = 'Не удалось изменить реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd['standart_titr'] = $_POST['standart_titr'];

        $isAdd = $usedModel->newUpdateSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Проводит реактив
     */
    public function updateStandartTitrReceive()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd['standart_titr_receive'] = $_POST['receive'];
        $isAdd = $usedModel->newUpdateSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

}
