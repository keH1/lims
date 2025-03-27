<form action="" class="row col-5 mb-4" method="post">
	<div class="col-6">
		<input type="month" class="form-control" name="month" value="<?=$this->data['statistic_date']?>">
	</div>
	<div class="col-4">
		<button type="submit" class="btn btn-success">Сформировать</button>
	</div>
</form>

<div class="panel panel-default">
	<header class="panel-heading">
		Отчет по руководителям ИЦ
		<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
    	</span>
	</header>
	<div class="panel-body">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th style="width: 30%"></th>
                    <th>Всего</th>
                    <?php foreach ($this->data['lab_list'] as $lab): ?>
                        <th><?=(empty($lab['short_name']))? $lab['NAME'] : $lab['short_name']?></th>
                    <?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
                <?php foreach ($this->data['field_report_protocol'] as $key => $field): ?>
                    <?php if ( is_numeric($key) ): ?>
                        <tr>
                            <th colspan="<?=2 + count($this->data['lab_list']?? [])?>"><?=$field?></th>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <td>
                                <?=$field?>
                            </td>
                            <td><?=$this->data['protocols']["all_{$key}"]?></td>
                            <?php foreach ($this->data['lab_list'] as $lab): ?>
                                <td>
                                    <?php if (isset($this->data['protocols']['dep'][$lab['id_dep']][$key])): ?>
                                        <?=$this->data['protocols']['dep'][$lab['id_dep']][$key]?>
                                    <?php else: ?>
                                        0
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<div class="panel panel-default">
	<header class="panel-heading">
		Отчет по сотрудникам ИЦ
		<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
    	</span>
	</header>
	<div class="panel-body" style="overflow: auto">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<td rowspan="2" class="align-middle">Показатель</td>
                    <?php $i = 0;?>
                    <?php foreach ($this->data['lab_list'] as $lab): ?>
                        <?php $colspan = count($this->data['users_from_dep'][$lab['id_dep']]??[]); ?>
                        <?php $colspan = $colspan == 0? 1 : $colspan; ?>
                        <?php $i += $colspan;?>
                        <th class="text-center" colspan="<?=$colspan?>"><?=(empty($lab['short_name']))? $lab['NAME'] : $lab['short_name']?></th>
                    <?php endforeach; ?>
				</tr>
				<tr>
                    <?php foreach ($this->data['lab_list'] as $lab): ?>
                        <?php if (!empty($this->data['users_from_dep'][$lab['id_dep']])): ?>
                            <?php foreach ($this->data['users_from_dep'][$lab['id_dep']] as $user): ?>
                                <td class="text-center" nowrap>
                                    <a href="/ulab/statistic/headerReportByUser/<?=$user['ID']?>"><?=$user['short_name']?></a>
                                </td>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <td class="text-center"></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
			<tr>
				<th colspan="<?=$i + 1?>">Испытания</th>
			</tr>
            <?php foreach ($this->data['field_report_user'] as $key => $row): ?>
                <tr>
                    <td nowrap><?=$row?></td>
                    <?php foreach ($this->data['lab_list'] as $lab): ?>
                        <?php if (!empty($this->data['users_from_dep'][$lab['id_dep']])): ?>
                            <?php foreach ($this->data['users_from_dep'][$lab['id_dep']] as $user): ?>
                                <td class="--text-center">
                                    <?php if ($key == 'percent_complete'): ?>
                                        <?php if ($this->data['user_methods'][$user['ID']]['complete'] > 0): ?>
                                            <?=round($this->data['user_methods'][$user['ID']]['dep_count'][$lab['id_dep']]/$this->data['user_methods'][$user['ID']]['complete']*100, 2)?>
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>
                                    <?php elseif ($key == 'percent_price'): ?>
                                        <?php if ($this->data['user_methods'][$user['ID']]['price'] > 0): ?>
                                            <?=round($this->data['user_methods'][$user['ID']]['dep_price'][$lab['id_dep']]/$this->data['user_methods'][$user['ID']]['price']*100, 2)?>
                                        <?php else: ?>
                                            0
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?=$this->data['user_methods'][$user['ID']][$key]?? "0"?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <td class="text-center">-</td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
			</tbody>
		</table>
		<button class="btn btn-success d-none" id="view-chart">Посмотреть график</button>
		<div class="visually-hidden" id="chartForm">
			<canvas id="myChart"></canvas>
		</div>
	</div>
