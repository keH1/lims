<?php if ( $this->data['is_show_btn'] ): ?>
    <header class="header-requirement mb-3">
        <nav class="header-menu">
            <ul class="nav">
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?=URI?>/import/organizationList/" title="Вернуться к журналу организаций">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
<?php endif; ?>

<form action="/ulab/import/orgUpdate" method="post" class="form-horizontal">
    <div class="panel panel-default">
        <header class="panel-heading">
            Общая информация
            <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
        </header>
        <div class="panel-body">

                <input type="hidden" name="org_id" id="org_id" value="<?=$this->data['info']['id']?>">

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Наименование *</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="form[name]" value="<?=$this->data['info']['name']?>" required>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Руководитель</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" name="form[head_user_id]">
                            <option value="">Не выбран</option>
                            <?php foreach ($this->data['users'] as $user): ?>
                                <option value="<?=$user['ID']?>" <?=$this->data['info']['head_user_id'] == $user['ID'] ? 'selected' : ''?>><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>

    <div class="panel panel-default">
        <header class="panel-heading">
            Реквизиты
            <span class="tools float-end">
                <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                <a href="javascript:;" class="fa fa-chevron-down"></a>
            </span>
        </header>
        <div class="panel-body" style="display: none">
            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Полное наименование организации</label>
                <div class="col-sm-8">
                    <input type="text" name="form[full_name]" class="form-control clearable" placeholder="Введите полное наименование"
                           value="<?= $this->data['info']['full_name'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ИП</label>
                <div class="col-sm-8">
                    <input class="form-check-input check-ip" type="checkbox" name="form[ip]" placeholder="Введите ИП"
                           value="1" <?= $this->data['info']['ip'] == 1 ? 'checked' : '' ?>>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Сайт</label>
                <div class="col-sm-8">
                    <input type="text" name="form[website]" class="form-control clearable" placeholder="Введите сайт"
                           value="<?= $this->data['info']['website'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Номер записи</label>
                <div class="col-sm-8">
                    <input type="text" name="form[ross_number]" class="form-control clearable" placeholder="Введите номер записи"
                           value="<?= $this->data['info']['ross_number'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ИНН</label>
                <div class="col-sm-8">
                    <input type="number" name="form[inn]" class="form-control appearance-none clearable" placeholder="Введите ИНН"
                           value="<?= $this->data['info']['inn'] ?? '' ?>">
                    <div id="innHelp" class="form-text"></div>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">КПП</label>
                <div class="col-sm-8">
                    <input type="text" name="form[kpp]" id="kpp" class="form-control number-only clearable" <?= $this->data['info']['ip'] == 0 ? '' : 'disabled' ?> placeholder="Введите КПП"
                           value="<?= $this->data['info']['kpp'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label for="ogrnip" class="col-sm-2 col-form-label">ОГРНИП</label>
                <div class="col-sm-8">
                    <input type="text" name="form[ogrnip]" id="ogrnip" placeholder="Введите ОГРНИП"
                           class="form-control number-only clearable" <?= $this->data['info']['ip'] == 1 ? '' : 'disabled' ?>
                           value="<?= $this->data['info']['ogrnip'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ОГРН</label>
                <div class="col-sm-8">
                    <input type="text" name="form[ogrn]" id="ogrn" class="form-control number-only clearable" <?= $this->data['info']['ip'] == 0 ? '' : 'disabled' ?> placeholder="Введите ОГРН"
                           value="<?= $this->data['info']['ogrn'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Город (для договора)</label>
                <div class="col-sm-8">
                    <input type="text" name="form[city]" class="form-control clearable" placeholder="Введите город"
                           value="<?= $this->data['info']['city'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Адрес</label>
                <div class="col-sm-8">
                    <input type="text" name="form[addr]" class="form-control clearable" placeholder="Введите адрес"
                           value="<?= $this->data['info']['addr'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Фактический адрес</label>
                <div class="col-sm-8">
                    <input type="text" id="actual_address" name="form[actual_address]" placeholder="Введите фактический адрес"
                           class="form-control clearable"
                           value="<?= $this->data['info']['actual_address'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Почтовый адрес</label>
                <div class="col-sm-8">
                    <input type="text" name="form[mailing_address]" class="form-control clearable" placeholder="Введите почтовый адрес"
                           value="<?= $this->data['info']['mailing_address'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">E-mail для договора</label>
                <div class="col-sm-8">
                    <input type="email" name="form[email]" class="form-control clearable" placeholder="_@_._"
                           value="<?= $this->data['info']['email'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">E-mail</label>
                <div class="col-sm-8">
                    <input type="email" name="form[post_mail]" class="form-control clearable" placeholder="_@_._"
                           value="<?= $this->data['info']['post_mail'] ?? '' ?>">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-primary add-email btn-add-del" type="button">
                        <i class="fa-solid fa-plus icon-fix"></i>
                    </button>
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Телефон</label>
                <div class="col-sm-8">
                    <input type="text" name="form[phone]" class="form-control clearable" placeholder="Введите телефон"
                           value="<?= $this->data['info']['phone'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Контактное лицо</label>
                <div class="col-sm-8">
                    <input type="text" name="form[contact_person]" class="form-control clearable" placeholder="Введите контактное лицо"
                           value="<?= $this->data['info']['contact_person'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Расчетный счёт</label>
                <div class="col-sm-8">
                    <input type="text" name="form[bank_account]" class="form-control clearable" placeholder="Введите расчетный счёт"
                           value="<?= $this->data['info']['bank_account'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Кор. счёт</label>
                <div class="col-sm-8">
                    <input type="text" name="form[correspondent_account]" class="form-control clearable" placeholder="Введите кор. счёт"
                           value="<?= $this->data['info']['correspondent_account'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Лицевой счёт</label>
                <div class="col-sm-8">
                    <input type="text" name="form[personal_account]" class="form-control clearable" placeholder="Введите лицевой счёт"
                           value="<?= $this->data['info']['personal_account'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">БИК</label>
                <div class="col-sm-8">
                    <input type="text" name="form[bik]" class="form-control clearable" placeholder="Введите БИК"
                           value="<?= $this->data['info']['bik'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ОКПО</label>
                <div class="col-sm-8">
                    <input type="text" name="form[okpo]" class="form-control clearable" placeholder="Введите ОКПО"
                           value="<?= $this->data['info']['okpo'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ОКВЭД</label>
                <div class="col-sm-8">
                    <input type="text" name="form[okved]" class="form-control clearable" placeholder="Введите ОКВЭД"
                           value="<?= $this->data['info']['okved'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ОКОНХ</label>
                <div class="col-sm-8">
                    <input type="text" name="form[okonh]" class="form-control clearable" placeholder="Введите ОКОНХ"
                           value="<?= $this->data['info']['okonh'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">ТОФК</label>
                <div class="col-sm-8">
                    <input type="text" name="form[tofk]" class="form-control clearable" placeholder="Введите ТОФК"
                           value="<?= $this->data['info']['tofk'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">OKTMO</label>
                <div class="col-sm-8">
                    <input type="text" name="form[oktmo]" class="form-control clearable" placeholder="Введите ОКТМО"
                           value="<?= $this->data['info']['oktmo'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">КБК</label>
                <div class="col-sm-8">
                    <input type="text" name="form[kbk]" class="form-control clearable" placeholder="Введите КБК"
                           value="<?= $this->data['info']['kbk'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Получатель</label>
                <div class="col-sm-8">
                    <input type="text" name="form[recipient]" class="form-control clearable" placeholder="Введите получателя"
                           value="<?= $this->data['info']['recipient'] ?? '' ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-sm-2 col-form-label">Наименование банка</label>
                <div class="col-sm-8">
                    <input type="text" name="form[bank_name]" class="form-control clearable" placeholder="Введите наименование банка"
                           value="<?= htmlentities($this->data['info']['bank_name'] ?? '') ?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>

<div class="panel panel-default">
    <header class="panel-heading">
        Департаменты
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <a href="#popup_form" class="popup-with-form btn btn-success mb-2">Добавить</a>

        <table id="journal_branch" class="table table-striped journal">
            <thead>
            <tr class="table-light">
                <th scope="col" class="text-nowrap">Название</th>
                <th scope="col" class="text-nowrap"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<form id="popup_form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/branchInsertUpdate" method="post">
    <div class="title mb-3 h-2">
        Данные департамента
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" id="form_entity_id" name="branch_id" value="">
    <input type="hidden" name="form[organization_id]" value="<?=$this->data['info']['id']?>">

    <div class="mb-3">
        <label class="form-label" for="form_entity_name">Наименование *</label>
        <input type="text" class="form-control" id="form_entity_name" name="form[name]" value="" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_head">Руководитель</label>
        <select id="form_entity_head" class="form-control select2" name="form[head_user_id]">
            <option value="">Не выбран</option>
            <?php foreach ($this->data['users'] as $user): ?>
                <option value="<?=$user['ID']?>"><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>