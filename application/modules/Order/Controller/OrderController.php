<?php

/**
 * @desc Договор контроллер
 * Class OrderController
 */
Class OrderController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     * route /order/
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }


    /**
     * @desc Журнал договоров
     * route /order/list/
     */
    public function list()
    {
        $this->data['title'] = 'Журнал договоров';

        /** @var Request $request */
        $request = $this->model('Request');

        $this->data['date_start'] = $request->getDateStart();

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
        $this->addJS("/assets/plugins/modal/modalWindow.js");

        $this->addJs('/assets/js/order-list.js');
        $this->addCSS("/assets/plugins/magnific-popup/magnific-popup.css");

        $this->addJs('/assets/plugins/magnific-popup/jquery.magnific-popup.min.js');

        $this->view('list');
    }


    /**
     * @desc Журнал Сверки
     * route /order/reviseList/
     */
    public function reviseList()
    {
        $this->data['title'] = 'Журнал Сверки';

        $this->addJs('/assets/js/order-revise-list.js');

        $this->view('revise_list', '', 'template_journal');
    }


    /**
     * @desc Получает данные для журнала договоров
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Order $order */
        $order = $this->model('Order');

        $filter = [
            'paginate' => [
                'length'    => $_POST['length'], // кол-во строк на страницу
                'start'     => $_POST['start'],  // текущая страница
            ],
            'search' => [],
            'order' => []
        ];

        foreach ($_POST['columns'] as $column) {
            if ( $column['search']['value'] !== '' ) {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if ( isset($_POST['order']) && !empty($_POST['columns']) ) {
            $filter['order']['by']  = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

        if ( !empty($_POST['dateStart']) ) {
            $filter['search']['dateStart'] = date('Y-m-d', strtotime($_POST['dateStart'])) . ' 00:00:00';
            $filter['search']['dateEnd'] = date('Y-m-d', strtotime($_POST['dateEnd'])) . ' 23:59:59';
        }
        if ( !empty($_POST['everywhere']) ) {
            $filter['search']['everywhere'] = $_POST['everywhere'];
        }

        $data = $order->getDataToJournal($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Получает данные для журнала договоров
     */
    public function getReviseDataJournalAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Order $order */
        $order = $this->model('Order');

        $filter = [
            'paginate' => [
                'length'    => $_POST['length'], // кол-во строк на страницу
                'start'     => $_POST['start'],  // текущая страница
            ],
            'search' => [],
            'order' => []
        ];

        foreach ($_POST['columns'] as $column) {
            if ( $column['search']['value'] !== '' ) {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if ( isset($_POST['order']) && !empty($_POST['columns']) ) {
            $filter['order']['by']  = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

        $data = $order->getReviseDataToJournal($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => $_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Карточка договора
     * @param $contractID
     */
    public function card($contractID)
    {
        if (empty($contractID)) {
            $this->redirect('/request/list/');
        }

        $contractID = (int) $contractID;

        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Company $company */
        $company = $this->model('Company');
        /** @var Order $order */
        $order = $this->model('Order');

        $this->data['title'] = "Карточка договора";

        $contract = $order->getContractById($contractID);
        $requisite = $company->getRequisiteByCompanyId($contract['CLIENT_ID']);
        $company = $company->getById($contract['CLIENT_ID']);
        $requestByOrder = $order->getDealToContractByContractId($contractID);
        $paymentOnContract = $order->getPaymentOnContract($contractID);
        $dealInfo = $request->getDealIdByTzId($contract['TZ_ID']);

        $totalCost = 0;
        $SumPayment = 0;
        foreach ($requestByOrder as $item) {
            $SumPayment += $item['OPLATA'];
//            if ($item['status'] == 'work') {

            $totalCost += $item['price_discount'];
//            }
        }

        $contractRequestCoast = 0;

        foreach ($requestByOrder as $item) {
            if ($item['ID_DEAL'] == $contract['head_request'] || $item['status'] == 'lose') continue;
            $contractRequestCoast += $request->getTzByDealId($item['ID_DEAL'])['price_discount'];
        }

        $this->data['history'] = $order->getFinanceHistory($contractID);

        $this->data['order']['company'] = $company['TITLE'];
        $this->data['order']['number'] = $contract['NUMBER'];
        $this->data['order']['type'] = $contract['CONTRACT_TYPE'];
        $this->data['order']['pdf'] = $contract['PDF'];
        $this->data['order']['actual_ver'] = $contract['ACTUAL_VER'];
        $this->data['order']['id'] = $contract['ID'];
        $this->data['order']['action'] = $contract['IS_ACTION'];
        $this->data['order']['longterm'] = $contract['LONGTERM'];
        $this->data['order']['head_request'] = $contract['head_request'];
        $this->data['order']['client_number'] = $contract['CLIENT_NUMBER'];
        $this->data['order']['flow_date'] = $contract['FLOW_DATE'];
        $this->data['order']['summ'] = $contract['SUMM'];
        $this->data['order']['finance'] = $contract['finance'];
        $this->data['order']['date'] = date('Y-m-d', strtotime($contract['DATE']));
        $this->data['client']['phone'] = $requisite['RQ_PHONE'];
        $this->data['client']['email'] = $requisite['RQ_EMAIL'];
        $this->data['client']['contact'] = $requisite['RQ_NAME'];
        $this->data['client']['price'] = $order->getClientPrice($contractID);
        $this->data['request'] = $requestByOrder;
        $this->data['order']['payment']['sum'] = $paymentOnContract['data'];
        $this->data['Cost'] = StringHelper::priceFormatRus($totalCost);
        $this->data['cost_contract'] = StringHelper::priceFormatRus($contractRequestCoast);
        $this->data['debt_contract'] = StringHelper::priceFormatRus($contract['SUMM'] - $contractRequestCoast);
        $this->data['Payment'] = StringHelper::priceFormatRus($paymentOnContract['paymentSum']);
        $this->data['Debt_Dog'] = StringHelper::priceFormatRus($contract['SUMM'] - $paymentOnContract['paymentSum']);
        $this->data['Debt_Dog_modal'] = $contract['SUMM'] - $paymentOnContract['paymentSum'];
        $this->data['Debt'] = StringHelper::priceFormatRus($totalCost - $SumPayment);
        $this->data['dealID'] = $dealInfo;
        $this->data['tz_id'] = $contract['TZ_ID'];

        // $this->data['is_show_finance'] = in_array($_SESSION["SESS_AUTH"]["USER_ID"], [88, 25]);

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
        $this->addJs("/assets/js/order.js?v={$r}");

        $this->view('card');
    }


    /**
     * @desc Получает данные для журнала заявки по договору
     */
    public function getJournalRequestAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Order $orderModel */
        $orderModel = $this->model('Order');


        $filter = [
            'paginate' => [
                'length'    => $_POST['length'],  // кол-во строк на страницу
                'start'     => $_POST['start'],  // текущая страница
            ],
            'search' => [],
            'order' => []
        ];

        foreach ($_POST['columns'] as $column) {
            if ( $column['search']['value'] !== '' ) {
                $filter['search'][$column['data']] = $column['search']['value'];
            }
        }

        if ( !empty($_POST['order_id']) ) {
            $filter['search']['order_id'] = $_POST['order_id'];
        }
        if ( $_POST['stage'] !== '' ) {
            $filter['search']['stage'] = $_POST['stage'];
        }

        if ( isset($_POST['order']) && !empty($_POST['columns']) ) {
            $filter['order']['by']  = $_POST['columns'][$_POST['order'][0]['column']]['data'];
            $filter['order']['dir'] = $_POST['order'][0]['dir'];
        }

        $data = $orderModel->getDataJournalRequest($filter);

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
     *  @desc Оплатить заявку из счета
     */
    public function addPayment()
    {
        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        $result = $orderModel->addPay((int) $_POST['orderId'], (int) $_POST['deal_id'], (float) $_POST['money']);

        if ( !$result['success'] ) {
            $this->showErrorMessage($result['error']);
        } else {
            $this->showSuccessMessage("Заявка оплачена на сумму: {$_POST['money']}");
        }

        $this->redirect('/order/card/' . $_POST['orderId']);
    }


    /**
     * @desc Добавить денег на счет
     */
    public function addFinance()
    {
        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        $orderModel->addFinance((int) $_POST['orderId'], (float) $_POST['money']);

        $this->showSuccessMessage("На счет добавлена сумма: {$_POST['money']}");

        $this->redirect('/order/card/' . $_POST['orderId']);
    }


    /**
     * @desc Изменяет данные договора
     */
    public function changeOrder()
    {
        /** @var Request $request */
        $request = $this->model('Request');
        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        $orderID = $_POST['orderID'];

        $data['NUMBER'] = $_POST["NUMBER"];
        $data['DATE'] = $_POST['DATE'];
        $data['LONGTERM'] = $_POST['LONGTERM'] == 'on' ? 1 : 0;
        $data['CONTRACT_TYPE'] = $_POST['CONTRACT_TYPE'];
        $data['FLOW_DATE'] = $_POST['FLOW_DATE']  == 'on' ? 1 : 0;
        $data['CLIENT_NUMBER'] = $_POST['CLIENT_NUMBER']  == 'on' ? 1 : 0;
        if ($_POST['LONGTERM'] == 'on') {
            if (!empty($_POST['head_request'])) {
                $data['SUMM'] = $request->getTzByDealId($_POST['head_request'])['price_discount'];
                $data['head_request'] = $_POST['head_request'];
            } else {
                $data['SUMM'] = $_POST['CONTRACT_SUMM'];
            }
        }

        $orderModel->setOrderById($orderID, $data);
        $location = '/order/card/' . $orderID;
        $this->redirect($location);
    }


    /**
     * @desc Создаёт прайс для клиента
     * @param $orderID
     */
    public function creatOrderPrice($orderID)
    {
        /** @var Order $order */
        $order = $this->model('Order');

        $location = '/order/card/' . $orderID;

        $res = $order->setClientPrice($orderID);

        if ($res) {
            $this->showSuccessMessage('Прайс успешно добавлен');
            $this->redirect($location);
        }
    }


    /**
     * @desc Сохраняет данные оплаты
     */
    public function setOplata()
    {
        /** @var Order $order */
        $order = $this->model('Order');

        $orderID = $_POST['order_id'];

        $location = '/ulab/order/card/' . $orderID;

        $data = [
            'ID_CONTRACT' => $orderID,
            'PAY_SUMM' => $_POST['pay'],
            'PAY_DATE' => $_POST['payDate'],
        ];

        $result = $order->setOplata($data);

        if ($result) {
            $this->showSuccessMessage('Оплата успешно добавлена');
            $this->redirect($location);
        } else {
            $this->showErrorMessage('Не удалось добавить оплату');
            $this->redirect($location);
        }
    }


    /**
     * @desc Загрузить pdf ТЗ
     */
    public function uploadTzDocPdfAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        if ( !empty($_POST['tz_id']) ) {
            $resultUpload = $orderModel->uploadTzDocPdf($_FILES["file"], $_POST['tz_id']);
        }

        if ( !empty($_POST['dogovor_id']) ) {
            $resultUpload = $orderModel->uploadPdf($_FILES["file"]);
        }

        if ( $resultUpload['success'] ) {
            if ( !empty($_POST['doc_id']) ) {
                $orderModel->saveTzDocPdf($resultUpload['data'], $_POST['doc_id']);
            }
            if ( !empty($_POST['dogovor_id']) ) {
                $orderModel->saveDogovorPdf($resultUpload['data'], $_POST['dogovor_id']);
            }

            $this->showSuccessMessage("Файл загружен");
        } else {
            $this->showErrorMessage($resultUpload['error']);
        }

        echo json_encode(['success' => $resultUpload], JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Аннулирует или восстанавливает договор
     * @param $orderId
     */
    public function cancelOrder($orderId)
    {
        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        $orderModel->orderCancelByOrderId($orderId);

        $result = $orderModel->getContractById($orderId)['IS_ACTION'];

        $location = '/ulab/order/card/' . $orderId;

        if ($result) {
            $this->showSuccessMessage("Договор восстановлен");

        } else {
            $this->showErrorMessage("Договор аннулирован");
        }
        $this->redirect($location);
    }


    /**
     * @desc Удаляет PDF файл прил. к договору (тз)
     */
    public function delTzDocAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Order $orderModel */
        $orderModel = $this->model('Order');

        $id = $_POST['id'];

        $result = $orderModel->deletePdfTZ($id);

        if ($result) {
            $this->showSuccessMessage("Файл успешно удален");
        } else {
            $this->showErrorMessage("Файл не удален");
        }

        echo json_encode(['succes' => $result], JSON_UNESCAPED_UNICODE);
    }
}
