<?php

/**
 * @desc Контроллер для протоколов
 * Class ProtocolController
 */
Class ProtocolController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     * route /protocol/
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }


    /**
     * @param string $protocolId
     */
    public function sig($protocolId = '')
    {
        $this->data['title'] = 'Электронная подпись';

        /** @var  Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');

        $protocolInfo = $protocolModel->getProtocolById($protocolId);

        $this->data['protocol_name'] = $protocolInfo['NUMBER_AND_YEAR'];
        $this->data['protocol_id'] = $protocolId;

        $this->data['today'] = date('d.m.Y');
        $this->data['file_name'] = $protocolInfo['pdf_name'];
        $this->data['user_id'] = App::getUserId();
        $this->data['outside_lis'] = $protocolInfo['PROTOCOL_OUTSIDE_LIS'];
        $this->data['outside_lis_path_pdf'] = '';
        if ( $protocolInfo['PROTOCOL_OUTSIDE_LIS'] == 1 ) {
            $this->data['outside_lis_path_pdf'] = $protocolInfo['outside_lis_protocol_path'] . $protocolInfo['pdf_name'];
            $this->data['new_pdf_path'] = $protocolInfo['new_pdf_path'] . $protocolInfo['pdf_name'];

            if ( empty($protocolInfo['pdf_name']) ) {
                $this->data['disable_btn'] = true;
                $this->showErrorMessage("Не прикреплён файл для подписи. Протокол вне ЛИС.");
            }
        }

        $this->data['deal_id'] = $protocolInfo['DEAL_ID'];

        $this->addJS("/assets/plugins/EDS/cadesplugin_api.js");
        $this->addJS("/assets/plugins/EDS/Code.js");

        $this->addJS("/assets/plugins/pdf-lib/pdf-lib-1.4.0.js");

        $this->addJS("/assets/js/protocol-sig.js?v=" . rand());

        $this->view('sig');
    }


    /**
     * route /protocol/results/
     */
    public function results()
    {
        $this->data['title'] = 'Результаты испытаний';

        /**
         * @var Protocol $protocol
         */
        $protocol = $this->model('Protocol');

        $this->view('results');
    }


    /**
     * @desc Журнал протоколов
     * route /protocol/list/
     */
    public function list()
    {
        $this->data['title'] = 'Журнал протоколов';

        /** @var Lab $lab */
        $lab = $this->model('Lab');
        /** @var Request $request */
        $request = $this->model('Request');

        $this->data['lab'] = $lab->getList();
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

        $r = rand();
        $this->addJs("/assets/js/protocol-list.js?v={$r}");

        $this->view('list');
    }


    /**
     * @desc Получает данные для «Журнала протоколов»
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Protocol $protocol */
        $protocol = $this->model('Protocol');

        $filter = $protocol->prepareFilter($_POST ?? []);

        $data = $protocol->getDataToJournal($filter);

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
     * @desc Проверяет данные у протокола перед формированием
     */
    public function validateProtocolAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');

		if (!in_array((int)$_POST['protocol_id'], [5675, 5651, 5711, 5679, 5752, 5810, 5754, 5755, 5803, 5834, 5875, 5913,
			5929, 5930,	5912, 5911, 5866, 5856, 5783, 6001, 6002, 5975, 6030, 6042, 6047, 5843, 6045, 6061, 6060, 6057,
			6064, 6065, 6070, 6166, 6167, 6168, 6171, 6173, 6174, 6179, 6176, 6177, 6186, 6185, 6190, 6192, 6170, 6197,
			6199, 5888, 6200, 6201, 6202, 6203, 6204, 6205, 6207, 6195, 5988, 6226, 6225, 6227, 6251, 6252, 6206, 6265,
			6269, 6270, 6271, 6272, 6286, 6285, 6290, 6292, 6293, 6295, 6296, 6297, 6300, 6301, 6302, 5812, 6006, 6267,
			6336, 6333, 6371, 6404, 6440, 6342, 6425, 6607, 6610, 6687, 6651, 6650, 6749, 6788, 6789, 6790, 6595, 6734,
			6947, 6930, 6931, 6840, 6962, 7078, 7086, 7224, 6786, 7273, 7213, 7237, 7271, 7272, 7280, 7281, 7417, 7380,
			7523, 7524, 7536, 7538, 7539, 7540, 7424, 7537, 7628, 8235, 8080, 8321, 8457, 8458, 8468]) ) {
        	$result = $protocolModel->validateProtocol((int)$_POST['protocol_id']);
		}

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc сохраняет ЭЦП
     */
    public function saveSigAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');

        $result = $protocolModel->saveSig((int)$_POST['id'], $_POST['file_name'], $_POST['sign']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc получает ЭЦП
     */
    public function sigAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var DocumentGenerator $generatorModel */
        $generatorModel = $this->model('DocumentGenerator');

        $result = $generatorModel->sigProtocol((int)$_POST['protocol_id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
