<?php

/**
 * @desc Статистика
 * Class StatisticController
 */
class StatisticController extends Controller
{
    /**
     * @desc Страница конструктора отчётов
     */
    public function reportConstructor()
    {
        $this->data['title'] = "Конструктор статистики";

        /** @var Statistic $statisticModel */
        $statisticModel = $this->model('Statistic');

        $this->data['entities'] = $statisticModel->entities;

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

        $this->addCSS("/assets/plugins/morris-chart/morris.css");
        $this->addJs('/assets/plugins/morris-chart/morris.js');
        $this->addJs('/assets/plugins/morris-chart/raphael-min.js');

        $this->addCDN("https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js");
        $this->addCDN("https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation");

        $this->addJS("/assets/js/statistic/report-constructor.js?v=" . rand());

        $this->view('report_constructor');
    }

    /**
     * @desc использование оборудования
     */
    public function reportOborud()
    {
        $this->data['title'] = "Отчет использования оборудования";

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

        $this->addJS("/assets/js/statistic/report-oborud.js");

        $this->view('report_oborud');
    }

    
    /**
     * @desc отчет лаба метод кол-во стоимость. завершенных работ
     */
    public function reportMethod()
    {
        $this->data['title'] = "Отчет";

        /** @var Lab $labModel */
        $labModel = $this->model('Lab');

        $this->data['lab_list'] = $labModel->getList();


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

        $this->addJS("/assets/js/statistic/report-method.js");

        $this->view('report_method');
    }

    /**
     * @desc Отчёт по сотрудникам
     */
	public function reportFinUser()
	{
		/** @var Request $request */
		$request = $this->model('Request');
		/** @var User $user */
		$user = $this->model('User');
		/** @var Material $material */
		$material = $this->model('Material');
		/** @var Order $order */
		$order = $this->model('Order');
		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$this->data['title'] = "Отчет";

		$this->data['a'] = $statistic->getReportByUsers('2022-9-1', '2022-9-30');

		$this->addJs("/assets/js/statistic.js");

		$this->view('reportFinUser');
	}


    /**
     * @desc Страница «Отчет за период»
     */
	public function headerReport()
	{
		/** @var Statistic $statisticModel */
		$statisticModel = $this->model('Statistic');

		$labModel = new Lab();
		$userModel = new User();

		$this->data['title'] = "Отчет за период";

		if (empty($_POST['month'])) {
			$monthReport = date('Y-m');
		} else {
			$monthReport = $_POST['month'];
		}

        $this->data['statistic_date'] = $monthReport;
        $this->data['lab_list'] = $labModel->getList();
        $this->data['protocols'] = $statisticModel->getStatisticProtocolByMonth($monthReport);
        $this->data['users_from_dep'] = $userModel->getUserFromDep();

        $this->data['user_methods'] = $statisticModel->getStatisticUserMethods($monthReport);

        $this->data['fin_report'] = $statisticModel->getFinReport($monthReport);

        $this->data['field_report_protocol'] = [
            'Протоколы',
            'count' => 'Общее количество протоколов, шт',
            'won' => 'Выдано протоколов, шт',
            'in_work' => 'Незавершенные протоколы, шт',
            'price' => 'Выдано протоколов на сумму, руб',
            'Методики',
            'won_methods' => 'Количество завершенных испытаний, шт',
            'methods' => 'Использовано методик, шт',
        ];

        $this->data['field_report_user'] = [
            'complete' => 'Количество завершенных испытаний, шт:',
            'incomplete' => 'Количество незавершенных испытаний, шт:',
            'percent_complete' => "Процент завершенных испытаний<br> относительно лаборатории, %:",
            'price' => 'Стоимость выполненных методик, руб:',
            'percent_price' => "Процент от общей стоимости<br> выполненных методик лаборатории, %",
        ];

        $this->data['fin_report_rows'] = [
            'month_price_new' => 'Общая стоимость новых заявок, руб',
            'year_price_new' => '-- С начала года, руб',
            'month_full_paid' => 'Всего оплачено, руб',
            'month_no_paid_count' => 'Всего неоплачено заявок, шт',
            'month_no_paid_price' => '-- На сумму, руб',
            'year_no_paid_price' => '-- С начала года, руб',
            'month_part_paid_count' => 'Оплачено частично, шт',
            'month_part_paid_price' => '-- На сумму, руб',
            'year_part_paid_price' => '-- С начала года, руб',
        ];

        $this->data['mfc_report_rows'] = [
            'Заявка',
            'a' => 'Общее количество заявок, шт',
            'b' => 'Успешные, шт',
            'c' => 'Неуспешные, шт',
            'd' => 'Уникальные (одна лаб.), шт',
            'e' => 'Совместные (несколько лаб.), шт',
            'Клиент',
            'f' => 'Новый клиентов, шт:',
            'Акты приемки',
            'g' => 'Общее количество актов приемки проб, шт',
            'k' => 'Завершенные, шт',
            'l' => 'Незавершенные, шт',
            'Договоры',
            'n' => 'Сфоромированно, шт',
            'm' => 'Подписаны, шт',
            'o' => 'Не подписаны, шт',
            'Техническое задание',
            'p' => 'Отправлены клиенту, шт',
            'Счета',
            'r' => 'Сформировано, шт',
            's' => 'Сформировано на сумму, руб',
            't' => 'Оплачено на сумму, руб',
        ];

        $this->addCDN("https://cdn.jsdelivr.net/npm/chart.js@4.2.1/dist/chart.umd.min.js");
		$this->addJs("/assets/js/statistic.js");

		$this->view('headerReport');
	}


