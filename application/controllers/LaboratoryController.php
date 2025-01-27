<?php

\Bitrix\Main\UI\Extension::load("ui.notification");

/**
 * @desc Контроллер для работы с лабораторией
 * Class LaboratoryController
 */
class LaboratoryController extends Controller
{
    const ADMIN_USERS = [39, 46, 41, 19, 36];

    // Ответственные за заявку (из ulab)
    const MANAGER_USERS = [
        [
            "id" => 18,
            "fio" => "Богусевич"
        ],
        [
            "id" => 94,
            "fio" => "Третьяков"
        ],

    ];

    /**
     * @desc Журнал входного контроля
     */
    public function registrationList()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Журнал входного контроля");

//        $this->data = Curl::request("/api/request/getRegistrationData", ["type" => 0]);
//     //   $this->data["gostArr"] = Curl::request("https://ulab.niistrom.pro/api/request/getRegistrationData", []);
//     //   $this->data["methods"] = Curl::request("https://ulab.niistrom.pro/api/request/getMethodsForRegistrationData", []);
//        $this->data["schemeArr"] = Curl::request("/api/scheme/getListAjax", []);
//
//        echo "<pre>";
//        var_dump($this->data["count"]);
//
//        echo "</pre>";

        $version = "?v=" . rand();

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/css/style-2.css" . $version);


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
        $this->addJS("/assets/plugins/modal/modalWindow.js");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");

        $this->addJS("/assets/js/registrationList.js" . $version);

        $this->view('registrationList');

    }


    /**
     * @desc Регистрационная карточка
     */
    public function registrationCard($ozTzId)
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Результаты испытаний");

        $version = "?v=" . rand();

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addCSS("/assets/css/style-2.css" . $version);

        $this->addJS("/assets/js/registrationCard.js" . $version);

        $user = $this->model('User');


        $this->data = Curl::request("/api/request/getResultCardAjax", ["oz_tz_id" => $ozTzId]);

        $this->data["cardInfo"]["deadline"] =  date("d.m.Y", strtotime(DateHelper::addWorkingDays($this->data["cardInfo"]["date"], 3)));
        $this->data["user_id"] = intval($user->getCurrentUserId());
        $this->data["is_admin"] = in_array($this->data["user_id"], self::ADMIN_USERS);
        $this->data["background"] = "";

      //  if (date("Y-m-d") > $this->data["cardInfo"]["date"] && $this->data["cardInfo"]["stage_id"] !== "WON") {
        if (date("d.m.Y") > $this->data["cardInfo"]["deadline"] && $this->data["cardInfo"]["stage_id"] !== "WON") {
            $this->data["background"] = "bg-light-red";
        }


        if (empty($this->data["cardInfo"]["id"]) || $this->data["cardInfo"]["del"] == 1) {
            $this->redirect("/laboratory/registrationList/");
        }

        echo "<pre>";
        if ($_GET["admin"]) {
            var_dump(date("Y-m-d"));
            var_dump($this->data["cardInfo"]);
            var_dump($this->data["is_admin"]);
        }
        echo "</pre>";

        $this->view('registrationCard-2');
      //  $this->view('registrationCard');
    }


    /**
     * @desc Редактор схем
     */
    public function editor()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Редактор схем");

        $version = "?v=" . rand();

        if ($_GET["type"] == 1) {
            $this->data["path"] = URI . "/laboratory/passportJournal/";
            $this->data["manufacturer"] = "Опытный завод УРАЛНИИСТРОМ";
        } else {
            $this->data["path"] = URI . "/laboratory/registrationList/";
            $this->data["manufacturer"] = "";
        }

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/css/style-2.css" . $version);


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
        $this->addJS("/assets/plugins/modal/modalWindow.js");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");

        $this->addJS("/assets/js/schemeEditor.js" . $version);

        $this->view('editor');
    }


    /**
     * @desc Список схем
     */
    public function schemeList()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Список схем");

        $version = "?v=" . rand();

        $this->addCSS("/assets/css/style-2.css" . $version);

        $this->addJS("/assets/js/registrationCard.js" . $version);
    }


    /**
     * @desc Карточка схемы
     * @param int $id
     */
    public function schemeCard(int $id)
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
        $this->addJS("/assets/plugins/DataTables/Buttons-2.0.1/js/dataTables.buttons.min.js");
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

        $this->addJS("/assets/js/schemeCard.js" . $version);

        $this->data["card"] = Curl::request(
            "/api/scheme/getSchemeCardAjax",
            ["scheme_id" => $id]
        );

        echo "<pre>";
//        if ($_GET["admin"]) {
//            var_dump($this->data);
//        }


