<?php
/**
 * Модель для работы с материалами
 * Class LabScheme
 */
class LabRequest extends Model
{

    public function getCountTz()
    {
        $curYear = date("Y");

        $sql = "SELECT count(*) AS count
                FROM oz_tz
                WHERE YEAR(date) = YEAR(CURDATE())";

        $stmt = $this->DB->query($sql);

        return $stmt->fetch();

    }

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
        /** @var LabScheme $scheme */
        $scheme = new LabScheme();
        /** @var Gost $gost */
        $gost = new Gost();
        /** @var Probe $probe */
        $probe = new Probe();

        $_SESSION['request_post'] = $post;
        $schemeId = $post["scheme_id"];

        $schemeItem = $scheme->getSchemeById($schemeId);
        // $materialId = $schemeItem["material_type_id"];

        $materialId = $post["material_id"];

        $materialItem = $scheme->getMaterialById($materialId);
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

        $materialToRequest = $material->setMaterialToRequest($dealId, $materialDataList);


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
            $probeArr[] = $gost->addGostToProbe($probeId, $gostItem["id"], $gostItem["price"]);
            $result = $gost->addUlabGostToProbe($gostItem["id"], $ulabMaterialToRequestId, $index + 1, $gostItem["price"]);
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

        $request->insertDealsToContracts($contractData);

        $updateData = [
            'LABA_ID' => "'{$labaIdStr}'",
            'PRICE' => $tzPriceSum,
        ];

        $this->updateTz($dealId, $updateData);


        //  return $materialToRequestId;
        return $baTzId;
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

    public function addProbe($materialToRequestId)
    {
        $sql = "INSERT INTO probe_to_materials (material_request_id)
                VALUES ({$materialToRequestId})";

        $this->DB->query($sql);

        return $this->DB->LastID();
    }
}
