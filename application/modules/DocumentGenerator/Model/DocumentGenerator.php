<?php

use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Field;
use NcJoes\OfficeConverter\OfficeConverter;
use \Bitrix\Main\Loader;

class DocumentGenerator extends Model
{
    // ширина документа в твипах
    protected const WIDTH_DOC_TWIP = 9780;
    // ширина документа альбомная версия в твипах
    protected const WIDTH_DOC_ALBUM_TWIP = 14428;

    public function __construct()
    {
        parent::__construct();

        Loader::includeModule('documentgenerator');
    }

    /**
     * @param int $percent
     * @param bool $isAlbum
     * @return float|int
     */
    protected static function percentToTwips($percent = 100, $isAlbum = false)
    {
        if ($percent <= 0) {
            return 0;
        }

        $width = $isAlbum ? self::WIDTH_DOC_ALBUM_TWIP : self::WIDTH_DOC_TWIP;

        if ($percent >= 100) {
            return $width;
        }

        return $width * ($percent / 100);
    }

    /**
	 * * Подготовка данных для коммерческого предложения
     * @param $dealID
     */
	public function commercialOffer($dealID)
	{
		$companyModel = new Company();
		$requestModel = new Request();
		$historyModel = new History();
		$userModel = new User();
		$gostModel = new Gost();

		$companyInformation = $companyModel->getRequisiteByDealId($dealID);

		$dealInformation = $requestModel->getTzByDealId($dealID);

		$TZ_ID = $dealInformation['ID'];

		$res_kp = $this->DB->Query("SELECT * FROM `KP` WHERE `TZ_ID`=" . $TZ_ID)->Fetch();

		$date = date('Y-m-d H:i:s');

		$dateKP = date('d-m-Y_H-i-s', strtotime($date));

		$curdate = "КП_" . $dateKP;
		$curdateEng = "KP_" . $dateKP;

		if ($res_kp) {
			$this->DB->Query("UPDATE `KP` SET `DATE`= NOW(), `ACTUAL_VER` ='" . $curdate . "' WHERE `TZ_ID`=" . $TZ_ID);
			$str_type = 'Обновление коммерческого предложения';
		} else {
			$this->DB->Query("INSERT INTO `KP` (`DATE`, `TZ_ID`, `ACTUAL_VER`) VALUES (NOW(), " . $TZ_ID . ", '" . $curdate . "')");
			$str_type = 'Формирование коммерческого предложения';
		}

		$currentUser = $userModel->getCurrentUser();

		$history = [
			'DATE' => $date,
			'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
			'TZ_ID' => $TZ_ID,
			'USER_ID' => $currentUser['ID'],
			'TYPE' => $str_type,
			'REQUEST' => $dealInformation['REQUEST_TITLE'],
		];

		$historyModel->addHistory($history);

		$str_num_request = !empty($dealInformation['REQUEST_TITLE']) ? explode(' ', $dealInformation['REQUEST_TITLE'])[1] : '';

        $res_kp = $this->DB->Query("SELECT * FROM `KP` WHERE `TZ_ID`=" . $TZ_ID)->Fetch();

		$CommInformation = [
			'id' => $dealID,
			'num_request' => $str_num_request,
			'date' => $res_kp['DATE'],
			'curDate' => $curdate,
			'curDateEn' => $curdateEng,
			'SUM' => $dealInformation['price_discount'],
			'NUM_KP' => $res_kp['ID'],
			'NUM_DATE' => date('d.m.Y', strtotime($res_kp['DATE'])),
			'NUM_REQUEST' => !empty($str_num_request) ? 'ПО ЗАЯВКЕ ' . $str_num_request : '',
			'CLIENT_NAME' => $companyInformation['NAME'],
			'COMMENT_KP' => !empty($dealInformation['COMMENT_KP']) ? $dealInformation['COMMENT_KP'] : '',
		];

		$arrGost = $gostModel->getGostMaterialByDealID($dealID);

		$this->createCommercialOfferDocument($CommInformation, $arrGost);
	}

	/**
	 * Подготовка данных для приложения к договору
	 * @param $dealID
	 */
	public function technicalSpecification($dealID)
    {
        $companyModel = new Company();
        $requestModel = new Request();
        $historyModel = new History();
        $userModel = new User();
        $gostModel = new Gost();
        $orderModel = new Order();
		$requirementModel = new Requirement();

        $companyInformation = $companyModel->getRequisiteByDealId($dealID);

        $dealInformation = $requestModel->getTzByDealId($dealID);

        $TZ_ID = $dealInformation['ID'];

        $date = date('Y-m-d H:i:s');

        $dateTZ = date('d-m-Y_H-i-s', strtotime($date));

        $curdate = "ТЗ_".$TZ_ID."_".$dateTZ;
        $curdateEng = "TZ_".$TZ_ID."_".$dateTZ;

        $res_tz = $this->DB->Query("SELECT * FROM `TZ_DOC` WHERE `TZ_ID`=" . $TZ_ID)->Fetch();
        if($res_tz['ID']) {
            $this->DB->Query("UPDATE `TZ_DOC` SET `DATE`= NOW(), `ACTUAL_VER` ='" . $curdate . "' WHERE `TZ_ID`=" . $TZ_ID);
            $str_type = 'Обновление приложения к договору (ТЗ)';
        } else {
            $this->DB->Query("INSERT INTO `TZ_DOC` (`DATE`, `TZ_ID`, `ACTUAL_VER`) VALUES (NOW(), " . $TZ_ID . ", '" . $curdate . "')");
            $str_type = 'Формирование приложения к договору (ТЗ)';
        }

        $currentUser = $userModel->getCurrentUser($dealID);

        $history = [
            'DATE' => $date,
            'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
            'TZ_ID' => $TZ_ID,
            'USER_ID' => $currentUser['ID'],
            'TYPE' => $str_type,
            'REQUEST' => $dealInformation['REQUEST_TITLE'],
        ];

        $historyModel->addHistory($history);

        if($dealInformation['DAY_TO_TEST']){
            $cur = date('d.m.Y'); //текущая дата
            switch ($dealInformation['type_of_day']) {
                case 'day':
                    $typeDay = StringHelper::num_word($dealInformation['DAY_TO_TEST'], 'день', 'дня', 'дней'); break;
                case 'work_day':
                    $typeDay = StringHelper::num_word($dealInformation['DAY_TO_TEST'], 'рабочий день', 'рабочих дня', 'рабочих дней'); break;
                case 'month':
                    $typeDay = StringHelper::num_word($dealInformation['DAY_TO_TEST'], 'месяц', 'месяца', 'месяцев'); break;
                default: $typeDay = '';
            }

            $current_day = $dealInformation['DAY_TO_TEST'] . ' ' . $typeDay; //кол-во пришедших дней

            $day_to_test_text = "Сроки проведения испытаний составляет " . $current_day . " с момента поступления проб (образцов) в ИЦ, подписания договора и оплаты испытаний.";
            $day_to_test_note = 'Сроки являются ориентировочными и могут быть скорректированы по согласованию сторон.';
        }else{
            $day_to_test_text = '';
            $day_to_test_note = '';
        }

        $str_num_request = !empty($dealInformation['REQUEST_TITLE']) ? explode(' ', $dealInformation['REQUEST_TITLE'])[1] : '';

        $res_tz = $this->DB->Query("SELECT * FROM `TZ_DOC` WHERE `TZ_ID`=" . $TZ_ID)->Fetch();

        $dogovor_num = $orderModel->getOrderByDealId($dealID);

        $actProbe = $requirementModel->getActBase($dealID);

        $placeProbe = ! empty($actProbe['PLACE_PROBE']) ? trim($actProbe['PLACE_PROBE']) . ';' : '-';
        $dateProbe = !empty($actProbe['DATE_PROBE']) ? date('d.m.Y', strtotime($actProbe['DATE_PROBE'])) : '';

        $placeDateStr = "{$placeProbe} {$dateProbe}";

        $sumPrice = !empty($dealInformation['DISCOUNT']) ? 'с учетом скидки:' . $dealInformation['price_discount'] : $dealInformation['price_discount'];

        $TZInformation = [
            'id' => $TZ_ID,
            'num_request' => !empty($str_num_request) ? '(Заявка ' . $str_num_request . ')' : '',
            'nDogovor' => $dogovor_num,
            'nZakazchik' => $companyInformation['NAME'],
            'innZakazchik' => $companyInformation['RQ_INN'],
            'ogrnZakazchik' => $companyInformation['RQ_OGRN'],
            'aZakazchik' => $companyInformation['RQ_ACCOUNTANT'],
            'Phone' => 'тел: ' . $companyInformation['RQ_PHONE'],
            'Email' => 'E-mail: ' . $companyInformation['RQ_FIRST_NAME'],
            'oStroit' => !empty($dealInformation['OBJECT']) ? $dealInformation['OBJECT'] : '-',
            'DAY_TO_TEST_TEXT' => $day_to_test_text,
            'DAY_TO_TEST_NOTE' => $day_to_test_note,
            'dProbe' => ($dealInformation['DATE_ACT'] ? date("d.m.Y", strtotime($dealInformation['DATE_ACT'])) : '-'),
            'date' => $res_tz['DATE'],
            'curDate' => $curdate,
            'curDateEn' => $curdateEng,
            'SUM' => $sumPrice,
            'NUM_REQUEST' => !empty($str_num_request) ? 'ПО ЗАЯВКЕ ' . $str_num_request : '',
            'CLIENT_NAME' => $companyInformation['NAME'],
            'COMMENT_TZ' => !empty($dealInformation['COMMENT_TZ']) ? $dealInformation['COMMENT_TZ'] : '',
			'mestoSboraProbe' => $placeDateStr,
        ];

        $arrGost = $gostModel->getGostMaterialByDealID($dealID);
        $this->createTechnicalSpecificationDocument($TZInformation, $arrGost);
    }