//        print_r($this->data["card"]["scheme"]);
//        print_r($this->data["card"]["gostList"][0]);
        echo "</pre>";

        $this->view('schemeCard');
    }


    /**
     * @desc Журнал паспортизации
     */
    public function passportJournal()
    {
        
        global $APPLICATION;
        $APPLICATION->SetTitle("Журнал паспортизации");

        $user = $this->model('User');
        $gost = $this->model('LabGost');
        $labScheme = $this->model('LabScheme');
        $labRequest = $this->model('LabRequest');

        $data["gostList"] = $gost->getMethodList();
        $data["materials"] = $labScheme->getOzMaterials(1);
        $data["count"] = $labRequest->getCountTz();

        $this->data = $data;
//        $this->data["schemeArr"] = $labScheme->getList();



        $this->data["user_id"] = intval($user->getCurrentUserId());
        $this->data["is_admin"] = in_array($this->data["user_id"], self::ADMIN_USERS);

        $version = "?v=" . rand();

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/css/style-2.css" . $version);


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
        $this->addJS("/assets/plugins/modal/modalWindow.js");
//		$this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
		$this->addJS("/assets/js/laboratory/passportJournal.js" . $version);

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");

        $this->view('passportJournal');
    }


    /**
     * @desc Карточка паспорта
     * @param $passportId
     */
    public function passportCard($passportId)
    {
        global $APPLICATION;
        $APPLICATION->SetTitle("Результаты испытаний");

        $version = "?v=" . rand();

        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->addCSS("/assets/css/style-2.css" . $version);

        $this->addJS("/assets/js/laboratory/passportCard.js" . $version);


        $user = $this->model('User');
        $file = $this->model('File');
        $passport = $this->model('LabPassport');

        $this->data["ulabGost"] = $passport->getUlabGostList($passportId);
        $this->data["ozGost"] = $passport->getOzGostList($passportId);
        $this->data["cardInfo"] = $passport->getResultCardInfo($passportId);

        $dir = $_SERVER["DOCUMENT_ROOT"] . "/api/upload/lab/passportCert/{$passportId}/";
        $this->data["files"]["cert"] = $file->getFilesFromDir($dir);

     //   $this->data = $data;

        if ($this->data["cardInfo"]["created_date"]) {
            $this->data["cardInfo"]["deadline"] = date("d.m.Y", strtotime(DateHelper::addWorkingDays($this->data["cardInfo"]["created_date"], 3)));
        }

        if (!$this->data["cardInfo"]["order_number"]) {
            $this->data["cardInfo"]["order_number"] = "n/a";
        }

        $this->data["user_id"] = intval($user->getCurrentUserId());
        $this->data["is_admin"] = in_array($this->data["user_id"], self::ADMIN_USERS);
        $this->data["background"] = "";

        if (date("Y-m-d") > $this->data["cardInfo"]["created_date"] && $this->data["cardInfo"]["stage_id"] !== "WON") {
            $this->data["background"] = "bg-light-red";
        }

//        if (empty($this->data["cardInfo"]["id"]) || $this->data["cardInfo"]["del"] == 1) {
//            $this->redirect("/laboratory/passportJournal/");
//        }

        echo "<pre>";
        if ($_GET["admin"]) {
            var_dump($this->data);
        }
        echo "</pre>";

        $this->view('passportCard');
        //  $this->view('registrationCard');
    }

//    public function addProductPassportAjax()
//    {
//        global $APPLICATION;
//        $APPLICATION->RestartBuffer();
//
//        $product = $this->model('Product');
//
//        $data = [
//            "b_product_id" => $_POST["b_product_id"],
//            "ulab_passport" =>  $_POST["ulab_passport"],
//        ];
//
//        $product->addProductPassport($data);
//
//        echo json_encode($data, JSON_UNESCAPED_UNICODE);
//    }

//    public function addProductTzAjax()
//    {
//        global $APPLICATION;
//        $APPLICATION->RestartBuffer();
//
//        $product = $this->model('Product');
//
//        $data = [
//            "product_id" => $_POST["product_id"],
//            "ulab_tz" =>  $_POST["ulab_tz"],
//        ];
//
//        $id = $product->addProductTz($data);
//
//        echo json_encode($id, JSON_UNESCAPED_UNICODE);
//    }

