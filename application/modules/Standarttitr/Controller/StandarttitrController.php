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
        $this->data['title'] = 'Журнал стандарт-титры';

        /** @var  Recipe $usedModel */
        $usedModel = $this->model($this->nameModel);

        foreach ($usedModel->getSelect() as $key => $item) {
            $this->data[$key] = $item;
        }
        $version = "?=" . rand();


        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/css/select2-bootstrap-5-theme.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $this->addJs("/assets/js/standarttitr-journal.js" . $version);

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Получает данные для журнала «Стандарт-титры»
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
     * @desc Добавляет производителя в «Стандарт-титры»
     */
    public function addManufacturer()
    {
        $successMsg = 'Производитель успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить производителя';
        $usedModel = $this->model($this->nameModel);

        $newAdd['standart_titr_manufacturer']= $_POST['standart_titr_manufacturer'];
        $newAdd['standart_titr_manufacturer']['organization_id'] = App::getOrganizationId();

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
        $newAdd['standart_titr']['organziation_id'] = App::getOrganizationId();
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
        $newAdd['standart_titr_receive']['organziation_id'] = App::getOrganizationId();
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

        $filter['id'] = (int)$_POST['which_select_id'];
        $filter['type'] = $usedModel->sanitize($_POST['type']);

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
        $newAdd['standart_titr']['organization_id'] = App::getOrganizationId();

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
        $newAdd['standart_titr_receive']['organization_id'] = App::getOrganizationId();
        $isAdd = $usedModel->newUpdateSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }
}
