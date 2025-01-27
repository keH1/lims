<?php

/**
 * @desc Внутренний лабораторный контроль в ЛБФ (микробиология)
 * Class MicrobController
 */
class MicrobController extends Controller
{
    private string $nameModel = 'Microb';

    /**
     * @desc Перенаправляет пользователя на страницу «Журналы внутреннего лабораторного контроля в ЛБФ (микробиология)»
     */
    public function index()
    {
        $usedModel = $this->model($this->nameModel);
        $this->redirect($usedModel->getLocation());
    }

    /**
     * @desc Журналы внутреннего лабораторного контроля в ЛБФ (микробиология)
     */
    public function list()
    {
        $this->data['title'] = 'Журналы внутреннего лабораторного контроля в ЛБФ (микробиология)';

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


        $this->addJs("/assets/js/microb-journal.js");

        $this->addCSS("/assets/plugins/flextabs/css/flextabs.css");
        $this->addJS("/assets/plugins/flextabs/js/flextabs.js");
        $this->view('list');
    }


    /**
     * @desc Получает данные для журнала внутреннего лабораторного контроля в ЛБФ (микробиология)
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
     * @desc Добавляет отбор пробы
     */
    public function addSamplingMediumControl()
    {
        $successMsg = 'Отбор пробы успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить отбор пробы';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addSamplingMediumControl');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Добавляет посев проб
     */
    public function addSowing()
    {
        $successMsg = 'Посев пробы успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить посев проб';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addSowing');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Добавляет результат посева проб
     */
    public function addResultSowing()
    {
        $successMsg = 'Результаты исследования успешно добавлены';
        $unsuccessfulMsg = 'Не удалось добавить результаты исследования ';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'addResultSowing');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Получает кол-во точек при выборе отобранной пробы при добавлении посева проб
     */
    public function setSeedingResult()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $data = $usedModel->addSeedingResult($_POST['sample_number']);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные пробы при дублировании отбора пробы
     */
    public function setSampleCopy()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);
        
        $data = $usedModel->getSampleData($_POST['sample_number']);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
