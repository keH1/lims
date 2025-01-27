<?php
/**
 * @desc Распределение оборудования
 * Class AssignmentController
 */
 class AssignmentController extends Controller
 {
     /**
      * @desc Страница «Распределение оборудования»
      */
    public function form()
    {
        $this->data['title'] = 'Распределение оборудования';
 
        /** @var Assignment $assignment */
        $assignment = $this->model('Assignment');

        $this->data['equipment'] = $assignment->getList();
        $this->data['selected_equipment'] = $assignment->GetEquipment("graduation");

        $this->addCSS("/assets/plugins/select2/css/select2.min.css");

        $this->addJs("/assets/plugins/select2/js/select2.min.js");
        $this->addJs("/assets/js/assignment.js");
 
        $this->view('form');
    }

    /**
     * @desc Сохраняет или обновляет оборудование для контроля градуировки pH-метра
     */
    public function insertUpdate()
    {
        /** @var Assignment $assignment */
        $assignment = $this->model('Assignment');

        $assignment->SetEquipment($_POST);

        $this->redirect('/assignment/form/');
    }
}