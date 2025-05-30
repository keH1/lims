<?php

use Bitrix\Im\Integration\Intranet\Department;


/**
 * @desc Профиль организации
 * Class ImportController
 * @order 199
 */
class ImportController extends Controller
{
    const WORKING_WITH_DEPARTMENTS = 26;// Работает с подразделениями
    const USERS_EDIT_ONBOARDING = [1];// Редактирует онбординг
    const MODULE_UPLOAD_DIR = "/../../../upload/"; // UPLOAD_DIR;
    const USER_ADMIN_ROLE_ID = 2;

    /**
     * Проверяет права доступа пользователя и выполняет перенаправление при необходимости
     * @return array Массив с данными пользователя и инициализированными моделями
     */
    private function checkAccessAndRedirect($id = null)
    {
        $orgModel = new Organization();
        $userModel = new User();
        $permissionModel = new Permission();

        $userId = App::getUserId();
        $dataUser = $orgModel->getAffiliationUserInfo($userId);
        $isAdmin = $permissionModel->getUserRole($userId) == self::USER_ADMIN_ROLE_ID ? true : false;

        if ($id == null) {
            if (!$isAdmin && empty($dataUser['lab_id'])) {
                $this->redirectToAccessDenied();
            } else if ($isAdmin && empty($dataUser['lab_id'])) {
                $this->redirect('/import/organization/');
            }
        }

        return [
            'dataUser' => $dataUser,
            'isAdmin' => $isAdmin,
            'orgModel' => $orgModel,
            'userModel' => $userModel,
            'permissionModel' => $permissionModel
        ];
    }

    /**
     * Перенаправляет пользователя на страницу «Профиль лаборатории»
     * @hide true
     */
    public function index()
    {
        $organizationId = App::getOrganizationId();

        // если админ, то перенаправляем в журнал организации
        if ( App::isAdmin()) {
            $this->redirect('/import/organizationList/');
        }

        $orgModel = new Organization();

        $data = $orgModel->getAffiliationUserInfo(App::getUserId());

        // если пользователь не админ, но входит в организацию, то перенаправляем в профиль организации
        if ( !empty($organizationId) ) {
            $this->redirect("/import/organization/");
        }

        $this->redirect('/request/list/');
    }


    /**
     * Перенаправляет пользователя на страницу «Журнал организаций»
     */
    public function organizationList()
    {
        if ( !App::isAdmin()) {
            $organizationId = App::getOrganizationId();
            // если пользователь не админ, но входит в организацию, то перенаправляем в профиль организации
            if ( !empty($organizationId) ) {
                $this->redirect("/import/organization/");
            } else {
                $this->redirect('/request/list/');
            }
        }

        $userModel = new User();

        $this->data['title'] = 'Журнал организаций';

        $this->data['users'] = $userModel->getUsers();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs("/assets/js/import/organization_list.js?v=" . rand());

        $this->view('organization_list', '', 'template_journal');
    }


    /**
     * Перенаправляет пользователя на страницу «Профиль организации»
     */
    public function organization()
    {
        $orgModel = new Organization();
        $userModel = new User();

        $organizationId = App::getOrganizationId();

        if ( !App::isAdmin()) {
            $data = $orgModel->getAffiliationUserInfo(App::getUserId());
            $this->data['is_show_btn'] = false;
        } else {
            $this->data['is_show_btn'] = true;
        }

        $this->data['title'] = 'Профиль организации';

        $this->data['users'] = $userModel->getUsers();
        $this->data['info'] = $orgModel->getOrgInfo($organizationId);

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs("/assets/js/import/organization.js?v=" . rand());

        $this->view('organization', '', 'template_journal');
    }


    /**
     * Перенаправляет пользователя на страницу «Профиль департамента»
     */
    public function branch($id = null)
    {
        $result = $this->checkAccessAndRedirect($id);

        $dataUser = $result['dataUser'];
        // $isAdmin = $result['isAdmin'];
        $orgModel = $result['orgModel'];
        $userModel = $result['userModel'];

        $branchId = empty($id) ? (int)$dataUser['branch_id'] : (int)$id;

        $this->data['title'] = 'Профиль департамента';

        $this->data['users'] = $userModel->getUsers();
        $this->data['info'] = $orgModel->getBranchInfo($branchId);
        $this->data['org_info'] = $orgModel->getOrgInfo($this->data['info']['organization_id']);

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs("/assets/js/import/branch.js?v=" . rand());

        $this->view('branch', '', 'template_journal');
    }


    /**
     * Перенаправляет пользователя на страницу «Профиль отдела»
     */
    public function dep($id = null)
    {
        $result = $this->checkAccessAndRedirect($id);
        $dataUser = $result['dataUser'];
        $orgModel = $result['orgModel'];
        $userModel = $result['userModel'];

        $depId = empty($id) ? (int)$dataUser['dep_id'] : (int)$id;
        
        $this->data['title'] = 'Профиль отдела';

        $this->data['users'] = $userModel->getUsers();
        $this->data['info'] = $orgModel->getDepInfo($depId);
        $this->data['branch_info'] = $orgModel->getBranchInfo($this->data['info']['branch_id']);
        $this->data['org_info'] = $orgModel->getOrgInfo($this->data['branch_info']['organization_id']);

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs("/assets/js/import/dep.js?v=" . rand());

        $this->view('dep', '', 'template_journal');
    }


    /**
     * Перенаправляет пользователя на страницу «Профиль лабооратории»
     */
    public function labProfile($id = null)
    {
        $result = $this->checkAccessAndRedirect($id);
        $dataUser = $result['dataUser'];
        $orgModel = $result['orgModel'];
        $userModel = $result['userModel'];

        $labId = empty($id) ? (int)$dataUser['lab_id'] : (int)$id;
        
        $this->data['title'] = 'Профиль лаборатории';

        $this->data['users'] = $userModel->getUsers();

        $positionList = [];
        foreach ($this->data['users'] as $us) {
            if ( !empty($us['WORK_POSITION']) && !in_array($us['WORK_POSITION'], $positionList) ) {
                $positionList[] = $us['WORK_POSITION'];
            }
		}

        $this->data['position_list'] = $positionList;
        $this->data['info'] = $orgModel->getLabInfo($labId);
        $this->data['dep_info'] = $orgModel->getDepInfo($this->data['info']['dep_id']);
        $this->data['branch_info'] = $orgModel->getBranchInfo($this->data['dep_info']['branch_id']);
        $this->data['org_info'] = $orgModel->getOrgInfo($this->data['branch_info']['organization_id']);
        $this->data['not_affiliation_users'] = $orgModel->getNotAffiliationUser();
        $this->data['status_list'] = $userModel->getStatusList();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs("/assets/js/import/lab_profile.js?v=" . rand());

        $this->view('lab_profile', '', 'template_journal');
    }


    /**
     * обновляет информацию об организации
     */
    public function orgUpdate()
    {
        $orgModel = new Organization();

        $id = (int)$_POST['org_id'];

        $orgModel->setOrgInfo($id, $_POST['form']);

        if ( !empty($_POST['form']['head_user_id']) ) {
            $data = ['org_id' => $id];
            $orgModel->setAffiliationUserInfo((int)$_POST['form']['head_user_id'], $data);
        }

        $this->showSuccessMessage("Данные организации обновлены");

        $this->redirect("/import/organization/{$id}");
    }


