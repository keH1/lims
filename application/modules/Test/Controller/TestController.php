<?php


/**
 * @desc Контроллер для тестов
 * Class TestController
 */
class TestController extends Controller
{
	public function index()
	{
		/** @var Request $request */
		$request = $this->model('Request');
		/** @var Material $material */
		$material = $this->model('Material');
		/** @var Requirement $requirement */
		$requirement = $this->model('Requirement');
		/** @var Gost $gost */
		$gost = $this->model('Gost');
		/** @var User $user */
		$user = $this->model('User');

		global $DB;

		$arr = [
'ГОСТ Р 58401.1',
'ГОСТ Р 58401.3',
'ГОСТ Р 58401.5',
'ГОСТ Р 58401.8',
'ГОСТ Р 58401.10',
'ГОСТ Р 58401.13',
'ГОСТ Р 58401.14',
'ГОСТ Р 58401.15',
'ГОСТ Р 58401.16',
'ГОСТ Р 58401.18',
'ГОСТ Р 58401.19',
'ГОСТ Р 58401.20',
'ГОСТ Р 58401.22',
'ГОСТ Р 58401.24',
'ГОСТ Р 58407.4',
'ГОСТ Р 58407.5',
'ГОСТ Р 58406.3',
'ГОСТ Р 58406.4',
'ГОСТ Р 58401.2',
'ГОСТ Р 58401.4',
'ГОСТ Р 58401.5',
'ГОСТ Р 58401.8',
'ГОСТ Р 58401.10',
'ГОСТ Р 58401.20',
'ГОСТ Р 58401.13',
'ГОСТ Р 58401.14',
'ГОСТ Р 58401.15',
'ГОСТ Р 58401.16',
'ГОСТ Р 58401.18',
'ГОСТ Р 58401.19',
'ГОСТ Р 58401.22',
'ГОСТ Р 58401.23',
'ГОСТ Р 58401.24',
'ГОСТ Р 58407.4',
'ГОСТ Р 58407.5',
'ГОСТ Р 58406.3',
'ГОСТ Р 58406.4',
'ГОСТ Р 58406.2',
'ГОСТ Р 58406.9',
'ГОСТ Р 58401.19',
'ГОСТ 33029',
'ГОСТ Р 58401.16',
'ГОСТ Р 58401.10',
'ГОСТ Р 58401.20',
'ГОСТ Р 58401.8',
'ГОСТ Р 58401.18',
'ГОСТ Р 58406.8',
'ГОСТ Р 58406.6',
'ГОСТ Р 58406.2',
'ГОСТ Р 58406.3',
'ГОСТ Р 58406.4',
'ГОСТ Р 58406.10',
'ГОСТ Р 58407.4',
'ГОСТ Р 58407.5',
'ГОСТ Р 58406.1',
'ГОСТ Р 58406.9',
'ГОСТ Р 58401.19',
'ГОСТ 33029',
'ГОСТ Р 58401.16',
'ГОСТ Р 58401.10',
'ГОСТ Р 58401.20',
'ГОСТ Р 58401.8',
'ГОСТ Р 58401.18',
'ГОСТ Р 58406.8',
'ГОСТ Р 58406.6',
'ГОСТ Р 58406.2',
'ГОСТ Р 58406.3',
'ГОСТ Р 58406.4',
'ГОСТ Р 58406.10',
'ГОСТ Р 58406.1',
'ГОСТ Р 58407.4',
'ГОСТ Р 58407.5',
'ГОСТ 9128',
'СП 78.13330.2012 ',
'СП 82.13330.2016 ',
'ГОСТ 12801',
'ГОСТ 31015',
'ГОСТ 12801',
'СТО-571841-002-114-18567-2005',
'ГОСТ 12801',
'ГОСТ Р 54401',
'ГОСТ Р 54400',
'ГОСТ Р 58406.6',
'ГОСТ Р 58407.4',
'ГОСТ Р 58407.5',
'ГОСТ Р 58401.8',
'ГОСТ Р 58401.10',
'ГОСТ Р 58401.16',
'ГОСТ Р 58401.19',
'ГОСТ 33029',
'ТУ 5718-002-04000633',
'ГОСТ 12801',
'ГОСТ 31015',
'СТО 5718-58528024-002-2013',
'ГОСТ 12801',
'ГОСТ 31015',
'ГОСТ 9128',
'СП 78.13330.2012',
'СТО 61595504-003',
'ГОСТ 8736',
'ГОСТ 32824',
'ГОСТ 8735',
'ГОСТ 8269.0',
'ГОСТ 30108',
'ГОСТ Р 58402.1',
'ГОСТ Р 58402.4',
'ГОСТ 32708',
'ГОСТ 32721',
'ГОСТ 32722',
'ГОСТ 32725',
'ГОСТ 32726',
'ГОСТ 32727',
'ГОСТ 32768',
'ГОСТ 32728',
'ГОСТ 25100',
'СП 34.13330.2021',
'ГОСТ 5180',
'ГОСТ 8269.0',
'ГОСТ 12536',
'ГОСТ 22733',
'ГОСТ 25584',
'ГОСТ 31424',
'ГОСТ 9128',
'ГОСТ 8735',
'ГОСТ 8269.0',
'ГОСТ 30108',
'ГОСТ 32730',
'ГОСТ 32708',
'ГОСТ 32717',
'ГОСТ 32720',
'ГОСТ 32721',
'ГОСТ 32722',
'ГОСТ 32725',
'ГОСТ 32726',
'ГОСТ 32727',
'ГОСТ 32728',
'ГОСТ 32768',
'ГОСТ Р 58402.1',
'ГОСТ Р 58402.4',
'ГОСТ 8267',
'ГОСТ 8269.0',
'ГОСТ 30108',
'ГОСТ 32703',
'ГОСТ 32817',
'ГОСТ 33026',
'ГОСТ 33028',
'ГОСТ 33029',
'ГОСТ 33030',
'ГОСТ 33047',
'ГОСТ 33048',
'ГОСТ 33051',
'ГОСТ 33053',
'ГОСТ 33054',
'ГОСТ 33055',
'ГОСТ 33057',
'ГОСТ 33109',
'ГОСТ Р 58402.3',
'ГОСТ Р 58402.5',
'ГОСТ Р 58402.6',
'ГОСТ 25607',
'ГОСТ 8267',
'СП 34.13330.2021',
'ГОСТ 8269.0',
'ГОСТ 8735',
'ГОСТ 5180',
'ГОСТ 25584',
'ГОСТ 30108',
'ГОСТ 23735',
'ГОСТ 8267',
'ГОСТ 8735',
'ГОСТ 8269.0',
'ГОСТ 32817',
'ГОСТ 30108',
'ГОСТ Р 52129',
'ГОСТ 12801',
'ГОСТ 30108',
'ГОСТ 32761',
'ГОСТ 32704',
'ГОСТ 32705',
'ГОСТ 32707',
'ГОСТ 32718',
'ГОСТ 32719',
'ГОСТ 32762',
'ГОСТ 32763',
'ГОСТ 32764',
'ГОСТ 32765',
'ГОСТ 32766',
'ГОСТ Р 58402.7',
'ГОСТ Р 58402.8',
'ГОСТ 7473',
'ГОСТ 10181',
'ГОСТ 26633',
'ГОСТ 25192',
'ГОСТ 6665',
'ГОСТ 13015',
'ГОСТ 17608',
'ГОСТ 18105',
'ГОСТ 12730.0',
'ГОСТ 12730.1',
'ГОСТ 12730.2',
'ГОСТ 12730.3',
'ГОСТ 12730.4',
'ГОСТ 12730.5',
'ГОСТ 28570',
'ГОСТ 22690',
'ГОСТ 10060',
'ГОСТ 10180',
'ГОСТ 17624',
'ГОСТ 30108',
'ГОСТ 32961',
'ГОСТ 32018',
'ГОСТ 30629',
'ГОСТ 30108',
'ГОСТ 32961',
'ГОСТ 32962',
'ГОСТ Р 50597',
'ГОСТ Р 52399',
'СП 78.13330.2012',
'СП 82.13330.2016',
'ГОСТ Р 56925',
'Пособие к СП 78.13330.2012',
'ГОСТ Р 59120',
'ТР 103',
'ГОСТ Р 56925',
'ТУ 5212-005-93000278',
'ГОСТ 33220',
'ГОСТ Р 52577',
'ГОСТ Р 56925',
'ГОСТ 33101',
'ГОСТ 33078',
'ГОСТ 32825',
'СП 78.13330.2012',
'СП 34.13330.2021',
'СП 78.13330.2012',
'ГОСТ Р 56925',
'ОДМ 218.4.039',
'ОДМ 218.11.001',
'ГОСТ 33220',
'ГОСТ 32964',
'ГОСТ 32759',
'ГОСТ 33388',
'ГОСТ 33078',
'ГОСТ 32825',
'ГОСТ 33101',
'ГОСТ Р 58349',
'ГОСТ Р 51256',
'ГОСТ Р 52289',
'ГОСТ Р 50597',
'ГОСТ Р 54809',
'ГОСТ 32953',
'ГОСТ Р 58350',
'ГОСТ 33220',
'ГОСТ 32952',
'ГОСТ 52575',
'ГОСТ 32830',
'ГОСТ 32848',
'ГОСТ Р 54306',
'ГОСТ Р 53170',
'ГОСТ Р 53172',
'ГОСТ Р 52576',
'ГОСТ Р 54307',
'ГОСТ Р 53171',
'ГОСТ Р 53173',
'ГОСТ 32829',
'ГОСТ 8420',
'ГОСТ 17537',
'ГОСТ 15140',
'ГОСТ 12801',
'ГОСТ 11506',
'ГОСТ 22245',
'ГОСТ 33133',
'ГОСТ 11501',
'ГОСТ 11506',
'ГОСТ 11507',
'ГОСТ 18180',
'ГОСТ 12801',
'ГОСТ 33134',
'ГОСТ 33136',
'ГОСТ 33137',
'ГОСТ 33138',
'ГОСТ 33141',
'ГОСТ 33142',
'ГОСТ 33143',
'ГОСТ 33140',
'ГОСТ Р 59119',
'ГОСТ Р 52056',
'ГОСТ 22245',
'ГОСТ 11501',
'ГОСТ 11506',
'ГОСТ 11505',
'ГОСТ 18180',
'ГОСТ 12801',
'ГОСТ Р 59119',
'ГОСТ Р 58400.1',
'ГОСТ Р 58400.2',
'ГОСТ Р 59119',
'ГОСТ Р 58400.3',
'ГОСТ Р 58400.4',
'ГОСТ Р 58400.5',
'ГОСТ Р 58400.6',
'ГОСТ Р 58400.7',
'ГОСТ Р 58400.8',
'ГОСТ Р 58400.9',
'ГОСТ Р 58400.10',
'ГОСТ 33141',
'ГОСТ 33140',
'ГОСТ 33137',
'ТУ 5775-002-42843072',
'ГОСТ 11501',
'ГОСТ 11506',
'ГОСТ Р 52056',
'СТО 577188-58528024-001-2013',
'ГОСТ 11501',
'ГОСТ 11506',
'ГОСТ 11505',
'ГОСТ 11507',
'ГОСТ 18180',
'ГОСТ 12801',
'ГОСТР 58952.1',
'ГОСТ Р 58952.1',
'ГОСТ Р 58952.4',
'ГОСТ Р 58952.5',
'ГОСТ Р 58952.6',
'ГОСТ Р 58952.7',
'ГОСТ Р 58952.8',
'ГОСТ Р 58952.9',
'ГОСТ Р 58952.10',
'ГОСТ Р 58952.3',
'ОДН 218.2.027',
'ГОСТ 8269.0',
'ГОСТ 8735',
'ГОСТ 13685',
'ГОСТ 30108',
'ГОСТ 33387',
'ГОСТ 33389',
		];

		$newArr = array_unique($arr);

		$str = '';
		$id = [];
		foreach ($newArr as $it) {
			$str .= " `reg_doc` NOT LIKE '{$it}' AND";
		}
//		$user->pre("select id from ulab_gost where {$str} 1");
		$res = $DB->Query("select id from ulab_gost where {$str} 1");
		while ($row = $res->fetch()) {
//			$DB->Query("delete from ulab_gost where id = {$row['id']}");
		}
		$this->view('index');
	}
}
