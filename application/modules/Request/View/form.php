<?php if ( isset($this->data['request']['id']) ): ?>
    <header class="header-requirement mb-3">
        <nav class="header-menu">
            <ul class="nav">
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?=URI?>/request/list/" title="Вернуться к списку">
                        <svg class="icon" width="20" height="20">
                            <use xlink:href="<?=URI?>/assets/images/icons.svg#list"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?=URI?>/request/card/<?=$this->data['request']['id']?>" title="Вернуться в карточку">
                        <svg class="icon" width="20" height="20">
                            <use xlink:href="<?=URI?>/assets/images/icons.svg#card"/>
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <h2 class="d-flex mb-3">
        <div class="stage-block rounded <?=$this->data['stage']['color']?> me-1 mt-1" title="<?=$this->data['stage']['title']?>"></div>
        Заявка <?=$this->data['deal_title']?>
    </h2>
<?php endif; ?>

<form class="form-horizontal" method="post" action="<?=URI?>/request/insertUpdate/">
    <div class="panel panel-default">
        <header class="panel-heading">
            Реквизиты
            <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
        </header>
        <div class="panel-body">
            <?php if ( isset($this->data['request']['id']) && !empty($this->data['request']['id']) ): ?>
                <input type="hidden" value="<?=$this->data['request']['id']?>" name="id">
            <?php endif; ?>
            <div class="form-group row">
                <label for="company" class="col-sm-2 col-form-label">Клиент <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <input id="company" class="form-control" list="company_list" type="text" name="company" value="<?=$this->data['request']['company']['TITLE']?? htmlspecialchars($this->data['request']['company'])?? '' ?>" autocomplete="off" required>
                    <input type="hidden" name="company_id" id="company-hidden" value="<?=$this->data['request']['company']['ID']?? $this->data['request']['company_id']?? ''?>">
                    <datalist id="company_list">
                        <?php if (isset($this->data['companies'])): ?>
                            <?php foreach ($this->data['companies'] as $company): ?>
                                <option data-value="<?=$company['ID']?>"><?=$company['TITLE']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ИП</label>
                <div class="col-sm-8">
                    <input class="form-check-input check-ip" type="checkbox" name="check_ip" value="1" <?=$this->data['request']['check_ip'] == 1? 'checked' : ''?>>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Полное наименование компании</label>
                <div class="col-sm-8">
                    <input type="text" name="CompanyFullName" class="form-control clearable" value="<?= $this->data['request']['CompanyFullName'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ИНН</label>
                <div class="col-sm-8">
                    <input type="text" name="INN" maxlength="12" class="form-control number-only appearance-none clearable" value="<?=$this->data['request']['INN'] ?? ''?>">
                    <div id="innHelp" class="form-text"></div>
                </div>
                <div class="col-sm-2"></div>
            </div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">КПП</label>
				<div class="col-sm-8">
					<input type="text" name="KPP" maxlength="9" class="form-control number-only clearable" value="<?=$this->data['request']['KPP'] ?? ''?>">
				</div>
				<div class="col-sm-2"></div>
			</div>

            <div class="form-group row">
                <label for="ogrnip" class="col-sm-2 col-form-label">ОГРНИП</label>
                <div class="col-sm-8">
                    <input type="text" name="OGRNIP" id="ogrnip" class="form-control number-only clearable" <?=$this->data['request']['check_ip'] == 1? '': 'disabled'?> value="<?=$this->data['request']['OGRNIP'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ОГРН</label>
                <div class="col-sm-8">
                    <input type="text" name="OGRN" maxlength="13" class="form-control number-only clearable" value="<?=$this->data['request']['OGRN'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Адрес</label>
                <div class="col-sm-8">
                    <input type="text" name="ADDR" class="form-control clearable" value="<?=$this->data['request']['ADDR'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Фактический адрес</label>
                <div class="col-sm-8">
                    <input type="text" id="actual_address" name="ACTUAL_ADDRESS" class="form-control clearable" value="<?= htmlspecialchars($this->data['request']['ACTUAL_ADDRESS']) ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Почтовый адрес</label>
                <div class="col-sm-8">
                    <input type="text" name="mailingAddress" class="form-control clearable" value="<?= htmlspecialchars($this->data['request']['mailingAddress']) ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">E-mail для договора</label>
                <div class="col-sm-8">
                    <input type="email" name="EMAIL" class="form-control clearable" placeholder="_@_._" value="<?=$this->data['request']['EMAIL'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">E-mail <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <input type="email" name="POST_MAIL" class="form-control clearable" placeholder="_@_._" required value="<?=$this->data['request']['POST_MAIL'] ?? ''?>">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary add_email btn-add-del" type="button">
                        <i class="fa-solid fa-plus icon-fix"></i>
                    </button>
                </div>
            </div>

            <?php if (isset($this->data['request']['addEmail'])): ?>
                <?php foreach ($this->data['request']['addEmail'] as $i => $email): ?>
                    <div class="form-group row added_mail">
                        <label class="col-sm-2 col-form-label">Дополнительный E-mail <?=($i+1)?></label>
                        <div class="col-sm-8">
                            <input type="email" name="addEmail[]" class="form-control" placeholder="_@_._" value="<?=$email?>">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-danger remove_this btn-add-del" type="button">-</button>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif; ?>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Телефон <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <input type="text" name="PHONE" class="form-control clearable" required value="<?=$this->data['request']['PHONE'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Контактное лицо</label>
                <div class="col-sm-8">
                    <input type="text" name="CONTACT" class="form-control clearable" maxlength="150" value="<?=$this->data['request']['CONTACT'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Должность руководителя</label>
                <div class="col-sm-8">
                    <input type="text" name="Position2" class="form-control clearable" value="<?=$this->data['request']['Position2'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Должность руководителя в родительном падеже</label>
                <div class="col-sm-8">
                    <input type="text" name="PositionGenitive" class="form-control clearable" value="<?=$this->data['request']['PositionGenitive'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ФИО руководителя</label>
                <div class="col-sm-8">
                    <input type="text" name="DirectorFIO" class="form-control clearable" value="<?=$this->data['request']['DirectorFIO'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">Действует на основании</label>
				<div class="col-sm-8">
					<input type="text" name="ACTS_BASIS" class="form-control" value="<?=$this->data['request']['ACTS_BASIS'] ?? 'Устава'?>" >
				</div>
				<div class="col-sm-2"></div>
			</div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Расчетный счет</label>
                <div class="col-sm-8">
                    <input type="text" name="RaschSchet" class="form-control clearable" value="<?=$this->data['request']['RaschSchet'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Кор. счёт</label>
                <div class="col-sm-8">
                    <input type="text" name="KSchet" class="form-control clearable" value="<?=$this->data['request']['KSchet'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Лицевой счёт</label>
                <div class="col-sm-8">
                    <input type="text" name="l_schet" class="form-control clearable" value="<?=$this->data['request']['l_schet'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">БИК</label>
                <div class="col-sm-8">
                    <input type="text" name="BIK" class="form-control clearable" value="<?=$this->data['request']['BIK'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Наименование банка</label>
                <div class="col-sm-8">
                    <input type="text" name="BankName" class="form-control clearable" value="<?= htmlentities($this->data['request']['BankName'] ?? '') ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <header class="panel-heading">
            Акт отбора проб
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-down"></a>
            </span>
        </header>
        <div class="panel-body panel-hidden" style="display: none;">

            <!-- <h6 class="d-flex mb-3">
                Заказчик: <?= $this->data['company'] ?>
            </h6>

            <h6 class="d-flex mb-3">
                Адрес отбора проб: <?= $this->data['object'] ?>
            </h6> -->

            <!-- <div class="mb-3 row">
                <div class="col-6">
                    <label class="form-label">Наименование проб:</label>
                    <select class="form-select" name="name_probe" style="max-width: 100%">
                        <?php if ($this->data['id_material'] == 1): ?>
                            <option value="Вода">Вода</option>
                        <?php elseif ($this->data['id_material'] == 3): ?>
                            <option value="Почва">Почва</option>
                            <option value="Грунт">Грунт</option>
                            <option value="Донные отложения">Донные отложения</option>
                        <?php elseif ($this->data['id_material'] == 5): ?>
                            <option value="Атмосферный воздух">Атмосферный воздух</option>
                        <?php else:?>
                            <option value="<?=$this->data['material']['NAME']?>"><?=$this->data['material']['NAME']?></option>
                        <?php endif; ?>
                    </select>
                </div>

                <input type="hidden" name="id_act" value="<?=$this->data['id_act']?>">

                <div class="col-6">
                    <label class="form-label">НД на отбор проб:</label>
                    <select name="ND" class="form-select" style="max-width: 100%">
                        <?php foreach ($this->data['ND'] as $ND):?>
                            <option value="<?=$ND['id']?>"
                                <?= $this->data['information']['ND'] == $ND['id'] ? 'selected' : ''?>>
                                <?=$ND['name']?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div> -->
            <div class="mb-3 row">
                <div class="col-6">
                    <label class="form-label">Дата и время отбора проб:</label>
                    <input type="datetime-local" class="form-control" name="information[date_sample_all]" value="<?=$this->data['request']['act_information']['date_sample_all']?>">
                </div>

                <div class="col-6">
                    <label class="form-label">Дата и время доставки проб в ИЛЦ:</label>
                    <input type="datetime-local" class="form-control" name="information[datetime_arrive]" value="<?=$this->data['request']['act_information']['datetime_arrive']?>">
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-6">
                    <label class="form-label">Условия доставки проб:</label>
                    <input type="text" class="form-control" name="information[delivery_terms]" value="<?=$this->data['request']['act_information']['delivery_terms']?>">
                </div>
                <div class="col-3">
                    <label class="form-label">Упаковка:</label>
                    <input type="text" class="form-control" name="information[package]" value="<?=$this->data['request']['act_information']['package']?>">
                </div>
                <div class="col-3">
                    <label class="form-label">Масса (объём):</label>
                    <input type="text" class="form-control" name="information[weight]" value="<?=$this->data['request']['act_information']['weight']?>">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="sampler" class="form-label">Отбор проб произвел:</label>
                    <select class="form-control assigned-select"
                            name="information[sampler]" id="sampler"
                    >
                        <option value="" <?= empty($this->data['request']['act_information']['sampler']) ? "selected" : "" ?> disabled>Выберите отборщика</option>
                        <?php foreach ($this->data['clients'] as $user): ?>
                            <option value="<?=$user['ID']?>" <?= ((int)$this->data['request']['act_information']['sampler'] == (int)$user['ID']) ? "selected" : ""?>>
                                <?=$user['LAST_NAME']?> <?=$user['NAME']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- <input type="text" class="form-control" name="information[sampler]" value="<?= $this->data['request']['act_information']['sampler'] ?>"> -->
                </div>
                <div class="col-6">
                    <label class="form-label">Цель проведения испытаний:</label>
                    <input type="text" class="form-control" name="information[objective]" value="<?=$this->data['request']['act_information']['objective']?>">
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="sample_come" class="form-label">Пробы принял:</label>
                    <select name="information[sample_come]" id="sample_come"
                            class="form-select" style="max-width: 100%"
                    >
                        <option value="" <?= empty($this->data['request']['act_information']['sample_come']) ? "selected" : "" ?> disabled>Выберите приемщика</option>
                        <?php foreach ($this->data['clients'] as $user):?>
                            <option value="<?=$user['ID']?>" <?= ((int)$this->data['request']['act_information']['sample_come'] == (int)$user['ID']) ? "selected" : ""?>>
                                <?=$user['LAST_NAME']?> <?=$user['NAME']?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <!-- <div class="mb-3 row">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Шифр в ИЛЦ</th>
                            <th>Наим. пробы</th>
                            <th>Время отбора</th>
                            <th>Точка отбора</th>
                            <th>Глубина отбора, м</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                            foreach ($this->data['probe'] as $probe): ?>
                                <tr>
                                    <td><input class="form-control" type="text" value="<?= $probe ?>" name="probe[]"
                                            readonly></td>
                                    <td><?= $this->data['material']['NAME'] ?></td>
                                    <td>
                                        <input type="time" class="form-control date_time_otbor"
                                            name="datetime_sample[<?= $i ?>]"
                                            value="<?= date('H:i', strtotime($this->data['information']['datetime_sample'][$i]))?>">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="point_sample[<?= $i ?>]"
                                            value="<?= $this->data['information']['point_sample'][$i] ?>">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" name="deep[<?= $i ?>]"
                                            value="<?= $this->data['information']['deep'][$i] ?>">
                                    </td>
                                </tr>
                        <?php $i++; endforeach; ?>
                    </tbody>
                </table>
            </div> -->
            <!-- <div class="mb-3 row">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                    aria-controls="panelsStayOpen-collapseOne">
                                Перечень определяемых показателей:
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse"
                            aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body w-100">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($this->data['gosts'] as $gosts): ?>
                                        <li class="list-group-item"><?= $gosts ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Данные заявки
            <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
        </header>
        <div class="panel-body">
			<div class="form-group row order-type <?=$this->data['request']['order_type'] == 2 ? 'visually-hidden' : ''?>">
				<label class="col-sm-2 col-form-label">Основание для проведения испытаний:</label>
				<div class="col-sm-8">
					<select class="form-control" name="NUM_DOGOVOR">
						<option value="">Новый договор</option>
						<?php foreach ($this->data['contracts'] as $contract): ?>
							<option value="<?=$contract['ID']?>" <?=$this->data['request']['DOGOVOR_NUM'] == $contract['ID']? 'selected' : '' ?>>
								<?=$contract['CONTRACT_TYPE'] ?? 'Договор'?> №<?=$contract['NUMBER']?> от <?=$contract['DATE']?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-sm-2"></div>
			</div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Тип заявки <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" name="REQ_TYPE" required>
                        <option value="SALE" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] === 'SALE') ? 'selected': ''?>>ИЦ</option>
                        <option value="COMPLEX" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] === 'COMPLEX') ? 'selected': ''?>>ОСК</option>
                        <option value="1" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '1') ? 'selected': ''?>>ВЛК</option>
                        <option value="2" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '2') ? 'selected': ''?>>МСИ</option>
                        <option value="5" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '5') ? 'selected': ''?>>АП</option>
                        <option value="4" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '4') ? 'selected': ''?>>НК</option>
                        <option value="8" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '8') ? 'selected': ''?>>Н</option>
                        <option value="7" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '7') ? 'selected': ''?>>ПР</option>
                        <option value="9" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '9') ? 'selected': ''?>>ГР</option>
                    </select>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <?php if ( !isset($this->data['request']['id']) ): ?>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Материал для исследования <span class="redStars">*</span></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input
                                    type="text"
                                    id="material0"
                                    list="materials"
                                    name="material[0][name]"
                                    class="form-control"
                                    value="<?=$this->data['request']['material'][0]['name']?>"
                                    autocomplete="off" required
                                <?=$this->data['is_edit']? 'readonly' : ''?>
                            >
                            <span class="input-group-text">Кол-во:</span>
                            <input type="number" name="material[0][count]" class="form-control material-count" min="1" step="1" required value="<?=$this->data['request']['material'][0]['count']?? 1?>">
                        </div>
                        <input type="hidden" name="material[0][id]" id="material0-hidden" class="material_id" value="<?=$this->data['request']['material'][0]['id']?? ''?>">
                        <datalist id="materials">
                            <?php if (isset($this->data['materials'])): ?>
                                <?php foreach ($this->data['materials'] as $material): ?>
                                    <option data-value="<?=$material['ID']?>"><?=$material['NAME']?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </datalist>
                    </div>
                    <div class="col-sm-2">
                        <?php if ( !$this->data['is_edit'] ): ?>
                            <button class="btn btn-primary add_material btn-add-del" type="button">
                                <i class="fa-solid fa-plus icon-fix"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($this->data['request']['material']) && count($this->data['request']['material']) > 1): ?>
                    <?php for ($i = 1; $i < count($this->data['request']['material']); $i++): ?>
                        <div class="form-group row added_material">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input
                                            type="text"
                                            id="material<?=$i?>"
                                            list="materials"
                                            name="material[<?=$i?>][name]"
                                            class="form-control"
                                            value="<?=$this->data['request']['material'][$i]['name']?>" required
                                        <?=$this->data['is_edit']? 'readonly' : ''?>
                                    >
                                    <span class="input-group-text">Кол-во:</span>
                                    <input type="number" name="material[<?=$i?>][count]" class="form-control material-count" min="1" step="1" required value="<?=$this->data['request']['material'][$i]['count']?? 1?>">
                                </div>
                                <input type="hidden" name="material[<?=$i?>][id]" id="material<?=$i?>-hidden" class="material_id" value="<?=$this->data['request']['material'][$i]['id']?>">
                            </div>
                            <div class="col-sm-2">
                                <?php if ( !$this->data['is_edit'] ): ?>
                                    <button class="btn btn-danger remove_this btn-add-del" type="button">
                                        <i class="fa-solid fa-minus icon-fix"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            <?php endif; ?>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Главный Ответственный <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <!-- <input
                            id="assigned0"
                            class="form-control"
                            type="text"
                            list="assigneds_main"
                            name="ASSIGNED[]"
                            size="20"
                            value="<?=$this->data['request']['assign'][0]['user_name'] ?? ''?>"
                            autocomplete="off"
                            required
                    > -->
                    <select class="form-control assigned-select"
                            id="assigned0"
                            required
                            name="ASSIGNED[]"
                    >
                        <option value="" <?= empty($this->data['request']['assign'][0]['user_id']) ? "selected" : "" ?> disabled>Выберите главного ответственного</option>
                        <?php foreach ($this->data['clients_main'] as $client): ?>
                            <option value="<?=$client['ID']?>" <?= ((int)$this->data['request']['assign'][0]['user_id'] == (int)$client['ID']) ? "selected" : ""?>>
                                <?=$client['LAST_NAME']?> <?=$client['NAME']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input name="id_assign[]" id="assigned0-hidden" type="hidden" class="assigned_id" value="<?=$this->data['request']['assign'][0]['user_id'] ?? ''?>">
                    <!-- <datalist id="assigneds_main">
                        <?php if (isset($this->data['clients_main'])): ?>
                            <?php foreach ($this->data['clients_main'] as $client): ?>
                                <option data-value="<?=$client['ID']?>"><?=$client['LAST_NAME']?> <?=$client['NAME']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist> -->
                    <!-- <datalist id="assigneds">
                        <?php if (isset($this->data['clients'])): ?>
                            <?php foreach ($this->data['clients'] as $client): ?>
                                <option data-value="<?=$client['ID']?>"><?=$client['LAST_NAME']?> <?=$client['NAME']?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist> -->
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary add_assigned btn-add-del" <?= empty($this->data['request']['assign'][0]['user_id']) ? "disabled" : "" ?> type="button">
                        <i class="fa-solid fa-plus icon-fix"></i>
                    </button>
                </div>
            </div>

            <?php if (!empty($this->data['request']['assign']) && count($this->data['request']['assign']) > 1): ?>
                <?php for ($i = 1; $i < count($this->data['request']['assign']); $i++): ?>
                    <div class="form-group row added_assigned">
                        <label class="col-sm-2 col-form-label">Ответственный</label>
                        <div class="col-sm-8">
                            <!-- <input
                                    id="assigned<?=$i?>"
                                    class="form-control"
                                    type="text"
                                    list="assigneds"
                                    name="ASSIGNED[]"
                                    size="20"
                                    value="<?=$this->data['request']['assign'][$i]['user_name']?>"
                                    required
                            > -->
                            <select class="form-control assigned-select"
                                    id="assigned<?=$i?>"
                                    required
                                    name="ASSIGNED[]"
                            >
                                <option value="" <?= empty($this->data['request']['assign'][$i]['user_id']) ? "selected" : "" ?> disabled>Выберите ответственного</option>
                                <?php foreach ($this->data['clients'] as $client): ?>
                                    <option value="<?=$client['ID']?>" <?= ((int)$this->data['request']['assign'][$i]['user_id'] == (int)$client['ID']) ? "selected" : ""?>>
                                        <?=$client['LAST_NAME']?> <?=$client['NAME']?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input name="id_assign[]" id="assigned<?=$i?>-hidden" class="assigned_id" type="hidden" value="<?=$this->data['request']['assign'][$i]['user_id']?>">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-danger remove_this btn-add-del" type="button">
                                <i class="fa-solid fa-minus icon-fix"></i>
                            </button>
                        </div>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>

        </div>
    </div>

    <button class="btn btn-primary block-after-click" type="submit" name="save" value="<?=$this->data['request']['save'] ?? ''?>">Сохранить</button>
</form>
