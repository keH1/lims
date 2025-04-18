<?php

/**
 * @desc Контроль воды
 * Class WaterController
 */
class WaterController extends Controller
{
    private string $nameModel = 'Water';

    /**
     * @desc Журнал контроля дистиллированной воды
     */
    public function list()
    {
        $this->data['title'] = 'Журнал контроля дистиллированной воды';

        $this->addJs("/assets/js/water-journal.js");

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Получает данные для «Журнала контроля дистиллированной воды»
     */
    public function getListProcessingAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        $usedModel = $this->model($this->nameModel);

        $filter = $usedModel->postToFilter($_POST ?? []);

        $data = $usedModel->getList($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @desc Добавляет измерения
     */
    public function addAnalysis()
    {
        $successMsg = 'Анализ успешно добавлен';
        $unsuccessfulMsg = 'Не удалось добавить анализ';
        $usedModel = $this->model($this->nameModel);

        $newAdd = $_POST['toSQL'];

        foreach ($newAdd['water'] as $key => $item) {
            if (empty($item)) {
                unset($newAdd['water'][$key]);
            }
        }

        $isAdd = false;
        if ( !empty($newAdd) ) {
            $isAdd = $usedModel->addToSQL($newAdd,'addAnalysis');
        }

        $this->checkAndShowSuccessOrErrorMessage($isAdd, $successMsg, $unsuccessfulMsg);

        $this->redirect($usedModel->getLocation());
    }


    /**
     * @desc Автозаполнение «Журнала контроля дистиллированной воды»
     */
    public function autoFill()
    {
        /** @var Water $waterModel */
        $waterModel = $this->model('Water');

        if ( empty($_POST['formAutoFill']['dateFrom']) || empty($_POST['formAutoFill']['dateTo']) ) {
            $this->showErrorMessage("Дата начала и дата конца не должны быть пустыми");
            $this->redirect("/water/list/");
        }

        $result = $waterModel->autoFill($_POST['formAutoFill']['dateFrom'], $_POST['formAutoFill']['dateTo']);

        $this->showSuccessMessage('Данные условий обновлены успешно. Было добавлено: ' . $result);
        $this->redirect("/water/list/");
    }
}
