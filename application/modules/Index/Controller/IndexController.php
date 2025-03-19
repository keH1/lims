<?php

use Bitrix\Main\Loader;

/**
 * @desc Страница по-умолчанию
 * Class IndexController
 */
class IndexController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }

    /**
     * @desc phpinfo
     */
    public function info()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        phpinfo();
    }


    /**
     * @desc Устанавливает статус успешно завершено, если заявка старая, по ней все сделано, но статус не сменен
     */
    public function setStatusWON()
    {
        return;
        global $DB;

        $requestModel = new Request();

        $reqSql = $DB->Query("
                        select distinct tz.REQUEST_TITLE, tz.ID, tz.ID_Z, tz.OPLATA, tz.PRICE, year(tz.DATE_CREATE_TIMESTAMP), tz.STAGE_ID 
                        from ba_tz tz, AKT_VR act
                        where 
                          tz.OPLATA = tz.PRICE 
                          and (tz.STAGE_ID = 2 or tz.STAGE_ID = 4) 
                          and (year(tz.DATE_CREATE_TIMESTAMP) = 2020 or year(tz.DATE_CREATE_TIMESTAMP) = 2021)
                          and tz.ID = act.TZ_ID
                          and (act.NUMBER is not null and act.NUMBER not like 'Ждем ответ от 1С')
                        order by
                          year(tz.DATE_CREATE_TIMESTAMP), tz.ID_Z
                        ");

        $i = 0;
        while ($row = $reqSql->Fetch()) {
            $requestModel->updateStageDeal($row['ID_Z'], 'WON', 6);

            $i++;
        }
        echo 'OK ';
        echo $i;
    }


    /**
     * @desc Устанавливает статус заявки ПРОИГРАНА если она старая и по ней ничего не происходит
     */
    public function setStatusLOSE()
    {
        return;
        global $DB;

        $requestModel = new Request();

        $reqSql = $DB->Query("
                        select distinct tz.REQUEST_TITLE, tz.ID, tz.ID_Z, tz.OPLATA, tz.PRICE, year(tz.DATE_CREATE_TIMESTAMP), tz.STAGE_ID 
                        from ba_tz tz
                        where 
                          tz.STAGE_ID IN ('NEW', 'PREPARATION', 'PREPAYMENT_INVOICE', 'EXECUTING') 
                          and (year(tz.DATE_CREATE_TIMESTAMP) = 2020 or year(tz.DATE_CREATE_TIMESTAMP) = 2021)
                          AND IF(tz.ACT_NUM, TRUE, FALSE) = FALSE
                        order by
                          year(tz.DATE_CREATE_TIMESTAMP), tz.ID_Z
                        ");

        $i = 0;
        while ($row = $reqSql->Fetch()) {
            $requestModel->updateStageDeal($row['ID_Z'], 'LOSE', 7);

            $i++;
        }
        echo 'OK ';
        echo $i;
    }

    /**
     * @desc Обновляет данные ГОСТов и методик в старых таблицах из новых таблиц
     */
    public function updateGostNewToOld($methodId = '')
    {
        return;
        global $DB;

        $where = "1";
        if ($methodId > 0) {
            $where = "um.id = {$methodId}";
        }

        $methodModel = new Methods();

        $reqSql = $DB->Query("
            select 
                   ug.reg_doc, ug.year, ug.description, ug.code_eaes, ug.code_okpd2, ug.materials,
                   um.*,
                   ud.unit_rus, ud.name unit_name
            from ulab_gost as ug
            inner join ulab_methods as um on um.gost_id = ug.id
            inner join ulab_dimension as ud on ud.id = um.unit_id
            where 
                  um.in_field = 1 and um.is_confirm = 1 and um.id in (select ulab_method_id from ba_gost where ulab_method_id > 0) and {$where}
        ");

        $i = 0;
        while ($row = $reqSql->Fetch()) {
            $baGost = $DB->Query("select * from ba_gost where ulab_method_id = {$row['id']}")->Fetch();

            if (empty($baGost)) {
                continue;
            }

            $laba = $methodModel->getLab($row['id']);
            $room = $methodModel->getRoom($row['id']);
            $ass = $methodModel->getAssigned($row['id']);
            $oborud = $methodModel->getOborud($row['id']);
            $infelicity = $methodModel->getUncertainty($row['id']);

            $data = [
                'GOST' => "'{$row['reg_doc']}'",
                'SPECIFICATION' => "'{$row['name']}'",
                'GOST_PUNKT' => "'{$row['clause']}'",
                'NAME_GOST' => "'{$row['description']}'",
                'NORM1' => "'{$row['definition_range_1']}'",
                'NORM2' => "'{$row['definition_range_2']}'",
                'MATCH_MANUAL' => 0,
                'NORM_TEXT' => $row['is_text_norm'],
                'ED' => "'{$row['unit_rus']}'",
                'PRICE' => "'{$row['price']}'",
                'IN_OA' => 1,
                'GOST_TYPE' => "'metodic'",
                'TNVED' => "'{$row['code_eaes']}'",
                'GOST_OBJECT' => "'{$row['materials']}'",
                'RES_TEXT' => $row['is_text_fact'],
                'GOST_YEAR' => $row['year'],
                'NUM_OA' => $row['num_oa'],
                'NUM_OA_NEW' => $row['num_oa'],
                'ACCURACY' => $row['decimal_places'],
                'OKPD' => "'{$row['code_okpd2']}'",
                'NON_ACTUAL' => 0,
                'PRESSURE' => $row['is_not_cond_pressure'],
                'HUMIDITY' => $row['is_not_cond_wet'],
                'TEMPERATURE' => $row['is_not_cond_temp'],
                'test_method_id' => $row['test_method_id'],
                'ulab_method_id' => $row['id'],
                'CHECKED' => $row['is_confirm'],
            ];

            if ( $row['definition_range_type'] == 1 ) {
                $data['IN_OUT'] = 0;
            }
            if ( $row['definition_range_type'] == 2 ) {
                $data['IN_OUT'] = 1;
            }
            if ( $row['definition_range_type'] == 3 ) {
                $data['UNNORM'] = 1;
            }

            $unserPlace = unserialize($baGost['PLACE']);
            foreach ($room as $item) {
                if (!in_array($item, $unserPlace)) {
                    $unserPlace[] = $item;
                }
            }
            $place = serialize($unserPlace);

            $press = serialize([$row['cond_pressure_1'], $row['cond_pressure_2']]);
            $temp = serialize([$row['cond_temp_1'], $row['cond_temp_2']]);
            $vlag = serialize([$row['cond_wet_1'], $row['cond_wet_2']]);

            $unserAssign = unserialize($baGost['ASSIGNED']);
            foreach ($ass as $item) {
                if (!in_array($item, $unserAssign)) {
                    $unserAssign[] = $item;
                }
            }
            $assign = serialize($unserAssign);

            $data['PLACE'] = "'{$place}'";
            $data['ASSIGNED'] = "'{$assign}'";
            $data['VLAG'] = "'{$vlag}'";
            $data['TEMP'] = "'{$temp}'";
            $data['PRESS'] = "'{$press}'";

            foreach ($laba as $lab) {
                if ( $lab == 1 ) {
                    $data['LFHI'] = 1;
                } else {
                    $data['LFHI'] = 0;
                }
                if ( $lab == 3 ) {
                    $data['LFMI'] = 1;
                } else {
                    $data['LFMI'] = 0;
                }
                if ( $lab == 4 ) {
                    $data['DSL'] = 1;
                } else {
                    $data['DSL'] = 0;
                }
                if ( $lab == 2 ) {
                    $data['LSM'] = 1;
                } else {
                    $data['LSM'] = 0;
                }
                if ( $lab == 5 ) {
                    $data['OSK'] = 1;
                } else {
                    $data['OSK'] = 0;
                }
            }

            $DB->Update('ba_gost', $data, "where ID = {$baGost['ID']}");
//            $gostId = $baGost['ID'];
//
//            foreach ($infelicity as $item) {
//                $dataItem = [
//                    'gost_id' => $gostId,
//                    'diapason_from' => $item['uncertainty_1'],
//                    'diapason_to' => $item['uncertainty_2'],
//                    'infelicity' => $item['uncertainty_3'],
//                ];
//
//                $DB->Insert('infelicity', $dataItem);
//            }
//
//            foreach ($oborud as $item) {
//                $dataItem = [
//                    'GOST_ID' => $gostId,
//                    'OBORUD_ID' => $item['id_oborud'],
//                    'COMMENT' => "'{$item['comment']}'",
//                    'EQUIP_GOST' => "'{$item['gost']}'",
//                ];
//
//                $DB->Insert('ba_connection', $dataItem);
//            }

            $i++;
        }

        echo 'OK ' . $i;
    }

    /**
     * @desc Клонирует данные id методик и ТУ в новые поля для всех проб
     */
    public function ulGostToProbe()
    {
        return;
        global $DB;

        $sql = $DB->Query("select * from ulab_gost_to_probe");

        $i = 0;
        while ($row = $sql->Fetch()) {
            $idMethod = $DB->Query("select ulab_method_id from ba_gost where ID = {$row['method_id']}")->Fetch();

            if ( empty($idMethod['ulab_method_id']) ) {
                continue;
            }

            $DB->Update('ulab_gost_to_probe', ['new_method_id' => $idMethod['ulab_method_id']], "where id = {$row['id']}");

            ///////////////////////////////////////////////////////////////////////////////

            $cond = $DB->Query("select id from ulab_tech_condition where ba_gost_id = {$row['conditions_id']}")->Fetch();

            if ( empty($cond['id']) ) {
                continue;
            }

            $DB->Update('ulab_gost_to_probe', ['tech_condition_id' => $cond['id']], "where conditions_id = {$row['conditions_id']}");


            $i++;
//
//            echo $idMethod['ulab_method_id'];
//            echo '<br>';
        }
        echo 'OK ' . $i;
    }

    /**
     * @desc Обновляет пункт документа ТУ в новой таблице из старой таблицы
     */
    public function updateClauseTu()
    {
        global $DB;

        $sql = $DB->Query("select * from ulab_tech_condition");

        $i = 0;
        while ($row = $sql->Fetch()) {
            if ( empty($row['ba_gost_id']) ) {
                continue;
            }
            $gost = $DB->Query("select GOST_PUNKT from ba_gost where ID = {$row['ba_gost_id']}")->Fetch();

            if ( !empty($gost['GOST_PUNKT']) ) {
                $i++;
                $DB->Update('ulab_tech_condition', ['clause' => "'{$gost['GOST_PUNKT']}'"], "where id = {$row['id']}");
            }
        }

        echo 'OK ' . $i;
    }

    /**
     * @desc Сохраняет данные ГОСТов из старых таблиц в новые
     */
	public function gostOldToNew($idOld)
	{
		global $DB;

		/** @var Methods $methodsModel */
		$methodsModel = $this->model('Methods');
		/** @var Gost $gostModel */
		$gostModel = $this->model('Gost');

		$resultAddMethod = 0;

		$sql = $DB->Query("SELECT * FROM ba_gost WHERE ID = {$idOld}");

		while ($row = $sql->Fetch()) {

			$gostName = trim($row['GOST']);
			$newGost = $DB->Query("select id from ulab_gost where reg_doc like '{$gostName}'")->Fetch();

			if ( empty($newGost['id']) ) {
				$dataGost = [
					'reg_doc' => trim($row['GOST']),
					'year' => $row['GOST_YEAR'],
					'description' => $row['NAME_GOST'],
					'code_eaes' => $row['TNVED'],
					'code_okpd2' => $row['OKPD'],
					'materials' => $row['GOST_OBJECT'],
				];
				$gostId = $gostModel->addGost($dataGost);
			} else {
				$gostId = $newGost['id'];
			}

			$ed = $DB->ForSql(trim($row['ED']));
			$unit = $DB->Query("SELECT id FROM ulab_dimension WHERE unit_rus = '{$ed}'")->Fetch();

			$temp = unserialize($row['TEMP']);
			$wet = unserialize($row['VLAG']);
			$pressure = unserialize($row['PRESS']);

			$rangeType = 1;
			if ( $row['UNNORM'] == 1 ) {
				$rangeType = 3;
			} else if ($row['IN_OUT']) {
				$rangeType = 2;
			}

			$data = [
				'gost_id' => $gostId,
				'unit_id' => $unit['id'] ?? 767,
				'name' => $row['SPECIFICATION'],
				'clause' => $row['GOST_PUNKT'],
				'decimal_places' => $row['ACCURACY'],
				'in_field' => $row['IN_OA'],
				'definition_range_1' => $row['NORM1'],
				'definition_range_2' => $row['NORM2'],
				'definition_range_type' => $rangeType,
				'cond_temp_1' => $temp[0],
				'cond_temp_2' => $temp[1],
				'is_not_cond_temp' => $row['TEMPERATURE'],
				'cond_wet_1' => $wet[0],
				'cond_wet_2' => $wet[1],
				'is_not_cond_wet' => $row['HUMIDITY'],
				'cond_pressure_1' => $pressure[0],
				'cond_pressure_2' => $pressure[1],
				'is_not_cond_pressure' => $row['PRESSURE'],
				'is_text_norm' => $row['NORM_TEXT'],
				'is_text_fact' => $row['RES_TEXT'],
				'price' => number_format($row['PRICE'], 2, '.', ''),
			];

			$resultAddMethod = $methodsModel->add($data);

			if ( !empty($resultAddMethod) ) {

				// laba
				$labIds = [];
				if ( $row['LFHI'] ) {
					$labIds[] = 1;
				}
				if ( $row['LFMI'] ) {
					$labIds[] = 3;
				}
				if ( $row['DSL'] ) {
					$labIds[] = 4;
				}
				if ( $row['LSM'] ) {
					$labIds[] = 2;
				}
				if ( $row['OSK'] ) {
					$labIds[] = 5;
				}

				$methodsModel->updateLab($resultAddMethod, $labIds);
				// \ laba

				// rooms
				$rooms = unserialize($row['PLACE']);

				$methodsModel->updateRoom($resultAddMethod, $rooms);
				// \ rooms

				// ass
				$ass = unserialize($row['ASSIGNED']);

				$methodsModel->updateAssigned($resultAddMethod, $ass);
				// \ ass

				// oborud
				$sqlOborud = $DB->Query("SELECT * FROM ba_connection WHERE GOST_ID = {$row['ID']}");

				$dataOborud = [];
				while ($rowOborud = $sqlOborud->Fetch()) {
					$dataOborud[] = [
						'gost' => $rowOborud['EQUIP_GOST']?? '-',
						'id_oborud' => $rowOborud['OBORUD_ID'],
						'comment' => $rowOborud['COMMENT'],
					];
				}

				$methodsModel->updateOborud($resultAddMethod, $dataOborud);
				// \ oborud

				// Расчет неопределенности
				$sqlInfelicity = $DB->Query("SELECT * FROM infelicity WHERE gost_id = {$row['ID']}");
				$dataInfelicity = [];
				while ($rowInfelicity = $sqlInfelicity->Fetch()) {
					$dataInfelicity[] = [
						'uncertainty_1' => $rowInfelicity['diapason_from']?? '-',
						'uncertainty_2' => $rowInfelicity['diapason_to'],
						'uncertainty_3' => $rowInfelicity['infelicity'],
					];
				}

				$methodsModel->updateUncertainty($resultAddMethod, $dataInfelicity);
				// \ Расчет неопределенности
			}
		}

		if ( !empty($resultAddMethod) ) {
			$this->showSuccessMessage('Успех');
			$this->redirect('/ulab/gost/method/' . $resultAddMethod);
		}
	}


    /**
     * @desc Перекинуть из ба_гост в ТУ по ид
     * @param $baGostId - ид из старой
     */
    public function tuOldToNew($baGostId)
    {
        global $DB;

        /** @var TechCondition $tuModel */
        $tuModel = $this->model('TechCondition');

        $resultAddMethod = 0;

        $sql = $DB->Query("SELECT * FROM ba_gost WHERE ID = {$baGostId}");

        while ($row = $sql->Fetch()) {

            $gostName = trim($row['GOST']);

            $ed = $DB->ForSql(trim($row['ED']));
            $unit = $DB->Query("SELECT id FROM ulab_dimension WHERE unit_rus = '{$ed}'")->Fetch();

            $rangeType = 1;
            if ( $row['UNNORM'] == 1 ) {
                $rangeType = 3;
            } else if ($row['IN_OUT']) {
                $rangeType = 2;
            }

            $data = [
                'reg_doc' => $gostName,
                'year' => $row['GOST_YEAR'],
                'clause' => $row['GOST_PUNKT'],
                'unit_id' => $unit['id'] ?? 767,
                'measured_properties_name' => $row['SPECIFICATION'],
                'name' => $row['NAME_GOST'],
                'type' => $row['GOST_TYPE'],
                'decimal_places' => $row['ACCURACY'],
                'definition_range_1' => $row['NORM1'],
                'definition_range_2' => $row['NORM2'],
                'definition_range_type' => $rangeType,
                'is_text_norm' => $row['NORM_TEXT'],
                'is_manual' => $row['MATCH_MANUAL'],
                'norm_comment' => $row['NORM_COMMENT'],
                'ba_gost_id' => $row['ID'],
                'dop_material' => $row['DOP'],
                'dop_value' => $row['VALUE_DOP'],
                'dop_norm' => $row['NORM_DOP'],
            ];

            $tuModel->add($data);

            $resultAddMethod++;
        }

        echo 'OK ' . $resultAddMethod;
    }

//    public function scype()
//	{
//	    return;
//	    global $DB;
//		$res = $DB->Query("select gtp.*, bg.ulab_method_id from gost_to_probe as gtp
//					left join probe_to_materials ptm on ptm.id = gtp.probe_id
//					left join MATERIALS_TO_REQUESTS mtr on mtr.id = ptm.material_request_id
//					left join ba_gost bg ON gtp.gost_method = bg.ID
//					where mtr.ID_DEAL = 9393");
//
//		while ($row = $res->Fetch()) {
//			$DB->Query("UPDATE gost_to_probe SET new_method_id = {$row['ulab_method_id']} where id = {$row['id']}");
//
//		}
//	}

    /**
     * @desc Сохраняет привязку протокола к лаборатории для всех протоколов
     */
	public function setDepartmentProtocol()
    {
        return;
        global $DB;

        /** @var User $userModel */
        $userModel = $this->model('User');

        $sql = $DB->Query("select id, VERIFY from PROTOCOLS");

        while ($row = $sql->Fetch()) {
            $users = unserialize($row['VERIFY']);
            $dep = [];

            if ( empty($users) ) {
                continue;
            }

            foreach ($users as $user) {
                $data = [
                    'protocol_id' => $row['id'],
                    'department_id' => $userModel->getDepartmentByUserId($user)
                ];

                $DB->Insert('protocol_lab', $data);
            }
        }

        echo 'OK';
    }

//    public function getMethodToGost()
//	{
//		global $DB;
//		/** @var Methods $methodsModel */
//		$methodsModel = $this->model('Methods');
//
//		$methods = $methodsModel->getListByGostId(346);
//		$mId = [];
//		foreach ($methods as $m) {
//			$mId[] = [$m['id'], $m['name']];
//
//			$data = ['method_id' => $m['id']];
////			$DB->Insert('ulab_method_tu', $data);
//		}
//		$methodsModel->pre($mId, false);
//
//	}

    public function showPermission()
    {
        $this->data['title'] = 'Роли и доступы';

        /** @var  Permission $permissionModel */
        $permissionModel = $this->model('Permission');

        $this->data['controller_method_list'] = $permissionModel->getControllerMethod();
//        $permissionModel->pre($this->data['controller_method_list']);
        $this->data['permission_list'] = $permissionModel->getPermission();

        $this->view('perm');
    }
}
