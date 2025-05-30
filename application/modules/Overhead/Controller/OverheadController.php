<?php

/**
 * @desc Накладные расходы
 * Class OverheadController
 */
class OverheadController extends Controller
{
    /**
     * @desc Журнал накладных расходов
     */
    public function journal()
    {
        $this->data['title'] = 'Журнал накладных расходов';

        $overhead = $this->model('Overhead');
        $project = $this->model('Project');

        $this->data['projects'] = array_merge(
            [
                ["id" => "", "name" => "Все проекты"],
                ["id" => 0, "name" => "Без проекта"]
            ],
            $project->getList()
        );

//        $this->data["result_stages"] = $contractor->getContractorResult();
//        $this->data["user_list"] = $contractor->getTgUserList();

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

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
        $this->addJS('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");

        $this->addCSS("/assets/css/style.css?v=" . rand());

        $this->addJs("/assets/js/overhead/journal.js?v=" . rand());

        $this->view('journal');
    }


    /**
     * @desc Получает данные для журнала накладных расходов
     */
    public function getJournalAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $overhead = $this->model('Overhead');

        $_POST['dateStart'] = $_POST['date_start'];
        $_POST['dateEnd'] = $_POST['date_end'];
        $filter = $overhead->prepareFilter($_POST ?? []);

        if (!empty($_POST['dateStart'])) {
            $filter['search']['date_start'] = $_POST['dateStart'];
        }

        if (!empty($_POST['dateEnd'])) {
            $filter['search']['date_end'] = $_POST['dateEnd'];
        }

        if ($_POST['project_id'] != "") {
            $filter['search']['project_id'] = (int)$_POST['project_id'];
        }

        $data = $overhead->getJournal($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "project_bg" => ProjectController::PROJECT_BG,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Добавляет или обновляет запись в журнале накладных расходов
     */
    public function insertUpdateAjax()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        $overhead = $this->model('Overhead');

        $data = [
            "project_id" => $_POST["project_id"],
            "sum" => $_POST["sum"],
            "date" => $_POST["date"],
        ];

        if (isset($_POST["id"]) && !empty($_POST["id"])) {
            $overhead->updateRow($data, (int)$_POST["id"]);
        } else {
            $overhead->insertRow($data);
        }

        // echo json_encode($id, JSON_UNESCAPED_UNICODE);
        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }
}