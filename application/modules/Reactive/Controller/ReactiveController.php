<?php

/**
 * @desc Журнал учета реактивов
 * Class ReactiveController
 */
class ReactiveController extends Controller
{
    private string $nameModel = 'Reactive';

    /**
     * @desc Журнал "Журнал учета реактивов"
     */
    public function list()
    {
        $this->data['title'] = 'Журнал учета реактивов';

        $usedModel = $this->model($this->nameModel);

        foreach ($usedModel->getSelect() as $key => $item) {
            $this->data[$key] = $item;
        }

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $this->addJs("/assets/js/reactive-journal.js?v=2");

        $this->view('list', '', 'template_journal');
    }

    /**
     *  Получает данные для журнала учета реактивов
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
     *  Добавляет НД и квалификацию реактива
     */
    public function addReactive()
    {
        $successMsg = 'Реактив успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd = [
            'reactive' => $_POST['reactive']
        ];

        $isAdd = $usedModel->addToSQL($newAdd, 'addReactive');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     *  Проводит реактив
     */
    public function addReceive()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';

        $usedModel = $this->model($this->nameModel);

        $organizationId = App::getOrganizationId();

        $newAdd = [
            'reactive_receive' => $_POST['reactive_receive'],
        ];
        $newAdd['reactive_receive']['organization_id'] = $organizationId;

        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     *  Добавляет реактив
     */
    public function addReactiveModel()
    {
        $successMsg = 'Реактив успешно сохранен';
        $unsuccessfulMsg = 'Не удалось сохранить реактив';

        $usedModel = $this->model($this->nameModel);

        $newAdd = [
            'reactive_model' => $_POST['reactive_model']
        ];

        $isAdd = $usedModel->addToSQL($newAdd, 'addReactiveModelNoUnitMeasurement');

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    public function addReceiveReactive()
    {
        $successMsg = 'Реактив успешно проведен';
        $unsuccessfulMsg = 'Не удалось провести реактив';
        $usedModel = $this->model($this->nameModel);

        $newAdd = [
            'reactive_receive' => $_POST['reactive_receive']
        ];

        $isAdd = $usedModel->addToSQL($newAdd);

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }

    /**
     *  Получает данные для редактирования реактива
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
     *  Обновляет данные реактива
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
     *  Редактирует проводку реактива
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
