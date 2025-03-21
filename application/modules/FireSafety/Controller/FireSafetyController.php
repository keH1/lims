<?php


/**
 * @desc Пожарная безопасность
 * Class FireSafetyController
 */
class FireSafetyController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу журнала пожарной безопасности
     * route /fireSafety/
     */
    public function index(): void
    {
        $this->redirect('/fireSafety/list/');
    }

    /**
     * @desc Страница журнала пожарной безопасности
     */
    public function list(): void
    {
        $this->data['title'] = 'Журнал пожарной безопасности';

        /** @var  User $userModel*/
        $userModel = $this->model('User');

        $this->data['date_start'] = date('Y-m-d', strtotime('-1 year'));
        $this->data['date_end'] = date('Y-m-d');

        $this->data['users'] = $userModel->getUsers();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs("/assets/js/fire-safety/list.js?v=" . rand());

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Получает данные для журнала пожарной безопасности
     */
    public function getFireSafetyLogAjax(): void
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var FireSafety $fireSafetyModel */
        $fireSafetyModel = $this->model('FireSafety');

        $filter = $fireSafetyModel->prepareFilter($_POST ?? []);

        $data = $fireSafetyModel->getFireSafetyLog($filter);

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
     * @desc Сохраняет данные для журнала пожарной безопасности
     */
    public function insert(): void
    {
        /** @var FireSafety $fireSafetyModel */
        $fireSafetyModel = $this->model('FireSafety');

        $location = "/fireSafety/list/";

        $data = $_POST['form'] ?? [];

        // Валидация
        $this->validate($data);

        $result = $fireSafetyModel->addFireSafetyLog($data);

        if (empty($result)) {
            $this->showErrorMessage('Не удалось сохранить данные пожарной безопасности');
        } else {
            $this->showSuccessMessage('Данные пожарной безопасности сохранены успешно');
        }

        $this->redirect($location);
    }

    /**
     * @desc Валидация данных по пожарной безопасности
     */
    private function validate(array $data): void
    {
        $location = "/fireSafety/list/";

        $validTheoryDate = $this->validateDate($data['theory_date'], 'Дата теоретического инструктажа', true);
        if (!$validTheoryDate['success']) {
            $this->showErrorMessage($validTheoryDate['error']);
            $this->redirect($location);
        }

        $validInstructionType = $this->validateField($data['instruction_type'], 'Вид инструктажа', true);
        if (!$validInstructionType['success']) {
            $this->showErrorMessage($validInstructionType['error']);
            $this->redirect($location);
        }

        $validInstructedId = $this->validateNumber($data['instructed_id'], 'ФИО инструктируемого', false);
        if (!$validInstructedId['success']) {
            $this->showErrorMessage($validInstructedId['error']);
            $this->redirect($location);
        }

        $validTheoryInstructorLastname = $this->validateField($data['theory_instructor_lastname'], 'Фамилия инструктора теоретическая часть', true);
        if (!$validTheoryInstructorLastname['success']) {
            $this->showErrorMessage($validTheoryInstructorLastname['error']);
            $this->redirect($location);
        }

        $validTheoryInstructorName = $this->validateField($data['theory_instructor_name'], 'Имя инструктора теоретическая часть', true);
        if (!$validTheoryInstructorName['success']) {
            $this->showErrorMessage($validTheoryInstructorName['error']);
            $this->redirect($location);
        }

        $validTheoryInstructorSecondname = $this->validateField($data['theory_instructor_secondname'], 'Отчество инструктора теоретическая часть', true);
        if (!$validTheoryInstructorSecondname['success']) {
            $this->showErrorMessage($validTheoryInstructorSecondname['error']);
            $this->redirect($location);
        }

        $validTheoryInstructorDoc = $this->validateField($data['theory_instructor_doc'], 'Документ инструктора теоретическая часть', true);
        if (!$validTheoryInstructorDoc['success']) {
            $this->showErrorMessage($validTheoryInstructorDoc['error']);
            $this->redirect($location);
        }

        $validPracticeDate = $this->validateDate($data['practice_date'], 'Дата практического инструктажа', false);
        if (!$validPracticeDate['success']) {
            $this->showErrorMessage($validPracticeDate['error']);
            $this->redirect($location);
        }

        $validPracticeInstructorLastname = $this->validateField($data['practice_instructor_lastname'], 'Фамилия инструктора практического инструктажа', false);
        if (!$validPracticeInstructorLastname['success']) {
            $this->showErrorMessage($validPracticeInstructorLastname['error']);
            $this->redirect($location);
        }

        $validPracticeInstructorName = $this->validateField($data['practice_instructor_name'], 'Имя инструктора практического инструктажа', false);
        if (!$validPracticeInstructorName['success']) {
            $this->showErrorMessage($validPracticeInstructorName['error']);
            $this->redirect($location);
        }

        $validPracticeInstructorSecondname = $this->validateField($data['practice_instructor_secondname'], 'Отчество инструктора практического инструктажа', false);
        if (!$validPracticeInstructorSecondname['success']) {
            $this->showErrorMessage($validPracticeInstructorSecondname['error']);
            $this->redirect($location);
        }

        $validPracticeInstructorDoc = $this->validateField($data['practice_instructor_doc'], 'Документ инструктора практического инструктажа', false);
        if (!$validPracticeInstructorDoc['success']) {
            $this->showErrorMessage($validPracticeInstructorDoc['error']);
            $this->redirect($location);
        }
    }
}