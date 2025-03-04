<?php

/**
 * Модель для работы с договорами
 * Class Order
 */
class Order extends Model {

    /**
     * @param $orderId
     * @return array|false
     */
    public function get($orderId)
    {
        return $this->DB->Query("select * from DOGOVOR where ID = {$orderId}")->Fetch();
    }

    /**
     * @param $filter
     * @return array
     */
    public function getDataToJournal($filter)
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                // Номер
                if ( isset($filter['search']['NUMBER']) ) {
                    $where .= "d.NUMBER LIKE '%{$filter['search']['NUMBER']}%' AND ";
                }
                // Дата
                if ( isset($filter['search']['DATE']) ) {
                    $where .= "LOCATE('{$filter['search']['DATE']}', DATE_FORMAT(d.DATE, '%d.%m.%Y')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(d.DATE >= '{$filter['search']['dateStart']}' AND d.DATE <= '{$filter['search']['dateEnd']}') AND ";
                }
                // Клиент
                if ( isset($filter['search']['COMPANY_TITLE']) ) {
                    $where .= "b.COMPANY_TITLE LIKE '%{$filter['search']['COMPANY_TITLE']}%' AND ";
                }
                // Клиент
                if ( isset($filter['search']['CONTRACT_TYPE']) ) {
                    $where .= "d.CONTRACT_TYPE LIKE '%{$filter['search']['CONTRACT_TYPE']}%' AND ";
                }
                // Клиент
                if ( isset($filter['search']['linkName2']) ) {
                    if ($filter['search']['linkName2'] == 1) {
                        $where .= "d.PDF is not null AND ";
                    } elseif ($filter['search']['linkName2'] == 2) {
                        $where .= "d.PDF is null AND ";
                    }
                }
                // везде
                if ( isset($filter['search']['everywhere']) ) {
                    $where .=
                        "(
                        d.NUMBER LIKE '%{$filter['search']['everywhere']}%' 
                        OR d.DATE LIKE '%{$filter['search']['everywhere']}%' 
                        OR b.COMPANY_TITLE LIKE '%{$filter['search']['everywhere']}%' 
                        ) AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }

                switch ($filter['order']['by']) {
                    case 'number':
                        $order['by'] = 'd.NUMBER';
                        break;
                    case 'DATE':
                        $order['by'] = 'd.DATE';
                        break;
                    case 'COMPANY_TITLE':
                        $order['by'] = 'b.COMPANY_TITLE';
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

        $data = $this->DB->Query(
            "SELECT b.ID b_id, b.STAGE_ID, b.ID_Z, b.COMPANY_TITLE, b.DOGOVOR_TABLE, 
                        d.ID d_id, d.NUMBER, d.DATE, d.PDF, d.ACTUAL_VER, d.IS_ACTION, d.CONTRACT_TYPE 
                    FROM ba_tz b
                    INNER JOIN DOGOVOR d on d.TZ_ID = b.ID 
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT count(*) val
                    FROM ba_tz b
                    INNER JOIN DOGOVOR d on d.TZ_ID = b.ID 
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> ''"
        )->Fetch();

        $dataFiltered = $this->DB->Query(
            "SELECT count(*) val
                    FROM ba_tz b
                    INNER JOIN DOGOVOR d on d.TZ_ID = b.ID 
                    WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}"
        )->Fetch();

        $result = [];

        while ($row = $data->Fetch()) {

            if ( !empty($row['PDF']) ) {
                $row['titleStage'] = 'Договор подписан';
                $row['bgStage'] = 'bg-green';
            } else {
                $row['titleStage'] = 'Договор не подписан';
                $row['bgStage'] = 'bg-yellow';
                $row['PDF'] = '';
            }

            if (!$row['IS_ACTION']) {
                $row['titleStage'] = 'Договор аннулирован';
                $row['bgStage'] = 'bg-red';
            }

            if ( !empty($row['ACTUAL_VER']) ) {
                $row['order_pdf'] = "{$row['d_id']}/{$row['ACTUAL_VER']}.pdf";
            } else {
                $row['order_pdf'] = '';
            }


            $row['b_tz_id'] = $row['b_id'];
            $row['DATE'] = date("d.m.Y", strtotime($row['DATE']));

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal['val'];
        $result['recordsFiltered'] = $dataFiltered['val'];

        return $result;
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
     * @param int $dealId
     */
    public function deleteContractFromRequest(int $dealId)
    {
        $this->DB->Query("DELETE FROM `DEALS_TO_CONTRACTS` WHERE `ID_DEAL` = {$dealId}");
    }


    /**
     * @param int $orderId
     * @param float $money
     */
    public function setAddedFinanceHistory(int $orderId, float $money)
    {
        $data = [
            'dogovor_id' => $orderId,
            'action' => 'добавлено',
            'money' => $money,
            'user_id' => $_SESSION['SESS_AUTH']['USER_ID'],
            'datetime' => date('Y-m-d H:i:s'),
        ];

        $dateSql = $this->prepearTableData('ulab_history_finance_order', $data);

        $this->DB->Insert('ulab_history_finance_order', $dateSql);
    }


    public function setDebitedFinanceHistory(int $orderId, float $money, int $dealId)
    {
        $data = [
            'dogovor_id' => $orderId,
            'deal_id' => $dealId,
            'action' => 'списано',
            'money' => $money,
            'user_id' => $_SESSION['SESS_AUTH']['USER_ID'],
            'datetime' => date('Y-m-d H:i:s'),
        ];

        $dateSql = $this->prepearTableData('ulab_history_finance_order', $data);

        $this->DB->Insert('ulab_history_finance_order', $dateSql);
    }


    /**
     * @param int $orderId
     * @return array
     */
    public function getFinanceHistory(int $orderId)
    {
        $userModel = new User();

        $result = [];

        $sql = $this->DB->Query(
            "select fo.*, tz.REQUEST_TITLE from ulab_history_finance_order as fo
                    left join ba_tz as tz on tz.ID_Z = fo.deal_id
                    where fo.dogovor_id = {$orderId}"
        );

        while ($row = $sql->Fetch()) {
            $user = $userModel->getUserData($row['user_id']);
            $row['user_name'] = $user['short_name'];
            $row['date'] = date('d.m.Y', strtotime($row['datetime']));

            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param int $orderId
     * @param int $dealId
     * @param float $money
     * @return array|bool[]
     */
    public function addPay(int $orderId, int $dealId, float $money)
    {
        $requestModel = new Request();

        $requestInfo = $requestModel->getTzByDealId($dealId);
        $orderInfo = $this->get($orderId);

        if ( $money > $orderInfo['finance'] ) {
            return [
                'success' => false,
                'error' => "Недостаточно средств на счете"
            ];
        }

        $summ = $requestInfo['price_discount'] - $requestInfo['OPLATA'];
        if ( $money > $summ ) {
            return [
                'success' => false,
                'error' => "Переплата. Сумма: {$money}. Необходимо: {$summ}/ {$requestInfo['price_discount']}"
            ];
        }

        if ( $money < 0 ) {
            return [
                'success' => false,
                'error' => "Невозможно"
            ];
        }

        $this->DB->Query("update ba_tz set `OPLATA` = `OPLATA` + {$money} where ID_Z = {$dealId}");
        $this->DB->Query("update DOGOVOR set `finance` = `finance` - {$money} where ID = {$orderId}");

        $this->setDebitedFinanceHistory($orderId, $money, $dealId);

        return [
            'success' => true,
        ];
    }


    /**
     * @param int $orderId
     * @param float $money
     */
    public function addFinance(int $orderId, float $money)
    {
        $result = $this->DB->Query("update DOGOVOR set `finance` = `finance` + {$money} where id = {$orderId}");

        if ( $result !== false ) {
            $this->setAddedFinanceHistory($orderId, $money);
        }
    }


    /**
     * @param int $orderId
     * @return array|false
     */
    public function getContractById(int $orderId)
    {
        return $this->DB->Query("SELECT *, CONCAT(CONTRACT_TYPE, ' №', NUMBER, ' от ', DATE_FORMAT(`DATE`, '%d.%m.%Y')) AS cont FROM `DOGOVOR` WHERE ID = {$orderId}")->Fetch();
    }

    /**
     * @param $dealId
     * @return array|false
     */
    public function getContractDealByDealId($dealId)
    {
        return $this->DB->Query("SELECT * FROM `DEALS_TO_CONTRACTS` WHERE `ID_DEAL` = {$dealId}")->Fetch();
    }


    /**
     * @param int $dealId
     * @param $contractId
     */
    public function setContractToRequest(int $dealId, $contractId)
    {
        $data = [
            'ID_DEAL' => $dealId,
            'ID_CONTRACT' => $contractId,
        ];

        $this->DB->Insert('DEALS_TO_CONTRACTS', $data);
    }


    public function getDataJournalRequest(array $filter = [])
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                // Заявка
                if ( isset($filter['search']['requestTitle']) ) {
                    $where .= "b.REQUEST_TITLE LIKE '%{$filter['search']['requestTitle']}%' AND ";
                }
                if ( isset($filter['search']['order_id']) ) {
                    $where .= "dtc.`ID_CONTRACT` = '{$filter['search']['order_id']}' AND ";
                }
                if ( isset($filter['search']['price_discount']) ) {
                    $where .= "(
                        (b.price_discount IS NOT NULL AND b.price_discount <> '' AND 
                        b.price_discount = CAST('{$filter['search']['price_discount']}' AS DECIMAL(13,2)))
                        OR 
                        (b.price_discount = 0.00 AND '{$filter['search']['price_discount']}' = '0')
                    ) AND ";
                }
                if ( isset($filter['search']['ACCOUNT']) ) {
                    $where .= "b.ACCOUNT = '{$filter['search']['ACCOUNT']}' AND ";
                }
                if ( isset($filter['search']['OPLATA']) ) {
                    if ($filter['search']['OPLATA'] === '0') {
                        $where .= "(b.OPLATA = 0) AND ";
                    } else {
                        $where .= "(
                            (b.OPLATA IS NOT NULL AND b.OPLATA <> '' AND 
                            (b.OPLATA = CAST('{$filter['search']['OPLATA']}' AS DOUBLE)))
                        ) AND ";
                    }
                }

                $stageArr = [
                    // "b.OPLATA < b.PRICE AND ",
                    // "b.OPLATA >= b.PRICE AND "
                    "(b.price_discount IS NULL OR b.price_discount = '' OR (b.OPLATA < b.price_discount)) AND ",
                    "b.OPLATA >= b.price_discount AND b.OPLATA > 0 AND "
                ];
                // стадии
                if ( isset($filter['search']['stage']) ) {
                    $where .= $stageArr[$filter['search']['stage']];
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
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

        $result = [];

        $data = $this->DB->Query(
            "SELECT 
                        dtc.`ID_DEAL`, dtc.ID_CONTRACT, 
                        b.`REQUEST_TITLE`, b.`DATE_CREATE`, b.`PRICE`, b.ACCOUNT, b.DISCOUNT, b.price_discount, b.OPLATA, b.STAGE_ID, b.`ID`, b.discount_type, b.ID_Z,
                        tz.ACTUAL_VER, tz.ID as tz_doc_id, tz.pdf as tz_pdf
                    FROM `DEALS_TO_CONTRACTS` dtc, `ba_tz` b
                    LEFT JOIN TZ_DOC tz ON b.ID = tz.TZ_ID
                    WHERE b.ID_Z = dtc.`ID_DEAL` and {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT b.ID val
                    FROM `DEALS_TO_CONTRACTS` dtc, `ba_tz` b
                    LEFT JOIN TZ_DOC tz ON b.ID = tz.TZ_ID
                    WHERE b.ID_Z = dtc.`ID_DEAL` AND dtc.`ID_CONTRACT` = '{$filter['search']['order_id']}'"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT b.ID val
                    FROM `DEALS_TO_CONTRACTS` dtc, `ba_tz` b
                    LEFT JOIN TZ_DOC tz ON b.ID = tz.TZ_ID
                    WHERE b.ID_Z = dtc.`ID_DEAL` AND {$where}"
        )->SelectedRowsCount();

        $i = 0;
        while ($row = $data->Fetch()) {
            $row['num'] = ++$i;
            // $row['is_show_finance'] = in_array($_SESSION["SESS_AUTH"]["USER_ID"], [88, 25]);

            $row['date'] = StringHelper::dateRu($row['DATE_CREATE']);

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @param $orderId
     * @return array
     */
    public function getDealToContractByContractId($orderId)
    {
        $request = new Request();
        $result = [];
        $stageArrLose = ['LOSE', '5', '6', '7', '8', '9', '10', '11', '12', '13'];

        $res = $this->DB->Query(
            "SELECT 
                    dtc.`ID_DEAL`, 
                    b.`REQUEST_TITLE`, b.`DATE_CREATE`, b.`PRICE`, b.ACCOUNT, b.DISCOUNT, b.OPLATA, b.STAGE_ID, b.`ID`, b.discount_type,
                    tz.ACTUAL_VER, tz.ID as tz_doc_id, tz.pdf as tz_pdf
                FROM `DEALS_TO_CONTRACTS` dtc, `ba_tz` b
                LEFT JOIN TZ_DOC tz ON b.ID = tz.TZ_ID
                WHERE dtc.`ID_CONTRACT` = {$orderId} AND b.ID_Z = dtc.`ID_DEAL`");

        while ($row = $res->Fetch()) {
            $row['STAGE_ID'] = $request->getStageRequestById($row['ID_DEAL']);
            if (in_array($row['STAGE_ID'], $stageArrLose)) {
                $row['status'] = 'lose';
            } elseif ($row['STAGE_ID'] == 'WON') {
                $row['status'] = 'won';
            } else {
                $row['status'] = 'work';
            }

            $price = 0;
            if ( !empty($row['PRICE']) ) {
                $price = (float)$row['PRICE'];

                if ( $row['discount_type'] == 'percent' ) {
                    $price -= $price * $row['DISCOUNT'] / 100;
                    $row['discount_type'] = '%';
                } elseif ( $row['discount_type'] == 'rub' ) {
                    $price -= $row['DISCOUNT'];
                    $row['discount_type'] = '';
                }

                $row['PRICE'] = StringHelper::priceFormatRus($row['PRICE']);
            } else {
                $row['PRICE'] = '₽';
            }

            if ( $price < 0 ) {
                $price = 0;
            }

            $row['price_discount'] = $price;

            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param $orderID
     * @param $data
     * @return mixed
     */
    public function setOrderById($orderID, $data)
    {
        $data['CONTRACT_TYPE'] = $this->quoteStr($this->DB->ForSql($data['CONTRACT_TYPE']));
        $data['NUMBER'] = $this->quoteStr($this->DB->ForSql($data['NUMBER']));
        $data['DATE'] = $this->quoteStr($this->DB->ForSql($data['DATE']));
        $upd = $this->DB->Update('DOGOVOR', $data, 'WHERE ID=' . $orderID);

        return $upd;
    }

    /**
     * @param $contractID
     * @return mixed
     */
    public function getClientPrice($contractID)
    {
        $res = $this->DB->Query("SELECT * FROM `PRICE_FOR_CONTRACTS` WHERE `ID_CONTRACT`= {$contractID}")->Fetch();

        return $res;
    }

    /**
     * @param $contractID
     * @return mixed
     */
    public function setClientPrice($contractID)
    {
        $res_oa = $this->DB->Query("SELECT * FROM `ba_gost` ORDER BY `PRICE` ASC");
        while($gost = $res_oa->Fetch())
        {
            if(!$gost['NON_ACTUAL'] && $gost['GOST_TYPE']!='TU_standart' && $gost['GOST_TYPE']!='TU_research' && $gost['GOST_TYPE']!='TU_group')
            {
                $this->DB->Query("INSERT INTO `PRICE_FOR_CONTRACTS` SET 
										`ID_CONTRACT` = {$contractID},
										`ID_GOST` = {$gost['ID']},
										`PRICE` = {$gost['PRICE']}");
            }

            $res = $this->DB->LastId();
        }

        return $res;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function setOplata($data)
    {
        $data['PAY_DATE'] = $this->quoteStr($this->DB->ForSql($data['PAY_DATE']));
        return $this->DB->Insert('PAYMENTS_TO_CONTRACTS', $data);
    }

    /**
     * @param $id
     * @return array
     */
    public function getPaymentOnContract($id)
    {
        $result = [];
        $sum = 0;
        $res = $this->DB->Query("SELECT PAY_DATE, PAY_SUMM FROM `PAYMENTS_TO_CONTRACTS` WHERE `ID_CONTRACT` = {$id}");

        while ($row = $res->Fetch()) {
            $sum += $row['PAY_SUMM'];
            $row['PAY_SUMM'] = StringHelper::priceFormatRus($row['PAY_SUMM']);
            $row['PAY_DATE'] = date('d.m.Y', strtotime($row['PAY_DATE']));
            $result['data'][] = $row;
        }

        $result['paymentSum'] = $sum;

        return $result;
    }


    public function uploadTzDocPdf($file, $tzId)
    {
        return $this->saveFile(PROTOCOL_PATH . "archive_tz/{$tzId}", $file["name"], $file["tmp_name"]);
    }


    public function uploadPdf($file)
    {
        return $this->saveFile($_SERVER["DOCUMENT_ROOT"] . "/pdf", $file["name"], $file["tmp_name"]);
    }


    public function saveTzDocPdf($fileName, $tzDocId)
    {
        $this->DB->Update('TZ_DOC', ['pdf' => $this->quoteStr($this->DB->ForSql($fileName))], "WHERE ID = {$tzDocId}");
    }


    public function saveDogovorPdf($fileName, $dogovorId)
    {
        $this->DB->Update('DOGOVOR', ['PDF' => $this->quoteStr($this->DB->ForSql($fileName))], "WHERE ID = {$dogovorId}");
    }

    /**
     * @param $dealId
     * @return false|string
     */
    public function getOrderByDealId($dealId)
    {
        global $DB;
        $result = $DB->Query("SELECT DOGOVOR_NUM FROM `ba_tz` WHERE ID_Z = {$dealId} ")->Fetch();

        if ( !empty($result) ) {
            $idDogovorArr = explode('ID', $result['DOGOVOR_NUM']);

            if (isset($idDogovorArr[1])) {
                $idDogovor = $idDogovorArr[1];
            } else {
                $idDogovor = $idDogovorArr[0];
            }
        } else {
            return false;
        }

        $contract = $DB->Query("SELECT * FROM `DOGOVOR` WHERE ID = '{$idDogovor}'")->Fetch();
        if (!empty($contract)) {
            return $contract['CONTRACT_TYPE'] . ' №' . $contract['NUMBER'] . ' от ' . date('d.m.Y', strtotime($contract['DATE']));
        } else {
            return false;
        }

    }

    public function changeOrderByHeadRequest($idHeadRequest, $dealId)
    {
        $order = $this->getContractDealByDealId($idHeadRequest);

        $orderChild = $this->getContractDealByDealId($dealId);

        $dataOrderDealsToContract = [
            'ID_CONTRACT' => $order['ID_CONTRACT'],
            'ID_DEAL' => $dealId
        ];

        $HeaderBa_tzData = $this->DB->Query("SELECT DOGOVOR_NUM, CONTRACT_ID, NO_CONTRACT, DOGOVOR_TABLE FROM ba_tz WHERE ID_Z = {$idHeadRequest}")->Fetch();

        $dataOrderBa_tz = $this->prepearTableData('ba_tz', $HeaderBa_tzData);

        $this->DB->Update('ba_tz', $dataOrderBa_tz, "where ID_Z = $dealId");
        if (!empty($orderChild)) {
            $this->DB->Update('DEALS_TO_CONTRACTS', $dataOrderDealsToContract, "where ID_DEAL = $dealId");
        } else {
            $this->DB->Insert('DEALS_TO_CONTRACTS', $dataOrderDealsToContract);
        }
    }

    public function orderCancelByOrderId($id)
    {
        $order = $this->DB->Query("UPDATE DOGOVOR SET `IS_ACTION` = !`IS_ACTION` WHERE ID = {$id}");
    }

    public function deletePdfTZ($id)
    {
        $tz = $this->DB->Query("SELECT * FROM TZ_DOC WHERE ID = {$id}")->Fetch();

        if (unlink($_SERVER["DOCUMENT_ROOT"] . "/protocol_generator/archive_tz/{$tz['TZ_ID']}/{$tz['pdf']}")) {
            $this->DB->Query("UPDATE TZ_DOC SET pdf = '' WHERE ID = {$id}");

            return true;
        }

        return false;
    }

    /**
     * @return int|mixed
     */
    public function getCurrentNumber()
    {
        $order = $this->DB->Query("SELECT SUBSTRING_INDEX(NUMBER, '/', 1) as num FROM `DOGOVOR` WHERE `CLIENT_NUMBER`='0' OR `CLIENT_NUMBER` IS NULL ORDER BY `ID` DESC LIMIT 1")->Fetch();

        return $order['num']+1;
    }
}
