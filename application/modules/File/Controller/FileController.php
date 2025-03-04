<?php

/**
 * @desc Работа с файлами
 * Class FileController
 */
class FileController extends Controller
{
    /**
     * @desc Сохраняет полученный двоичный файл на сервере по указанному пути
     */
    public function bytePdfToServerAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $byteFile = $_POST["file"];
        $path = $_POST["path"];

        $file = $this->model("Secondment");

        $file->byteFileToServer($byteFile, $path);

        echo json_encode(true);
    }

}