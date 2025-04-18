<?php

/**
 * @desc Журнал счетов
 * Class InvoiceController
 */
class InvoiceController extends Controller
{
    /**
     * @desc Перенаправляет пользователя на страницу «Формирование заявки на испытания»
     */
    public function index()
    {
        $this->redirect('/request/new/');
    }

    /**
     * route /probe/list/
     * @desc Журнал счетов
     */
    public function list()
    {
        $this->data['title'] = 'Журнал счетов';

        /** @var Lab $lab */
        $lab = $this->model('Lab');
        /** @var Request $request */
        $request = $this->model('Request');

        $this->data['date_start'] = $request->getDateStart();

        $this->data['lab'] = $lab->getList();

        $r = rand();
        $this->addJs("/assets/js/invoice-list.js?v={$r}");

        $this->view('list', '', 'template_journal');
    }


    /**
     * @desc Получает данные для журнала счетов
     */
    public function getListAjax()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();

        /** @var  Request $request*/
        $request = $this->model('Request');

        $filter = $request->prepareFilter($_POST ?? []);

        $data = $request->getDatatoJournalInvoice($filter);

        $recordsTotal = $data['recordsTotal'];
        $recordsFiltered = $data['recordsFiltered'];
        $error = $data['error'];

        unset($data['recordsTotal']);
        unset($data['recordsFiltered']);
        unset($data['error']);

        $jsonData = [
            "draw" => (int)$_POST['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            'error' => $error,
            "data" => $data,
        ];

        echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
    }
}