//    public function deleteProductTzAjax()
//    {
//        global $APPLICATION;
//        $APPLICATION->RestartBuffer();
//
//        $product = $this->model('Product');
//
//        if ($_POST["product_id"] && $_POST["ulab_tz"]) {
//            $product->deleteProductTz($_POST["product_id"], $_POST["ulab_tz"]);
//            echo json_encode(["msg" => "Успешно удалено!"], JSON_UNESCAPED_UNICODE);
//        }
//    }

    /**
     * @desc Дашборд
     */
    public function dashboard($schemeId)
    {

        $this->data = Curl::request("/api/scheme/getDashboardCardAjax", ["scheme_id" => $schemeId]);

        $this->data["scheme_id"] = $schemeId;
        $this->data["type"] = $this->data["scheme"]["material_type"] == 1 ? "passportCard" : "registrationCard";

       // var_dump($this->data);

        $version = "?v=" . rand();

        $this->addCSS("/assets/css/style-2.css" . $version);
        $this->addCSS("/assets/css/shipmentCard.css" . $version);

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addCSS("/assets/plugins/DataTables/ColReorder-1.5.5/css/colReorder.dataTables.min.css");
        $this->addCSS("/assets/plugins/DataTables/Buttons-2.0.1/css/buttons.dataTables.min.css");
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");
        $this->addCSS("/assets/plugins/DataTables/FixedColumns-4.2.1/css/fixedColumns.css");

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
        $this->addJS("/assets/plugins/modal/modalWindow.js");
        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');
        $this->addJS("/assets/plugins/DataTables/FixedHeader-3.2.0/js/dataTables.fixedHeader.min.js");
        $this->addJS("/assets/plugins/DataTables/FixedColumns-4.2.1/js/fixedColumns.js");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");

        $this->addJS("/assets/js/laboratory/dashboard.js" . $version);

        if ($schemeId) {
            $this->view('dashboard');
        } else {
            $this->view('dashboard-test');
        }

    }

//    public function test()
//    {
//        $composition = $this->model("Composition");
//
//        $compositionData = $composition->getRowByProductBxId(16649);
//        if ($compositionData) {
//            $result = $composition->getCompositionCode($compositionData["id"], $compositionData["parent_id"], $compositionData["group_id"]);
//        }
//
//      //  $result = $composition->getCompositionCode(10, null, 2);
//
//        var_dump($result);
//    }


    /**
     * @desc Получает данные для журнала паспортизации
     */
	public function getJournalAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		$passport = $this->model('Laboratory');

		$filter = [
			'paginate' => [
				'length' => $_POST['length'],  // кол-во строк на страницу
				'start' => $_POST['start'],  // текущая страница
			],
			'search' => [],
			'order' => []
		];

		foreach ($_POST['columns'] as $column) {
			if (!empty($column['search']['value'])) {
				$filter['search'][$column['data']] = $column['search']['value'];
			}
		}

		if (isset($_POST['order']) && !empty($_POST['columns'])) {
			$filter['order']['by'] = $_POST['columns'][$_POST['order'][0]['column']]['data'];
			$filter['order']['dir'] = $_POST['order'][0]['dir'];
		}

		if (!empty($_POST['dateStart'])) {
			$filter['search']['dateStart'] = date('Y-m-d', strtotime($_POST['dateStart']));
			$filter['search']['dateEnd'] = date('Y-m-d', strtotime($_POST['dateEnd']));
		}

		if (isset($_POST['hidden'])) {
			$filter['search']['hidden'] = $_POST["hidden"];
		}

		$data = $passport->getJournal($filter);

		$recordsTotal = $data['recordsTotal'];
		$recordsFiltered = $data['recordsFiltered'];
		$test = $data['test'];

		unset($data['recordsTotal']);
		unset($data['recordsFiltered']);
		unset($data['test']);


		//  $test = CCrmDeal::GetList();

		$jsonData = [
			"draw" => $_POST["draw"],
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
			"post" => $filter,
			"test" => $test
		];


		echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);

	}


    /**
     * @desc Сохраняет данные для журнала паспортизации
     */
    public function insertAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();


        //  sleep(5);

//        echo json_encode(["answer" => "Убрать загрушку", "post" => $_POST]);
//        die();

//        $request = $this->model('Request');
//        $gost = $this->model('Gost');

        /** @var Laboratory $request */
        $request = new Laboratory();

        /** @var Material $material */
        $material = new Material();
        /** @var LabScheme $scheme */
        $scheme = new LabScheme();
        /** @var Gost $gost */
        $gost = new LabGost();

        $gostArr = $gost->getGostBySchemeId($_POST["scheme_id"]);
        $ulabGostArr = [];
        $ozGostArr = [];

        foreach ($gostArr as $gostItem) {
            if ($gostItem["laboratory_status"] == 1) {
                $ozGostArr[] = $gostItem;
            } else {
                $ulabGostArr[] = $gostItem;
            }
        }

        $baTzId = NULL;

        // && $_POST["hidden"] !== 1
        if (!empty($ulabGostArr) && $_POST["hidden"] != 1) {
            // Заявка в ulab
            usleep(300);
            $baTzId = $request->createUlabRequest($_POST, $ulabGostArr);
            usleep(300);
        }


