<?php


/**
 * @desc Пожарная безопасность
 * Class FireSafetyController
 */
class FireSafetyController extends Controller
{
    /**
     * @desc Страница журнала пожарной безопасности
     */
    public function list()
    {
        $this->data['title'] = 'Журнал пожарной безопасности';

        /** @var  User $userModel*/
        $userModel = $this->model('User');

        if (!empty($_SESSION['post'])) {
            $this->data['form'] = $_SESSION['post']['form'];
            unset($_SESSION['post']);
        }

        $this->data['date_start'] = date('Y-m-d', strtotime('-1 year'));
        $this->data['date_end'] = date('Y-m-d');

        $this->data['users'] = $userModel->getUsers();

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

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $r = rand();
        $this->addJs("/assets/js/fire-safety/list.js?v={$r}");

        $this->view('list');
    }

    /**
     * @desc Получает данные для журнала пожарной безопасности
     */
    public function getFireSafetyLogAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var FireSafety $fireSafetyModel */
        $fireSafetyModel = $this->model('FireSafety');

        $filter = [
            'paginate' => [
                'length' => $_POST['length'],  // кол-во строк на страницу
                'start' => $_POST['start'],  // текущая страница
            ],
            'search' => [],
            'order' => []
        ];

        foreach ($_POST['columns'] as $column) {
            if ($column['search']['value'] !== '') {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if (isset($_POST['order']) && !empty($_POST['columns'])) {
            $filter['order']['by'] = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

        if ( !empty($_POST['dateStart']) ) {
            $filter['search']['dateStart'] = date('Y-m-d', strtotime($_POST['dateStart'])) . ' 00:00:00';
            $filter['search']['dateEnd'] = date('Y-m-d', strtotime($_POST['dateEnd'])) . ' 23:59:59';
        }

        if (!empty($_POST['room'])) {
            $filter['search']['room'] = $_POST['room'];
        }


        $data = $fireSafetyModel->getFireSafetyLog($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Сохраняет данные для журнала пожарной безопасности
     */
    public function insert()
    {
        /** @var FireSafety $fireSafetyModel */
        $fireSafetyModel = $this->model('FireSafety');

        $location = "/fireSafety/list/";

        $data = $_POST['form'] ?? [];
        $data['created_by'] = (int)$_SESSION['SESS_AUTH']['USER_ID'];

        $_SESSION['post'] = $_POST;

        // Валидация
        $this->validate($data);

        $result = $fireSafetyModel->addFireSafetyLog($_POST['form']);

        if (empty($result)) {
            $this->showErrorMessage('Не удалось сохранить данные пожарной безопасности');
        } else {
            $this->showSuccessMessage('Данные пожарной безопасности сохранены успешно');
            unset($_SESSION['post']);
        }

        $this->redirect($location);
    }

    /**
     * @desc Валидация данных по пожарной безопасности
     */
    private function validate($data)
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