<header class="header-requirement mb-4">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?= URI ?>/request/card/<?=$this->data['deal_id']?>" title="Вернуться в карточку">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link" href="<?= URI ?>/request/list/<?=$this->data['comm']??''?>" title="Вернуться в журнал заявок">
                    <i class="fa-solid fa-list"></i>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link link-card" href="#">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?=URI?>/assets/images/icons.svg#card"/>
                    </svg>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link link-docs" href="#">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?=URI?>/assets/images/icons.svg#docs"/>
                    </svg>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link link-doc-edit" href="#">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?=URI?>/assets/images/icons.svg#doc-edit"/>
                    </svg>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link popup-help" href="/ulab/help/LIMS_Manual_Stand/Technical_spec_int/Tec_spec_int.html" title="Техническая поддержка">
                    <i class="fa-solid fa-question"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>


<div class="wrapper-requirement m-auto">
    <h2 class="d-flex mb-3">
        Заявка <?= $this->data['deal_title'] ?? '' ?>
    </h2>

    <?php if ($this->data['tz']['TYPE_ID'] == '9'): ?>
    <div class="panel panel-default">
        <header class="panel-heading">
            Работы
            <span class="tools float-end">
                <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                <a href="#" class="fa fa-chevron-up"></a>
             </span>
        </header>
        <div class="panel-body">
            <table id="work_table" class="table">
                <thead>
                <tr>
                    <th scope="col" class="text-center">
                        <input type="radio" class="form-check-input work_radio" name="work_radio" value="" checked>
                    </th>
                    <th scope="col">Гос работа (наименование)</th>
                    <th scope="col">Объект</th>
                    <th scope="col">Материал</th>
                    <th scope="col">Кол-во материала</th>
                    <th scope="col">Испытание в лаборатории (статус)</th>
                    <th scope="col">Результаты испытаний</th>
                    <th scope="col">Протокол испытаний</th>
                    <th scope="col">Дата протоколов</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->data['work_list'] as $row): ?>
                <tr>
                    <td class="text-center">
                        <input type="radio" class="form-check-input work_radio" name="work_radio" id="work_radio_<?=$row['id']?>" value="<?=$row['id']?>">
                    </td>
                    <td>
                        <label for="work_radio_<?=$row['id']?>"><?=$row['name']?></label>
                    </td>
                    <td>
                        <?=$row['object']?>
                    </td>
                    <td>
                        <?=$row['material_name']?>
                    </td>
                    <td>
                        <?=$row['probe_count']?>
                    </td>
                    <td>
                        <?=$row['work_status']?? 'Испытания не начаты'?>
                    </td>
                    <td class="text-center">
                        <?php if ( empty($row['file_name_result']) ): ?>
                            <form class="form form-upload-file" method="post"
                                  action="#"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="work_id" value="<?=$row['id']?>">
                                <input type="hidden" name="deal_id" value="<?= $this->data['deal_id'] ?>">
                                <label class="btn btn-sm btn-success" title="Загрузить результаты испытаний">
                                    Добавить
                                    <input class="d-none" type="file" name="file_result" accept=".doc, .docx, .xls, .xlsx, .pdf">
                                </label>
                            </form>
                        <?php else: ?>
                            <a href="/ulab/upload/request/<?=$this->data['deal_id']?>/government_work/<?=$row['id']?>/result/<?=$row['file_name_result']?>">
                                <?=$row['file_name_result']?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ( empty($row['file_name_protocol']) ): ?>
                            <form class="form form-upload-file" method="post"
                                  action="#"
                                  enctype="multipart/form-data">
                                <input type="hidden" name="work_id" value="<?=$row['id']?>">
                                <input type="hidden" name="deal_id" value="<?= $this->data['deal_id'] ?>">
                                <label class="btn btn-sm btn-success" title="Загрузить протокол испытаний">
                                    Добавить
                                    <input class="d-none" type="file" name="file_protocol" accept=".doc, .docx, .xls, .xlsx, .pdf">
                                </label>
                            </form>
                        <?php else: ?>
                            <a href="/ulab/upload/request/<?=$this->data['deal_id']?>/government_work/<?=$row['id']?>/protocol/<?=$row['file_name_protocol']?>">
                                <?=$row['file_name_protocol']?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="text-center text_date_protocol">
                        <?= empty($row['date_protocol'])? '' : date('d.m.Y', strtotime($row['date_protocol'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr id="work_table_last_row">
                    <td class="text-center">
                        <a href="#add-work-modal-form" class="popup-with-form btn btn-success btn-square add-work" title="Добавить работу">
                            <i class="fa-solid fa-plus icon-fix"></i>
                        </a>
                    </td>
                    <td colspan="8"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <form class="form form-requirement" id="form_requirement" method="post" action="<?=URI?>/requirement/updateTz/">

        <input type="hidden" id="tz_id" name="tz_id" value="<?= $this->data['tz_id'] ?>">
        <input type="hidden" id="deal_id" name="deal_id" value="<?= $this->data['deal_id'] ?>">
        <input type="hidden" id="clear_confirm" name="clear_confirm" value="0">

        <div class="panel panel-default">
            <header class="panel-heading">
                Общая информация
                <span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="#" class="fa fa-chevron-up"></a>
                 </span>
            </header>
            <div class="panel-body">
                <div class="wrapper-info-header">

                    <div class="row mb-3">
                        <div class="col-sm-6">
                            <label class="form-label mb-1">Основание для проведения испытаний</label>
                            <div>
                                <strong>
                                <?php if ($this->data['tz']['TYPE_ID'] == '9'): ?>
                                    <?php if ( !empty($this->data['contract_number']) ): ?>
                                        <?= $this->data['contract_type'] ?> №<?= $this->data['contract_number'] ?> от <?= $this->data['contract_date'] ?>
                                    <?php else: ?>
                                        Договор еще не составлен
                                    <?php endif; ?>
                                <?php else: ?>
                                    Экспертное задание
                                <?php endif; ?>
                                </strong>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label mb-1">Основание для формирования протокола</label>
                            <div class="formation-protocol-reason">
                                <strong>
                                    <?php if (!empty($this->data['act_number'])): ?>
                                        Акт приемки/передачи проб № <?= $this->data['act_number'] ?> от <?= $this->data['act_date'] ?>
                                    <?php else: ?>
                                        Пробы не поступили
                                    <?php endif; ?>
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label class="form-label mb-1" for="day_to_test">Срок проведения испытаний</label>
                            <div class="input-group mb-3">
                                <input type="number" class="clear_confirm_change form-control number-only day-to-test" id="day_to_test" name="tz[DAY_TO_TEST]" value="<?= $this->data['tz']['DAY_TO_TEST']?? 20 ?>" aria-describedby="basic-addon2">
                                <select class="input-group-text col-3 clear_confirm_change" id="basic-addon2" name="tz[type_of_day]">
                                    <option value="work_day" <?=$this->data['tz']['type_of_day'] == 'work_day' ? 'selected' : ''?>>рабочих дней</option>
                                    <option value="day" <?=$this->data['tz']['type_of_day'] == 'day' ? 'selected' : ''?>>дней</option>
                                    <option value="month" <?=$this->data['tz']['type_of_day'] == 'month' ? 'selected' : ''?>>месяц(ев)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!--./wrapper-info-header-->

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        Испытания проводятся
                    </label>
                    <div class="col-2 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tz[tests_for]" id="inlineRadio1" value="own" <?=$this->data['tz']['tests_for'] == 'own' || empty($this->data['tz']['tests_for']) ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio1">для собственных нужд</label>
                        </div>
                    </div>
                    <div class="col-6 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tz[tests_for]" id="inlineRadio3" value="certification" <?=$this->data['tz']['tests_for'] == 'certification' ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio3">для предоставления в орган по сертификации (сертификация, декларация продукции)</label>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        Указывать соответствие
                    </label>
                    <div class="col-sm-2 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tz[is_indicate_compliance]" id="inlineRadio21" value="0" <?=$this->data['tz']['is_indicate_compliance'] == 0 ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio21">нет</label>
                        </div>
                    </div>
                    <div class="col-sm-2 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tz[is_indicate_compliance]" id="inlineRadio22" value="1" <?=$this->data['tz']['is_indicate_compliance'] == 1 ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio22">да (указать)</label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" id="compliance" maxlength="255" name="tz[compliance]" value="<?=$this->data['tz']['compliance']?? ''?>" placeholder="ГОСТ ТУ" <?=$this->data['tz']['is_indicate_compliance'] != 1 ? 'disabled' : '' ?>>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        Результаты испытаний (исследований) оформить
                    </label>
                    <div class="col-sm-2 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tz[presented_as]" id="inlineRadio31" value="protocol" <?=$this->data['tz']['presented_as'] == 'protocol' || empty($this->data['tz']['presented_as']) ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio31">протокол испытаний по ГОСТ Р 58973</label>
                        </div>
                    </div>
                    <div class="col-sm-2 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tz[presented_as]" id="inlineRadio32" value="another" <?=$this->data['tz']['presented_as'] == 'another' ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio32">протокол испытаний по форме Заказчика</label>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">
                        Необходимые сведения и документы для проведения испытаний (см. п.2.2.4)
                    </label>
                    <div class="col-sm-8 pt-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="tz[add_info][]" id="inlineRadio41" value="passport" <?=in_array('passport', $this->data['tz']['add_info']) ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio41">особые сведения о физических, химических характеристиках продукции (состав, направления формования и т.д.)</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="tz[add_info][]" id="inlineRadio42" value="tu" <?=in_array('tu', $this->data['tz']['add_info']) ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio42">ТУ на производство продукции</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="tz[add_info][]" id="inlineRadio43" value="consist" <?=in_array('consist', $this->data['tz']['add_info']) ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio43">документ о качестве продукции</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="tz[add_info][]" id="inlineRadio44" value="another" <?=in_array('another', $this->data['tz']['add_info']) ? 'checked' : ''?>>
                            <label class="form-check-label" for="inlineRadio44">иное</label>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>
            <!--./panel-body-->
        </div>



        <div class="panel panel-default">
            <header class="panel-heading">
                Дополнительная информация
                <span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="#" class="fa fa-chevron-up"></a>
                 </span>
            </header>
            <div class="panel-body">
                <div class="wrapper-add-info mt-2 --flex-column">
                    <div class="row row-cols-2">
                        <div class="form-group col">
                            <label class="form-label mb-1" for="infoObject">Объект строительства</label>
                            <textarea class="form-control mw-100 clear_confirm_change" name="tz[OBJECT]"><?= $this->data['tz']['OBJECT'] ?></textarea>
                        </div>

                        <?php if ($this->data['tz']['TYPE_ID'] != '9'): ?>
                            <div class="form-group col">
                                <label class="form-label mb-1" for="commentKp">Комментарий к КП</label>
                                <textarea class="form-control mw-100 comment-kp clear_confirm_change" id="commentKp" name="tz[COMMENT_KP]" placeholder="Введите текст"><?= $this->data['tz']['COMMENT_KP'] ?></textarea>
                            </div>
                        <?php endif; ?>

                        <div class="form-group col">
                            <label class="form-label mb-1" for="commentTz">Комментарий к ТЗ</label>
                            <textarea class="form-control mw-100 comment-requirement clear_confirm_change" id="commentTz" name="tz[COMMENT_TZ]" placeholder="Введите текст"><?= $this->data['tz']['COMMENT_TZ'] ?></textarea>
                        </div>

                        <div class="form-group col">
                            <div class="row">
                                <?php if ($this->data['requirement']['creation_stage'] !== 'new'): ?>
                                    <div class="col-sm-9">
                                        <label class="form-label mb-1">Заявка учтена</label>
                                        <select class="form-control select2 clear_confirm_change" name="tz[TAKEN_ID_DEAL]">
                                            <option value="">Нет</option>
                                            <?php foreach ($this->data['requests_to_company'] as $request): ?>
                                                <option value="<?= $request['ID_Z'] ?>" <?= $this->data['tz']['TAKEN_ID_DEAL'] == $request['ID_Z'] ? 'selected' : '' ?>>
                                                    Заявка <?= $request['REQUEST_TITLE'] ?>, <?= $request['COMPANY_TITLE'] ?>, от <?= $request['DATE_CREATE'] ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <div class="col-sm-3">
                                    <label class="form-label mb-1">Серт. испытания</label>
                                    <div class="d-flex align-items-center taken-request-wrapper">
                                        <div>
                                            <label class="switch">
                                                <input class="form-check-input clear_confirm_change" name="tz[TAKEN_SERT_ISP]" type="checkbox" value="1"
                                                    <?= $this->data['tz']['TAKEN_SERT_ISP'] == 1 ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                        <input type="hidden" class="hidden-taken">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--./wrapper-add-info-->
            </div>
            <!--./panel-body-->
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                Объект испытаний
                <span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="javascript:;" class="fa fa-chevron-up"></a>
                </span>
            </div>
            <div class="panel-body">
                <div class="row flex-nowrap">

                    <div class="col-auto">
                        <div class="js-sticky-widget2">
                            <?php if ($this->data['tz']['TYPE_ID'] != '9'): ?>
                                <div class="col-auto mb-3">
                                    <a href="#add-material-modal-form" class="popup-with-form btn btn-success w125 btn-sm">
                                        <i class="fa-solid fa-plus icon-fix"></i> Объект
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="col-auto mb-3">
                                <a href="#add-probe-modal-form" class="popup-with-form btn btn-success w125 btn-sm">
                                    <i class="fa-solid fa-plus icon-fix"></i> Пробу
                                </a>
                            </div>

                            <div class="col-auto mb-3 d-none">
                                <a href="#edit-group-probe-modal-form" class="btn-group-edit popup-with-form btn btn-success w125 btn-sm disabled">
                                    <i class="fa-solid fa-pencil"></i> Группы
                                </a>
                            </div>

                            <div class="col-auto mb-3">
                                <a href="#add-methods-modal-form" class="btn-add-methods popup-with-form btn btn-primary w125 btn-sm disabled">
                                    <i class="fa-solid fa-plus icon-fix"></i> Испытания
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col flex-grow-1 min-w-0">
                        <div class="input-group mb-2">
                            <span class="input-group-text">Показать: </span>
                            <select id="filter-material" class="form-control select2 filter">
                                <option value="">Все объекты испытаний</option>
                                <?php foreach ($this->data['tz_material_list'] as $id => $materialName): ?>
                                    <option value="<?=$id?>"><?=$materialName?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <table id="journal_material_2" class="table table-striped journal table-hover table-sm table-light">
                            <thead>
                            <tr>
                                <th scope="col">Объект испытаний</th>
                                <th scope="col"></th>
                                <th scope="col">Проба</th>
                                <th scope="col">Маркировка заказчика (информация об объекте испытания)</th>
                                <th scope="col">Кол-во методик</th>
                                <th scope="col"></th>
                            </tr>
                            <tr class="header-search">
                                <th scope="col"></th>
                                <th scope="col" colspan="5">
                                    <input id="filter-cipher" type="text" class="form-control search filter" placeholder="Фильтр по шифру">
                                </th>
                            </tr>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($this->data['tz']['TYPE_ID'] != '9'): ?>
                <div class="line-dashed"></div>

                <div class="wrapper-discount bg-light-secondary p-2">
                    <div class="row justify-content-end">
                        <div class="col-auto d-flex flex-column">
                            <label class="form-label mb-1">Итого</label>
                            <span id="str_total" class="total mt-2"><?= $this->data['tz']['price_ru'] ?></span>
                            <input id="price-total" type="hidden" name="tz[PRICE]" value="<?= $this->data['tz']['PRICE'] ?>">
                            <input id="price_discount" type="hidden" name="tz[price_discount]" value="<?= $this->data['tz']['price_discount'] ?? $this->data['tz']['PRICE'] ?>">
                        </div>

                        <div class="form-group col-auto">
                            <label class="form-label" for="input_discount">Скидка</label>
                            <div class="input-group">
                                <input name="tz[DISCOUNT]" type="number" class="form-control bg-white discount-input clear_confirm_change" min="0" value="<?= $this->data['tz']['DISCOUNT']?? '0' ?>">
                                <select name="tz[discount_type]" class="form-control bg-white discount-type clear_confirm_change">
                                    <option value="percent" <?=$this->data['tz']['discount_type'] == 'percent'? 'selected' : ''?>>%</option>
                                    <option value="rub" <?=$this->data['tz']['discount_type'] == 'rub'? 'selected' : ''?>>₽</option>
                                </select>
                                <button type="button" class="btn btn-primary discount-apply">Применить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Подтверждение ТЗ
                <span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="javascript:;" class="fa fa-chevron-up"></a>
                </span>
            </div>
            <div class="panel-body">
                <?php if (!empty($this->data['lab_head']['user'])): ?>
                    <?php foreach ($this->data['lab_head']['user'] as $user): ?>
                        <div class="head-user-block <?=$user['user_id'] == $this->data['curr_user']? 'curr_user' : ''?>">
                            <?php if ( $user['is_confirm'] == CHECK_TZ_NOT_SENT && $this->data['check_state'] != CHECK_TZ_NOT_SENT ): ?>
                                <span class="icon" title="ТЗ не отправлено">
                                    <i class="fa-solid fa-minus"></i>
                                </span>
                            <?php elseif ( $user['is_confirm'] == CHECK_TZ_NOT_SENT ): ?>
                                <span class="icon" title="ТЗ не отправлено">
                                    <i class="fa-regular fa-paper-plane"></i>
                                </span>
                            <?php elseif ($user['is_confirm'] == CHECK_TZ_APPROVE): ?>
                                <span class="text-green icon" title="ТЗ потверждено">
                                    <i class="fa-regular fa-circle-check"></i>
                                </span>
                            <?php elseif ($user['is_confirm'] == CHECK_TZ_NOT_APPROVE): ?>
                                <span class="text-red icon" title="ТЗ не потверждено">
                                    <i class="fa-regular fa-circle-xmark"></i>
                                </span>
                            <?php else: ?>
                                <span class="icon" title="Ожидание проверки">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </span>
                            <?php endif; ?>

                            <span class="<?=$user['user_id'] == $this->data['curr_user']? 'fw-bold' : ''?>"><?=$user['short_name'];?></span>
                        </div>
                    <?php endforeach; ?>


                    <?php if (!empty($this->data['lab_head']['user']) && $this->data['check_state'] == CHECK_TZ_APPROVE): ?>
                        <div class="mt-1">
                            <label class="form-label text-green fw-bold">Техническое задание утверждено.</label>
                        </div>
                    <?php endif;?>


                    <div class="line-dashed"></div>

                    <?php if ($this->data['lab_head']['is_curr_user']): ?>
                        <?php if ($this->data['check_state'] == CHECK_TZ_NOT_SENT): ?>
                                <button type="button"
                                        class="btn btn-primary sent_approve_tz <?=$this->data['lab_head']['check_state'] == CHECK_TZ_NOT_SENT? '': 'disable'?>"
                                ><i class="fa-regular fa-paper-plane"></i> Передать и утвердить</button>
                        <?php else: ?>
                            <button type="button"
                                    class="btn btn-success me-3 approve_tz
                                    <?=$this->data['check_state'] == CHECK_TZ_WAIT && $this->data['lab_head']['curr_user_status'] != 1? '': 'disable'?>"
                            ><i class="fa-regular fa-circle-check"></i> Утвердить</button>
                            <a href="#return-modal-form"
                                    class="btn btn-danger me-3 not_approve_tz popup-with-form
                                    <?=$this->data['check_state'] == CHECK_TZ_WAIT && $this->data['lab_head']['curr_user_status'] != 1? '': 'disable'?>"
                            ><i class="fa-regular fa-circle-xmark"></i> Вернуть</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="#send-modal-form"
                           class="btn btn-primary popup-with-form <?=$this->data['check_state'] == CHECK_TZ_NOT_SENT? '': 'disable'?>"
                        ><i class="fa-regular fa-paper-plane"></i> Передать</a>
                    <?php endif; ?>

                <?php else: ?>
                    <span class="fw-bold">Сохраните техническое задание</span>
                <?php endif; ?>
            </div>
            <!--./panel-body-->
        </div>

        <?php if (!empty($this->data['lab_head']['user']) && $this->data['check_state'] != CHECK_TZ_NOT_SENT): ?>
            <label class="form-label text-red">Техническое задание на проверке. При нажатии "Сохранить" отзовет проверку</label>
            <button class="form-control btn btn-primary mw-100 save" id="save" name="save" onclick="return confirm('Техническое задание на проверке. При нажатии Сохранить отзовет проверку! Продолжить?')" type="submit">Сохранить</button>
        <?php else: ?>
            <button class="form-control btn btn-primary mw-100 save" id="save" name="save" type="submit">Сохранить</button>
        <?php endif;?>

	</form>
    <!--./form-requirement-->
</div>
<!--./wrapper-requirement-->


<form id="edit-group-probe-modal-form" class="bg-light mfp-hide col-6 m-auto p-3 position-relative" method="post" action="<?=URI?>/requirement/">
    <div class="title mb-3 h-2">
        Групповое редактирование проб
    </div>

    <div class="line-dashed-small"></div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">
    <input class="probe-id-list" name="probe_id_list" value="" type="hidden">

    <div class="row mb-3">
        <label for="inputEmail3" class="col col-form-label">Выбрано проб: <span class="count-selected-probe"></span></label>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>


<form id="edit-probe-modal-form" class="bg-light mfp-hide col-6 m-auto p-3 position-relative" method="post" action="<?=URI?>/requirement/editProbe">
    <div class="title mb-3 h-2">
        Редактирование пробы
    </div>

    <div class="line-dashed-small"></div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">
    <input name="probe_id" class="probe_id" value="" type="hidden">

    <div class="mb-3">
        <label class="form-label">Маркировка заказчика (информация об объекте испытания)</label>

        <input type="text" name="form[name_for_protocol]" class="form-control name_for_protocol">
    </div>

    <div class="mb-3">
        <label class="form-label">Группа объекта испытаний</label>

        <select class="form-control select2 select_group clear_confirm_change" name="form[group]">
            <option value="">Без группы</option>
        </select>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex justify-content-between">
        <button type="submit" name="button" value="save" class="btn btn-primary">Сохранить</button>

        <button type="submit" name="button" value="delete" class="btn btn-danger delete-probe-btn">Удалить</button>
    </div>
</form>


<form id="add-methods-modal-form" class="bg-light mfp-hide col-9 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Добавление методик
    </div>

    <div class="line-dashed-small"></div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">
    <input class="probe-id-list" name="probe_id_list" value="" type="hidden">
    <input name="scheme_id" value="<?=$this->data['scheme_id'] ?>" id="scheme_id" type="hidden">

    <div class="row mb-3">
        <label for="inputEmail3" class="col col-form-label">Выбрано проб: <span class="count-selected-probe"></span></label>
    </div>

    <div class="mb-3">
        <label class="form-label">Схема</label>
        <div class="input-group">
            <select class="form-control select2" id="select-scheme">
                <option value="">Нет схемы / ручной ввод</option>
            </select>
            <button type="button" class="btn btn-primary" id="apply-scheme">Применить</button>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <label class="form-label mb-1">Методика испытаний <span class="redStars">*</span></label>
        </div>
        <div class="col-4">
            <label class="form-label mb-1">Нормативная документация</label>
        </div>
        <div class="col-2">
            <label class="form-label mb-1">Исполнитель</label>
        </div>
        <div class="col">
            <label class="form-label mb-1">Цена</label>
        </div>
    </div>

    <div class="method-container mb-3">

    </div>

    <div class="row justify-content-center">
        <div class="col-auto">
            <button type="button" class="btn btn-success w150 add-new-method">
                <i class="fa-solid fa-plus icon-fix"></i> Методику
            </button>
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Добавить</button>
</form>


<form id="add-material-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" method="post" action="<?=URI?>/requirement/addMaterialToTz/">
    <div class="title mb-3 h-2">
        Добавление объекта испытаний
    </div>

    <div class="line-dashed-small"></div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="mb-3">
        <label class="form-label">Объект испытаний <span class="redStars">*</span></label>

        <select class="form-control select2" name="material_id" required>
            <option value="">Выбрать объект испытаний</option>
            <?php foreach ($this->data['material_list'] as $material): ?>
                <option value="<?=$material['ID']?>"><?=$material['NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Количество проб <span class="redStars">*</span></label>

        <input type="number" class="form-control bg-white" name="number" min="1" value="1" required>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Добавить</button>
</form>


<form id="add-probe-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" method="post" action="<?=URI?>/requirement/addProbeToMaterial/">
    <div class="title mb-3 h-2">
        Добавление проб
    </div>

    <div class="line-dashed-small"></div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="mb-3">
        <label class="form-label">Объект испытаний (мультивыбор) <span class="redStars">*</span></label>

        <?php $selected = count($this->data['tz_material_list']) == 1? 'selected': '' ?>
        <select class="form-control select2" name="material_id[]" multiple required>
            <option value="" disabled>Выбрать объект испытаний</option>
            <?php foreach ($this->data['tz_material_list'] as $id => $materialName): ?>
                <option value="<?=$id?>" <?=$selected?>><?=$materialName?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Количество проб <span class="redStars">*</span></label>

        <input type="number" name="number" class="form-control bg-white" min="1" value="1" required>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Добавить</button>
</form>


<div id="return-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Причина возврата
    </div>

    <div class="line-dashed-small"></div>

    <textarea class="form-control" id="desc_return" rows="5" required></textarea>

    <div class="line-dashed-small"></div>

    <button type="button" class="btn btn-primary not_approve_tz_btn">Отправить</button>
</div>


<form id="send-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/requirement/confirmTzSent/" method="post">
    <div class="title mb-3 h-2">
        Отправка руководителям на проверку
    </div>

    <div class="line-dashed-small"></div>

    <?php if (!empty($this->data['lab_head']['user'])): ?>
        <?php foreach ($this->data['lab_head']['user'] as $user): ?>
            <div class="head-user-block form-check <?=$user['user_id'] == $this->data['curr_user']? 'curr_user' : ''?>">
                <input id="send_user_<?=$user['user_id']?>" type="checkbox" name="users[]" class="form-check-input" value="<?=$user['user_id']?>" checked>

                <label class="form-check-label" for="send_user_<?=$user['user_id']?>">
                    <span class="<?=$user['user_id'] == $this->data['curr_user']? 'fw-bold' : ''?>"><?=$user['short_name'];?></span>
                </label>
            </div>
        <?php endforeach; ?>

        <?php if (!empty($this->data['lab_head']['user']) && $this->data['check_state'] == CHECK_TZ_APPROVE): ?>
            <div class="mt-1">
                <label class="form-label text-green fw-bold">Техническое задание утверждено.</label>
            </div>
        <?php endif;?>

    <?php else: ?>
        <span class="fw-bold">Сохраните техническое задание</span>
    <?php endif; ?>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary"><i class="fa-regular fa-paper-plane"></i> Передать</button>
</form>

<form id="add-work-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="#"
      method="post" enctype="multipart/form-data">
    <div class="title mb-3 h-2">
        Добавить работу
    </div>

    <div class="line-dashed-small"></div>

    <div class="mb-3">
        <label class="form-label">Гос работа (наименование) <span class="redStars">*</span></label>

        <input type="text" class="form-control" name="form[name]" value="" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Объект</label>

        <input type="text" class="form-control" name="form[object]" value="">
    </div>

    <div class="mb-3">
        <label class="form-label">Материал <span class="redStars">*</span></label>

        <select class="form-control select2" name="form[material_id]" data-placeholder="Выбрать материал" required>
            <option value="">Выбрать материал</option>
            <?php foreach ($this->data['material_list'] as $material): ?>
                <option value="<?=$material['ID']?>"><?=$material['NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Количество материала <span class="redStars">*</span></label>

        <input type="number" class="form-control bg-white" name="form[probe_count]" min="1" value="1" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Результаты испытаний</label>

        <input type="file" name="file_result" class="form-control" value="" accept=".doc, .docx, .xls, .pdf">
    </div>

    <div class="mb-3">
        <label class="form-label">Протокол испытаний</label>

        <input type="file" name="file_protocol" class="form-control" value="" accept=".doc, .docx, .xls, .pdf">
    </div>

    <input name="form[deal_id]" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