    /**
     * @desc Отчет за период по сотруднику
     * @param $user_id
     */
	public function headerReportByUser($user_id)
	{
		/** @var Request $request */
		$request = $this->model('Request');
		/** @var User $user */
		$user = $this->model('User');
		/** @var Material $material */
		$material = $this->model('Material');
		/** @var Order $order */
		$order = $this->model('Order');
		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$this->data['title'] = "Отчет за период по сотруднику";
		$date1 = strtotime('2022-11');
		$date2 = strtotime(date('Y-m'));

		for($i = $date1; $i <= $date2; $i = strtotime('+1 month', $i)) {
			$monthReport = date('Y-m', $i);
			$dateRep = date('Ym', strtotime($monthReport));
			$monthRep = date('m', strtotime($monthReport));
			$yearRep = date('Y', strtotime($monthReport));

			$data['Header'] = $statistic->getStatisticHeaderByMonth($monthReport);
			$data['Staff'] = $statistic->getStatisticStaffByMonth($monthReport);
			$data['Finance'] = $statistic->getStatisticFinanceByMonth($monthReport);
			if (!empty($data['Staff']['user'][54][$user_id])) {
				$this->data['Staff'][$dateRep] = $data['Staff']['user'][54][$user_id];
				$this->data['Staff'][$dateRep]['ruDate'] = $yearRep . ' ' . StringHelper::getMonthTitle($monthRep);
			}
			if (!empty($data['Staff']['user'][55][$user_id])) {
				$this->data['Staff'][$dateRep] = $data['Staff']['user'][55][$user_id];
				$this->data['Staff'][$dateRep]['ruDate'] = $yearRep . ' ' . StringHelper::getMonthTitle($monthRep);
			}
			if (!empty($data['Staff']['user'][56][$user_id])) {
				$this->data['Staff'][$dateRep] = $data['Staff']['user'][56][$user_id];
				$this->data['Staff'][$dateRep]['ruDate'] = $yearRep . ' ' . StringHelper::getMonthTitle($monthRep);
			}
			if (!empty($data['Staff']['user'][57][$user_id])) {
				$this->data['Staff'][$dateRep] = $data['Staff']['user'][57][$user_id];
				$this->data['Staff'][$dateRep]['ruDate'] = $yearRep . ' ' . StringHelper::getMonthTitle($monthRep);
			}
		}

		$this->data['User'] = $user->getUserData($user_id);

		$this->addJs("/assets/js/statistic.js");

		$this->view('headerReportByUser');
	}