</div>
<div class="panel panel-default">
	<header class="panel-heading">
		Финансовый Отчет ИЦ
		<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
    	</span>
	</header>
	<div class="panel-body">
		<table class="table table-striped table-bordered">
			<thead>
			<tr>
				<th style="width: 30%">Показатель</th>
				<th>Всего</th>
				<th>ЛФХИ</th>
				<th>ДСЛ</th>
				<th>ЛФМИ</th>
				<th>ЛСМ</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<th colspan="6">Заявка</th>
			</tr>
			<tr>
				<td>
					Общая стоимость новых заявок, руб
				</td>
				<td><?=$this->data['Finance']['total']?></td>
				<td><?=$this->data['Finance']['totalLFHI']?></td>
				<td><?=$this->data['Finance']['totalDSL']?></td>
				<td><?=$this->data['Finance']['totalLFMI']?></td>
				<td><?=$this->data['Finance']['totalLSM']?></td>
			</tr>
			<tr>
				<td>
					-- С начала года, руб
				</td>
				<td><?=$this->data['Finance']['totalByYear']?></td>
				<td><?=$this->data['Finance']['totalLFHIByYear']?></td>
				<td><?=$this->data['Finance']['totalDSLByYear']?></td>
				<td><?=$this->data['Finance']['totalLFMIByYear']?></td>
				<td><?=$this->data['Finance']['totalLSMByYear']?></td>
			</tr>
			<tr>
				<td>
					Всего оплачено, руб
				</td>
				<td><?=$this->data['Finance']['paidTotal']?></td>
				<td><?=$this->data['Finance']['paidTotalLFHI']?></td>
				<td><?=$this->data['Finance']['paidTotalDSL']?></td>
				<td><?=$this->data['Finance']['paidTotalLFMI']?></td>
				<td><?=$this->data['Finance']['paidTotalLSM']?></td>
			</tr>
			<tr>
				<td>
					Всего неоплачено заявок, шт
				</td>
				<td><?=$this->data['Finance']['notPaidTotal']?></td>
				<td><?=$this->data['Finance']['notPaidTotalLFHI']?></td>
				<td><?=$this->data['Finance']['notPaidTotalDSL']?></td>
				<td><?=$this->data['Finance']['notPaidTotalLFMI']?></td>
				<td><?=$this->data['Finance']['notPaidTotalLSM']?></td>
			</tr>
			<tr>
				<td>
					-- На сумму, руб
				</td>
				<td><?=$this->data['Finance']['notPaidSumTotal']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalLFHI']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalDSL']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalLFMI']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalLSM']?></td>
			</tr>
			<tr>
				<td>
					-- С начала года, руб
				</td>
				<td><?=$this->data['Finance']['notPaidSumTotalByYear']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalLFHIByYear']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalDSLByYear']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalLFMIByYear']?></td>
				<td><?=$this->data['Finance']['notPaidSumTotalLSMByYear']?></td>
			</tr>
			<tr>
				<td>
					Оплачено частично, шт
				</td>
				<td><?=$this->data['Finance']['partiallyPaidTotal']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalLFHI']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalDSL']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalLFMI']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalLSM']?></td>
			</tr>
			<tr>
				<td>
					-- На сумму, руб
				</td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSum']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumLFHI']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumDSL']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumLFMI']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumLSM']?></td>
			</tr>
			<tr>
				<td>
					-- С начала года, руб
				</td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumByYear']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumLFHIByYear']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumDSLByYear']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumLFMIByYear']?></td>
				<td><?=$this->data['Finance']['partiallyPaidTotalSumLSMByYear']?></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="panel panel-default">
	<header class="panel-heading">
		Отчет по МФЦ ИЦ
		<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
    	</span>
	</header>
	<div class="panel-body">
		<table class="table table-striped table-bordered w-50">
			<thead>
			<tr>
				<th>Показатель</th>
				<th>Всего</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<th colspan="2">Заявка</th>
			</tr>
			<tr>
				<td>
					Общее количество заявок, шт
				</td>
				<td><?=$this->data['MFC']['request']?></td>
			</tr>
			<tr>
				<td>
					Успешные, шт
				</td>
				<td><?=$this->data['MFC']['request_won']?></td>
			</tr>
			<tr>
				<td>
					Неуспешные, шт
				</td>
				<td><?=$this->data['MFC']['request_lose']?></td>
			</tr>
			<tr>
				<td>
					Уникальные (одна лаб.), шт
				</td>
				<td><?=$this->data['MFC']['laba_uniq']?></td>
			</tr>
			<tr>
				<td>
					Совместные (несколько лаб.), шт
				</td>
				<td><?=$this->data['MFC']['laba_non_uniq']?></td>
			</tr>
			<tr>
				<th colspan="2">Клиент</th>
			</tr>
			<tr>
				<td>
					Новый клиентов, шт:
				</td>
				<td><?=$this->data['MFC']['new_company']?></td>
			</tr>
