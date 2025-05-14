<?php

/**
 * @desc Формирование документов
 * Class GeneratorController
 */
class GeneratorController extends Controller
{
    /**
     * @desc Формирует коммерческое предложение
     * @param $dealID
     */
	public function	CommercialOffer($dealID)
	{
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var DocumentGenerator $generator */
		$generator = $this->model('DocumentGenerator');

		$generator->commercialOffer($dealID);

		$this->showSuccessMessage("Коммерческое предложение сформировано");
	}


    /**
     * @desc Формирует прил. к договору (ТЗ)
     * @param $dealID
     */
    public function TechnicalSpecification($dealID)
    {
		global $APPLICATION;

		$APPLICATION->RestartBuffer();

        /** @var DocumentGenerator $generator */
        $generator = $this->model('DocumentGenerator');

        $generator->technicalSpecification($dealID);

        $this->showSuccessMessage("Техническое задание сформировано");
    }


    /**
     * @desc Формирует протокол
     * @param $protocolID
     */
    public function ProtocolDocument($protocolID)
    {	global $APPLICATION;

		$APPLICATION->RestartBuffer();

        /** @var DocumentGenerator $generator */
        $generator = $this->model('DocumentGenerator');

        $generator->protocolGenerator($protocolID);
    }


    /**
     * @desc Заключение Специализированная контрольно-аналитическая лаборатория Дорожной инспекции
     * @param $protocolId
     */
    public function conclusionDocument($protocolId)
    {	global $APPLICATION;

		$APPLICATION->RestartBuffer();

        /** @var DocumentGenerator $generator */
        $generatorModel = $this->model('DocumentGenerator');

        $generatorModel->conclusionDocument($protocolId);

        $this->showSuccessMessage("Протокол сформирован");
    }



	/**
     * @desc Формирует Акт приема проб
     * @param $protocolID
	 */
	public function actSampleDocument($dealID)
	{	global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var DocumentGenerator $generator */
		$generator = $this->model('DocumentGenerator');

        $generator->actSampleGeneratorNew($dealID);

		$this->showSuccessMessage("Акт приема проб сформирован");
	}

    /**
     * @desc Формирует Акт отбора проб
     * @param int|null $dealId
     */
    public function generateSamplingActDocument(?int $dealId): void
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();

        /** @var DocumentGenerator $generatorModel */
        $generatorModel = $this->model('DocumentGenerator');
        /** @var Request $requestModel */
        $requestModel = $this->model('Request');

        $dealId = (int)$dealId;
        if ($dealId <= 0) {
            $this->showErrorMessage('Не указан, или указан неверно ИД заявки');
            $this->redirect("/request/list/");
        }

        $deal = $requestModel->getDealById($dealId);
        if (empty($deal)) {
            $this->showErrorMessage("Заявки с ИД {$dealId} не существует");
            $this->redirect('/request/list/');
        }

        $generatorModel->generateSamplingAct($dealId);

        $this->showSuccessMessage("Акт отбора проб сформирован");
    }


    /**
     * @desc Формирует Счет-оферта
     * @param $dealID
     */
	public function invoiceOfferDocument($dealID)
	{	global $APPLICATION;

		$APPLICATION->RestartBuffer();

		/** @var DocumentGenerator $generator */
		$generator = $this->model('DocumentGenerator');

			$generator->invoiceOfferGenerator($dealID);

		$this->showSuccessMessage("Счет-оферта сформирован");
	}

    public function getInventoryList()
    {
        /** @var DocumentGenerator $generator */
        $generator = $this->model('DocumentGenerator');

        $inform = [
            'dateInv' => !empty($_POST['inputDateEnd']) ? $_POST['inputDateEnd'] : date('Y-m-d'),
            'dateInvStart' => !empty($_POST['invDateStart']) ? $_POST['invDateStart'] : date('Y-m-d'),
            'directive' => !empty($_POST['directive']) ? $_POST['directive'] : '',
            'directive_date' => !empty($_POST['directive_date']) ? $_POST['directive_date'] : date('Y-m-d'),
        ];
        $oa = (int)$_POST['in_oa'] ?: 0;

        $generator->InventoryList($inform, $oa);
    }


//    /**
//     *
//     */
//	public function getContentDocx()
//	{
//		/** @var DocumentGenerator $generator */
//		$generator = $this->model('DocumentGenerator');
//
//		$generator->getContent();
//	}

    public function getVerificationGraph()
    {
        /** @var DocumentGenerator $generator */
        $generator = $this->model('DocumentGenerator');

        $year  = (int)$_POST['year'];
        $type  = (int)$_POST['type'];
        $oa    = !empty($_POST['in_oa']) ? 1 : 0;
        $month = !empty($_POST['month2']) ? 1 : 0;

        $generator->VerificationGraph($year, $type, $oa, $month);
    }
}
