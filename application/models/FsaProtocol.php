<?php

/**
 * @desc ФСА протокол - АПИ для Россаккредитации - протоколы
 * Class FsaProtocol
 */
class FsaProtocol extends Fsa
{
    private $filePath = UPLOAD_DIR . "/fsa/protocols/";

    /**
     * @param $idXmlProtocol
     * @return array
     */
    public function send($idXmlProtocol)
    {
        $protocol = $this->DB->Query("select * from ulab_xml_protocol where id = {$idXmlProtocol}")->Fetch();

        if ( empty($protocol['id']) ) {
            return [
                'success' => false,
                'error' => "Протокол не найден"
            ];
        }

        $guidFile = [];

        foreach ([$protocol['file_xml'], $protocol['file_sig']] as $file) {
            $error = false;
            for ($i = 0; $i < 3; $i++) {
                $result = $this->sendFile($this->filePath . $file);

                $_SESSION['message_warning'] = '<pre>' . print_r($result, true);

                if ( empty($result) || is_array(json_decode($result, true)) ) {
                    $error = true;
                    sleep(1);
                } else {
                    $error = false;
                    $guidFile[] = $result;
                    break;
                }
            }

            if ( $error ) {
                return [
                    'success' => false,
                    'error' => "Файл {$file} не удалось отправить. " . $result
                ];
            }
        }

        $guid = $this->generateGUID();

        $request = '';
        $match = [];
        $error = false;
        for ($i = 0; $i < 3; $i++) {
            $request = $this->sendRequest($guidFile[0], $guidFile[1], $guid, "protocolsResearch");

            preg_match('#HTTP\/1\.1 (\d+)#', $request, $match);

            if ( !in_array($match[1], [200, 201]) ) {
                $error = true;

                sleep(1);
            } else {
                $error = false;
                break;
            }
        }

        if ( $error ) {
            return [
                'success' => false,
                'error' => "Не удалось отправить данные отправить в ФСА. " . $match[0] . ". Повторите попытку позже"
            ];
        }

        $response['request'] = $request;

        sleep(1);

        $response['result'] = $this->getResults($guid, "protocolsResearch");

        $this->saveHistory($guid, $guidFile[0], $guidFile[1], "protocolsResearch", $protocol['file_xml'], $response['result']);

        return [
            'success' => true,
            'data' => $response
        ];
    }


