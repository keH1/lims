<header class="header-requirement mb-3">
	<nav class="header-menu">
		<ul class="nav">
			<li class="nav-item me-2">
				<a class="nav-link" href="<?= URI ?>/order/list/" title="Вернуться к списку">
					<svg class="icon" width="20" height="20">
						<use xlink:href="<?= URI ?>/assets/images/icons.svg#list"/>
					</svg>
				</a>
			</li>
			<li class="nav-item me-2">
				<a class="nav-link link-card" href="<?= URI ?>/order/list/" title="Вернуться в карточку заявки">
					<svg class="icon" width="20" height="20">
						<use xlink:href="/ulab/assets/images/icons.svg#card"></use>
					</svg>
				</a>
			</li>
			<li class="nav-item me-2">
				<a class="nav-link popup-help" href="/ulab/help/LIMS_Manual_Stand/Contract_card/Contract_card.html"
				   title="ПОМОГИТЕ">
					<i class="fa-solid fa-question"></i>
				</a>
			</li>
		</ul>
	</nav>
</header>

<h2 class="d-flex mb-3">
	<?= $this->data['order']['company'] ?>
</h2>

<form class="form-horizontal" method="post" id="contract" action="<?= URI ?>/order/changeOrder/" enctype="multipart/form-data">
	<input type="hidden" id="input_order_id" name="orderID" value="<?= $this->data['order']['id'] ?>">

	<div class="panel panel-default"
		 style="<?= $this->data['order']['action'] ? '' : 'background-color: #ff3d3d45;' ?>">
		<header class="panel-heading">
			Основная информация
			<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
		</header>
		<div class="panel-body">
			<div class="input-group mb-3">
				<input type="text" name="CONTRACT_TYPE" class="form-control"
					   value="<?= $this->data['order']['type'] ?>" <?= $this->data['order']['client_number'] == 1 ? '' : 'readonly' ?>>
				<span class="input-group-text">№</span>
				<input type="text" class="form-control" name="NUMBER"
					   value="<?= $this->data['order']['number'] ?>" <?= $this->data['order']['client_number'] == 1 ? '' : 'readonly' ?>>
				<span class="input-group-text">от</span>
				<input type="date" class="form-control" name="DATE"
					   value="<?= $this->data['order']['date'] ?>" <?= $this->data['order']['client_number'] == 1 ? '' : 'readonly' ?>>
			</div>

			<div>Тип договора:</div>

			<div class="mb-3">
				<div class="form-check form-check-inline">
					<input type="checkbox" class="btn-check" name="LONGTERM" id="LONGTERM"
						   autocomplete="off" <?= $this->data['order']['longterm'] == 1 ? 'checked' : '' ?>>
					<label class="btn btn-outline-primary" for="LONGTERM">Абонентский</label>
				</div>
				<div class="form-check form-check-inline">
					<input type="checkbox" class="btn-check" name="CLIENT_NUMBER" id="CLIENT_NUMBER"
						   autocomplete="off" <?= $this->data['order']['client_number'] == 1 ? 'checked' : '' ?>>
					<label class="btn btn-outline-primary" for="CLIENT_NUMBER">Договор клиента</label>
				</div>
				<div class="form-check form-check-inline">
					<input type="checkbox" class="btn-check" name="FLOW_DATE" id="FLOW_DATE"
						   autocomplete="off" <?= $this->data['order']['longterm'] == 1 ? '' : 'checked' ?>>
					<label class="btn btn-outline-primary" for="FLOW_DATE">На 11 месяцев</label>
				</div>
			</div>

			<div class="line-dashed"></div>

			<nav class="footer-menu mb-3">
				<ul class="nav">
					<li class="nav-item me-2">
						<?php if (!empty($this->data['order']['pdf'])): ?>
							<a class="nav-link link-card" href="/pdf/<?= $this->data['order']['pdf'] ?>"
							   title="Скачать подписанную версию">
								<svg class="icon" width="30" height="30">
									<use xlink:href="/ulab/assets/images/icons.svg#docs"></use>
								</svg>
							</a>
						<?php else: ?>
							<label class="upload-pdf cursor-pointer nav-link link-card"
								   title="Загрузить PDF-версию">
								<svg class="icon" width="30" height="30">
									<use xlink:href="<?= URI ?>/assets/images/icons.svg#upload"/>
								</svg>
								<input class="d-none upload_pdf" type="file" name="upload_pdf"
									   data-dogovor_id="<?= $this->data['order']['id'] ?>">
							</label>
						<?php endif; ?>
					</li>
					<li class="nav-item me-2">
						<a class="nav-link link-card"
						   href="/protocol_generator/archive_dog/<?= $this->data['order']['id'] ?>/<?= $this->data['order']['actual_ver'] ?>.pdf"
						   title="Скачать договор">
							<!--							<svg class="icon" width="30" height="30">-->
							<!--								<use xlink:href="/ulab/assets/images/icons.svg#edit"></use>-->
							<!--							</svg>-->
							<i class="fa-regular fa-share-from-square" style="font-size: 1.9rem; color: #0b0b0b"></i>
						</a>
					</li>
					<li class="nav-item me-2">
						<a class="nav-link link-card"
						   href="/protocol_generator/dogovor.php?ID=<?= $this->data['dealID'] ?>&TZ_ID=<?= $this->data['tz_id'] ?>&ID_C=<?= $this->data['order']['id'] ?>"
						   title="Переформировать договор">
							<svg class="icon" width="30" height="30">
								<use xlink:href="/ulab/assets/images/icons.svg#edit"></use>
							</svg>
						</a>
					</li>
					<li class="nav-item me-2">
						<a class="nav-link link-card" style="color: black; font-size: 20px"
						   href="/ulab/order/cancelOrder/<?= $this->data['order']['id'] ?>"
						   title="Аннулировать договор">
							<i class="fa-solid fa-xmark fa-2xl"></i>
						</a>
					</li>
					<?php if ($this->data['order']['longterm'] == 1): ?>
						<li class="nav-item me-2">
							<?php if (!empty($this->data['client']['price'])): ?>
								<a class="nav-link disable-after-click"
								   href="/price_upd.php?ID_CONTRACT=<?= $this->data['order']['id'] ?>"
								   title="Перейти к прайсу">
									<svg class="icon" width="30" height="30">
										<use xlink:href="<?= URI ?>/assets/images/icons.svg#price"/>
									</svg>
								</a>
							<?php else: ?>
								<a class="nav-link disable-after-click"
								   href="/ulab/order/creatOrderPrice/<?= $this->data['order']['id'] ?>"
								   title="Создать прайс клиент">
									<svg class="icon" width="30" height="30">
										<use xlink:href="<?= URI ?>/assets/images/icons.svg#addPrice"/>
									</svg>
								</a>
							<?php endif; ?>
						</li>
					<?php endif; ?>
				</ul>
			</nav>

            <div class="">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
		</div>

	</div>


	<div class="panel panel-default"
		 style="<?= $this->data['order']['action'] ? '' : 'background-color: #ff3d3d45;' ?>">
		<header class="panel-heading">
			Информация о заказчике
			<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
		</header>
		<div class="panel-body">
			<div class="row mb-3">
				<div class="col-4">
					<div>Е-mail:</div>
					<div><strong><?= $this->data['client']['email'] ?></strong></div>
				</div>
				<div class="col-4">
					<div>Телефон:</div>
					<div><strong><?= $this->data['client']['phone'] ?></strong></div>
				</div>
				<div class="col-4">
					<div>Контактное лицо:</div>
					<div><strong><?= $this->data['client']['contact'] ?></strong></div>
				</div>
			</div>
		</div>
	</div>


	<div class="panel panel-default <?= $this->data['order']['longterm'] == 1 ? '' : 'visually-hidden' ?>" id="payment">
		<header class="panel-heading">
			Оплаты по договору
			<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
		</header>
		<div class="panel-body">
			<div class="row mb-3">
				<div class="col-5">
					<div class="row mb-3">
							<label class="col-sm-5 col-form-label">Основная заявка контракта/договора</label>
							<div class="col-sm-6">
								<select class="form-control select2 col-sm-6" name="head_request">
									<option value="">Нет</option>
									<?php foreach ($this->data['request'] as $request): ?>
										<option value="<?= $request['ID_DEAL'] ?>" <?= $this->data['order']['head_request'] == $request['ID_DEAL'] ? 'selected' : '' ?>>
											Заявка <?= $request['REQUEST_TITLE'] ?> от <?= $request['DATE_CREATE'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
					</div>
				</div>
				<div class="col-5">
					<div class="row mb-3">
						<label for="contract_summ" class="col-sm-3 col-form-label">Сумма договора:</label>
						<div class="col-sm-6">
							<input type="number" name="CONTRACT_SUMM" class="form-control" id="contract_summ"
								   value="<?= $this->data['order']['summ'] ?>"
								<?= $this->data['order']['longterm'] == 1 ? '' : 'disabled' ?>>
						</div>
					</div>
				</div>
			</div>

				</div>

	</div>


    <div class="panel panel-default"
         style="<?= $this->data['order']['action'] ? '' : 'background-color: #ff3d3d45;' ?>">
        <header class="panel-heading">
            Финансы
            <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
        </header>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-auto col-form-label">
                    Счет
                </label>
                <div class="col-auto">
                    <input id="finance" type="number" step="0.01" name="" class="form-control" value="<?=$this->data['order']['finance'] ?? '0'?>" readonly>
                </div>
                <?php if ($this->data['is_show_finance']): ?>
                    <div class="col-auto">
                        <a href="#add-finance" class="btn btn-primary popup-with-form">Добавить</a>
                    </div>
                <?php endif; ?>
                <div class="col-auto">
                    <a href="#history-finance" class="btn btn-primary popup-with-form">История</a>
                </div>
            </div>
        </div>
    </div>


	<div class="panel panel-default"
		 style="<?= $this->data['order']['action'] ? '' : 'background-color: #ff3d3d45;' ?>">
		<header class="panel-heading">
			Заявки по договору:
			<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
		</header>
		<div class="panel-body">

			<div class="filters mb-4">
				<select id="selectStage" class="form-control filter filter-pay">
					<option value="">Фильтр по оплате</option>
					<option value="0">Не оплачено</option>
					<option value="1">Оплачено</option>
				</select>
			</div>

			<table id="journal_order" class="table table-striped journal">
				<thead>
                <tr class="table-light">
					<th>Заявка</th>
					<th>Дата</th>
					<th>Сумма</th>
					<th>Счет</th>
					<th>ТЗ</th>
					<th>ТЗ PDF</th>
					<th>Скидка</th>
					<th>Оплата</th>
					<th></th>
				</tr>
                <tr class="header-search">
                    <th scope="col">
                        <input type="text" class="form-control search">
                    </th>
                    <th scope="col">

                    </th>
                    <th scope="col">
                        <input type="text" class="form-control search">
                    </th>
                    <th scope="col">
                        <input type="text" class="form-control search">
                    </th>
                    <th scope="col">
                    </th>
                    <th scope="col">
                    </th>
                    <th scope="col">
                    </th>
                    <th scope="col">
                        <input type="text" class="form-control search">
                    </th>
                    <th scope="col">
                    </th>
                </tr>
				</thead>
				<tbody>
				</tbody>
			</table>

			<?php if ($this->data['order']['longterm'] == 1):?>
				<div class="mb-3">
					Общая расчетная сумма по заявкам: <b><?= $this->data['cost_contract'] ?></b>
				</div>
				<div class="mb-3">
					Остаток по договору (без заявок ИЦ): <b><?= $this->data['debt_contract'] ?></b>
				</div>
			<?php else:?>
				<div class="mb-3">
					Общая расчетная сумма по всем рабочим заявкам договора: <b><?= $this->data['Cost'] ?></b>
				</div>
				<div class="mb-3">
					Сумма по всем неоплаченным заявкам договора: <b><?= $this->data['Debt'] ?></b>
				</div>
			<?php endif;?>
		</div>
	</div>
</form>

<?php if ($this->data['is_show_finance']): ?>
    <form id="add-payment" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
          action="/ulab/order/addPayment/" method="post">
        <div class="title mb-3 h-2">
            Оплатить заявку
        </div>
    
        <div class="line-dashed-small"></div>

        <div class="mb-3">
            <label class="form-label">На счету</label>
            <input type="number" step="0.01" min="0" class="form-control" value="<?=$this->data['order']['finance'] ?? '0'?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Необходимо оплатить</label>
            <input type="number" id="need-input" step="0.01" min="0" class="form-control" value="0" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Оплата</label>
            <input type="number" id="money-input" name="money" step="0.01" min="0" class="form-control" value="0" required>
            <input type="hidden" name="orderId" value="<?= $this->data['order']['id'] ?>">
            <input type="hidden" id="deal_id_input" name="deal_id" value="">
        </div>

        <div class="line-dashed-small"></div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>


    <form id="add-finance" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
          action="/ulab/order/addFinance/" method="post">
        <div class="title mb-3 h-2">
            Данные оплаты
        </div>

        <div class="line-dashed-small"></div>

        <div class="mb-3">
            <label class="form-label">Добавить на счет</label>
            <input type="number" name="money" step="0.01" min="0" class="form-control" value="" required>
            <input type="hidden" name="orderId" value="<?= $this->data['order']['id'] ?>">
        </div>

        <div class="line-dashed-small"></div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
<?php endif; ?>

<div id="history-finance" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        История финансов
    </div>

    <div class="line-dashed-small"></div>

    <div class="mb-3">
        <div class="row">
            <div class="col"><strong>Дата</strong></div>
            <div class="col"><strong>Событие</strong></div>
            <div class="col"><strong>Заявка</strong></div>
            <div class="col"><strong>Сумма</strong></div>
            <div class="col"><strong>Пользователь</strong></div>
        </div>
        <?php foreach ($this->data['history'] as $row): ?>
            <div class="row">
                <div class="col"><?=$row['date']?></div>
                <div class="col"><?=$row['action']?></div>
                <div class="col"><?=$row['REQUEST_TITLE']?></div>
                <div class="col"><?=$row['money']?></div>
                <div class="col"><?=$row['user_name']?></div>
            </div>
        <?php endforeach; ?>
    </div>

</div>


<form id="pay-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
	  action="/ulab/order/setOplata/" method="post">
	<div class="title mb-3 h-2">
		Данные оплаты
	</div>

	<div class="line-dashed-small"></div>

	<div class="mb-3">
		<label class="form-label">Оплата</label>
		<input type="number" name="pay" step="0.01" max="<?= $this->data['Debt_Dog_modal'] ?>" class="form-control"
			   value="<?= $this->data['Debt_Dog_modal'] ?>" required>
	</div>

	<div class="mb-3">
		<label class="form-label">Дата оплаты</label>
		<input type="date" name="payDate" class="form-control" value="<?= date('Y-m-d') ?>">
	</div>

	<input name="order_id" value="<?= $this->data['order']['id'] ?>" type="hidden">

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Отправить</button>
</form>
