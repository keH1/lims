<?php

/**
 * @desc ФСА - АПИ для Россаккредитации
 * Class FsaController
 */
class FsaController extends Controller
{
    /**
     * @desc Начальная страница
     */
    public function index()
    {
        $this->data['title'] = 'Модуль ФСА';

        
        $this->view('index');
    }


    /**
     * @desc Страница настроек
     */
    public function settings()
    {
        $this->data['title'] = 'Настройки';

        /** @var Fsa $fsaModel */
        $fsaModel = $this->model('Fsa');

        $this->data['form'] = $fsaModel->getSettings();

        $this->view('settings');
    }


    /**
     * @desc Страница создания XML протоколов
     */
    public function protocol($id = '')
    {
        $this->data['title'] = 'Подготовка к отправке протокола';

        /** @var Protocol $protocolModel */
        $protocolModel = $this->model('Protocol');

        $this->data['protocol_id'] = $id;

        $this->data['protocol_list'] = $protocolModel->getList();

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');
        $this->addJs('/assets/js/fsa/fsa-protocol.js?v=' . rand());

        $this->view('protocol');
    }

    /**
     * @desc Отправляет протокол в Росаккредитацию
     * @param $id
     */
    public function sendProtocol($id)
    {
        /** @var FsaProtocol $fsaProtocolModel */
        $fsaProtocolModel = $this->model('FsaProtocol');

        $data = $fsaProtocolModel->getData($id);

        $resultSend = $fsaProtocolModel->send($id);

        if ( $resultSend['success'] ) {
            $this->showSuccessMessage("Протокол успешно отправлен. В личном кабинете Росаккредитации протокол находится в черновиках.");
        } else {
            $this->showErrorMessage($resultSend['error']);
        }

        $this->redirect('/fsa/protocol/' . $data['id_protocol']);
    }

    /**
     * @desc Страница «Электронная подпись»
     */
    public function sigProtocol($id = '')
    {
        if (empty($id)) {
            $this->redirect('/fsa/protocol/');
        }

        $this->data['title'] = 'Электронная подпись';

        /** @var FsaProtocol $fsaProtocolModel */
        $fsaProtocolModel = $this->model('FsaProtocol');

        $data = $fsaProtocolModel->getData($id);

        $this->data['file_name'] = $data['file_xml'];
        $this->data['url_xml'] = $data['url_xml'];

        $this->data['protocol_xml_id'] = $id;
        $this->data['protocol_id'] = $data['id_protocol'];

        $this->data['file_base64'] = $fsaProtocolModel->getBase64File($data['file_xml']);

        $this->addJS("/assets/plugins/EDS/cadesplugin_api.js");
        $this->addJS("/assets/plugins/EDS/Code.js");

        $this->addJS("/assets/js/fsa/fsa-sig.js");

        $this->view('sig');
    }


    /**
     * @desc Метод обновления настроек
     */
    public function updateSettings()
    {
        /** @var Fsa $fsaModel */
        $fsaModel = $this->model('Fsa');

        $fsaModel->setSettings($_POST['form']);

        $this->showSuccessMessage("Настройки сохранены");
        $this->redirect('/fsa/settings/');
    }


    /**
     * @desc Страница выбора и отправки протокола
     */
    public function createXMLProtocol()
    {
        /** @var FsaProtocol $fsaModel */
        $fsaModel = $this->model('FsaProtocol');

        $resultCreate = $fsaModel->createXMLProtocol((int)$_POST['protocol_id']);

        if ( $resultCreate['success'] ) {
            $this->showSuccessMessage("XML протокола создан");
            $this->redirect('/fsa/protocol/'.$_POST['protocol_id']);
        } else {
            $this->showErrorMessage($resultCreate['error']);
            $this->redirect('/fsa/protocol/');
        }
    }

    /**
     * @desc Тестовая отправка протокола
     */
    public function test()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $this->data['title'] = 'Тестовая отправка протокола';