	/**
	 * @desc Журнал радиологии
	 */
	public function radiologistsList()
	{
		/** @var Request $request */
		$request = $this->model('Request');
		/** @var User $user */
		$user = $this->model('User');
		/** @var Material $material */
		$material = $this->model('Material');
		/** @var Order $order */
		$order = $this->model('Order');
		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$this->data['title'] = "Журнал радиологии";

		$dir = $_SERVER['DOCUMENT_ROOT'] . '/archiveRadiologyProtocol/';

		$this->data['radiologists_request'] = $statistic->getRadiologyRequest();

		foreach ($statistic->getRadiologyRequestNew() as $item) {
			array_push($this->data['radiologists_request'], $item);
		}

		$this->addJs("/assets/js/statistic.js");

		$this->view('radiologists_request');

	}

	/**
	 * @desc Журнал минералогии
	 */
	public function mineralList()
	{
		/** @var Request $request */
		$request = $this->model('Request');
		/** @var User $user */
		$user = $this->model('User');
		/** @var Material $material */
		$material = $this->model('Material');
		/** @var Order $order */
		$order = $this->model('Order');
		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$this->data['title'] = "Журнал минералогии";

		$dir = $_SERVER['DOCUMENT_ROOT'] . '/archiveRadiologyProtocol/';

		$this->data['minerals_request'] = $statistic->getMineralogyRequest();

		$this->addJs("/assets/js/statistic.js");

		$this->view('minerals_request');

	}

	/**
	 * @desc Получает данные по сотрудникам за период для отчёта
	 */
	public function getReportByUsersAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		$response = [];

		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$dateIn = $_POST['dateIn'];
		$dateOut = $_POST['dateOut'];

		$res = $statistic->getReportByUsers($dateIn, $dateOut);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * @desc Загружает PDF файл протокола по радиологии
	 */
	public function uploadFile($id)
	{
		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$dir = $_SERVER['DOCUMENT_ROOT'] . '/archiveRadiologyProtocol/' . $id;

		for ($i = 0; $i < count($_FILES['protocols']['name']); $i++) {
			$filename = $_FILES['protocols']['name'][$i];
			$tmp = $_FILES['protocols']['tmp_name'][$i];

			$pattern = "/\.(pdf)$/i";
			if ( !preg_match($pattern, $filename) ) {
				$this->showErrorMessage('Неверное расширение файла. Загрузите pdf ' . $filename);
				$this->redirect("/statistic/radiologistsList");
			}

			$file = $statistic->saveFile($dir, $filename, $tmp);

			if (!$file) {
				$this->showErrorMessage('Не удалось загрузить файл' . $file['data']);
				$this->redirect("/statistic/radiologistsList");
			}
		}

		$this->showSuccessMessage('Файлы успешно загружены');
		$this->redirect("/statistic/radiologistsList");
	}

    /**
     * @desc Сохраняет для «Журнал радиологии» дату отправки в лабораторию
     */
	public function setRadiologyDate()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$id = (int)$_POST['id'];
		$date = $_POST['date'];

		$setDate = $statistic->setRadiologyDate($id, $date);

		if ($setDate == 0) {
			return false;
		}

		$response = $this->response(true);

