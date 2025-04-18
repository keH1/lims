<?php

/**
 * Модель для работы с протоколами
 * Class Protocol
 */
class Protocol extends Model
{

    /**
     * @param $dealId
     * @return array
     */
    public function getAssignedByDealId($dealId)
    {
        $users = $this->DB->Query("SELECT user_id, is_main FROM `assigned_to_request` WHERE deal_id = {$dealId}");

        $result = [];
        while ($row = $users->Fetch()) {
            $user = CUser::GetByID($row['user_id'])->Fetch();
            $name = trim($user['NAME']);
            $lastName = trim($user['LAST_NAME']);
            $shortName = StringHelper::shortName($name);

            $resultData = [
                'user_id'       => $row['user_id'],
                'name'          => trim($name),
                'last_name'     => trim($lastName),
                'user_name'     => "{$name} {$lastName}",
                'short_name'    => "{$shortName}. {$lastName}",
                'is_main'       => $row['is_main'],
                'department'    => $user["UF_DEPARTMENT"],
            ];

            $result[] = $resultData;
        }

        // TODO: старое
        if (empty($result)) {
            global $USER_FIELD_MANAGER;

            $userFields = $USER_FIELD_MANAGER->GetUserFields('CRM_DEAL', $dealId);

            $deal = CCrmDeal::GetByID($dealId);

            if ( !empty($deal['ASSIGNED_BY_ID']) ) {
                $user = CUser::GetByID($deal['ASSIGNED_BY_ID'])->Fetch();
                $name = trim($user['NAME']);
                $lastName = trim($user['LAST_NAME']);
                $shortName = StringHelper::shortName($name);

                $resultData = [
                    'user_id' => $deal['ASSIGNED_BY_ID'],
                    'name' => $name,
                    'last_name' => $lastName,
                    'user_name' => "{$lastName} {$name}",
                    'short_name' => "{$shortName}. {$lastName}",
                    'is_main' => 1,
                    'department' => $user["UF_DEPARTMENT"],
                ];

                $result[] = $resultData;
            }

            foreach ($userFields['UF_CRM_1571643970']['VALUE'] as $ass) {
                $user = CUser::GetByID($ass)->Fetch();
                $name = trim($user['NAME']);
                $lastName = trim($user['LAST_NAME']);
                $shortName = StringHelper::shortName($name);

                $resultData = [
                    'user_id'       => $ass,
                    'name'          => $name,
                    'last_name'     => $lastName,
                    'user_name'     => "{$lastName} {$name}",
                    'short_name'    => "{$shortName}. {$lastName}",
                    'is_main'       => 0,
                    'department'    => $user["UF_DEPARTMENT"],
                ];

                $result[] = $resultData;
            }
        }

        return $result;
    }


