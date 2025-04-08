<?php

/**
 * @desc Управление персоналом
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     * @return void
     * @hide true
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }

    /**
     * @desc Получает список статусов пользователя.
     * @return void
     * @hide true
     */
    public function getStatusListAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $info = $userModel->getStatusList();

        echo json_encode($info, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Страница со списком пользователей
     */
    public function list()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        $this->data['title'] = 'Пользователи';

        /** @var  User $user */
        $user = $this->model('User');
        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');
        /** @var  Lab $labModel */
        $labModel = $this->model('Lab');

        $this->data['role_list'] = $permissionModel->getPermission();
        $positionList = [];
        $departmentList = [];

        $users = $user->getUsers();

        foreach ($users as $us) {
            if ( !empty($us['DEPARTMENT_NAME']) && !in_array($us['DEPARTMENT_NAME'], $departmentList) ) {
                $departmentList[] = $us['DEPARTMENT_NAME'];
            }
            if ( !empty($us['WORK_POSITION']) && !in_array($us['WORK_POSITION'], $positionList) ) {
                $positionList[] = $us['WORK_POSITION'];
            }
		}

        $this->data['position_list'] = $positionList;
        $this->data['department_list'] = $labModel->getList();

        $this->data['department_all'] = $user->getDepartmentsList();

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs("/assets/plugins/select2/js/select2.min.js");

        $r = rand();
        $this->addJs("/assets/js/user/users-list.js?v={$r}");
        $this->addJs("/assets/js/user/users-form.js?v={$r}");

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Страница со статусами
     */
    public function status()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        $this->data['title'] = 'Статусы сотрудников';

        /** @var User $userModel */
        $userModel = $this->model('User');

        $this->data['statuses'] = $userModel->getStatusList();

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

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $r = rand();
        $this->addJs("/assets/js/user/status-list.js");

        $this->view('status');
    }

    /**
     * @desc Получает данные пользователей для журнала «Статусы сотрудников»
     * @return void
     * @hide true
     */
    public function getUsersForStatusAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $filter = $userModel->prepareFilter($_POST ?? []);

        $data = $userModel->getUsersForStatusJournal($filter);

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
     * @desc Получает список пользователей
     * @return void
     * @hide true
     */
    public function getUsersListAjax() {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $data = $userModel->getAllUsersList();

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Обновлять роль у пользователя
     * @hide true
     */
    public function updateStatus()
    {
        /** @var  User $userModel */
        $userModel = $this->model('User');

        $userModel->updateStatus($_POST['user_id'], $_POST['status_id']);

        $this->showSuccessMessage("Статусы успешно обновлены");

        $this->redirect("/user/status/");
    }

    /**
     * @desc Обновлять роль у пользователя
     * @hide true
     */
    public function updateReplacement()
    {
        /** @var  User $userModel */
        $userModel = $this->model('User');

        $userModel->updateReplacement((int)$_POST['user_id'], (int)$_POST['replacementId']);

        $this->showSuccessMessage("Статусы успешно обновлены");

        $this->redirect("/user/status/");
    }

    /**
     * Принимает на вход массив
     * @desc Обновлять статус у нескольких пользователей сразу
     * @hide true
     */
    public function updateUsersStatus()
    {
        /** @var  User $userModel */
        $userModel = $this->model('User');

        $usersData = $_POST['array_update_users'];
        foreach ($usersData as $userData) {
              $userId = $userData['user_id'];
              $statusId = $userData['status_id'];
              $userModel->updateStatus($userId, $statusId);
        }

        $this->showSuccessMessage("Статусы успешно обновлены");
    }

    /**
     * Принимает на вход массив
     * @desc Обновлять замены у нескольких пользователей сразу
     * @hide true
     */
    public function updateUsersReplacement()
    {
        /** @var  User $userModel */
        $userModel = $this->model('User');

        $usersData = $_POST['array_update_users'];
        foreach ($usersData as $userData) {
              $userId = (int)$userData['user_id'];
              $replacementId = (int)$userData['replacement_id'];
              $userModel->updateReplacement($userId, $replacementId);
        }

        $this->showSuccessMessage("Статусы успешно обновлены");
    }

    /**
     * @desc Обновляет заметку пользователя
     * @return void
     * @hide true
     */
    public function updateUsersNote()
    {
        /** @var  User $userModel */
        $userModel = $this->model('User');

        $usersData = $_POST['array_update_users'];
        foreach ($usersData as $userData) {
              $userId = $userData['user_id'];
              $text = $userData['replacement_text'];
              $userModel->updateNote($userId, $text);
        }

        $this->showSuccessMessage("Статусы успешно обновлены");
    }

    /**
     * @desc Обновляет должность пользователя
     * @return void
     * @hide true
     */
    public function updateUsersJob()
    {
        /** @var  User $userModel */
        $userModel = $this->model('User');

        $usersData = $_POST['array_update_users'];
        foreach ($usersData as $userData) {
              $userId = $userData['user_id'];
              $text = $userData['job_title'];
              $userModel->updateJob($userId, $text);
        }

        $this->showSuccessMessage("Статусы успешно обновлены");
    }

    /**
     * @hide
     */
    public function getUserHomeAjax() {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var  User $userModel */
        $userModel = $this->model('User');
        $data = $userModel->getUserHomePage() ?? "/ulab/request/new";
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получает данные пользователей
     */
    public function getUsersDataAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var  User $userModel */
        $userModel = $this->model('User');

        $email = $_POST['email'] ?? '';
        $login = $_POST['login'] ?? '';
        $userId = $_POST['user_id'] ?? 0;
        
        $result = [];
        $i = 0;
        
        if (!empty($email) || !empty($login)) {
            $userData = $userModel->getUsersData($email, $login, $userId);
            
            if (!empty($userData['email'])) {
                $result[$i]['email'] = $userData['email'];
                $result[$i]['error_messages'] = 'Пользователь с таким Email уже существует';
                $i++;
            }
            
            if (!empty($userData['login'])) {
                $result[$i]['login'] = $userData['login'];
                $result[$i]['error_messages'] = 'Пользователь с таким логином уже существует';
                $i++;
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}