		echo json_encode($response, JSON_UNESCAPED_UNICODE);

	}

    /**
     * @desc Удаляет PDF файл протокола по радиологии
     */
	public function delRadiologyProtocol()
	{
		$response = '';
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$href = $_POST['href'];

		$delete = $statistic->deleteProtocolRadiology($href);

		if ($delete) {
			$response = $this->response(true);
		}

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}


	/**
	 *  @desc Загружает PDF файл протокола по минералогии
	 */
	public function uploadFileMineralogy($id)
	{
		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$dir = $_SERVER['DOCUMENT_ROOT'] . '/archiveMineralogyProtocol/' . $id;

		for ($i = 0; $i < count($_FILES['protocols']['name']); $i++) {
			$filename = $_FILES['protocols']['name'][$i];
			$tmp = $_FILES['protocols']['tmp_name'][$i];

			$pattern = "/\.(pdf)$/i";
			if ( !preg_match($pattern, $filename) ) {
				$this->showErrorMessage('Неверное расширение файла. Загрузите pdf ' . $filename);
				$this->redirect("/statistic/mineralList");
			}

			$file = $statistic->saveFile($dir, $filename, $tmp);

			if (!$file) {
				$this->showErrorMessage('Не удалось загрузить файл' . $file['data']);
				$this->redirect("/statistic/mineralList");
			}
		}

		$this->showSuccessMessage('Файлы успешно загружены');
		$this->redirect("/statistic/mineralList");
	}

    /**
     * @desc Сохраняет для «Журнал минералогии» дату отправки в лабораторию
     */
	public function setMineralogyDate()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$id = $_POST['id'];
		$date = $_POST['date'];

		$setDate = $statistic->setMinerallogyDate($id, $date);

		if ($setDate == 0) {
			return false;
		}

		$response = $this->response(true);

		echo json_encode($response, JSON_UNESCAPED_UNICODE);

	}

    /**
     * @desc Удаляет PDF файл протокола по минералогии
     */
	public function delMineralogyProtocol()
	{
		$response = '';
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Statistic $statistic */
		$statistic = $this->model('Statistic');

		$href = $_POST['href'];

		$delete = $statistic->deleteProtocolMinerallogy($href);

		if ($delete) {
			$response = $this->response(true);
		}

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}


    /**
     * @desc Получает статистику персонала по месяцам для диаграммы
     */
	public function getChartAjax()
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var Statistic $statistic */
        $statisticModel = $this->model('Statistic');


		if (empty($_POST['month'])) {
			$monthReport = date('Y-m');
		} else {
			$monthReport = $_POST['month'];
		}

		$response = $statisticModel->getStatisticUserMethods($monthReport);

		echo json_encode($response, JSON_UNESCAPED_UNICODE);
	}


    /**
     * @desc Получение данных для журнала отчет лаба метод кол-во стоимость. завершенных работ
     */
    public function getJournalReportMethodAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Statistic $statisticModel */
        $statisticModel = $this->model('Statistic');

        $filter = $statisticModel->prepareFilter($_POST ?? []);

        $data = $statisticModel->getJournalReportMethodList($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];
        $sql = $data['sql'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);
        unset($data['sql']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "sql" => $sql,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение данных для журнала оборудования
     */
    public function getJournalOborudAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Statistic $statisticModel */
        $statisticModel = $this->model('Statistic');

        $filter = $statisticModel->prepareFilter($_POST ?? []);

        $data = $statisticModel->getJournalReportOborudList($filter);

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
     * @desc Получение сущности для конструктора журнала
     */
    public function getStatisticEntityAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Statistic $statisticModel */
        $statisticModel = $this->model('Statistic');

        $result = $statisticModel->getStatisticEntity($_POST['entity'], (int)$_POST['id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Получение колонок для конструктора журнала
     */
    public function getStatisticColumnAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Statistic $statisticModel */
        $statisticModel = $this->model('Statistic');

        $result = $statisticModel->getColumnsEntity($_POST['entity'], $_POST['column']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получение данных для конструктора журнала
     */
    public function getStatisticConstructorJournal()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Statistic $statisticModel */
        $statisticModel = $this->model('Statistic');

        $filter = $statisticModel->prepareFilter($_POST ?? []);

        $filter['entity']['key'] = $statisticModel->sanitize($_POST['entity']);
        $filter['entity']['column'] = array_map($this->sanitize, $_POST['column']);

        $data = $statisticModel->getStatisticConstructorJournal($filter);

        $chart = $data['chart'];
        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['chart']);
        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "chart" => $chart,
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }
}
