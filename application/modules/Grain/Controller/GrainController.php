<?php
/**
 * @desc Справочник сит зерновых составов
 * Class GrainController
 */

class GrainController extends Controller
{

    /**
     * Страница зернового состава
     */
    public function card($grainListID): void
    {
        $grainListID = (int)$grainListID;

         if ($grainListID <= 0) {
             $this->redirect('/grain/list/');
         }

        /** @var Grain $grain*/
        $grain = $this->model('Grain');

        $this->data['title'] = "Зерновой состав";

        $this->data['grain_list_id'] = $grainListID;
        $this->data['grain_list_gost'] = $grain->getGrainGostList($grainListID);
        $this->data['grain_seave_size'] = $grain->getGrainSeaveSize();
        $this->data['grain'] = $grain->getGrainSeaveValues($grainListID);

        $this->addCSS("/assets/plugins/select2/dist/css/select2.min.css");
        $this->addCSS("/assets/plugins/select2/dist/css/select2-bootstrap-5-theme.min.css");

        $this->addJs('/assets/plugins/select2/dist/js/select2.min.js');

        $this->addJs('/assets/js/grain/grain.js');
        $this->view('card');
    }

    /**
     * Сохраняет данные зернового состава
     */
    public function updateGrainList(int $grainListID): void
    {
        /** @var Grain $grain*/
        $grain = $this->model('Grain');

        $grain->update((int)$grainListID, $_POST);

        $this->redirect('/grain/card/' . $grainListID);
    }

    /**
     * @desc Страница справочника сит зерновых составов
     */
    public function list(): void
    {
        $this->data['title'] = 'Журнал зерновых составов';

        $this->addJs("/assets/js/grain/grain-list.js");

        $this->view('list', '', 'template_journal');
    }

    /**
     * @desc Получает данные для справочника сит зерновых составов
     */
    public function getListProcessingAjax(): void
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var Grain $grain */
        $grain = $this->model('Grain');

        $filter = $grain->prepareFilter($_POST ?? []);

        $data = $grain->getDataToJournalGrain($filter);

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
     * Добавляет новый зерновой состав
     */
    public function addZern(): void
    {
        /** @var Grain $grain */
        $grain = $this->model('Grain');

        $name = $_POST['name']??'';

        $validName = $this->validateField($name, 'Название', true);
        if ( !$validName['success'] ) {
            $this->showErrorMessage($validName['error']);
            $this->redirect('/grain/list/');
        }

        $newId = $grain->addZern($name);

        $this->redirect("/ulab/grain/card/{$newId}");
    }
}