    /**
     * обновляет информацию о департаменте
     */
    public function branchUpdate()
    {
        $orgModel = new Organization();

        $id = (int)$_POST['branch_id'];

        $orgModel->setBranchInfo($id, $_POST['form']);

        if ( !empty($_POST['form']['head_user_id']) ) {
            $data = ['branch_id' => $id];
            $orgModel->setAffiliationUserInfo((int)$_POST['form']['head_user_id'], $data);
        }

        $this->showSuccessMessage("Данные департамента обновлены");

        $this->redirect("/import/branch/{$id}");
    }


    /**
     * обновляет информацию об отделе
     */
    public function depUpdate()
    {
        $orgModel = new Organization();

        $id = (int)$_POST['dep_id'];

        $orgModel->setDepInfo($id, $_POST['form']);

        if ( !empty($_POST['form']['head_user_id']) ) {
            $data = ['dep_id' => $id];
            $orgModel->setAffiliationUserInfo((int)$_POST['form']['head_user_id'], $data);
        }

        $this->showSuccessMessage("Данные отдела обновлены");

        $this->redirect("/import/dep/{$id}");
    }


    /**
     * Обновляет информацию о лаборатории
     */
    public function labUpdate()
    {
        $orgModel = new Organization();

        $id = (int)$_POST['lab_id'];

        $orgModel->setLabInfo($id, $_POST['form']);

        if ( !empty($_POST['form']['HEAD_ID']) ) {
            $data = ['lab_id' => $id];
            $orgModel->setAffiliationUserInfo((int)$_POST['form']['HEAD_ID'], $data);
        }

        $this->showSuccessMessage("Данные лаборатории обновлены");

        $this->redirect("/import/labProfile/{$id}");
    }


    /**
     * @desc добавляет/обновляет информацию об организации
     */
    public function orgInsertUpdate()
    {
        $orgModel = new Organization();

        if ( empty($_POST['org_id']) ) {
            $id = $orgModel->addOrgInfo($_POST['form']);
        } else {
            $id = (int)$_POST['org_id'];

            $orgModel->setOrgInfo($id, $_POST['form']);
        }

        if ( !empty($_POST['form']['head_user_id']) ) {
            $data = ['org_id' => $id];
            $orgModel->setAffiliationUserInfo((int)$_POST['form']['head_user_id'], $data);
        }

        $this->showSuccessMessage("Данные успешно добавлены/обновлены");

        $this->redirect("/import/organizationList/");
    }


    /**
     * @desc добавляет/обновляет информацию о департаменте
     */
    public function branchInsertUpdate()
    {
        $orgModel = new Organization();

        if ( empty($_POST['branch_id']) ) {
            $orgModel->addBranchInfo($_POST['form']);
        } else {
            $id = (int)$_POST['branch_id'];

            $orgModel->setBranchInfo($id, $_POST['form']);
        }

        $this->showSuccessMessage("Данные успешно добавлены/обновлены");

        $this->redirect("/import/organization/{$_POST['form']['organization_id']}");
    }


    /**
     * @desc Добавляет/обновляет информацию об отделе
     */
    public function depInsertUpdate()
    {
        $orgModel = new Organization();

        if ( empty($_POST['dep_id']) ) {
            $orgModel->addDepInfo($_POST['form']);
        } else {
            $id = (int)$_POST['dep_id'];

            $orgModel->setDepInfo($id, $_POST['form']);
        }

        $this->showSuccessMessage("Данные успешно добавлены/обновлены");

        $this->redirect("/import/branch/{$_POST['form']['branch_id']}");
    }


    /**
     * @desc Добавляет/обновляет информацию о лаборатории
     */
    public function labInsertUpdate()
    {
        $orgModel = new Organization();

        if ( empty($_POST['lab_id']) ) {
            $orgModel->addLabInfo($_POST['form']);
        } else {
            $id = (int)$_POST['lab_id'];

            $orgModel->setLabInfo($id, $_POST['form']);
        }

        $this->showSuccessMessage("Данные успешно добавлены/обновлены");

        $this->redirect("/import/dep/{$_POST['form']['dep_id']}");
    }


    /**
     * @desc добавляет/обновляет информацию о помещении
     */
    public function roomInsertUpdate()
    {
        $labModel = new Lab();

        if ( empty($_POST['room_id']) ) {
            $labModel->addRoom($_POST['form']);
        } else {
            $id = (int)$_POST['room_id'];

            $labModel->updateRoom($id, $_POST['form']);
        }

        $this->showSuccessMessage("Данные успешно добавлены/обновлены");

        $this->redirect("/import/labProfile/{$_POST['form']['LAB_ID']}");
    }


    /**
     * Добавляет связь пользователя к лабе
     */
    public function addAffiliationUser()
    {
        $userModel = new User();
        $labModel = new Lab();
        $orgModel = new Organization();

        $userId = (int)$_POST['user_id'];
        $labId = (int)$_POST['lab_id'];
        $status = (int)$_POST['status'];
        $replacementUserId = (isset($_POST['replace']) && $_POST['replace'] !== '') ? (int)$_POST['replace'] : null;

        $data = [
            'lab_id' => $labId,
            'status' => $status,
            'replacement_user_id' => $replacementUserId
        ];

        $lab = $labModel->get($labId);
        $orgModel->setAffiliationUserInfo($userId, $data);
        $userModel->updateUserDepartment($userId, $lab['id_dep']);

        $this->showSuccessMessage("Данные успешно добавлены/обновлены");

        $this->redirect("/import/labProfile/{$labId}");
    }


    /**
     * route /import/
     * Страница профиль лаборатории (страница в левом меню)
     */
    public function list()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var User $userModel */
        $userModel = $this->model('User');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');
        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');
        /** @var Company $companyModel */
        $companyModel = $this->model('Company');
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $this->data['title'] = 'Профиль лаборатории';

        // Сведенья о лаборатории
        $this->data['company_info'] = $companyModel->getCompanyInfo();
        // Отделы
        $this->data['labs'] = $labModel->getList();
        // Пользователи
        $users = array_filter($userModel->getUsers(), fn($user) => $user['ID'] != 1); // Игнорируем админа, которого нельзя удалить
        $this->data['users'] = $users;
        // Оборудования
        $this->data['oboruds'] = $oborudModel->getList();
        // ГОСТы
        $this->data['gosts'] = $gostModel->getUlabGostList();
        // Материалы
        $this->data['materials'] = $materialModel->getMaterialsKeyValue();

        $this->data['oborud_success'] = $_SESSION['import_message']['oborud_success'];
        $this->data['methods_success'] = $_SESSION['import_message']['methods_success'];
        $this->data['material_success'] = $_SESSION['import_message']['material_success'];

        $fsaController = $_SERVER["DOCUMENT_ROOT"] . '/ulab/modules/Fsa/controller/FsaController.php';
        $this->data['fsa_installed'] = file_exists($fsaController);

        unset($_SESSION['import_message']);



