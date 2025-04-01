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

<div id="error-message"></div>

<form class="form-horizontal" method="post" action="<?=URI?>/request/insertUpdate/">
    <?php if ( isset($this->data['request']['id']) && !empty($this->data['request']['id']) ): ?>
        <input type="hidden" value="<?=$this->data['request']['id']?>" name="id">
    <?php endif; ?>

    <div class="panel panel-default" id="request-data-panel">
        <header class="panel-heading">
            Данные заявки
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Тип заявки <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control" name="REQ_TYPE" id="req-type-select" required>
                        <option value="" selected disabled>Выберите тип заявки</option>
                        <option value="SALE" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] === 'SALE') ? 'selected': ''?>>ИЦ</option>
                        <option value="9" <?=(isset($this->data['request']['REQ_TYPE']) && $this->data['request']['REQ_TYPE'] == '9') ? 'selected': ''?>>Гос работы</option>
                    </select>
                </div>
                <div class="col-sm-2"></div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Клиент/Организация <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <input id="company" class="form-control company-field"
                           list="company_list" type="text" name="company"
                           value="<?=$this->data['request']['company']['TITLE'] ?? htmlspecialchars($this->data['request']['company'] ?? '') ?? ''?>"
                           autocomplete="off" data-conditionally-required="true" required
                    >
                    <input type="hidden" name="company_id" id="company-hidden"
                           value="<?=$this->data['request']['company']['ID'] ?? $this->data['request']['company_id'] ?? ''?>"
                    >
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

            <div class="form-group row" id="main-responsible-block">
                <label class="col-sm-2 col-form-label">Главный Ответственный <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control assigned-select"
                            id="assigned0"
                            required
                            name="ASSIGNED[]"
                    >
                        <option value="" <?= empty($this->data['request']['assign'][0]['user_id']) ? "selected" : "" ?> disabled>Выберите главного ответственного</option>
                        <?php foreach ($this->data['clients_main'] as $client): ?>
                            <option value="<?=$client['ID']?>" <?= ((int)($this->data['request']['assign'][0]['user_id'] ?? 0) == (int)$client['ID']) ? "selected" : ""?>>
                                <?=$client['LAST_NAME']?> <?=$client['NAME']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input name="id_assign[]" id="assigned0-hidden" type="hidden" class="assigned_id" value="<?=$this->data['request']['assign'][0]['user_id'] ?? ''?>">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary add_assigned btn-add-del" <?= empty($this->data['request']['assign'][0]['user_id']) ? "disabled" : "" ?> type="button">
                        <i class="fa-solid fa-plus icon-fix"></i>
                    </button>
                </div>
            </div>
            
            <datalist id="materials">
                <?php if (isset($this->data['materials'])): ?>
                    <?php foreach ($this->data['materials'] as $material): ?>
                        <option data-value="<?=$material['ID']?>"><?=$material['NAME']?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </datalist>
        </div>
    </div>

    <div class="panel panel-default type-specific-block type-sale-block <?=$this->data['display']['sale_materials'] ?? 'visually-hidden'?>" id="sale-materials-block">
        <header class="panel-heading">
            Материалы
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <?php if ( !isset($this->data['request']['id']) ): ?>
                <div class="form-group row" id="material-block">
                    <label class="col-sm-2 col-form-label">Материал для исследования <span class="redStars">*</span></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input
                                    type="text"
                                    id="material0"
                                    list="materials"
                                    name="material[0][name]"
                                    class="form-control"
                                    value="<?=$this->data['request']['material'][0]['name'] ?? ''?>"
                                    autocomplete="off" required
                                <?=$this->data['is_edit']? 'readonly' : ''?>
                            >
                            <span class="input-group-text">Кол-во:</span>
                            <input type="number" name="material[0][count]" class="form-control material-count"
                                   min="1" step="1" required value="<?=$this->data['request']['material'][0]['count'] ?? 1?>"
                            >
                        </div>
                        <input type="hidden" name="material[0][id]" id="material0-hidden"
                               class="material_id" value="<?=$this->data['request']['material'][0]['id'] ?? ''?>"
                        >
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
                                    <input type="number" name="material[<?=$i?>][count]"
                                           class="form-control material-count" min="1" step="1"
                                           value="<?=$this->data['request']['material'][$i]['count'] ?? 1?>"
                                           required
                                    >
                                </div>
                                <input type="hidden" name="material[<?=$i?>][id]"
                                       id="material<?=$i?>-hidden" class="material_id"
                                       value="<?=$this->data['request']['material'][$i]['id'] ?? ''?>"
                                >
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
        </div>
    </div>
    
    <div class="panel panel-default type-specific-block type-gov-block <?=$this->data['display']['gov'] ?? 'visually-hidden'?>">
        <header class="panel-heading">
            Сведения о государственных работах
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Объект</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="object"
                           name="object" placeholder="Наименование объекта"
                           value="<?=$this->data['request']['object'] ?? ''?>"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Сроки заявки</label>
                <div class="col-sm-8">
                    <input type="date" class="form-control"
                           id="gov_deadline" name="gov_deadline"
                           value="<?=$this->data['request']['deadline'] ?? ''?>"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Работы</label>
                <div class="col-sm-10">
                    <div class="table-responsive" style="max-height: none; overflow-y: visible;">
                        <table class="table table-bordered table-sm gov-works-table" id="govWorksTable">
                            <thead>
                                <tr>
                                    <th>Наименование</th>
                                    <th>Объект</th>
                                    <th>Материал</th>
                                    <th>Кол-во</th>
                                    <th>Сроки</th>
                                    <th>Ответственный</th>
                                    <th>Дата выезда</th>
                                    <th>Испытания в лаборатории</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($this->data['request']['application_type'])): ?>
                                    <?php foreach ($this->data['request']['application_type'] as $index => $work): ?>
                                        <tr class="gov-work-row">
                                            <td>
                                                <div class="editable-cell" data-type="text" data-required="true">
                                                    <span class="cell-display"><?= isset($work['name']) ? htmlspecialchars($work['name']) : '' ?></span>
                                                    <input type="text" name="gov_works[name][]"
                                                           class="cell-input visually-hidden form-control-sm"
                                                           value="<?= isset($work['name']) ? htmlspecialchars($work['name']) : '' ?>"
                                                    >
                                                    <input type="hidden" name="gov_works[work_id][]" value="<?= isset($work['id']) ? $work['id'] : '' ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="text">
                                                    <span class="cell-display"><?= isset($work['object']) ? htmlspecialchars($work['object']) : '' ?></span>
                                                    <input type="text" name="gov_works[object][]"
                                                           class="cell-input visually-hidden form-control-sm"
                                                           value="<?= isset($work['object']) ? htmlspecialchars($work['object']) : '' ?>"
                                                    >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="select" data-required="true">
                                                    <span class="cell-display"><?= isset($work['material_name']) ? htmlspecialchars($work['material_name']) : '' ?></span>
                                                    <select name="gov_works[material][]" class="cell-input visually-hidden form-control-sm"
                                                            value="<?= isset($work['material']) ? htmlspecialchars($work['material']) : '' ?>"
                                                    >
                                                        <option value="">Выберите материал</option>
                                                        <?php foreach ($this->data['materials'] as $material): ?>
                                                            <option value="<?= $material['ID'] ?>" <?= isset($work['material_id']) && $work['material_id'] == $material['ID'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($material['NAME']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="number" data-required="true">
                                                    <span class="cell-display"><?= isset($work['quantity']) ? htmlspecialchars($work['quantity']) : '' ?></span>
                                                    <input type="number" name="gov_works[quantity][]"
                                                           class="cell-input visually-hidden form-control-sm"
                                                           value="<?= isset($work['quantity']) ? htmlspecialchars($work['quantity']) : '' ?>"
                                                    >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="date">
                                                    <span class="cell-display"><?= isset($work['deadline']) ? date('d.m.Y', strtotime($work['deadline'])) : '' ?></span>
                                                    <input type="date" name="gov_works[deadline][]"
                                                           class="cell-input visually-hidden form-control-sm"
                                                           value="<?= isset($work['deadline']) ? $work['deadline'] : '' ?>"
                                                    >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="select">
                                                    <span class="cell-display">
                                                        <?php 
                                                        if (!empty($work['assigned_id'])) {
                                                            foreach ($this->data['clients'] as $client) {
                                                                if ($client['ID'] == $work['assigned_id']) {
                                                                    echo htmlspecialchars($client['LAST_NAME'] . ' ' . $client['NAME']);
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </span>
                                                    <select name="gov_works[assigned_id][]" class="cell-input visually-hidden form-control-sm">
                                                        <option value="">Выберите ответственного</option>
                                                        <?php foreach ($this->data['clients'] as $client): ?>
                                                            <option value="<?= $client['ID'] ?>" <?= isset($work['assigned_id']) && $work['assigned_id'] == $client['ID'] ? 'selected' : '' ?>>
                                                                <?= htmlspecialchars($client['LAST_NAME'] . ' ' . $client['NAME']) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="date">
                                                    <span class="cell-display"><?= isset($work['departure_date']) ? date('d.m.Y', strtotime($work['departure_date'])) : '' ?></span>
                                                    <input type="date" name="gov_works[departure_date][]"
                                                           class="cell-input visually-hidden form-control-sm"
                                                           value="<?= isset($work['departure_date']) ? $work['departure_date'] : '' ?>"
                                                    >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="editable-cell" data-type="select">
                                                    <span class="cell-display"><?= isset($work['laboratory_name']) ? htmlspecialchars($work['laboratory_name']) : '' ?></span>
                                                    <select name="gov_works[lab_id][]"
                                                            class="cell-input visually-hidden form-control-sm"
                                                    >
                                                        <option value="">Выберите лабораторию</option>
                                                        <?php if (!empty($this->data['laboratories'])): ?>
                                                            <?php foreach ($this->data['laboratories'] as $lab): ?>
                                                                <option value="<?= $lab['ID'] ?>" <?= isset($work['lab_id']) && (int)$work['lab_id'] == (int)$lab['ID'] ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($lab['NAME']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-gov-work">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-2">
                        <button type="button" class="btn btn-primary btn-sm" id="addGovWork">
                            <i class="fas fa-plus"></i> Добавить работу
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default type-specific-block type-sale-block <?=$this->data['display']['sale'] ?? 'visually-hidden'?>">
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
                <label class="col-sm-2 col-form-label">ИП</label>
                <div class="col-sm-8">
                    <input class="form-check-input check-ip" type="checkbox"
                           name="check_ip" value="1" <?=$this->data['request']['check_ip'] == 1? 'checked' : ''?>
                    >
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
                    <input type="text" name="INN" maxlength="12"
                           class="form-control number-only appearance-none clearable"
                           value="<?=$this->data['request']['INN'] ?? ''?>"
                    >
                    <div id="innHelp" class="form-text"></div>
                </div>
                <div class="col-sm-2"></div>
            </div>

			<div class="form-group row">
				<label class="col-sm-2 col-form-label">КПП</label>
				<div class="col-sm-8">
					<input type="text" name="KPP" maxlength="9"
                           class="form-control number-only clearable"
                           value="<?=$this->data['request']['KPP'] ?? ''?>"
                    >
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
                    <input type="email" name="POST_MAIL" class="form-control clearable" placeholder="_@_._" data-conditionally-required="true" value="<?=$this->data['request']['POST_MAIL'] ?? ''?>">
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
                    <input type="text" name="PHONE" class="form-control clearable" data-conditionally-required="true" value="<?=$this->data['request']['PHONE'] ?? ''?>">
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

    <button class="btn btn-primary block-after-click" type="submit" name="save" value="<?=$this->data['request']['save'] ?? ''?>">Сохранить</button>
</form>