//        echo json_encode($baTzId, JSON_UNESCAPED_UNICODE);
//        exit();

        $curDate = date("Y-m-d");

        $ozPassportData = [
            "ba_tz_id" => "'{$baTzId}'",
            "scheme_id" => $_POST["scheme_id"],
            "batch_number" => "'{$_POST["batch_number"]}'",
            "b_product_id" => "'{$_POST["b_product_id"]}'",
            "order_number" => "'{$_POST["order_number"]}'",
            //  "assigned_name" => "'{$_POST["assigned_name"]}'",
            "assigned_id" => "'{$_POST["assigned_id"]}'",
            "quantity" => "'{$_POST["quantity"]}'",
            "client" => "'{$_POST["client"]}'",
            "created_date" => "'{$curDate}'",
            "hidden" => $_POST["hidden"]
            //   "assigned_name" => $_POST["assigned_name"]
        ];

        if ($_POST["composition_code"]) {
            $ozPassportData["composition_code"] = "'{$_POST["composition_code"]}'";
        }

        //$ozTzId = $request->addOzTz($ozTzData);
        //   $ozPassportId = $passport->addPassportData($ozPassportData); // не работает через passport
        $ozPassportId = $request->addPassportData($ozPassportData);

        $result = [];
        foreach ($ozGostArr as $gostItem) {

            $PassportGostData = [
                'scheme_gost_id' => $gostItem["scheme_gost_id"],
                'oz_passport_id' => $ozPassportId

            ];

            $result[] = $request->addPassportGost($PassportGostData);

        }

      //  echo json_encode(["id" => $ozPassportId]);
        echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
    }

//    public function getPassportCardAjax()
//    {
//        global $APPLICATION;
//
//        $APPLICATION->RestartBuffer();
//
//        $passportId = $_POST["passport_id"];
//
//        $passport = $this->model('LabPassport');
//        $file = $this->model('File');
//
//        $jsonData["ulabGost"] = $passport->getUlabGostList($passportId);
//        $jsonData["ozGost"] = $passport->getOzGostList($passportId);
//        $jsonData["cardInfo"] = $passport->getResultCardInfo($passportId);
//
//        $dir = $_SERVER["DOCUMENT_ROOT"] . "/api/upload/lab/passportCert/{$passportId}/";
//        $jsonData["files"]["cert"] = $file->getFilesFromDir($dir);
//        $jsonData["post"] = $_POST;
//
//        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
//    }


    /**
     * @desc Сохраняет данные результатов испытаний
     */
    public function updatePassportGostAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $gost = $this->model('LabGost');
        $request = $this->model('Request');
        $file = $this->model('File');
        $comment = $this->model('LabComment');
        $passport = $this->model('LabPassport');

        $tzGostId = $_POST["id"];

        $value = $_POST["value"];
        $tzId = $_POST["tz_id"];
        $batchNumber = $_POST["batch_number"];


        $commentData = [
            "TEXT" => $_POST["ulab_comment"]
        ];

        if ($_POST["ulab_comment_id"]) {
            $comment->updateData($commentData, $_POST["ulab_comment_id"]);
        } elseif ($_POST["ulab_comment"]) {
            $commentData["ID_REQ"] = $_POST["deal_id"];
            $comment->add($commentData, $_POST["ulab_comment_id"]);
        }

        if ($value == "") {
            $value = "NULL";
        }

        $data = [
            "value" => $value
        ];

  //      $data = $value;

//        if (!empty($_POST["file_delete"])) {
//            $pathArr = explode(",", $_POST["file_delete"]);
//            foreach ($pathArr as $path) {
//                if ($path != "" && substr($path, 0, 23) == "/laboratory/upload/lab/") {
//                    unlink($_SERVER["DOCUMENT_ROOT"] . $path);
//                }
//            }
//        }
//
//        for ($j = 0; $j < count($_FILES["cert"]["name"]); $j++) {
//            $dir = "upload/lab/passportCert/{$tzId}";
//            $file->saveFile($dir, $_FILES["cert"]["name"][$j], $_FILES["cert"]["tmp_name"][$j]);
//        }



        $gost->updatePassportGost($tzGostId, $data);

        $tzData = [
            "batch_number" => $batchNumber
        ];
        $passport->updateRow($tzData, $tzId);

        $result["post"] = $_POST;
        $result["files"] = [$tzId, $tzData];

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

}