    /**
     * Создать XML протокола для аккредитации
     * @param $protocolId
     * @return array|bool[]
     */
    public function createXMLProtocol($protocolId)
    {
        $protocolModel = new Protocol();
        $companyModel = new Company();
        $requestModel = new Request();

        $settings = $this->getSettings();

        if (
            empty($settings['acc_person_user_id'])
            || empty($settings['acc_person_address_id'])
            || empty($settings['acc_person_address_name'])
        ) {
            return [
                'success' => false,
                'error' => 'Не заполнены <a href="/ulab/fsa/settings/">настройки</a> (ид пользователя из РАЛ, адрес, ид адреса из РАЛ)'
            ];
        }

        $protocolInfo = $protocolModel->getProtocolById($protocolId);

        if ( empty($protocolInfo) ) {
            return [
                'success' => false,
                'error' => "Не найден протокол: ({$protocolId})"
            ];
        }

        $dealId = $requestModel->getDealIdByTzId((int)$protocolInfo['ID_TZ']);
        $dealInfo = $requestModel->getTzByDealId((int)$dealId);
        $companyInfo = $companyModel->getRequisiteByDealId((int)$dealId);
        $materialMethodInfo = $protocolModel->getDataForDoc($protocolId);




        $dealDateCreate = date("Y-m-d", strtotime($dealInfo['DATE_CREATE']));
        $dateProbe = date("Y-m-d", strtotime($dealInfo['DATE_ACT']));
        $yearProtocol = date("Y")%10 ? substr(date("Y", strtotime($protocolInfo['DATE'])), -2) : date("Y", strtotime($protocolInfo['DATE']));

        $guidAttachmentProtocolPdf = ''; //$this->sendFile(PROTOCOL_PATH . '.pdf');

        $objectInfo = '';
        foreach ($materialMethodInfo as $materialInfo) {
            $researchInfo = '';

            for ($i = 0; $i < count($materialInfo['gosts']['method']); $i++) {
                $value = json_decode($materialInfo['gosts']['result'][$i]['actual_value'], true)[0];
                if ( $value == '' ) { continue; }

                $name = mb_strimwidth($materialInfo['gosts']['method'][$i]['mp_name'], 0, 60);

                $researchInfo .= <<< XML
                    <tns:ResearchInfoDetails>
                        <tns:Indicator>
                            <tns:Id>{$materialInfo['gosts']['method'][$i]['mp_fsa_id']}</tns:Id>
                            <tns:Name>{$name}</tns:Name>
                        </tns:Indicator>
                        <tns:IndicatorFactValue>{$value}</tns:IndicatorFactValue>
                        <tns:Measurement>
                            <tns:Id>{$materialInfo['gosts']['method'][$i]['unit_fsa_id']}</tns:Id>
                        </tns:Measurement>
                        <tns:NormativeDoc>
                            <tns:DocId>3</tns:DocId>
                            <tns:Method>{$materialInfo['gosts']['method'][$i]['view_gost_for_protocol']}</tns:Method>
                        </tns:NormativeDoc>
                    </tns:ResearchInfoDetails>
XML;
            }

            $objectInfo .= <<< XML
                <tns:TypeObject>
                    <tns:Id>1</tns:Id>
                </tns:TypeObject>
                <tns:ModelGetDate>{$dateProbe}</tns:ModelGetDate>
                <tns:NameFull>{$materialInfo['material_name']}</tns:NameFull>
                <tns:SamplesCount>1</tns:SamplesCount>
                <tns:ResearchInfo>
                    {$researchInfo}
                </tns:ResearchInfo>
XML;
        }

        $attachmentProtocolPdf = '';
        if ( !empty($guidAttachmentProtocolPdf) ) {
            $attachmentProtocolPdf = <<< XML
            <tns:Protocol>
                <tns:AttachmentId>{$guidAttachmentProtocolPdf}</tns:AttachmentId>
            </tns:Protocol>
XML;
        }



        $xml = <<< XML
<?xml version="1.0" encoding="UTF-8"?>
<tns:Message xmlns:com="https://fsa.gov.ru/use-of-technology/common/types/1.0"
 xmlns:tns="https://fsa.gov.ru/use-of-technology/LimsProtocolsResearch/types/1.0"
 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:schemaLocation="https://fsa.gov.ru/use-of-technology/LimsProtocolsResearch/types/1.0">
    <tns:AccreditedPersonInfo>
        <tns:UserId>{$settings['acc_person_user_id']}</tns:UserId>
    </tns:AccreditedPersonInfo>
    <tns:ProtocolResearch>
        <tns:Number>{$protocolInfo['NUMBER_AND_YEAR']}</tns:Number>
        <tns:ProtocolDate>{$protocolInfo['DATE']}</tns:ProtocolDate>
        <tns:StartResearchDate>{$protocolInfo['DATE_BEGIN']}</tns:StartResearchDate>
        <tns:EndResearchDate>{$protocolInfo['DATE_END']}</tns:EndResearchDate>
		<tns:Address>
			<tns:AddressAccreditedPerson>
				<tns:Id>{$settings['acc_person_address_id']}</tns:Id>
				<tns:Name>{$settings['acc_person_address_name']}</tns:Name>
			</tns:AddressAccreditedPerson>
		</tns:Address>
        <tns:ApplicationDate>{$dealDateCreate}</tns:ApplicationDate>
        <tns:Customer>
            <tns:CustomerType>
                <tns:Id>dic_process_actor_type_1</tns:Id>
            </tns:CustomerType>
            <tns:Organization>
                <tns:NameFull>{$companyInfo['NAME']}</tns:NameFull>
                <tns:OGRN>{$companyInfo['RQ_OGRN']}</tns:OGRN>
                <tns:INN>{$companyInfo['RQ_INN']}</tns:INN>
            </tns:Organization>
        </tns:Customer>
        <tns:Equipments>
			<tns:NoEquipmentInfo>true</tns:NoEquipmentInfo>
        </tns:Equipments>
        <tns:ApprovedUsers>
            <tns:ApprovedUser>
                <tns:FullName>
                    <tns:Id>{$settings['approved_user_id']}</tns:Id>
                    <tns:Name>{$settings['approved_user_name']}</tns:Name>
                </tns:FullName>
                <tns:Position>
					<tns:Name>Руководитель</tns:Name>
                </tns:Position>
                <tns:Roles>
                    <tns:Role>
                        <tns:Id>1</tns:Id>
                    </tns:Role>
                </tns:Roles>
            </tns:ApprovedUser>
        </tns:ApprovedUsers>
        <tns:ObjectInfo>
            {$objectInfo}
        </tns:ObjectInfo>
    </tns:ProtocolResearch>
</tns:Message>
XML;

        $dateFile = date("mdHis");
        $fileName = "protocol_{$protocolInfo['NUMBER']}_{$yearProtocol}_{$dateFile}.xml";

        if ( file_put_contents($this->filePath . $fileName, $xml) === false ){
            return [
                'success' => false,
                'error' => "Не удалось сохранить файл ({$fileName}) в папку ({$this->filePath})"
            ];
        } else {
            $this->DB->Insert('ulab_xml_protocol', ['file_xml' => $this->quoteStr($fileName), 'date' => 'NOW()', 'id_protocol' => $protocolId]);
        }

        return ['success' => true];
    }


