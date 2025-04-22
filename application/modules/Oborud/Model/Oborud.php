<?php

/**
 * Модель для работы с оборудованием
 * Class Oborud
 */
class Oborud extends Model {

    /**
     * @var int
     */
    protected $poverkaTime = 5184000;

    /**
     * @param $data
     * @return array
     */
    private function prepearData($data)
    {
        $sqlData = $this->prepearTableData('ba_oborud', $data);

        $sqlData['IN_AREA']             = isset($data['IN_AREA'])? 1 : 0;
        $sqlData['IN_STOCK']            = isset($data['IN_STOCK'])? 1 : 0;
        $sqlData['CHECKED']             = isset($data['CHECKED'])? 1 : 0;
        $sqlData['TEMPERATURE']         = isset($data['TEMPERATURE'])? 1 : 0;
        $sqlData['HUMIDITY']            = isset($data['HUMIDITY'])? 1 : 0;
        $sqlData['PRESSURE']            = isset($data['PRESSURE'])? 1 : 0;
        $sqlData['NO_METR_CONTROL']     = isset($data['NO_METR_CONTROL'])? 1 : 0;
        $sqlData['LONG_STORAGE']        = isset($data['LONG_STORAGE'])? 1 : 0;
        $sqlData['is_portable']         = isset($data['is_portable'])? 1 : 0;
        $sqlData['is_vagon']            = isset($data['is_vagon'])? 1 : 0;

        return $sqlData;
    }


