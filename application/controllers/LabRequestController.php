<?php

/**
 * @desc Контроллер лабораторных запросов
 * Class LabRequestController
 */
class LabRequestController extends Controller
{
//    public function getRegistrationData()
//    {
//        header("Access-Control-Allow-Origin: " . self::ALLOWED_DOMAIN);
//        header("Content-Type: application/json; charset=UTF-8");
//        header("Access-Control-Allow-Methods: POST");
//        header("Access-Control-Max-Age: 3600");
//        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
//
//        Model::checkAccess();
//
//        $gost = $this->model('Gost');
//        $material = $this->model('Material');
//        $request = $this->model('Request');
//
//        $type = "";
//
//        if (isset($_POST["type"])) {
//            $type = $_POST["type"];
//        }
//
//        $data["gostList"] = $gost->getList();
//        $data["materials"] = $material->getOzMaterials($type);
//        $data["count"] = $request->getCountTz();
//
//        echo json_encode($data, JSON_UNESCAPED_UNICODE);
//    }
}