<?php

/**
 * Работа с материалами
 * Class ProbeController
 */
class MaterialController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }


    /**
     * route /material/list/
     * @desc Страница «Журнал материалов»
     */
    public function list()
    {
        $this->data['title'] = 'Журнал материалов';

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

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

        $r = rand();
        $this->addJs("/assets/js/material-list.js?v={$r}");

        $this->view('list');
    }

    /**
     * @desc Добавляет новый материал
     */
    public function add() {
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $validName = $this->validateField($_POST['name'], 'Название');
        if ( !$validName['success'] ) {
            $this->showErrorMessage($validName['error']);
            $this->redirect('/material/list/');
        }

        $materialModel->add($_POST['name']);

        $this->showSuccessMessage("Материал '{$_POST['name']}' успешно добавлен");
        $this->redirect('/material/list/');
    }


    /**
     * @desc Добавляет группу для материала
     */
    public function addGroup()
    {
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $groupId = $materialModel->addGroupMaterial((int)$_POST['material_id'], $_POST['name']);

        if ( empty($groupId) ) {
            $this->showErrorMessage("Не удалось добавить группу '{$_POST['name']}'");
        } else {
            $materialModel->updateTuGroupMaterial((int)$groupId, $_POST['group']['new']['tu']);

            $this->showSuccessMessage("Группа '{$_POST['name']}' добавлена");
        }

        $this->redirect("/material/card/{$_POST['material_id']}");
    }


    /**
     * @desc Обновляет группу для материала
     */
    public function updateGroups()
    {
        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $materialModel->updateGroupAndTu((int)$_POST['material_id'], $_POST['group']);

        $this->showSuccessMessage("Данные групп обновлены");

        $this->redirect("/material/card/{$_POST['material_id']}");
    }


    /**
     * @desc Карточка материала
     * @param $id
     */
    public function card( $id )
    {
        $matID = (int) $id;

        /** @var Material $materialModel */
        $materialModel = $this->model('Material');
        /** @var Scheme $schemeModel */
        /** @var Methods $methodsModel */
        $methodsModel = $this->model('Methods');
        /** @var TechCondition $tcModel */
        $tcModel = $this->model('TechCondition');
        /** @var NormDocGost $normDocGostModel */
        $normDocGostModel = $this->model('NormDocGost');

        $data = $materialModel->getById($matID);
        if (empty($data)) {
            $this->showErrorMessage("Материал с ИД {$id} не существует");
            $this->redirect('/material/list/');
        }

        $this->data['title'] = "Карточка материала";

        $this->data['id'] = $matID;

        $this->data['groups'] = $materialModel->getGroupMaterial($matID);

        $this->data['gost_to_material'] = $materialModel->getGostToMaterialByMatID($matID);

        $this->data['method_list'] = $methodsModel->getList();
        $this->data['condition_list'] = $normDocGostModel->getMethodList();
        $this->data['scheme'] = $materialModel->getSchemeByMaterial($matID);
        $this->data['name'] = $data['NAME'];

        $this->data['gost'] = $methodsModel->getList();

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/js/bootstrap.min.js');
        $this->addJs('/assets/js/bootstrap.bundle.min.js');
        $this->addJs('/assets/plugins/select2/js/select2.min.js');
        $this->addJs('/assets/js/material.js?v=2');

        $this->view('card');
    }


