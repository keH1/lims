<?php

/**
 * @desc Учет работ по очистки и дезинфекции кондиционеров
 * Class DisinfectionConditionersController
 */
class DisinfectionConditionersController extends Controller
{
    private string $nameModel = 'DisinfectionConditioners';


    /**
     * @desc Журнал учета работ по очистки и дезинфекции кондиционеров
     */
    public function list()
    {
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $this->data['title'] = 'Журнал учета работ по очистки и дезинфекции кондиционеров';

        $this->data['rooms'] = $labModel->getRooms();

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $this->addJs("/assets/js/disinfection-conditioners-journal.js");

        $this->view('list', '', 'template_journal');
    }


    /**
     * @desc Получает данные для журнала учета работ по очистки и дезинфекции кондиционеров
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
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Добавляет запись
     */
    public function addRecord()
    {
        $successMsg = 'Запись успешно добавлена';
        $unsuccessfulMsg = 'Не удалось добавить запись';

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
}
