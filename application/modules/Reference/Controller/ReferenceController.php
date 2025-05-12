<?php

/**
 * reference books
 * @desc Для работы со справочниками
 * Class ReferenceController
 */
class ReferenceController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }

    /**
     * @desc Журнал определяемой хар-ки / показателя
     */
    public function measuredPropertiesList($methodId = '')
    {
        $this->data['title'] = 'Журнал определяемой хар-ки / показателя';

        $this->data['method_id'] = $methodId;

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");

        $this->addJs('/assets/js/reference/measured-properties-list.js?v=' . rand());

        $this->view('measured_properties_list');
    }


    /**
     * @desc Журнал единиц измерения
     */
    public function unitList($methodId = '')
    {
        $this->data['title'] = 'Журнал единиц измерения';

        $this->data['method_id'] = $methodId;

        $this->addCSS("/assets/plugins/DataTables/datatables.min.css");
        $this->addJS("/assets/plugins/DataTables/DataTables-1.11.3/js/jquery.dataTables.min.js");
        $this->addJS("/assets/plugins/DataTables/dataRender/ellipsis.js");

        $this->addJs('/assets/js/reference/unit-list.js?v=' . rand());

        $this->view('unit_list');
    }


    /**
     * @desc Получает данные для журнала показателей через аякс
     */
    public function getDataMeasuredPropertiesListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Reference $referenceModel */
        $referenceModel = $this->model('Reference');

        $filter = $referenceModel->prepareFilter($_POST ?? []);

        $data = $referenceModel->getDataToJournalMeasuredProperties($filter);

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
     * @desc Получает данные для журнала ед. измерений через аякс
     */
    public function getDataUnitListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Reference $referenceModel */
        $referenceModel = $this->model('Reference');

        $filter = $referenceModel->prepareFilter($_POST ?? []);

        $data = $referenceModel->getDataToJournalUnits($filter);

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
     * @desc Меняет статус Определяемой характеристики
     */
    public function changeUsedMeasuredPropertiesAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Reference $referenceModel */
        $referenceModel = $this->model('Reference');

        $referenceModel->changeUsedMeasuredProperties((int) $_POST['id']);
    }


    /**
     * @desc Меняет статус единиц измерения
     */
    public function changeUsedUnitsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Reference $referenceModel */
        $referenceModel = $this->model('Reference');

        $referenceModel->changeUsedUnits((int) $_POST['id']);
    }


    /**
     * @desc Синхронизирует данный с фса
     */
    public function syncMeasuredPropertiesAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Reference $referenceModel */
        $referenceModel = $this->model('Reference');

        $result = $referenceModel->syncMeasuredProperties();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    /**
     * @desc Синхронизирует данный с фса
     */
    public function syncUnitsAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var Reference $referenceModel */
        $referenceModel = $this->model('Reference');

        $result = $referenceModel->syncUnits();

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}