    public function getDataToJournalXml($filter)
    {
        $result = [];

        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                if ( isset($filter['search']['protocol_id']) ) {
                    $where .= "id_protocol = {$filter['search']['protocol_id']} AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {

                }
            }

            // работа с пагинацией
            if ( isset($filter['paginate']) ) {
                $offset = 0;
                // количество строк на страницу
                if ( isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0 ) {
                    $length = $filter['paginate']['length'];

                    if ( isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0 ) {
                        $offset = $filter['paginate']['start'];
                    }
                    $limit = "LIMIT {$offset}, {$length}";
                }
            }
        }
        $where .= "1 ";


        $data = $this->DB->Query(
            "SELECT `id`, `date`, `file_xml`, `file_sig`
                    FROM ulab_xml_protocol
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_xml_protocol
                    WHERE 1"
        )->Fetch();
        $dataFiltered = $this->DB->Query(
            "SELECT count(*) val
                    FROM ulab_xml_protocol
                    WHERE {$where}"
        )->Fetch();

        while ($row = $data->Fetch()) {
            $row['date'] = date('d.m.Y', strtotime($row['date']));
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal['val'];
        $result['recordsFiltered'] = $dataFiltered['val'];

        return $result;
    }


    public function uploadSigProtocol($file, $protocolId)
    {
        $protocol = $this->DB->Query("select * from ulab_xml_protocol where id = {$protocolId}")->Fetch();

        if ( empty($protocol['id']) ) {
            return [
                'success' => false,
                'error' => "Протокол не найден"
            ];
        }

        if ( $protocol['file_xml'] . '.sig' != $file["name"] ) {
            return [
                'success' => false,
                'error' => "Некорректное имя файла. Имя должно быть: {$protocol['file_xml']}.sig"
            ];
        }

        $result = $this->saveFile($this->filePath, $file["name"], $file["tmp_name"]);

        if ( $result['success'] ) {
            $name = $this->quoteStr($protocol['file_xml'] . '.sig');
            $this->DB->Update('ulab_xml_protocol', ['file_sig' => $name], "where id = {$protocolId}");
        }

        return $result;
    }


    /**
     * @param $id
     * @return array|false
     */
    public function getData($id)
    {
        $data = $this->DB->Query("select * from ulab_xml_protocol where id = {$id}")->Fetch();

        if ( empty($data) ) {
            return [];
        }

        $data['url_xml'] = UPLOAD_URL . '/fsa/protocols/' . $data['file_xml'];

        return $data;
    }


    /**
     * @param $file
     * @return string
     */
    public function getBase64File($file)
    {
        $file = file_get_contents($this->filePath . $file);

        return base64_encode($file);
    }


    /**
     * @param $id
     * @param $text
     * @return array|bool[]
     */
    public function saveSig($id, $text)
    {
        $data = $this->getData($id);

        if ( empty($data) ) {
            return [
                'success' => false,
                'error' => "Не удалось получить данные"
            ];
        }


        $fileFull = $this->filePath . $data['file_xml'] . '.sig';

        $result = file_put_contents($fileFull, $text);

        if ( empty($result) ) {
            return [
                'success' => false,
                'error' => "Не удалось создать файл: {$fileFull}"
            ];
        }

        $this->DB->Update('ulab_xml_protocol', ['file_sig' => $this->quoteStr($data['file_xml'] . '.sig')], "where id = {$id}");

        return [
            'success' => true,
            'protocol_id' => $data['id_protocol']
        ];
    }
}