<?php

/**
 * @desc Журнал автотранспорта
 * Class TransportController
 */
class TransportController extends Controller
{
    /**
     * @desc Журнал автотранспорта
     */
    public function list()
    {
        $this->data['title'] = 'Журнал автотранспорта';

        $this->addJs("/assets/js/transport-list.js?v=" . rand());

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Добавляет транспорт в журнал автотранспорта
     * route /protocol/results/
     */
    public function addTransportAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        $data = [
            "model" => $_POST["model"],
            "owner_name" => $_POST["owner_name"],
            "fuel_id" => $_POST["fuel_id"],
            "consumption_rate" => $_POST["consumption_rate"],
            "number" => $_POST["number"],
            "personal" => $_POST["personal"]
        ];

        if (empty($_POST["transport_id"])) {
            $transportId = $transport->create($data, 'transport');
        } else {
            $transportId = $transport->update($data, 'transport', (int)$_POST["transport_id"]);
        }

        $transportItem = $transport->getTransportById($transportId);
        echo json_encode($transportItem);


        //$transportItem = $transport->getVehicleById($transportId);

    }

    /**
     *  Удаляет транспорт из справочника транспорта
     */
    public function deleteAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        $table = 'transport';
        $deleteTransport = (int)$_POST["delete_transport"];

