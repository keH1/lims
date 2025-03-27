<?php

/**
 * @desc Контроллер для создания шаблонов документа
 * Class DocTemplateController
 */
class DocTemplateController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Журнал шаблонов»
     * route /docTemplate/
     */
    public function index()
    {
        $this->redirect('/docTemplate/listTemplate/');
    }


    /**
     * @desc журнал шаблонов
     */
    public function listTemplate()
    {
        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        $this->data['title'] = 'Журнал шаблонов';

        $this->data['type_list'] = $docTemplateModel->getTemplateTypeList();


        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");

        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/ColReorder-1.5.5/js/dataTables.colReorder.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");

        $r = rand();
        $this->addJs("/assets/js/doc-template/list-template.js?v={$r}");

        $this->view('list_template');
    }


    /**
     * @desc журнал макросов
     */
    public function listMacros()
    {
        $this->data['title'] = 'Журнал макросов';

        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');


        $this->data['type_list'] = $docTemplateModel->getMacrosTypeList();


        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");

        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/ColReorder-1.5.5/js/dataTables.colReorder.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");

        $r = rand();
        $this->addJs("/assets/js/doc-template/list-macros.js?v={$r}");

        $this->view('list_macros');
    }


    /**
     * @desc добавление шаблона и загруженного файла
     */
    public function addTemplate()
    {
        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        // сохраним пост в сессию, что бы при ошибке не заполнять поля заново
        $_SESSION['request_post'] = $_POST;

        //// блок проверок

        ///  \блок проверок

        if ( empty($_POST['id']) ) {
            $result = $docTemplateModel->add($_POST['form'], $_FILES['file']);
        } else {
            $result = $docTemplateModel->update((int)$_POST['id'], $_POST['form'], $_FILES['file']);
        }

        if ( !$result['success'] ) {
            $this->showErrorMessage($result['error']);
        } else {
            $this->showSuccessMessage("Шаблон создан/обновлен");
        }

        $this->redirect("/docTemplate/listTemplate/");
    }


    /**
     * @desc удаление шаблона и загруженного файла
     * @param $id
     */
    public function deleteTemplate($id)
    {
        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        $docTemplateModel->deleteTemplate($id);

        $this->showSuccessMessage("Шаблон удален");

        $this->redirect("/docTemplate/listTemplate/");
    }


    /**
     * @desc страница создания документа для тестов
     */
    public function document()
    {
        $this->data['title'] = 'Создать документ';

        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        $this->data['template_list'] = $docTemplateModel->getList();

        $this->view('document');
    }


    /**
     * @desc получение данных для журнала шаблонов
     */
    public function getJournalTemplateAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        $filter = $docTemplateModel->prepareFilter($_POST ?? []);

        $data = $docTemplateModel->getDataJournalTemplate($filter);

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
     * @desc получение данных для журнала макросов
     */
    public function getJournalMacrosAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        $filter = $docTemplateModel->prepareFilter($_POST ?? []);

        $data = $docTemplateModel->getDataJournalMacros($filter);

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
     * @desc получение данных для формы редактирования
     */
    public function getAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  DocTemplate $docTemplateModel*/
        $docTemplateModel = $this->model('DocTemplate');

        echo json_encode($docTemplateModel->get( (int) $_POST['id']), JSON_UNESCAPED_UNICODE);
    }
}
