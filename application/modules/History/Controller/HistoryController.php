<?php
/**
 * @desc Журнал истории изменений
 * Class HistoryController
 */

class HistoryController extends Controller
{

    /**
     * @desc Страница журнала истории
     */
    public function list()
    {
        $this->data['title'] = 'История изменений';

        $this->addJs("/assets/js/history-list.js");

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Получает данные для журнала истории
     */
    public function getListProcessingAjax(): void
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var History $history */
        $history = $this->model('History');

        $filter = $history->prepareFilter($_POST ?? []);
        $data = $history->getDataToJournalHistory($filter);

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
}