        if (isset($_POST['table'])) {
            $table = $transport->sanitize($_POST['table']);

            if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
                echo json_encode("Не удалось удалить. Имя таблицы содержит недопустимые символы.");
                return false;
            }
        }

        if (!empty($deleteTransport)) {
            $transport->update(["del" => 1], $table, $deleteTransport);
            echo json_encode("Успешно удалено");
            return;
        }
        echo json_encode("Не удалось удалить");
    }

    /**
     * @desc Получает данные для справочника транспорта
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Secondment $secondment */
        $transport = $this->model('Transport');

        $filter = $transport->prepareFilter($_POST ?? []);

        $data = $transport->getTransportListToJournal($filter);

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
     *  Справочник видов топлива
     * journal
     * route /transport/fuelList/
     */
    public function fuelList()
    {
        $this->data['title'] = 'Справочник топлива';

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

        $this->addCSS("/assets/css/fuel_list.css?v=" . rand());

        $this->addJs("/assets/js/fuel-list.js?v=" . rand());

        $this->view('fuelList');
    }

    /**
     *  Получает данные для справочника топлива
     */
    public function getFuelListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Secondment $secondment */
        $transport = $this->model('Transport');

        $filter = $transport->prepareFilter($_POST ?? []);

        $data = $transport->getFuelListToJournal($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);


        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Обновляет цены для справочника топлива
     */
    public function updateFuelAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        // Россия, Москва, Центральный административный округ
        $url = 'https://multigo.ru/benzin/55.7538479;37.595463571455895/11';
        $fuelArr = $transport->parseFuelPrice($url);

        foreach ($fuelArr as $index => $fuel) {
            $transport->update(["price" => $fuel], 'fuel_types', $index);
        }

        echo json_encode(true);
    }


    /**
     *  Справочник отчетов по бензину
     * journal
     * route /transport/reportList/
     */
    public function reportList()
    {
        $this->data['title'] = 'Отчет по бензину';

        $transport = $this->model("Transport");
        $userId = App::getUserId();


        $this->data["currentUser"] = $userId;
        $this->data["transportList"] = $transport->getTransportList();


        $this->data["userList"] = $user->getUsersByIdArr($userIdArr);

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


        $this->addJs("/assets/js/transport-report-list.js?v=" . rand());

        $this->view('reportList');
    }

    /**
     *  Получает данные для справочника отчетов по бензину
     */
    public function getReportListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Secondment $secondment */
        $transport = $this->model('Transport');

        $filter = $transport->prepareFilter($_POST ?? []);

        if (!empty($_POST['date_start'])) {
            $filter['search']['date_start'] = $transport->sanitize($_POST['date_start']);
        }
        if (!empty($_POST['date_end'])) {
            $filter['search']['date_end'] = $transport->sanitize($_POST['date_end']);
        }

        $data = $transport->getReposrtListToJournal($filter);

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
     *  Добавляет или обновляет отчёт по бензину
     */
    public function addReportAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        $dataReport = [
            "user_id" => $_POST["user_id"],
            "transport_id" => $_POST["transport_id"]
        ];

        if (empty($_POST["report_id"])) {
            $reportId = $transport->create($dataReport, 'transport_report');
        } else {
            $reportId = $transport->update($dataReport, 'transport_report', (int)$_POST["report_id"]);
        }

        if (isset($_POST["gsmData"])) {
            foreach ($_POST["gsmData"] as $row) {

                $rowData = [
                    "date" => $row["date"],
                    "time_start" => $row["time_start"],
                    "time_end" => $row["time_end"],
                    "km" => $row["km"],
                    "gsm" => $row["gsm"],
                    "price" => $row["price"],
                    "place" => $row["object"],
                    "report_id" => $reportId
                ];

                if (!isset($row["id"])) {
                    $transport->create($rowData, 'transport_report_row');
                } else {
                    $transport->update($rowData, 'transport_report_row', (int)$row["id"]);
                }
            }
         }

        echo json_encode($_POST);
    }

    /**
     *  Добавляет или обновляет запись в отчёте
     */
    public function addReportRowAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        $dataReport = [
            "date" => $_POST["date"],
            "time_start" => $_POST["time_start"],
            "time_end" => $_POST["time_end"],
            "km" => round($_POST["km"], 2),
            "gsm" => round($_POST["gsm"], 2),
            "price" => $_POST["price"],
            "place" => $_POST["place"],
            "report_id" => $_POST["report_id"]
        ];

        if (empty($_POST["report_row_id"])) {
            $reportId = $transport->create($dataReport, 'transport_report_row');
        } else {
            $reportId = $transport->update($dataReport, 'transport_report_row', (int)$_POST["report_row_id"]);
        }

        echo json_encode($_POST);
    }

    /**
     *  Таблица отчёта по бензину
     * @param int $id
     */
    public function reportTable(int $id)
    {
        $this->data['title'] = 'Отчет по бензину';

        $transport = $this->model("Transport");
        $user = $this->model("User");

        $userId = App::getUserId();
        $this->data["reportId"] = $id;
        $this->data["files"] = [];

        $fileCategories = ["memo", "report", "compensation"];

        foreach ($fileCategories as $category) {
            $name = $transport->getFilesFromDir($_SERVER["DOCUMENT_ROOT"] . "/ulab/upload/transport/{$category}/{$id}/")[0];

            if (!is_null($name)) {
                $this->data["files"][$category]["name"] = $name;
                $this->data["files"][$category]["href"] = "/ulab/upload/transport/{$category}/{$id}/{$name}";
            }
        }

//        echo "<pre>";
//        var_dump($this->data["files"]);
//        echo "</pre>";

        $this->data["mainData"] = $transport->getDataByReportId($id);


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
        $this->addJS("/assets/plugins/DataTables/sorting/date-de.js");
        $this->addJS("/assets/plugins/DataTables/sorting/natural.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/intl.js");
        $this->addJS("/assets/plugins/DataTables/FixedHeader-3.2.0/js/dataTables.fixedHeader.min.js");
        $this->addJS('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");

        $this->addCSS("/assets/css/style.css?v=" . rand());

        $this->addJs("/assets/js/transport-report-table.js?v=" . rand());

        $this->view('reportTable');
    }

    /**
     *  Получает данные для таблицы отчёта по бензину
     */
    public function getReportTableAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Secondment $secondment */
        $transport = $this->model('Transport');

        $id = (int)$_POST["report_id"];

        $jsonData["data"] = $transport->getReportTable($id);

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Получает данные для таблицы с чеками
     */
    public function getCheckTableAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Secondment $secondment */
        $transport = $this->model('Transport');

        $id = (int)$_POST["report_id"];

        $jsonData["data"] = $transport->getCheckTable($id);

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Добавляет или обновляет данные чека
     */
    public function addReportCheckAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        $dataReport = [
            "date" => $_POST["date"],
            "number" => $_POST["number"],
            "sum" => round($_POST["sum"], 2),
            "place" => $_POST["place"],
            "report_id" => $_POST["report_id"]
        ];

        if (empty($_POST["report_check_id"])) {
            $reportId = $transport->create($dataReport, 'transport_report_check');
        } else {
            $reportId = $transport->update($dataReport, 'transport_report_check', (int)$_POST["report_check_id"]);
        }

        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Выполняет мягкое удаление данных чеков
     */
    public function deleteReportCheckAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');

        $transport->update(["del" => 1], 'transport_report_check', (int)$_POST["report_check_id"]);

        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Формирует документ «Служебная записка»
     */
    public function generateMemoDocAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');
        $user = $this->model('User');

        $id = (int)$_POST["report_id"];

        $reportData =  $transport->getDataByReportId($id);
        $checkData =  $transport->getCheckTable($id);
        $userData = $user->getUserData($reportData['user_id']);

        $dateStart = $checkData[0]["date_str"];
        $dateEnd = $checkData[count($checkData) - 1]["date_str"];

        $monthStart = explode(".", $dateStart)[1];
        $monthEnd = explode(".", $dateEnd)[1];

        $data = [
            "id" => $id,
            "ending" => $userData["PERSONAL_GENDER"] == "F" ? "а" : "",
            "year" => explode(".", $dateStart)[2],
            "monthPeriod" => $monthStart == $monthEnd
                ? StringHelper::getMonthTitle($monthStart)
                : StringHelper::getMonthTitle($monthStart) . " - " . StringHelper::getMonthTitle($monthEnd),
            "dateStart" => $dateStart,
            "dateEnd" => $dateEnd,
            "transportText" => implode("<w:br/>", array_map(function($arr) {
                    return  "Кассовый чек с " . $arr["place"] . " " . $arr["number"] . " от " . $arr["date_str"] . " на сумму " . $arr["sum"] . " руб.";
                }, $checkData)),
            "transportModel" => $reportData["model"],
            "transportNumber" => $reportData["number"],
            "fio" => $userData["LAST_NAME"] . " " . substr($userData["NAME"], 0, 1) . ".",
            "genitiveLastname" => morphos\Russian\LastNamesInflection::getCase($userData["LAST_NAME"], 'родительный') . " " . substr($userData["NAME"], 0, 1) . ".",
            "todayTextDate" => date("d.m.Y"),
            "fullSumText" => StringHelper::setTextMoneyFormat(array_sum(array_map(function($arr) {
                    return $arr["sum"];
                }, $checkData)))
        ];

        $fileName = $transport->generateMemoDoc($data);

        echo json_encode(["id" => "{$id}", "file_name" => $fileName], JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Формирует документ «Отчёт» по бензину
     */
    public function generateReportDocAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');
        $user = $this->model('User');

        $id = (int)$_POST["report_id"];

        $reportData = $transport->getDataByReportId($id);
        $reportTable = $transport->getReportTable($id);
        $userData = $user->getUserData($reportData['user_id']);

        $fileName = "testName";
        $fio = $userData["LAST_NAME"] . " " . substr($userData["NAME"], 0, 1) . ".";

        $tableArr = [];
        $fullSum = 0;
        $gsmSum = 0;
        $kmSum = 0;

        $dateStart = $reportTable[0]["date"];
        $dateEnd = $reportTable[count($reportTable) - 1]["date"];
        $year = explode("-", $dateStart)[0];

        $monthStart = explode("-", $dateStart)[1];
        $monthEnd = explode("-", $dateEnd)[1];

        foreach ($reportTable as $i => $reportItem) {
            $tableArr[] = [
                "n" => $i + 1,
                "date" => $reportItem["date_str"],
                "start" => $reportItem["time_start"],
                "end" => $reportItem["time_end"],
                "km" => $reportItem["km"],
                "gsm" => $reportItem["gsm"],
                "price" => $reportItem["price"],
                "sum" => $reportItem["sum"],
                "object" => $reportItem["place"],
                "consumptionGsm" => $reportData["consumption_rate"],
            ];

            $fullSum += $reportItem["sum"];
            $gsmSum += $reportItem["gsm"];
            $kmSum += $reportItem["km"];
        }

        $data = [
            "id" => $id,
            "model" => $reportData["model"],
            "number" => $reportData["number"],
            "tableArr" => $tableArr,
            "todayDate" => date("d.m.Y"),
            "fio" => $fio,
            "kmSum" => round($kmSum, 2),
            "gsmSum" => round($gsmSum, 2),
            "fullSum" => round($fullSum, 2),
            "genitiveFio" => morphos\Russian\LastNamesInflection::getCase($userData["LAST_NAME"], 'родительный') . " " . substr($userData["NAME"], 0, 1) . ".",
            "monthPeriod" => $monthStart == $monthEnd
                ? StringHelper::getMonthTitle($monthStart)
                : StringHelper::getMonthTitle($monthStart) . " - " . StringHelper::getMonthTitle($monthEnd),
            "dateStart" => $dateStart,
            "year" => $year
        ];

        $fileName = $transport->generateReportDoc($data);

        echo json_encode(["id" => "{$id}", "file_name" => $fileName], JSON_UNESCAPED_UNICODE);
    }

    /**
     *  Формирует документ «Компенсация» по бензину
     */
    public function generateCompensationDocAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $transport = $this->model('Transport');
        $user = $this->model('User');

        $id = (int)$_POST["report_id"];

        $reportData = $transport->getDataByReportId($id);
        $reportTable = $transport->getReportTable($id);
        $userData = $user->getUserData($reportData['user_id']);

        $fio = $userData["LAST_NAME"] . " " . substr($userData["NAME"], 0, 1) . ".";

        $fullSum = 0;

        $dateStart = $reportTable[0]["date_str"];
        $dateEnd = $reportTable[count($reportTable) - 1]["date_str"];

        foreach ($reportTable as $reportItem) {
            $fullSum += $reportItem["sum"];
        }

        $data = [
            "id" => $id,
            "model" => $reportData["model"],
            "number" => $reportData["number"],
            "todayDate" => date("d.m.Y"),
            "fio" => $fio,
            "fullSum" => StringHelper::setTextMoneyFormat(round($fullSum / 2, 2)),
            "start" => $dateStart,
            "end" => $dateEnd
        ];

        $fileName = $transport->generateCompensationDoc($data);

        echo json_encode(["id" => "{$id}", "file_name" => $fileName], JSON_UNESCAPED_UNICODE);
    }
}