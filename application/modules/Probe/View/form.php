<header class="header-requirement mb-3">
	<nav class="header-menu">
		<ul class="nav">
			<li class="nav-item me-2">
				<a class="nav-link" href="<?=URI?>/request/list/<?=$this->data['comm']??''?>" title="Вернуться к списку">
					<svg class="icon" width="20" height="20">
						<use xlink:href="<?=URI?>/assets/images/icons.svg#list"/>
					</svg>
				</a>
			</li>
			<li class="nav-item me-2">
				<a class="nav-link" href="<?=URI?>/request/card/<?=$this->data['deal_id']?>" title="Вернуться в карточку">
					<svg class="icon" width="20" height="20">
						<use xlink:href="<?=URI?>/assets/images/icons.svg#card"/>
					</svg>
				</a>
			</li>
			<li class="nav-item me-2">
				<a class="nav-link popup-help" href="/ulab/help/LIMS_Manual_Stand/Create_request_card/Create_request_card.html" title="Техническая поддержка">
					<i class="fa-solid fa-question"></i>
				</a>
			</li>
		</ul>
	</nav>
</header>

<h2 class="d-flex mb-3">
	Заявка <?= $this->data['deal_title'] ?? '' ?>
</h2>

<form action="/ulab/probe/insertUpdateActProbeNew/<?=$this->data['deal_id']?>" method="post">
<div class="panel panel-default">
	<header class="panel-heading">
		Данные акта
		<span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="#" class="fa fa-chevron-up"></a>
                 </span>
	</header>
	<div class="panel-body">
		<div class="wrapper-add-info mt-2 flex-column">
			<div class="row">
				<div class="form-group col-sm-6">
					<label class="form-label mb-1" for="infoObject">Номер акта</label>
					<input type="text" class="form-control object" name="act[ACT_NUM]" list="objects" value="<?= $this->data['new_act_number'] ?>" readonly>
				</div>

				<div class="form-group col-sm-6">
					<div class="col">
						<label class="form-label mb-1" for="infoDescription">Дата поступления проб в ИЦ (дата доставки проб)</label>
						<input type="date" class="form-control bg-white" name="act[ACT_DATE]" value="<?=date('Y-m-d')?>">
					</div>
				</div>
			</div>

			<div class="row mb-2">
				<div class="form-group col-sm-6">
					<label class="form-label mb-1" for="protocolDeadline">Пробу передал</label>
					<input type="text" class="form-control bg-white w-100" name="act[deliveryman]">
				</div>
				<div class="form-group col-sm-6">
					<label class="form-label mb-1" for="protocolDeadline">Пробу принял</label>
					<input type="text" class="form-control w-100" value="<?=$this->data['current_user']['short_name']?>"  readonly>
				</div>
			</div>

			<div class="row mb-2">
				<div class="form-group col-sm-6 row">
					<div class="col-6">
						<div>Заказчик</div>
						<div><strong><?= $this->data['company_name'] ?></strong></div>
					</div>
					<div class="col-6">
						<div>Проба отобрана не заказчиком</div>
						<div>
							<label class="switch">
								<input class="form-check-input" name="act[SELECTION_TYPE]" type="checkbox" value="1">
								<span class="slider"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group col-sm-6">
					<div>Основание для проведения испытаний</div>
					<strong>
						<?php if ( !empty($this->data['contract_number']) ): ?>
							<?= $this->data['contract_type'] ?> №<?= $this->data['contract_number'] ?> от <?= $this->data['contract_date'] ?>
						<?php else: ?>
							Договор еще не составлен
						<?php endif; ?>
					</strong>
				</div>
			</div>
		</div>
		<!--./wrapper-add-info-->
	</div>
	<!--./panel-body-->
</div>
<div class="panel panel-default">
	<header class="panel-heading">
		Информация о пробах (образцах)
		<span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="#" class="fa fa-chevron-up"></a>
                 </span>
	</header>
	<div class="panel-body">
		<div class="row mb-2">
			<div class="form-group col-sm-6">
				<label class="form-label mb-1" for="protocolDeadline">Место отбора (общее)</label>
				<input type="text" class="form-control bg-white w-100" id="place_all">
			</div>
			<div class="form-group col-sm-2">
				<label class="form-label mb-1" for="protocolDeadline">Дата отбора (общее)</label>
				<input type="date" class="form-control bg-white w-100" id="date_all">
			</div>
		</div>
		<table class="table">
			<thead>
				<th>№</th>
				<th>Наименование проб (образцов)</th>
				<th>Маркировка заказчика (при наличии)</th>
				<th>Место отбора</th>
				<th>Дата отбора</th>
			</thead>
			<tbody>
			<?php $i = 1;
			foreach ($this->data['material_probe'] as $val):
				foreach ($val['probe'] as $k => $item):?>
					<tr>
						<td><?=$i?></td>
						<td>
							<?=$val['material_name']?>
							<input type="hidden" name="act[deal_id]" value="<?=$this->data['deal_id']?>">
							<input type="hidden" name="act[act_type]" value="1">
						</td>
						<td>
							<input type="text" class="form-control bg-white w-100" name="act[probe][<?=$k?>][name_for_protocol]" value="<?=$item['name_for_protocol'] ?? ''?>">
						</td>
						<td>
							<input type="text" class="form-control place bg-white w-100" name="act[probe][<?=$k?>][place]">
						</td>
						<td>
							<input type="date" class="form-control date bg-white w-100" name="act[probe][<?=$k?>][date_probe]">
						</td>
					</tr>
				<?php $i++;
				endforeach;
			endforeach;?>
			</tbody>
		</table>
	</div>
</div>

<button class="form-control btn btn-primary mw-100 save" id="save" name="save" type="submit">Сохранить</button>
</form>


