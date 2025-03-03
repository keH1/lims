<?php

/**
 * @desc Юстировка
 * Class ScaleController
 */
class ScaleController extends Controller
{
    private string $nameModel = 'ScaleCalibration';

    /**
     * @desc Журнал юстировки весов
     */
    public function list()
    {
        $this->data['title'] = 'Журнал юстировки весов';

		/** @var  ScaleCalibration $usedModel*/
        $usedModel = $this->model($this->nameModel);

        $this->data['scale'] = $usedModel->getFromSQL('scale');
        $this->data['weight'] = $usedModel->getFromSQL('weight');
        $maxMinDate = $usedModel->getMinMaxDateFridgeControl();

        $this->data['max_date'] = date('Y-m', strtotime($maxMinDate['max_date']));
        $this->data['min_date'] = date('Y-m', strtotime($maxMinDate['min_date']));

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


        $this->addJs("/assets/js/scaleCalibration-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала юстировки весов
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

		/** @var  ScaleCalibration $usedModel*/
        $usedModel = $this->model($this->nameModel);

        $filter = [
            'paginate' => [
                'length' => $_POST['length'],  // кол-во строк на страницу
                'start' => $_POST['start'],  // текущая страница
            ],
            'search' => [],
            'order' => []
        ];

        foreach ($_POST['columns'] as $column) {
            if ($column['search']['value'] != '') {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if (isset($_POST['order']) && !empty($_POST['columns'])) {
            $filter['order']['by'] = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

        $filter['idScale'] = $_POST['idScale'];

        $filter['date_start'] = $_POST['dateStart'];
        $filter['date_end'] = $_POST['dateEnd'];

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
     * @desc Добавляет данные юстировки весов
     */
    public function addScaleCalibration()
    {
        $successMsg = 'Запись успешно добавлена';
        $unsuccessfulMsg = 'Не удалось добавить запись';

		/** @var  ScaleCalibration $usedModel*/
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
     * @desc Автозаполнение журнала юстировки весов
     */
    public function autoFill()
    {
        /** @var ScaleCalibration $scaleModel */
        $scaleModel = $this->model('ScaleCalibration');

        if ( empty($_POST['formAutoFill']['dateFrom']) || empty($_POST['formAutoFill']['dateTo']) ) {
            $this->showErrorMessage("Дата начала и дата конца не должны быть пустыми");
            $this->redirect("/scale/list/");
        }

        $result = $scaleModel->autoFill($_POST['formAutoFill']['dateFrom'], $_POST['formAutoFill']['dateTo']);

        $this->showSuccessMessage('Данные условий обновлены успешно. Было добавлено: ' . $result);
        $this->redirect("/scale/list/");
    }
}
