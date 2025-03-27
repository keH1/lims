<?php

/**
 * @desc Схемы
 * Class LabSchemeController
 */
class LabSchemeController extends Controller
{
    /**
     * @desc Редактор схем
     */
    public function editor()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Редактор схем");

        $version = "?v=" . rand();

        if ($_GET["type"] == 1) {
            $this->data["path"] = "/ulab/laboratory/passportJournal/";
            $this->data["manufacturer"] = "Опытный завод УРАЛНИИСТРОМ";
        } else {
            $this->data["path"] = "/ulab/laboratory/passportJournal/";
            $this->data["manufacturer"] = "";
        }

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/css/style-2.css" . $version);


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
        $this->addJS("/assets/plugins/modal/modalWindow.js");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
//
        $this->addJS("/assets/js/lab-scheme/editor.js" . $version);

        $this->view('editor');
    }


    /**
     * @desc Получает данные для редактора схем
     */
    public function getSchemeEditorDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $scheme = $this->model('LabScheme');

        $filter = $scheme->prepareFilter($_POST ?? []);

        $filter['search']['type'] = 0;

        if (!empty($_POST['type'])) {
            $filter['search']['type'] = (int)$_POST['type'];
        }

        $data = $scheme->getSchemeEditorData($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];
        $test = $data['test'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);
        unset($data['test']);


        //  $test = CCrmDeal::GetList();

        $jsonData = [
            "draw" => (int)$_POST["draw"],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
          //  "post" => $filter,
          //  "test" => $test
        ];


        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Добавляет материал в редактор схем
     */
    public function addMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $labScheme = $this->model('LabScheme');

        $materialId = "";

        $manufacturer = $_POST["manufacturer"];
        $type = $_POST["type"];

        if (isset($_POST["material_id"]) && !empty($_POST["material_id"])) {
            $materialId = (int)$_POST["material_id"];

            $materialName = $_POST["material_name"];

            $labScheme->update($materialId, $materialName);
            $labScheme->updateOz($materialId, $manufacturer);

        } elseif (isset($_POST["material_name"])) {
            $materialName = $_POST["material_name"];

            $materialId = $labScheme->add($materialName);

            $labScheme->addToOz($materialId, $manufacturer, $type);
        }

        // echo json_encode($materialId, JSON_UNESCAPED_UNICODE);
        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет материал из редактора схем
     */
    public function deleteMaterialAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $labScheme = $this->model('LabScheme');

        if (isset($_POST["material_id"])) {
            $labScheme->delete(intval($_POST["material_id"]));
        }

        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Добавляет схему в редактор схем
     */
    public function addAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $labScheme = $this->model('LabScheme');
        //  $test = $request->b24("crm.deal.get", ["id" => 8919])["result"];

        $schemeId = $labScheme->addScheme((int)$_POST["material_id"], $_POST["scheme_name"], $_POST["gost"]);
        $schemeName = $_POST["scheme_name"];

        //  echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
        echo json_encode(["id" => $schemeId, "name" => $schemeName], JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Карточка схемы
     * @param $id
     */
    public function card($id)
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Карточка схемы");
        $version = "?v=" . rand();

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/css/style-2.css" . $version);


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
        $this->addJS("/assets/plugins/modal/modalWindow.js");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");


        $this->addCSS("/assets/css/style-2.css" . $version);

        $this->addJS("/assets/js/lab-scheme/card.js" . $version);

        $labScheme = $this->model('LabScheme');
        $labGost = $this->model('LabGost');

        $schemeObject = $labScheme->getSchemeById($id);

        $result["scheme"] = $schemeObject;
        $result["gostArr"] = $labGost->getGostBySchemeId($schemeObject["id"]);
        $result["methodList"] = $labGost->getMethodList();
        $result["materials"] = $labScheme->getOzMaterials($schemeObject["material_type"]);

        $this->data["card"] = $result;

//        echo "<pre>";
//        var_dump($result);
//        echo "</pre>";


        echo "<pre>";
//        if ($_GET["admin"]) {
//            var_dump($this->data);
//        }


//        print_r($this->data["card"]["scheme"]);
//        print_r($this->data["card"]["gostList"][0]);
        echo "</pre>";

        $this->view('card');
    }


    /**
     * @desc Получает данные для карточки схемы
     */
    public function getSchemeCardDataAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $labScheme = $this->model('LabScheme');

        $filter = $labScheme->prepareFilter($_POST ?? []);

        if (!empty($_POST['scheme_id'])) {
            $filter['search']['scheme_id'] = (int)$_POST['scheme_id'];
        }

        $data = $labScheme->getSchemeCardData($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];
        $test = $data['test'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);
        unset($data['test']);

        $jsonData = [
            "draw" => (int)$_POST["draw"],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            "test" => $test,
            "post" =>  $filter['search']
        ];


        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Добавляет ГОСТ в карточку схемы
     */
    public function addGostToSchemeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $labScheme = $this->model('LabScheme');

        $rowId = "";
        $result = "";

        $rangeFrom = $_POST["range_from"] == "" ? "NULL" : $_POST["range_from"];
        $rangeBefore = $_POST["range_before"] == "" ? "NULL" : $_POST["range_before"];

        if (isset($_POST["scheme_id"]) && isset($_POST["gost_id"])) {
            if (empty($_POST["scheme_gost_id"])) {
                $rowId = $labScheme->addGostToScheme(
                    $_POST["scheme_id"],
                    $_POST["gost_id"],
                    $rangeFrom,
                    $rangeBefore,
                    intval($_POST["laboratory_status"]),
                    $_POST["param"]
                );
                $result = $_POST;
            } else {
                $schemeGostId = intval($_POST["scheme_gost_id"]);
//                $rangeFrom = floatval($_POST["range_from"]);
//                $rangeBefore = floatval($_POST["range_before"]);
                $laboratoryStatus = intval($_POST["laboratory_status"]);
                $param = $_POST["param"];

                $data = [
                    //     "id" => "{$schemeGostId}",
                    "range_from" => "{$rangeFrom}",
                    "range_before" => "{$rangeBefore}",
                    "laboratory_status" => "{$laboratoryStatus}",
                    "param" => "{$param}"
                ];

                $labScheme->updateGostToScheme($schemeGostId, $data);
                $result = $data;
            }
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);

    }


    /**
     * @desc Получаем схемы по id материала
     */
    public function getListAjax()
    {

        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $scheme = $this->model('LabScheme');

        $materialId = intval($_POST["material_id"]);

        $schemeList = $scheme->getList($materialId);

        echo json_encode($schemeList, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет схему
     */
    public function deleteSchemeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $scheme = $this->model('LabScheme');

        if (isset($_POST["scheme_id"])) {
            $scheme->deleteScheme(intval($_POST["scheme_id"]));
        }

        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Удаляет гост из схемы
     */
    public function deleteGostFromSchemeAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $scheme = $this->model('LabScheme');
        $result = "";

        if (isset($_POST["scheme_gost_id"])) {
            // $result = $scheme->deleteGostFromScheme(intval($_POST["scheme_gost_id"]));
            $result = $scheme->deleteGost(intval($_POST["scheme_gost_id"]));
        }

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}