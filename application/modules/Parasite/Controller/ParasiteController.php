<?php

/**
 * @desc Внутренний лабораторный контроль в ЛБФ (паразитология)
 * Class ParasiteController
 */
class ParasiteController extends Controller
{
    private string $nameModel = 'Parasite';

    /**
     * @desc Журнал внутреннего лабораторного контроля в ЛБФ (паразитология)
     */
    public function list()
    {
        $this->data['title'] = 'Журнал внутреннего лабораторного контроля в ЛБФ (паразитология)';

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

        $this->addJs("/assets/js/parasite-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала внутреннего лабораторного контроля в ЛБФ (паразитология)
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
     * @desc Добавляет отбор проб
     */
    public function addSampling()
    {
        $successMsg = 'Отбор пробы успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить отбор пробы';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Добавляет результат исследования смывов на паразитологические показатели
     */
    public function addResultParasite()
    {
        $successMsg = 'Результат анализа успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить результат анализа';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addResultParasite');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Добавляет результат исследование смывов на цисты (ооцисты) патогенных простейших
     */
    public function addResultSimple()
    {
        $successMsg = 'Результат анализа успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить результат анализа';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addResultSimple');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc При выборе отобранной пробы получаем количество точек
     */
    public function setSeedingResult()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $data = $usedModel->addSeedingResult((int)$_POST['sampling_id']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
