<?php


/**
 * @desc Охрана труда
 * Class SafetyTrainingController
 */
class SafetyTrainingController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу журнала охраны труда
     * route /safetyTraining/
     */
    public function index(): void
    {
        $this->redirect('/safetyTraining/list/');
    }

    /**
     * @desc Страница журнала охраны труда
     */
    public function list(): void
    {
        $this->data['title'] = 'Журнал охраны труда';

        $this->data['date_start'] = date('Y-m-d', strtotime('-1 year'));
        $this->data['date_end'] = date('Y-m-d');

        $this->addJs("/assets/js/safety-training/list.js?v=" . rand());

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Получает данные для журнала охраны труда
     */
    public function getSafetyTrainingLogAjax(): void
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var SafetyTraining $safetyTrainingModel */
        $safetyTrainingModel = $this->model('SafetyTraining');

        $filter = $safetyTrainingModel->prepareFilter($_POST ?? []);

        $data = $safetyTrainingModel->getSafetyTrainingLog($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Сохраняет данные для журнала охраны труда
     */
    public function insert(): void
    {
        /** @var SafetyTraining $safetyTrainingModel */
        $safetyTrainingModel = $this->model('SafetyTraining');

        $location = "/safetyTraining/list/";

        $data = $_POST['form'] ?? [];

        // Валидация
        $this->validate($data);

        $result = $safetyTrainingModel->addSafetyTrainingLog($_POST['form']);

        if (empty($result)) {
            $this->showErrorMessage('Не удалось сохранить данные охраны труда');
        } else {
            $this->showSuccessMessage('Данные охраны труда сохранены успешно');
        }

        $this->redirect($location);
    }

    /**
     * @desc Валидация данных по охране труда
     */
    private function validate(array $data): void
    {
        $location = "/safetyTraining/list/";

        $validLastName = $this->validateField($data['last_name'], 'Фамилия', true);
        if (!$validLastName['success']) {
            $this->showErrorMessage($validLastName['error']);
            $this->redirect($location);
        }

        $validName = $this->validateField($data['name'], 'Имя', true);
        if (!$validName['success']) {
            $this->showErrorMessage($validName['error']);
            $this->redirect($location);
        }

        $validSecondName = $this->validateField($data['second_name'], 'Отчество', true);
        if (!$validSecondName['success']) {
            $this->showErrorMessage($validSecondName['error']);
            $this->redirect($location);
        }

        $validTrainingType = $this->validateField($data['training_type'], 'Вид инструктажа', true);
        if (!$validTrainingType['success']) {
            $this->showErrorMessage($validTrainingType['error']);
            $this->redirect($location);
        }

        $validTrainingDate = $this->validateDate($data['training_date'], 'Дата инструктажа', true);
        if (!$validTrainingDate['success']) {
            $this->showErrorMessage($validTrainingDate['error']);
            $this->redirect($location);
        }
    }
}