//    /**
//     * @param $ID
//     */
//    public function test( $ID )
//    {
//        $matID = (int) $ID;
//
//        /** @var Request $request */
//        $request = $this->model('Request');
//        /** @var User $user */
//        $user = $this->model('User');
//        /** @var Requirement $requirement */
//        $requirement = $this->model('Requirement');
//        /** @var Probe $probeModel */
//        $probeModel = $this->model('Probe');
//        /** @var Material $lab */
//        $material = $this->model('Material');
//        /** @var Gost $gost */
//        $gost = $this->model('Gost');
//
//        $this->data['title'] = "Карточка материала";
//
//        $this->data['id'] = $matID;
//        $this->data['scheme'] = $material->getSchemeByMaterId($ID);
//        $this->data['unit'] = $request->getUnit();
//
//        $data = $material->getMaterial($matID);
//
//        $gostToMaterial = $material->getGostToMaterialByMatID($matID);
//
//        $gostArr = $gost->getListForVeiw();
//
//        $matGroups = unserialize($data['GROUPS']);
//
//        $this->data['name'] = $data['NAME'];
//        $this->data['name_group'] = $data['GROUP_NAME'];
//        $this->data['groups'] = $matGroups;
//        $this->data['gost'] = $gostArr;
//        $this->data['gost_to_material'] = $gostToMaterial;
//
//
//        $this->data['test'] = $gostToMaterial;
//
//        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
//        $this->addCSS("/assets/plugins/select2/css/select2.min.css");
//        $this->addCSS("/assets/plugins/select2/css/select2-bootstrap-5-theme.min.css");
//
//        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
//        $this->addJs('/assets/js/bootstrap.min.js');
//        $this->addJs('/assets/js/bootstrap.bundle.min.js');
//        $this->addJs('/assets/plugins/select2/js/select2.min.js');
//        $this->addJs('/assets/js/material.js');
//
//        $this->view('test');
//    }


    /**
     * @desc Сохраняет или обновляет данные «Карточки материала»
     */
    public function insertUpdate() {
        /** @var Material $material */
        $material = $this->model('Material');

        $_SESSION['material_post'] = $_POST;

        $ID = (int)$_POST['id'];
        $successMsg = empty($_POST['id'])? 'Материал успешно создан' : "Материал успешно изменен";

        $dataGroup = [];
        $dataGost = [];
        $Groups = '';

        if (!empty($_POST['GROUP_VAL'])) {
            $arrGroup = array_diff($_POST['GROUP_VAL'], array('', NULL, false));
            $Groups = serialize($arrGroup);
        }
        $dataGost = $_POST['arrGost'];

        $dataGroup['GROUP_NAME'] = !empty($_POST['GROUP_NAME']) ? $_POST['GROUP_NAME'] : '';
        $dataGroup['GROUP_VAL'] = $Groups;

        if (!empty($_POST['NAME']) && empty($ID)) {
            $name = $_POST['NAME'];
            $ID = $material->setNewMaterial($name);
        }

        $location = "/material/card/{$ID}";

//        $material->setGroupMat($ID, $dataGroup);
        $material->setGostToMaterial($ID, $dataGost);

        $this->showSuccessMessage($successMsg);
        unset($_SESSION['request_post']);
        $this->redirect($location);
    }


    /**
     * @desc Получает данные для «Журнала материалов»
     */
    public function getListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $material*/
        $material = $this->model('Material');

        $filter = $material->prepareFilter($_POST ?? []);

        $data = $material->getDatatoJournalMaterial($filter);

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
     * @desc Удаляет материал
     */
    public function deleteMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $material*/
        $material = $this->model('Material');

        $material->deleteMaterial((int)$_POST['id']);
    }

    /**
     * @desc Обновляет данные материала
     */
    public function updateMaterialAjax()
    {

		global $APPLICATION;

		$APPLICATION->RestartBuffer();
        /** @var  Material $material*/
        $material = $this->model('Material');

        $id = $_POST['id'];
        $name = $_POST['name'];

        $successMsg = 'Материал изменен';

        $material->updateMaterial($name, $id);

        echo $name;
    }

    /**
     * @desc Получает данные схемы по id материала
     * @param $materialId
     * @return array
     */
    public function getSchemeByMaterialId($materialId)
    {
        /** @var  Material $material*/
        $material = $this->model('Material');

        return $material->getSchemeByMaterId($materialId);
    }

    /**
     * @desc Создаёт или обновляет схему материала
     */
    public function setSchemeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        /** @var  Material $material*/
        $material = $this->model('Material');
        $id = (int)$_POST['id_scheme'];
        $materialId = !empty($_POST['id_material']) ? $_POST['id_material'] : 0;

        $dataGost = $_POST['gosts'];

        $data = [
            'name' => $_POST['name'],
            'material_id' => $materialId,
        ];

        if (!empty($id)) {
            $material->updateScheme($id, $data);
            echo 'Схема успешно обновлена';
            $this->showSuccessMessage('Схема успешно обновлена');
        } else {
            $id = $material->addNewScheme($data);
            echo 'Схема успешно создана';
            $this->showSuccessMessage('Схема успешно создана');
        }

        $material->addGostToScheme($id, $dataGost);

        echo '1';
    }

    /**
     * @desc Удаляет схему у материала
     */
    public function deleteSchemeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        /** @var  Material $material*/
        $material = $this->model('Material');

        $id = $_POST['id_scheme'];

        $material->deleteScheme($id);
        $this->showSuccessMessage('Схема успешно удалена');

        echo '1';
    }

    /**
     * @deprecated
     * Получает данные группы материала [deprecated]
     */
    public function getGroupMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        /** @var  Material $material*/
        $material = $this->model('Material');

        $data = $material->getSieveAndNorm((int)$_POST['groupId']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет группу у материала
     */
    public function deleteGroupAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $materialModel->deleteGroup((int)$_POST['group_id']);

        echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные группы по id материала
     */
    public function getGroupByMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $data = $materialModel->getGroupMaterial((int)$_POST['material_id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные групп материалов из списка проб
     */
    public function getGroupByMaterialByUmtrIdAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');
		foreach ($_POST['probe_id_list'] as $umtrId) {
			$materialId = $materialModel->getMaterialByUmtrId((int)$umtrId);

			$data[$materialId] = $materialModel->getGroupMaterial((int)$materialId);
			$data[$materialId]['material_name'] = $materialModel->getById((int)$materialId)['NAME'];
		}

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные схемы по id материала
     */
    public function getSchemeByMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $data = $materialModel->getSchemeByMaterial((int)$_POST['material_id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает параметры схемы
     */
    public function getSchemeParamAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $data = $materialModel->getSchemeParam((int)$_POST['scheme_id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Меняет статус
     */
    public function changeActiveMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Material $materialModel */
        $materialModel = $this->model('Material');

        $materialModel->changeActiveMaterial((int) $_POST['id']);
    }


    /**
     * @desc получает схему
     */
    public function getNewSchemeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $data = $materialModel->getNewScheme((int)$_POST['id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc получает схему
     */
    public function getNewSchemeMethodsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $data = $materialModel->getNewSchemeMethods((int)$_POST['id']);

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc удаляет схему
     */
    public function deleteNewSchemeMethodsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $materialModel->deleteNewSchemeMethods((int)$_POST['id']);

        echo json_encode([], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Устанавливает новое имя материала
     */
    public function setNewNameAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var  Material $materialModel */
        $materialModel = $this->model('Material');

        $materialModel->setNewName((int)$_POST['id_material'], $_POST['name']);
    }
}