    /**
     * Результаты испытания пробы асфальтобетонной смеси
     * @param $protocolId
     */
    public function conclusionDocument($protocolId)
    {
        $materialModel = new Material();
        $normDocModel = new NormDocGost();
        $protocolModel = new Protocol();
        $methodModel = new Methods();

        $protocolInfo = $protocolModel->getProtocolById($protocolId);

        $ex = [
            'less_or_equal' => 'не более',
            'more_or_equal' => 'не менее',
            'more' => 'от',
            'less' => 'до',
        ];


        $sql = $this->DB->Query(
            "select 
                        ugtp.*, umtr.material_id, umtr.group as material_group_id
                    from 
                        ulab_gost_to_probe as ugtp
                    inner join 
                        ulab_material_to_request as umtr on umtr.id = ugtp.material_to_request_id 
                    where
                        ugtp.protocol_id = {$protocolId} and ugtp.norm_doc_method_id > 0 and umtr.group > 0"
        );


        $isConfirmAll = true;

        $data = [];

        while ($row = $sql->Fetch()) {
            $infoGroupMaterial = $materialModel->getGroupMaterialByNormDoc($row['norm_doc_method_id'], $row['material_group_id'])[0];
            $normDocInfo = $normDocModel->getMethod($row['norm_doc_method_id']);
            $methodInfo = $methodModel->get($row['method_id']);

            if ( empty($infoGroupMaterial) ) { continue; }

            $ndGostId = $normDocInfo['gost_id'];
            $regDoc = $normDocInfo['reg_doc'];
            $gostNameYear = $normDocInfo['view_name_year'];
            $viewName = $normDocInfo['view_name'];
            $methodName = $normDocInfo['name'];
            $materialGroupId = $row['material_group_id'];
            $materialGroupName = $infoGroupMaterial['group_name'];
            $resultValue = $row['actual_value'];
            $resultValueStr = number_format($row['actual_value'], $methodInfo['decimal_places']?? 0, ',', '');
            $rangeStr = "";
            $isConfirm = true;

            $val1 = number_format($infoGroupMaterial['val_1'], $normDocInfo['decimal_places']?? 0, ',', '');
            $val2 = number_format($infoGroupMaterial['val_2'], $normDocInfo['decimal_places']?? 0, ',', '');

            // текст диапазона
            if (is_null($infoGroupMaterial['val_1']) && is_null($infoGroupMaterial['val_2'])) {
                $rangeStr .= "–";
            } elseif ( !$infoGroupMaterial['no_val_1'] && !$infoGroupMaterial['no_val_2'] && !is_null($infoGroupMaterial['val_1']) && !is_null($infoGroupMaterial['val_2']) ) {
                $rangeStr .= "{$val1} – {$val2}";
            } elseif ( $infoGroupMaterial['no_val_1'] && $infoGroupMaterial['no_val_2'] ) {
                $rangeStr = "–";
            } elseif ( !$infoGroupMaterial['no_val_1'] || is_null($infoGroupMaterial['val_1']) ) {
                $rangeStr .= "{$ex[$infoGroupMaterial['comparison_val_1']]} {$val1} ";
            } elseif ( !$infoGroupMaterial['no_val_2'] || is_null($infoGroupMaterial['val_2']) ) {
                $rangeStr .= "{$ex[$infoGroupMaterial['comparison_val_2']]} {$val2}";
            }


            // сравниваем актуальное значение с диапазоном
            for ( $i = 1; $i < 3; $i++ ) {
                if ( !$infoGroupMaterial["no_val_{$i}"] ) {
                    if ( $infoGroupMaterial["comparison_val_{$i}"] == 'less_or_equal' ) {
                        $isConfirm &= (float)$resultValue <= (float)$infoGroupMaterial["val_{$i}"];
                    } elseif ( $infoGroupMaterial["comparison_val_{$i}"] == 'more_or_equal' ) {
                        $isConfirm &= (float)$resultValue >= (float)$infoGroupMaterial["val_{$i}"];
                    } elseif ( $infoGroupMaterial["comparison_val_{$i}"] == 'more' ) {
                        $isConfirm &= (float)$resultValue > (float)$infoGroupMaterial["val_{$i}"];
                    } elseif ( $infoGroupMaterial["comparison_val_{$i}"] == 'less' ) {
                        $isConfirm &= (float)$resultValue < (float)$infoGroupMaterial["val_{$i}"];
                    }
                }
            }

            if ( !$isConfirm ) {
                $isConfirmAll = false;
            }

            $data["{$materialGroupId}_{$ndGostId}"]['reg_doc'] = $regDoc;
            $data["{$materialGroupId}_{$ndGostId}"]['gost_name_year'] = $gostNameYear;
            $data["{$materialGroupId}_{$ndGostId}"]['group_name'] = $materialGroupName;
            $data["{$materialGroupId}_{$ndGostId}"]['is_confirm_all'] = (int)$isConfirmAll;

            $data["{$materialGroupId}_{$ndGostId}"]['method_data'][$normDocInfo['id']]['method_name'] = $methodName;
            $data["{$materialGroupId}_{$ndGostId}"]['method_data'][$normDocInfo['id']]['view_name'] = $viewName;
            $data["{$materialGroupId}_{$ndGostId}"]['method_data'][$normDocInfo['id']]['actual_value'] = $resultValue;
            $data["{$materialGroupId}_{$ndGostId}"]['method_data'][$normDocInfo['id']]['actual_value_str'] = $resultValueStr;
            $data["{$materialGroupId}_{$ndGostId}"]['method_data'][$normDocInfo['id']]['range_str'] = $rangeStr;
            $data["{$materialGroupId}_{$ndGostId}"]['method_data'][$normDocInfo['id']]['is_confirm'] = (int)$isConfirm;
        }


        // создаем документ
        $templateDoc = $_SERVER['DOCUMENT_ROOT'] .  '/protocol_generator/result_test_asphalt.docx';

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $template = new \PhpOffice\PhpWord\TemplateProcessor($templateDoc);

        $styleTable = array('alignment' =>'center', 'borderSize' => 5, 'borderColor' => '000000');
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        $cellColSpan4 = array('gridSpan' => 4, 'valign' => 'center');
        $cellColSpan6 = array('gridSpan' => 6, 'valign' => 'center');

        $cellHCentered = array('align' => 'center');
        $cellVCentered = array('valign' => 'center');

        $section = $phpWord->addSection();
        $table = $section->addTable($styleTable);

        $table->addRow(null, array('tblHeader' => true));

        $table->addCell(2000, $cellRowSpan)->addText('Тип Марка', array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(3000, $cellRowSpan)->addText('Наименование показателей', array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(2000, $cellColSpan2)->addText('Значение показателей', array('size' => 10, 'bold' => true), $cellHCentered);

        $table->addRow(null, array('tblHeader' => true));

        $table->addCell(2000, $cellRowContinue)->addText(null, array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(3000, $cellRowContinue)->addText(null, array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(2000, $cellRowSpan)->addText(reset($data)['gost_name_year'], array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(1500, $cellRowSpan)->addText('испытанной пробы', array('size' => 10, 'bold' => true), $cellHCentered);

        $confirmMethods = [];

        foreach ($data as $itemMater) {
            foreach ($itemMater['method_data'] as $k => $value) {
                $table->addRow();
                if ($k === array_key_first($itemMater['method_data'])) {
                    $table->addCell(null, $cellRowSpan)->addText($itemMater['group_name'], array('size' => 10, 'bold' => true), $cellHCentered);
                } else {
                    $table->addCell(null, $cellRowContinue);
                }
                $table->addCell()->addText($value['method_name'], array('size' => 10), $cellHCentered);
                $table->addCell()->addText($value['range_str'], array('size' => 10, 'bold' => true), $cellHCentered);
                $table->addCell()->addText($value['actual_value_str'], array('size' => 10, 'bold' => !$value['is_confirm']), $cellHCentered);

                if ( !$value['is_confirm'] ) {
                    $confirmMethods[] = "{$value['view_name']}";
                }
            }
        }

        $confirmMethodsStr = implode('; ', $confirmMethods);

        if ( !empty($confirmMethods) ) {
            $str = $section->addText('не соответствует', ['bold' => true], null);
            $template->setComplexBlock('ConditionsTitle', $str);
            $conclusionStr = "не соответствует требованиям {$confirmMethodsStr}";
        } else {
            $conclusionStr = "соответствует требованиям";
        }

        $param = [
            'conclusion' => $conclusionStr
        ];

        $template->setValues($param);

        $template->setComplexBlock('table', $table);

        $curDate = date('d.m.Y');
        $protocolYear = date('Y', strtotime($protocolInfo['DATE']));

        $nameFile = "Протокол_" . ($protocolInfo['NUMBER'] ?: '') . "_" . date("d-m-Y_H-i-s") . ".docx";
        $outputPath = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive/{$protocolInfo['ID_TZ']}{$protocolYear}/{$protocolInfo['ID']}/";

        if( !is_dir( $outputPath ) ) {
            mkdir($outputPath, 0777, true);
        }

        $template->saveAs($outputPath.$nameFile);

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="Заключение №' . $protocolInfo['NUMBER'] . ' от ' . $curDate . '.docx"');
        readfile($outputPath.$nameFile);
    }


	/**
	 * Подготовка данных для приложения к договору
	 * @param $dealID
	 */
	public function invoiceOfferGenerator($dealID)
	{
		$companyModel = new Company();
		$requestModel = new Request();
		$historyModel = new History();
		$userModel = new User();
		$gostModel = new Gost();
		$orderModel = new Order();
		$requirementModel = new Requirement();

		$companyInformation = $companyModel->getRequisiteByDealId($dealID);

		$dealInformation = $requestModel->getTzByDealId($dealID);

		$TZ_ID = $dealInformation['ID'];

		$date = date('Y-m-d H:i:s');

		$dateTZ = date('d-m-Y_H-i-s', strtotime($date));

		$curdate = "SO_".$TZ_ID."_".$dateTZ;
		$curdateEng = "SO_".$TZ_ID."_".$dateTZ;

		$res_deals = $orderModel->getContractDealByDealId($dealID);

		if (!empty($res_deals['ID_CONTRACT'])) {
			$res_dogovor = $this->DB->Query("SELECT * FROM `DOGOVOR` WHERE `ID`= {$res_deals['ID_CONTRACT']}")->Fetch();
		}

		if($res_dogovor['ID']) {
			$this->DB->Query("UPDATE `DOGOVOR` SET `ACTUAL_VER` ='{$curdateEng}' WHERE `TZ_ID`= {$TZ_ID}");
			$str_type = 'Обновление Счета-оферты';
		} else {
//		    $this->pre($this->quoteStr($curdateEng));
            $curYear = date('y');
		    $number = $orderModel->getCurrentNumber() . '/' . $curYear;
            $curdate = "SO_{$number}_{$dateTZ}";
            $data = [
                'DATE' => $this->quoteStr($date),
                'TZ_ID' => $TZ_ID,
                'ACTUAL_VER' => $this->quoteStr($curdateEng),
                'CONTRACT_TYPE' => $this->quoteStr('Счет-оферта'),
                'NUMBER' => $this->quoteStr($number),
                'DEAL_ID' => $dealID,
            ];

			$id = $this->DB->Insert('DOGOVOR', $data);

			$str_type = 'Формирование Счета-Оферты';
            $invoice = "Счет-оферта №{$number} от " . StringHelper::dateRu($date);

			$ba_tz = [
                'DOGOVOR_NUM' => $id,
                'DOGOVOR_TABLE' => $this->quoteStr($invoice),
            ];

            $this->DB->Update('ba_tz', $ba_tz, "where ID_Z = {$dealID}");

            $orderModel->setContractToRequest($dealID, $id);
		}

        //работа с 1С

        $iin_user_1c = !empty($companyInformation['RQ_INN']) ? $companyInformation['RQ_INN'] : '';

        $company_title = !empty($companyInformation['NAME']) ? $companyInformation['NAME'] : '';

        $res_gogovor = $this->DB->Query("SELECT * FROM `DOGOVOR` WHERE `ID`= {$res_dogovor['ID']}")->Fetch();

        $number_dogovor_1c = !empty($res_gogovor['NUMBER']) ? 'Cчет-оферта ' . $res_gogovor['NUMBER'] : '';

        $date_dogovor_1c = !empty($res_gogovor['DATE']) ? date("d.m.Y", strtotime($res_gogovor['DATE'])) : '';

        $kpp_1c = !empty($companyInformation['RQ_KPP']) ? $companyInformation['RQ_KPP'] : '';

        $price_account_1c = $dealInformation['price_discount'];

        $arr_data_1c = [
            "fl" => 0,
            "number" => $number_dogovor_1c,
            "date" => $date_dogovor_1c,
            "inn" => $iin_user_1c,
            "summ" => $price_account_1c,
            "title" => $company_title,
            "n_dogovor" => $number_dogovor_1c,
            "d_dogovor" => $date_dogovor_1c,
            "kpp" => $kpp_1c
        ];

        $res_select_requests_for_1c = $this->DB->Query("SELECT * FROM `REQUESTS_FOR_1C` WHERE `ID` = 1")->Fetch();

        if (!empty($res_select_requests_for_1c['REQ'])) {
            $arrReq = !empty($res_select_requests_for_1c['REQ']) ? unserialize($res_select_requests_for_1c['REQ']) : '';

            $check_true = 1;
            if (isset($arrReq['requests'])) {
                foreach ($arrReq['requests'] as $key_request => $val_request) {

                    if (!empty($val_request['number']) && $number_dogovor_1c == $val_request['number']) {
                        $check_true = 0;
                    }

                }
            }

            if (isset($arrReq['requests']) && !empty($check_true) && $check_true == 1 && !empty($number_dogovor_1c)) {
                array_push($arrReq['requests'], $arr_data_1c);
                $this->DB->Query("UPDATE `REQUESTS_FOR_1C` SET `REQ`='" . serialize($arrReq) . "' WHERE `ID` = 1");
            }

        } else {
            $arrReq['requests'] = [];

            if (!empty($number_dogovor_1c)) {
                array_push($arrReq['requests'], $arr_data_1c);
//                $this->pre("UPDATE `REQUESTS_FOR_1C` SET `REQ`='" . serialize($arrReq) . "' WHERE `ID` = 1");
                $this->DB->Query("UPDATE `REQUESTS_FOR_1C` SET `REQ`='" . serialize($arrReq) . "' WHERE `ID` = 1");
            }
        }


        //работа с 1С. Конец

		$currentUser = $userModel->getCurrentUser();

		$history = [
			'DATE' => $date,
			'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
			'TZ_ID' => $TZ_ID,
			'USER_ID' => $currentUser['ID'],
			'TYPE' => $str_type,
			'REQUEST' => $dealInformation['REQUEST_TITLE'],
		];

		$historyModel->addHistory($history);

		$str_num_request = !empty($dealInformation['REQUEST_TITLE']) ? explode(' ', $dealInformation['REQUEST_TITLE'])[1] : '';

		$res_tz = $this->DB->Query("SELECT * FROM `DOGOVOR` WHERE `TZ_ID`=" . $TZ_ID)->Fetch();

		$dogovor_num = $orderModel->getOrderByDealId($dealID);

		$actProbe = $requirementModel->getActBase($dealID);

		$placeProbe = ! empty($actProbe['PLACE_PROBE']) ? trim($actProbe['PLACE_PROBE']) . ';' : '-';
		$dateProbe = !empty($actProbe['DATE_PROBE']) ? date('d.m.Y', strtotime($actProbe['DATE_PROBE'])) : '';

		$placeDateStr = "{$placeProbe} {$dateProbe}";

		if($dealInformation['DAY_TO_TEST']){
			$cur = date('d.m.Y'); //текущая дата
			switch ($dealInformation['type_of_day']) {
				case 'day':
					$typeDay = StringHelper::num_word($dealInformation['DAY_TO_TEST'], 'день', 'дня', 'дней'); break;
				case 'work_day':
					$typeDay = StringHelper::num_word($dealInformation['DAY_TO_TEST'], 'рабочий день', 'рабочих дня', 'рабочих дней'); break;
				case 'month':
					$typeDay = StringHelper::num_word($dealInformation['DAY_TO_TEST'], 'месяц', 'месяца', 'месяцев'); break;
				default: $typeDay = '';
			}

			$current_day = $dealInformation['DAY_TO_TEST'] . ' ' . $typeDay; //кол-во пришедших дней

			$day_to_test_text = "Сроки проведения испытаний составляет " . $current_day . " с момента поступления проб (образцов) в ИЦ, подписания договора и оплаты испытаний.";
			$day_to_test_note = 'Сроки являются ориентировочными и могут быть скорректированы по согласованию сторон.';
		}else{
			$day_to_test_text = '';
			$day_to_test_note = '';
		}

		//??
		$str = StringHelper::num2str($dealInformation['price_discount']);

		$first = mb_substr($str,0,1, 'UTF-8');//первая буква
		$last = mb_substr($str,1);//все кроме первой буквы
		$first = mb_strtoupper($first, 'UTF-8');
		$last = mb_strtolower($last, 'UTF-8');
		$name1 = $first.$last;

		//
		$TZInformation = [
			'id' => $TZ_ID,
			'num_request' => !empty($str_num_request) ? $str_num_request : '',
			'nDogovor' => $dogovor_num,
			'nZakazchik' => $companyInformation['NAME'],
			'innZakazchik' => $companyInformation['RQ_INN'],
			'ogrnZakazchik' => $companyInformation['RQ_OGRN'],
			'aZakazchik' => $companyInformation['RQ_ACCOUNTANT'],
			'Phone' => 'тел: ' . $companyInformation['RQ_PHONE'],
			'Email' => 'E-mail: ' . $companyInformation['RQ_FIRST_NAME'],
			'oStroit' => $dealInformation['OBJECT'],
			'dTest' => $current_day,
			'DAY_TO_TEST_TEXT' => $day_to_test_text,
			'DAY_TO_TEST_NOTE' => $day_to_test_note,
			'dProbe' => ($dealInformation['DATE_ACT'] ? date("d.m.Y", strtotime($dealInformation['DATE_ACT'])) : '-'),
			'date' => $res_tz['DATE'],
			'curDate' => $curdate,
			'curDateEn' => $curdateEng,
			'SUM' => number_format($dealInformation['price_discount'], 2, ',', ''),
			'SUMr' => $dealInformation['price_ru'],
			'NUM_REQUEST' => !empty($str_num_request) ? 'ПО ЗАЯВКЕ ' . $str_num_request : '',
			'CLIENT_NAME' => $companyInformation['NAME'],
			'COMMENT_TZ' => !empty($dealInformation['COMMENT_TZ']) ? $dealInformation['COMMENT_TZ'] : '',
			'mestoSboraProbe' => $placeDateStr,
			'SumAllWord' => $name1,
		];

		//

		$arrGost = $gostModel->getGostMaterialByDealID($dealID);

		$this->invoiceOfferDocument($TZInformation, $arrGost);
	}

	/**
	 * Формирование таблицы phpword для Счет-оферты
	 * @param $info
	 * @param $arrGost
	 * @throws \NcJoes\OfficeConverter\OfficeConverterException
	 * @throws \PhpOffice\PhpWord\Exception\CopyFileException
	 * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
	 */
	public function invoiceOfferDocument($info, $arrGost)
	{
		$template_doc = $_SERVER["DOCUMENT_ROOT"] . '/OfferInvoice.docx';

		$type = 'dog';
		$countM = [];
		$result = [];
		foreach ($arrGost as $key => $val) {
			$countM[] = $val['m_id'];
			$result[$val['material_id']]['material_name'] = $val['material_name'];
			$result[$val['material_id']]['gosts'][] = [
				'method_name' => $val['method_name'],
				'gost_name' => $val['gost_name'],
				'price' => $val['price'],
				'tech_condition' => $val['tech_condition'] ? $val['tech_condition'] : '-',
				'sum' => $val['sum'],
				'amount' => $val['amount'],
			];
		}

		$info['countM'] = count(array_unique($countM));

		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$template = new \PhpOffice\PhpWord\TemplateProcessor($template_doc);

		$styleTable = array('alignment' =>'center', 'borderSize' => 5, 'borderColor' => '000000');
		$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
		$cellRowContinue = array('vMerge' => 'continue');
		$cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
		$cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
		$cellColSpan4 = array('gridSpan' => 4, 'valign' => 'center');
		$cellColSpan6 = array('gridSpan' => 6, 'valign' => 'center');

		$cellHCentered = array('align' => 'center');
		$cellVCentered = array('valign' => 'center');

		$section = $phpWord->addSection();
		$table = $section->addTable($styleTable);
		$table->addRow(null, array('tblHeader' => true));
		$table->addCell(2000, $cellRowSpan)->addText('Наименование объекта испытаний (пробы/образца)', array('size' => 10, 'bold' => true), $cellHCentered);
		$table->addCell(3000, $cellRowSpan)->addText('Определяемые характеристики', array('size' => 10, 'bold' => true), $cellHCentered);
		$table->addCell(2000, $cellRowSpan)->addText('Нормативный документ на метод испытания', array('size' => 10, 'bold' => true), $cellHCentered);
		$table->addCell(1500, $cellRowSpan)->addText('Нормативный документ на требования к объекту испытаний', array('size' => 10, 'bold' => true), $cellHCentered);
		$table->addCell(1500, $cellRowSpan)->addText('Цена за пробу (образец), руб.', array('size' => 10, 'bold' => true), $cellHCentered);
		$i = 1;
		$sumTotal = 0;
		foreach ($result as $itemMater) {

			foreach ($itemMater['gosts'] as $k => $value) {
				$table->addRow();
				if ($k == 0) {
					$obj = $itemMater['material_name'] . (!empty($itemMater['nfp']) ? '<w:br/>('.$itemMater['nfp'].')' : '');
					$table->addCell(null, $cellRowSpan)->addText($obj, array('size' => 9), $cellHCentered);
				} else {
					$table->addCell(null, $cellRowContinue);
				}
				$table->addCell()->addText($value['method_name'], array('size' => 10), $cellHCentered);
				$table->addCell()->addText($value['gost_name'], array('size' => 10), $cellHCentered);
				$table->addCell()->addText($value['tech_condition'], array('size' => 10), $cellHCentered);
				$table->addCell()->addText($value['price'], array('size' => 10), $cellHCentered);
			}
		}

		$this->generateDocumentWithTable($table, $info, $template_doc, $type);
	}

    /**
     * @param $protocolID
     */
    public function protocolGenerator($protocolID)
    {
        $companyModel = new Company();
        $requestModel = new Request();
        $historyModel = new History();
        $userModel = new User();
        $orderModel = new Order();
        $ProtocolModel = new Protocol();
        $ResultModel = new Result();
        $oborudModel = new Oborud();
        $materialModel = new Material();
		$methodModel = new Methods();
		$gostModel = new Gost();
        $requirementModel = new Requirement();
        $nkModel = new Nk;

		$protocol = $ProtocolModel->getProtocolById($protocolID);
		$order = $orderModel->getOrderByDealId($protocol['DEAL_ID']);
		$dealInformation = $requestModel->getTzByDealId($protocol['DEAL_ID']);
		$companyInformation = $companyModel->getRequisiteByDealId($protocol['DEAL_ID']);
		$probeResults = $ProtocolModel->getDataForDoc((int)$protocolID);

		//Данные для сохранения протокола
		$curdate = "Протокол_" . ($protocol['NUMBER'] ? $protocol['NUMBER'] : '') . "_" . date("d-m-Y_H-i-s");

		if (!empty($curdate)) {
			$this->DB->Query("UPDATE `PROTOCOLS` SET `ACTUAL_VERSION`= '" . $curdate . "' WHERE `ID`=" . $protocolID);
		}


		//Дата начала и окончания испытаний
		$dateBegin = !empty($protocol['DATE_BEGIN']) ? date('d.m.Y', strtotime($protocol['DATE_BEGIN'])) : '';
		$dateEnd = !empty($protocol['DATE_END']) ? date('d.m.Y', strtotime($protocol['DATE_END'])) : '';

		//Номер заявки
		$requestNum = explode('№', $dealInformation['REQUEST_TITLE'])[1];

		//Дата заявки
		$requestDate = $dealInformation['DATE_CREATE'];

		//Заявка в поле основание
		$requestFor = " (Заявка №{$requestNum} от {$requestDate})";

		//Данные о заказчике
		$nInfo = $companyInformation['RQ_ACCOUNTANT'];

		//Руководитель на подпись
		$header = $userModel->checkHeader(53);

		//Получаем пробы в протоколе
		$count_probe_str = (count($probeResults?? []) > 1 ? count($probeResults?? []) . " " . StringHelper::numDeclension(count($probeResults?? []), ['проба', 'пробы/образца', 'проб/образцов']) . ": " : "");

		//информация об объекте
		$material_probe = [];
		$gostsInProtocol = [];
		$condititionsInProtocol = [];
		foreach ($probeResults as $item) {

			$material_probe[] = $item['material_name'] . ($item['name_for_protocol'] ? ', ' . $item['name_for_protocol'] : '') . ' (' . $item['cipher'] . ')';
			foreach ($item['gosts']['method'] as $key => $value) {
				if ($value['is_selection'] == 0) {
					$gostsInProtocol[] = trim($value['reg_doc']) . (!empty($value['year']) ? '-' . trim($value['year']) : '') . ' ' . trim($value['description']);
				}

				//Документ, устанавливающий правила и методы исследований (испытаний) и измерений и ТУ
				$condititionInProtocol = $item['gosts']['condition'][$key];

				if ($condititionInProtocol['reg_doc'] == '-' || $condititionInProtocol['reg_doc'] == '--' || $condititionInProtocol['reg_doc'] == '') {
				} else {
					$condititionsInProtocol[] = trim($condititionInProtocol['reg_doc']) . (!empty($condititionInProtocol['year']) ? '-' . trim($condititionInProtocol['year']) : '') . ' ' . trim($condititionInProtocol['name']);
				}
			}
		}

		$gostsInProtocolView = implode(', ', array_diff(array_unique($gostsInProtocol), [' ']));

//		if (!$protocol['NO_COMPLIANCE']) {
			$condititionsInProtocolView = implode(', ', array_diff(array_unique($condititionsInProtocol), [' ']));
//		} else {
//			$condititionsInProtocolView = '';
//		}

		// Вывод всех объектов с шифрами проб

		$allProbeInProtocol = '';
		for ($i = 0; $i < count($probeResults?? []); $i++) {

			$allProbeInProtocol .= $material_probe[$i] . ((count($material_probe?? []) - (int)$i) == 1 ? "" : ", ");

		}

		$number_probe = $count_probe_str . $allProbeInProtocol;



		//Дата протокола
		$protocolDate = !empty($protocol['DATE']) ? date('d.m.Y', strtotime($protocol['DATE'])) : '';

		//Подпись в протоколе
        $verify = [];
        if ( !empty($protocol['VERIFY']) ) {
            $tmp = unserialize($protocol['VERIFY']);

            if ( !empty($tmp) && is_array($tmp) ) {
                $verify = $tmp;
            }
        }

		//Пробы отобраны не заказчиком
		$selectionType = $dealInformation['SELECTION_TYPE'] ? '' : '– пробы отобраны и доставлены в ИЦ заказчиком.';

		//Продпись в протоколе
		$verifyArr = [];

//		foreach ($arrVerify as $userVerify) {
//			$user = $userModel->getUserShortById($userVerify);
//
//			$verifyArr[] = [
//				'WorkPos' => $user['work_position'],
//				'Ispolnitel' => $user['short_name'],
//				'Ispolnitel2' => 'подписано ' . $user['short_name'],
//				'IC' => ($user['department'] == 58 ? '' : ' ИЦ')
//			];
//		}

		$order111 = array("\r\n", "\n", "\r");
		$replace111 = '<w:br/>';

		//Таблица информации в протокол
		$protocolInformation = [
			'protocol_type' => $protocol['PROTOCOL_TYPE'],
			'tz_id' => $protocol['ID_TZ'],
			'protocolYear' => date('Y', strtotime($protocol['DATE'])),
			'id' => $protocolID,
			'curdate' => $curdate,
			'number' => $protocol['NUMBER'],
			'nProtocol' => $protocol['NUMBER_AND_YEAR'],
			'A' => ($protocol['IN_ATTESTAT_DIAPASON'] ? 'A' : ''),
			'Attestat' => ($protocol['ATTESTAT_IN_PROTOCOL'] ? ' Уникальный номер записи об аккредитации в реестре аккредитованных лиц RA.RU.21ЧЦ49' : ''),
			'Attestat2' => ($protocol['ATTESTAT_IN_PROTOCOL'] ? '' : ''),
			'dProtocol' => ($protocolDate ? date("d.m.Y", strtotime($protocolDate)) : date("d.m.Y")),
			'nDogovor' => $order . $requestFor,
			'nZakazchik' => $companyInformation['NAME'],
			'innZakazchik' => $companyInformation['RQ_INN'],
			'ogrnZakazchik' => $companyInformation['RQ_OGRN'],
			'aZakazchik' => $nInfo,
			'header' => $header['short_name'],
			'work_position' => $header['work_position'],
			'dProbe' => $dealInformation['DATE_ACT'] ? date("d.m.Y", strtotime($dealInformation['DATE_ACT'])) : '',
			'nProbe_reg' => $dealInformation['NUM_ACT_TABLE'],
			'oStroit' => !empty($protocol['OBJECT']) ? $protocol['OBJECT'] : '',
			'oIspit' => htmlspecialchars($number_probe),
			'metodIspit' => $gostsInProtocolView,
			'normDoc' => !empty($condititionsInProtocolView) ? $condititionsInProtocolView : '-',
			'mIspit' => '',
			'celIspit' => '',
			'd1Ispit' => $dateBegin,
			'd2Ispit' => strtotime($dateEnd) == strtotime($dateBegin) ? '' : '-' . date('d.m.Y', strtotime($dateEnd)),
            'tIspit' => $protocol['TEMP_O']==$protocol['TEMP_TO_O'] ? number_format($protocol['TEMP_O'], 1, ',', '')." " : number_format($protocol['TEMP_O'], 1, ',', '')." - ".number_format($protocol['TEMP_TO_O'], 1, ',', ''),
            'vIspit' => $protocol['VLAG_O']==$protocol['VLAG_TO_O'] ? number_format($protocol['VLAG_O'], 1, ',', '') : number_format($protocol['VLAG_O'], 1, ',', '')." - ".number_format($protocol['VLAG_TO_O'], 1, ',', ''),
			'dopInfo' => trim(str_replace(["\r\n", "\n", "\r"], '<w:br/>', $protocol['DOP_INFO'])),
			'Description' => trim(html_entity_decode($protocol['DESCRIPTION'])),
			'metodOtbor' => '',
			'ISP' => (count($verify) > 1 ? 'Исполнители' : 'Исполнитель'),
			'Point' => '',
			'OtborProb' => $selectionType,
			'table_verify' => '',//$verifyArr['WorkPos'] . ' ' . $verifyArr['IC'] . ' __________' . $verifyArr['Ispolnitel'],
			'verify_arr' => '',
            'mestoSboraProbe' => (!empty($protocol['PLACE_PROBE']) ? trim($protocol['PLACE_PROBE']) : '-') . '; ' . (!empty($protocol['DATE_PROBE']) && $protocol['DATE_PROBE'] != '0000-00-00' ? date('d.m.Y', strtotime($protocol['DATE_PROBE'])) : '-'),
            'dealTypeId' => $dealInformation['TYPE_ID'],
		];

		//Таблица оборудования
		$oborudData = $oborudModel->getTzObConnectByProtocolId($protocolID);

		//Выбор скртификата поверки
		foreach ($oborudData as $k => $ob) {
			$certificateOborud = $oborudModel->getCertificateByOborudId($k);
			$oborudData[$k]['certificate'] = $certificateOborud;
		}

		// Результаты испытаний
		$results = [];
        $frost = [];
        $tableData = [];

		foreach ($probeResults as $k => $val) {
			$object = $val['material_name'] . ' ' .  $val['name_for_protocol'] .' (' . $val['cipher'] . ')';

			$count_val = 0;
			foreach ($val['gosts']['method'] as $key => $method) {

				if ($method['is_selection']) {
					continue;
				}

				$condition = $val['gosts']['condition'][$key];
				$resultValue = $val['gosts']['result'][$key];

                // В ГОСТе Методике "Факт. значения текстом" И есть ТУ и И ТУ НЕ Ручное управление "соотв/не соотв",
                // то Параметр М-ки(ф/значение текстом) не соотв. параметру в ТУ (управление не вручную), не должно попадать в протокол
				if ($protocol['DEAL_ID'] >= DEAL_NEW_RESULT) {
					if (!empty($method['is_text_fact']) && !empty($condition['id']) && empty($condition['is_manual'])) {
						continue;
					}
				}

				$measuringSheet = $protocol['DEAL_ID'] >= DEAL_NEW_RESULT ?
                    json_decode($val['measuring_sheet'][$key], true) : json_decode($resultValue['measuring_sheet'], true);

                // Если в ТУ "Текст нормативного значения по ГОСТу" выводиться в протокол, то берем нормативное значение "Текст нормативного значения по ГОСТу"
                if (/*$protocol['DEAL_ID'] >= DEAL_NEW_RESULT && */empty($condition['is_manual']) && !empty($condition['is_output'])) {
                    $normval = $condition['norm_comment'];
                } else {
                    $normativeValue = !empty($resultValue['normative_value']) ? trim(htmlspecialchars_decode($resultValue['normative_value'])) : '-';
                    $normval = $protocol['DEAL_ID'] >= DEAL_NEW_RESULT ? $val['normative_val'][$key] : $normativeValue;
                }

                $match_value = [
                    '0' => 'Не соответствует',
                    '1' => 'Соответствует',
                    '2' => '-',
                    '3' => 'Не нормируется',
                ];
                $matchValue = $protocol['DEAL_ID'] >= DEAL_NEW_RESULT ?
                    $match_value[$val['match'][$key]] : $match_value[$resultValue['match']];
                
                $actualValue = $protocol['DEAL_ID'] >= DEAL_NEW_RESULT ?
                    $val['actual_value'][$key] : json_decode($resultValue['actual_value'], true)[0];

                if (
                    empty($method['is_text_fact']) &&
                    !empty($method['is_range_text']) &&
                    is_numeric($actualValue)
                ) {
                    // Внутренний диапазон
                    if ($method['definition_range_type'] == 1) {
                        if ($actualValue < $method['definition_range_1']) {
							$method['definition_range_1'] = str_replace('.', ',', $method['definition_range_1']);
                            $actualValue = "{$method['range_text_in']} {$method['definition_range_1']}";
                        } elseif ($actualValue > $method['definition_range_2']) {
							$method['definition_range_2'] = str_replace('.', ',', $method['definition_range_2']);
                            $actualValue = "{$method['range_text_out']} {$method['definition_range_2']}";
                        }
                    } else if ($method['definition_range_type'] == 2) { // Внешний диапазон
                        if ($actualValue > $method['definition_range_1']) {
							$method['definition_range_1'] = str_replace('.', ',', $method['definition_range_1']);
                            $actualValue = "{$method['range_text_out']} {$method['definition_range_1']}";
                        } elseif ($actualValue < $method['definition_range_2']) {
							$method['definition_range_2'] = str_replace('.', ',', $method['definition_range_2']);
                            $actualValue = "{$method['range_text_in']} {$method['definition_range_2']}";
                        }
                    }
                }
                $actualValue = is_numeric($actualValue) ? str_replace('.', ',', $actualValue) : $actualValue;
				$actualValue = htmlentities($actualValue);
                
				// если есть лист измерений
				if (isset($measuringSheet)) {

					if ($measuringSheet['type'] == 'grain' && $measuringSheet['checkbox'] != 1) {

						$tableZern = [];
						$methodInfo = $methodModel->get($measuringSheet['method_id']);
						$materialInfo = $gostModel->getMaterialByUgtpId($measuringSheet['ugtp_id']);

						foreach ($measuringSheet['in_protocol'] as $key) {
							$zern = $materialModel->getSieveAndNorm($measuringSheet['zern']);
//
							$tableZern['name_table'] = '${table_zern}';
							$tableZern['num_table'] = 2;
							$tableZern['a'][] = str_replace('.', ',', $measuringSheet['a'][$key]);
							$tableZern['p'][] = str_replace('.', ',', $measuringSheet['p'][$key]);
							$tableZern['norm'][] = $measuringSheet['norm'][$key];
							$tableZern['title'][] = $measuringSheet['title'][$key];
							$tableZern['name'] = $zern['NAME'];
							$tableZern['a_in_protocol'] = $measuringSheet['a_in_protocol'];
							$tableZern['p_in_protocol'] = $measuringSheet['p_in_protocol'];
							$tableZern['req_in_protocol'] = $measuringSheet['req_in_protocol'];
							$tableZern['initial_mass'] = $measuringSheet['initial_mass'];
							$tableZern['name_material'] = $materialInfo['NAME'];
							$tableZern['cipher'] = $materialInfo['cipher'];
							$tableZern['method'] = [
								'view' => $methodInfo['view_gost_for_protocol'],
								'name' => $methodInfo['mp_name'],
								'unit' => $methodInfo['unit_rus']
							];
						}
					} elseif ($measuringSheet['type'] == 'sred') {
                        $eol = '<w:br/>';

                        // Если фактическое значение пустое, то невыводим в протокол
                        if ($actualValue === '' || !isset($actualValue)) {
                            continue;
                        }

                        // Данные для отдельной таблицы с единичными значениями, если отмечен checkbox "Выводить единичные значения в протокол?"
                        if ($measuringSheet['is_single_values']) {
                            $tableData['sred']['name_table'] = '${table_sred}';

                            $tableData['sred'][] = [
                                'Object' => $object,
                                'Specification' => $method['name'],
                                'SpecificationTU' => !empty($condition['measured_properties_name']) ? $condition['measured_properties_name'] : '-',
                                'Ed' => $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'],
                                'Normdoc' => !empty($condition['reg_doc']) ? $condition['reg_doc'] . (!empty($condition['clause']) ? ' ' . $condition['clause'] : '') : '-',
                                'Normval' => $normval,
                                'Normdocmet' => $method['view_gost_for_protocol'],
                                'Normdoc_desc' => '',
                                'SingleValues' => $measuringSheet['actual_value'] ? implode($eol, str_replace('.', ',', $measuringSheet['actual_value'])) : '-',
                                'Value' => ($condition['type'] == "TU_sred"
                                    || $condition['type'] == "TU_sred3"
                                    || $condition['type'] == "TU_sred2"
                                    || $condition['type'] == "TU_sred4"
                                    || $condition['type'] == "TU_sred5"
                                ) ?
                                    number_format($resultValue['average_value'], $measuringSheet['decimal_places'], ',', '') : "-",
                                'Values' => $actualValue,
                                'Result' => $matchValue,
                                'id' => $k,
                            ];

                            // Данные для упрощённого протокола с единичными и без единичных значений
                            $protocolShort = $protocolInformation['protocol_type'] == 34 || $protocolInformation['protocol_type'] == 33;
                            if ($protocolShort) {
                                $results[] = [
                                    'Object' => $object,
                                    'Specification' => $method['name'],
                                    'SpecificationTU' => !empty($condition['measured_properties_name']) ? $condition['measured_properties_name'] : '-',
                                    'Ed' => $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'],
                                    'Normdoc' => !empty($condition['reg_doc']) ? $condition['reg_doc'] . (!empty($condition['year']) ? '-' . $condition['year'] : '') : '-',
                                    'Normval' => $normval,
                                    'Normdocmet' => $method['view_gost_for_protocol'],
                                    'Normdoc_desc' => '',
//                                    'SingleValues' => $measuringSheet['actual_value'] ? implode($eol, str_replace('.', ',', $measuringSheet['actual_value'])) : '-',
                                    'SingleValues' => $measuringSheet['actual_value'] ? implode('  <w:br/>  ', str_replace('.', ',', $measuringSheet['actual_value'])) : '-',
                                    'Value' => ($condition['type'] == "TU_sred"
                                        || $condition['type'] == "TU_sred3"
                                        || $condition['type'] == "TU_sred2"
                                        || $condition['type'] == "TU_sred4"
                                        || $condition['type'] == "TU_sred5"
                                    ) ?
                                        number_format($resultValue['average_value'], $measuringSheet['decimal_places'], ',', '') : "-",
                                    'Values' => $actualValue,
                                    'Result' => $matchValue,
                                    'id' => $k,
                                ];
                            }
                        } else { // если не отмечен checkbox "Выводить единичные значения в протокол?", данные без единичных значений для вывода в таблицу результатов

                            $results[] = [
                                'Object' => $object,
                                'Specification' => $method['name'],
                                'SpecificationTU' => !empty($condition['measured_properties_name']) ? $condition['measured_properties_name'] : '-',
                                'Ed' => $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'],
                                'Normdoc' => !empty($condition['reg_doc']) ? $condition['reg_doc'] . (!empty($condition['year']) ? '-' . $condition['year'] : '') : '-',
                                'Normval' => $normval,
                                'Normdocmet' => $method['view_gost_for_protocol'],
                                'Normdoc_desc' => '',
                                //'SingleValues' => '-',
                                'Values' => $actualValue,
                                'Value' => ($condition['type'] == "TU_sred" || $condition['type'] == "TU_sred3" || $condition['type'] == "TU_sred2" || $condition['type'] == "TU_sred4" || $condition['type'] == "TU_sred5") ? $resultValue['average_value'] : "-",
                                'Result' => $matchValue,
                                'id' => $k,
                            ];
                        }

					} elseif ($measuringSheet['type'] == 'frost') { // Морозостойкость
                        $sheet = $ResultModel->getMeasurement($method['measurement_id']);

                        $frost['name_table'] = '${table_frost}';
                        $eol = '<w:br/>';
                        $isIntermediate = isset($measuringSheet['ratio1']) && $measuringSheet['ratio1'] === '0';

                        //Убераем пустые поля, чтобы не выводить в протокол
                        $controlStrength1 = array_diff($measuringSheet['control_strength1'], ['']);
                        $controlStrength2 = array_diff($measuringSheet['control_strength2'], ['']);
                        $mainStrength1 = array_diff($measuringSheet['main_strength1'], ['']);
                        $mainStrength2 = array_diff($measuringSheet['main_strength2'], ['']);

                        $cycle = $isIntermediate ? $measuringSheet['cycle_intermediate'] : $measuringSheet['cycle_control'];
                        $normdocmet = $method['view_gost_for_protocol'] . $eol . $cycle . " циклов";
                        $ed = $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'];
                        $controlDamage = $isIntermediate ? $measuringSheet['control_damage1'] : $measuringSheet['control_damage2'];
                        $mainDamage = $isIntermediate ? $measuringSheet['main_damage1'] : $measuringSheet['main_damage2'];
                        $controlMass = $isIntermediate ? $measuringSheet['control_mass1'] : $measuringSheet['control_mass2'];
                        $mainMass = $isIntermediate ? $measuringSheet['main_mass1'] : $measuringSheet['main_mass2'];
//                        $controlStrength = $isIntermediate ? implode($eol, $measuringSheet['control_strength1']) :
//                            implode($eol, $measuringSheet['control_strength2']);
//                        $mainStrength = $isIntermediate ? implode($eol, $measuringSheet['main_strength1']) :
//                            implode($eol, $measuringSheet['main_strength2']);
                        $controlStrength = $isIntermediate ? implode($eol, $controlStrength1) :
                            implode($eol, $controlStrength2);
                        $mainStrength = $isIntermediate ? implode($eol, $mainStrength1) :
                            implode($eol, $mainStrength2);
                        $controlMedium = $isIntermediate ? $measuringSheet['control_medium1'] : $measuringSheet['control_medium2'];
                        $mainMedium = $isIntermediate ? $measuringSheet['main_medium1'] : $measuringSheet['main_medium2'];
                        $controlBottomLine = $isIntermediate ? $measuringSheet['control_bottom_line1'] : $measuringSheet['control_bottom_line2'];
                        $mainBottomLine = $isIntermediate ? $measuringSheet['main_bottom_line1'] : $measuringSheet['main_bottom_line2'];
                        $ratio = $isIntermediate ? $measuringSheet['ratio1'] : $measuringSheet['ratio2'];

                        $markName = $sheet['initial_data'][$measuringSheet['mark']]['mark'] ?? '';

                        if ($ratio === '1') {
                            $tested = 'выдержали';
                            $match = 'соответствуют';
                            $ratioName = 'соблюдается';
                        } else {
                            $tested = 'не выдержали';
                            $match = 'не соответствуют';
                            $ratioName = 'не соблюдается';
                        }

                        $frost['Object'] = $object;
                        $frost['Normdocmet'] = $normdocmet;
                        $frost['Ed'] = $ed;
                        $frost['ControlDamage'] = $controlDamage;
                        $frost['MainDamage'] = $mainDamage;
                        $frost['ControlMass'] = $controlMass;
                        $frost['MainMass'] = $mainMass;
                        $frost['ControlStrength'] = $controlStrength;
                        $frost['MainStrength'] = $mainStrength;
                        $frost['ControlMedium'] = $controlMedium;
                        $frost['MainMedium'] = $mainMedium;
                        $frost['ControlBottomLine'] = $controlBottomLine;
                        $frost['MainBottomLine'] = $mainBottomLine;
                        $frost['Ratio'] = $ratioName;
                        $frost['Tested'] = $tested;
                        $frost['Match'] = $match;
                        $frost['Mark'] = $markName;
                        $frost['umtr_id'] = $k;
                    } elseif ($measuringSheet['type'] == 'asphalt') {

//						$this->pre($measuringSheet);
						$tableZern = [];
						$methodInfo = $methodModel->get($measuringSheet['method_id']);
						$materialInfo = $gostModel->getMaterialByUgtpId($measuringSheet['ugtp_id']);

						foreach ($measuringSheet['in_protocol'] as $key) {
							$zern = $materialModel->getSieveAndNorm($measuringSheet['zern']);

							$tableZern['name_table'] = '${table_zern}';
							$tableZern['type'] = 'asphalt';
							$tableZern['num_table'] = 2;
							$tableZern['fp'][] = str_replace('.', ',', $measuringSheet['fp'][$key]);
							$tableZern['p'][] = str_replace('.', ',', $measuringSheet['p'][$key]);
							$tableZern['a'][] = str_replace('.', ',', $measuringSheet['a'][$key]);
							$tableZern['req'][] = str_replace('.', ',', $measuringSheet['norm'][$key]);
							$tableZern['a_in_protocol'] = $measuringSheet['a_in_protocol'];
							$tableZern['p_in_protocol'] = $measuringSheet['p_in_protocol'];
							$tableZern['fp_in_protocol'] = $measuringSheet['fp_in_protocol'];
							$tableZern['recept_in_protocol'] = $measuringSheet['recept_in_protocol'];
							$tableZern['req_in_protocol'] = $measuringSheet['req_in_protocol'];
							$tableZern['r'][] = str_replace('.', ',', $measuringSheet['r'][$key]);
							$tableZern['norm'][] = $measuringSheet['norm'][$key];
							$tableZern['title'][] = $measuringSheet['title'][$key];
							$tableZern['name'] = $zern['NAME'];
							$tableZern['initial_mass'] = $measuringSheet['initial_mass'];
							$tableZern['name_material'] = $materialInfo['NAME'];
							$tableZern['cipher'] = $materialInfo['cipher'];
							$tableZern['method'] = [
								'view' => $methodInfo['view_gost_for_protocol'],
								'name' => $methodInfo['mp_name'],
								'unit' => $methodInfo['unit_rus']
							];
						}

					} elseif ( in_array($measuringSheet['type'], ['concrete_strength_17624_7', 'actual_class_18105_8_4_1']) ) {
                        $designCalculation = !empty($measuringSheet['design_calculation']);

                        if ($designCalculation) {
                            $measuringSheet = $requirementModel->getGostToProbe($measuringSheet['design_calculation'])['measuring_sheet'];
                        }

                        $twentyEight = 28;
                        $scheme = $measuringSheet['scheme'] ?? '';
                        $measurementId = $measuringSheet['measurement_id'] ?? '';
                        $eol = '<w:br/>';

                        if ($scheme === 'v') {
                            $measurement = $nkModel->getGraduation($measurementId);

                            $dir = UPLOAD_DIR . "/plot/{$measurementId}";
                            $files = $requestModel->getFilesFromDir($dir);
                            $chartName = end($files);

                            $tableData['gradation'][$measurementId] = [
                                'table_name' => '${gradation_'.$measurementId.'}',
                                'name_plot' => '${chart_'.$measurementId.'}',
                                'search_plot' => 'CompanyLogo',
                                'chart_name' => '',
                                'Name' => $measurement['data']['name'],
                                'Mean' => $measurement['data']['mean'],
                                'ShearStrength' => $measurement['data']['shear_strength'],
                                'GradationStrength' => $measurement['data']['gradation_strength'],
                                'Condition' => $measurement['data']['condition'],
                                'a' => $measurement['data']['a'],
                                'b' => $measurement['data']['b'],
                                'MeasuringDevice' => $measurement['data']['measuring_device'],
                                'id' => $k,
                                'material_id' => $val['material_id'],
                                'measurement_id' => $measurementId,
                                'protocol_id' => $protocolID,
                                'Comment' => str_replace(PHP_EOL, $eol, $measurement['data']['comment']),
                                'Note' => $measurement['data']['note'],
                                'Description' => str_replace(PHP_EOL, $eol, $measurement['data']['description']),
                                'Chart' => $dir .'/'. $chartName
                            ];
                        }

                        // Нормативная(проектная) документация
                        if ($designCalculation) {
                            $normdoc = $measuringSheet['cipher'];
                        } else {
                            $normdoc = !empty($condition['reg_doc']) ? $condition['reg_doc'] . (!empty($condition['year']) ? '-' . $condition['year'] : '') : '-';
                        }

                        // Нормативное значение
                        if ($designCalculation) { // Если есть "Класс бетона", то выводим его иначе нормативное значение
                            $normval = 'B'.$measuringSheet['class'];
                        }

                        // Фактическое значение характеристики конструкции
                        if ($designCalculation) {
                            $value = 'B'.abs($measuringSheet['concrete_class']);
                        } else {
                            $value = $measuringSheet['result_value'];
                        }

                        // Соответствие
                        if ($designCalculation) {
                            $match = $measuringSheet['day_to_test'] < $twentyEight ? 'Не нормируется' : $measuringSheet['percent'];
                            $percentMatch = $measuringSheet['percent'] ? "({$measuringSheet['percent']}% от Bпр)" : '';
                            $match = $match . $eol . $percentMatch;
                        } else {
                            $match = '-';
                        }

                        //$tableData['nk'][$k]['name_table'] = '${table_nk_'.$val['material_id'].'}';
                        $tableData['nk'][$k]['name_table'] = '${table_nk_'.$k.'}';

                        $tableData['nk'][$k][] = [
                            'Object' => $val['name_for_protocol'],
                            'Date' => !empty($measuringSheet['сoncreting_date']) ? date('d.m.Y', strtotime($measuringSheet['сoncreting_date'])) : '',
                            'Specification' => $method['name'],
                            'SpecificationTU' => !empty($condition['measured_properties_name']) ? $condition['measured_properties_name'] : '-',
                            'Ed' => $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'],
                            'Normdoc' => $normdoc,
                            'Normval' => $normval,
                            'Normdocmet' => $method['view_gost_for_protocol'],
                            'Normdoc_desc' => '',
                            'SingleValues' => $designCalculation ? '-' : implode($eol, $measuringSheet['single_values']),
                            'Values' => $value,
                            //'Result' => $measuringSheet['match'] ? $measuringSheet['match'] : $match_value[$resultValue['match']],
                            'Result' => $match,
                            'Comment' => $measuringSheet['comment'],
                            'id' => $k,
                            'material_id' => $val['material_id'],
                        ];

                    } else {
						$results[] = [
							'Object' => $object,
							'Specification' => $method['name'],
							'SpecificationTU' => !empty($condition['measured_properties_name']) ? $condition['measured_properties_name'] : '-',
							'Ed' => $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'],
							'Normdoc' => !empty($condition['reg_doc']) ? $condition['reg_doc'] . (!empty($condition['year']) ? '-' . $condition['year'] : '') . (!empty($condition['clause']) ? ' ' . $condition['clause'] : '') : '-',
							'Normval' => $normval,
							'Normdocmet' => $method['view_gost_for_protocol'],
							'Normdoc_desc' => '',
							'Values' => is_numeric($actualValue) ? round(str_replace(',', '.', $actualValue), $method['decimal_places'], PHP_ROUND_HALF_UP) : $actualValue,
							'Value' => ($condition['type'] == "TU_sred" || $condition['type'] == "TU_sred3" || $condition['type'] == "TU_sred2" || $condition['type'] == "TU_sred4" || $condition['type'] == "TU_sred5") ? $resultValue['average_value'] : "-",
							'Result' => $matchValue,
							'id' => $k,
						];
					}
				} else {
					if ($actualValue) {
						$count_val++;

						$results[] = [
							'Object' => $object,
							'Specification' => $method['name'],
							'SpecificationTU' => !empty($condition['measured_properties_name']) ? $condition['measured_properties_name'] : '-',
							'Ed' => $method['unit_fsa_id'] == 804 ? '-' : $method['unit_rus'],
							'Normdoc' => !empty($condition['reg_doc']) ? $condition['reg_doc'] . (!empty($condition['clause']) ? ' ' . $condition['clause'] : '') : '-',
							'Normval' => $normval,
							'Normdocmet' => $method['view_gost_for_protocol'],
							'Normdoc_desc' => '',
							'Values' => $actualValue,
							'Value' => ($condition['type'] == "TU_sred"
								|| $condition['type'] == "TU_sred3"
								|| $condition['type'] == "TU_sred2"
								|| $condition['type'] == "TU_sred4"
								|| $condition['type'] == "TU_sred5") ?
								$resultValue['average_value'] : "-",
							'Result' => $matchValue,
							'id' => $k,
						];
					}
				}
			}

			if ($protocol['NO_COMPLIANCE']) {
				$results['no_compliance'] = 1;
			} else {
				$results['no_compliance'] = 0;
			}
			$results['count'][$k] = $count_val;
			$results['name_table'] = '${table_result}';
		}

//		if ($_SESSION['SESS_AUTH']['USER_ID'] == 61) {
//			echo '<pre>';
//			print_r($frost);
//			exit();
//		}

		//отправляем на формирование
//		$this->pre($measuringSheet);
		$this->createProtocolDocument($protocolInformation, $verifyArr, $oborudData, $results, $tableData, $tableZern, $frost);

		//Добавляем историю
		$date = date('Y-m-d H:i:s');
		$currentUser = $userModel->getCurrentUser();

		$history = [
			'DATE' => $date,
			'ASSIGNED' => $currentUser['NAME'] . ' ' . $currentUser['LAST_NAME'],
			'TZ_ID' => $dealInformation['ID'],
			'USER_ID' => $currentUser['ID'],
			'TYPE' => 'Формирование протокола',
			'REQUEST' => $dealInformation['REQUEST_TITLE'],
		];

		$historyModel->addHistory($history);

	}

    /**
     * Генератор протокола
     * @param $protocolInformation
     * @param $varify
     * @param $oborud
     * @param $results
     * @param $tableData
     * @param $tableZern
     * @param $frost
     */
    public function createProtocolDocument($protocolInformation, $varify, $oborud, $results, $tableData=[], $tableZern = [], $frost = [])
    {

		$tableAllResult = [];
        $protocolShort = $protocolInformation['protocol_type'] == 34 || $protocolInformation['protocol_type'] == 33;

        if ( $protocolInformation['protocol_type'] == 34 || $protocolInformation['protocol_type'] == 33 ) {
            // шаблон упрощенного протокола
            $template_doc = $_SERVER["DOCUMENT_ROOT"] . '/ProtocolShort.docx';

            $file = new Bitrix\Main\IO\File($template_doc);

            $document = new Bitrix\DocumentGenerator\Body\Docx($file->getContents());

            $document->normalizeContent();

            $document->setValues([
                'Verify' => new Bitrix\DocumentGenerator\DataProvider\ArrayDataProvider(
                    $protocolInformation['verify_arr'],
                    [
                        'ITEM_NAME' => 'Item',
                        'ITEM_PROVIDER' => Bitrix\DocumentGenerator\DataProvider\HashDataProvider::class,
                    ]
                ),
                'VerifyItemWorkPos' => 'Verify.Item.WorkPos',
                'VerifyItemIspolnitel' => 'Verify.Item.Ispolnitel',
                'VerifyItemIspolnitel2' => 'Verify.Item.Ispolnitel2',
                'VerifyItemIC' => 'Verify.Item.IC',
            ]);

            $result = $document->process();
            $content = $document->getContent();
		} elseif ($protocolInformation['protocol_type'] == 1) {
			// шаблон протокола с эцп
			$template_doc = $_SERVER["DOCUMENT_ROOT"] . '/ProtocolStandartECP.docx';

			$file = new Bitrix\Main\IO\File($template_doc);

			$document = new Bitrix\DocumentGenerator\Body\Docx($file->getContents());

			$document->normalizeContent();

			$document->setValues([
				'Verify' => new Bitrix\DocumentGenerator\DataProvider\ArrayDataProvider(
					$protocolInformation['verify_arr'],
					[
						'ITEM_NAME' => 'Item',
						'ITEM_PROVIDER' => Bitrix\DocumentGenerator\DataProvider\HashDataProvider::class,
					]
				),
				'VerifyItemWorkPos' => 'Verify.Item.WorkPos',
				'VerifyItemIspolnitel' => 'Verify.Item.Ispolnitel',
				'VerifyItemIspolnitel2' => 'Verify.Item.Ispolnitel2',
				'VerifyItemIC' => 'Verify.Item.IC',
			]);

			$result = $document->process();
			$content = $document->getContent();
			//временно
		} elseif ($protocolInformation['protocol_type'] == 2) {
			$template_doc = $_SERVER["DOCUMENT_ROOT"] . '/ProtocolStandart1.docx';
		} else {
            if ($protocolInformation['dealTypeId'] === TYPE_DEAL_NK) {
                // шаблон протокола НК
                $template_doc = $_SERVER["DOCUMENT_ROOT"] . '/ProtocolStandartNk2.docx';
            } else {
                // стандартный шаблон
                $template_doc = $_SERVER["DOCUMENT_ROOT"] . '/ProtocolStandart1.docx';
            }
        }

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $template = new \PhpOffice\PhpWord\TemplateProcessor($template_doc);

        $newpath = "archive/{$protocolInformation['tz_id']}{$protocolInformation['protocolYear']}/{$protocolInformation['id']}/{$protocolInformation['curdate']}.docx";
        $newpath2 = "archive/{$protocolInformation['tz_id']}{$protocolInformation['protocolYear']}/{$protocolInformation['id']}/forsign.docx";
        $interimPath = "interim_archive_prot/{$protocolInformation['tz_id']}{$protocolInformation['protocolYear']}/{$protocolInformation['id']}/{$protocolInformation['curdate']}.docx";
        $outputFile = "p №" . $protocolInformation['number'] . " от " . date("d.m.Y", strtotime($protocolInformation['dProtocol'])) . ".pdf";
        $outputPath = "archive/{$protocolInformation['tz_id']}{$protocolInformation['protocolYear']}/{$protocolInformation['id']}/";

        $fullPathDocx = $_SERVER['DOCUMENT_ROOT'] .'/protocol_generator/'.$interimPath;
        $fullPathPDF = $_SERVER['DOCUMENT_ROOT'] .'/protocol_generator/'.$outputPath;

        $newDirectory = $_SERVER['DOCUMENT_ROOT'] . '/protocol_generator/'. $outputPath;

        if( !is_dir( $newDirectory ) ) {
            mkdir($newDirectory, 0777, true);
        }

        //TODO: Посмотреть по формату протокола
        $pathDocCurdate = $_SERVER['DOCUMENT_ROOT'] . '/protocol_generator/' . $newpath;
        $forSign = $_SERVER['DOCUMENT_ROOT'] . '/protocol_generator/' . $newpath2;

        //Таблица оборудования
        foreach ($oborud as $ob) {
            $table_ob[] = [
//                'Name' => $ob['OBJECT'] . ' ' . $ob['TYPE_OBORUD'] . ', ' . $ob['FACTORY_NUMBER'] . ', '
//                    . $ob['REG_NUM'] . ', ' . date("d.m.Y", strtotime($ob['god_vvoda_expluatation'])),
                'Name' => $ob['OBJECT'] . ' ' . $ob['TYPE_OBORUD'] . ', ' . $ob['FACTORY_NUMBER'] . ', '
                    . $ob['REG_NUM'],

                'Poverka' => $ob['certificate'] . ' до ' . date("d.m.Y", strtotime($ob['POVERKA'])),
            ];
        }

        $styleTable = array('borderSize' => 3, 'cellMarginLeft'=>0, 'cellMarginRight'=>0,'borderColor' => '000000', 'leftFromText' => 0, 'rightFromText' => 0, 'bottomFromText' => 0 );
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        $cellColSpan4 = array('gridSpan' => 4, 'valign' => 'center');
        $cellColSpan6 = array('gridSpan' => 6, 'valign' => 'center');
        $cellColSpan5 = array('gridSpan' => 5, 'valign' => 'center');
        $cellColSpan10 = array('gridSpan' => 10, 'valign' => 'center');
        $FontStyle = ['size' => 10,
            'name' => 'Times New Roman'
        ];
        $FontStyleTitle = ['size' => 10,
            'name' => 'Times New Roman',
            'bold'=>true
        ];
        $paragraphStyle = [
            'spaceAfter' => 0,
            'spaceBefore' => 0,
			'space' => ['after' => 0],
			'spacing' => 10,
			'lineHeight' => 1,
            'align' => 'center',
			'indentation' => ['left' => 0, 'right' => 0]
        ];
        $paragraphStyleText = [
            'indentation' => ['left' => 700]
        ];

        $cellHCentered = array('align' => 'center');
        $cellAllCentered = array('align' => 'center', 'valign' => 'center');
        $cellVCentered = array('valign' => 'center');

        //Таблица оборудования
        $section = $phpWord->addSection();
        $table_oborud = $section->addTable($styleTable);
        $table_oborud->addRow(null, array('tblHeader' => true));
        $table_oborud->addCell(3000, $cellRowSpan)->addText('Наименование, марка, заводской и инвентарный №', array('size' => 10), $cellHCentered);
        $table_oborud->addCell(100, $cellRowSpan)->addText('Сведения о поверке/калибровке/аттестации', array('size' => 10), $cellHCentered);
        foreach ($table_ob as $itemMater) {
            $table_oborud->addRow();
            $table_oborud->addCell(null, $cellRowSpan)->addText($itemMater['Name'], array('size' => 10), $cellHCentered);
            $table_oborud->addCell(null, $cellRowSpan)->addText($itemMater['Poverka'], array('size' => 10), $cellHCentered);
        }

        //Зерновой состав общий
		if (!empty($tableZern)) {

			if ($tableZern['type'] == 'asphalt') {

				$countTitle = count($tableZern['title']?? []);
				$section = $phpWord->addSection();
				$table_zern = $section->addTable(array('borderSize' => 3, 'borderColor' => '000000'));
				$table_zern->addRow(null, array('tblHeader' => true));
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Объект испытаний (шифр проб/образцов в ИЦ)', $FontStyle, $paragraphStyle);
				$table_zern->addCell(1200, ['vMerge' => 'restart'])->addText('Определяемая характеристика (показатель)', $FontStyle, $paragraphStyle);
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Ед. изм.', $FontStyle, $paragraphStyle);
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Нормативный документ на метод испытания (раздел, пункт)', $FontStyle, $paragraphStyle);
//				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Наименова-ние остатка', $FontStyle, $paragraphStyle);
				$table_zern->addCell('auto', ['gridSpan' => $countTitle, 'valign' => 'center'])->addText('Размер ячейки сита, мм', $FontStyle, $paragraphStyle);
				$table_zern->addRow(null, array('tblHeader' => true));
				$table_zern->addCell(900, $cellRowContinue);
				$table_zern->addCell(900, $cellRowContinue);
				$table_zern->addCell(900, $cellRowContinue);
				$table_zern->addCell(900, $cellRowContinue);
//				$table_zern->addCell(900, $cellRowContinue);
				foreach ($tableZern['title'] as $title) {
					$table_zern->addCell(1200)->addText($title, $FontStyle, $paragraphStyle);
				}
				if ($tableZern['a_in_protocol'] == 1) {
					$table_zern->addRow(['exactHeight' => 500]);
					$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['name_material'] . " ({$tableZern['cipher']})", $FontStyle, $paragraphStyle);
					$table_zern->addCell(null, $cellRowSpan)->addText('Частный остаток на каждом сите', $FontStyle, $paragraphStyle);
					$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['unit'], $FontStyle, $paragraphStyle);
					$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['view'], $FontStyle, $paragraphStyle);
//				$table_zern->addCell(null, $cellAllCentered)->addText('Полный проход', $FontStyle, $paragraphStyle);
					foreach ($tableZern['a'] as $key_a => $fp) {
						$table_zern->addCell(null, $cellAllCentered)->addText($fp, $FontStyle, $paragraphStyle);
					}
				}
				if ($tableZern['p_in_protocol'] == 1) {
					$table_zern->addRow(['exactHeight' => 500]);

					if ($tableZern['a_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellRowContinue);
					} else {
						$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['name_material'] . " ({$tableZern['cipher']})", $FontStyle, $paragraphStyle);
					}

					$table_zern->addCell(null, $cellRowSpan)->addText('Полный остаток на каждом сите', $FontStyle, $paragraphStyle);

					if ($tableZern['a_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellRowContinue);
					} else {
						$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['unit'], $FontStyle, $paragraphStyle);
					}

					if ($tableZern['a_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellRowContinue);
					} else {
						$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['view'], $FontStyle, $paragraphStyle);
					}
//				$table_zern->addCell(null, $cellAllCentered)->addText('Полный проход', $FontStyle, $paragraphStyle);
					foreach ($tableZern['p'] as $key_a => $fp) {
						$table_zern->addCell(null, $cellAllCentered)->addText($fp, $FontStyle, $paragraphStyle);
					}
				}
				if ($tableZern['fp_in_protocol'] == 1) {
					$table_zern->addRow(['exactHeight' => 500]);

					if ($tableZern['p_in_protocol'] == 1 || $tableZern['a_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellRowContinue);
					} else {
						$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['name_material'] . " ({$tableZern['cipher']})", $FontStyle, $paragraphStyle);
					}
					$table_zern->addCell(null, $cellRowSpan)->addText('Полный проход через сито', $FontStyle, $paragraphStyle);

					if ($tableZern['p_in_protocol'] == 1 || $tableZern['a_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellRowContinue);
					} else {
						$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['unit'], $FontStyle, $paragraphStyle);
					}

					if ($tableZern['p_in_protocol'] == 1 || $tableZern['a_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellRowContinue);
					} else {
					$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['view'], $FontStyle, $paragraphStyle);
					}
//				$table_zern->addCell(null, $cellAllCentered)->addText('Полный проход', $FontStyle, $paragraphStyle);
					foreach ($tableZern['fp'] as $key_a => $fp) {
						$table_zern->addCell(null, $cellAllCentered)->addText($fp, $FontStyle, $paragraphStyle);
					}
				}
				if ($tableZern['recept_in_protocol'] == 1) {
					$table_zern->addRow(['exactHeight' => 500]);
					$table_zern->addCell(null, $cellRowContinue);
					$table_zern->addCell(null, $cellRowContinue);
					$table_zern->addCell(null, $cellRowContinue);
//					$table_zern->addCell(null, $cellRowContinue);
					$table_zern->addCell(null, $cellAllCentered)->addText('Требования рецепта', $FontStyle, $paragraphStyle);
					foreach ($tableZern['r'] as $key_p => $r) {
						$table_zern->addCell(null, $cellAllCentered)->addText(!empty($r) ? $r : '-', $FontStyle, $paragraphStyle);
					}
				}
				if ($tableZern['req_in_protocol'] == 1) {
					$table_zern->addRow(['exactHeight' => 500]);
					if ($tableZern['recept_in_protocol'] == 1) {
						$table_zern->addCell(null, $cellColSpan4)->addText("Предельно допустимые отклонения от утвержденного рецепта {$tableZern['name']}", $FontStyle, $paragraphStyle);
					} else {
						$table_zern->addCell(null, $cellColSpan4)->addText("Требования {$tableZern['name']}", $FontStyle, $paragraphStyle);
					}
					foreach ($tableZern['norm'] as $key_norm => $norm) {
						$table_zern->addCell(null, $cellAllCentered)->addText($norm, $FontStyle, $paragraphStyle);
					}
				}
			} else {
				$countTitle = count($tableZern['title']?? []);
				$section = $phpWord->addSection();
				$table_zern = $section->addTable(array('borderSize' => 3, 'borderColor' => '000000'));
				$table_zern->addRow(null, array('tblHeader' => true));
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Объект испытаний (шифр проб/образцов в ИЦ)', $FontStyle, $paragraphStyle);
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Определяемая характеристика (показатель)', $FontStyle, $paragraphStyle);
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Ед. изм.', $FontStyle, $paragraphStyle);
				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Нормативный документ на метод испытания (раздел, пункт)', $FontStyle, $paragraphStyle);
//				$table_zern->addCell(900, ['vMerge' => 'restart'])->addText('Наименова-ние остатка', $FontStyle, $paragraphStyle);
				$table_zern->addCell('auto', ['gridSpan' => $countTitle, 'valign' => 'center'])->addText('Размер ячейки сита, мм', $FontStyle, $paragraphStyle);
				$table_zern->addRow(null, array('tblHeader' => true));
				$table_zern->addCell(900, $cellRowContinue);
				$table_zern->addCell(900, $cellRowContinue);
				$table_zern->addCell(900, $cellRowContinue);
//				$table_zern->addCell(900, $cellRowContinue);
				$table_zern->addCell(900, $cellRowContinue);
				foreach ($tableZern['title'] as $title) {
					$table_zern->addCell(1200)->addText($title, $FontStyle, $paragraphStyle);
				}
				$table_zern->addRow(['exactHeight' => 500]);
				$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['name_material'] . " ({$tableZern['cipher']})", $FontStyle, $paragraphStyle);
				$table_zern->addCell(null, $cellAllCentered)->addText('Частный остаток на каждом сите', $FontStyle, $paragraphStyle);
				$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['unit'], $FontStyle, $paragraphStyle);
				$table_zern->addCell(null, $cellRowSpan)->addText($tableZern['method']['view'], $FontStyle, $paragraphStyle);
//				$table_zern->addCell(null, $cellAllCentered)->addText('Частный', $FontStyle, $paragraphStyle);
				foreach ($tableZern['a'] as $key_a => $a) {
					$table_zern->addCell(null, $cellAllCentered)->addText($a, $FontStyle, $paragraphStyle);
				}
				$table_zern->addRow(['exactHeight' => 500]);
				$table_zern->addCell(null, $cellRowContinue);
				$table_zern->addCell(null, $cellAllCentered)->addText('Полный остаток на каждом сите', $FontStyle, $paragraphStyle);
				$table_zern->addCell(null, $cellRowContinue);
				$table_zern->addCell(null, $cellRowContinue);
//				$table_zern->addCell(null, $cellAllCentered)->addText('Полный', $FontStyle, $paragraphStyle);
				foreach ($tableZern['p'] as $key_p => $p) {
					$table_zern->addCell(null, $cellAllCentered)->addText($p, $FontStyle, $paragraphStyle);
				}
				if ($tableZern['req_in_protocol'] == 1) {
					$table_zern->addRow(['exactHeight' => 500]);
					$table_zern->addCell(null, $cellColSpan4)->addText("Требования {$tableZern['name']}", $FontStyle, $paragraphStyle);
					foreach ($tableZern['norm'] as $key_norm => $norm) {
						$table_zern->addCell(null, $cellAllCentered)->addText($norm, $FontStyle, $paragraphStyle);
					}
				}
			}
			$tableAllResult[] = $tableZern;
		}

		// Морозостойкость
        if ( !empty($frost) ) {
            $section = $phpWord->addSection();
            $table_frost = $section->addTable($styleTable);
            $table_frost->addRow(null, array('tblHeader' => true));
            $table_frost->addCell(null, $cellRowSpan)->addText('Объект испытаний (шифр проб/образцов в ИЦ)', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Метод испытаний, число циклов замораживания и оттаивания', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Определяемые характеристики контрольных образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Ед. изм.', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Результаты испытаний контрольных образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Определяемые характеристики основных образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Ед. изм.', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText('Результаты испытаний основных образцов', array('size' => 10), $cellAllCentered);

            $table_frost->addRow();
            $table_frost->addCell(null, $cellRowSpan)->addText($frost['Object'], array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellRowSpan)->addText($frost['Normdocmet'], array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('Наличие трещин, сколов, шелушения', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('-', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['ControlDamage']), array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('Наличие трещин, сколов, шелушения', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('-', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['MainDamage']), array('size' => 10), $cellAllCentered);

            $table_frost->addRow();
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellAllCentered)->addText('Среднее уменьшение массы образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('%', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['ControlMass']), array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('Среднее уменьшение массы образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('%', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['MainMass']), array('size' => 10), $cellAllCentered);

            $table_frost->addRow();
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellAllCentered)->addText('Прочность при сжатии насыщенных образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('МПа', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['ControlStrength']), array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('Прочность при сжатии образцов после испытания', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('МПа', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['MainStrength']), array('size' => 10), $cellAllCentered);

            $table_frost->addRow();
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellAllCentered)->addText('Средняя прочность при сжатии насыщенных образцов', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('МПа', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['ControlMedium']), array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('Средняя прочность при сжатии образцов после испытания', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('МПа', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['MainMedium']), array('size' => 10), $cellAllCentered);

            $table_frost->addRow();
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellRowContinue);
            $table_frost->addCell(null, $cellAllCentered)->addText('Нижняя граница доверительного интервала XminI', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('МПа', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['ControlBottomLine']), array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('Нижняя граница доверительного интервала XminII', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText('МПа', array('size' => 10), $cellAllCentered);
            $table_frost->addCell(null, $cellAllCentered)->addText(str_replace('.', ',', $frost['MainBottomLine']), array('size' => 10), $cellAllCentered);

            $table_frost->addRow();
            $table_frost->addCell(null, array('gridSpan' => 8, 'valign' => 'center'))->addText("Примечания: Соотношение XminII ≥ 0,9 XminI {$frost['Ratio']} <w:br/> Образцы {$frost['Tested']} испытание на морозостойкость и {$frost['Match']} марке по морозостойкости {$frost['Mark']}", array('size' => 10), $cellAllCentered);

            $tableAllResult[] = $frost;
        }

        // НК
        if ( !empty($tableData['nk']) ) {
            $table_nk = [];
            $tableAllNk = [];
            $section = $phpWord->addSection();
            foreach ($tableData['nk'] as $k => $val) {
                $table_nk[$k] = $section->addTable($styleTable);
                $table_nk[$k]->addRow(null, array('tblHeader' => true));
                $table_nk[$k]->addCell(null, $cellRowSpan)->addText('Наименование, месторасполо-жение и дата бетонирования конструкции', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowSpan)->addText('Дата бетонирования', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowSpan)->addText('Определяемые характеристики конструкции', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowSpan)->addText('Ед. изм.', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellColSpan2)->addText('Требование к характеристике', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowSpan)->addText('Нормативный документ на метод испытания(раздел, пункт)', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellColSpan2)->addText('Результаты испытаний', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowSpan)->addText('Соответствие характеристики требованиям нормативной (проектной) документации', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addRow(null, array('tblHeader' => true));
                $table_nk[$k]->addCell(null, $cellRowContinue);
                $table_nk[$k]->addCell(null, $cellRowContinue);
                $table_nk[$k]->addCell(null, $cellRowContinue);
                $table_nk[$k]->addCell(null, $cellRowContinue);
                $table_nk[$k]->addCell(null)->addText('Нормативная (проектная) документация(раздел, пункт)', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null)->addText('Нормативное значение', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowContinue);
                $table_nk[$k]->addCell(null)->addText('Фактическое единичное значение характеристики (по участкам конструкции)', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null)->addText('Фактическое значение характеристики конструкции', array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell(null, $cellRowContinue);
                $table_nk[$k]->addRow(null, array('tblHeader' => true));
                $table_nk[$k]->addCell()->addText(1, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(2, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(3, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(4, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(5, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(6, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(7, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(8, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(9, array('size' => 10), $cellAllCentered);
                $table_nk[$k]->addCell()->addText(10, array('size' => 10), $cellAllCentered);
                $curId = 0;
                $comment = '';
                foreach ($val as $key => $tableRes) {
                    if ($key === 'name_table') {
                        $tableAllNk[$k] = [
                            $key => $tableRes
                        ];
                        continue;
                    }
                    if ($key === 'count') {
                        continue;
                    }
                    $table_nk[$k]->addRow();
                    if ($tableRes['id'] != $curId) {
                        $curId = $tableRes['id'];
                        $table_nk[$k]->addCell(null, $cellRowSpan)->addText($tableRes['Object'], array('size' => 10), $cellAllCentered);
                        $table_nk[$k]->addCell(null, $cellRowSpan)->addText($tableRes['Date'], array('size' => 10), $cellAllCentered);
                    } else {
                        $table_nk[$k]->addCell(null, $cellRowContinue);
                        $table_nk[$k]->addCell(null, $cellRowContinue);
                    }
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Specification'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Ed'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Normdoc'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Normval'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Normdocmet'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['SingleValues'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Values'], array('size' => 10), $cellAllCentered);
                    $table_nk[$k]->addCell(null, $cellAllCentered)->addText($tableRes['Result'], array('size' => 10), $cellAllCentered);
                    if (!empty($tableRes['Comment'])) {
                        $comment = $tableRes['Comment'];
                    }
                }
                if (!empty($comment)) {
                    $table_nk[$k]->addRow(null, array('tblHeader' => true));
                    $table_nk[$k]->addCell(null, $cellColSpan10)->addText($comment, array('size' => 10), $cellAllCentered);
                }

                $footer = $section->addFooter();
                $footer->addPreserveText('Text', null, array('align' => 'right'));
            }


            if ($tableData['gradation']) {
                $section = $phpWord->addSection();
                $gradation = [];
                $gradationAllNk = [];
                foreach ($tableData['gradation'] as $k => $val) {
                    $gradation[$k] = $section->addTable($styleTable);
                    $gradation[$k]->addRow(null, array('tblHeader' => true));
                    $gradation[$k]->addCell(null, $cellRowSpan)->addText('Наименование, дата изготовления контрольных образцов-кубов', array('size' => 10), $cellAllCentered);
                    $gradation[$k]->addCell(null, $cellRowSpan)->addText($val['MeasuringDevice'] === 'УКС' ? 'Скорость распространения ультразвука, м/с' : 'Прочность бетона на сжатие, ИПС, МПа', array('size' => 10), $cellAllCentered);
                    $gradation[$k]->addCell(null, $cellColSpan2)->addText('Прочность, МПа', array('size' => 10), $cellAllCentered);
                    $gradation[$k]->addCell(null, $cellRowSpan)->addText('Условие отбраковки |RiH-Riф|/S', array('size' => 10), $cellAllCentered);
                    $gradation[$k]->addRow(null, array('tblHeader' => true));
                    $gradation[$k]->addCell(null, $cellRowContinue);
                    $gradation[$k]->addCell(null, $cellRowContinue);
                    $gradation[$k]->addCell(null)->addText('По результатам испытаний методом отрыва со скалыванием', array('size' => 10), $cellAllCentered);
                    $gradation[$k]->addCell(null)->addText('По градуировочной зависимости', array('size' => 10), $cellAllCentered);
                    $gradation[$k]->addCell(null, $cellRowContinue);
                    foreach ($val['Name'] as $key => $res) {
                        $gradation[$k]->addRow();
                        $gradation[$k]->addCell(null, $cellAllCentered)->addText($res, array('size' => 10), $cellAllCentered);
                        $gradation[$k]->addCell(null, $cellAllCentered)->addText($val['Mean'][$key], array('size' => 10), $cellAllCentered);
                        $gradation[$k]->addCell(null, $cellAllCentered)->addText($val['ShearStrength'][$key], array('size' => 10), $cellAllCentered);
                        $gradation[$k]->addCell(null, $cellAllCentered)->addText($val['GradationStrength'][$key], array('size' => 10), $cellAllCentered);
                        $gradation[$k]->addCell(null, $cellAllCentered)->addText($val['Condition'][$key], array('size' => 10), $cellAllCentered);
                    }

                    if (!empty($val['Comment'])) {
                        $gradation[$k]->addRow(null, array('tblHeader' => true));
                        $gradation[$k]->addCell(null, $cellColSpan10)->addText($val['Comment'], array('size' => 10), array('align' => 'left', 'valign' => 'center'));
                    }
                    if (!empty($val['Note'])) {
                        $gradation[$k]->addRow(null, array('tblHeader' => true));
                        $gradation[$k]->addCell(null, $cellColSpan10)->addText($val['Note'], array('size' => 10), array('align' => 'left', 'valign' => 'center'));
                    }

                    $footer = $section->addFooter();
                    $footer->addPreserveText('Text', null, array('align' => 'right'));

                    $gradationAllNk[$k] = ['name_table' => $val['table_name']];
                }


                $plot = [];
                $plotTable = [];
                $chartAllNk = [];
                $section = $phpWord->addSection();
                foreach ($tableData['gradation'] as $k => $val) {
                    $chartAllNk[$k] = [
                        'name_plot' => $val['name_plot'],
                        'chart_search' => $val['chart_search'],
                    ];

                    $plotTable[$k] = $section->addTable($cellHCentered);
                    $plotTable[$k]->addRow();
                    //$plotTable[$k]->addCell()->addText('${myImage}');
                    $plotTable[$k]->addCell()->addText('${myImage}');
                    $plotTable[$k]->addRow();
                    $plotTable[$k]->addCell()->addText("Рисунок 1 – Графическое изображение градуировочной зависимости <w:br/>", ['size' => 10], $cellHCentered);
                    $plotTable[$k]->addRow();
                    $plotTable[$k]->addCell()->addText($val['Description'], ['size' => 10], ['spaceBefore' => 5, 'align' => 'left']);

                    //$plot[$k] = $dir."/".$fileName;
                    $plot[$k] = $val['Chart'];

                }


            }
        }

        // Расчуёт среднего значения с единичными значениями для стандартного протокола
        // (Данные для отдельной таблицы с единичными значениями, если отмечен checkbox "Выводить единичные значения в протокол?" и Тип протокола не упрощённый)
        if (!empty($tableData['sred']) && !$protocolShort) {
            $section = $phpWord->addSection();
            $table_sred = $section->addTable($styleTable);
            $table_sred->addRow(null, array('tblHeader' => true));
            $table_sred->addCell(null, $cellRowSpan)->addText('Объект испытаний (шифр проб/образцов в ИЦ)', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null, $cellRowSpan)->addText('Определяемая характеристика (показатель)', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null, $cellRowSpan)->addText('Ед. изм.', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null, $cellColSpan3)->addText('Требования нормативных документов', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null, $cellRowSpan)->addText('Документ, устанавливающий правила и методы исследований (испытаний) и измерений (раздел, пункт)', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null, $cellColSpan2)->addText('Результаты исследований (испытаний) и измерений', array('size' => 10), $cellAllCentered);
            if ($results['no_compliance'] == 0) {
                $table_sred->addCell(null, $cellRowSpan)->addText('Соответствие характеристики (показателя) требованиям нормативной (проектной) документации', array('size' => 10), $cellAllCentered);
            }
            $table_sred->addRow(null, array('tblHeader' => true));
            $table_sred->addCell(null, $cellRowContinue);
            $table_sred->addCell(null, $cellRowContinue);
            $table_sred->addCell(null, $cellRowContinue);
            $table_sred->addCell(null)->addText('нормативная (проектная) документация (раздел, пункт)', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null)->addText('наименование характеристики (показателя)', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null)->addText('нормативное значение характеристики (показателя)', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null, $cellRowContinue);
            $table_sred->addCell(null)->addText('Единичное', array('size' => 10), $cellAllCentered);
            $table_sred->addCell(null)->addText('Среднее', array('size' => 10), $cellAllCentered);
			if ($results['no_compliance'] == 0) {
				$table_sred->addCell(null, $cellRowContinue);
			}
            $table_sred->addRow(null, array('tblHeader' => true));
            $table_sred->addCell()->addText(1, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(2, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(3, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(4, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(5, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(6, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(7, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(8, array('size' => 10), $cellAllCentered);
            $table_sred->addCell()->addText(9, array('size' => 10), $cellAllCentered);
            if ($results['no_compliance'] == 0) {
                $table_sred->addCell()->addText(10, array('size' => 10), $cellAllCentered);
            }
            foreach ($tableData['sred'] as $key => $tableRes) {
                if ($key === 'count' || $key === 'name_table' || $key === 'no_compliance') {
                    continue;
                }
                $curId = 0;
                $table_sred->addRow();
                if ($tableRes['id'] != $curId) {
                    $curId = $tableRes['id'];
                    $table_sred->addCell(null, $cellRowSpan)->addText($tableRes['Object'], array('size' => 10), $cellAllCentered);
                } else {
                    $table_sred->addCell(null, $cellRowContinue);
                }
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Specification'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Ed'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Normdoc'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['SpecificationTU'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Normval'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Normdocmet'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['SingleValues'], array('size' => 10), $cellAllCentered);
                $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Values'], array('size' => 10), $cellAllCentered);
                if ($results['no_compliance'] == 0) {
                    $table_sred->addCell(null, $cellAllCentered)->addText($tableRes['Result'], array('size' => 10), $cellAllCentered);
                }
            }
        }

        //Таблица результатов
        if ($protocolShort) {
            if (!empty($tableData['sred'])) { // Сокращённый протокол с единичными значениями
                $section = $phpWord->addSection();
                $table_result = $section->addTable($styleTable);
                $table_result->addRow(null, array('tblHeader' => true));
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2600, ['vMerge' => 'restart'])->addText('Определяемая характеристика (показатель)', $FontStyle, $paragraphStyle);
                $table_result->addCell(800, ['vMerge' => 'restart'])->addText('Ед. изм.', $FontStyle, $paragraphStyle);
                $table_result->addCell('auto', $cellColSpan3)->addText('Требования нормативных документов', $FontStyle, $paragraphStyle);
                $table_result->addCell(1800, ['vMerge' => 'restart'])->addText('Нормативный документ на метод испытания (раздел,пункт)', $FontStyle, $paragraphStyle);
                $table_result->addCell('auto', $cellColSpan2)->addText('Фактические значения показателя', $FontStyle, $paragraphStyle);
				if ($results['no_compliance'] == 0) {
					$table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Соответствие характеристики требованиям нормативной (проектной) документации', $FontStyle, $paragraphStyle);
				}
                $table_result->addRow(null, array('tblHeader' => true));
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2600, $cellRowContinue);
                $table_result->addCell(800, $cellRowContinue);
                $table_result->addCell(1400)->addText('нормативная (проектная) документация (раздел,пункт)', $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText('наименование показателя', $FontStyle, $paragraphStyle);
                $table_result->addCell(1200)->addText('нормативное значение показателя', $FontStyle, $paragraphStyle);
                $table_result->addCell(1800, $cellRowContinue);
                $table_result->addCell($results['no_compliance'] == 0 ? 750 : 480)->addText('Единичное', $FontStyle, $paragraphStyle);
                $table_result->addCell($results['no_compliance'] == 0 ? 800 : 800)->addText('Среднее', $FontStyle, $paragraphStyle);
				if ($results['no_compliance'] == 0) {
					$table_result->addCell(1400, $cellRowContinue);
				}
                $table_result->addRow(null, array('tblHeader' => true));
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2600)->addText(1, $FontStyle, $paragraphStyle);
                $table_result->addCell(800)->addText(2, $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText(3, $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText(4, $FontStyle, $paragraphStyle);
                $table_result->addCell(1200)->addText(5, $FontStyle, $paragraphStyle);
                $table_result->addCell(1800)->addText(6, $FontStyle, $paragraphStyle);
                $table_result->addCell(1800)->addText(7, $FontStyle, $paragraphStyle);
                $table_result->addCell(1800)->addText(8, $FontStyle, $paragraphStyle);
				if ($results['no_compliance'] == 0) {
					$table_result->addCell(1400)->addText(9, $FontStyle, $paragraphStyle);
				}

                foreach ($results as $key => $tableRes) {
                    if ($key === 'count' || $key === 'name_table' || $key === 'no_compliance') {
                        continue;
                    }
                    $table_result->addRow('auto');
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Specification'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Ed'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdoc'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['SpecificationTU'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normval'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdocmet'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['SingleValues'] ?? '-', $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Values'], $FontStyle, $paragraphStyle);
					if ($results['no_compliance'] == 0) {
						$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Result'], $FontStyle, $paragraphStyle);
					}
                }
            } else { // Сокращённый протокол без единичных значений
//			if ($_SESSION['SESS_AUTH']['USER_ID'] == 61) {
//				echo '<pre>';
//				print_r($results);
//				exit();
//				$section = $phpWord->addSection();
//				$table_result = $section->addTable($styleTable);
//				$table_result->addRow(null, array('tblHeader' => true));
//				$table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Определяемая характеристика (показатель)', $FontStyle, $paragraphStyle);
//				$table_result->addCell(800, ['vMerge' => 'restart'])->addText('Ед. изм.', $FontStyle, $paragraphStyle);
//				$table_result->addCell('auto', $cellColSpan3)->addText('Требования нормативных документов', $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Нормативный документна метод испытания (раздел,пункт)', $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Фактические значения показателя', $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Соответствие характеристики требованиям нормативной (проектной) документации', $FontStyle, $paragraphStyle);
//				$table_result->addRow(null, array('tblHeader' => true));
//				$table_result->addCell(1400, $cellRowContinue);
//				$table_result->addCell(800, $cellRowContinue);
//				$table_result->addCell(1400)->addText('Нормативная (проектная) документация (раздел,пункт)', $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400)->addText('Наименование показателя', $FontStyle, $paragraphStyle);
//				$table_result->addCell(1200)->addText('Нормативное значение показателя', $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400, $cellRowContinue);
//				$table_result->addCell(1400, $cellRowContinue);
//				$table_result->addCell(1400, $cellRowContinue);
//				$table_result->addRow(null, array('tblHeader' => true));
//				$table_result->addCell(1400)->addText(1, $FontStyle, $paragraphStyle);
//				$table_result->addCell(800)->addText(2, $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400)->addText(3, $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400)->addText(4, $FontStyle, $paragraphStyle);
//				$table_result->addCell(1200)->addText(5, $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400)->addText(6, $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400)->addText(7, $FontStyle, $paragraphStyle);
//				$table_result->addCell(1400)->addText(8, $FontStyle, $paragraphStyle);
//				foreach ($results as $key => $tableRes) {
//					if ($key === 'count' || $key === 'name_table') {
//						continue;
//					}
//					$table_result->addRow();
//					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Specification'], $FontStyle, $paragraphStyle);
//					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Ed'], $FontStyle, $paragraphStyle);
//					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdoc'], $FontStyle, $paragraphStyle);
//					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['SpecificationTU'], $FontStyle, $paragraphStyle);
////					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normval'], $FontStyle, $paragraphStyle);
////					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdocmet'], $FontStyle, $paragraphStyle);
////					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Values'], $FontStyle, $paragraphStyle);
////					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Result'], $FontStyle, $paragraphStyle);
//				}
//			} else {
//			if ($_SESSION['SESS_AUTH']['USER_ID'] == 61) {
//					echo '<pre>';
//					print_r($results);
//					exit($results);
//				}
                $section = $phpWord->addSection();
                $table_result = $section->addTable($styleTable);
                $table_result->addRow(null, array('tblHeader' => true));
//				if ($results['no_compliance'] == 1) {
//					$table_result->addCell(3050, ['vMerge' => 'restart'])->addText('Объект испытаний (шифр проб/образцов в ИЦ)', $FontStyle, $paragraphStyle);
//				}
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 3050, ['vMerge' => 'restart'])->addText('Определяемая характеристика (показатель)', $FontStyle, $paragraphStyle);
                $table_result->addCell(800, ['vMerge' => 'restart'])->addText('Ед. изм.', $FontStyle, $paragraphStyle);
//				if ($results['no_compliance'] == 0) {
                $table_result->addCell('auto', $cellColSpan3)->addText('Требования нормативных документов', $FontStyle, $paragraphStyle);
//				}
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2100, ['vMerge' => 'restart'])->addText('Нормативный документ на метод испытания (раздел,пункт)', $FontStyle, $paragraphStyle);
                $table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Фактические значения показателя', $FontStyle, $paragraphStyle);
                if ($results['no_compliance'] == 0) {
                    $table_result->addCell(1400, ['vMerge' => 'restart'])->addText('Соответствие характеристики требованиям нормативной (проектной) документации', $FontStyle, $paragraphStyle);
                }

                $table_result->addRow(null, array('tblHeader' => true));
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2100, $cellRowContinue);
                $table_result->addCell(800, $cellRowContinue);
//				if ($results['no_compliance'] == 0) {
                $table_result->addCell(1400)->addText('нормативная (проектная) документация (раздел,пункт)', $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText('наименование показателя', $FontStyle, $paragraphStyle);
                $table_result->addCell(1200)->addText('нормативное значение показателя', $FontStyle, $paragraphStyle);
//				}
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2100, $cellRowContinue);
                $table_result->addCell(1400, $cellRowContinue);
				if ($results['no_compliance'] == 0) {
					$table_result->addCell(1400, $cellRowContinue);
				}

                $table_result->addRow(null, array('tblHeader' => true));
                $table_result->addCell($results['no_compliance'] == 0 ? 1400 : 2100)->addText(1, $FontStyle, $paragraphStyle);
                $table_result->addCell(800)->addText(2, $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText(3, $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText(4, $FontStyle, $paragraphStyle);
                $table_result->addCell(1200)->addText(5, $FontStyle, $paragraphStyle);
//				if ($results['no_compliance'] == 0) {
                $table_result->addCell(2100)->addText(6, $FontStyle, $paragraphStyle);
                $table_result->addCell(1400)->addText(7, $FontStyle, $paragraphStyle);
                if ($results['no_compliance'] == 0) {
                    $table_result->addCell(1400)->addText(8, $FontStyle, $paragraphStyle);
                }
                foreach ($results as $key => $tableRes) {
                    if ($key === 'count' || $key === 'name_table' || $key === 'no_compliance') {
                        continue;
                    }
                    $curId = 0;
                    $table_result->addRow();
//					if ($results['no_compliance'] == 1) {
//						if ($tableRes['id'] != $curId) {
//							$curId = $tableRes['id'];
//							$table_result->addCell(null, $cellRowSpan)->addText($tableRes['Object'], $FontStyle, $cellAllCentered);
//						} else {
//							$table_result->addCell(null, $cellRowContinue);
//						}
//					}
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Specification'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Ed'], $FontStyle, $paragraphStyle);
//					if ($results['no_compliance'] == 0) {
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdoc'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['SpecificationTU'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normval'], $FontStyle, $paragraphStyle);
//					}
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdocmet'], $FontStyle, $paragraphStyle);
                    $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Values'], $FontStyle, $paragraphStyle);
                    if ($results['no_compliance'] == 0) {
                        $table_result->addCell(null, $cellAllCentered)->addText($tableRes['Result'], $FontStyle, $paragraphStyle);
                    }
                }
//			}
            }
        } else { // Не сокращённый и без единичных значений протокол

            $section = $phpWord->addSection();
            $table_result = $section->addTable($styleTable);
//			if ($_SESSION['SESS_AUTH']['USER_ID'] != 61) {
            $table_result->addRow(null, array('tblHeader' => true));
            $table_result->addCell(null, $cellRowSpan)->addText('Объект испытаний (шифр проб/образцов в ИЦ)', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null, $cellRowSpan)->addText('Определяемая характеристика (показатель)', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null, $cellRowSpan)->addText('Ед. изм.', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null, $cellColSpan3)->addText('Требования нормативных документов', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null, $cellRowSpan)->addText('Документ, устанавливающий правила и методы исследований (испытаний) и измерений (раздел, пункт)', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null, $cellRowSpan)->addText('Результаты исследований (испытаний) и измерений', array('size' => 10), $cellAllCentered);
			if ($results['no_compliance'] != 1) {
				$table_result->addCell(null, $cellRowSpan)->addText('Соответствие характеристики (показателя) требованиям нормативной (проектной) документации', array('size' => 10), $cellAllCentered);
			}
            $table_result->addRow(null, array('tblHeader' => true));
            $table_result->addCell(null, $cellRowContinue);
            $table_result->addCell(null, $cellRowContinue);
            $table_result->addCell(null, $cellRowContinue);
            $table_result->addCell(null)->addText('нормативная (проектная) документация (раздел, пункт)', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null)->addText('наименование характеристики (показателя)', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null)->addText('нормативное значение характеристики (показателя)', array('size' => 10), $cellAllCentered);
            $table_result->addCell(null, $cellRowContinue);
            $table_result->addCell(null, $cellRowContinue);
				if ($results['no_compliance'] != 1) {
					$table_result->addCell(null, $cellRowContinue);
				}
            $table_result->addRow(null, array('tblHeader' => true));
            $table_result->addCell()->addText(1, array('size' => 10), $cellAllCentered);
            $table_result->addCell()->addText(2, array('size' => 10), $cellAllCentered);
            $table_result->addCell()->addText(3, array('size' => 10), $cellAllCentered);
            $table_result->addCell()->addText(4, array('size' => 10), $cellAllCentered);
            $table_result->addCell()->addText(5, array('size' => 10), $cellAllCentered);
            $table_result->addCell()->addText(6, array('size' => 10), $cellAllCentered);
            $table_result->addCell()->addText(7, array('size' => 10), $cellAllCentered);
            //if (isset($results[0]['SingleValues'])) {
            //    $table_result->addCell($cellColSpan2)->addText(8, array('size' => 10), $cellAllCentered);
            //} else {
            //    $table_result->addCell()->addText(8, array('size' => 10), $cellAllCentered);
            //}
            $table_result->addCell()->addText(8, array('size' => 10), $cellAllCentered);
			if ($results['no_compliance'] != 1) {
				$table_result->addCell()->addText(9, array('size' => 10), $cellAllCentered);
			}
			$curId = 0;
				foreach ($results as $key => $tableRes) {
					if ($key === 'count' || $key === 'name_table' || $key === 'no_compliance') {
						continue;
					}
					$table_result->addRow();
					if ($tableRes['id'] != $curId) {
						$curId = $tableRes['id'];
						$table_result->addCell(null, $cellRowSpan)->addText($tableRes['Object'], array('size' => 10), $cellAllCentered);
					} else {
						$table_result->addCell(null, $cellRowContinue);
					}
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Specification'], array('size' => 10), $cellAllCentered);
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Ed'], array('size' => 10), $cellAllCentered);
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdoc'], array('size' => 10), $cellAllCentered);
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['SpecificationTU'], array('size' => 10), $cellAllCentered);
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normval'], array('size' => 10), $cellAllCentered);
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Normdocmet'], array('size' => 10), $cellAllCentered);
					//if (isset($tableRes['SingleValues'])) {
					//	$table_result->addCell(null, $cellAllCentered)->addText($tableRes['SingleValues'], array('size' => 10), $cellAllCentered);
					//}
					$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Values'], array('size' => 10), $cellAllCentered);
					if ($results['no_compliance'] != 1) {
						$table_result->addCell(null, $cellAllCentered)->addText($tableRes['Result'], array('size' => 10), $cellAllCentered);
					}
				}

        }

        $result2 = $results;

		unset($result2['count']);
		unset($result2['name_table']);
        unset($result2['no_compliance']);

		if (!empty($result2)) {
			$tableAllResult[] = $results;
		}

        // Если есть при расчёте среднего отмечен чекбокс "Выводить единичные значения в протокол?" и протокол стандартный, еще одну таблицу с единичными значениями.
        if (!empty($tableData['sred']) && !$protocolShort) {
            $tableAllResult[] = $tableData['sred'];
        }

		$countTable = count($tableAllResult?? []);
		$replacement = [];
        $replacementPlot = [];
		$numTable = 2;

		foreach ($tableAllResult as $tab) {
			$replacement[] = [
				'numTable' => $numTable,
				'table_r' => $tab['name_table'],
			];
			$numTablePre[] = $numTable;
			$numTable++;
		}

        // Неразрушающий контроль
        if (!empty($tableAllNk)) {
            $i = 0;
            foreach ($tableAllNk as $tab) {
                $tableName = $i ? '' : 'Таблица ' . $numTable;

                $replacement[] = [
                    'numTable' => $tableName,
                    'table_r' => $tab['name_table'],
                ];
                $i++;
            }
            $numTable++;

            foreach ($gradationAllNk as $k => $tab) {
                $tableName = 'Таблица ' . $numTable;

                $replacementPlot[] = [
                    'numTable' => $tableName,
                    'table_p' => $tab['name_table'],
                ];

                $replacementPlot[] = [
                    'numTable' => '',
                    'table_p' => $chartAllNk[$k]['name_plot'],
                ];
                $numTable++;
            }
        }

		$protocolInformation['numTabStr'] = implode(', ', $numTablePre);

		$template->cloneBlock('block_result', $countTable, true, false, $replacement);
		if (!empty($table_zern)) {
			$template->setComplexBlock('table_zern', $table_zern);
		}
        if (!empty($table_sred)) {
            $template->setComplexBlock('table_sred', $table_sred);
        }

        if (!empty($table_frost)) {
            $template->setComplexBlock('table_frost', $table_frost);
        } elseif (!empty($table_nk)) {
            $template->cloneBlock('block_plot', $countTable, true, false, $replacementPlot);
            foreach ($tableAllNk as $key => $val) {
                $template->setComplexBlock($val['name_table'], $table_nk[$key]);
            }

            foreach ($gradationAllNk as $key => $val) {
                $template->setComplexBlock($val['name_table'], $gradation[$key]);
            }

            // Вывод графика
            foreach ($chartAllNk as $key => $val) {
                if (!str_ends_with($plot[$key], '.png')) {
                    $template->setValue($val['name_plot'], '');
                    $template->setValue('${myImage}', '');
                    continue;
                }

                $template->setComplexBlock($val['name_plot'], $plotTable[$key]);

                $template->setImageValue('myImage', [
                    'path' => $plot[$key],
                    'width' => 680,
                    'height' => 400,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                ]);
            }
        } else {
			if (!empty($results)) {
				$template->setComplexBlock('table_result', $table_result);
			}
        }


//		$template->setComplexBlock('table_result', $table_result);
		$template->setComplexBlock('table_oborud', $table_oborud);

        //Условия проведения испытаний
        $section = $phpWord->addSection();
        $conditionHeader = $protocolInformation['inputInProtocol'] ? "8 Условия проведения испытаний:" : "";
        $conditionText = $protocolInformation['inputInProtocol'] ? "Температура: {$protocolInformation['tIspit']}<w:br/>Влажность: {$protocolInformation['vIspit']}" : "";
        $conditionsTitle = $section->addText($conditionHeader, $FontStyleTitle, $paragraphStyleText);
        $conditionsText = $section->addText($conditionText, $FontStyle, $paragraphStyleText);
        $template->setComplexBlock('ConditionsTitle', $conditionsTitle);
        $template->setComplexBlock('Conditions', $conditionsText);

		//Подписи в протоколе
		$verifyStr = '';
//        $section = $phpWord->addSection();
//        $table_verify = $section->addTable($styleTable);
        foreach ($varify as $var) {
            $verifyStr .= "{$var['WorkPos']} {$var['IC']} __________{$var['Ispolnitel']}<w:br/>";
        }

		$protocolInformation['verify_protocol'] = $verifyStr;

        $styleTableInfo = array('borderSize' => 3, 'borderColor' => '000000');

        $rowHight = 557;

        $section = $phpWord->addSection();
        $table_information = $section->addTable($styleTableInfo);
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Основание для проведения испытаний', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText($protocolInformation['nDogovor'], array('size' => 10));
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Заказчик', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText($protocolInformation['nZakazchik'], array('size' => 10));
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Адрес заказчика юридический/фактический', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText($protocolInformation['aZakazchik'], array('size' => 10));
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('ИНН/ОГРН Заказчика', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText("{$protocolInformation['innZakazchik']}/{$protocolInformation['ogrnZakazchik']}", array('size' => 10));
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Дата поступления проб/образцов (№ регистрации в ИЦ)', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText("{$protocolInformation['dProbe']} ({$protocolInformation['nProbe_reg']})", array('size' => 10));
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Место отбора; дата отбора проб/образцов*', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText("{$protocolInformation['mestoSboraProbe']}", array('size' => 10));
        if (!empty($protocolInformation['oStroit'])) {
            $table_information->addRow($rowHight);
            $table_information->addCell(null, $cellVCentered)->addText('Объект строительства*', array('size' => 10));
            $table_information->addCell(null, $cellVCentered)->addText("{$protocolInformation['oStroit']}", array('size' => 10));
        }
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Объект испытаний (шифр пробы/образца в ИЦ)', array('size' => 10));
        $table_information->addCell(null, $cellVCentered)->addText("{$protocolInformation['oIspit']}", array('size' => 10));
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Дата проведения испытаний', array('size' => 10), $cellVCentered);
        $table_information->addCell(null, $cellVCentered)->addText("{$protocolInformation['d1Ispit']}{$protocolInformation['d2Ispit']}", array('size' => 10), $cellVCentered);
        $table_information->addRow($rowHight);
        $table_information->addCell(null, $cellVCentered)->addText('Результаты испытаний', array('size' => 10), $cellVCentered);
        $table_information->addCell(null, $cellVCentered)->addText("Приведены в таблице(ах) {$protocolInformation['numTabStr']}", array('size' => 10), $cellVCentered);

        $template->setComplexBlock('table_info', $table_information);
        $template->setValues($protocolInformation);


        $template->setValues($protocolInformation);

        $template->saveAs($pathDocCurdate);

        if ( $protocolInformation['protocol_type'] == 34 || $protocolInformation['protocol_type'] == 33 || $protocolInformation['protocol_type'] == 1  ) {

            $fileDoc = new Bitrix\Main\IO\File($pathDocCurdate);

            $document = new Bitrix\DocumentGenerator\Body\Docx($fileDoc->getContents());

            $document->normalizeContent();

            $document->setValues([
                'Verify' => new Bitrix\DocumentGenerator\DataProvider\ArrayDataProvider(
                    $protocolInformation['verify_arr'],
                    [
                        'ITEM_NAME' => 'Item',
                        'ITEM_PROVIDER' => Bitrix\DocumentGenerator\DataProvider\HashDataProvider::class,
                    ]
                ),
                'VerifyItemWorkPos' => 'Verify.Item.WorkPos',
                'VerifyItemIspolnitel' => 'Verify.Item.Ispolnitel',
                'VerifyItemIspolnitel2' => 'Verify.Item.Ispolnitel2',
                'VerifyItemIC' => 'Verify.Item.IC',
            ]);

            $result = $document->process();
            $content = $document->getContent();

            file_put_contents($forSign, $content);

            file_put_contents($pathDocCurdate, $content);
        }

        if (!($protocolInformation['protocol_type'] == 34 || $protocolInformation['protocol_type'] == 33)
            && !empty($protocolInformation['number']) && is_numeric($protocolInformation['number'])) {

            $qrPath = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive/{$protocolInformation['tz_id']}{$protocolInformation['protocolYear']}/{$protocolInformation['id']}/qrNEW.png";

            QRcode::png("http://niistrom.pro/check/?NUMP=" . $protocolInformation['number'] . "&DATE=" . date('d.m.Y', strtotime($protocolInformation['dProtocol'])), $qrPath);

            $this->addQrCode($qrPath, $pathDocCurdate);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="Протокол №"'. $protocolInformation['nProtocol'] . ' от ' . $protocolInformation['dProtocol'] . '".docx"');
        readfile($pathDocCurdate);

//        $this->generateProtocol();
    }

    /**
	 * Формирование таблицы phpword для КП
     * @param $info
     * @param $arrGost
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
	public function createCommercialOfferDocument($info, $arrGost)
	{
		$result = [];
		foreach ($arrGost as $key => $val) {
			$result[$val['material_id']]['material_name'] = $val['material_name'];
			$result[$val['material_id']]['gosts'][] = [
				'method_name' => $val['method_name'],
				'gost_name' => $val['gost_name'],
				'price' => $val['price'],
				'tech_condition' => $val['tech_condition'],
				'sum' => $val['sum'],
				'amount' => $val['amount'],
			];
		}

		$template_doc = $_SERVER["DOCUMENT_ROOT"] . '/CommercialOffer.docx';

        $type = 'kp';

		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$template = new \PhpOffice\PhpWord\TemplateProcessor($template_doc);

		$styleTable = array('borderSize' => 3, 'borderColor' => '000000');
		$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
		$cellRowContinue = array('vMerge' => 'continue');
		$cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
		$cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
		$cellColSpan4 = array('gridSpan' => 4, 'valign' => 'center');
		$cellColSpan6 = array('gridSpan' => 6, 'valign' => 'center');

		$cellHCentered = array('align' => 'center');
		$cellVCentered = array('valign' => 'center');

		$section = $phpWord->addSection();
		$table = $section->addTable($styleTable);
		$table->addRow(null, array('tblHeader' => true));
		$table->addCell(2000, $cellRowSpan)->addText('Наименование объекта испытаний', array('bold' => false), $cellHCentered);
		$table->addCell(500, $cellRowSpan)->addText('Кол-во испытаний', array('bold' => false), $cellHCentered);
		$table->addCell(3000, $cellRowSpan)->addText('Определяемые характеристики', array('bold' => false), $cellHCentered);
		$table->addCell(2000, $cellRowSpan)->addText('Нормативный документ на метод испытаний', array('bold' => false), $cellHCentered);
		$table->addCell(1480, $cellRowSpan)->addText('Стоимость испытания, руб.', array('bold' => false), $cellHCentered);
		$table->addCell(1480, $cellRowSpan)->addText('Общая стоимость, руб.', array('bold' => false), $cellHCentered);
		$i = 1;
		$sumTotal = 0;
		foreach ($result as $itemMater) {
			foreach ($itemMater['gosts'] as $k => $value) {
				$table->addRow();
				if ($k == 0) {
					$table->addCell(null, $cellRowSpan)->addText($itemMater['material_name'], array('size' => 11), $cellHCentered);
				} else {
					$table->addCell(null, $cellRowContinue);
				}
				$table->addCell()->addText($value['amount'], array('size' => 11), $cellHCentered);
				$table->addCell()->addText($value['method_name'], array('size' => 11), $cellHCentered);
				$table->addCell()->addText($value['gost_name'], array('size' => 11), $cellHCentered);
				$table->addCell()->addText($value['price'], array('size' => 11), $cellHCentered);
				$table->addCell()->addText($value['sum'], array('size' => 11), $cellHCentered);
			}
		}

		$this->generateDocumentWithTable($table, $info, $template_doc, $type);

	}

	/**
	 * Формирование таблицы phpword для Приложения к договору
	 * @param $info
	 * @param $arrGost
	 * @throws \NcJoes\OfficeConverter\OfficeConverterException
	 * @throws \PhpOffice\PhpWord\Exception\CopyFileException
	 * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
	 */
	public function createTechnicalSpecificationDocument($info, $arrGost)
    {
        $template_doc = $_SERVER["DOCUMENT_ROOT"] . '/TechSpecification.docx';

        $type = 'tz';

		$result = [];
		foreach ($arrGost as $key => $val) {
			$result[$val['mtr_id']]['material_name'] = $val['material_name'];
			$result[$val['mtr_id']]['nfp'] = $val['name_for_protocol'];
			$result[$val['mtr_id']]['gosts'][] = [
				'method_name' => $val['method_name'],
				'gost_name' => $val['gost_name'],
				'price' => $val['price'],
				'tech_condition' => $val['tech_condition'] ? $val['tech_condition'] : '-',
				'sum' => $val['sum'],
				'amount' => $val['amount'],
			];
		}

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $template = new \PhpOffice\PhpWord\TemplateProcessor($template_doc);

        $styleTable = array('alignment' =>'center', 'borderSize' => 5, 'borderColor' => '000000');
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        $cellColSpan4 = array('gridSpan' => 4, 'valign' => 'center');
        $cellColSpan6 = array('gridSpan' => 6, 'valign' => 'center');

        $cellHCentered = array('align' => 'center');
        $cellVCentered = array('valign' => 'center');

        $section = $phpWord->addSection();
        $table = $section->addTable($styleTable);
        $table->addRow(null, array('tblHeader' => true));
        $table->addCell(2000, $cellRowSpan)->addText('Наименование объекта испытаний (пробы/образца)', array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(3000, $cellRowSpan)->addText('Определяемые характеристики', array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(2000, $cellRowSpan)->addText('Нормативный документ на метод испытания', array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(1500, $cellRowSpan)->addText('Нормативный документ на требования к объекту испытаний', array('size' => 10, 'bold' => true), $cellHCentered);
        $table->addCell(1500, $cellRowSpan)->addText('Цена за пробу (образец), руб.', array('size' => 10, 'bold' => true), $cellHCentered);
        $i = 1;
        $sumTotal = 0;
        foreach ($result as $itemMater) {

			foreach ($itemMater['gosts'] as $k => $value) {
				$table->addRow();
				if ($k == 0) {
					$obj = $itemMater['material_name'] . (!empty($itemMater['nfp']) ? '<w:br/>('.$itemMater['nfp'].')' : '');

					$table->addCell(null, $cellRowSpan)->addText($obj, array('size' => 9), $cellHCentered);
				} else {
					$table->addCell(null, $cellRowContinue);
				}
				$table->addCell()->addText($value['method_name'], array('size' => 10), $cellHCentered);
				$table->addCell()->addText($value['gost_name'], array('size' => 10), $cellHCentered);
				$table->addCell()->addText($value['tech_condition'], array('size' => 10), $cellHCentered);
				$table->addCell()->addText($value['price'], array('size' => 10), $cellHCentered);
			}
        }

        $this->generateDocumentWithTable($table, $info, $template_doc, $type);
    }

    /**
     * @param $table
     * @param $info
     * @param $templateDoc
     * @throws \NcJoes\OfficeConverter\OfficeConverterException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
	public function generateDocumentWithTable($table, $info, $templateDoc, $type)
	{
		$phpWord = new \PhpOffice\PhpWord\PhpWord();
		$template = new \PhpOffice\PhpWord\TemplateProcessor($templateDoc);

		$interimPath = "interim_archive_{$type}/".$info['id']."/".$info['curDateEn'].".docx";
		$interimPathPDF = "interim_archive_{$type}/".$info['id']."/".$info['curDateEn'].".PDF";
		$outputFile = $info['curDate'].".pdf";
		$outputPath = "archive_{$type}/".$info['id']."/";

		$file = $_SERVER['DOCUMENT_ROOT'] .'/protocol_generator/'.$interimPath;
		$filePDF = $_SERVER['DOCUMENT_ROOT'] .'/protocol_generator/'.$interimPathPDF;

		$newDirectory = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/interim_archive_{$type}/".$info['id'];

		if( !is_dir( $newDirectory ) ) {
			mkdir($newDirectory, 0777, true);
		}

		$template->setValues($info);

		$template->setComplexBlock('table', $table);

		$template->saveAs($file);

		if ($type === 'kp') {
		    $name_file = 'КП';
        } elseif ($type === 'tz') {
            $name_file = 'ТЗ';
        } elseif ($type === 'dog') {
            $name_file = 'CO';
        }

        $newDirectory = $_SERVER['DOCUMENT_ROOT'] ."/protocol_generator/archive_{$type}/" . $info['id'];

        if( !is_dir( $newDirectory ) ) {
            mkdir($newDirectory, 0777, true);
        }

        file_put_contents($newDirectory.'/'.$info['curDate'].".docx", $file);

        try {
            $fullPathDocx = $_SERVER['DOCUMENT_ROOT'] .'/protocol_generator/'.$interimPath;
            $fullPathPDF = $_SERVER['DOCUMENT_ROOT'] .'/protocol_generator/'.$outputPath;

            $converter  =  new OfficeConverter($fullPathDocx, $fullPathPDF);
            $converter->convertTo($outputFile);
        } catch (Exception $e) {
//            $this->pre($e);
        }

		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Disposition: attachment; filename="' . $name_file . ' №' . $info['id'] . ' от ' . date('d.m.Y', strtotime($info['date'])) .'.docx"');
		readfile($file);
	}

	/**
	 * @param $qrPath
	 * @param $pathDoc
	 * @throws \PhpOffice\PhpWord\Exception\CopyFileException
	 * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
	 */
    public function addQrCode($qrPath,$pathDoc) {
        $newTemplate = new \PhpOffice\PhpWord\Template($pathDoc);
        $newTemplate->setImageValue('image2.png', $qrPath);
        $newTemplate->saveAs($pathDoc); // Сохранение документа
    }

    /**
     * @param $dealID
     * @throws \Bitrix\Main\IO\FileNotFoundException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function actSampleGenerator($dealID)
	{
		$companyModel = new Company();
		$dealModel = new Request();
		$orderModel = new Order();

		$fileDoc = $_SERVER['DOCUMENT_ROOT'] . '/Act1.docx';
		$signPath = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive_client/{$dealID}/sign.png";
		$pathDoc = $_SERVER['DOCUMENT_ROOT'] . '/Act.docx';
		$newTemplate = new \PhpOffice\PhpWord\Template($pathDoc);
		$newTemplate->setImageValue('image1.png', $signPath);
		$newTemplate->saveAs($fileDoc);





		$fileDocx = new Bitrix\Main\IO\File($fileDoc);
        $document = new Bitrix\DocumentGenerator\Body\Docx($fileDocx->getContents());
		$document->normalizeContent();

		$actBase = $this->DB->Query("SELECT * FROM `ACT_BASE` WHERE `ID_Z`= {$dealID}")->Fetch();

		$company = $companyModel->getRequisiteByDealId($dealID);
		$deal = $dealModel->getDealById($dealID);

		$deliveryman = $actBase['deliveryman'];
		$zakaz_man = $deal['COMPANY_TITLE'];
		$num_request = explode(' ', $deal['TITLE'])[1];
		$dogovor = $orderModel->getOrderByDealId($dealID);
		$date = $actBase['ACT_DATE'] ? date("d.m.Y", strtotime($actBase['ACT_DATE'])) : date("d.m.Y");

		if ($dealID < DEAL_START_NEW_AREA) {
			$probe_arr = [];
			$probeinf = $this->DB->Query("SELECT mtr.`NAME_MATERIAL`, mtr.`ID_MATERIAL`, mtr.`OBIEM`, ptm.`cipher`, 
										(SELECT GROUP_CONCAT(`SPECIFICATION` SEPARATOR ', ') FROM `ba_gost` bg, `gost_to_probe` gtp 
										WHERE bg.`ID` = gtp.`gost_method` AND gtp.`probe_id` = ptm.`ID`) gtp 
										FROM `MATERIALS_TO_REQUESTS` mtr, `probe_to_materials` ptm 
										WHERE mtr.`ID` = ptm.`material_request_id` AND `ID_DEAL`={$dealID}");

			while ( $arrProbe = $probeinf->Fetch() ) {
				$probe_arr[] = [
					'Number' => $arrProbe['cipher'],
					'Name' => $arrProbe['NAME_MATERIAL'],
				];
				$gosts_str[] = $arrProbe['gtp'];
			}
			$gostStr = implode(', ',$gosts_str);
		} else {
			$probeinf = $this->DB->Query("SELECT m.`NAME`, umtr.`material_id`, umtr.`cipher`, um.name, um.`is_selection`
										FROM `ulab_material_to_request` umtr
										LEFT JOIN `ulab_gost_to_probe` ugtp ON umtr.`id` = ugtp.`material_to_request_id`																				
										LEFT JOIN `ulab_methods` um ON ugtp.`new_method_id` = um.`id`																				
										LEFT JOIN `MATERIALS` m ON umtr.`material_id` = m.`ID`
										WHERE umtr.`deal_id`={$dealID}");

			while ( $arrProbe = $probeinf->Fetch() ) {
				$probe_arr[$arrProbe['cipher']] = [
					'Number' => $arrProbe['cipher'],
					'Name' => $arrProbe['NAME'],
				];
				if ($arrProbe['is_selection'] == 0) {
					continue;
				}
				$gosts_str[] = $arrProbe['name'];
			}

			$gostStr = implode(', ', array_unique($gosts_str));
		}

		$document->setValues([
			'Number' => $actBase['ACT_NUM'],
			'DogovorNum' => $dogovor,
			'DatePost' => $date,
			'ZakazMan' => $zakaz_man,
			'deliveryman' => $deliveryman,
			'mesto' => $actBase['PLACE_PROBE'],
			'dateProbe' => !empty($actBase['DATE_PROBE']) ? ' от ' . date('d.m.Y', strtotime($actBase['DATE_PROBE'])) : '',
			'probe_proizv' => $actBase['PROBE_PROIZV'],
			'SPECIFICATION' => !empty($gostStr) ? 'Определяемые показатели: ' . $gostStr : '',
//			'DESCRIPTION' => !empty($sql['DESCRIPTION']) ? $sql['DESCRIPTION'] : '',
//	'QUARRY' => !empty($sql['QUARRY_ID']) ? 'Карьер: ' . $quarList['NAME'] : '',
			//--'Вставка №Заявки в шаблоны' начало--//
			'num_request' => !empty($num_request) ? 'по заявке ' . $num_request : '',
			'Probe' => new Bitrix\DocumentGenerator\DataProvider\ArrayDataProvider(
				$probe_arr,
				[
					'ITEM_NAME' => 'Item',
					'ITEM_PROVIDER' => Bitrix\DocumentGenerator\DataProvider\HashDataProvider::class,
				]
			),
			'ProbeItemName' => 'Probe.Item.Name',
			'ProbeItemNumber' => 'Probe.Item.Number',
		]);

		$result = $document->process();
		$content = $document->getContent();

// вывод непосредственно в браузер
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header("Content-Disposition: attachment;filename=\"Акт приемки проб {$actBase['ACT_NUM']} от {$date}.docx\"");
		header('Cache-Control: max-age=0');
		echo $content;

	}

	/**
	 * @param $dealID
	 * @throws \Bitrix\Main\IO\FileNotFoundException
	 * @throws \PhpOffice\PhpWord\Exception\CopyFileException
	 * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
	 */
	public function actSampleGeneratorNew($dealID)
	{
		$companyModel = new Company();
		$dealModel = new Request();
		$orderModel = new Order();
		$userModel = new User();

		$fileDoc = $_SERVER['DOCUMENT_ROOT'] . '/ActNew.docx';
		$signPath = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive_client/{$dealID}/sign.png";
		$signAct = $_SERVER['DOCUMENT_ROOT'] . "/protocol_generator/archive_client/{$dealID}/Act.docx";

		$pathDoc = $_SERVER['DOCUMENT_ROOT'] . '/ActNew.docx';
		$newTemplate = new \PhpOffice\PhpWord\Template($pathDoc);
		if (file_exists($signPath)) {
			$newTemplate->setImageValue('image1.png', $signPath);
			$newTemplate->saveAs($signAct);
		}

		$fileDocx = new Bitrix\Main\IO\File($fileDoc);
		$document = new Bitrix\DocumentGenerator\Body\Docx($fileDocx->getContents());
		$document->normalizeContent();

		$actBase = $this->DB->Query("SELECT * FROM `ACT_BASE` WHERE `ID_Z`= {$dealID}")->Fetch();

		$company = $companyModel->getRequisiteByDealId($dealID);
		$deal = $dealModel->getDealById($dealID);

		$deliveryman = $actBase['deliveryman'];
		$zakaz_man = $deal['COMPANY_TITLE'];
		$num_request = explode(' ', $deal['TITLE'])[1];
		$dogovor = $orderModel->getOrderByDealId($dealID);
		$createrArr = $userModel->getUserShortById($actBase['creater']);
		$createrStr = $createrArr['work_position'] . ' ' . $createrArr['short_name'];

		$date = $actBase['ACT_DATE'] ? date("d.m.Y", strtotime($actBase['ACT_DATE'])) : date("d.m.Y");

		$probeinf = $this->DB->Query("SELECT m.`NAME`, umtr.`material_id`, umtr.`cipher`, umtr.`name_for_protocol`, um.name
										FROM `ulab_material_to_request` umtr
										LEFT JOIN `ulab_gost_to_probe` ugtp ON umtr.`id` = ugtp.`material_to_request_id`																				
										LEFT JOIN `ulab_methods` um ON ugtp.`new_method_id` = um.`id`																				
										LEFT JOIN `MATERIALS` m ON umtr.`material_id` = m.`ID`
										WHERE umtr.`deal_id`={$dealID} and umtr.in_act = 1
										ORDER BY umtr.material_number, umtr.id ");

		while ($arrProbe = $probeinf->Fetch()) {
			$probe_arr[$arrProbe['cipher']] = [
				'Number' => $arrProbe['cipher'],
				'Name' => $arrProbe['NAME'],
				'Marker' => $arrProbe['name_for_protocol'],
			];
			$gosts_str[] = $arrProbe['name'];
		}

		$gostStr = implode(', ', array_unique($gosts_str));

		$document->setValues([
			'Number' => $actBase['ACT_NUM'],
			'DogovorNum' => $dogovor,
			'DatePost' => $date,
			'ZakazMan' => $zakaz_man,
			'deliveryman' => $deliveryman,
			'mesto' => $actBase['PLACE_PROBE'],
			'dateProbe' => !empty($actBase['DATE_PROBE']) ? ' от ' . date('d.m.Y', strtotime($actBase['DATE_PROBE'])) : '',
			'probe_proizv' => $actBase['PROBE_PROIZV'],
			'SPECIFICATION' => !empty($gostStr) ? 'Определяемые показатели: ' . $gostStr : '',
			'creater' => $createrStr,
//			'DESCRIPTION' => !empty($sql['DESCRIPTION']) ? $sql['DESCRIPTION'] : '',
//	'QUARRY' => !empty($sql['QUARRY_ID']) ? 'Карьер: ' . $quarList['NAME'] : '',
			//--'Вставка №Заявки в шаблоны' начало--//
			'num_request' => !empty($num_request) ? 'по заявке ' . $num_request : '',
			'Probe' => new Bitrix\DocumentGenerator\DataProvider\ArrayDataProvider(
				$probe_arr,
				[
					'ITEM_NAME' => 'Item',
					'ITEM_PROVIDER' => Bitrix\DocumentGenerator\DataProvider\HashDataProvider::class,
				]
			),
			'ProbeItemName' => 'Probe.Item.Name',
			'ProbeItemNumber' => 'Probe.Item.Number',
			'ProbeItemMarker' => 'Probe.Item.Marker',
		]);

		$result = $document->process();
		$content = $document->getContent();

// вывод непосредственно в браузер
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header("Content-Disposition: attachment;filename=\"Акт приемки проб {$actBase['ACT_NUM']} от {$date}.docx\"");
		header('Cache-Control: max-age=0');
		echo $content;

	}

    /**
     * @param $year
     * @param $type
     * @param null $oa
     * @param null $month
     */
    public function VerificationGraph($year, $type, $oa = null, $month = null)
    {
        $oborudModel = new Oborud();


        switch ($type) {
            case '1':
                $type_control = 'POVERKA';
                $type_oborud = 'SI';
                $doc_name = "План графика поверки, калибровки СИ за {$year} год.docx";
                $pathDoc = $_SERVER['DOCUMENT_ROOT'] . '/poverkaSINew.docx';
                $fileDoc = $_SERVER['DOCUMENT_ROOT'] . '/poverkaSINewTest.docx';
                break;
            case '2':
                $type_control = 'TECH_CHAR';
                $type_oborud = 'VO';
                $doc_name = "План графика проверки ВО за {$year}.docx";
                $pathDoc = $_SERVER['DOCUMENT_ROOT'] . '/poverkaVONew.docx';
                $fileDoc = $_SERVER['DOCUMENT_ROOT'] . '/poverkaVONewTest.docx';
                break;
            case '3':
                $type_control = 'ATTESTATION';
                $type_oborud = 'IO';
                $doc_name = "План графика атестации ИО за {$year}.docx";
                $pathDoc = $_SERVER['DOCUMENT_ROOT'] . '/attestatIONew.docx';
                $fileDoc = $_SERVER['DOCUMENT_ROOT'] . '/attestatIONewTest.docx';
                break;
            case '4':
                $type_control = 'TECH_CHAR';
                $type_oborud = 'TS';
                $doc_name = 'Проведения измерений значений нормированных параметров технических средств (вспомогательное оборудование)';
                break;
        }

        $where = '';

        if ($oa) {
            $where .= "bo.IN_AREA = 1 and ";
        }

        if ($month) {
            $where .= "(boc.date_end - interval 2 month) <= NOW() and ";
        }

        $where .= 1;

        $newTemplate = new \PhpOffice\PhpWord\TemplateProcessor($pathDoc);

        $result = [];
        $k = 1;

        $res = $this->DB->Query("SELECT bo.*, r.NAME as rName, r.Number as rNumber,
       						boc.date_end as pLast, boc.date_start as pNow FROM `ba_oborud` as bo
							left join ROOMS as r on bo.roomnumber = r.ID
							left join ba_oborud_certificate as boc on boc.oborud_id = bo.ID and boc.is_actual = 1
							where `IDENT`='{$type_oborud}' AND `METR_CONTROL`='{$type_control}'
                            AND `roomnumber` != '9' AND `roomnumber` != '10' AND `roomnumber` != '11'
                            AND LONG_STORAGE = 0 AND is_decommissioned = 0 and NO_METR_CONTROL != 1
                            and year(boc.date_end) = {$year}
							and {$where}
							order by boc.date_end");

        while ($row = $res->fetch()) {
            $result[] = [
                'num' => $k,
                'object' => $row['OBJECT'],
                'type_oborud' => $row['TYPE_OBORUD'],
                'gosreestr' => $row['GOSREESTR'],
                'reg_num' => $row['REG_NUM'],
                'factory_number' => $row['FACTORY_NUMBER'],
                'year' => $row['YEAR'],
                'class' => $row['сlass_precision_and_accuracy'],
                'measuring_range' => $row['measuring_range'],
                'mc_interval' => $row['MC_INTERVAL'],
                'poverka_last' => date('d.m.Y', strtotime($row['pNow'])),
                'poverka_place' => $row['POVERKA_PLACE'],
                'poverka' => date("d.m.Y", strtotime($row['pLast'])),
                'place' => "{$row['rName']} {$row['rNumber']}"
            ];
            $k++;
        }

        $newTemplate->cloneRowAndSetValues('num', $result);
        $newTemplate->setValue('year_graph', $year);
        $newTemplate->saveAs($fileDoc);

        // header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        // header('Content-Disposition: attachment; filename="' . $doc_name . '"');
        // readfile($fileDoc);

        $doc_name = rawurlencode($doc_name);
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $doc_name . '"; filename*=UTF-8\'\'' . $doc_name);
        readfile($fileDoc);
    }

    /**
     * @param $year
     * @param $type
     * @param null $oa
     * @param null $month
     */
    public function InventoryList($inform, $oa = '0')
    {
        $oborudModel = new Oborud();

        $pathDoc = $_SERVER['DOCUMENT_ROOT'] . '/testBD/inventoryList.docx';
        $fileDoc = $_SERVER['DOCUMENT_ROOT'] . '/testBD/inventoryListDoc.docx';


        $styleTable = array('borderSize' => 3, 'cellMarginLeft' => 0, 'cellMarginRight' => 0, 'borderColor' => '000000', 'leftFromText' => 0, 'rightFromText' => 0, 'bottomFromText' => 0);
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
        $cellColSpan3 = array('gridSpan' => 3, 'valign' => 'center');
        $cellColSpan4 = array('gridSpan' => 4, 'valign' => 'center');
        $cellColSpan6 = array('gridSpan' => 6, 'valign' => 'center');
        $cellColSpan5 = array('gridSpan' => 5, 'valign' => 'center');
        $cellColSpan8 = array('gridSpan' => 8, 'valign' => 'center');
        $cellColSpan9 = array('gridSpan' => 9, 'valign' => 'center');
        $FontStyle = ['size' => 10,
            'name' => 'Times New Roman'
        ];
        $FontStyleTitle = ['size' => 10,
            'name' => 'Times New Roman',
            'bold' => true
        ];
        $paragraphStyle = [
            'spaceAfter' => 0,
            'spaceBefore' => 0,
            'space' => ['after' => 0],
            'spacing' => 10,
            'lineHeight' => 1,
            'align' => 'center',
            'indentation' => ['left' => 0, 'right' => 0]
        ];
        $paragraphStyleText = [
            'indentation' => ['left' => 700]
        ];
        $paragraphStyleText1 = [
            'indentation' => ['left' => 825]
        ];

        $cellHCentered = array('align' => 'center');
        $cellAllCentered = array('align' => 'center', 'valign' => 'center');
        $cellVCentered = array('valign' => 'center');

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $template = new \PhpOffice\PhpWord\TemplateProcessor($pathDoc);

        $result = [];
        $k = 1;

        $info = [
            'date_inv' => date('d.m.Y', strtotime($inform['dateInv'])),
            'dateStart' => date('d.m.Y', strtotime($inform['dateInvStart'])),
            'directive_date' => date('d.m.Y', strtotime($inform['directive_date'])),
            'directive' => $inform['directive'],
            'date' => date('d.m.Y')
        ];

        $in_oa = $oa == 1 ? 'and bo.IN_AREA = 1' : '';


        $res = $this->DB->Query("SELECT bo.id, bo.NAME, bo.IDENT, FACTORY_NUMBER, REG_NUM, OBJECT, TYPE_OBORUD, place_of_installation_or_storage as lab_id, 
										ID_ASSIGN1, bl.short_name,concat(u.LAST_NAME, ' ', left(u.NAME, 1), '.', left(u.SECOND_NAME, 1)) as assigna, u.WORK_POSITION 
       									from ba_oborud as bo
										left join ba_laba as bl on bo.place_of_installation_or_storage = bl.ID
										left join b_uts_iblock_5_section as lab on lab.VALUE_ID = bl.id_dep
										left join b_user as u on lab.UF_HEAD = u.ID
										where `IDENT` IN ('SI','VO','IO') and (SPISANIE is null or SPISANIE = '') and
										LONG_STORAGE = 0 and bo.place_of_installation_or_storage != '' {$in_oa} 
										and bo.is_vagon = 0
 										ORDER BY `place_of_installation_or_storage`");
        $i = 1;
        while ($row = $res->fetch()) {
            $result[$row['lab_id']]['lab_name'] = $row['short_name'];
            $result[$row['lab_id']]['boss_name'] = $row['assigna'];
            $result[$row['lab_id']]['boss_position'] = $row['WORK_POSITION'];
            $result[$row['lab_id']][] = $row;
        }

        $styleTable = array('alignment' => 'center', 'borderSize' => 5, 'borderColor' => '000000', 'width' => '100%');
        $section = $phpWord->addSection();
        $table_oborud = $section->addTable($styleTable);

        $table_oborud->addRow(null, array('tblHeader' => true));
        $table_oborud->addCell(self::percentToTwips(5), $cellAllCentered)->addText('№п/п', array('bold' => true, 'size' => 12), $cellHCentered);
        $table_oborud->addCell(self::percentToTwips(50), $cellAllCentered)->addText('Наименование', array('bold' => true,'size' => 12), $cellHCentered);
        $table_oborud->addCell(self::percentToTwips(20), $cellAllCentered)->addText('Зав. №', array('bold' => true,'size' => 12), $cellHCentered);
        $table_oborud->addCell(self::percentToTwips(20), $cellAllCentered)->addText('Инв.№', array('bold' => true,'size' => 12), $cellHCentered);

        foreach ($result as $val) {
            $table_oborud->addRow();
            $table_oborud->addCell(null, $cellColSpan4)->addText($val['lab_name'], array('bold' => true, 'size' => 11), $cellHCentered);
            foreach ($val as $key=>$oborud) {
                if (in_array($key, ['lab_name', 'boss_name', 'boss_position'])) {continue;}
                $oborud['OBJECT'] = $oborud['OBJECT'] ? htmlspecialchars(trim($oborud['OBJECT']), ENT_QUOTES, 'UTF-8') : '';
                $table_oborud->addRow();
                $table_oborud->addCell(self::percentToTwips(5))->addText($i, array('size' => 11), $cellHCentered);
                $table_oborud->addCell(self::percentToTwips(50))->addText("{$oborud['OBJECT']} {$oborud['TYPE_OBORUD']}", array('size' => 11));
                $table_oborud->addCell(self::percentToTwips(25))->addText($oborud['FACTORY_NUMBER'], array('size' => 11), $cellHCentered);
                $table_oborud->addCell(self::percentToTwips(15))->addText($oborud['REG_NUM'], array('size' => 11), $cellHCentered);
                $i++;
            }
            $table_oborud->addRow();
            $table_oborud->addCell(null, $cellColSpan4)->addText("Материально ответственное лицо: {$val['boss_position']} <w:br/>
			<w:br/>{$val['boss_name']}", array('bold' => true, 'size' => 11));
        }

        $countStr = StringHelper::numberToRussian($i-1);

        $info['countStr'] = $countStr;

        $template->setComplexBlock('table_oborud', $table_oborud);
        $template->setValues($info);

        $template->saveAs($fileDoc);

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename=Инвентарная ведомость.docx');
        readfile($fileDoc);

    }

    /**
     * @param $protocolId
     * @return array
     */
    public function sigProtocol($protocolId)
    {
        $protocolModel = new Protocol();
        $userModel = new User();

        $userId = App::getUserId();

        $protocolInfo = $protocolModel->getProtocolById($protocolId);
        $userInfo = $userModel->getUserShortById($userId);

        if (empty($protocolInfo)) {
            return [
                'success' => false,
                'error' => 'Не удалось получить данные о протоколе.'
            ];
        }

//		$yearProtocol = substr($protocolInformation['protocolYear'],-2);
//		$numDeal = str_replace('/', '.', $protocolInformation['dealNum']);
//		$att = !empty($protocolInformation['Attestat']) ? ' С' : '';
//		$nameProtocol = "ПИ {$protocolInformation['number']}.{$yearProtocol}{$att} {$numDeal}";

        $qrPath = $protocolInfo['full_protocol_path'] . 'qrNEW.png';
        QRcode::png("https://niistrom.pro/check/index.php?NUMP=" . $protocolInfo['NUMBER'] . "&DATE=" . $protocolInfo['DATE'], $qrPath);
        $pathDoc = $protocolInfo['full_protocol_path'] . 'forsign.docx';

        try {
            $template = new \PhpOffice\PhpWord\TemplateProcessor($pathDoc);
            $template->setValue('work_position', $userInfo['work_position']);
            $template->setValue('header', $userInfo['short_name']);
            $template->setValue('date_sign', date("d.m.Y"));
            $template->saveAs($pathDoc);

            $pathFile2 = $_SERVER['DOCUMENT_ROOT'] . "/sign_".App::getUserId().".png";
            $newTemplate = new \PhpOffice\PhpWord\Template($pathDoc);
            $newTemplate->setImageValue('image2.png', $qrPath);
            $newTemplate->setImageValue('image3.jpg', $pathFile2);
            $newTemplate->setImageValue('image3.png', $pathFile2);
            $newTemplate->saveAs($protocolInfo['full_protocol_path'] . 'signed.docx'); // Сохранение документа

            $converter = new  OfficeConverter($protocolInfo['full_protocol_path'] . 'signed.docx', $protocolInfo['full_protocol_path']);
            $converter->convertTo($protocolInfo['pdf_name']); // генерирует pdf файл в том же каталоге
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Не удалось изменить документ: ' . $e->getMessage()
            ];
        }

        $base64 = $protocolModel->getBase64EncodeFile($protocolInfo['full_protocol_path'], $protocolInfo['pdf_name']);

        if (empty($base64)) {
            return [
                'success' => false,
                'error' => 'Не удалось получить base64.'
            ];
        }

        return [
            'success' => true,
            'file_base64' => $base64,
            'url_file' => PROTOCOL_GENERATOR_URL . $protocolInfo['protocol_path'] . $protocolInfo['pdf_name'],
            'pdf_file_name' => $protocolInfo['pdf_name'],
        ];
    }
}