<!--			<tr>-->
<!--				<td>-->
<!--					постоянный клиент-->
<!--				</td>-->
<!--				<td></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td>-->
<!--					недобросовестный клиент-->
<!--				</td>-->
<!--				<td></td>-->
<!--			</tr>-->
			<tr>
				<th colspan="2">Акты приемки</th>
			</tr>
			<tr>
				<td>
					Общее количество актов приемки проб, шт
				</td>
				<td><?=$this->data['MFC']['new_act']?></td>
			</tr>
			<tr>
				<td>
					Завершенные, шт
				</td>
				<td><?=$this->data['MFC']['act_won']?></td>
			</tr>
			<tr>
				<td>
					Незавершенные, шт
				</td>
				<td><?=$this->data['MFC']['act_work']?></td>
			</tr>
			<tr>
				<th colspan="2">Договоры</th>
			</tr>
			<tr>
				<td>
					Сфоромированно, шт
				</td>
				<td><?=$this->data['MFC']['dogovors_all']?></td>
			</tr>
			<tr>
				<td>
					Подписаны, шт
				</td>
				<td><?=$this->data['MFC']['dogovors_complete']?></td>
			</tr>
			<tr>
				<td>
					Не подписаны, шт
				</td>
				<td><?=$this->data['MFC']['dogovors_null']?></td>
			</tr>
			<tr>
				<th colspan="2">Техническое задание</th>
			</tr>
			<tr>
				<td>
					Отправлены клиенту, шт
				</td>
				<td><?=$this->data['MFC']['tz']?></td>
			</tr>
<!--			<tr>-->
<!--				<td>-->
<!--					подписано-->
<!--				</td>-->
<!--				<td></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td>-->
<!--					не подписано-->
<!--				</td>-->
<!--				<td></td>-->
<!--			</tr>-->
			<tr>
				<th colspan="2">Счета</th>
			</tr>
			<tr>
				<td>
					Сформировано, шт
				</td>
				<td><?=$this->data['MFC']['invoice']?></td>
			</tr>
			<tr>
				<td>
					Сформировано на сумму, руб
				</td>
				<td><?=$this->data['MFC']['invoice_price']?></td>
			</tr>
			<tr>
				<td>
					Оплачено на сумму, руб
				</td>
				<td><?=$this->data['MFC']['invoice_pay_win']?></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

<div class="panel panel-default">
	<header class="panel-heading">
		Годовой статистический отчет
		<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
    	</span>
	</header>
	<div class="panel-body">
		<h4>За данный год было:</h4>
		<p>Принято <strong><?=$this->data['Year']['fullYearRequest']?></strong> заявок</p>
		<br>
		<p>Заключено договоров: <b><?=$this->data['Year']['fullYearContract']?></b>, из них абонентских <b><?=$this->data['Year']['fullYearContractLongterm']?></b></p>
		<br>
		<p>Всего проведено испытаний: <b><?=$this->data['Year']['fullYearTests']?></b></p>
		<br>
		<p>Всего выдано протоколов: <b><?=$this->data['Year']['fullYearProtocols']?></b></p>
		<br>
		<p>Всего принято проб: <b><?=$this->data['Year']['fullYearProbe']?></b></p>
	</div>
</div>
<!--<div class="row gx-2">-->
<!--	<div class="col-md-6">-->
<!--		<div class="panel">-->
<!--			<header class="panel-heading">Отчет по руководителям ИЦ</header>-->
<!--		</div>-->
<!--	</div>-->
<!--	<div class="col-md-6">-->
<!--		<div class="panel">-->
<!--			<header class="panel-heading">Отчет по сотрудникам ИЦ</header>-->
<!--		</div>-->
<!--	</div>-->
<!--	<div class="col-md-6">-->
<!--		<div class="panel">-->
<!--			<header class="panel-heading">Отчет по финансам ИЦ</header>-->
<!--		</div>-->
<!--	</div>-->
<!--	<div class="col-md-6">-->
<!--		<div class="panel">-->
<!--			<header class="panel-heading">Отчет по МФЦ ИЦ</header>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
