<form action="" class="row col-5 mb-4" method="post">
	<div class="col-6">
		<input type="date" class="form-control" name="month" value="<?=$this->data['statistic_date']?>">
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
	<div class="panel-body overflow-auto">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th></th>
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
                                        <?php if ($key == 'price'): ?>
                                            <?=number_format($this->data['protocols']['dep'][$lab['id_dep']][$key], 2, ',', ' ')?>
                                        <?php else: ?>
                                            <?=$this->data['protocols']['dep'][$lab['id_dep']][$key]?>
                                        <?php endif; ?>
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
	<div class="panel-body overflow-auto">
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
<!--                                    <a href="/ulab/statistic/headerReportByUser/--><?php //=$user['ID']?><!--">--><?php //=$user['short_name']?><!--</a>-->
                                    <?=$user['short_name']?>
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
                                <td class="text-center">
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
                                    <?php elseif ($key == 'price'): ?>
                                        <?=number_format($this->data['user_methods'][$user['ID']]['price'], 2, ',', ' ')?>
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
	<div class="panel-body overflow-auto">
		<table class="table table-striped table-bordered">
			<thead>
            <tr>
                <td>Показатель</td>
                <th>Всего</th>
                <?php foreach ($this->data['lab_list'] as $lab): ?>
                    <th><?=(empty($lab['short_name']))? $lab['NAME'] : $lab['short_name']?></th>
                <?php endforeach; ?>
            </tr>
			</thead>
			<tbody>
			<tr>
				<th colspan="<?=2 + count($this->data['lab_list']?? [])?>">Заявка</th>
			</tr>
            <?php foreach ($this->data['fin_report_rows'] as $key => $row): ?>
                <tr>
                    <td>
                        <?=$row?>
                    </td>
                    <td>
                        <?=$this->data['fin_report']["all_{$key}"]?? 0?>
                    </td>
                    <?php foreach ($this->data['lab_list'] as $lab): ?>
                        <td>
                            <?php if (isset($this->data['fin_report']['dep'][$lab['id_dep']][$key])): ?>
                                <?=$this->data['fin_report']['dep'][$lab['id_dep']][$key]?>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
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
            <?php foreach ($this->data['mfc_report_rows'] as $key => $row): ?>
                <tr>
                <?php if ( is_numeric($key) ): ?>
                    <th colspan="2"><?=$row?></th>
                <?php else: ?>
                    <td>
                        <?=$row?>
                    </td>
                    <td>
                        0
                    </td>
                <?php endif; ?>
                </tr>
            <?php endforeach; ?>
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

