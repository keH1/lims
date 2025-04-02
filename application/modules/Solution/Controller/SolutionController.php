<?php

/**
 * @desc Растворы и реактивы
 * Class SolutionController
 */
class SolutionController extends Controller
{
    private string $nameModel = 'Solution';

    /**
     * @desc Журнал приготовления растворов и реактивов
     */
    public function list()
    {
        $this->data['title'] = 'Журнал приготовления растворов и реактивов';

        /** @var  Solution $solution */
        $usedModel = $this->model($this->nameModel);
        $version = "?=" . rand();
        $this->data['recipe'] = $usedModel->getFromSQL('recipe');

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

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $this->addJs("/assets/js/solution-journal.js" . $version);

        $this->view('list');
    }

    /**
     * @desc Получает данные для «Журнала приготовления растворов и реактивов»
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

    public function getListReactives()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);
        $idRecipe = (int)$_POST['idRecipe'];
        $getList = $usedModel->getList('reactive', $idRecipe);
        $jsonData = [
            "draw" => $_POST['draw'],
            "data" => $getList
        ];
        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает список реактивов по рецепту
     */
    public function getListReactivesNew()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);
        $idRecipe = (int)$_POST['idRecipe'];
        $getList = $usedModel->getReactivesList($idRecipe);

        echo json_encode($getList, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает список растворов
     */
    public function getListSolvent()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        $usedModel = $this->model($this->nameModel);
        $idRecipe = (int)$_POST['idRecipe'];
        $getList = $usedModel->getList('solvent', $idRecipe);
        $jsonData = [
            "draw" => $_POST['draw'],
            "data" => $getList
        ];
        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Создаёт реактива
     */
    public function addSolutionAndConsume()
    {
        
        $successMsg = 'Реактив успешно создан';
        $unsuccessfulMsg = 'Не удалось создать реактив';

        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'solutionAndConsume');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());

    }
}
