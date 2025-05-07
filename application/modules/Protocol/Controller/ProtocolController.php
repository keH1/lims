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

        $r = rand();
        $this->addJs("/assets/js/protocol-list.js?v={$r}");

        $this->view('list', '', 'template_journal');
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

        $result = $protocolModel->validateProtocol((int)$_POST['protocol_id']);

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