        /** @var Fsa $fsa */
        $fsa = $this->model('Fsa');

        $guid = $fsa->generateGUID();

        $xmlFile = "protocol_44_24_0116090914.xml";
        $sigFile = "{$xmlFile}.sig";

        echo date('d.m.Y H:i:s');
        echo '<br>';
        echo "Адрес: http://5.143.238.171:8080 <br>";
        echo 'guid: ' . $guid . '<br><br>';


        echo "Отправляем '{$xmlFile}' <br>";

        // отправляем первый файл
        $sendXml = $fsa->sendFile(UPLOAD_DIR . "/fsa/protocols/" . $xmlFile);

        echo "Ответ: {$sendXml} <br><br>";

        if ( empty($sendXml) ) {
            $sendXml = $fsa->sendFile(UPLOAD_DIR . "/fsa/protocols/" . $xmlFile, true);
            echo "Ошибка: ответ не получен <br><pre>";
            var_dump($sendXml);
            echo "<br>";
            exit;
        }

        sleep(1);

        echo "Отправляем '{$sigFile}' <br>";

        // отправляем второй файл
        $sendSig = $fsa->sendFile(UPLOAD_DIR . "/fsa/protocols/" . $sigFile);

        echo "Ответ: {$sendSig} <br><br>";

        if ( empty($sendSig) ) {
            $sendSig = $fsa->sendFile(UPLOAD_DIR . "/fsa/protocols/" . $sigFile, true);
            echo "Ошибка: ответ не получен <br><pre>";
            var_dump($sendSig);
            echo "<br>";
            exit;
        }

        sleep(1);

        echo "Отправляем протокол <br>";

        // отправляем протокол
        $sendProtocol = $fsa->sendRequest($sendXml, $sendSig, $guid, "protocolsResearch");

//        $fsa->saveHistory($guid, $sendXml, $sendSig, "protocolsResearch", $xmlFile, $sendProtocol['result']);

        echo '<pre>';
        var_dump($sendProtocol);
        echo '</pre><hr>';
    }


    public function last()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Fsa $fsa */
        $fsa = $this->model('Fsa');

        echo '<pre>';
        var_dump(json_decode($fsa->getResultLastRequest(), true));
        echo '</pre>';
    }


    /**
     * @desc История запросов
     */
    public function list()
    {
        $this->data['title'] = 'История запросов';

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");

        $this->addJs('/assets/js/fsa/fsa-list.js?v=' . rand());

        $this->view('list');
    }


    /**
     * @desc Получает данные для истории запросов
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Fsa $fsa */
        $fsa = $this->model('Fsa');

        $filter = $fsa->prepareFilter($_POST ?? []);

        $data = $fsa->getDataToJournalHistory($filter);

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
     * @desc Получает данные для таблицы созданных XML протоколов
     */
    public function getListXmlAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var FsaProtocol $fsa */
        $fsa = $this->model('FsaProtocol');

        $filter = $fsa->prepareFilter($_POST ?? []);

        if ( !empty($_POST['protocol_id']) ) {
            $filter['search']['protocol_id'] = (int)$_POST['protocol_id'];
        }

        $data = $fsa->getDataToJournalXml($filter);

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
     * @desc Загружает файл подписи
     */
    public function uploadSigProtocolAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var FsaProtocol $fsaProtocolModel */
        $fsaProtocolModel = $this->model('FsaProtocol');

        $resultUpload = $fsaProtocolModel->uploadSigProtocol($_FILES["file"], (int)$_POST['protocol_id']);

        echo json_encode($resultUpload, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Сохраняет файл подписи
     */
    public function saveSigAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var FsaProtocol $fsaProtocolModel */
        $fsaProtocolModel = $this->model('FsaProtocol');

        $result = $fsaProtocolModel->saveSig((int)$_POST['id'], $_POST['sign']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
