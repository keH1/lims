<?php

/**
 * @desc Рецепты
 * Class RecipeController
 */
class RecipeController extends Controller
{
    private string $nameModel = 'Recipe';

    /**
     * @desc Журнал рецептов
     */
    public function list($param = '')
    {
        $this->data['title'] = 'Журнал рецептов';

        /** @var Request $request */
        $request = $this->model('Request');
        $methodModel = new Methods();

        /** @var  Recipe $usedModel */
        $usedModel = $this->model($this->nameModel);

        $this->data['date_start'] = $request->getDateStart();
        $this->data['param'] = urldecode($param);
        $this->data['reactive'] = $usedModel->getFromSQL('reactive');
        $this->data['solvent'] = $usedModel->getFromSQL('solvent');
        $this->data['doc'] = $methodModel->getList();
        $this->data['recipe'] = $usedModel->getFromSQL('recipe');
        $this->data['unit_of_concentration'] = $usedModel->getFromSQL('unit_of_concentration');
        $this->data['unit_of_quantity'] = $usedModel->getFromSQL('unit_of_quantity');
        $this->data['recipe_type'] = $usedModel->getFromSQL('recipe_type');

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/css/select2-bootstrap-5-theme.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

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
        $this->addCSS("/assets/plugins/DataTables/Responsive-2.4.0/responsive.dataTables.css");

        $this->addJs("/assets/js/recipe-journal.js");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала рецептов
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Recipe $usedModel */
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
            if (!empty($column['search']['value'])) {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if (isset($_POST['order']) && !empty($_POST['columns'])) {
            $filter['order']['by'] = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

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
     * @desc Добавляет рецепт
     */
    public function addModelRecipe()
    {
        $successMsg = 'Рецепт успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить рецепт';

        $usedModel = $this->model($this->nameModel);

        $newAdd['recipe_model'] = $_POST['recipe_model'];
        $newAdd['solvent'] = $_POST['solvent'];
        $newAdd['reactives'] = $_POST['reactives'];
        
        $isAdd = $usedModel->addToSQL($newAdd, 'modelRecipe');

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->location);
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->location);
    }

    /**
     * @desc Добавляет раствор как реактив
     */
    public function addSolutionAsReactive()
    {
        $successMsg = 'Реактив успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        $isAdd = $usedModel->addToSQL($newAdd, 'solutionAsReactive');

        if (!$isAdd) {
            $this->showErrorMessage($unsuccessfulMsg);
            $this->redirect($usedModel->location);
        }

        $this->showSuccessMessage($successMsg);
        $this->redirect($usedModel->location);
    }
}
