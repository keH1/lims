<?php
/**
 * Модель для работы с материалами
 * Class Material
 */
class Laboratory extends Model
{
	public function getJournal($filter)
	{
		$where = "op.del <> 1 AND ";
		$limit = "";
		$order = [
			'by' => 'op.id',
			'dir' => 'DESC'
		];
		if (!empty($filter)) {
			// из $filter собирать строку $where тут
			// формат такой: $where .= "что-то = чему-то AND ";
			// или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
			// слева без пробела, справа всегда AND пробел

			// работа с фильтрами
			if (!empty($filter['search'])) {
				// Заявка
				if (isset($filter['search']['id'])) {
					$where .= "op.id LIKE '%{$filter['search']['id']}%' AND ";
				}

				if (isset($filter['search']['batch_number'])) {
					$where .= "op.batch_number LIKE '%{$filter['search']['batch_number']}%' AND ";
				}

				if (isset($filter['search']['material_name'])) {
					$where .= "m.NAME LIKE '%{$filter['search']['material_name']}%' AND ";
				}

				if (isset($filter['search']['created_date'])) {
					$where .= "op.created_date LIKE '%{$filter['search']['date']}%' AND ";
				}

				if (isset($filter['search']['manufacturer'])) {
					$where .= "om.manufacturer LIKE '%{$filter['search']['manufacturer']}%' AND ";
				}

				if (isset($filter['search']['quantity'])) {
					$where .= "op.quantity LIKE '%{$filter['search']['quantity']}%' AND ";
				}

				if (isset($filter['search']['client'])) {
					$where .= "op.client LIKE '%{$filter['search']['client']}%' AND ";
				}

				if (isset($filter['search']['order_number'])) {
					$where .= "op.order_number LIKE '%{$filter['search']['order_number']}%' AND ";
				}

				if (isset($filter['search']['composition_code'])) {
					$where .= "op.composition_code LIKE '%{$filter['search']['composition_code']}%' AND ";
				}

//                if (isset($filter['search']['ASSIGNED'])) {
//                    $where .= "op.assigned LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
//                }

				// даты
				if (isset($filter['search']['dateStart'])) {
					$where .= "op.created_date >= '{$filter['search']['dateStart']}' AND ";
				}

				if (isset($filter['search']['dateEnd'])) {
					$where .= "op.created_date <= '{$filter['search']['dateEnd']}' AND ";
				}

				if (isset($filter['search']['hidden'])) {
					$where .= "op.hidden = {$filter['search']['hidden']} AND ";
				}
			}

			// работа с сортировкой
			if (!empty($filter['order'])) {
				if ($filter['order']['dir'] === 'asc') {
					$order['dir'] = 'ASC';
				}

				switch ($filter['order']['by']) {
					case 'id':
						$order['by'] = "op.id";
						break;
					case 'batch_number':
						$order['by'] = "op.batch_number";
						break;
					case 'material_name':
						$order['by'] = "m.NAME";
						break;
					case 'date':
						$order['by'] = "ot.created_date";
						break;
					case 'manufacturer':
						$order['by'] = "om.manufacturer";
						break;
					case 'assigned':
						$order['by'] = "op.assigned_name";
						break;
					case 'composition_code':
						$order['by'] = "op.composition_code";
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
		}

		$where .= "1 ";

		$result = [];



		$sql = "
            SELECT op.*, DATE_FORMAT(op.created_date, '%d.%m.%Y') AS date, os.material_id, m.NAME AS material_name, om.manufacturer, bt.ID_Z as deal_id, 
                   bt.STAGE_ID AS stage_id, CONCAT(UPPER(SUBSTRING(bu.NAME,1,1)), '. ', bu.LAST_NAME) AS assigned,
            (SELECT CONCAT('[', GROUP_CONCAT(opg.value SEPARATOR ', '), ']') 
              FROM oz_passport_gost AS opg WHERE opg.oz_passport_id = op.id) AS custom_gosts,
            (SELECT CONCAT('[', GROUP_CONCAT(opg.value >= IFNULL(osg.range_from, -2147483648) AND opg.value <= IFNULL(osg.range_before, 2147483647) SEPARATOR ', '), ']') AS check_status 
              FROM oz_passport_gost AS opg 
              LEFT JOIN oz_scheme_gost AS osg ON osg.id = opg.scheme_gost_id
              WHERE opg.oz_passport_id = op.id AND osg.laboratory_status = 1) AS oz_suitable_status,
            (SELECT CONCAT('[', GROUP_CONCAT((if (JSON_EXTRACT(utr_us.actual_value, '$[0]') REGEXP '[а-Я]' = 1, 2, if (JSON_SEARCH(utr_us.actual_value, 'all', '') IS NOT NULL, NULL, utr_us.average_value >= IFNULL(osg_us.range_from, -2147483648) AND utr_us.average_value <= IFNULL(osg_us.range_before, 2147483647)))) SEPARATOR ', '), ']') AS check_status_us 
              FROM ulab_material_to_request umtr_us
              LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
              LEFT JOIN ulab_trial_results utr_us ON utr_us.gost_to_probe_id = ugtp_us.id 
              LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id 
              LEFT JOIN oz_passport AS op_us ON op_us.ba_tz_id = bt_us.ID
              LEFT JOIN oz_scheme_gost AS osg_us ON osg_us.scheme_id = op_us.scheme_id AND osg_us.method_id = ugtp_us.new_method_id
              WHERE bt_us.ID = bt.ID AND osg_us.laboratory_status = 0) AS ulab_suitable_status,
            (SELECT CONCAT('[', GROUP_CONCAT((if (ugtp_us.actual_value REGEXP '[а-Я]' = 1, 2, if (ugtp_us.actual_value IS NULL, NULL, ugtp_us.actual_value >= IFNULL(osg_us.range_from, -2147483648) AND ugtp_us.actual_value <= IFNULL(osg_us.range_before, 2147483647)))) SEPARATOR ', '), ']') AS check_status_us 
              FROM ulab_material_to_request umtr_us
              LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
              LEFT JOIN ulab_trial_results utr_us ON utr_us.gost_to_probe_id = ugtp_us.id 
              LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id 
              LEFT JOIN oz_passport AS op_us ON op_us.ba_tz_id = bt_us.ID
              LEFT JOIN oz_scheme_gost AS osg_us ON osg_us.scheme_id = op_us.scheme_id AND osg_us.method_id = ugtp_us.new_method_id
              WHERE bt_us.ID = bt.ID AND osg_us.laboratory_status = 0) AS ulab_suitable_status_new,
            (SELECT CONCAT('[', GROUP_CONCAT(ugtp_us.method_id SEPARATOR ', '), ']')
              FROM ulab_material_to_request umtr_us
              LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
              LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id
              WHERE bt_us.ID = bt.ID) AS ulab_methods,
            (SELECT CONCAT('[', GROUP_CONCAT(osg_us.method_id SEPARATOR ', '), ']')
              FROM oz_scheme_gost osg_us
              WHERE osg_us.scheme_id = op.scheme_id AND osg_us.laboratory_status = 0) AS scheme_methods
            FROM oz_passport AS op
            LEFT JOIN ba_tz AS bt ON bt.ID = op.ba_tz_id
            LEFT JOIN oz_scheme AS os ON os.id = op.scheme_id
            LEFT JOIN b_user AS bu ON bu.ID = op.assigned_id
            LEFT JOIN MATERIALS AS m ON m.ID = os.material_id
            LEFT JOIN oz_materials AS om ON om.ulab_material_id = m.ID
            WHERE {$where}
            GROUP BY op.id ORDER BY {$order['by']} {$order['dir']} {$limit}
            ";

		$data = $this->DB->query($sql);

		$dataTotal = $this->DB->Query(
			"SELECT id FROM oz_passport"
		)->SelectedRowsCount();

		$dataFiltered = $this->DB->Query(
			"SELECT id FROM oz_passport"
		)->SelectedRowsCount();

		while ($row = $data->Fetch()) {
			$row["oz_suitable_status"] = json_decode($row["oz_suitable_status"]);

			if ($row["ba_tz_id"] > 10713) {
				$row["ulab_suitable_status"] = json_decode($row["ulab_suitable_status_new"]);
			} else {
				$row["ulab_suitable_status"] = json_decode($row["ulab_suitable_status"]);
			}

			$result[] = $row;
		}

		$result['recordsTotal'] = $dataTotal;
		$result['recordsFiltered'] = $dataFiltered;

		return $result;
	}

	public function getDataRegistrationJournal($filter)
	{
		// $where = "b.TYPE_ID = 7 AND ";
		$where = "ot.del <> 1 AND ";
		$limit = "";
		$order = [
			'by' => 'ot.id',
			'dir' => 'DESC'
		];
		if (!empty($filter)) {
			// из $filter собирать строку $where тут
			// формат такой: $where .= "что-то = чему-то AND ";
			// или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
			// слева без пробела, справа всегда AND пробел


			// работа с фильтрами
			if (!empty($filter['search'])) {
				// Заявка
				if (isset($filter['search']['id'])) {
					$where .= "ot.id LIKE '%{$filter['search']['id']}%' AND ";
				}

				if (isset($filter['search']['batch_number'])) {
					$where .= "ot.batch_number LIKE '%{$filter['search']['batch_number']}%' AND ";
				}

				if (isset($filter['search']['material_name'])) {
					$where .= "m.NAME LIKE '%{$filter['search']['material_name']}%' AND ";
				}

				if (isset($filter['search']['fraction_name'])) {
					$where .= "ot.fraction_name LIKE '%{$filter['search']['fraction_name']}%' AND ";
				}

				if (isset($filter['search']['date'])) {
					$where .= "ot.date LIKE '%{$filter['search']['date']}%' AND ";
				}

				if (isset($filter['search']['manufacturer'])) {
					$where .= "om.manufacturer LIKE '%{$filter['search']['manufacturer']}%' AND ";
				}

				if (isset($filter['search']['ASSIGNED'])) {
					$where .= "ot.assigned LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
				}

				// даты
				if (isset($filter['search']['dateStart'])) {
					$where .= "ot.date >= '{$filter['search']['dateStart']}' AND ";
				}

				if (isset($filter['search']['dateEnd'])) {
					$where .= "ot.date <= '{$filter['search']['dateEnd']}' AND ";
				}
			}

			// работа с сортировкой
			if (!empty($filter['order'])) {
				if ($filter['order']['dir'] === 'asc') {
					$order['dir'] = 'ASC';
				}

				switch ($filter['order']['by']) {
					case 'id':
						$order['by'] = "ot.id";
						break;
					case 'batch_number':
						$order['by'] = "ot.batch_number";
						break;
					case 'material_name':
						$order['by'] = "m.NAME";
						break;
					case 'fraction_name':
						$order['by'] = "ot.fraction_name";
						break;
					case 'date':
						$order['by'] = "ot.date";
						break;
					case 'manufacturer':
						$order['by'] = "om.manufacturer";
						break;
					case 'assigned':
						//  $order['by'] = "b.assigned";
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
		}

		// $where .= "TYPE_ID = 'SALE' ";
		$where .= "1 ";

		$result = [];

//        $data = $this->DB->query(
//            "SELECT
//                *,
//                DATE_FORMAT(DATE_SOZD, '%d.%m.%Y') AS DATE_SOZD,
//                (SELECT GROUP_CONCAT(CONCAT(UPPER(SUBSTRING(b_u.NAME,1,1)), '. ', b_u.LAST_NAME) SEPARATOR ', ')
//                 FROM assigned_to_request AS a_r
//                 LEFT JOIN b_user As b_u ON b_u.ID = a_r.user_id
//                 WHERE deal_id = ba_tz.ID_Z) AS ASSIGNED
//             FROM ba_tz
//             WHERE {$where}
//             ORDER BY {$order['by']} {$order['dir']} {$limit}"
//        );

		$sql = "
            SELECT ot.*, DATE_FORMAT(ot.date, '%d.%m.%Y') AS date, os.material_id, m.NAME AS material_name, om.manufacturer, bt.ID_Z as deal_id, bt.STAGE_ID AS stage_id,
            CONCAT(UPPER(SUBSTRING(bu.NAME,1,1)), '. ', bu.LAST_NAME) AS assigned,
            (SELECT GROUP_CONCAT(CONCAT(UPPER(SUBSTRING(b_u.NAME,1,1)), '. ', b_u.LAST_NAME) SEPARATOR ', ')
              FROM assigned_to_request AS a_r
              LEFT JOIN b_user As b_u ON b_u.ID = a_r.user_id
                                 WHERE a_r.deal_id = bt.ID_Z) AS ulab_assigned,
            (SELECT CONCAT('[', GROUP_CONCAT(otg.value SEPARATOR ', '), ']') 
              FROM oz_tz_gost AS otg WHERE otg.oz_tz_id = ot.id) AS custom_gosts,
            (SELECT CONCAT('[', GROUP_CONCAT(otg.value >= IFNULL(osg.range_from, -2147483648) AND otg.value <= IFNULL(osg.range_before, 2147483647) SEPARATOR ', '), ']') AS check_status 
              FROM oz_tz_gost AS otg 
              LEFT JOIN oz_scheme_gost AS osg ON osg.id = otg.scheme_gost_id
              WHERE otg.oz_tz_id = ot.id AND osg.laboratory_status = 1) AS oz_suitable_status,
            (SELECT CONCAT('[', GROUP_CONCAT((if (JSON_EXTRACT(utr_us.actual_value, '$[0]') REGEXP '[а-Я]' = 1, 2, if (JSON_SEARCH(utr_us.actual_value, 'all', '') IS NOT NULL, NULL, utr_us.average_value >= IFNULL(osg_us.range_from, -2147483648) AND utr_us.average_value <= IFNULL(osg_us.range_before, 2147483647)))) SEPARATOR ', '), ']') AS check_status_us
              FROM ulab_material_to_request umtr_us
              LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
              LEFT JOIN ulab_trial_results utr_us ON utr_us.gost_to_probe_id = ugtp_us.id 
              LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id 
              LEFT JOIN oz_tz AS ot_us ON ot_us.ba_tz_id = bt_us.ID
              LEFT JOIN oz_scheme_gost AS osg_us ON osg_us.scheme_id = ot_us.scheme_id AND osg_us.method_id = ugtp_us.new_method_id
              WHERE bt_us.ID = bt.ID AND osg_us.laboratory_status = 0) AS ulab_suitable_status,
            (SELECT CONCAT('[', GROUP_CONCAT((if (ugtp_us.actual_value REGEXP '[а-Я]' = 1, 2, if (ugtp_us.actual_value IS NULL, NULL, ugtp_us.actual_value >= IFNULL(osg_us.range_from, -2147483648) AND ugtp_us.actual_value <= IFNULL(osg_us.range_before, 2147483647)))) SEPARATOR ', '), ']') AS check_status_us 
              FROM ulab_material_to_request umtr_us
              LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
              LEFT JOIN ulab_trial_results utr_us ON utr_us.gost_to_probe_id = ugtp_us.id 
              LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id 
              LEFT JOIN oz_tz AS ot_us ON ot_us.ba_tz_id = bt_us.ID
              LEFT JOIN oz_scheme_gost AS osg_us ON osg_us.scheme_id = ot_us.scheme_id AND osg_us.method_id = ugtp_us.new_method_id
              WHERE bt_us.ID = bt.ID AND osg_us.laboratory_status = 0) AS ulab_suitable_status_new,
            (SELECT CONCAT('[', GROUP_CONCAT(ugtp_us.method_id SEPARATOR ', '), ']')
              FROM ulab_material_to_request umtr_us
              LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
              LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id
              WHERE bt_us.ID = bt.ID) AS ulab_methods,
            (SELECT CONCAT('[', GROUP_CONCAT(osg_us.method_id SEPARATOR ', '), ']')
              FROM oz_scheme_gost osg_us
              WHERE osg_us.scheme_id = ot.scheme_id AND osg_us.laboratory_status = 0) AS scheme_methods
            FROM oz_tz AS ot
            LEFT JOIN ba_tz AS bt ON bt.ID = ot.ba_tz_id
            LEFT JOIN b_user AS bu ON bu.ID = ot.assigned_id
            LEFT JOIN oz_scheme AS os ON os.id = ot.scheme_id
            LEFT JOIN MATERIALS AS m ON m.ID = os.material_id
            LEFT JOIN oz_materials AS om ON om.ulab_material_id = m.ID
            WHERE {$where}
            GROUP BY ot.id ORDER BY {$order['by']} {$order['dir']} {$limit}
            ";

		$data = $this->DB->Query($sql);

		$dataTotal = $this->DB->Query(
			"SELECT id FROM oz_tz"
		)->SelectedRowsCount();

		$dataFiltered = $this->DB->Query(
			"SELECT id FROM oz_tz"
		)->SelectedRowsCount();



		//    $result = $data->fetch_all(MYSQLI_ASSOC);
		while ($row = $data->Fetch()) {
			//  $stage = $this->getStage($row);

			// $row['titleStage'] = $stage['title'];
			//  $row['bgStage'] = $stage['color'];
			$row["oz_suitable_status"] = json_decode($row["oz_suitable_status"]);
			//   $row["ulab_suitable_status"] = json_decode($row["ulab_suitable_status"]);
//            $row["ulab_suitable_status"] = [true];
			if ($row["ba_tz_id"] > 10713) {
				$row["ulab_suitable_status"] = json_decode($row["ulab_suitable_status_new"]);
			} else {
				$row["ulab_suitable_status"] = json_decode($row["ulab_suitable_status"]);
			}
			$result[] = $row;
		}

		$result['recordsTotal'] = $dataTotal;
		$result['recordsFiltered'] = $dataFiltered;
		$result['test'] = $sql;

		return $result;
	}

//    public function getDataRegistrationJournal($filter)
//    {
//       // $where = "b.TYPE_ID = 7 AND ";
//        $where = "";
//        $limit = "";
//        $order = [
//            'by' => 'b.ID_Z',
//            'dir' => 'DESC'
//        ];
//        if (!empty($filter)) {
//            // из $filter собирать строку $where тут
//            // формат такой: $where .= "что-то = чему-то AND ";
//            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
//            // слева без пробела, справа всегда AND пробел
//
//
//            // работа с фильтрами
//            if (!empty($filter['search'])) {
//                // Заявка
//                if (isset($filter['search']['REQUEST_TITLE'])) {
//                    $where .= "b.REQUEST_TITLE LIKE '%{$filter['search']['REQUEST_TITLE']}%' AND ";
//                }
//
//                if (isset($filter['search']['probe_number'])) {
//                    $where .= "b.probe_number LIKE '%{$filter['search']['probe_number']}%' AND ";
//                }
//
//                if (isset($filter['search']['MATERIAL'])) {
//                    $where .= "b.MATERIAL LIKE '%{$filter['search']['MATERIAL']}%' AND ";
//                }
//
//                if (isset($filter['search']['DATE_SOZD'])) {
//                    $where .= "b.DATE_SOZD LIKE '%{$filter['search']['DATE_SOZD']}%' AND ";
//                }
//
//                if (isset($filter['search']['COMPANY_TITLE'])) {
//                    $where .= "b.COMPANY_TITLE LIKE '%{$filter['search']['COMPANY_TITLE']}%' AND ";
//                }
//
//                if (isset($filter['search']['ASSIGNED'])) {
//                    $where .= "b.ASSIGNED LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
//                }
//
//                // даты
//                if (isset($filter['search']['dateStart'])) {
//                    $where .= "b.DATE_SOZD >= '{$filter['search']['dateStart']}' AND ";
//                }
//
//                if (isset($filter['search']['dateEnd'])) {
//                    $where .= "b.DATE_SOZD <= '{$filter['search']['dateEnd']}' AND ";
//                }
//            }
//
//            // работа с сортировкой
//            if (!empty($filter['order'])) {
//                if ($filter['order']['dir'] === 'asc') {
//                    $order['dir'] = 'ASC';
//                }
//
//                switch ($filter['order']['by']) {
//                    case 'REQUEST_TITLE':
//                        $order['by'] = "b.REQUEST_TITLE";
//                        break;
//                    case 'probe_number':
//                        $order['by'] = "b.probe_number";
//                        break;
//                    case 'MATERIAL':
//                        $order['by'] = "b.MATERIAL";
//                        break;
//                    case 'DATE_SOZD':
//                        $order['by'] = "b.DATE_SOZD";
//                        break;
//                    case 'COMPANY_TITLE':
//                        $order['by'] = "b.COMPANY_TITLE";
//                        break;
//                    case 'ASSIGNED':
//                        $order['by'] = "b.ASSIGNED";
//                        break;
//                }
//            }
//
//            // работа с пагинацией
//            if (isset($filter['paginate'])) {
//                $offset = 0;
//                // количество строк на страницу
//                if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
//                    $length = $filter['paginate']['length'];
//
//                    if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
//                        $offset = $filter['paginate']['start'];
//                    }
//                    $limit = "LIMIT {$offset}, {$length}";
//                }
//            }
//        }
//
//       // $where .= "TYPE_ID = 'SALE' ";
//        $where .= "1 ";
//
//        $result = [];
//
////        $data = $this->DB->query(
////            "SELECT
////                *,
////                DATE_FORMAT(DATE_SOZD, '%d.%m.%Y') AS DATE_SOZD,
////                (SELECT GROUP_CONCAT(CONCAT(UPPER(SUBSTRING(b_u.NAME,1,1)), '. ', b_u.LAST_NAME) SEPARATOR ', ')
////                 FROM assigned_to_request AS a_r
////                 LEFT JOIN b_user As b_u ON b_u.ID = a_r.user_id
////                 WHERE deal_id = ba_tz.ID_Z) AS ASSIGNED
////             FROM ba_tz
////             WHERE {$where}
////             ORDER BY {$order['by']} {$order['dir']} {$limit}"
////        );
//
//        $data = $this->DB->query(
//            "SELECT DISTINCT b.ID, b.TZ, b.STAGE_ID, b.ID_Z, b.ACT_NUM, b.REQUEST_TITLE, b.TAKEN_SERT_ISP, b.RESULTS,
//                    CONVERT(substring_index(substring_index(b.REQUEST_TITLE, '№', -1), '/', 1 ),UNSIGNED INTEGER) request,
//                    b.DATE_CREATE_TIMESTAMP, b.COMPANY_TITLE, b.DEADLINE,  b.DEADLINE_TABLE, b.ACCOUNT, b.MATERIAL,
//                    b.ASSIGNED, b.NUM_ACT_TABLE, b.PRICE, b.OPLATA, b.DATE_OPLATA, b.PDF, b.ID_Z,
//                    b.DOGOVOR_TABLE, b.probe_number,
//                    b.MANUFACTURER_TITLE, b.USER_HISTORY, b.LABA_ID, b.ACTUAL_VER b_actual_ver, c.leader, c.confirm,
//                    count(c.id) c_count, count(c.date_return) с_date_return, k.ID k_id,
//                    DATE_FORMAT(b.DATE_SOZD, '%d.%m.%Y') AS DATE_SOZD,
//                    (SELECT GROUP_CONCAT(CONCAT(UPPER(SUBSTRING(b_u.NAME,1,1)), '. ', b_u.LAST_NAME) SEPARATOR ', ')
//                     FROM assigned_to_request AS a_r
//                     LEFT JOIN b_user As b_u ON b_u.ID = a_r.user_id
//                     WHERE a_r.deal_id = b.ID_Z) AS ASSIGNED
//
//                FROM ba_tz b
//                LEFT JOIN ACT_BASE a ON a.ID_TZ = b.ID
//                LEFT JOIN CHECK_TZ c ON b.ID=c.tz_id
//                LEFT JOIN KP k ON b.ID=k.TZ_ID
//                LEFT JOIN PROTOCOLS p ON p.ID_TZ=b.ID
//                LEFT JOIN DOGOVOR d ON d.TZ_ID=b.ID
//                LEFT JOIN AKT_VR act ON act.TZ_ID=b.ID
//                WHERE b.TYPE_ID != '3' AND b.REQUEST_TITLE <> '' AND {$where}
//                GROUP BY b.ID ORDER BY {$order['by']} {$order['dir']} {$limit}");
//
//        $sql = "";
//
//        $data = $this->DB->query($sql);
//
//        $dataTotal = $this->DB->Query(
//            "SELECT ID_Z FROM ba_tz"
//        )->SelectedRowsCount();
//
//        $dataFiltered = $this->DB->Query(
//            "SELECT b.ID_Z FROM ba_tz b WHERE {$where}"
//        )->SelectedRowsCount();
//
//
//
//    //    $result = $data->fetch_all(MYSQLI_ASSOC);
//        while ($row = $data->fetch_assoc()) {
//            $stage = $this->getStage($row);
//
//            $row['titleStage'] = $stage['title'];
//            $row['bgStage'] = $stage['color'];
//
//            $result[] = $row;
//        }
//
//        $result['recordsTotal'] = $dataTotal;
//        $result['recordsFiltered'] = $dataFiltered;
//
//        return $result;
//    }

	/**
	 * @param $data
	 * @return false|int
	 */
	public function create( $data )
	{
		// $request = $this->model("Request");
		$year = (int)date("Y")%10 ? substr(date("Y"), -2) : date("Y");
		$countDeal = $this->getCountDeal() + 1;

		//$newDeal = new CCrmDeal;

		// TODO: убрать костыль
		while (1) {
			$title = "{$data['type_rus']} №{$countDeal}/{$year}";

			$tmp = $this->DB->Query("SELECT ID FROM `b_crm_deal` WHERE `TITLE` = '{$title}'")->fetch_row();

			if ( empty($tmp) ) {
				break;
			} else {
				$countDeal++;
			}
		}

		$arFields = [
			"TITLE" => $title,
			"COMPANY_ID" => $data['company_id'],
			"TYPE_ID" => 7,
			"ASSIGNED_BY_ID" => $data['assigned'],
		];

		//$result = $newDeal->Add($arFields);
		$result = $this->b24("crm.deal.add", ["fields" => $arFields])["result"];

		$arDealUpdate = [
			//   'STAGE_ID' => 'PREPARATION',
			'STAGE_ID' => '1',
			'UF_CRM_1571643970' => $data['arrAssigned'],
		];

		$params = ['DISABLE_USER_FIELD_CHECK' => true];

		if ( $result ) {
			$this->b24("crm.deal.update",
				[
					"id" => $result,
					"fields" => $arDealUpdate,
					"params" =>  $params
				]);
		}

		return $result;
	}

	public function getDealById(int $idDeal)
	{
		//$request = $this->model('Request');
		return  $this->b24("crm.deal.get", ["id" => $idDeal])["result"];
	}

	public function getCountDeal($type = '')
	{
		//  $count = 22; // Заглушка
		$curYear = date("Y");

		$sql = "SELECT count(*) AS count
                FROM `b_crm_deal`
                WHERE DATE_CREATE > '{$curYear}-01-01' AND TYPE_ID != 1";

		return $this->DB->query($sql)->fetch_row()[0];
	}

	public function addTz($dealId, $data)
	{
		$dateCreate = date('d.m.Y');
		$dateCreateTimestamp = date('Y-m-d H:i:s');

		$data['ID_Z'] = $dealId;
		$materialName = $data["MATERIAL"];
		$stageId = $data["STAGE_ID"];
		$requestTitle = $data["REQUEST_TITLE"];
		$companyTitle = $data["COMPANY_TITLE"];
		$companyId = $data["COMPANY_ID"];
		$reqType = $data["TYPE_ID"];
		$tz = $data["TZ"];




//        $data['DATE_CREATE_TIMESTAMP'] = "'{$dateCreateTimestamp}'";

		$sql = "INSERT INTO ba_tz (ID_Z, TZ, MATERIAL, DATE_CREATE, DATE_CREATE_TIMESTAMP, probe_number, STAGE_ID, REQUEST_TITLE, COMPANY_TITLE, COMPANY_ID, TYPE_ID, DOGOVOR_NUM, DAY_TO_TEST, type_of_day)
                VALUES ({$dealId}, '{$tz}', '{$materialName}', '{$dateCreate}', '{$dateCreateTimestamp}', 1, '{$stageId}', '{$requestTitle}', '{$companyTitle}', {$companyId}, '{$reqType}', 1657, 3, 'work_day')";

		$this->DB->query($sql);

		return $this->DB->LastID();
		//   return $this->DB->insertT("ba_tz", $data);
	}

	public function updateTz($dealId, $data)
	{
		$where = "WHERE ID_Z = {$dealId}";
		return $this->DB->update('ba_tz', $data, $where);
	}

//    public function b24($method, $arData = [])
//    {
//        $queryUrl = "https://ulab.niistrom.pro/rest/84/1rp7cy5qsgmmn8eh/" . $method . "/";
//        $curl = curl_init();
//        $timeout = 0;
//        curl_setopt ( $curl, CURLOPT_URL, $queryUrl );
//        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
//        curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
//        curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, $timeout );
//
//        if(!empty($arData)){
//            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($arData));
//        }
//
//        $result = curl_exec($curl);
//        curl_close($curl);
//
//        return json_decode($result,true);
//    }

	public function getAssignedByDealId($dealId)
	{
		$users = $this->DB->query("SELECT user_id, is_main FROM `assigned_to_request` WHERE deal_id = {$dealId}");

		// $request = $this->model("Request");

		$result = [];
		while ($row = $users->fetch_assoc()) {
			$user = $this->b24("user.get", ["ID" => $row['user_id']])["result"][0];
			//   $user = CUser::GetByID($row['user_id'])->Fetch();
			$name = trim($user['NAME']);
			$lastName = trim($user['LAST_NAME']);
			// $shortName = StringHelper::shortName($name);
			$shortName = $name;

			$resultData = [
				'user_id' => $row['user_id'],
				'name' => trim($name),
				'last_name' => trim($lastName),
				'user_name' => "{$name} {$lastName}",
				'short_name' => "{$shortName}. {$lastName}",
				'is_main' => $row['is_main'],
				'department' => $user["UF_DEPARTMENT"],
			];

			$result[] = $resultData;
		}

		return $result;
	}

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
			&& (!$row['OPLATA'] || (float)$row['OPLATA'] < (float)$row['PRICE'])
			&& $row['STAGE_ID'] === '2'
		) {
			$bgColor = 'bg-dark-red';
			$title = 'Испытания в лаборатории завершены. Оплата не поступила.';
		}

		$confirm = empty($row['confirm'])? 0 : 1;

		if (!empty($row['c_count']) && empty($confirm) && empty($row['с_date_return'])) {
			$bgColor = 'bg-dark-blue';
			$title = 'Заявка на стадии проверки ТЗ';
		}

		return [
			'title' => $title,
			'color' => $bgColor,
		];
	}

	public function getResultCardInfo($ozTzId)
	{

		$sql = "SELECT ot.*, m.NAME as material_name, om.manufacturer, 
                       bt.REQUEST_TITLE AS ulab_title, bt.ID_Z AS deal_id, bt.DAY_TO_TEST AS day_to_test, bt.STAGE_ID AS stage_id,
                       c.ID AS comment_id, c.TEXT AS comment_text  
                FROM oz_tz AS ot
                LEFT JOIN oz_scheme AS os ON os.id = ot.scheme_id
                LEFT JOIN MATERIALS AS m ON m.ID = os.material_id
                LEFT JOIN oz_materials AS om ON om.ulab_material_id = m.ID
                LEFT JOIN ba_tz AS bt ON bt.ID = ot.ba_tz_id
                LEFT JOIN COMMENTS AS c ON c.ID_REQ = bt.ID_Z 
                WHERE ot.id = {$ozTzId}";

		$stmt = $this->DB->query($sql);

		return $stmt->fetch_assoc();
	}

	public function getResultCard($ozTzId)
	{
		$sql = "SELECT umtr.id umtr_id, umtr.deal_id, umtr.probe_number, umtr.cipher, umtr.protocol_id, 
                       m.ID m_id, m.NAME m_mame, 
                       ugtp.id ugtp_id, ugtp.method_id, ugtp.conditions_id, ugtp.gost_number, ugtp.actual_value AS actual_value_new, 
                       ug.reg_doc bgm_gost, um.name bgm_specification, bgm.ED bgm_ed, um.clause GOST_PUNKT,
                       utr.match, utr.actual_value, utr.normative_value, utr.average_value, p.NUMBER p_number,
                       DATE_FORMAT(ba.DATE_SOZD, '%d.%m.%Y') AS date,
                       ud.unit_rus AS unit_char, 
                       ot.scheme_id, sg.range_from, sg.range_before,
                       JSON_EXTRACT(utr.actual_value, '$[0]') AS actual_value_text, JSON_EXTRACT(utr.actual_value, '$[0]') REGEXP '[а-Я]' AS actual_value_type 
                FROM ulab_material_to_request umtr 
                LEFT JOIN MATERIALS m ON m.ID = umtr.material_id 
                LEFT JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id 
                LEFT JOIN ba_gost bgm ON bgm.ID = ugtp.method_id
                LEFT JOIN ulab_trial_results utr ON utr.gost_to_probe_id = ugtp.id 
                LEFT JOIN PROTOCOLS p ON p.ID = umtr.protocol_id 
                LEFT JOIN ba_tz ba ON ID_Z = umtr.deal_id 
                LEFT JOIN oz_tz ot ON ot.ba_tz_id = ba.ID
                
                LEFT JOIN ulab_methods um ON um.id = ugtp.new_method_id
                LEFT JOIN ulab_dimension ud ON ud.id = um.unit_id 
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                LEFT JOIN oz_scheme_gost sg ON sg.scheme_id = ot.scheme_id AND sg.method_id = um.id
                WHERE ot.id = {$ozTzId} GROUP BY ugtp_id";

		//AND bgm.GOST_TYPE <> 'metodic_otbor'

		$data = [];

		$stmt = $this->DB->query($sql);

		$index = 0;

		while ($row = $stmt->fetch_assoc()) {
			if (intval($row["deal_id"]) > 10752) {
				$actualValue = $row["actual_value_new"];
			} else {
				if ($row["actual_value"] == "[\"\"]") {
					$row["actual_value"] = NULL;
				}

				$actualValue = str_replace(['"', '[', ']'], '', $row["actual_value"]);
				$actualValue = str_replace([','], '.', $actualValue);
			}

			$data[$index]["title"] = $row["bgm_specification"];
			$data[$index]["value"] = $row["average_value"];
			$data[$index]["bgm_ed"] = $row["bgm_ed"];
			$data[$index]["unit_char"] = $row["unit_char"];
			$data[$index]["gost"] = $row["bgm_gost"];
			$data[$index]["gost_punkt"] = $row["GOST_PUNKT"];
			$data[$index]["range_from"] = $row["range_from"];
			$data[$index]["range_before"] = $row["range_before"];
			$data[$index]["scheme_id"] = $row["scheme_id"];

			//  $avgValue = $row["average_value"];


			$data[$index]["actual_value"] = $actualValue;

			$rangeFrom = $row["range_from"];
			$rangeBefore = $row["range_before"];

			if (is_null($rangeFrom)) {
				$rangeFrom = -INF;
			} else {
				$rangeFrom = floatval($rangeFrom);
			}

			if (is_null($rangeBefore)) {
				$rangeBefore = INF;
			} else {
				$rangeBefore = floatval($rangeBefore);
			}

			// if (is_null($avgValue) || $avgValue == 0) {
			if (is_null($actualValue) || empty($actualValue)) {
				$data[$index]["background"] = "";
			} else {
				$actualValue = floatval($actualValue);
				$data[$index]["background"] = $actualValue >= $rangeFrom && $actualValue <= $rangeBefore ? "bg-light-green-2" : "bg-light-red";
			}

			if ($row["actual_value_type"] == 1) {
				$data[$index]["background"] = "bg-orange-2";
				$data[$index]["value"] = str_replace('"', '', $row["actual_value_text"]);
			}

			$row["test"] = "{$actualValue} >= {$rangeFrom} && {$actualValue} <= {$rangeBefore}";

			$index++;
		}

		return $data;
	}

	public function getCustomGostList($ozTzId)
	{
		$sql = "SELECT otg.id AS oz_tz_gost_id, otg.value, sg.*, ug.reg_doc AS gost, um.name AS spec
                FROM oz_tz_gost AS otg
                LEFT JOIN oz_tz AS ot ON otg.oz_tz_id = ot.id
                LEFT JOIN oz_scheme_gost AS sg ON sg.id = otg.scheme_gost_id
                LEFT JOIN ulab_methods um ON um.id = sg.method_id
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                WHERE ot.id = {$ozTzId}";

		$stmt = $this->DB->query($sql);
		$data = [];

		while ($row = $stmt->fetch_assoc()) {


			$avgValue = $row["value"];
			$rangeFrom = $row["range_from"];
			$rangeBefore = $row["range_before"];

			$valueStatus = "";

			if (is_null($rangeFrom)) {
				$rangeFrom = -INF;
			} else {
				$rangeFrom = floatval($rangeFrom);
			}

			if (is_null($rangeBefore)) {
				$rangeBefore = INF;
			} else {
				$rangeBefore = floatval($rangeBefore);
			}

			if (is_null($avgValue)) {
				$row["background"] = "";
			} else {
				$avgValue = floatval($avgValue);
				$row["background"] = $avgValue >= $rangeFrom && $avgValue <= $rangeBefore ? "bg-light-green-2" : "bg-light-red";
			}

			$row["test"] = "{$avgValue} >= {$rangeFrom} && {$avgValue} <= {$rangeBefore}";



//            if (is_null($row["range_from"]) && is_null($row["range_before"]) || is_null($row["value"])) {
//                $row["background"] = "";
//            } else {
//                $row["background"] = $avgValue >= $rangeFrom && $avgValue <= $rangeBefore ? "bg-light-green-2" : "bg-light-red";
//            }

			$data[] = $row;
		}

		return $data;
	}

	public function getChemMethods()
	{
		$sql = $this->DB->query("SELECT 
        bgm.id,bgm.gost_id as gost_id,bgm.methods as methods,bgm.type as type,
        bg.GOST as gost, bg.GOST_PUNKT as gost_punkt
        FROM ba_gost_methods bgm
        LEFT JOIN ba_gost bg ON bg.ID = bgm.gost_id
        WHERE type = 'ХИМИЯ'
        ");

		$data = [];

		while ($row = $sql->fetch_assoc()){
			$data['chem'][$row['id']]['id'] = $row['id'];
			$data['chem'][$row['id']]['gost_id'] = $row['gost_id'];
			$data['chem'][$row['id']]['methods'] = $row['methods'];
			$data['chem'][$row['id']]['type'] = $row['type'];
			$data['chem'][$row['id']]['gost'] = $row['gost'];
			$data['chem'][$row['id']]['gost_punkt'] = $row['gost_punkt'];
		}
		return $data;
	}
	public function getPhysicsPoroshMethods()
	{
		$sql = $this->DB->query("SELECT
        bgm.id,bgm.gost_id as gost_id,bgm.methods as methods,bgm.type as type,
        bg.GOST as gost, bg.GOST_PUNKT as gost_punkt
        FROM ba_gost_methods bgm
        LEFT JOIN ba_gost bg ON bg.ID = bgm.gost_id
        WHERE type = 'ПОРОШ'
        ");

		$data = [];

		while ($row = $sql->fetch_assoc()) {
			$data['porosh'][$row['id']]['id'] = $row['id'];
			$data['porosh'][$row['id']]['gost_id'] = $row['gost_id'];
			$data['porosh'][$row['id']]['methods'] = $row['methods'];
			$data['porosh'][$row['id']]['type'] = $row['type'];
			$data['porosh'][$row['id']]['gost'] = $row['gost'];
			$data['porosh'][$row['id']]['gost_punkt'] = $row['gost_punkt'];
		}
		return $data;
	}
	public function getPhysicsObrazcMethods()
	{
		$sql = $this->DB->query("SELECT
        bgm.id,bgm.gost_id as gost_id,bgm.methods as methods,bgm.type as type,
        bg.GOST as gost, bg.GOST_PUNKT as gost_punkt
        FROM ba_gost_methods bgm
        LEFT JOIN ba_gost bg ON bg.ID = bgm.gost_id
        WHERE type = 'ОБРАЗЦ'
        ");

		$data = [];

		while ($row = $sql->fetch_assoc()) {
			$data['obrazc'][$row['id']]['id'] = $row['id'];
			$data['obrazc'][$row['id']]['gost_id'] = $row['gost_id'];
			$data['obrazc'][$row['id']]['methods'] = $row['methods'];
			$data['obrazc'][$row['id']]['type'] = $row['type'];
			$data['obrazc'][$row['id']]['gost'] = $row['gost'];
			$data['obrazc'][$row['id']]['gost_punkt'] = $row['gost_punkt'];
		}
		return $data;
	}

	// создает заявку в ulab
	public function createUlabRequest($post, $gostArr)
	{
		/** @var Request $request */
		$request = new Request();

		/** @var Material $material */
		$material = new Material();
		/** @var User $user */
		$user = new User();
		/** @var Order $order */
		$order = new Order();
		/** @var LabScheme $labScheme */
		$labScheme = new LabScheme();
		/** @var Gost $gost */
		$gost = new Gost();
        /** @var LabGost $labGost */
        $labGost = new LabGost();
		/** @var Probe $probe */
		$probe = new Probe();

		$_SESSION['request_post'] = $post;
		$schemeId = (int)$post["scheme_id"];

		$schemeItem = $labScheme->getSchemeById($schemeId);
		// $materialId = $schemeItem["material_type_id"];

		$materialId = (int)$post["material_id"];

		$materialItem = $labScheme->getMaterialById($materialId);
		$materialName = $materialItem["NAME"];

		//$gostArr = $gost->getGostBySchemeId($schemeId);

		$resetId = 1;

		$companyId = 347;
		$companyTitle = "ООО Опытный завод \"УралНИИстром\"";
		$reqType = 7;
		$type = 'ПР';
		$assignedId = 18;

		if (isset($_POST["assigned_id"])) {
			$assignedId = $_POST["assigned_id"];
		}

		$dataRequest = [
			'company_id' => $companyId,
			'type' => $reqType,
			'type_rus' => $type,
			'assigned' => $assignedId,
			// 'arrAssigned' => $arrAssigned,
		];

		$dealId = $request->create( $dataRequest );



		$dataTz = [
			'COMPANY_TITLE' => htmlspecialchars($companyTitle), //TODO: надо убрать из таблицы это поле
			'COMPANY_ID' => $companyId,
			'TYPE_ID' => $reqType,
			//  'POSIT_LEADS' => "'{$_POST['PositionGenitive']}'",
		];

		//  создать материал, если такого нет
		$arrMaterialName = [];
		$materialDataList = [];

		$arrMaterialName = [$materialName];
		$materialDataList = [
			[
				'id' => $materialId,
				'count' => 1,
				'name' => $materialName
			]
		];

		$newDeal = $request->getDealById($dealId);

		$strMaterial = implode(', ', $arrMaterialName);

		$dataTz['REQUEST_TITLE'] = $newDeal['TITLE'];
		$dataTz['MATERIAL'] = $materialName;
		// $dataTz['STAGE_ID'] = "NEW";
		$dataTz['STAGE_ID'] = "FINAL_INVOICE";
		// узнать про stage number
		//   $dataTz['probe_number'] = "'{$post['probe_number']}'";
		// $dataTz['probe_number'] = "1";

		$dataTz['TZ'] = 'a:1:{s:4:\"test\";s:2:\"pr\";}';

		$baTzId = $this->addTz($dealId, $dataTz);

		//  return $baTzId;

		//$materialToRequest = $material->setMaterialToRequest($dealId, $materialDataList);
		$materialToRequest = $labScheme->setMaterialToRequest($dealId, $materialDataList);


		$order->deleteContractFromRequest($dealId);
		// return $baTzId;
		$userList = [$assignedId];
		$resultSet = $user->setAssignedUserList($dealId, $userList);

		// обновление лабораторий в заявке
		$assigned = $user->getAssignedByDealId($dealId);

		$labaId = [];
		foreach ($assigned as $item) {
			$labaId[] = $item['department'][0];
		}

		$labaIdStr = implode(',', array_unique($labaId));



		$materialToRequestId = $materialToRequest["mtr_id"];
		$ulabMaterialToRequestId = $materialToRequest["umtr_id"];

		$probeId = $this->addProbe($materialToRequestId);

		$probeArr = [];

		$gostIdArr = [];
		$gostNewArr = [];
		$priceArr = [];
		$tzPriceSum = 0;

		foreach ($gostArr as $index => $gostItem) {
		  //  return [$gostItem["id"], $ulabMaterialToRequestId, $index + 1, $gostItem["price"]];

			$probeArr[] = $labGost->addGostToProbe($probeId, $gostItem["id"], $gostItem["price"]);
			$result = $labGost->addUlabGostToProbe($gostItem["id"], $ulabMaterialToRequestId, $index + 1, $gostItem["price"]);
			$gostIdArr[] = $gostItem["id"];
			$gostNewArr[] = "2522";
			$priceArr[] = "0";
			$tzPriceSum += $gostItem["price"];
		}



		// Тестовый метод
//        $probeArr[] = $gost->addGostToProbe($probeId, 1332);
//        $result = $gost->addUlabGostToProbe(1332, $ulabMaterialToRequestId, 6 + 1);

		$contractData = [
			"ID_DEAL" => intval($dealId),
			"ID_CONTRACT" => 1657
		];
//
		$this->insertDealsToContracts($contractData);

		$updateData = [
			'LABA_ID' => "'{$labaIdStr}'",
			'PRICE' => $tzPriceSum,
		];

		$this->updateTz($dealId, $updateData);


		//  return $materialToRequestId;
		return $baTzId;
	}

	public function addOzTz($data)
	{
		//   return $this->DB->insert("oz_tz", $data);
		$data = parent::prepareData($data);
		return parent::insert("oz_tz", $data);
		// return $data;
		//  return $this->DB->insert_id;
	}

	public function addOzTzGost($data)
	{
		return $this->DB->insert("oz_tz_gost", $data);
		//  return $this->DB->insert_id;
	}

	public function getCountTz()
	{
		$curYear = date("Y");

		$sql = "SELECT count(*) AS count
                FROM oz_tz
                WHERE YEAR(date) = YEAR(CURDATE())";

		$stmt = $this->DB->query($sql);

		return $stmt->fetch();

	}

	public function addPassportData($data)
	{
        $sqlData = $this->prepearTableData('oz_passport', $data);
		return $this->DB->insert("oz_passport", $sqlData);
	}

	public function addPassportGost($data)
	{
        $sqlData = $this->prepearTableData('oz_passport_gost', $data);
		return $this->DB->insert("oz_passport_gost", $sqlData);
	}

	public function updatePassport($id, $data)
	{
		$where = "WHERE id = {$id}";

		// return $this->DB->update('oz_passport', $data, $where);
		$sql = "UPDATE oz_passport SET batch_number = {$data["batch_number"]} {$where}";
		$this->DB->query($sql);

		return $data;
	}

	public function insertDealsToContracts($data)
	{
       // $sqlData = $this->prepearTableData('DEALS_TO_CONTRACTS', $data);
        return $this->DB->Insert('DEALS_TO_CONTRACTS', $data);
	}

	public function getList($searchElem = "")
	{
		$result = [];
		$where = "";

		if (!empty($searchElem)) {
			$where .= "m.NAME LIKE '%{$searchElem}%' AND ";
		}

		$where .= 1;

		$sql = "SELECT ot.*, m.NAME AS material_name 
                FROM oz_tz AS ot
                LEFT JOIN oz_scheme AS os ON os.id = ot.scheme_id
                LEFT JOIN MATERIALS AS m ON m.ID = os.material_id
                WHERE {$where}";

		$stmt = $this->DB->query($sql);

		while ($row = $stmt->fetch_assoc()) {
			$arr = [
				"id" => $row["id"],
				"text" => $row["title"] . "|" . $row["batch_number"] . "|" . $row["material_name"]
			];
			$result[] = $arr;
		}

		return $result;
	}

	public function getListByIdArr($idArr)
	{
		if (empty($idArr)) {
			return [];
		}

		$idStr = join(",", $idArr);

		$result = [];

		$sql = "SELECT ot.*, m.NAME AS material_name,
                    (SELECT CONCAT('[', GROUP_CONCAT(otg.value >= IFNULL(osg.range_from, -2147483648) AND otg.value <= IFNULL(osg.range_before, 2147483647) SEPARATOR ', '), ']') AS check_status 
                     FROM oz_tz_gost AS otg 
                     LEFT JOIN oz_scheme_gost AS osg ON osg.id = otg.scheme_gost_id
                     WHERE otg.oz_tz_id = ot.id AND osg.laboratory_status = 1) AS oz_suitable_status,
                    (SELECT CONCAT('[', GROUP_CONCAT((if (JSON_EXTRACT(utr_us.actual_value, '$[0]') REGEXP '[а-Я]' = 1, 2, if (JSON_SEARCH(utr_us.actual_value, 'all', '') IS NOT NULL, NULL, utr_us.average_value >= IFNULL(osg_us.range_from, -2147483648) AND utr_us.average_value <= IFNULL(osg_us.range_before, 2147483647)))) SEPARATOR ', '), ']') AS check_status_us
                     FROM ulab_material_to_request umtr_us
                     LEFT JOIN ulab_gost_to_probe ugtp_us ON ugtp_us.material_to_request_id = umtr_us.id
                     LEFT JOIN ulab_trial_results utr_us ON utr_us.gost_to_probe_id = ugtp_us.id 
                     LEFT JOIN ba_tz AS bt_us ON bt_us.ID_Z = umtr_us.deal_id 
                     LEFT JOIN oz_tz AS ot_us ON ot_us.ba_tz_id = bt_us.ID
                     LEFT JOIN oz_scheme_gost AS osg_us ON osg_us.scheme_id = ot_us.scheme_id AND osg_us.method_id = ugtp_us.new_method_id
                     WHERE bt_us.ID = ot.ba_tz_id AND osg_us.laboratory_status = 0) AS ulab_suitable_status
                FROM oz_tz AS ot
                LEFT JOIN oz_scheme AS os ON os.id = ot.scheme_id
                LEFT JOIN MATERIALS AS m ON m.ID = os.material_id
                WHERE ot.id IN ({$idStr})";

		$stmt = $this->DB->query($sql);

		$tempArr = [];

		while ($row = $stmt->fetch_assoc()) {
			$row["ulab_status"] = $this->getStatus(json_decode($row["ulab_suitable_status"]));
			$row["oz_status"] = $this->getOzStatus(json_decode($row["oz_suitable_status"]));
			$row["status"] = $this->getStatus([$row["ulab_status"], $row["oz_status"]]);

			if (is_null($row["status"])) {
				$row["bg"] = "text-secondary";
			}
			if ($row["status"] === 0) {
				$row["bg"] = "text-danger";
			}
			if ($row["status"] === 1) {
				$row["bg"] = "text-success";
			}
			if ($row["status"] === 2) {
				$row["bg"] = "color-dark-yellow";
			}

			$tempArr[$row["id"]] = $row;
		}

		foreach ($idArr as $id) {
			$result[] = $tempArr[$id];
		}

		return $result;
	}

	public function getStatus($gostArr)
	{
		if (empty($gostArr)) {
			return -1;
		}
		if (in_array(null, $gostArr, true)) {
			return null;
		}
		if (in_array(0, $gostArr, true)) {
			return 0;
		}
		if (in_array(2, $gostArr, true)) {
			return 2;
		}

		return 1;
	}

	public function getOzStatus($gostArr)
	{
		if (empty($gostArr)) {
			return -1;
		}
		if (in_array(null, $gostArr, true)) {
			return null;
		}
		if (in_array(false, $gostArr)) {
			return 0;
		}

		return 1;
	}

	public function updateBaTz($data, $id)
	{
		$where = "WHERE ID = {$id}";
		$data = parent::prepareData($data);
		return parent::update("ba_tz", $data, $where);
	}

	public function updateData($data, $id)
	{
		$where = "WHERE id = {$id}";
		$data = parent::prepareData($data);
		return parent::update("oz_tz", $data, $where);
	}

	public function getListByScheme($schemeId)
	{
		$sql = "SELECT *, date AS created_date
                FROM oz_tz
                WHERE scheme_id = {$schemeId}";

		$data = [];

		$stmt = $this->DB->query($sql);

		while ($row = $stmt->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}

	public function getGostValuesBySchemeId($schemeId)
	{
		$sql = "SELECT otg.*, ot.date AS created_date
                FROM oz_tz_gost AS otg
                LEFT JOIN oz_tz AS ot ON ot.id = otg.oz_tz_id
                WHERE ot.scheme_id = {$schemeId}";

		$data = [];

		$stmt = $this->DB->query($sql);

		while ($row = $stmt->fetch_assoc()) {
			$data[] = $row;
		}

		return $data;
	}

	public function getUlabGostValuesBySchemeId($schemeId)
	{
		$sql = "SELECT ot.id As tz_id, sg.id AS scheme_gost_id, umtr.id umtr_id, umtr.deal_id, umtr.probe_number, umtr.cipher, umtr.protocol_id, 
                       m.ID m_id, m.NAME m_mame, 
                       ugtp.id ugtp_id, ugtp.method_id, ugtp.conditions_id, ugtp.gost_number, ugtp.actual_value AS actual_value_new, 
                       ug.reg_doc bgm_gost, um.name bgm_specification, bgm.ED bgm_ed, um.clause GOST_PUNKT,
                       utr.match, utr.actual_value, utr.normative_value, utr.average_value, p.NUMBER p_number,
                       DATE_FORMAT(ba.DATE_SOZD, '%d.%m.%Y') AS date,
                       ud.unit_rus AS unit_char, 
                       ot.scheme_id, sg.range_from, sg.range_before,
                       JSON_EXTRACT(utr.actual_value, '$[0]') AS actual_value_text, JSON_EXTRACT(utr.actual_value, '$[0]') REGEXP '[а-Я]' AS actual_value_type 
                FROM ulab_material_to_request umtr 
                LEFT JOIN MATERIALS m ON m.ID = umtr.material_id 
                LEFT JOIN ulab_gost_to_probe ugtp ON ugtp.material_to_request_id = umtr.id 
                LEFT JOIN ba_gost bgm ON bgm.ID = ugtp.method_id
                LEFT JOIN ulab_trial_results utr ON utr.gost_to_probe_id = ugtp.id 
                LEFT JOIN PROTOCOLS p ON p.ID = umtr.protocol_id 
                LEFT JOIN ba_tz ba ON ID_Z = umtr.deal_id 
                LEFT JOIN oz_tz ot ON ot.ba_tz_id = ba.ID
                
                LEFT JOIN ulab_methods um ON um.id = ugtp.new_method_id
                LEFT JOIN ulab_dimension ud ON ud.id = um.unit_id 
                LEFT JOIN ulab_gost ug ON ug.id = um.gost_id  
                LEFT JOIN oz_scheme_gost sg ON sg.scheme_id = ot.scheme_id AND sg.method_id = um.id
                WHERE ot.scheme_id = {$schemeId} GROUP BY ugtp_id";

		$data = [];

		$stmt = $this->DB->query($sql);

		while ($row = $stmt->fetch_assoc()) {

			if (intval($row["deal_id"]) > 10752) {
				$row["actual_value"] = $row["actual_value_new"];
			} else {
				if ($row["actual_value"] == "[\"\"]") {
					$row["actual_value"] = NULL;
				}

				$row["actual_value"] = str_replace(['"', '[', ']'], '', $row["actual_value"]);
				$row["actual_value"]= str_replace([','], '.', $row["actual_value"]);
			}

			$data[] = $row;
		}

		return $data;
	}

	public function addProbe($materialToRequestId)
    {
        $sql = "INSERT INTO probe_to_materials (material_request_id)
                VALUES ({$materialToRequestId})";

        $this->DB->query($sql);

        return $this->DB->LastID();
    }

}