    /**
     * @param $filter
     * @return array
     */
    public function getDataToJournal($filter)
    {
        $organizationId = App::getOrganizationId();

        $currentDate = date('Y-m-d');

        $labModel = new Lab();

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
                // Измеряемая харак-ка
                if ( isset($filter['search']['NAME']) ) {
                    $where .= "b.NAME LIKE '%{$filter['search']['NAME']}%' AND ";
                }
                // Наименование
                if ( isset($filter['search']['OBJECT']) ) {
                    $where .= "b.OBJECT LIKE '%{$filter['search']['OBJECT']}%' AND ";
                }
                // Тип
                if ( isset($filter['search']['TYPE_OBORUD']) ) {
                    $where .= "b.TYPE_OBORUD LIKE '%{$filter['search']['TYPE_OBORUD']}%' AND ";
                }
                // Идентификация оборудования
                if ( isset($filter['search']['IDENT']) ) {
                    $where .= "b.IDENT LIKE '%{$filter['search']['IDENT']}%' AND ";
                }
                // Заводской номер
                if ( isset($filter['search']['FACTORY_NUMBER']) ) {
                    $where .= "b.FACTORY_NUMBER LIKE '%{$filter['search']['FACTORY_NUMBER']}%' AND ";
                }
                // Инв. номер
                if ( isset($filter['search']['REG_NUM']) ) {
                    $where .= "b.REG_NUM LIKE '%{$filter['search']['REG_NUM']}%' AND ";
                }
                // В области акредитации
                if ( isset($filter['search']['IN_AREA']) && $filter['search']['IN_AREA'] !== '' ) {
                    $where .= "b.IN_AREA = {$filter['search']['IN_AREA']} AND ";
                }
                // Ввод в экспл.
                if ( isset($filter['search']['god_vvoda_expluatation']) ) {
                    $where .= "LOCATE('{$filter['search']['god_vvoda_expluatation']}', DATE_FORMAT(b.god_vvoda_expluatation, '%d.%m.%Y')) > 0 AND ";
                }
                // Статус
                if ( isset($filter['search']['stage']) ) {
                    $stage = [
                        // Все статусы
                        'all' => "b.LONG_STORAGE = 0 AND b.`is_decommissioned` = 0 AND ",
                        // Нет замечаний
                        'norm' => "b.CHECKED = 1 and b.LONG_STORAGE = 0 and (b.NO_METR_CONTROL = 1 or (c.is_actual = 1 and (c.date_end - interval 90 day) > '{$currentDate}')) and b.is_decommissioned = 0 and ",
                        // Не заполнено
                        'unnorm' => "1 AND ",
                        // Не проверено
                        'unchecked' => "b.`CHECKED` = 0 AND b.`LONG_STORAGE` = 0 AND b.`is_decommissioned` = 0 AND ",
                        // Истекает срок поверки
                        'poverka' => "b.LONG_STORAGE = 0 and b.is_decommissioned = 0 and NO_METR_CONTROL <> 1 and c.is_actual = 1 and (c.date_end - interval 90 day) <= '{$currentDate}' and c.date_end >= '{$currentDate}' AND ",
                        // Истек срок поверки
                        'poverka_alarm' => "NO_METR_CONTROL <> 1 and b.LONG_STORAGE = 0 and b.is_decommissioned = 0 and (select max(date_end) from ba_oborud_certificate where is_actual = 1 and oborud_id = b.ID) < '{$currentDate}' AND ",
                        // Нет сертификатов
                        'no_certificate' => "NO_METR_CONTROL <> 1 and c.id is null and b.LONG_STORAGE = 0 and b.is_decommissioned = 0 AND ",
                        // Архив
                        'archive' => "b.`is_decommissioned` = 1 and ",
                        // На длительном
                        'longstorage' => "b.LONG_STORAGE <> 0 AND b.`is_decommissioned` = 0 AND ",
                        // Вагоны
                        'vagon' => "b.`REG_NUM` LIKE '%В%' AND b.`LONG_STORAGE` = 0 AND b.`is_decommissioned` = 0 AND ",
                    ];

                    $where .= $stage[$filter['search']['stage']];
                }

                // Лаба Комната
                if ( isset($filter['search']['lab']) ) {
                    if ( (int)$filter['search']['lab'] == -1 ) {
                        $where .= "b.`place_of_installation_or_storage` = 0 AND ";
                    } else if ( $filter['search']['lab'] < 100 ) {
                        $where .= "b.`place_of_installation_or_storage` = {$filter['search']['lab']} AND ";
                    } else if ($filter['search']['lab'] > 100) {
                        $roomId = (int) $filter['search']['lab'] - 100;
                        $where .= "b.`roomnumber` = {$roomId} AND ";
                    }
                }

                // Контролирующая организация
                if ( isset($filter['search']['POVERKA_PLACE']) ) {
                    $where .= "b.POVERKA_PLACE LIKE '%{$filter['search']['POVERKA_PLACE']}%' AND ";
                }

                // Право собственности
                if ( isset($filter['search']['property_rights']) ) {
                    $where .= "b.property_rights LIKE '%{$filter['search']['property_rights']}%' AND ";
                }

                // Наименование лаборатории
                if ( isset($filter['search']['laba_name']) ) {
                    $where .= "l.NAME LIKE '%{$filter['search']['laba_name']}%' AND ";
                }

                // Место установки
                if ( isset($filter['search']['room']) ) {
                    $where .= "b.roomnumber IN (SELECT r.ID FROM ROOMS r WHERE CONCAT(r.NAME, ' ', r.NUMBER) COLLATE utf8mb3_unicode_ci LIKE '%{$filter['search']['room']}%') AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }
                switch ($filter['order']['by']) {
                    case 'NAME':
                        $order['by'] = 'b.NAME';
                        break;
                    case 'OBJECT':
                        $order['by'] = 'b.OBJECT';
                        break;
                    case 'TYPE_OBORUD':
                        $order['by'] = 'b.TYPE_OBORUD';
                        break;
                    case 'FACTORY_NUMBER':
                        $order['by'] = 'b.FACTORY_NUMBER';
                        break;
                    case 'REG_NUM':
                        $order['by'] = 'b.REG_NUM';
                        break;
                    case 'god_vvoda_expluatation':
                        $order['by'] = 'b.god_vvoda_expluatation';
                        break;
                    case 'date_start':
                        $order['by'] = 'c.date_start';
                        break;
                    case 'date_end':
                        $order['by'] = 'c.date_end';
                        break;
                    default:
                        $order['by'] = 'b.ID';
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
        $where .= "b.organization_id = {$organizationId}";

        $data = $this->DB->Query(
            "SELECT b.*, l.NAME laba_name, c.name as certificate_name, c.date_start, c.date_end
                    FROM ba_oborud as b
                    LEFT JOIN ba_laba as l ON l.ID = b.place_of_installation_or_storage
                    left join ba_oborud_certificate as c on c.oborud_id = b.ID and c.is_actual = 1
                    WHERE {$where}
                    group by b.ID
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT b.ID
                    FROM ba_oborud b
                    LEFT JOIN ba_laba l ON l.ID = b.place_of_installation_or_storage
                    left join ba_oborud_certificate as c on c.oborud_id = b.ID
                    WHERE b.organization_id = {$organizationId}
                    group by b.ID"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT b.ID
                    FROM ba_oborud b
                    LEFT JOIN ba_laba l ON l.ID = b.place_of_installation_or_storage
                    left join ba_oborud_certificate as c on c.oborud_id = b.ID
                    WHERE {$where}
                    group by b.ID"
        )->SelectedRowsCount();

        $result = [];


        while ($row = $data->Fetch()) {
            $status = $this->getStatus($row);

            $row = array_merge($row, $status);

            switch ($row['IDENT']) {
                case 'SI':
                    $ident = "СИ";
                    break;
                case 'IO':
                    $ident = "ИО";
                    break;
                case 'VO':
                    $ident = "ВО";
                    break;
                case 'SO':
                    $ident = "СО";
                    break;
                case 'KO':
                    $ident = "КО";
                    break;
                case 'TS':
                    $ident = "ТС";
                    break;
                case 'REACT':
                    $ident = "Реактивы";
                    break;
                case 'OOPP':
                    $ident = "ООПП";
                    break;
                default:
                    $ident = $row['IDENT'];
            }

            $row['IDENT'] = $ident;

            if ( !empty($row['date_start']) && $row['date_start'] !== '0000-00-00' ) {
                $row['date_start'] = date('d.m.Y', strtotime($row['date_start']));
            } else {
                $row['date_start'] = '';
            }

            if ( !empty($row['date_end']) && $row['date_end'] !== '0000-00-00' ) {
                $row['date_end'] = date('d.m.Y', strtotime($row['date_end']));
            } else {
                $row['date_end'] = '';
            }

            $row['certificate'] = '';

            if ( !empty($row['god_vvoda_expluatation']) && $row['god_vvoda_expluatation'] !== '0000-00-00' ) {
                $row['god_vvoda_expluatation'] = date("d.m.Y", strtotime($row['god_vvoda_expluatation']));
            } else {
                $row['god_vvoda_expluatation'] = '';
            }


            $room = $labModel->getRoomById($row['roomnumber']);
            $row['room'] = $room['name'];

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * @param $data
     * @return array
     */
    public function getStatus($data)
    {
        $result['bgStage'] = 'bg-dark-red';
        $result['titleStage'] = 'Неизвестный статус';

        if (!empty($data['is_decommissioned'])) {
            $result['bgStage'] = 'bg-grey';
            $result['titleStage'] = 'Оборудование списано';

            return $result;
        } else if (!empty($data['LONG_STORAGE'])) {
            $result['bgStage'] = 'bg-dark-blue';
            $result['titleStage'] = 'Оборудование находится на длительном хранении';

            return $result;
        }

        if ( !$data['NO_METR_CONTROL'] ) {
            $certificateList = $this->getCertificateByOborud($data['ID'], true);

            if ( empty($certificateList) ) {
                $result['bgStage'] = 'bg-dark-red';
                $result['titleStage'] = 'Отсутствуют сертификаты, но подлежит периодическому метрологическому контролю';

                return $result;
            }

            foreach ($certificateList as $certificate) {
                if ($certificate['is_actual']) {
                    $poverka = strtotime($certificate['date_end']) - time();

                    if (($poverka > $this->poverkaTime || $data['NO_METR_CONTROL']) && $data['CHECKED'] && !($data['LONG_STORAGE'] || !empty($data['is_decommissioned']))) {
                        $result['bgStage'] = 'bg-light-green';
                        $result['titleStage'] = 'Нет замечаний';
                    } else if (($poverka <= 0) && !$data['NO_METR_CONTROL'] && !($data['LONG_STORAGE'] || !empty($data['is_decommissioned']))) {
                        $result['bgStage'] = 'bg-red';
                        $result['titleStage'] = 'Истек срок поверки!';
                    } else if (($poverka > $this->poverkaTime || $data['NO_METR_CONTROL']) && $data['CHECKED'] == '0' && !($data['LONG_STORAGE'] || !empty($data['is_decommissioned']))) {
                        $result['bgStage'] = 'bg-light-blue';
                        $result['titleStage'] = 'Оборудование не проверено отделом метрологии!';
                    } else if (!$data['NO_METR_CONTROL'] && !($data['LONG_STORAGE'] || !empty($data['is_decommissioned']))) {
                        $result['bgStage'] = 'bg-yellow';
                        $result['titleStage'] = 'До истечения срока поверки осталось менее 90 дней!';
                    }
                }
            }
        } else {
            if ( $data['CHECKED'] && !($data['LONG_STORAGE'] || !empty($data['is_decommissioned'])) ) {
                $result['bgStage'] = 'bg-light-green';
                $result['titleStage'] = 'Нет замечаний';
            } else if ( $data['CHECKED'] == '0' && !($data['LONG_STORAGE'] || !empty($data['is_decommissioned'])) ) {
                $result['bgStage'] = 'bg-light-blue';
                $result['titleStage'] = 'Оборудование не проверено отделом метрологии!';
            }
        }

        return $result;
    }


    /**
     * @param $filter
     * @return array
     */
    public function getDataToOborudMovingJournal($filter)
    {
        $organizationId = App::getOrganizationId();
        $userModel = new User();

        $where = "";
        $limit = "";
        $order = [
            'by' => 'b.ID',
            'dir' => 'DESC'
        ];
        if ( !empty($filter) ) {
            if ( !empty($filter['search']) ) {
                if ( isset($filter['search']['oborud_id']) ) {
                    $where .= "b.ID = '{$filter['search']['oborud_id']}' AND ";
                }

                if ( isset($filter['search']['name']) ) {
                    $where .= "(b.OBJECT LIKE '%{$filter['search']['name']}%' OR b.REG_NUM LIKE '%{$filter['search']['name']}%') AND ";
                }

                if ( isset($filter['search']['place']) ) {
                    $placeFilter = trim($filter['search']['place']);
                    $placeLower = mb_strtolower($placeFilter);
                    
                    $isNonMoved = preg_match('/(н[её]|п[еере])/ui', $placeLower);
                    if ($isNonMoved) {
                        $where .= "(m.place IS NULL OR m.place = '') AND ";
                    } else {
                        $where .= "m.place LIKE '%{$placeFilter}%' AND ";
                    }
                }
            }

            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }
                switch ($filter['order']['by']) {
                    case 'name':
                        $order['by'] = 'b.REG_NUM';
                        break;
                    case 'place':
                        $order['by'] = 'm.place';
                        break;
                    case 'date':
                        $order['by'] = 'm.datetime';
                        break;
                    case 'responsible_user':
                        $order['by'] = "LEFT(TRIM(CONCAT_WS(' ', ur.NAME, ur.LAST_NAME)), 1)";
                        break;
                    case 'receiver_user':
                        $order['by'] = "LEFT(TRIM(CONCAT_WS(' ', uv.NAME, uv.LAST_NAME)), 1)";
                        break;
                    default:
                        $order['by'] = 'b.ID';
                }
            }

            if ( isset($filter['paginate']) ) {
                $offset = 0;
                if ( isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0 ) {
                    $length = $filter['paginate']['length'];

                    if ( isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0 ) {
                        $offset = $filter['paginate']['start'];
                    }
                    $limit = "LIMIT {$offset}, {$length}";
                }
            }
        }
        $where .= "b.organization_id = '{$organizationId}'";

        $data = $this->DB->Query(
            "SELECT *, 
                        CASE
                            WHEN TRIM(CONCAT_WS(' ', ur.NAME, ur.LAST_NAME)) = '' THEN '--'
                            ELSE TRIM(CONCAT_WS(' ', ur.NAME, ur.LAST_NAME))
                        END AS responsible_user,
                        CASE
                            WHEN TRIM(CONCAT_WS(' ', uv.NAME, uv.LAST_NAME)) = '' THEN '--'
                            ELSE TRIM(CONCAT_WS(' ', uv.NAME, uv.LAST_NAME))
                        END AS receiver_user
                    FROM ba_oborud as b
                    LEFT JOIN ba_oborud_moving m ON m.oborud_id = b.ID 
                    LEFT JOIN b_user AS ur ON ur.ID = m.responsible_user_id 
                    LEFT JOIN b_user AS uv ON uv.ID = m.receiver_user_id 
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT b.id
                    FROM ba_oborud b
                    LEFT JOIN ba_oborud_moving m ON m.oborud_id = b.ID 
                    LEFT JOIN b_user AS ur ON ur.ID = m.responsible_user_id 
                    LEFT JOIN b_user AS uv ON uv.ID = m.receiver_user_id 
                    WHERE organization_id = '{$organizationId}'"
        )->SelectedRowsCount();

        $dataFiltered = $this->DB->Query(
            "SELECT b.id
                    FROM ba_oborud b
                    LEFT JOIN ba_oborud_moving m ON m.oborud_id = b.ID 
                    LEFT JOIN b_user ur ON ur.ID = m.responsible_user_id 
                    LEFT JOIN b_user uv ON uv.ID = m.receiver_user_id 
                    WHERE {$where}"
        )->SelectedRowsCount();

        $result = [];

        while ($row = $data->Fetch()) {
            $row['name'] = $row['OBJECT'] . ' | ' . $row['REG_NUM'];

            if ( empty($row['place']) ) {
                $row['place'] = 'Не перемещался';
                $row['date'] = '--';
            } else {
                $row['date'] = date('d.m.Y', strtotime($row['datetime']));
            }

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }


    /**
     * @param $oborudId
     * @return array|false
     */
    public function getOborudById($oborudId)
    {
        $result = $this->DB->Query("SELECT * FROM `ba_oborud` WHERE ID = {$oborudId}")->Fetch();

        $result['ident_en'] = $result['IDENT'];

        switch ($result['IDENT']) {
            case 'SI':
                $ident = "СИ";
                break;
            case 'IO':
                $ident = "ИО";
                break;
            case 'VO':
                $ident = "ВО";
                break;
            case 'SO':
                $ident = "СО";
                break;
            case 'KO':
                $ident = "КО";
                break;
            case 'TS':
                $ident = "ТС";
                break;
            case 'REACT':
                $ident = "Реактивы";
                break;
            case 'OOPP':
                $ident = "ООПП";
                break;
            default:
                $ident = $result['IDENT'];
        }

        $result['view_name'] = "{$result['OBJECT']} | {$result['REG_NUM']}";

        $result['actual_certificate'] = $this->getCertificateByOborud($oborudId, true);
        $result['precision_table'] = json_decode($result['precision'], true);

        $result['photo_oborud'] = htmlspecialchars($result['photo_oborud']);
        $result['property_rights_pdf'] = htmlspecialchars($result['property_rights_pdf']);
        $result['passport_pdf'] = htmlspecialchars($result['passport_pdf']);

        $result['IDENT'] = $ident;

        return $result;
    }


    /**
     * получает список сертификатов
     * @param $oborudId
     * @param false $isActualOnly - только актуальные
     * @return array
     */
    public function getCertificateByOborud($oborudId, $isActualOnly = false)
    {
        if ( empty($oborudId) ) {
            return [];
        }

        $where = '';
        if ( $isActualOnly ) {
            $where = "and is_actual = 1";
        }

        $sql = $this->DB->Query("select * from ba_oborud_certificate where oborud_id = {$oborudId} {$where} order by is_actual desc, id desc");

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }


    /**
     * @param $oborudId
     * @param $data
     */
    public function setPrecisionTable($oborudId, $data)
    {
        $a = array_values($data);

        $d['precision'] = $this->quoteStr($this->DB->ForSql(json_encode($a)));

        $this->DB->Update('ba_oborud', $d, "where ID = {$oborudId}");
    }


    /**
     * @param $data
     * @return false|mixed|string
     */
    public function insertOborud($data)
    {
        $organizationId = App::getOrganizationId();
        $yearShort = date('y', strtotime($data['god_vvoda_expluatation']));
        $number = 1;

        $lab = $data['place_of_installation_or_storage'];
        $labFind = "= " . (int)$data['place_of_installation_or_storage'];

        if ( $lab == 4 ) {
            $lab = 2;
        } else if ( $lab == 2 ) {
            $lab = 4;
        } else if ( $lab == 9 ) {
            $lab = 5;
            $labFind = 5;
        } else if ( $lab == 10 ) {
            $lab = 5;
            $labFind = 5;
        }

        if ( $lab == 5 ) {
            $labFind = "in (5, 9, 10)";
        }

        $regNum = "№ {$yearShort}.{$lab}";

        $year = date('Y', strtotime($data['god_vvoda_expluatation']));
        $isVagon = isset($data['is_vagon'])? 1 : 0;

        $row = $this->DB->Query(
            "select max(`number`) as last_number 
                    from ba_oborud 
                    where year(`god_vvoda_expluatation`) = {$year} and place_of_installation_or_storage {$labFind} and `is_vagon` = {$isVagon} and organization_id = {$organizationId}"
        )->Fetch();

        if ( !empty($row) && !empty($row['last_number']) ) {
            $number = $row['last_number'] + 1;
        }

        $regNum .= "-{$number}";

        if ( $isVagon ) {
            $regNum .= "-В";
        }

        $data['number'] = $number;
        $data['REG_NUM'] = $regNum;
        $data['organization_id'] = $organizationId;

        $sqlData = $this->prepearData($data);

        return $this->DB->Insert('ba_oborud', $sqlData);
    }


    /**
     * @param $oborudId
     * @param $data
     * @return bool|int|string
     */
    public function updateOborud($oborudId, $data)
    {
        $sqlData = $this->prepearData($data);

        return $this->DB->Update('ba_oborud', $sqlData, "WHERE ID = {$oborudId} AND organization_id = " . App::getOrganizationId());
    }


    /**
     * @param $oborudId
     * @param $field
     * @param $value
     */
    public function updateFieldOborud($oborudId, $field, $value)
    {
        $this->DB->Update('ba_oborud', [$field => $this->quoteStr($this->DB->ForSql($value))], "WHERE ID = {$oborudId} AND organization_id = " . App::getOrganizationId());
    }


    /**
     * @param $oborudId
     * @param $arrayData
     * @param $files
     */
    public function updateCertificateArray($oborudId, $arrayData, $files)
    {

        foreach ($arrayData as $certId => $data) {
            $certId = (int)$certId;

            $file = [
                'name' => $files['name'][$certId],
                'type' => $files['type'][$certId],
                'tmp_name' => $files['tmp_name'][$certId],
                'error' => $files['error'][$certId],
                'size' => $files['size'][$certId],
            ];

            if ( !empty($file['name']) ) {
                $resultFile = $this->saveOborudFile($oborudId, $file);

                if (!$resultFile['success']) {
                    $_SESSION['message_warning'] = "Не сохранился файл 'Свидетельство о поверке'. " . $resultFile['error'];
                } else {
                    $_SESSION['message_warning'] = $resultFile['data'];
                    $data['file'] = $resultFile['data'];
                }
            }

            $data['oborud_id'] = $oborudId;
            $sqlData = $this->prepearTableData('ba_oborud_certificate', $data);

            $sqlData['is_actual'] = isset($data['is_actual'])? 1 : 0;

            $this->DB->Update('ba_oborud_certificate', $sqlData, "where id = {$certId}");
        }
    }


    /**
     * @param $oborudId
     * @return array
     */
    public function getLastOborudMoving($oborudId)
    {
        if ( empty($oborudId) ) {
            return [];
        }

        $sql = $this->DB->Query("select * from ba_oborud_moving where oborud_id = {$oborudId} order by id desc ");

        $row = $sql->Fetch();
        $row['date'] = date('d.m.Y', strtotime($row['datetime']));

        return $row;
    }


    /**
     * @param $data
     */
    public function addOborudMoving($data)
    {
        $data['is_return'] = isset($data['is_return'])? 1 : 0;

        $data['datetime'] = date('Y-m-d H:i:s');

        $sqlData = $this->prepearTableData('ba_oborud_moving', $data);

        $this->DB->Insert('ba_oborud_moving', $sqlData);

        return $data;
    }


    /**
     * @param $oborudMovingId
     */
    public function setConfirmReceive($oborudMovingId)
    {
        $this->DB->Update('ba_oborud_moving', ['is_confirm' => 1], "where id = {$oborudMovingId}");
    }


    /**
     * удаляет взаимозаменяемое оборудование
     * @param $oborudId
     */
    public function deleteInterchangeableOborud($oborudId)
    {
        $this->DB->Query("delete from ba_oborud_interchangeable where oborud_id = {$oborudId} or inter_oborud_id = {$oborudId}");
    }


    /**
     * обновляет взаимозаменяемое оборудование
     * @param $oborudId
     * @param $arrOborudId
     */
    public function updateInterchangeableOborud($oborudId, $arrOborudId)
    {
        $this->deleteInterchangeableOborud($oborudId);

        foreach ($arrOborudId as $id) {
            $id = (int)$id;
            $this->DB->Insert('ba_oborud_interchangeable', ['oborud_id' => $oborudId, 'inter_oborud_id' => $id]);
            $this->DB->Insert('ba_oborud_interchangeable', ['oborud_id' => $id, 'inter_oborud_id' => $oborudId]);
        }
    }


    /**
     * возвращает массив оборудования взаимозаменяемого оборудования
     * @param $oborudId
     * @return array
     */
    public function getInterchangeableOborud($oborudId)
    {
        $result = [];

        $sql = $this->DB->Query("select * from ba_oborud_interchangeable where oborud_id = {$oborudId}");

        while ($row = $sql->Fetch()) {
            $result[] = $this->getOborudById($row['inter_oborud_id']);
        }

        return $result;
    }


    /**
     * @param $data
     * @param $file
     * @return array|false|mixed
     */
    public function addCertificate($data, $file)
    {
        if (!empty($file['name'])) {
            $resultFile = $this->saveOborudFile($data['oborud_id'], $file);
    
            if (!$resultFile['success']) {
                $_SESSION['message_warning'] = "Не сохранился файл 'Свидетельство о поверке'. " . $resultFile['error'];
                return false;
            } else {
                $data['file'] = $resultFile['data'];
            }
        }

        $data['is_actual'] = isset($data['is_actual']) ? 1 : 0;

        $sqlData = $this->prepearTableData('ba_oborud_certificate', $data);

        $id = $this->DB->Insert('ba_oborud_certificate', $sqlData);
        $data['id'] = $id;

        return $data;
    }

    
    public function saveOborudFile($oborudId, $file, $folder = '')
    {
        if ( $folder != '' ) {
            $folder = "/{$folder}/";
        }
        $uploadDescFileDir = $_SERVER['DOCUMENT_ROOT'] . '/file_oborud/' . $oborudId . $folder;

        return $this->saveFile($uploadDescFileDir, $file['name'], $file['tmp_name']);
    }


    public function deleteFile($oborudId, $file)
    {
        $uploadDescFileDir = $_SERVER['DOCUMENT_ROOT'] . "/file_oborud/{$oborudId}/$file";

        unlink($uploadDescFileDir);
    }


    /**
     * @deprecated
     * Оборудование в помещениях
     * @param $roomIdList
     * @return array
     */
    public function getOborutByRooms($roomIdList)
    {
//        if ( empty($roomIdList) ) {
//            return [];
//        }
//
//        $str = implode(',', $roomIdList);

        $sql = $this->DB->Query(
            "SELECT distinct *
                    FROM ba_oborud
                    WHERE 1 "); //or `roomnumber` IN ({$str})

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Оборудование в помещении
     * @param int $roomId
     * @return array
     */
    public function getOborudByRoom(int $roomId): array
    {
        $result = [];

        if ( empty($roomId) ) {
            return $result;
        }

        $sql = $this->DB->Query(
            "SELECT distinct *
                    FROM ba_oborud
                    WHERE roomnumber = {$roomId}");

        while ($row = $sql->Fetch()) {

            $row['actual_certificate'] = $this->getCertificateByOborud($row['ID'], true);

            $result[] = $row;
        }

        return $result;
    }

    /**
     * оборудование для результатов испытания
     * @deprecated
     * @return array
     */
    public function getOborudsForResults(): array
    {
        $organizationId = App::getOrganizationId();
        $response = [];
        $result = $this->DB->Query("SELECT b_o.ID b_o_id, b_o.TYPE_OBORUD, b_o.OBJECT, b_o.REG_NUM, 
            b_c.GOST_ID, b_c.OBORUD_ID, 
            b_g.ID b_g_id, b_g.GOST, b_g.GOST_PUNKT, b_g.NUM_OA_NEW, b_o.IDENT  
            FROM ba_oborud b_o 
            LEFT JOIN ba_connection b_c ON b_c.OBORUD_ID = b_o.ID 
            LEFT JOIN ba_gost b_g ON b_g.ID = b_c.GOST_ID 
            WHERE b_g.NUM_OA_NEW > 0 AND b_o.IDENT NOT IN ('VO', 'TS', 'REACT') AND b_o.organization_id = {$organizationId} ORDER BY b_o.OBJECT");

        while ($row = $result->Fetch()) {

            $row['actual_certificate'] = $this->getCertificateByOborud($row['b_o_id'], true);

            $response[] = $row;
        }

        return $response;
    }

    /**
     * @param int|null $protocolId
     * @return array
     */
    public function getOborudsByProtocolId(?int $protocolId): array
    {
        $organizationId = App::getOrganizationId();
        $response = [];
        $currentDate = date('Y-m-d');

        if (empty($protocolId) || $protocolId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT b_o.ID b_o_id, b_o.TYPE_OBORUD, b_o.OBJECT, b_o.REG_NUM, boc.date_end as POVERKA
            FROM ulab_gost_to_probe as ugtp
            INNER JOIN ba_connection b_c ON b_c.GOST_ID = ugtp.method_id
            INNER JOIN ba_oborud b_o ON b_o.ID = b_c.OBORUD_ID
            left join ba_oborud_certificate boc on b_o.ID = boc.oborud_id and boc.is_actual = 1 
            INNER JOIN ba_gost b_g ON b_g.ID = b_c.GOST_ID
            WHERE ugtp.protocol_id = {$protocolId} AND b_g.NUM_OA_NEW > 0
                AND (b_o.IDENT IS NULL OR b_o.IDENT NOT IN ('VO', 'TS', 'REACT'))
                AND b_o.is_decommissioned = 0 AND (b_o.LONG_STORAGE = 0 OR b_o.LONG_STORAGE IS NULL)
                AND b_o.organization_id = {$organizationId}
            ORDER BY b_o.OBJECT");

        while ($row = $result->Fetch()) {

            //TODO: POVERKA больше не актуальна
            $dateMonthBefore  = date('Y-m-d', strtotime($row['POVERKA'] . '-1 month'));

            //Цвет фона оборудования если поверка истекла или осталось меньше месяца
            if ($currentDate > $row['POVERKA']) {
                $row['bg_color'] = 'bg-danger';
            } elseif ($currentDate >= $dateMonthBefore && $currentDate <= $row['POVERKA']) {
                $row['bg_color'] = 'bg-warning';
            } else {
                $row['bg_color'] = '';
            }

            $row['actual_certificate'] = $this->getCertificateByOborud($row['b_o_id'], true);

            $response[$row['b_o_id']] = $row;
        }

        return $response;
    }

    /**
     * @param int|null $protocolId
     * @return array
     */
    public function oborudsByProtocolId(?int $protocolId): array
    {
        $organizationId = App::getOrganizationId();
        $response = [];
        $currentDate = date('Y-m-d');

        if (empty($protocolId) || $protocolId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT b_o.ID b_o_id, b_o.TYPE_OBORUD, b_o.OBJECT, b_o.REG_NUM, boc.date_end as POVERKA
            FROM ulab_gost_to_probe as ugtp  
            INNER JOIN ulab_methods_oborud mo ON mo.method_id = ugtp.method_id 
            INNER JOIN ba_oborud b_o ON b_o.ID = mo.id_oborud
            left join ba_oborud_certificate boc on b_o.ID = boc.oborud_id and boc.is_actual = 1     
            INNER JOIN ulab_methods m ON m.id = mo.method_id 
            WHERE ugtp.protocol_id = {$protocolId}
                AND (b_o.IDENT IS NULL OR b_o.IDENT NOT IN ('VO', 'TS', 'REACT')) 
                AND b_o.is_decommissioned = 0 AND (b_o.LONG_STORAGE = 0 OR b_o.LONG_STORAGE IS NULL)
                AND b_o.organization_id = {$organizationId}
            ORDER BY b_o.OBJECT");

        while ($row = $result->Fetch()) {
            $dateMonthBefore  = date('Y-m-d', strtotime($row['POVERKA'] . '-1 month'));

            //Цвет фона оборудования если поверка истекла или осталось меньше месяца
            if ($currentDate > $row['POVERKA']) {
                $row['bg_color'] = 'bg-danger';
            } elseif ($currentDate >= $dateMonthBefore && $currentDate <= $row['POVERKA']) {
                $row['bg_color'] = 'bg-warning';
            } else {
                $row['bg_color'] = '';
            }

            $response[$row['b_o_id']] = $row;
        }


        return $response;
    }

    /**
     * получить оборудование по сделке
     * @param int $dealId
     * @return array
     */
    public function getOborudsByDealId(int $dealId): array
    {
        $organizationId = App::getOrganizationId();
        $response = [];

        if (empty($dealId) || $dealId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT b_o.ID b_o_id, b_o.TYPE_OBORUD, b_o.OBJECT, b_o.REG_NUM   
            FROM ulab_material_to_request umtr 
            INNER JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id 
            INNER JOIN ba_connection b_c ON b_c.GOST_ID = ugtp.method_id 
            INNER JOIN ba_oborud b_o ON b_o.ID = b_c.OBORUD_ID 
            INNER JOIN ba_gost b_g ON b_g.ID = b_c.GOST_ID 
            WHERE umtr.deal_id = {$dealId} AND b_g.NUM_OA_NEW > 0 AND b_o.IDENT NOT IN ('VO', 'TS', 'REACT')
            AND b_o.organization_id = {$organizationId}
            AND b_o.is_decommissioned = 0 AND b_o.LONG_STORAGE = 0 ORDER BY b_o.OBJECT");

        while ($row = $result->Fetch()) {

            $row['actual_certificate'] = $this->getCertificateByOborud($row['b_o_id'], true);

            $response[$row['b_o_id']] = $row;
        }

        return $response;
    }


    /**
     * списание оборудования
     */
    public function setDecommissioned($oborudId, $data, $newOborudId = '')
    {
        $sqlData = $this->prepearTableData('ba_oborud', $data);

        $sqlData['is_decommissioned'] = 1;
        $sqlData['decommissioned_user_id'] = App::getUserId();

        $this->DB->Update('ba_oborud', $sqlData, "where id = {$oborudId}");

        if ( !empty($newOborudId) ) {
            $this->DB->Update('ulab_methods_oborud', ['id_oborud' => $newOborudId], "where id_oborud = {$oborudId}");
        }

        return $data;
    }

    /**
     * на длительное хранение
     */
    public function setLongStorage($oborudId, $data, $newOborudId = '')
    {
        $sqlData = $this->prepearTableData('ba_oborud', $data);

        $this->DB->Update('ba_oborud', $sqlData, "where id = {$oborudId}");

        if ( !empty($newOborudId) ) {
            $this->DB->Update('ulab_methods_oborud', ['id_oborud' => $newOborudId], "where id_oborud = {$oborudId}");
        }

        return $data;
    }


    /**
     * получить оборудовоание привязанное к протоколу
     * @param $protocolId
     * @return array
     */
    public function getTzObConnectByProtocolId($protocolId)
    {
        $organizationId = App::getOrganizationId();
        $response = [];
        $currentDate = date('Y-m-d');

        if (empty($protocolId) || $protocolId < 0) {
            return $response;
        }

        $result = $this->DB->Query(
            "SELECT 
                        toc.ID as toc_id, boc.date_end as POVER,
                        b_o.ID as b_o_id, b_o.*
                    FROM TZ_OB_CONNECT toc 
                    INNER JOIN ba_oborud b_o ON b_o.ID = toc.ID_OB
					left join ba_oborud_certificate boc on b_o.ID = boc.oborud_id and boc.is_actual = 1 
                    WHERE PROTOCOL_ID = {$protocolId}
                    AND b_o.organization_id = {$organizationId}
                    ");

        while ($row = $result->Fetch()) {

            // TODO: POVERKA больше не актуальна
            $dateMonthBefore  = date('Y-m-d', strtotime($row['POVER'] . '-1 month'));

            //Цвет фона оборудования если поверка истекла или осталось меньше месяца
            $row['poverka_success'] = true;
            if ($currentDate > $row['POVER']) {
                $row['poverka_success'] = false;
                $row['bg_color'] = 'bg-danger';
            } elseif ($currentDate >= $dateMonthBefore && $currentDate <= $row['POVER']) {
                $row['bg_color'] = 'bg-warning';
            } else {
                $row['bg_color'] = '';
            }

            $row['actual_certificate'] = $this->getCertificateByOborud($row['b_o_id'], true);

            $response[$row['b_o_id']] = $row;
        }

        return $response;
    }

    /**
     * удалить оборудувоание привязанное к протоколу
     * @param int $protocolId
     * @return array
     */
    public function delTzObConnectByProtocolId(int $protocolId): array
    {
        if (empty($protocolId) && $protocolId < 0) {
            return [
                'success' => false,
                'error' => [
                    'message' => "Не указан, или указан неверно ИД протокола",
                ]
            ];
        }

        $this->DB->Query("DELETE FROM TZ_OB_CONNECT WHERE PROTOCOL_ID = {$protocolId}");

        return [
            'success' => true
        ];
    }

    /**
     * @param array $data
     * @param string $table
     * @return int
     */
    public function create(array $data, string $table): int
    {
        foreach ($data as $key => $item) {
            if (is_string($item)) {
                $data[$key] = $this->quoteStr($this->DB->ForSql(trim($item)));
            }
        }

        $result = $this->DB->Insert($table, $data);

        return intval($result);
    }


    /**
     * @return array
     */
    public function getList()
    {
        $result = [];

        $stmt = $this->DB->Query("
            SELECT *
            FROM ba_oborud
            order by is_decommissioned asc, LONG_STORAGE asc, OBJECT asc
        ");

        while ($row = $stmt->Fetch()) {
            $row['view_name'] = "{$row['OBJECT']} | {$row['REG_NUM']}";

            $row['actual_certificate'] = $this->getCertificateByOborud($row['ID'], true);

            $row['precision_table'] = json_decode($row['precision'], true);

            $row['color'] = '';
            if ( $row['is_decommissioned'] == 1 || $row['LONG_STORAGE'] == 1 ) {
                $row['color'] = '#F00';
            }

            $result[] = $row;
        }

        return $result;
    }


    public function getListBySecondmentId($id)
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $stmt = $this->DB->Query("
            SELECT b.ID, b.NAME, b.OBJECT, b.REG_NUM, s_ob.completed
            FROM ba_oborud as b
            LEFT JOIN secondment_oborud s_ob ON s_ob.oborud_id = b.id
            WHERE s_ob.secondment_id = {$id}
            AND b.organization_id = {$organizationId}
        ");

        while ($row = $stmt->Fetch()) {
            $result[$row["ID"]] = $row;
        }

        return $result;
    }

    /**
     * ???
     * @return array
     */
    public function getOboruds()
    {
        $organizationId = App::getOrganizationId();
        $sql = $this->DB->Query(
            "SELECT mo.method_id, o.id o_id, o.IDENT, o.REG_NUM, o.OBJECT, o.TYPE_OBORUD, m.clause, g.id g_id, g.reg_doc   
                FROM ba_oborud o, ulab_methods_oborud mo, ulab_methods m, ulab_gost g 
                WHERE o.IDENT NOT IN ('VO', 'TS', 'REACT') AND o.ID = mo.id_oborud AND mo.method_id = m.id 
                AND m.gost_id = g.id AND o.organization_id = {$organizationId} ORDER BY o.OBJECT"
        );

        $result = [];

        while ($row = $sql->Fetch()) {

            switch ($row['IDENT']) {
                case 'SI':
                    $ident = "СИ";
                    break;
                case 'IO':
                    $ident = "ИО";
                    break;
                case 'VO':
                    $ident = "ВО";
                    break;
                case 'TS':
                    $ident = "ТС";
                    break;
                case 'SO':
                    $ident = "ТС";
                    break;
                case 'REACT':
                    $ident = "Реактивы";
                    break;
                case 'OOPP':
                    $ident = "ООПП";
                    break;
                default:
                    $ident = "";
            }

            $row['ident'] = $ident;

            $result[] = $row;
        }

        return $result;
    }

    /**
     * @deprecated
     * @param $id
     * @return false|mixed
     */
    public function getCertificateByOborudId($id)
    {
        $res = $this->DB->Query("SELECT * FROM `b_verification_certificate` WHERE `id_oborud`={$id}")->Fetch();

        $certificateName = unserialize($res['name_f']);

        if ( empty($certificateName) ) {
            $certificateName = [];
        }

        return end($certificateName);
    }

    /**
     * получить данные окружающей среды и условия испытания оборудования
     * @param $ugtpIds
     * @param $dateStart
     * @param $dateEnd
     * @return array
     */
    public function getConditionsForOboruds($ugtpIds, $dateStart, $dateEnd): array
    {
        $labModel = new Lab();
        $methodsModel = new Methods();
        $organizationId = App::getOrganizationId();
        $result = [];

        if (empty($ugtpIds)) {
            return $result;
        }

        $periods = $methodsModel->getDatesFromRange($dateStart, $dateEnd, $format = 'd.m.Y');

        $strUgtpIds = implode(',', $ugtpIds);
        $where = "ugtp.id IN ({$strUgtpIds})";

        $sql = $this->DB->Query(
            "SELECT 
                umtr.deal_id, umtr.protocol_id,  
                ugtp.id ugtp_id, 
                umo.id_oborud, 
                bo.*, bo.ID bo_id, boc.date_end as POVER    
                FROM ulab_material_to_request umtr 
                    INNER JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id  
                    LEFT JOIN ulab_methods_oborud umo ON umo.method_id = ugtp.method_id 
                    LEFT JOIN ba_oborud bo ON bo.ID = umo.id_oborud
					left join ba_oborud_certificate boc on bo.ID = boc.oborud_id and boc.is_actual = 1 
                    WHERE {$where}"
        );

        while ($row = $sql->Fetch()) {
            $roomId = (int)$row['roomnumber'];

            $oborudProtocol = $this->getTzObConnectByProtocolId($row['protocol_id']);
            $oborudProtocolIds = array_column($oborudProtocol, 'b_o_id');

            //если проба привязана к протоколу (получаем оборудование только которое есть в протоколе)
            if ( !empty($row['protocol_id']) && !in_array($row['bo_id'], $oborudProtocolIds) ) {
                continue;
            }

            $result[$row['ugtp_id']]['id'] = $row['ugtp_id'];
            $result[$row['ugtp_id']]['oborud'][] = $row;
            $result[$row['ugtp_id']]['oborud_by_protocol'] = $oborudProtocolIds;
            $result[$row['ugtp_id']]['rooms'][] = $labModel->getRoomById($roomId);
            $result[$row['ugtp_id']]['conditions'][] = $labModel->getConditionsByRoom($roomId, $dateStart, $dateEnd);

            //находим даты для которых не были заполнены условия окружающей среды
            $periodsDB = $labModel->getDatesByPeriodsForRoom($roomId, $dateStart, $dateEnd);
            $periodsDiff = array_diff($periods, $periodsDB);
            $result[$row['ugtp_id']]['no_conditions'][] = implode(',' , $periodsDiff);
        }

        return $result;
    }

    /**
     * @param $protocolId
     * @return array
     */
    public function getOborudsForProtocols($protocolId): array
    {
        $result = [];

        if (empty($protocolId)) {
            return $result;
        }

        $sql = $this->DB->Query(
            "SELECT 
                umtr.deal_id, ugtp.protocol_id,  
                ugtp.id ugtp_id, 
                p.ID p_id, p.NUMBER p_number, 
                bo.*, bo.ID bo_id  
                FROM ulab_material_to_request umtr 
                    INNER JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id  
                    INNER JOIN PROTOCOLS p ON p.ID = IF(umtr.protocol_id = 0, ugtp.protocol_id, umtr.protocol_id)  
                    INNER JOIN TZ_OB_CONNECT toc ON toc.PROTOCOL_ID = IF(umtr.protocol_id = 0, ugtp.protocol_id, umtr.protocol_id)   
                    INNER JOIN ba_oborud bo ON bo.ID = toc.ID_OB
                    WHERE ugtp.protocol_id = {$protocolId}"
        );

        while ($row = $sql->Fetch()) {
            $row['actual_certificate'] = $this->getCertificateByOborud($row['bo_id'], true);

            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param int $protocolId
     * @return array
     */
    public function getOborudsForMethods(int $protocolId): array
    {
        $response = [];

        if (empty($protocolId) || $protocolId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT 
                                umtr.deal_id, ugtp.protocol_id,  
                                ugtp.id ugtp_id, 
                                bo.*, bo.ID bo_id  
                                    FROM ulab_material_to_request umtr 
                                    INNER JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id 
                                    INNER JOIN ulab_methods_oborud umo ON umo.method_id = ugtp.method_id 
                                    INNER JOIN ba_oborud bo ON bo.ID = umo.id_oborud
                                    WHERE ugtp.protocol_id = {$protocolId}");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    /**
     * @param $data - Данные контрольной пробы
     * @return array
     */
    public function getSampleStage($data) {
        if (empty($data)) {
            return [
                'bgStage' => '',
                'titleStage' => ''
            ];
        }

        $beforeDate = strtotime($data['EXPIRY_DATE']) - time();

        if (empty($data['IS_ACTUAL'])) {
            $data['bgStage'] = 'bg-grey';
            $data['titleStage'] = 'Образец контроля не актуален';
        } else if (empty($data['UNLIMITED_EXPIRY']) && $beforeDate <= 0) {
            $data['bgStage'] = 'bg-red';
            $data['titleStage'] = 'Истек срок стандартного образца';
        } else if (empty($data['UNLIMITED_EXPIRY']) && $beforeDate < 5184000) {
            $data['bgStage'] = 'bg-yellow';
            $data['titleStage'] = 'До истечения срока стандартного образца осталось менее 90 дней';
        } else if (!empty($data['UNLIMITED_EXPIRY']) || $beforeDate >= 5184000) {
            $data['bgStage'] = 'bg-light-green';
            $data['titleStage'] = '';
        } else {
            $data['bgStage'] = 'bg-dark-red';
            $data['titleStage'] = 'Неизвестный статус';
        }

        return [
            'bgStage' => $data['bgStage'],
            'titleStage' => $data['titleStage']
        ];
    }

    /**
     * Получает данные для журнала стандартных образцов
     * @param array $filter
     * @return array
     */
    public function getSampleList($filter = [])
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => "ss.NAME",
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {
                // Наименование
                if ( isset($filter['search']['NAME']) ) {
                    $where .= "ss.NAME LIKE '%{$filter['search']['NAME']}%' AND ";
                }
                // Номер
                if ( isset($filter['search']['NUMBER']) ) {
                    $where .= "ss.NUMBER LIKE '%{$filter['search']['NUMBER']}%' AND ";
                }
                // Дата выпуска
                if ( isset($filter['search']['MANUFACTURE_DATE']) ) {
                    $where .= "LOCATE('{$filter['search']['MANUFACTURE_DATE']}', DATE_FORMAT(ss.MANUFACTURE_DATE, '%d.%m.%Y')) > 0 AND ";
                }
                // Годен до
                if ( isset($filter['search']['EXPIRY_DATE']) ) {
                    $where .= "LOCATE('{$filter['search']['EXPIRY_DATE']}', DATE_FORMAT(ss.EXPIRY_DATE, '%d.%m.%Y')) > 0 AND ";
                }
                // Метрологические характеристики
                if ( isset($filter['search']['COMPONENTS']) && !empty($filter['search']['COMPONENTS']) ) {
                    $where .= "CONCAT(uc.name, ' ', uc.certified_value) LIKE '%{$filter['search']['COMPONENTS']}%' AND ";
                }

                // Статус
                if ( isset($filter['search']['stage']) ) {
                    $stage = [
                        // Все статусы
                        'all' => "1 AND ",
                        // Не актуальны
                        'unactual' => "ss.IS_ACTUAL <> 1 AND ",
                        // На длительном
                        'unlimited_expiry' => "ss.UNLIMITED_EXPIRY = 1 AND ",
                    ];

                    $where .= $stage[$filter['search']['stage']];
                }

                // Лаба Комната
                if ( isset($filter['search']['lab']) ) {
                    if ( (int)$filter['search']['lab'] == -1 ) {
                        $where .= "ss.LAB_ID = 0 AND ";
                    } else if ( $filter['search']['lab'] < 100 ) {
                        $where .= "ss.LAB_ID = {$filter['search']['lab']} AND ";
                    } else if ($filter['search']['lab'] > 100) {
                        $roomId = (int) $filter['search']['lab'] - 100;
                        $where .= "ss.ROOM_ID = {$roomId} AND ";
                    }
                }
            }
            // везде
            if (isset($filter['search']['everywhere'])) {
                $where .=
                    "";
            }
        }

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'NUMBER':
                    $order['by'] = 'ss.NUMBER';
                    break;
                case 'MANUFACTURE_DATE':
                    $order['by'] = 'ss.MANUFACTURE_DATE';
                    break;
                case 'EXPIRY_DATE':
                    $order['by'] = 'ss.EXPIRY_DATE';
                    break;
                case 'NAME':
                default:
                    $order['by'] = 'ss.NAME';
                    break;
            }
        }

        // работа с пагинацией
        if (isset($filter['paginate'])) {
            $offset = 0;
            // количество строк на страницу
            if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                $length = $filter['paginate']['length'];

                if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                    $offset = $filter['paginate']['start'];
                }
                $limit = "LIMIT {$offset}, {$length}";
            }
        }

        $where .= "1 ";

        $result = [];

        $data = $this->DB->Query(
            "SELECT
                        DISTINCT ss.*
                    FROM ST_SAMPLE ss 
                    LEFT JOIN ulab_component uc ON uc.st_sample_id = ss.ID 
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT
                        DISTINCT ss.*
                    FROM ST_SAMPLE ss 
                    LEFT JOIN ulab_component uc ON uc.st_sample_id = ss.ID 
                    WHERE {$where}"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT
                        DISTINCT ss.*
                    FROM ST_SAMPLE ss 
                    LEFT JOIN ulab_component uc ON uc.st_sample_id = ss.ID 
                    WHERE {$where}"
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $sampleStage = $this->getSampleStage($row);
            $row['components'] = $this->getComponentsBySampleId($row['ID']);

            $row['bgStage'] = $sampleStage['bgStage'];
            $row['titleStage'] = $sampleStage['titleStage'];

            if (!empty($row['MANUFACTURE_DATE'])) {
                $row['MANUFACTURE_DATE'] = date('d.m.Y',  strtotime($row['MANUFACTURE_DATE']));
            }
            if (!empty($row['EXPIRY_DATE'])) {
                $row['EXPIRY_DATE'] = date('d.m.Y', strtotime($row['EXPIRY_DATE']));
            }

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }

    /**
     * Получить данные стандартного образца по id
     * @param $sampleId
     * @return array
     */
    public function getSample($sampleId)
    {
        $response = [];

        if (empty($sampleId) || $sampleId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM ST_SAMPLE WHERE ID = {$sampleId}")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * Добавить данные стандартного образца
     * @param $data
     * @return false|mixed|string
     */
    public function addSample($data)
    {
        // Дата выпуска
        if (empty($data['MANUFACTURE_DATE'])) {
            unset($data['MANUFACTURE_DATE']);
        }
        // Срок годности (Годен до)
        if (empty($data['EXPIRY_DATE'])) {
            unset($data['EXPIRY_DATE']);
        }

        // Срок годности не ограничен (1 - срок годности не ограничен)
        $data['UNLIMITED_EXPIRY'] = $data['UNLIMITED_EXPIRY'] ?? 0;

        $sqlData = $this->prepearTableData('ST_SAMPLE', $data);

        return $this->DB->Insert('ST_SAMPLE', $sqlData);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|string
     */
    public function updateSample($id, $data)
    {
        if (empty($data['MANUFACTURE_DATE'])) {
            unset($data['MANUFACTURE_DATE']);
        }
        if (empty($data['EXPIRY_DATE'])) {
            unset($data['EXPIRY_DATE']);
        }

        $data['UNLIMITED_EXPIRY'] = $data['UNLIMITED_EXPIRY'] ?? 0;

        $sqlData = $this->prepearTableData('ST_SAMPLE', $data);

        $where = "WHERE ID = {$id}";
        return $this->DB->Update('ST_SAMPLE', $sqlData, $where);
    }

    /**
     * @param $sampleId
     * @param $field
     * @param $value
     */
    public function updateFieldSample($sampleId, $field, $value)
    {
        $this->DB->Update('ST_SAMPLE', [$field => $this->quoteStr($this->DB->ForSql($value))], "WHERE ID = {$sampleId}");
    }

    /**
     * Сохранить файл "Описание типа СО"
     * @param $sampleId
     * @param $file
     * @param $folder
     * @return array
     */
    public function saveSampleFile($sampleId, $file, $folder)
    {
        $uploadDescFileDir = UPLOAD_DIR . '/oborud/' . $folder . '/' . $sampleId;

        return $this->saveFile($uploadDescFileDir, $file['name'], $file['tmp_name']);
    }

    /**
     * Получить историю стандартных образцов
     * @param $sampleId
     * @return array
     */
    public function getSampleHistory($sampleId)
    {
        $result = [];

        if (empty($sampleId) || $sampleId < 0) {
            return $result;
        }

        $userModel = new User();

        $sql = $this->DB->Query("SELECT * FROM ulab_st_samples_history WHERE st_sample_id = {$sampleId} order by id asc");

        while ($row = $sql->Fetch()) {
            $user = $userModel->getUserData($row['user_id']);
            $row['short_name'] =  $user['short_name'];
            $row['date'] = date('d.m.Y H:i:s', strtotime($row['date']));

            $result[] = $row;
        }

        return $result;
    }

    /**
     * @param $sampleId
     * @param $action
     */
    public function addHistorySample($sampleId, $action)
    {
        $data = [
            'user_id' => App::getUserId(),
            'st_sample_id' => $sampleId,
            'date' => date('Y-m-d H:i:s'),
            'action' => $action,
        ];

        $sqlData = $this->prepearTableData('ulab_st_samples_history', $data);
        $this->DB->Insert('ulab_st_samples_history', $sqlData);
    }

    /**
     * Получить данные компонентов по id стандартного образца
     * @param $sampleId
     * @return array
     */
    public function getComponentsBySampleId($sampleId)
    {
        $response = [];

        if (empty($sampleId) || $sampleId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM ulab_component WHERE st_sample_id = {$sampleId}");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    /**
     * Получить данные компонента по id
     * @param $componentId
     * @return array
     */
    public function getComponent($componentId)
    {
        $response = [];

        if (empty($componentId) || $componentId < 0) {
            return $response;
        }

        $result = $this->DB->Query("SELECT * FROM ulab_component WHERE id = {$componentId}")->Fetch();

        if (!empty($result)) {
            $response = $result;
        }

        return $response;
    }

    /**
     * Добавить данные компонента
     * @param $data
     * @return false|mixed|string
     */
    public function addComponent($data)
    {
        $sqlData = $this->prepearTableData('ulab_component', $data);

        if (isset($data['uncertainty']) && !is_numeric($data['uncertainty'])) {
            $sqlData['uncertainty'] = 'NULL';
        }

        if (isset($data['error_characteristic']) && !is_numeric($data['error_characteristic'])) {
            $sqlData['error_characteristic'] = 'NULL';
        }

        return $this->DB->Insert('ulab_component', $sqlData);
    }

    /**
     * @param $id
     * @param $data
     * @return bool|int|string
     */
    public function updateComponent($id, $data)
    {
        $sqlData = $this->prepearTableData('ulab_component', $data);

        if (isset($data['uncertainty']) && !is_numeric($data['uncertainty'])) {
            $sqlData['uncertainty'] = 'NULL';
        }

        if (isset($data['error_characteristic']) && !is_numeric($data['error_characteristic'])) {
            $sqlData['error_characteristic'] = 'NULL';
        }

        $where = "WHERE id = {$id}";
        return $this->DB->Update('ulab_component', $sqlData, $where);
    }

    /**
     * Удалить данные компонента по id
     * @param $id
     */
    public function removeComponent($id)
    {
        $response = [
            'success' => true
        ];

        if (empty($id) || $id < 0) {
            return [
                'success' => false,
                'error' => [
                    'message' => "Не удалось удалить данные компонента, не указан или указан неверно ИД компонента",
                ]
            ];
        }

        $this->DB->Query("DELETE FROM ulab_component WHERE id = {$id}");

        return $response;
    }

    /**
     * @return array
     */
    public function getStSamples()
    {
        $response = [];

        $result = $this->DB->Query("SELECT * FROM ST_SAMPLE");

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    /**
     * @return array
     */
    public function getValidComponents()
    {
        $response = [];

        $result = $this->DB->Query(
            "SELECT 
                        uc.*, 
                        ss.ID ss_id, ss.NAME ss_name, ss.NUMBER ss_number, 
                        udc.unit_rus udc_unit_rus 
                    FROM ulab_component uc 
                    INNER JOIN ST_SAMPLE ss ON ss.ID = uc.st_sample_id 
                    LEFT JOIN ulab_dimension as udc on udc.id = uc.certified_unit_id
                    WHERE ss.IS_ACTUAL = 1 AND IF (ss.UNLIMITED_EXPIRY, 1, ss.EXPIRY_DATE > CURDATE())"
        );

        while ($row = $result->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    public function updateOborudsToStorageRoom($oborudIds = [], int $roomId = null)
    {
        if (empty($oborudIds) || empty($roomId)) {
            return [
                'success' => false,
                'error' => [
                    'message' => "Неверные данные оборудования и/или лаборатории",
                ]
            ];
        }
        $organizationId = App::getOrganizationId();
        $this->DB->Query("
            UPDATE ba_oborud
            SET roomnumber = NULL
            WHERE roomnumber = {$roomId}
        ");

        foreach ($oborudIds as $oborudId) {
            $oborudId = (int)$oborudId;
            $this->DB->Update('ba_oborud', ['roomnumber' => $roomId], "WHERE id = {$oborudId} AND organization_id = {$organizationId}");
        }

        return ['success' => true];
    }

    public function updateOborudsToOperatingRoom(array $oborudIds = [], int $roomId = null)
    {
        if (empty($oborudIds) || empty($roomId)) {
            return [
                'success' => false,
                'error' => [
                    'message' => "Неверные данные оборудования и/или лаборатории",
                ]
            ];
        }

        $organizationId = App::getOrganizationId();
        $this->DB->Query("
            UPDATE ba_oborud
            SET roomnumber = NULL
            WHERE roomnumber = {$roomId}
        ");

        foreach ($oborudIds as $oborudId) {
            $oborudId = (int)$oborudId;
            $this->DB->Update('ba_oborud', ['roomnumber' => $roomId], "WHERE id = {$oborudId} AND organization_id = {$organizationId}");
        }

        return ['success' => true];
    }

    public function deleteOborud($oborudId)
    {
        $this->DB->Query("DELETE FROM ba_oborud WHERE ID = '{$oborudId}'");
    }

    public function getOborudByStorageRoom(string $roomId = ''): array
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $sql = $this->DB->Query("
            SELECT ID as id, OBJECT as name, roomnumber as id_storage_room
            FROM ba_oborud
            WHERE roomnumber = '{$roomId}' OR roomnumber IS NULL OR roomnumber = 0 AND organization_id = {$organizationId}
        ");

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getOborudByOperatingRoom(string $roomId = ''): array
    {
        $organizationId = App::getOrganizationId();
        $result = [];

        $sql = $this->DB->Query("
            SELECT ID as id, OBJECT as name, roomnumber as id_storage_room
            FROM ba_oborud
            WHERE roomnumber = '{$roomId}' OR roomnumber IS NULL AND organization_id = {$organizationId}
        ");

        while ($row = $sql->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Проверяет существование оборудования по ID
     * @param int $oborudId
     * @return bool
     */
    public function isExistEquipment(int $oborudId): bool
    {
        $organizationId = App::getOrganizationId();
        $result = $this->DB->Query("SELECT COUNT(*) FROM ba_oborud WHERE ID = {$oborudId} AND organization_id = {$organizationId}");
        return $result->Fetch()['COUNT(*)'] > 0;
    }


    /**
     * получает статистику кол-во оборудования:
     *  Всего единиц оборудования
     *  Истёк срок проверки
     *  Требует проверки
     *  На консервации
     * @param int $organizationId
     * @return array|mixed
     */
    public function getStatisticsCounts(int $organizationId)
    {
        $curDate = date("Y-m-d");

        $sql = $this->DB->Query(
            "select 
                count(distinct o.ID) as all_oborud,
                count(CASE WHEN o.CHECKED = 0 AND o.`LONG_STORAGE` = 0 AND o.`is_decommissioned` = 0 THEN 1 end) as need_check,
                count(CASE WHEN o.LONG_STORAGE <> 0 AND o.`is_decommissioned` = 0 THEN 1 end) as long_storage
            from ba_oborud as o
            where o.organization_id = {$organizationId}"
        )->Fetch();

        $sql2 = $this->DB->Query(
            "select 
                count(o.ID) as end_verification
            from ba_oborud as o
            where o.organization_id = {$organizationId} and o.NO_METR_CONTROL <> 1 and o.LONG_STORAGE = 0 and o.is_decommissioned = 0 and 
            (select max(date_end) from ba_oborud_certificate where is_actual = 1 and oborud_id = o.ID) < '{$curDate}'"
        )->Fetch();

        return [
            'all_oborud' => $sql['all_oborud'],
            'need_check' => $sql['need_check'],
            'long_storage' => $sql['long_storage'],
            'end_verification' => $sql2['end_verification'],
        ];
    }
}