        $this->view('import');
    }

    /**
     * Страница внесения сведений о компании (лаборатории)
     * @order 2
     */
    public function companyEmployees()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        $this->data['title'] = 'Внесение сведений о руководстве';

        /** @var Company $companyModel */
        $companyModel = $this->model('Company');

        if (isset($_SESSION['company_post'])) {
            $this->data['form'] = $_SESSION['company_post'];
            unset($_SESSION['company_post']);
        } else {
            $this->data['form'] = $companyModel->getCompanyInfo(false);
        }

        $this->data['users'] = $this->model('User')->getUsers();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/modules/Import/assets/js/company_employees.js');

        $this->view('company_employees');
    }

    /**
     * Страница внесения сведений о компании (лаборатории)
     * @order 2
     */
    public function companyInfo()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Company $companyModel */
        $companyModel = $this->model('Company');

        $this->data['file'] ='logo.png';
        $this->data['title'] = 'Внесение сведений о компании (лаборатории)';
        $this->data['is_file_exists'] = file_exists(UPLOAD_DIR . 'import/' . $this->data['file']);

        if (isset($_SESSION['company_post'])) {
            $this->data['form'] = $_SESSION['company_post'];
            unset($_SESSION['company_post']);
        } else {
            $this->data['form'] = $companyModel->getCompanyInfo();
        }

        $this->data['users'] = $this->model('User')->getUsers();

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/modules/Import/assets/js/company_info.js');

        $this->view('company_info');
    }

    /**
     * Страница отделов
     */
    public function lab()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var User $userModel */
        $userModel = $this->model('User');
        /** @var User $permissionModel */
        $permissionModel = $this->model('Permission');

        $this->data['title'] = 'Внесение сведений об отделах (подразделениях)';

        $deptList = $userModel->getDepartmentsList();
        $deptHead = array_filter($deptList, function($row) {
            return $row['ID_HEAD_USER'] !== null;
        });
        $deptHeadList = array_values($deptHead);
        $this->data['dept_head'] = $deptHeadList;
        $this->data['users'] = $userModel->getUsers();
        $this->data['permissions'] = $permissionModel->getPermission();

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.colVis.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.print.min.js");
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/buttons.html5.min.js");
        $this->addJS("/assets/plugins/DataTables/JSZip-2.5.0/jszip.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/intl.js");

        $this->addJs("/assets/js/import/lab.js?v=" . rand());

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addCSS('/assets/plugins/jquery-multi-select/css/multi-select.css');
        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/js/import/lab-form.js');

        $this->view('lab');
    }

    /**
     * @desc Страница внесения сведений об отделах и помещениях
     * @param $id - id лаборатории
     */

    public function rooms($id = null)
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        $oborudModel = $this->model('Oborud');

        $result = $this->checkAccessAndRedirect($id);
        $dataUser = $result['dataUser'];

        $labId = empty($id) ? (int)$dataUser['lab_id'] : (int)$id;

        $this->data['title'] = 'Справочник помещений';
        $this->data['lab_id'] = $labId;

        $this->data['labs'] = $labModel->getList();

        if (isset($_SESSION['lab_post'])) {
            $this->data['form_lab'] = $_SESSION['lab_post'];
            unset($_SESSION['lab_post']);
        } elseif (isset($_SESSION['room_post'])) {
            $this->data['form_room'] = $_SESSION['room_post'];
            unset($_SESSION['room_post']);
        } else {
            $this->data['form_lab'] = $labModel->getLab($labId);
            $this->data['form_room'] = $labModel->getRoomByLabId($labId);
        }

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/js/select2.min.js');

        $this->addJs('/assets/js/import/rooms.js');

        $this->view('rooms', '', 'template_journal');
    }

    /**
     * Добавление или обновление отдела
     */

    /**
     * Страница импорта оборудования
     */
    public function oborud()
    {
        require_once __DIR__ . '/templates/import/oborud.php';

        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $this->data['title'] = 'Импорт оборудования';

        $this->data['template'] = URI . '/modules/Import/assets/templates/xlsx/oborud.xlsx';
        $this->data['file'] = URI . '/modules/Import/upload/oborud.csv';

        $this->data['is_file_exists'] = file_exists(self::MODULE_UPLOAD_DIR . "oborud.csv");

        $oborud = (new ImportOborudController())->readOrWriteDataFromCsv(false);

        if (!$oborud['success']) {
            $this->showErrorMessage($oborud['error']['message']);
        }

        $this->data['oborud'] = !empty($oborud) ? $oborud : [];

        $this->addJs('/modules/Import/assets/js/oborud.js');

        $this->view('oborud');
    }

    /**
     * Страница привязки оборудования к помещениям
     */
    public function oborudToRoom()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');

        $this->data['title'] = 'Привязка оборудования к помещениям';

        if (isset($_SESSION['room_id'])) {
            $this->data['room_id'] = $_SESSION['room_id'];
            unset($_SESSION['room_id']);
        }

        $this->data['rooms'] = $labModel->getRooms();
        $this->data['oboruds'] = $oborudModel->getList();

        $this->addCSS('/assets/plugins/jquery-multi-select/css/multi-select.css');
        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');

        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.multi-select.js');
        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.quicksearch.js');
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/modules/Import/assets/js/oborud-to-room.js?v=' . rand());

        $this->view('oborud_to_room');
    }

    /**
     * Страница импорта методик
     */
    public function methods()
    {
        require_once __DIR__ . '/templates/import/methods.php';

        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $this->data['title'] = 'Импорт областей аккредитации и методик';

        $this->data['template'] = URI . '/modules/Import/assets/templates/xlsx/methods.xlsx';
        $this->data['file'] = URI . '/modules/Import/upload/methods.csv';

        $this->data['is_file_exists'] = file_exists(self::MODULE_UPLOAD_DIR . "methods.csv");

        $methods = (new ImportMethodsController())->readOrWriteDataFromCsv(false);

        if (!$methods['success']) {
            $this->showErrorMessage($methods['error']['message']);
        }

        $this->data['methods'] = !empty($methods) ? $methods : [];

        $this->addJs('/modules/Import/assets/js/methods.js');

        $this->view('methods');
    }

    /**
     * Страница привязки оборудования к методикам
     */
    public function oborudToMethod()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');
        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $this->data['title'] = 'Привязка оборудования к методикам';
        $this->data['methods'] = [];


        if (isset($_SESSION['gost_id'])) {
            $this->data['gost_id'] = $_SESSION['gost_id'];
            $this->data['methods'] = $methodsModel->getListByGostId($_SESSION['gost_id']);
            unset($_SESSION['gost_id']);
        }

        if (isset($_SESSION['method_id'])) {
            $this->data['method_id'] = $_SESSION['method_id'];
            unset($_SESSION['method_id']);
        }

        $this->data['gosts'] = $gostModel->getUlabGostList();
        $this->data['oboruds'] = $oborudModel->getList();

        $this->addCSS('/assets/plugins/jquery-multi-select/css/multi-select.css');
        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.multi-select.js');
        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.quicksearch.js');
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addJs('/modules/Import/assets/js/oborud-to-method.js?v=' . rand());

        $this->view('oborud_to_method');
    }

    /**
     * Страница привязки лаборатории и сотрудников
     */
    public function labUserToMethod()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Gost $gostModel */
        $gostModel = $this->model('Gost');
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        /** @var User $userModel */
        $userModel = $this->model('User');

        $this->data['title'] = 'Привязка отделов и сотрудников к методикам';

        $this->data['methods'] = [];

        if (!empty($_SESSION['gost_id'])) {
            $this->data['gost_id'] = $_SESSION['gost_id'];
            $this->data['methods'] = $methodsModel->getListByGostId($_SESSION['gost_id']);
            unset($_SESSION['gost_id']);
        }

        if (!empty($_SESSION['method_id'])) {
            $this->data['method_id'] = $_SESSION['method_id'];

            $this->data['lab'] = $methodsModel->getLab($_SESSION['method_id']);
            $this->data['room'] = $methodsModel->getRoom($_SESSION['method_id']);
            $this->data['assigned'] = $methodsModel->getAssigned($_SESSION['method_id']);

            unset($_SESSION['method_id']);
        }

        $this->data['gosts'] = $gostModel->getUlabGostList();
        $this->data['room_list'] = $labModel->getRooms();
        $this->data['lab_list'] = $labModel->getList();
        $this->data['assigned_list'] = $userModel->getAssignedUserListByLab($this->data['lab']);


        $this->addCSS('/assets/plugins/jquery-multi-select/css/multi-select.css');
        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.multi-select.js');
        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.quicksearch.js');
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs('/modules/Import/assets/js/lab-user-to-method.js');

        $this->view('lab_user_to_method');
    }

    /**
     * @desc Страница внесения сведений о сотрудниках
     * @hide true
     */
    public function user()
    {
        $this->data['title'] = 'Внесения сведений о сотрудниках';
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

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs("/modules/Import/assets/js/user.js");

        $this->showErrorMessage("Внимание! Текущая версия страницы устарела. Пожалуйста, перейдите на  
            <a href='/ulab/permission/users/' title='Пользователи'>обновленную версию</a> для актуальной информации.");

        $this->view('user');
    }

    /**
     * Страница привязки сотрудников к подразделениям
     */
    public function department()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var User $companyModel */
        $userModel = $this->model('User');

        $this->data['title'] = 'Привязка сотрудников к подразделениям';

        $this->data['users'] = $userModel->getUsers();
        $this->data['departments'] = Department::getList();

        $this->data['is_may_change'] = in_array(self::WORKING_WITH_DEPARTMENTS, App::getUserGroupIds());

        $this->addCSS('/assets/plugins/jquery-multi-select/css/multi-select.css');
        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.multi-select.js');
        $this->addJs('/assets/plugins/jquery-multi-select/js/jquery.quicksearch.js');

        $this->addCSS("/assets/plugins/fuelux/css/tree-style.css");
        $this->addJs('/assets/plugins/fuelux/js/tree.min.js');

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs("/modules/Import/assets/js/department.js?v=".rand());

        $this->view('department');
    }

    /**
     * Страница импорта объектов испытаний
     */
    public function material()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $this->data['title'] = 'Импорт объектов испытаний';

        $this->data['template'] = URI . '/modules/Import/assets/templates/csv/material.csv';
        $this->data['file'] = URI . '/modules/Import/upload/material.csv';

        $this->data['is_file_exists'] = file_exists(self::MODULE_UPLOAD_DIR . "material.csv");

        $material = $importModel->getCsvData('material', ';', 5, 1);

        if (!$material['success']) {
            $this->showErrorMessage($material['error']['message']);
        }

        $this->data['material'] = !empty($material['data']) ? $material['data'] : [];

        $this->addJs('/modules/Import/assets/js/material.js');

        $this->view('material');
    }

    /**
     * Страница внесения сведений о провайдере электронной почты
     */
    public function mail()
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Company $companyModel */
        $companyModel = $this->model('Company');

        //$crypter = new Crypter(ENCRYPTION_KEY);

        $this->data['file'] ='logo.png';
        $this->data['title'] = 'Внесения сведений о провайдере электронной почты';

        $this->data['is_file_exists'] = file_exists(UPLOAD_DIR . "import/mail/{$this->data['file']}");

        if (isset($_SESSION['mail_post'])) {
            $this->data['form'] = $_SESSION['mail_post'];
            unset($_SESSION['mail_post']);
        } else {
            $this->data['form'] = $companyModel->getMail();
        }

        //$this->data['form']['smtp_password'] = $crypter->decrypt($this->data['form']['smtp_password']);
        $this->data['form']['smtp_password'] = $this->data['form']['smtp_password'];


        $this->addCSS('/assets/plugins/magnific-popup/magnific-popup.css');

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJs('/modules/Import/assets/js/mail.js');

        $this->view('mail');
    }

    /**
     * Страница онбординга
     * @param $onboardingId
     */
    public function onboarding($onboardingId = null)
    {
        $this->model('Permission')->checkPermission(__FUNCTION__, __FILE__);

        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $this->data['title'] = 'Лабораторная база знаний';
        $this->data['onboarding'] = [];

        $this->data['onboardings'] = $importModel->getOnboardings();
        if (!empty($onboardingId)) {
            $this->data['onboarding'] = $importModel->getOnboardingById($onboardingId);
        }

        $this->data['is_may_change'] = in_array(App::getUserId(), self::USERS_EDIT_ONBOARDING);
        $this->data['name'] = empty($this->data['onboarding']) ? 'Добавление нового раздела' : 'Обновление раздела';

        $this->addJs('/assets/plugins/tinymce/tinymce.min.js');
        $this->addJs('/modules/Import/assets/js/onboarding.js?v=' . rand());

        $this->view('onboarding');
    }

    /**
     * загрузить CSV версию
     * @param $name - наименование директории и файла
     * @hide true
     */
    public function uploadCsv($name)
    {
        /** @var File $fileModel */
        $fileModel = $this->model('File');

        $location = "/import/{$name}/";
        $fileName = $name . '.csv';

        if (empty($name)) {
            $this->showErrorMessage('Не указан или указан не верно параметр загружаемого фала');
            $this->redirect($location);
        }

        $saveCsvFile = $fileModel->saveCsvFile(self::MODULE_UPLOAD_DIR, $_FILES['upload_csv'], $fileName);

        if (!empty($saveCsvFile['success'])) {
            $this->showSuccessMessage("CSV файл успешно загружен");
        }

        if (!empty($saveCsvFile['error'])) {
            $this->showErrorMessage($saveCsvFile['error']['message']);
        }

        $this->redirect($location);
    }

    /**
     * CSV файл
     * @param $name - наименование директории и файла
     * @hide true
     */
    public function deleteCsv($name)
    {
        $location = "/import/{$name}/";
        $fileName = $name . '.csv';

        if (empty($name)) {
            $this->showErrorMessage('Не указан или указан не верно параметр удаляемого фала');
            $this->redirect($location);
        }

        if (!unlink(self::MODULE_UPLOAD_DIR . $fileName)) {
            $this->showErrorMessage("Файл {$fileName} не может быть удален из-за ошибки");
            $this->redirect($location);
        } else {
            $this->showSuccessMessage("Файл {$fileName} успешно удален");
            $this->redirect($location);
        }
    }

    /**
     * импортировать CSV данные
     * @param $name - наименование директории и файла
     * @hide true
     */
    public function importCsv($name)
    {

        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $location = "/import/{$name}/";
        $successMsg = 'Данные успешно импортированы';

        if (empty($name)) {
            $this->showErrorMessage('Не указан или указан не верно параметр импортируемого фала');
            $this->redirect($location);
        }

        if ($name == 'oborud') {
            require_once __DIR__ . "/templates/import/{$name}.php";
            $result = (new ImportOborudController())->readOrWriteDataFromCsv(true);
        } else if ($name == 'methods') {
            require_once __DIR__ . "/templates/import/{$name}.php";
            $result = (new ImportMethodsController())->readOrWriteDataFromCsv(true);
        } else {
            $result = $importModel->importCsvData($name);
        }

        if ($result === TRUE) {
            $_SESSION["import_message"]["{$name}_success"] = $successMsg;
            unlink(self::MODULE_UPLOAD_DIR . "{$name}.csv");
            $this->redirect("/import/");
        } else {
            $this->showErrorMessage('Не удалось импортировать данные');
            $this->redirect($location);
        }
    }

    /**
     * Создание изменение информации о компании (лаборатории)
     * @hide true
     */
    public function insertUpdateInfo()
    {
        /** @var Company $companyModel */
        $companyModel = $this->model('Company');
        /** @var File $fileModel */

        $fileModel = $this->model('File');

        $location = '/import/companyInfo/';
        $successMsg = !empty($_POST['id']) ? 'Сведения о компании (лаборатории) успешно изменены' : 'Сведения о компании (лаборатории) успешно сохранены';

        $_SESSION['company_post'] = $_POST['form'];


        // Компания(лаборатория) *
        $valid = $this->validateField($_POST['form']['title'], "Компания(лаборатория)");
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Полное наименование компании
        $valid = $this->validateField($_POST['form']['company_full_name'], "Полное наименование компании", false, 1000);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ИНН
        if (strlen($_POST['form']['inn']) !== 0 && strlen($_POST['form']['inn']) !== 10 && strlen($_POST['form']['inn']) !== 12) {
            $l = strlen($_POST['form']['inn']);
            $this->showErrorMessage("В поле ИНН введено {$l} символов. Должно быть 10 или 12");
            $this->redirect($location);
        }
        $valid = $this->validateNumber($_POST['form']['inn'], "ИНН", false, 12);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }



        if (!isset($_POST['form']['ip'])) {           //Если выключен ИП, то проверяем ОГРН и КПП
            // ОГРН
            $valid = $this->validateNumber($_POST['form']['ogrn'], "ОГРН", false, 13);
            if ( !$valid['success'] ) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }

            // КПП
            $valid = $this->validateNumber($_POST['form']['kpp'], "КПП", false, 9);
            if ( !$valid['success'] ) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }
        } else {                                    //Если ИП включен, то проверяем ОГРНИП
            // ОГРНИП
            $valid = $this->validateNumber($_POST['form']['ogrnip'], "ОГРНИП", false, 15);
            if ( !$valid['success'] ) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }
        }

        // Адрес
        $valid = $this->validateField($_POST['form']['addr'], "Адрес", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Фактический адрес
        $valid = $this->validateField($_POST['form']['actual_address'], "Фактический адрес", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Почтовый адрес
        $valid = $this->validateField($_POST['form']['mailing_address'], "Почтовый адрес", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // E-mail для договора
        $valid = $this->validateEmail($_POST['form']['email'], false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // E-mail *
        $valid = $this->validateEmail($_POST['form']['post_mail']);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Дополнительный E-mail
        foreach ($_POST['form']['add_email'] as $item) {
            $valid = $this->validateEmail($item, false);
            if (!$valid['success']) {
                $this->showErrorMessage($valid['error']);
                $this->redirect($location);
            }
        }

        // Телефон *
        $valid = $this->validateField($_POST['form']['phone'], "Телефон");
        if (!$valid['success']) {
            $this->showErrorMessage($valid['form']['error']);
            $this->redirect($location);
        }

        // Контактное лицо
        $valid = $this->validateField($_POST['form']['contact_person'], "Контактное лицо", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Расчетный счет
        $valid = $this->validateField($_POST['form']['bank_account'], "Расчетный счет", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Кор. счёт
        $valid = $this->validateField($_POST['form']['correspondent_account'], "Кор. счёт", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Лицевой счёт
        $valid = $this->validateField($_POST['form']['personal_account'], "Лицевой счёт", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // БИК
        $valid = $this->validateField($_POST['form']['bik'], "БИК", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Наименование банка
        $valid = $this->validateField($_POST['form']['bank_name'], "Наименование банка", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        if (!empty($_POST['id'])) { // редактирование
            $result = $companyModel->updateCompanyInfo((int)$_POST['id'], $_POST['form']);
        } else { // создание
            $result = $companyModel->addCompanyInfo($_POST['form']);
        }

        if (empty($result)) {
            $this->showErrorMessage("Сведения о лаборатории не удалось сохранить");
        } else {
            unset($_SESSION['company_post']);
            $this->showSuccessMessage($successMsg);
        }

        $this->redirect($location);
    }

    /**
     * Создание изменение информации о важных сотрудниках
     * @hide true
     */
    public function insertUpdateEmployees()
    {
        /** @var Company $companyModel */
        $companyModel = $this->model('Company');
        /** @var File $fileModel */

        $location = '/import/list/';
        $successMsg = !empty($_POST['id']) ? 'Сведения о главных сотрудниках успешно изменены' : 'Сведения о главных сотрудниках успешно сохранены';

        $_SESSION['company_post'] = $_POST['form'];

        // Должность руководителя
        $valid = $this->validateField($_POST['form']['position'], "Должность руководителя", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Должность руководителя в родительном падеже
        $valid = $this->validateField($_POST['form']['position_genitive'], "Должность руководителя в родительном падеже", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // ФИО руководителя
        $valid = $this->validateField($_POST['form']['director'], "ФИО руководителя", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        if (!empty($_POST['id'])) { // редактирование
            $result = $companyModel->updateCompanyInfo((int)$_POST['id'], $_POST['form']);
        } else { // создание
            $result = $companyModel->addCompanyInfo($_POST['form']);
        }

        if (empty($result)) {
            $this->showErrorMessage("Сведения о главных сотрудниках не удалось сохранить");
        } else {
            unset($_SESSION['company_post']);
            $this->showSuccessMessage($successMsg);
        }

        $this->redirect($location);
    }

    /**
     * Создание изменение помещений
     * @param $roomId
     * @hide true
     */
    public function insertUpdateRoom($roomId)
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');
        $oborudModel = $this->model('Oborud');

        $successMsg = !empty($_POST['form_room']['room_id']) ? 'Помещение успешно изменено' : 'Помещение успешно сохранено';

        $_SESSION['room_post'] = $_POST;
        if (!empty($_POST['form_room']['room_id'])) {
            $result = $labModel->updateRoom((int)$_POST['form_room']['room_id'], $_POST['form_room']);
            $oborudModel->updateOborudsToStorageRoom($_POST['form_room']['equipment_storaged'], (int)$_POST['form_room']['room_id']);
            $oborudModel->updateOborudsToOperatingRoom([], (int)$_POST['form_room']['room_id']);
        } else {
            $result = $labModel->addRoom($_POST['form_room']);
            $labModel->assignRoomToLab((int)$result, (int)$_POST['form_room']['LAB_ID']);
            $oborudModel->updateOborudsToStorageRoom($_POST['form_room']['equipment_storaged'] ? $_POST['form_room']['equipment_storaged'] : [], (int)$result);
            $oborudModel->updateOborudsToOperatingRoom([], (int)$result);
        }

        if (empty($result)) {
            echo json_encode(['success' => false, 'error' => 'Помещение не удалось сохранить']);
        } else {
            echo json_encode(['success' => true, 'message' => $successMsg]);
            unset($_SESSION['room_post']);
        }
    }

    /**
     * Создание изменение отделение
     * @param $deptid
     * @hide true
     */
    public function insertUpdateDepartment($deptid)
    {
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $location = "/import/lab";
        $successMsg = !empty($_POST['form_dept']['ID']) ? 'Отделение успешно изменено' : 'Отделение успешно сохранено';

        $_SESSION['dept_post'] = $_POST['form_dept'];

        if (!empty($_POST['form_dept']['ID'])) {
            $labModel->updateDept($_POST['form_dept']['ID'], $_SESSION['dept_post']);
        } else {
            $bitrixId = $labModel->createDeptBitrix($_POST['form_dept']['NAME']);
            $ulabId = $labModel->addDept($_POST['form_dept']);
            $labModel->connectionBitrixAndUlab($ulabId, $bitrixId);
        }


        $this->showSuccessMessage($successMsg);
        unset($_SESSION['dept_post']);
        $this->redirect($location);

    }

    /**
     * Создание изменение привязки оборудования к методике
     * @hide true
     */
    public function addLabUserToMethod()
    {
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $location = "/import/labUserToMethod/";

        $data = $_POST['form'];

        $methodsModel->updateLab($_POST['method'], $data['lab']);
        $methodsModel->updateRoom($_POST['method'], $data['room']);
        $methodsModel->updateAssigned($_POST['method'], $data['assigned']);

        $_SESSION['gost_id'] = $_POST["gost"];
        $_SESSION['method_id'] = $_POST["method"];

        $this->showSuccessMessage("Данные сохранены");

        $this->redirect($location);
    }

    /**
     * Создание изменение сведений о сотрудниках
     * @hide true
     */
    public function insertUpdateUser()
    {
        /** @var User $userModel */
        $userModel = $this->model('User');
        /** @var Organization $organizationModel */
        $organizationModel = $this->model('Organization');

        $location = '/user/list/';
        $successMsg = !empty($_POST['user_id']) ? 'Сведения о сотруднике успешно изменены' : 'Сведения о сотруднике успешно сохранены';

        $_SESSION['user_post'] = $_POST;

        // Имя
        $valid = $this->validateField($_POST['NAME'], "Имя", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Фамилия
        $valid = $this->validateField($_POST['LAST_NAME'], "Фамилия", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Отчество
        $valid = $this->validateField($_POST['LAST_NAME'], "Отчество", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Должность
        $valid = $this->validateField($_POST['WORK_POSITION'], "Должность", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // E-mail *
        $valid = $this->validateEmail($_POST['EMAIL']);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Логин *
        $valid = $this->validateField($_POST['LOGIN'], "Логин");
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }


        // Если редактирование пользователя, то не обязательно вводить пароль
        $isRequiredPass = !empty($_POST['user_id']) ? false : true;
        // Новый пароль *
        $valid = $this->validateField($_POST['NEW_PASSWORD'], "Новый пароль", $isRequiredPass);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Подтверждение нового пароля *
        $valid = $this->validateField($_POST['NEW_PASSWORD'], "Подтверждение нового пароля", $isRequiredPass);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }


        if (!empty($_POST['user_id'])) { // редактирование
            $result = $userModel->updateUser((int)$_POST['user_id'], $_POST);

            $userId = (int)$_POST['user_id'];
            $_SESSION['user_id'] = $userId;
        } else { // создание
            $result = $userModel->insertUser($_POST);
            $userId = (int)$result['data'];
            $_SESSION['user_id'] = $userId;
        }

        $affiliationData = ['bitrix_department_id' => (int)$_POST['DEPARTMENT_ID']];
        $organizationModel->setAffiliationUserInfo($userId, $affiliationData);
        $userModel->updateUserDepartment($userId, (int)$_POST['DEPARTMENT_ID']);

        if (empty($result['success'])) {
            $this->showErrorMessage($result['error']['message']);
        } else {
            unset($_SESSION['user_post']);
            $this->showSuccessMessage($successMsg);
        }

        $this->redirect($location);
    }

    /**
     * Создание изменение разделов онбординга
     * @hide true
     */
    public function insertUpdateOnboarding()
    {
        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $location = !empty($_POST['id']) ? "/import/onboarding/{$_POST['id']}" : "/import/onboarding/";
        $successMsg = !empty($_POST['id']) ? 'Данные раздела успешно изменены' : 'Данные раздела успешно сохранены';

        $_SESSION['onboarding_post'] = $_POST['form'];

        // Наименование раздела *
        $valid = $this->validateField($_POST['form']['title'], "Наименование раздела", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Ссылка на видео
        $valid = $this->validateField($_POST['form']['link_video'], "Ссылка на видео", false);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Описание
        $valid = $this->validateField($_POST['form']['description'], "Описание", false, 4200000000);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        if (!empty($_POST['id'])) { // редактирование
            $result = $importModel->updateOnboarding((int)$_POST['id'], $_POST['form']);
            $errorMsg = 'Не удалось обновить данные раздела';
        } else { // создание
            $result = $importModel->addOnboarding($_POST['form']);
            $errorMsg = 'Не удалось сохранить данные раздела';
        }

        if ( empty($result) ) {
            $this->showErrorMessage($errorMsg);
        } else {
            unset($_SESSION['onboarding_post']);
            $this->showSuccessMessage($successMsg);
        }

        $this->redirect($location);
    }




    /**
     * Создание изменение сведений о провайдере электронной почты
     * @hide true
     */
    public function insertUpdateMail()
    {
        /** @var Company $companyModel */
        $companyModel = $this->model('Company');
        /** @var File $fileModel */
        $fileModel = $this->model('File');

        //$crypter = new Crypter(ENCRYPTION_KEY);

        $location = '/import/mail/';
        $successMsg = !empty($_POST['id']) ?
            'Сведения о провайдере электронной почты успешно изменены' : 'Сведения о провайдере электронной почты успешно сохранены';

        $_SESSION['mail_post'] = $_POST['form'];


        // Адрес почтового сервера *
        $valid = $this->validateField($_POST['form']['smtp_host'], "Адрес почтового сервера");
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Порт *
        $valid = $this->validateNumber($_POST['form']['smtp_port'], "Порт");
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }

        // Использовать защищенное соединение
        $isSmtpCheckSecured = !empty($_POST['form']['smtp_check_secured']);
        $_POST['form']['smtp_secured'] = $_POST['form']['smtp_secured'] ?? '';
        // Защищенное соединение
        $valid = $this->validateField($_POST['form']['smtp_secured'], "Защищенное соединение", $isSmtpCheckSecured);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }


        // Использовать аутентификацию
        $isSmtpAuthentication = !empty($_POST['form']['smtp_authentication']);
        $_POST['form']['smtp_login'] = $_POST['form']['smtp_login'] ?? '';
        $_POST['form']['smtp_password'] = $_POST['form']['smtp_password'] ?? '';
        // Логин
        $valid = $this->validateField($_POST['form']['smtp_login'], "Логин", $isSmtpAuthentication);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }
        // Пароль
        $valid = $this->validateField($_POST['form']['smtp_password'], "Пароль", $isSmtpAuthentication);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }
        //$_POST['form']['smtp_password'] = $crypter->encrypt($_POST['form']['smtp_password']);
        $_POST['form']['smtp_password'] = $_POST['form']['smtp_password'];


        // Электронная почта
        $valid = $this->validateEmail($_POST['form']['email']);
        if (!$valid['success']) {
            $this->showErrorMessage($valid['error']);
            $this->redirect($location);
        }


        if (!empty($_POST['id'])) { // редактирование
            $result = $companyModel->updateMail((int)$_POST['id'], $_POST['form']);
        } else { // создание
            $result = $companyModel->addMail($_POST['form']);
        }

        if (empty($result)) {
            $this->showErrorMessage("Сведения о провайдере электронной почты не удалось сохранить");
        } else {
            unset($_SESSION['mail_post']);
            $this->showSuccessMessage($successMsg);
        }

        $this->redirect($location);
    }

    /**
     * загрузить PNG версию
     * @hide true
     */
    public function uploadPngAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var File $fileModel */
        $fileModel = $this->model('File');

        $fileName = 'logo.png';

        $response = $fileModel->savePNGFile(UPLOAD_DIR . "import", $_FILES['file'], $fileName);

        //TODO: Временно сохраняет PNG файл, для работы остальных скриптов до их рефакторинга
        //start
        //if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/DataTables/DataTables-1.10.18/images")) {
        //    mkdir($_SERVER['DOCUMENT_ROOT'] . "/DataTables/DataTables-1.10.18/images", 0766, true);
        //}

        copy(UPLOAD_DIR . "/import/" . $fileName, $_SERVER['DOCUMENT_ROOT'] . "/DataTables/DataTables-1.10.18/images/" . $fileName);
        //end

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Удаляет сохранённый файл с помощью Ajax-запроса
     * @return void
     * @hide true
     */
    public function deleteFileAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var File $fileModel */
        $fileModel = $this->model('File');

        $file = $_POST['file'] ?: '';
        $dir = UPLOAD_DIR . "/import/{$file}";

        $result = $fileModel->removeFile($dir, $file);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * загрузить логотип почты
     * @hide true
     */
    public function uploadMailLogoAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var File $fileModel */
        $fileModel = $this->model('File');

        $fileName = 'logo.png';

        $response = $fileModel->savePNGFile(UPLOAD_DIR . "import/mail", $_FILES['file'], $fileName);

        //TODO: Временно сохраняет PNG файл, для работы остальных скриптов до их рефакторинга
        //start
        //if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/DataTables/DataTables-1.10.18/images")) {
        //    mkdir($_SERVER['DOCUMENT_ROOT'] . "/DataTables/DataTables-1.10.18/images", 0766, true);
        //}

        //copy(UPLOAD_DIR . "/import/mail/" . $fileName, $_SERVER['DOCUMENT_ROOT'] . "/DataTables/DataTables-1.10.18/images/" . $fileName);
        //end

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Удаляет логотип
     * @return void
     * @hide true
     */
    public function deleteMailLogoAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var File $fileModel */
        $fileModel = $this->model('File');

        $file = $_POST['file'] ?: '';
        $dir = UPLOAD_DIR . "/import/mail/{$file}";

        $result = $fileModel->removeFile($dir, $file);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получение данных об отделах для журнала
     * @hide true
     */
    public function getLabJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $filter = $labModel->prepareFilter($_POST ?? []);

        $data = $labModel->getJournalList($filter);

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
     * полученние данных помещения по id
     * @hide true
     */
    public function getRoomAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $result = $labModel->getRoom((int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * Получает данные лаборатории с данными роли начальника лаборатории
     * @hide true
     */
    public function getLabAjax() {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $result = $labModel->getLab((int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * удалить данные помещения
     * @hide true
     */
    public function deleteRoomAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $response = [
            'success' => false,
            'error' => [
                'message' => "Не удалось удалить данные помещения, не указан или указан неверно ИД помещения",
            ]
        ];

        if (!empty($_POST['id']) && $_POST['id'] > 0) {
            $labModel->deleteRoom((int)$_POST['id']);

            $this->showSuccessMessage("Данные помещения удалены");

            $response = [
                'success' => true
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * удалить данные лаборатории
     * @hide true
     */
    public function deleteLabAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $response = [
            'success' => false,
            'error' => [
                'message' => "Не удалось удалить данные помещения, не указан или указан неверно ИД помещения",
            ]
        ];

        if (!empty($_POST['id']) && $_POST['id'] > 0 && $_POST['id'] != 53) {
            $labModel->deleteLab((int)$_POST['id']);

            $this->showSuccessMessage("Данные отделения удалены");

            $response = [
                'success' => true
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * привязка оборудования к помещению
     * @hide true
     */
    public function oborudToRoomAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');


        $response = [
            'success' => true
        ];

        if (!empty($_POST["room"]) && !empty($_POST["hidden_oborud"])) {
            $oborudIds = !empty($_POST["hidden_oborud"]) ? explode(',', $_POST["hidden_oborud"]) : [];

            $oborud = $oborudModel->getOborudByRoom((int)$_POST["room"]);
            $prevOborudIds = array_column($oborud, 'ID');
            $oborudDiff = array_diff($prevOborudIds, $oborudIds);


            //привязываем оборудование к помещению
            foreach ($oborudIds as $oborudId) {
                $dataItem = [
                    'id_room' => (int)$_POST["room"],
                ];

                $oborudModel->updateOborudToRooms((int)$oborudId, $dataItem);
            }

            //отвязываем оборудование от помещения
            foreach ($oborudDiff as $oborudId) {
                $dataItem = [
                    'id_room' => (int)$_POST["room"],
                ];

                $oborudModel->deleteOborudToRooms((int)$oborudId, $dataItem);
            }

            $_SESSION['room_id'] = (int)$_POST["room"];
        } else {
            $response = [
                'success' => false,
                'error' => [
                    'message' => "Не удалось привязать оборудование отсутствуют параметры помещения или оборудования",
                ]
            ];
        }

        if (!empty($response['success'])) {
            $this->showSuccessMessage("Оборудование успешно привязано к помещению");
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получение оборудования по помещению
     * @hide true
     */
    public function getOborudByRoomAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Oborud $oborudModel */
        $oborudModel = $this->model('Oborud');

        $result = $oborudModel->getOborudByRoom((int)$_POST['room']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * привязка оборудования к методике
     * @hide true
     */
    public function oborudToMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');


        $response = [
            'success' => true
        ];

        if (!empty($_POST["method"]) && !empty($_POST["hidden_oborud"])) {
            $oborudIds = !empty($_POST["hidden_oborud"]) ? explode(',', $_POST["hidden_oborud"]) : [];

            $dataOborud = [];
            foreach ($oborudIds as $oborudId) {
                $dataOborud[] = [
                    'id_oborud' => $oborudId,
                ];
            }

            $methodsModel->updateOborud((int)$_POST["method"], $dataOborud);

            $_SESSION['method_id'] = $_POST["method"];
            $_SESSION['gost_id'] = $_POST["gost"];
        } else {
            $response = [
                'success' => false,
                'error' => [
                    'message' => "Не удалось привязать оборудование отсутствуют параметры помещения или оборудования",
                ]
            ];
        }

        if (!empty($response['success'])) {
            $this->showSuccessMessage("Оборудование успешно привязано к помещению");
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получение методики по ГОСТу
     * @hide true
     */
    public function getMethodsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $result = $methodsModel->getListByGostId((int)$_POST['gost']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получение оборудования по методики
     * @hide true
     */
    public function getOborudByMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');

        $result = $methodsModel->getOborud((int)$_POST['method']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * Получаем свободное оборудование
     */
    public function getUnboundOborudAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $oborudModel = $this->model('Oborud');

        $result['equipment_storaged'] = $oborudModel->getOborudByStorageRoom();
        $result['equipment_operating'] = $oborudModel->getOborudByOperatingRoom();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * полученние данных пользователя
     * @hide true
     */
    public function getUserAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if ( !empty($_POST['user_id']) ) {
            /** @var User $userModel */
            $userModel = $this->model('User');
            $response = $userModel->getUserData((int)$_POST['user_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * удаление пользователя
     * @hide true
     */
    public function deleteUserAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $response = [
            'success' => false,
            'error' => [
                'message' => "Не удалось удалить пользователя",
            ]
        ];

        if (!empty($_POST['user_id']) && $_POST['user_id'] > 0) {
            $userModel->deleteUser((int)$_POST['user_id']);

            $this->showSuccessMessage("Пользователь удален");

            $response = [
                'success' => true
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получение данных пользователей для журнала
     * @hide true
     */
    public function getUserJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $filter = $userModel->prepareFilter($_POST ?? []);

        $data = $userModel->getJournalList($filter);

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
     * получить данные структуры подразделений
     * @hide true
     */
    public function getDepartmentsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $response = [];

        if (isset($_POST['id'])) {
            $response = $userModel->getDepartmentsStructure((int)$_POST['id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получить данные подразделения
     * @hide true
     */
    public function getDepartmentDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');

        $response = [];

        if (isset($_POST['department_id'])) {
            $response = $userModel->getDepartmentById((int)$_POST['department_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * полученние данных пользователей для подразделения
     * @hide true
     */
    public function getUsersByDeparmentAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $response = [];

        if ( !empty($_POST['department_id']) ) {
            /** @var User $userModel */
            $userModel = $this->model('User');
            $response = $userModel->getUsersByDeparment((int)$_POST['department_id']);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * привязка оборудования к помещению
     * @hide true
     */
    public function usersToDepartmentAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var User $userModel */
        $userModel = $this->model('User');


        $response = [
            'success' => true
        ];

        if (!empty($_POST["department_id"]) && !empty($_POST["users"])) {
            $usersIds = $_POST["users"];

            $users = $userModel->getUsersByDeparment((int)$_POST['department_id']);
            $prevUserIds = array_column($users['users'], 'ID');
            $usersDiff = array_diff($prevUserIds, $usersIds);


            // отвязываем пользователей от подразделения
            foreach ($usersDiff as $userId) {
                $dataItem = [
                    "UF_DEPARTMENT" => [],
                ];

                $userModel->update((int)$userId, $dataItem);
            }

            //привязываем пользователей к подразделению
            foreach ($usersIds as $userId) {

                $dataItem = [
                    "UF_DEPARTMENT" => [(int)$_POST["department_id"]],
                ];

                $userModel->update((int)$userId, $dataItem);
            }
        } else {
            $response = [
                'success' => false,
                'error' => [
                    'message' => "Не удалось привязать оборудование отсутствуют параметры помещения или оборудования",
                ]
            ];
        }

        if (!empty($response['success'])) {
            $this->showSuccessMessage("Оборудование успешно привязано к помещению");
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Создание/изменение лаборатории
     * @hide true
     */
    public function insertUpdateLabAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $lab = $labModel->getLabByDepId($_POST['id_dep']);

        if (!empty($lab)) {
            $result = $labModel->updateLabByDepId($_POST['id_dep'], $_POST);
        } else {
            $result = $labModel->addLab($_POST);
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * получение лабораторий и пользователей по методике
     * @hide true
     */
    public function getLabUserToMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var User $userModel */
        $userModel = $this->model('User');
        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $result['lab'] = [];
        $result['room'] = [];
        $result['assigned'] = [];

        if (!empty($_POST['method'])) {
            $result['lab'] = $methodsModel->getLab((int)$_POST['method']);
            $result['room'] = $methodsModel->getRoom((int)$_POST['method']);
            $result['assigned'] = $methodsModel->getAssigned((int)$_POST['method']);
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Отправление тестового письма
     * @hide true
     */
    public function sendTestEmailAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $result = [
            'success' => false,
            'message' => 'Не удалось отправить письмо, отсутствуют параметры почты',
        ];

        if (!empty($_POST['mail_id']) && !empty($_POST['email_to'])) {
            $mailModel = new Mail($_POST['mail_id']);
            $result = $mailModel->sendTestEmail($_POST['email_to']);

        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * удалить onboarding
     * @hide true
     */
    public function deleteOnboardingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $response = [
            'success' => false,
            'error' => [
                'message' => "Не удалось удалить данные, не указан или указан неверно ИД",
            ]
        ];

        if (!empty($_POST['id']) && $_POST['id'] > 0) {
            $importModel->deleteOnboarding((int)$_POST['id']);

            $this->showSuccessMessage("Данные удалены");

            $response = [
                'success' => true
            ];
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }


    /**
     * Скачивает файл с данными об областях аккредитации и методиках из системы
     * @hide true
     */
    public function exportMethods()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require_once __DIR__ . "/templates/export/methods.php";
        $result = (new ExportMethodsController())->exportToExcel();
    }


    /**
     * Скачивает файл с данными об оборудовании из системы
     * @hide true
     */
    public function exportOborud()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require_once __DIR__ . "/templates/export/oborud.php";
        $result = (new ExportOborudController())->exportToExcel();
    }


    /**
     * Импортирует данные ТНВЭД
     */
    public function importTnved()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require_once __DIR__ . "/templates/import/classifier_tnved.php";
        $result = (new ImportTnvedController())->importTnvedFromTxt();
    }


    /*public function importSoexXls()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require_once __DIR__ . "/templates/import/soex_oa.php";
        $result = (new ImportSoexOA())->ImportSoexOA();
    }*/

    /*public function importSoexClients()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require_once __DIR__ . "/templates/import/soex_clients.php";
        $result = (new ImportSoexClients())->ImportSoexClients();
    }*/


    /**
     * Получает список доступов
     * @return array
     */
    public function getPermission()
    {
        $sql = $this->DB->Query("SELECT * FROM `ulab_permission`");

        $result = [];

        while ($row = $sql->Fetch()) {
            $row['permission'] = json_decode($row['permission'], true);
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @desc Получает журнал организаций
     */
    public function getOrganizationJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $filter = $orgModel->prepareFilter($_POST ?? []);

        $data = $orgModel->getOrgJournal($filter);

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
     * @desc Получает журнал департамента
     */
    public function getBranchJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $filter = $orgModel->prepareFilter($_POST ?? []);

        $data = $orgModel->getBranchJournal((int) $_POST['id'], $filter);

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
     * @desc Получает журнал отделов
     */
    public function getDepJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $filter = $orgModel->prepareFilter($_POST ?? []);

        $data = $orgModel->getDepJournal((int) $_POST['id'], $filter);

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
     * @desc Получает журнал лабораторий
     */
    public function getLabNewJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $filter = $orgModel->prepareFilter($_POST ?? []);

        $data = $orgModel->getLabJournal((int) $_POST['id'], $filter);

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
     * @desc Получает журнал помещений для лабораторий
     */
    public function getLabRoomsJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $filter = $orgModel->prepareFilter($_POST ?? []);

        $data = $orgModel->getLabRoomsJournal((int) $_POST['id'], $filter);

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
     * @desc Получает журнал сотрудников лаборатории
     */
    public function getLabUsersJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $filter = $orgModel->prepareFilter($_POST ?? []);

        $data = $orgModel->getLabUsersJournal((int) $_POST['id'], $filter);

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
     * Удаление связи пользователя организации, департаменту, отделу или лаборатории
     */
    /**
     * Удаление связи пользователя организации, департаменту, отделу или лаборатории
     * @return void
     */
    public function deleteAffiliationUserAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $orgModel = new Organization();

        $userId = (int)$_POST['user_id'];

        echo $orgModel->deleteAffiliationUser($userId);
    }


    /**
     * Ссылка для скачивания формы №6 для помещений
     */
    public function dowloadForm(int $labId)
    {
        /** @var Import $importModel */
        $importModel = $this->model('Import');

        $GLOBALS['APPLICATION']->RestartBuffer();
        header("Content-Description: File Transfer");
        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=\"Форма №6.docx\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        $importModel->getForm($labId, $_GET['type']);
        exit();
    }
}
