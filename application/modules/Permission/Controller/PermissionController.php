<?php

/**
 * @desc Страница управление доступами
 * Class PermissionController
 */
class PermissionController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу со списком пользователей
     */
    public function index()
    {
        $this->redirect('/permission/users/');
    }


    /**
     * @desc Страница со списком пользователей
     */
    public function users()
    {
        $this->data['title'] = 'Пользователи';

        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');
        /** @var  User $user */
        $user = $this->model('User');

        $this->data['role_list'] = $permissionModel->getPermission();
        $positionList = [];
        $departmentList = [];

        $users = $user->getUsers();
        $data = [];

        foreach ($users as $us) {
            $userRole = $permissionModel->getUserRole($us['ID']);

            if ( !empty($us['WORK_DEPARTMENT']) && !in_array($us['WORK_DEPARTMENT'], $departmentList) ) {
                $departmentList[] = $us['WORK_DEPARTMENT'];
            }
            if ( !empty($us['WORK_POSITION']) && !in_array($us['WORK_POSITION'], $positionList) ) {
                $positionList[] = $us['WORK_POSITION'];
            }

        	$data[] = [
        		'id' => $us['ID'],
        		'FIO' => $us['LAST_NAME'] . ' ' . $us['NAME'] . ' ' . $us['SECOND_NAME'],
				'department' => empty($us['WORK_DEPARTMENT'])? 'Отдел не указан' : $us['WORK_DEPARTMENT'],
				'position' => empty($us['WORK_POSITION'])? 'Должность не указана' : $us['WORK_POSITION'],
                'role' => $userRole,
			];
		}

        $this->data['position_list'] = $positionList;
        $this->data['department_list'] = $departmentList;

        $this->data['users'] = $data;

        $r = rand();
        $this->addJs("/assets/js/permission-users.js?v={$r}");

        $this->view('users');
    }


    /**
     * @desc Управление доступами
     * @param int|string $roleId - выбранна роль
     */
    public function list($roleId = 1)
    {
        $this->data['title'] = 'Роли и доступы';

        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $this->data['controller_method_list'] = $permissionModel->getControllerMethod();

        $this->data['permission_list'] = $permissionModel->getPermission();

        if ( empty($roleId) ) {
            $roleId = 1;
        }

        $this->data['role_id'] = $roleId;

        $this->data['role_info'] = $permissionModel->getRoleInfo($roleId);

        $this->addJs('/assets/js/permission.js');

        $this->view('list');
    }


    /**
     * @desc Обновляет данные доступов у роли
     */
    public function updateRole()
    {
        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $roleId = (int)$_POST['role_id'];
        $postData = $_POST ?? [];

        $permissionModel->updateRole($roleId, $postData);

        $this->showSuccessMessage("Роль успешно обновлена");

        $this->redirect("/permission/list/{$roleId}");
    }


    /**
     * @desc Обновляет роли у пользователя
     */
    public function updateUser()
    {
        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $userId = (int)$_POST['user_id'];
        $roleId = (int)$_POST['role_id'];

        $permissionModel->updateUser($userId, $roleId);

        $this->showSuccessMessage("Пользователь успешно обновлен");

        $this->redirect("/permission/users/");
    }


    /**
     * @desc Получает для аякса информацию о выбранной роли
     */
    public function getRoleInfoAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $roleId = (int)$_POST['role_id'];

        $info = $permissionModel->getRoleInfo($roleId);

        echo json_encode($info, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные ролей
     */
    public function getPermissionListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $info = $permissionModel->getPermission();

        echo json_encode($info, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает список лабораторий
     * @hide true
     */
    public function getDepartmentsListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  User $user */
        $user = $this->model('User');

        $info = $user->getDEpartmentsList();

        echo json_encode($info, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает доступы текущего пользователя
     * @return void
     * @hide true
     */
    public function getCurrentUserPermissionAjax() {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        global $USER;
        $userId = $USER->GetID();

        /** @var  Permission $permission*/
        $permissionModel = $this->model('Permission');

        $permissionData =  $permissionModel->getUserPermission($userId);
        $permissionData['userId'] = $userId;


        $replacementUser = $permissionModel->getReplacementUserId($userId);
        if(isset($replacementUser)) {
            $permissionDataReplacement = $permissionModel->getUserPermission($replacementUser);
            $rolePermissionReplacement = $permissionDataReplacement['permission'];
            $roleViewNameReplacement = $permissionDataReplacement['view_name'];

            $temp = $permissionData['permission'];
            $permissionData['permission'] = array_merge([0 => $temp], [$rolePermissionReplacement]);

            if ($roleViewNameReplacement == 'admin')
                $permissionData['view_name'] = 'admin';
        }

        echo json_encode($permissionData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные для журнала «Пользователи»
     * @return void
     * @hide true
     */
    public function getUsersAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Permission $permission*/
        $permission = $this->model('Permission');

        $filter = $permission->prepareFilter($_POST ?? []);

        $data = $permission->getDatatoJournalUsers($filter);

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
     * @desc Обновляет роль пользователю
     */
    public function updateUsersRole()
    {
        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $usersData = $_POST['array_update_users'] ?? [];
        foreach ($usersData as $userData) {
            $userId = (int)$userData['user_id'];
            $roleId = (int)$userData['role_id'];
            $permissionModel->updateUser($userId, $roleId);
        }

        $this->showSuccessMessage("Пользователи успешно обновлены");
    }


    /**
     * @desc Обновляет лабораторию для пользователей
     * @return void
     * @hide true
     */
    public function updateUsersDepartment()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var  User $userModel */
        $userModel = $this->model('User');

        $usersData = $_POST['array_update_users'] ?? [];

        foreach ($usersData as $userData) {
            $userId = (int)$userData['user_id'];
            $departmentId = (int)$userData['department_id'];
            $userModel->updateUserDepartment($userId, $departmentId);
        }

        $this->showSuccessMessage("Пользователи успешно обновлены");
    }
}