    /**
     * @param $filter
     * @return array
     */
    public function getDataToJournal($filter)
    {
        $RequestModel = new Request();
        $where = "";
        $having = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            if ( !empty($filter['search']) ) {
                // Протокол
                if ( isset($filter['search']['NUMBER_AND_YEAR']) ) {
                    $where .= "p.NUMBER_AND_YEAR LIKE '%{$filter['search']['NUMBER_AND_YEAR']}%' AND ";
                }
                if ( isset($filter['search']['ATTESTAT_IN_PROTOCOL']) ) {
					if ( $filter['search']['ATTESTAT_IN_PROTOCOL'] == '0' ) {
						$where .= "p.ATTESTAT_IN_PROTOCOL is null AND ";
					} else if ( $filter['search']['ATTESTAT_IN_PROTOCOL'] == '1' ) {
						$where .= "p.ATTESTAT_IN_PROTOCOL = 1 AND ";
					}
                }
                if ( isset($filter['search']['type_protocol']) ) {
                    if ( $filter['search']['type_protocol'] == 1 ) {
                        $where .= "p.PROTOCOL_TYPE in (33, 34, 35, 36, 37, 38, 39) AND ";
                    } else {
                        $where .= "p.PROTOCOL_TYPE not in (33, 34, 35, 36, 37, 38, 39) AND ";
                    }
                }
                // Заявка
                if ( isset($filter['search']['requestTitle']) ) {
                    $where .= "b.REQUEST_TITLE LIKE '%{$filter['search']['requestTitle']}%' AND ";
                }
                // Дата
                if ( isset($filter['search']['DATE']) ) {
                    $where .= "LOCATE('{$filter['search']['DATE']}', DATE_FORMAT(p.DATE, '%d.%m.%Y')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(p.DATE >= '{$filter['search']['dateStart']}' AND p.DATE <= '{$filter['search']['dateEnd']}') AND ";
                }
                // Клиент
                if ( isset($filter['search']['COMPANY_TITLE']) ) {
                    $where .= "b.COMPANY_TITLE LIKE '%{$filter['search']['COMPANY_TITLE']}%' AND ";
                }
                // Счет
                if ( isset($filter['search']['ACCOUNT']) ) {
                    $where .= "b.ACCOUNT LIKE '%{$filter['search']['ACCOUNT']}%' AND ";
                }
                // Объект испытаний
                if ( isset($filter['search']['MATERIAL']) ) {
                    $where .= "m.NAME LIKE '%{$filter['search']['MATERIAL']}%' AND ";
                }
                // Ответственный
                if (isset($filter['search']['ASSIGNED'])) {
                    $having .= "ASSIGNED LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
                }
                // ТЗ
                if ( isset($filter['search']['tz']) ) {
                    $where .= "b.ID LIKE '%{$filter['search']['tz']}%' AND ";
                }
                if ( isset($filter['search']['PROTOCOL_OUTSIDE_LIS']) ) {
                    if ( $filter['search']['PROTOCOL_OUTSIDE_LIS'] == 1 ) {
                        $where .= "p.PROTOCOL_OUTSIDE_LIS = 1 AND ";
                    } else {
                        $where .= "p.PROTOCOL_OUTSIDE_LIS = 0 AND ";
                    }
                }
                // Договор
                if ( isset($filter['search']['DOGOVOR_TABLE']) ) {
                    $where .= "b.DOGOVOR_TABLE LIKE '%{$filter['search']['DOGOVOR_TABLE']}%' AND ";
                }
                // Стоимость
                if ( isset($filter['search']['PRICE']) ) {
                    $where .= "b.PRICE LIKE '%{$filter['search']['PRICE']}%' AND ";
                }
                // Дата оплаты
                if ( isset($filter['search']['DATE_OPLATA']) ) {
                    $where .= "b.DATE_OPLATA LIKE '%{$filter['search']['DATE_OPLATA']}%' AND ";
                }
                // Производитель
                if ( isset($filter['search']['MANUFACTURER_TITLE']) ) {
                    $where .= "b.MANUFACTURER_TITLE LIKE '%{$filter['search']['MANUFACTURER_TITLE']}%' AND ";
                }
                // Последнее изменение (пользователь)
                if ( isset($filter['search']['USER_HISTORY']) ) {
                    $where .= "b.USER_HISTORY LIKE '%{$filter['search']['USER_HISTORY']}%' AND ";
                }
                if ( isset($filter['search']['lab']) ) {
                    $where .= "lab.id_dep = '{$filter['search']['lab']}' AND ";
                }
                // стадии
                if ( isset($filter['search']['stage']) ) {
                    switch ($filter['search']['stage']) {
                        case 1: // Пробы не поступили
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING') AND IF(b.ACT_NUM, TRUE, FALSE) = FALSE AND ";
                            break;
                        case 2: // Пробы поступили
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING', 'FINAL_INVOICE') AND IF(b.ACT_NUM, TRUE, FALSE) = TRUE AND ";
                            break;
                        case 3: // Проводятся испытания
                            $where .= "b.STAGE_ID = 1 AND ";
                            break;
                        case 4: // Испытания завершены
                            $where .= "b.STAGE_ID IN ('2', '3', '4') AND ";
                            break;
                        case 5: // Заявка неуспешна
                            $where .= "b.STAGE_ID IN ('5', '6', '7', '8', '9', 'LOSE') AND ";
                            break;
                        case 6: // Заявка не оплачена
                            $where .= "b.`PRICE` IS NOT NULL AND b.`PRICE` > 0 AND (b.`OPLATA` = 0 || b.`OPLATA` IS NULL) AND b.STAGE_ID NOT IN ('LOSE', '5', '6', '7', '8', '9', '10', '11', '12', '13') AND ";
                            break;
                        case 7: // Заявка оплачена не полностью
                            $where .= "b.OPLATA < b.PRICE AND b.`OPLATA` > 0 AND ";
                            break;
                        case 8: // По заявке переплата
                            $where .= "b.OPLATA > b.PRICE AND ";
                            break;
                        case 9: // Заявка оплачена полностью
                            $where .= "b.OPLATA = b.PRICE AND ";
                            break;
                        case 10: // Все кроме новых и неуспешных
                            $where .= "b.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING', 'FINAL_INVOICE', '1', '2', '3', '4', 'WON') AND IF(b.ACT_NUM, TRUE, FALSE) = TRUE AND ";
                            break;
                        case 11: // Успешно завершенные
                            $where .= "b.STAGE_ID = 'WON' AND ";
                            break;
                    }
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
					case 'NUMBER_AND_YEAR':
						$order['by'] = "`DATE` {$order['dir']}, NUMBER";
						break;
                    case 'ATTESTAT_IN_PROTOCOL':
                        $order['by'] = "p.ATTESTAT_IN_PROTOCOL";
                        break;
                    case 'requestTitle':
                        $order['by'] = 'b.REQUEST_TITLE';
                        break;
                    case 'DATE':
                        $order['by'] = 'p.DATE';
                        break;
                    case 'COMPANY_TITLE':
                        $order['by'] = 'b.COMPANY_TITLE';
                        break;
                    case 'ACCOUNT':
                        $order['by'] = 'b.ACCOUNT';
                        break;
                    case 'MATERIAL':
                        $order['by'] = "GROUP_CONCAT(
                            DISTINCT m.NAME 
                            SEPARATOR ', '
                        )";
                        break;
                    case 'ASSIGNED':
                        $order['by'] = "LEFT(GROUP_CONCAT(DISTINCT TRIM(CONCAT_WS(' ', usr.NAME, usr.LAST_NAME)) SEPARATOR ', '), 1)";
                        break;
                    case 'DOGOVOR_TABLE':
                        $order['by'] = 'b.DOGOVOR_TABLE';
                        break;
                    case 'PRICE':
                        $order['by'] = 'b.PRICE';
                        break;
                    case 'DATE_OPLATA':
                        $order['by'] = 'b.DATE_OPLATA';
                        break;
                    case 'USER_HISTORY':
                        $order['by'] = 'b.USER_HISTORY';
                        break;
                    case 'ATTESTAT':
                        $order['by'] = 'year(p.DATE) desc, p.IN_ATTESTAT_DIAPASON';
                        break;
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
        $having .= "1";

        $data = $this->DB->Query(
            "SELECT b.ID b_id, b.TZ, b.STAGE_ID, b.ID_Z, b.REQUEST_TITLE, b.ACT_NUM, 
                          b.COMPANY_TITLE, b.ACCOUNT, p.PROBE, b.RESULTS, b.TAKEN_ID_DEAL, 
                          b.DOGOVOR_TABLE, b.PRICE, b.OPLATA, b.DATE_OPLATA, b.PDF,
                          b.USER_HISTORY, p.NUMBER_AND_YEAR, p.DATE, p.ID protocol_id, 
                          p.NUMBER, p.PROTOCOL_TYPE, p.PDF protocol_pdf, p.PROTOCOL_OUTSIDE_LIS, p.ACTUAL_VERSION, p.is_non_actual, 
                          GROUP_CONCAT(DISTINCT TRIM(CONCAT_WS(' ', usr.NAME, usr.LAST_NAME)) SEPARATOR ', ') as ASSIGNED, 
                    CASE 
                        WHEN p.IN_ATTESTAT_DIAPASON = 1 
                            THEN 'A' 
                        ELSE '' 
                    END AS ATTESTAT, 
                    GROUP_CONCAT(
                            DISTINCT m.NAME 
                            SEPARATOR ', '
                        ) as MATERIAL
                    FROM ba_tz b
                    INNER JOIN PROTOCOLS p on p.ID_TZ = b.ID and p.NUMBER_AND_YEAR is not NULL
                    inner join ulab_gost_to_probe as ugtp on p.ID = ugtp.protocol_id
                    inner join ulab_material_to_request as umtr on umtr.id = ugtp.material_to_request_id
                    inner join MATERIALS as m on umtr.material_id = m.ID
                    inner join ulab_methods as met on ugtp.new_method_id = met.id
                    left join ulab_methods_lab as m_lab on met.id = m_lab.method_id
                    left join ba_laba as lab on m_lab.lab_id = lab.ID
                    LEFT JOIN assigned_to_request ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user usr ON ass.user_id = usr.ID 
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
                    GROUP BY p.ID 
                    HAVING {$having} 
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );


        $dataTotal = $this->DB->Query(
            "SELECT p.ID val
                    FROM ba_tz b
                    INNER JOIN PROTOCOLS p on p.ID_TZ = b.ID and p.NUMBER_AND_YEAR is not NULL
                    LEFT JOIN assigned_to_request ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user usr ON ass.user_id = usr.ID 
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' 
                    GROUP BY p.ID
                    "
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT p.ID val, GROUP_CONCAT(DISTINCT TRIM(CONCAT_WS(' ', usr.NAME, usr.LAST_NAME)) SEPARATOR ', ') as ASSIGNED 
                    FROM ba_tz b
                    INNER JOIN PROTOCOLS p on p.ID_TZ = b.ID and p.NUMBER_AND_YEAR is not NULL
                    inner join ulab_gost_to_probe as ugtp on p.ID = ugtp.protocol_id
                    inner join ulab_material_to_request as umtr on umtr.id = ugtp.material_to_request_id
                    inner join MATERIALS as m on umtr.material_id = m.ID
                    inner join ulab_methods as met on ugtp.new_method_id = met.id
                    left join ulab_methods_lab as m_lab on met.id = m_lab.method_id
                    left join ba_laba as lab on m_lab.lab_id = lab.ID
                    LEFT JOIN assigned_to_request ass ON ass.deal_id = b.ID_Z
                    LEFT JOIN b_user usr ON ass.user_id = usr.ID
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where} 
                    GROUP BY p.ID 
                    HAVING {$having}"
        )->SelectedRowsCount();

        $result = [];
        while ($row = $data->Fetch()) {
            $row['date_end_trials'] = $this->getDateEndTrials($row['protocol_id']);

            $yearProtocol = !empty($row['DATE']) ? date("Y", strtotime($row['DATE'])) : '';

            $protocol = '';
            if (empty($row['PROTOCOL_OUTSIDE_LIS'])) {
                if (!empty($row['ACTUAL_VERSION'])) {

                    $directory = !empty($row['b_id']) && !empty($yearProtocol) && !empty($row['protocol_id']) ? $row['b_id'] . $yearProtocol . '/' . $row['protocol_id'] : '';

                    if (!empty($directory) && !empty($row['NUMBER'])) {

                        $pathToPdfProtocol = current(glob(PROTOCOL_PATH. 'archive/' . $directory . '/*.pdf'));
                        $link = explode('/', $pathToPdfProtocol);
                        $fileName = end($link);

                        $pathToSigFile = PROTOCOL_PATH . 'archive/' . $directory . '/' . $row['NUMBER'] . '.sig';

                        if (isset($row['PROTOCOL_TYPE']) && in_array($row['PROTOCOL_TYPE'], [33, 34, 35, 36, 37, 38, 39]) && file_exists($pathToSigFile) && file_exists($pathToPdfProtocol)) {
                            $protocol = '/protocol_generator/archive/' . $directory . '/' . $fileName;
                        } elseif (isset($row['PROTOCOL_TYPE']) && !in_array($row['PROTOCOL_TYPE'], [33, 34, 35, 36, 37, 38, 39]) && file_exists($pathToPdfProtocol)) { //Если тип заявки НЕ упрощенный и есть файл pdf
                            $protocol = '/protocol_generator/archive/' . $directory . '/' . $fileName;;
                        }
                    }
                }
            } else {
                $protocol = !empty($row['protocol_pdf']) ? '/pdf/' . $row['protocol_id'] . "/" . $row['protocol_pdf'] : '';
//                $protocol = !empty($row['protocol_pdf']) ? $row['protocol_id'] . "/" . $row['protocol_pdf'] : '';
            }

            $y = !empty($row['NUMBER_AND_YEAR']) && in_array($row['PROTOCOL_TYPE'], [33, 34, 35, 36, 37, 38, 39]) ? ' У' : '';

            $row['NUMBER_AND_YEAR'] .= $y;

            $row['DOC'] = $protocol;

            $row['ATTESTAT_IN_PROTOCOL'] = $row['ATTESTAT_IN_PROTOCOL'] ? 'C' : '';

            if ( $row['is_non_actual'] == 1 ) {
                $stage['title'] = "Протокол неактуальный";
                $stage['color'] = "bg-red";
            } else {
                $stage = $RequestModel->getStage($row);
            }

            $row['titleStage'] = $stage['title'];
            $row['bgStage'] = $stage['color'];

            $row['b_tz_id'] = $row['b_id'];

            $row['DATE'] = date("d.m.Y", strtotime($row['DATE']));

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * @param $protocolId
     * @return string
     */
    public function getDateEndTrials($protocolId)
    {
        $result = [];
        $dates = [];

        $response = $this->DB->Query("
                                SELECT ugtp.id, ust.state, ust.date
                                FROM ulab_gost_to_probe ugtp
                                LEFT JOIN ulab_start_trials ust ON ust.ugtp_id = ugtp.id
                                LEFT JOIN ulab_methods um ON um.id = ugtp.method_id
                                WHERE ugtp.protocol_id = {$protocolId} AND um.is_selection = 0
        ");

        while($row = $response->Fetch()) {
            $result[$row['id']] = $row['state'];

            $dates[] = strtotime($row['date']);
        }

        if (empty($dates) || in_array('start', $result) || in_array(null, $result)) {
            return '--';
        } else {
            return date('d.m.Y', max($dates));
        }
    }

    /**
     * @param $row - ba_tz
     * @return string[]
     */
    public function getStage($row)
    {
        switch ($row['STAGE_ID']) {
            case 'NEW':
            case 'PREPARATION':
            case 'PREPAYMENT_INVOICE':
            case 'EXECUTING':
                $bgColor = 'bg-light-blue';
                $title = 'Испытания еще не проводились. Пробы не получены.';
                break;
            case 'FINAL_INVOICE':
                $bgColor = 'bg-yellow';
                $title = 'Пробы получены. Проводятся испытания';
                break;
            case '1':
                $bgColor = 'bg-yellow';
                $title = 'Пробы получены. Проводятся испытания.';
                break;
            case '2':
                $bgColor = 'bg-purple';
                $title = 'Испытания в лаборатории завершены. Оплата получена или не требуется.';
                break;
            case '4':
                $bgColor = 'bg-light-green';
                $title = 'Акты ВР отправлены заказчику.';
                break;
            case 'WON':
                $bgColor = 'bg-green';
                $title = 'Акты ВР получены. Заявка успешно завершена.';
                break;
            case 'LOSE':
                $bgColor = 'bg-red';
                $title = 'Испытания не проведены. Заявка прекращена.';
                break;
            case '7':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Не проводим подобные испытания.';
                break;
            case '6':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчик не вышел на связь.';
                break;
            case '5':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчика не устроила цена.';
                break;
            case '8':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Создана другая заявка.';
                break;
            case '9':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчик выбрал лабораторию в своем городе.';
                break;
            case '10':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Заказчик решил не проводить испытания.';
                break;
            case '11':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Отказались сами в связи с высокой загруженностью.';
                break;
            case '12':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Судебная экспертиза.';
                break;
            case '13':
                $bgColor = 'bg-red';
                $title = 'Заявка прекращена. Участие в тендере.';
                break;
            default:
                $bgColor = 'bg-red';
                $title = '';
                break;
        }

        if (
            $row['ACT_NUM']
            && in_array($row['STAGE_ID'], ['NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING'])
        ) {
            $bgColor='bg-yellow';
            $title = 'Протокол не выдан. Испытания еще не проводились. Пробы получены';
        }
        if (
            $row['PRICE'] && $row['RESULTS']
            && (!$row['OPLATA'] || $row['OPLATA'] < $row['PRICE'])
            && $row['STAGE_ID'] === '2'
        ) {
            $bgColor = 'bg-dark-red';
            $title = 'Испытания в лаборатории завершены. Оплата не поступила.';
        }

        $confirm = empty($row['confirm'])? 0 : 1;

        if (!empty($row['c_count']) && empty($confirm)) {
            $bgColor = 'bg-dark-blue';
            $title = 'Заявка на стадии проверки ТЗ';
        }

        return [
            'title' => $title,
            'color' => $bgColor,
        ];
    }

    /**
     * Берет sig файл из папки протокола
     * @param $dir
     * @return string
     */
    public function getSigFile($dir)
    {
        $arrayFiles = glob($dir . '/*.sig', GLOB_BRACE);

        if ( !empty($arrayFiles[0]) ) {
            return basename($arrayFiles[0]);
        }

        return '';
    }

    /**
     * Берет pdf файл из папки протокола
     * @param $dir
     * @return string
     */
    public function getPdfFile($dir)
    {
        $arrayFiles = glob($dir . '/*.pdf', GLOB_BRACE);

        if ( !empty($arrayFiles[0]) ) {
            $tmp = explode("/", $arrayFiles[0]);
            return array_pop($tmp);
        }

        return '';
    }


    /**
     * @return mixed
     */
    public function getProtocolsCount()
    {
        return $this->DB->Query("SELECT max(`NUMBER`) as `count_protocols` FROM `PROTOCOLS` 
            WHERE YEAR(`DATE`) = YEAR(CURDATE()) AND `NUMBER` IS NOT NULL AND `NUMBER` > 0")->Fetch();
    }


    /**
     * @param $protocolId
     * @return array|false
     */
    public function getProtocolById($protocolId)
    {
		$requestModel = new Request();

        $protocolInfo = $this->DB->Query("select *, year(`DATE`) as protocol_year, DEAL_ID from PROTOCOLS where ID = {$protocolId}")->Fetch();

        if ( empty($protocolInfo) ) {
            return [];
        }

		$dealInformation = $requestModel->getTzByDealId($protocolInfo['DEAL_ID']);
		$requestNum = explode('№', $dealInformation['REQUEST_TITLE'])[1];


		$yearProtocol = substr($protocolInfo['protocol_year'],-2);
		$numDeal = str_replace('/', '.', $requestNum);
		$att = !empty($protocolInformation['Attestat']) ? ' С' : '';

        $protocolInfo['protocol_path'] = "archive/{$protocolInfo['ID_TZ']}{$protocolInfo['protocol_year']}/{$protocolInfo['ID']}/";
        $protocolInfo['full_protocol_path'] = PROTOCOL_GENERATOR . "archive/{$protocolInfo['ID_TZ']}{$protocolInfo['protocol_year']}/{$protocolInfo['ID']}/";

//        $fileName = "Протокол №" . $protocolInfo['NUMBER'] . " от " . date("d.m.Y", strtotime($protocolInfo['DATE']));
        $fileName = "ПИ {$protocolInfo['NUMBER']}.{$yearProtocol}{$att} {$numDeal}";

        $protocolInfo['pdf_name'] = $fileName . '.pdf';
        $protocolInfo['doc_name'] = $fileName . '.docx';

        if ( $protocolInfo['PROTOCOL_OUTSIDE_LIS'] == 1 ) {
            $protocolInfo['outside_lis_protocol_path'] = "/ulab/upload/result/pdf/{$protocolInfo['ID']}/";
            $protocolInfo['outside_lis_full_protocol_path'] = $_SERVER['DOCUMENT_ROOT'] . "/ulab/upload/result/pdf/{$protocolInfo['ID']}/";

            $protocolInfo['new_pdf_path'] = "/pdf/{$protocolInfo['ID']}/";
            $protocolInfo['new_pdf_full_path'] = $_SERVER['DOCUMENT_ROOT'] . "/pdf/{$protocolInfo['ID']}/";

            $files = $this->getFilesFromDir($protocolInfo['outside_lis_full_protocol_path'], ["{$protocolInfo['pdf_name']}.sig"]);

            $protocolInfo['pdf_name'] = $files[0];
        }

        return $protocolInfo;
    }


    /**
     * @return array|false
     */
    public function getList()
    {
        $sql = $this->DB->Query("select * from PROTOCOLS where NUMBER IS NOT NULL ORDER BY ID desc ");

        $result = [];

        while ($row = $sql->Fetch()) {
            $date = StringHelper::dateRu($row['DATE']);
            $row['name'] = $row['NUMBER_AND_YEAR'] . " от " . $date;
            $result[] = $row;
        }

        return $result;
    }


    /**
     * Получает данные для документа протокола. Пробы, методики, ТУ
     * @param int $protocolId - ид протокола
     * @return array
     */
    public function getDataForDoc(int $protocolId)
    {
        $methodsModel = new Methods();
        $conditionModel = new TechCondition();
        $resultModel = new Result();
        $normDocModel = new NormDocGost();

        $sql = $this->DB->Query(
            "select 
                        umtr.*,
                        mater.NAME material_name,
                        ugtp.id as ugtp_id, ugtp.new_method_id as method_id, ugtp.tech_condition_id as condition_id, 
                        ugtp.normative_val, ugtp.actual_value, ugtp.match, ugtp.measuring_sheet, ugtp.norm_doc_method_id as nd_id
                    from ulab_material_to_request as umtr
                    left join MATERIALS as mater on mater.ID = umtr.material_id
                    left join ulab_gost_to_probe as ugtp on ugtp.material_to_request_id = umtr.id
                    where ugtp.protocol_id = {$protocolId} order by umtr.material_number
                    "
        );

        $result = [];
        while ($row = $sql->Fetch()) {
            $result[$row['id']]['id'] = $row['id'];
            $result[$row['id']]['mtr_id'] = $row['mtr_id'];
            $result[$row['id']]['deal_id'] = $row['deal_id'];
            $result[$row['id']]['material_id'] = $row['material_id'];
            $result[$row['id']]['probe_number'] = $row['probe_number'];
            $result[$row['id']]['material_number'] = $row['material_number'];
            $result[$row['id']]['material_group_name'] = $row['material_group_name'];
            $result[$row['id']]['cipher'] = $row['cipher'];
            $result[$row['id']]['name_for_protocol'] = $row['name_for_protocol'];
            $result[$row['id']]['protocol_id'] = $protocolId;
            $result[$row['id']]['material_name'] = $row['material_name'];
            $result[$row['id']]['ugtp_id'] = $row['ugtp_id'];
            $result[$row['id']]['nd_id'] = $row['nd_id'];
            $result[$row['id']]['normative_val'][] = $row['normative_val'];
            $result[$row['id']]['actual_value'][] = $row['actual_value'];
            $result[$row['id']]['match'][] = $row['match'];
            $result[$row['id']]['measuring_sheet'][] = $row['measuring_sheet'];
            $result[$row['id']]['gosts']['method'][] = $methodsModel->get($row['method_id']);
            $result[$row['id']]['gosts']['condition'][] = $conditionModel->get($row['condition_id']);
            $result[$row['id']]['gosts']['norm_doc'][] = $normDocModel->getMethod($row['nd_id']);
            $result[$row['id']]['gosts']['result'][] = $resultModel->getTrialResult($row['ugtp_id']);
        }

        return $result;
    }


    /**
     * Делает проверки
     * @param $protocolId - ид протокола
     * @return bool[]
     */
    public function validateProtocol($protocolId)
    {

        $oborudModel = new Oborud();

        $isSuccess = true;
        $errors = []; // тексты сообщений ошибок

        $probeData = $this->getDataForDoc($protocolId);
        $oborudData = $oborudModel->getTzObConnectByProtocolId($protocolId);
        $protocolData = $this->DB->Query("select * from PROTOCOLS where ID = {$protocolId}")->Fetch();

        if ( $protocolData['DEAL_ID'] < DEAL_START_NEW_AREA ) {
            return ['success' => true];
        }
        ////////// Проверка заявки?


        ////////// Проверка протокола
        if ( $protocolData['TEMP_O'] == null || $protocolData['TEMP_TO_O'] == null ) {
            $errors[] = "Внимание! Не указан диапазон температур у протокола";
        }
        if ( $protocolData['VLAG_O'] == null || $protocolData['VLAG_TO_O'] == null ) {
            $errors[] = "Внимание! Не указан диапазон влажности у протокола";
        }
        // TODO: Проверка если дата внесения результатов позже даты протокола

        $tempP1 = (float) $protocolData['TEMP_O'];
        $tempP2 = (float) $protocolData['TEMP_TO_O'];

        $wetP1 = (float) $protocolData['VLAG_O'];
        $wetP2 = (float) $protocolData['VLAG_TO_O'];

        ////////// Проверка на условия оборудования
        foreach ($oborudData as $oborud) {
            $anchor = "<a href='/oborud.php?ID={$oborud['b_o_id']}'>{$oborud['OBJECT']} {$oborud['TYPE_OBORUD']} {$oborud['REG_NUM']}</a>";

            // Температура
            if ( empty($oborud['TEMPERATURE']) ) { // если нормируется
                $tempO1 = (float) $oborud['TOO_EX'];
                $tempO2 = (float) $oborud['TOO_EX2'];


                if ($tempP1 < $tempO1 || $tempP2 > $tempO2) {
                    $errors[] =
                        "Внимание! Температура при проведении испытаний не соответствует условиям эксплуатации оборудования {$anchor}!";
                }
            }

            // Влажность
            if ( empty($oborud['HUMIDITY']) ) { // если нормируется
                $wetO1 = (float) $oborud['OVV_EX'];
                $wetO2 = (float) $oborud['OVV_EX2'];


                if ($wetP1 < $wetO1 || $wetP2 > $wetO2) {
                    $errors[] =
                        "Внимание! Влажность при проведении испытаний не соответствует условиям эксплуатации оборудования {$anchor}!";
                }
            }

            // Проверка на проверку
            if (!$oborud['CHECKED']) {
                $errors[] = "Протокол не может быть сформирован! Оборудование {$anchor} не проверено!";
            }

            // TODO: переделать на новое
            // Срок поверки
//            $poverka = strtotime($oborud['POVERKA']) - strtotime($protocolData['DATE_END']);
//            if ($poverka <= 0 && $oborud['IDENT'] != "OOPP" && $oborud['IDENT'] != "VO") {
//                $errors[] = "Протокол не может быть сформирован! Истек срок поверки оборудования {$anchor}!";
//            }

            // TODO: Сертификаты??
            if ($oborud['IDENT'] != "VO" && $oborud['IDENT'] != "TS" && $oborud['IDENT'] != "REACT") {

            }
        }


        ////////// Проверка методик
        foreach ($probeData as $probe) {
            foreach ($probe['gosts']['method'] as $method) {
                // методики отбора пропускаются
                if ($method['is_selection']) {
                    continue;
                }

                $anchor = "<a href='/ulab/gost/method/{$method['id']}'>{$method['view_gost_for_protocol']}</a>";

                // Температура
                if ( !$method['is_not_cond_temp'] ) { // если нормируется
                    if ($tempP1 < $method['cond_temp_1'] || $tempP2 > $method['cond_temp_2']) {
                        $errors[] =
                            "Внимание! Температура при проведении испытаний не соответствует условиям в методике {$anchor}!";
                    }
                }

                // Влажность
                if ( !$method['is_not_cond_wet'] ) { // если нормируется
                    if ($wetP1 < $method['cond_wet_1'] || $wetP2 > $method['cond_wet_2']) {
                        $errors[] =
                            "Внимание! Влажность при проведении испытаний не соответствует условиям в методике {$anchor}!";
                    }
                }

                // Актуальность методики
                if ( !$method['is_actual'] ) {
                    $errors[] =
                        "Внимание! Методика {$anchor} неактуальна!";
                }

                // Подтвержденность
                if ( !$method['is_confirm'] ) {
                    $errors[] =
                        "Внимание! Методика {$anchor} не проверена отделом метрологии!";
                }
            }
        }

//		if ($_SESSION['SESS_AUTH']['USER_ID'] == 61) {
//			echo '<pre>';
//			print_r($wet);
//			exit();
//		}


        // TODO: Проверка ТУ? Проверка результатов? Проверка зернового?


        if ( !empty($errors) ) {
            $isSuccess = false;
        }

        return [
            'success' => $isSuccess,
            'errors' => $errors,
            'data' => '',
        ];
    }


    /**
     * @param int $dealId
     * @return array
     */
    public function getProtocolsByDealId($dealId)
    {
        $response = [];

        if (empty($dealId) || $dealId < 0) {
            return $response;
        }

        $result = $this->DB->Query(
            "SELECT *, 
                    (select COUNT(*) from ulab_material_to_request where protocol_id = PROTOCOLS.ID) as probe_count,  
                    (select COUNT(*) from ulab_gost_to_probe where protocol_id = PROTOCOLS.ID) as gost_probe_count  
                    FROM PROTOCOLS WHERE DEAL_ID = {$dealId}"
        );

        while ($row = $result->Fetch()) {

            if ( $row['probe_count'] == 0 && $row['gost_probe_count'] > 0 ) {
                $row['probe_count'] = $row['gost_probe_count'];
            }

            $row['view_number'] = $row['NUMBER'] ?: 'Номер не присвоен';
            $row['date_ru'] = !empty($row['DATE']) && $row['DATE'] !== '0000-00-00' ?
                date('d.m.Y', strtotime($row['DATE'])) : '';
            $row['ostatki'] = !empty($row['ostatki']) ? unserialize($row['ostatki']) : [];
            $row['sostav'] = !empty($row['sostav']) ? unserialize($row['sostav']) : [];

            $response[] = $row;
        }

        return $response;
    }

    /**
     * @deprecated
     * @param $id
     * @return array
     */
    public function getProtocolPrice($id)
    {
        $protPrice = [];
        $labPrice = [];
        $allPrice = [];
        $protocol = $this->DB->Query("SELECT gtp.price, u2d.UF_DEPARTMENT as dep, p.ID
									FROM `PROTOCOLS` p
									LEFT JOIN `ulab_material_to_request` umtr ON p.ID = umtr.protocol_id
									LEFT JOIN `ulab_gost_to_probe` ugtp ON umtr.id = ugtp.`material_to_request_id`LEFT JOIN `MATERIALS_TO_REQUESTS` mtr ON umtr.`mtr_id` = mtr.`ID`
									LEFT JOIN `probe_to_materials` ptm ON mtr.`ID` = ptm.`material_request_id`
									LEFT JOIN `gost_to_probe` gtp ON ptm.`id` = gtp.`probe_id`
									LEFT JOIN `b_uts_user` u2d ON gtp.`assigned` = u2d.`VALUE_ID`									
									WHERE p.ID = {$id} and u2d.UF_DEPARTMENT is not NULL and p.is_non_actual = 0 group by gtp .id");

        while ($prot = $protocol->Fetch()) {
            $protPrice['allPrice'][] = $prot['price'];
            $tmp = unserialize($prot['dep']);
            if (empty($tmp)) {
                continue;
            }
            $depId = $tmp[0];
            $labPrice[$depId][] = $prot['price'];
            $allPrice['id'] = $prot['ID'];
        }

        foreach ($labPrice as $lab => $val) {
            $allPrice[$lab] = array_sum($val);
        }

        $allPrice['allPrice'] = array_sum($protPrice['allPrice']);
        return $allPrice;
    }


    /**
     * получает цены у протокола по методикам, привязанным к протоколу. протоколы с номером
     * @param int $protocolId
     * @return int|float
     */
    public function getPriceWonProtocol(int $protocolId)
    {
        $sql = $this->DB->Query(
            "
                SELECT sum(price) as price from ulab_gost_to_probe as ugtp
                inner join PROTOCOLS as p on ugtp.protocol_id = p.ID
                where p.ID = {$protocolId} and p.NUMBER is not null
            "
        )->Fetch();

        if ( empty($sql) ) {
            return 0;
        }

        return $sql['price'];
    }


	/**
	 * @param $id
	 * @return array
	 */
	public function getProtocolPriceNew($id)
	{

		$protPrice = [];
		$labPrice = [];
		$allPrice = [];
		$lab = [];
		$protocol = $this->DB->Query("SELECT ugtp.price, l.id_dep as dep, p.ID
									FROM `PROTOCOLS` p
									LEFT JOIN `ulab_material_to_request` umtr ON p.ID = umtr.protocol_id
									LEFT JOIN `ulab_gost_to_probe` ugtp ON umtr.id = ugtp.`material_to_request_id`
									LEFT JOIN `ulab_methods_lab` u2d ON ugtp.`method_id` = u2d.`method_id`
									LEFT JOIN `ba_laba` as l ON u2d.lab_id = l.ID
									WHERE p.ID = {$id} and  p.is_non_actual = 0 group by ugtp.id");

		while ($prot = $protocol->Fetch()) {
			if (empty($prot['dep'])) {
				continue;
			}
			$lab[] = $prot['dep'];
			$protPrice['allPrice'][] = $prot['price'];
			$labPrice[$prot['dep']][] = $prot['price'];
			$allPrice['id'] = $prot['ID'];
		}
		$allPrice['lab'] = array_unique($lab);

		foreach ($labPrice as $lab => $val) {
			$allPrice[$lab] = array_sum($val);
		}

		$allPrice['allPrice'] = array_sum($protPrice['allPrice']);
		return $allPrice;
	}


    /**
     * @param $protocolId
     * @param $userId
     */
	public function addDepartmentProtocol($protocolId, $userId)
    {
        $userModel = new User();

        $data = [
            'protocol_id' => $protocolId,
            'department_id' => $userModel->getDepartmentByUserId($userId)
        ];

        $this->DB->Insert('protocol_lab', $data);
    }


    /**
     * @param $protocolId
     */
	public function clearDepartmentProtocol($protocolId)
    {
        $this->DB->Query("delete from protocol_lab where protocol_id = {$protocolId}");
    }

    /**
     * @param int $protocolId
     * @param array $data
     * @return mixed
     */
    public function update(int $protocolId, array $data)
    {
        $sqlData = $this->prepearTableData('PROTOCOLS', $data);

        $where = "WHERE ID = {$protocolId}";
        return $this->DB->Update('PROTOCOLS', $sqlData, $where);
    }

    /**
     * @param int $dealId
     * @param array $outDiapason
     */
    public function updateAttestat(int $dealId, array $outDiapason)
    {
        $protocolModel = new Protocol();

        $protocols = $protocolModel->getProtocolsByDealId($dealId);

        $response = [
            'success' => true
        ];

        foreach ($protocols as $val) {
            if ((!empty($val['NUMBER']) && empty($val['EDIT_RESULTS'])) || !empty($val['INVALID'])) {
                continue;
            }

            // Фактические значения и методики у протокола в диапазоне аттестата(соответствуют условиям аттестации)?
            // 1 - в деапазоне(соответсвуют)
            $inAttestatDiapason = empty($outDiapason[$val['ID']]) ? 1 : 0;

            // Протокол с аттестатом акредитации если протокол соответствует диапазону(условиям) аттестации
            // И в результатах испытаний у протокола отмечен чекбокс "C аттестатом аккредитации"
            $attestatInProtocol = $inAttestatDiapason && !empty($val['ATTESTAT_IN_PROTOCOL']) ? 1 : 0;

            $protocolData = [
                'IN_ATTESTAT_DIAPASON' => $inAttestatDiapason,
                'ATTESTAT_IN_PROTOCOL' => $attestatInProtocol,
            ];

            $protocolModel->update($val['ID'], $protocolData);
        }

        return $response;
    }

    /**
     * Сохранить данные по оборудованию для протокола
     * @param $protocolId
     * @param $data
     */
    public function saveOborud($protocolId, $data)
    {
        $this->DB->Query("DELETE FROM TZ_OB_CONNECT WHERE PROTOCOL_ID = {$protocolId}");

        $oborudIds = !empty($data['equipment_ids']) ? json_decode($data['equipment_ids']) : [];

        foreach ($oborudIds as $id) {
            if (empty($protocolId) || empty($data['deal_id']) || empty($id)) {
                continue;
            }

            $equipmentData = [
                'ID_TZ' => $data['deal_id'],
                'ID_OB' => $id,
                'PROTOCOL_ID' => $protocolId
            ];

            $sqlData = $this->prepearTableData('TZ_OB_CONNECT', $equipmentData);
            $this->DB->Insert('TZ_OB_CONNECT', $sqlData);
        }
    }

    /**
     * @param $protocolId
     * @param $tzId
     * @param $data
     * @return bool
     */
    public function unlinkProtocolPdf($protocolId, $tzId)
    {
        if (empty($protocolId) || empty($tzId) || empty($data)) {
            return false;
        }

        $protocol = $this->getProtocolById($protocolId);

        //TODO: Временно, место хранения файлов протокола до рефакторинга
        $pathToPdfFile = PROTOCOL_PATH . 'archive/' . $tzId . date('Y', strtotime($protocol['DATE'])) . '/' . $protocolId . '/Протокол №' . $protocol['NUMBER'] . ' от ' . date('d.m.Y', strtotime($protocol['DATE'])) . '.pdf';
        $pathToSigFile = PROTOCOL_PATH . 'archive/' . $tzId . date('Y', strtotime($protocol['DATE'])) . '/' . $protocolId . '/' . $protocol['NUMBER'] . '.sig';
        $pathToForsignFile = PROTOCOL_PATH . 'archive/' . $tzId . date('Y', strtotime($protocol['DATE'])) . '/' . $protocolId . '/forsign.docx';

        //Если протокол выдан вне ЛИС удаляем файлы сформированного протокола (pdf, sig и forsign.docx)
        if (!empty($protocol['PROTOCOL_OUTSIDE_LIS'])) {
            unlink($pathToSigFile);
            unlink($pathToForsignFile);
            unlink($pathToPdfFile);
        }

        //Если тип протокола "Стандартный", то удаляем файл pdf, sig и forsign.docx
        if (!in_array($protocol['PROTOCOL_TYPE'], [PROTOCOL_TYPE_STANDARD])) {
            unlink($pathToSigFile);
            unlink($pathToForsignFile);
            unlink($pathToPdfFile);
        }

        return true;
    }


    /**
     * @param $id
     * @param $fileName
     * @param $text
     * @return array
     */
    public function saveSig($id, $fileName, $text)
    {
        $info = $this->getProtocolById($id);

        if ( empty($info) ) {
            return [
                'success' => false,
                'error' => "Не удалось получить данные"
            ];
        }

        if ( $info['PROTOCOL_OUTSIDE_LIS'] == 1 ) {
            $info['full_protocol_path'] = $info['new_pdf_full_path'];
        }


        $fileFull = $info['full_protocol_path'] . $fileName . '.sig';

        $result = file_put_contents($fileFull, $text);

        if ( empty($result) ) {
            return [
                'success' => false,
                'error' => "Не удалось создать файл: {$fileFull}"
            ];
        }

        return [
            'success' => true,
            'protocol_id' => $id,
            'file_name' => $fileName,
        ];
    }
}
