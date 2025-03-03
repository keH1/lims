<?php

/**
 * @desc Протоколирование рабочего времени
 * Class GanttController
 */
class GanttController extends Controller
{
    /**
     * @desc Страница «Протоколирование рабочего времени»
     * route: /ulab/gantt/index
     * @return void
     */
    public function index()
    {
        global $APPLICATION;
//        $APPLICATION->RestartBuffer();
        $APPLICATION->setTitle("Протоколирование рабочего времени");

        $version = "?v=" . rand();

        $this->addJS("/assets/js/jquery-3.5.1.min.js" . $version);
        $this->addJS("/assets/js/gantt/gantt.js" . $version);
        $this->addJS("/assets/plugins/modal/modalWindow.js");
        $this->addJS('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/css/gantt.css" . $version);

        $filter = [
            'user_filter' => $_GET['user_filter'],
            'project_filter' => $_GET['project_filter'],
        ];

        $this->data['calendar'] = $this->createCalendar();
        $this->data['current_date'] = $this->getCurrentDate();
        $this->data['filter'] = $filter;
        if ($_GET['action'] == "reset") {
            $this->data['filter'] = [];
            $filter = [];
            unset($_GET['user_filter']);
            unset($_GET['project_filter']);
        }

        $gantt = new Gantt();
        $this->data['table']['users'] = $gantt->getUsers();

        if (is_null($_GET['VIEW_MODE']) || $_GET['VIEW_MODE'] == "1") {
            $projects = $gantt->getProjectsInfo($filter);
            $this->data['table']['projects'] = $projects;

            $viewName = "index";

            $this->data['VIEW_MODE'] = 1;
            $this->view($viewName);
            return;
        }
        $this->data['VIEW_MODE'] = 2;

        $this->data['table']['table_info'] = $gantt->getTableInfo($filter);

        $viewName = "index2";

        $this->view($viewName);
    }

    /**
     * @desc Получает информацию о временной шкале
     *  method: post
     *  route: /ulab/gantt/collectTimeLineInfo
     * @return void
     */
    public function collectTimeLineInfo()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $gantt = new Gantt();

        $response = [];
//        $projects = $gantt->getProjectsInfo();
        $projects = $gantt->getAllMembers();
        $response['data'] = $projects;

        echo json_encode($response);
    }


    /**
     * @desc Получает массив данных о текущей дате
     * @return array
     */
    protected function getCurrentDate(): array
    {
        $currentDate = new DateTime();
        $timezone = new DateTimeZone('Asia/Yekaterinburg');
        $currentDate->setTimezone($timezone);
//        $currentDate->setTimezone(new DateTimeZone('GMT+5'));

        $currentMonth = $currentDate->format('F');
        $currentMonthNumber = $currentDate->format('n');
        $currentYear = $currentDate->format('Y');
        $currentDay = $currentDate->format('j');

        return [
            'month' => [
                'name' => $currentMonth,
                'number' => $currentMonthNumber,
            ],
            'year' => $currentYear,
            'day' => $currentDay,
            'full_date' => $currentDate->format('Y-m-d'),
        ];
    }


    /**
     * @desc Создаёт календарь
     * @return array
     */
    protected function createCalendar(): array
    {

        $currentDate = new DateTime();
        $timezone = new DateTimeZone('Asia/Yekaterinburg');
//        $currentDate->setTimezone($timezone);
        $endDate = clone $currentDate;
        $startDate = clone $currentDate;


        $interval = new DateInterval('P14D');
        $endDate->add(new DateInterval('P7D'));


        $calendar = [];

        $startDate->sub($interval);

        while ($startDate <= $endDate) {
            $monthName = $startDate->format('F');
            $monthNumber = $startDate->format('n');
            $year = $startDate->format('Y');

            if (!isset($calendar[$monthName])) {
                $calendar[$monthName] = [
                    'local_name' => strftime("%B", $startDate->getTimestamp()),
                    'days' => [],
                    'month_number' => $monthNumber,
                    'year' => $year,
                    'full_date' => $startDate->format('Y-m-d'),
                ];
            }

            $calendar[$monthName]['days'][] = $startDate->format('j');

            $startDate->add(new DateInterval('P1D'));
        }

        return $calendar;
    }


    /**
     * @desc Добавляет сотрудника для протоколирования рабочего времени
     * route: /ulab/gantt/addUser
     * method: POST
     * @return void
     */
    public function addUser()
    {
        $attributes = [
            'name' => $_POST['name'],
            'position' => $_POST['position'],
            'salary' => $_POST['salary'],
        ];

        $gantt = new Gantt();
        $gantt->createUser($attributes);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    /**
     * @desc Добавляет проект
     * route: /ulab/gantt/addProject
     * method: POST
     * @return void
     */
    public function addProject()
    {
        $attributes = [
            'project_name' => $_POST['project_name'],
            'color1' => $_POST['color1'],
            'color2' => $_POST['color2'],
        ];

        $gantt = new Gantt();
        $gantt->createProject($attributes);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    /**
     * @desc Получает данные пользователя из протоколирования рабочего времени
     * route: /ulab/gantt/getUser
     * method: POST
     * @return void
     */
    public function getUser()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $response = [
            'error' => false,
            'message' => '',
            'data' => [],
        ];

        if (empty($_POST['id'])) {
            $response['error'] = true;
            $response['message'] = "Отсутствует идентификатор пользователя";


            echo json_encode($response);
            return;
        }

        $gantt = new Gantt();
        $data = $gantt->getUser($_POST['id'], empty($_POST['project_id']) ? null : $_POST['project_id']);

        $response['data'] = $data;

        echo json_encode($response);
    }


    /**
     * @desc Получает данные проекта
     * route: /ulab/gantt/getProject
     * method: POST
     * @return void
     */
    public function getProject()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $response = [
            'error' => false,
            'message' => '',
            'data' => [],
        ];

        if (empty($_POST['id'])) {
            $response['error'] = true;
            $response['message'] = "Отсутствует идентификатор проекта";


            echo json_encode($response);
            return;
        }

        $gantt = new Gantt();
        $data = $gantt->getProject($_POST['id'], $_POST['user_id']);

        $response['data'] = $data;

        echo json_encode($response);
    }


    /**
     * @desc Обновляет данные пользователя из протоколирования рабочего времени
     * route: /ulab/gantt/editUser
     * method: POST
     * @return void
     */
    public function editUser()
    {
        $attributes = [
            'name' => $_POST['name'],
            'position' => $_POST['position'],
            'salary' => $_POST['salary'],
        ];
        $dates = $_POST['dates'];
        $userId = $_POST['user_id'];

        $gantt = new Gantt();
        $gantt->editUser($attributes, $dates, $userId);

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }


    /**
     * @desc Обновляет данные проекта
     * route: /ulab/gantt/editProject
     * method: POST
     * @return void
     */
    public function editProject()
    {

        $attributes = [
            'project_name' => $_POST['project_name'],
        ];
        $projectId = $_POST['project_id'];
        $memberId = $_POST['project_member_id'];
        $dates = $_POST['dates'];

        $gantt = new Gantt();

        $gantt->editProject($attributes, $projectId, $dates);
        if (!empty($memberId) && intval($memberId) != -1) {
            $gantt->connectUserToProject($memberId, $projectId);
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}