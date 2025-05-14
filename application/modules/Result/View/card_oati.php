<div class="wrapper-card m-auto">
    <header class="header-result mb-3">
        <nav class="header-menu">
            <ul class="nav">
                <li class="nav-item me-2">
                    <a class="nav-link link-list" href="<?= URI ?>/request/list/<?=$this->data['comm']??''?>" title="Вернуться к списку">
                        <svg class="icon" width="25" height="25">
                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#list"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link link-card" href="<?= URI ?>/request/card/<?= $this->data['deal_id'] ?? '' ?>"
                       title="Карточка заявки">
                        <svg class="icon" width="25" height="25">
                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#card"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item me-2 disabled">
                    <a class="nav-link link-doc-send disabled" href="#">
                        <svg class="icon" width="25" height="25">
                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#doc-send"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link link-docs disabled" href="#">
                        <svg class="icon" width="25" height="25">
                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#docs"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link link-doc-edit disabled" href="#">
                        <svg class="icon" width="25" height="25">
                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#doc-edit"/>
                        </svg>
                    </a>
                </li>
                <li class="nav-item me-2 d-none">
                    <a class="nav-link popup-help" href="/ulab/help/LIMS_Manual_Stand/Result_card/Result_card.html" title="Техническая поддержка">
                        <i class="fa-solid fa-question"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <h2 class="d-flex mb-3">
        Заявка <?= $this->data['deal_title'] ?? '' ?>
    </h2>

    <div class="row">
        <div class="col-md-12">
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

                        <div class="row">
                            <div class="col-sm-6">
                                <div>Основание для проведения испытаний</div>
                                <div>
                                    <strong>
                                        <?php if (!empty($this->data['contract_number'])): ?>
                                            <?= $this->data['contract_type'] ?> №<?= $this->data['contract_number'] ?> от <?= $this->data['contract_date'] ?>
                                        <?php else: ?>
                                            Договор еще не составлен
                                        <?php endif; ?>
                                    </strong>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>Основание для формирования протокола</div>
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
                    </div>
                    <!--./wrapper-info-header-->
                </div>
                <!--./panel-body-->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <header class="panel-heading">
                    Таблица созданных протоколов
                    <span class="tools float-end">
                        <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                        <a href="#" class="fa fa-chevron-up"></a>
                     </span>
                </header>
                <div class="panel-body">
                    <div class="protocols-wrapper">
                        <input class="selected_protocol_id" type="hidden" value="<?=$_GET['protocol_id']?? ''?>">
                        <?php if (!empty($this->data['protocols'])): ?>
                            <div class="table-responsive mb-2">
                                <table class="table text-center table-hover align-middle">
                                    <thead>
                                    <tr class="table-secondary align-middle">
                                        <th class="border-0">
                                            Выбранные пробы
                                            <br>
                                            <input class="form-check-input selected-probe scale-1_5 cursor-pointer mt-2"
                                                   type="radio" name="selected_probe" value="" title="Все пробы"
                                                <?= $this->data['selected'] ? '' : 'checked' ?>>
                                        </th>
                                        <th class="border-0">Номер протокола</th>
                                        <th class="border-0">Дата протокола</th>
                                        <th class="border-0">Информация по протоколу</th>
                                        <th class="border-0">Вне системы</th>
                                        <th class="border-0">PDF-версия</th>
                                        <th class="border-0">Сформировать протокол</th>
                                        <th class="border-0">Скачать протокол</th>
                                        <th class="border-0">Присвоить номер</th>
                                        <th class="border-0">Удалить протокол</th>
                                        <?php if ($this->data['is_may_unlock']): ?>
                                            <th class="border-0">Разблокировать</th>
                                        <?php endif; ?>
                                        <th class="border-0">Протокол недействителен</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($this->data['protocols'] as $val): ?>
                                        <tr class="<?= $val['table_green'] ?>">
                                            <td>

                                                <?php if ($val['probe_count']): ?>
                                                    <input class="form-check-input selected-probe scale-1_5 cursor-pointer"
                                                           type="radio" name="selected_probe"
                                                           value="<?= $val['ID'] ?>" <?= $val['selected_probe'] ?>>
                                                <?php else: ?>
                                                    <span title="Отсутствуют прикреплённые пробы у протокола">
                                                        <input class="form-check-input scale-1_5" type="radio"
                                                               value="" disabled>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ( empty($val['INVALID']) ): ?>
                                                    <a href="<?= URI ?>/result/card_oati/<?= $this->data['deal_id'] ?>?protocol_id=<?= $val['ID'] ?><?= $val['selected_probe'] ? '&selected' : '' ?>"
                                                       class="text-decoration-none text-nowrap fw-bold">
                                                        <?= $val['view_number'] ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?= $val['view_number'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $val['date_ru'] ?></td>
                                            <td>
                                                <?php if($val['protocol_info']):?>
                                                    <span title="Для доступа к данным протокола проверте выбран ли протокол, протокол является действительным и у протокола отсутсвует номер или есть номер но протокол разблокирован, и к протоколу прикреплены пробы">
                                                        <svg class="icon icon-disabled" width="35" height="35">
                                                            <use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
                                                        </svg>
                                                    </span>
                                                <?php else:?>
                                                    <button type="button"
                                                            class="btn bg-transparent border-0 mt-0 p-0 protocol-information"
                                                            data-protocol="<?= $val['ID'] ?>" title="Информация по протоколу">
                                                        <svg class="icon" width="35" height="35">
                                                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#edit"/>
                                                        </svg>
                                                    </button>
                                                <?php endif;?>
                                            </td>
                                            <td>
                                                <?php if (!empty($val['PROTOCOL_OUTSIDE_LIS'])): ?>
                                                    <svg class="icon" width="30" height="30">
                                                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#check-circle"/>
                                                    </svg>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($val['PROTOCOL_OUTSIDE_LIS'])): ?>
                                                    <?php if (!empty($val['file']['file'])): ?>
                                                        <form class="form form-upload-pdf" method="post"
                                                              action="<?= URI ?>/result/deletePdf/<?= $val['ID'] ?>"
                                                              enctype="multipart/form-data">
                                                            <?php if (!empty($this->data['deal_id'])): ?>
                                                                <input class="deal-id" type="hidden" name="deal_id"
                                                                       value="<?= $this->data['deal_id'] ?>">
                                                            <?php endif; ?>

                                                            <?php if (!empty($this->data['tz_id'])): ?>
                                                                <input class="tz-id" type="hidden" name="tz_id"
                                                                       value="<?= $this->data['tz_id'] ?>">
                                                            <?php endif; ?>

                                                            <?php if (!empty($val['file']['file'])): ?>
                                                                <input class="file" type="hidden" name="file"
                                                                       value="<?= $val['file']['file'] ?>">
                                                            <?php endif; ?>

                                                            <?php if (!empty($this->data['selected'])): ?>
                                                                <input class="selected" type="hidden" name="selected" value="selected">
                                                            <?php endif; ?>

                                                            <div class="position-relative d-inline-block">
                                                                <a href="<?= $val['file']['dir'] ?><?= $val['file']['file'] ?>"
                                                                   
                                                                   title="<?= $val['file']['file'] ?>">
                                                                    <svg class="icon" width="30" height="30">
                                                                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#pdf_file"/>
                                                                    </svg>
                                                                </a>
                                                                <button type="submit"
                                                                        class="button-del-file button-close button-outline"
                                                                        name="delete_pdf"
                                                                        title="Удалить pdf файл"></button>
                                                            </div>
                                                        </form>
                                                    <?php else: ?>
                                                        <form class="form form-upload-pdf" method="post"
                                                              action="<?= URI ?>/result/uploadPdf/<?= $val['ID'] ?>"
                                                              enctype="multipart/form-data">
                                                            <?php if (!empty($this->data['deal_id'])): ?>
                                                                <input class="deal-id" type="hidden" name="deal_id"
                                                                       value="<?= $this->data['deal_id'] ?>">
                                                            <?php endif; ?>

                                                            <?php if (!empty($this->data['tz_id'])): ?>
                                                                <input class="tz-id" type="hidden" name="tz_id"
                                                                       value="<?= $this->data['tz_id'] ?>">
                                                            <?php endif; ?>

                                                            <?php if (!empty($this->data['selected'])): ?>
                                                                <input class="selected" type="hidden" name="selected" value="selected">
                                                            <?php endif; ?>

                                                            <label class="upload-pdf cursor-pointer"
                                                                   title="Загрузить PDF-версию">
                                                                <svg class="icon" width="30" height="30">
                                                                    <use xlink:href="<?= URI ?>/assets/images/icons.svg#upload"/>
                                                                </svg>
                                                                <input class="d-none" id="uploadPdf" type="file"
                                                                       name="upload_pdf" accept=".pdf, application/pdf" onchange="form.submit()">
                                                            </label>
                                                        </form>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span title="Для загрузки PDF-версии, в протоколе отмете 'Протокол выдается вне системы'">
                                                        <svg class="icon icon-disabled" width="30" height="30">
                                                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#upload"/>
                                                        </svg>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($val['is_create_protocol']): ?>
                                                    <span title="Для формирования протокола проверьте выбран ли протокол и является ли протокол действительным, выбраны ли пробы для протокола. <?= $val['probe_count'] ? '' : 'Внимание! У протокола отсутствуют прикреплённые пробы' ?>">
                                                        <svg class="icon icon-disabled" width="35" height="35">
                                                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#form"/>
                                                        </svg>
                                                    </span>
                                                <?php else: ?>
                                                    <a class="no-decoration me-1 <?= $val['validation_class'] ?>"
                                                       data-protocol_id="<?=$val['ID']?>"
                                                       data-href="/ulab/generator/ProtocolDocument/<?= $val['ID'] ?>"
                                                       title="Сформировать">
                                                        <svg class="icon" width="35" height="35">
                                                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#form"/>
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($val['doc_send']): ?>
                                                    <span title="Для скачивания протокола проверьте был ли сформирован протокол, является ли протокол действительным и не является протоколом выданным в не ЛИС. <?= !empty($val['file']['file']) ? 'Внимание! Файл для скачивания отсутствует.' : '' ?>">
                                                        <svg class="icon icon-disabled" width="30" height="30">
                                                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#doc-send"/>
                                                        </svg>
                                                    </span>
                                                <?php else: ?>
                                                    <a class="doc-send"
                                                       href="<?= $val['file']['dir'] ?><?= $val['file']['file'] ?>"
                                                       title="<?= $val['file']['file'] ?>" download>
                                                        <svg class="icon" width="30" height="30">
                                                            <use xlink:href="<?= URI ?>/assets/images/icons.svg#doc-send"/>
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($val['why_block_protocol_number'] != ''): ?>
                                                    <button type="button"
                                                            class="btn border-light-gray bg-light-gray text-nowrap mt-0 text-white"
                                                            data-bs-container="body" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="top"
                                                            data-bs-content="<?=$val['why_block_protocol_number']?>"
                                                            title="Присвоить номер">
                                                        Присвоить номер
                                                    </button>
                                                <?php else: ?>
                                                    <form class="form form-create-protocol" method="post"
                                                          action="<?= URI ?>/result/addProtocolNumber/<?= $val['ID'] ?>">
                                                        <?php if (!empty($this->data['deal_id'])): ?>
                                                            <input class="deal-id" type="hidden" name="deal_id"
                                                                   value="<?= $this->data['deal_id'] ?>">
                                                        <?php endif; ?>

                                                        <?php if (!empty($this->data['tz_id'])): ?>
                                                            <input class="tz-id" type="hidden" name="tz_id"
                                                                   value="<?= $this->data['tz_id'] ?>">
                                                        <?php endif; ?>

                                                        <?php if (!empty($this->data['selected_protocol'])): ?>
                                                            <input class="selected-protocol-id" type="hidden"
                                                                   name="selected_protocol"
                                                                   value="<?= $this->data['selected_protocol'] ?>">
                                                        <?php endif; ?>

                                                        <?php if (!empty($this->data['selected'])): ?>
                                                            <input class="selected" type="hidden" name="selected" value="selected">
                                                        <?php endif; ?>

                                                        <button type="submit"
                                                                class="btn btn-primary add-protocol-number text-nowrap mt-0"
                                                                data-protocol_id="<?=$val['ID']?>"
                                                                name="add_protocol_number">
                                                            Присвоить номер
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($val['delete_protocol']): ?>
                                                    <button class="btn border-light-gray bg-light-gray mt-0 fill-white btn-square"
                                                            type="button" title="Для удаления протокола проверте выбран ли протокол, протокол является действительным и у протокола отсутсвует номер">
                                                        <i class="fa-solid fa-minus icon-fix"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <form class="form form-create-protocol" method="post"
                                                          action="<?= URI ?>/result/deleteProtocol/<?= $val['ID'] ?>">
                                                        <?php if (!empty($this->data['deal_id'])): ?>
                                                            <input class="deal-id" type="hidden" name="deal_id"
                                                                   value="<?= $this->data['deal_id'] ?>">
                                                        <?php endif; ?>

                                                        <?php if (!empty($this->data['tz_id'])): ?>
                                                            <input class="tz-id" type="hidden" name="tz_id"
                                                                   value="<?= $this->data['tz_id'] ?>">
                                                        <?php endif; ?>
                                                        <button class="btn btn-danger mt-0 delete-protocol btn-square"
                                                                type="submit"
                                                                name="delete_protocol[<?= $val['ID'] ?>]">
                                                            <i class="fa-solid fa-minus icon-fix"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <?php if ($this->data['is_may_unlock']): ?>
                                            <td>
                                                <?php if ($val['edit_results']): ?>
                                                    <span title="Для разблокировки протокола проверте выбран ли протокол и является ли протокол действительным">
                                                        <label class="switch d-inline-block <?= $val['EDIT_RESULTS'] ? 'checkbox-disabled' : '' ?>">
                                                            <input class="form-check-input" type="checkbox"
                                                                <?= $val['EDIT_RESULTS'] ? 'checked' : '' ?> disabled>
                                                            <span class="slider pe-none <?= $val['EDIT_RESULTS'] ? '' : 'bg-light-gray' ?>"></span>
                                                        </label>
                                                    </span>
                                                <?php else: ?>
                                                    <form class="form form-create-protocol" method="post"
                                                          action="<?= URI ?>/result/editResults/<?= $val['ID'] ?>">
                                                        <?php if (!empty($this->data['deal_id'])): ?>
                                                            <input class="deal-id" type="hidden" name="deal_id"
                                                                   value="<?= $this->data['deal_id'] ?>">
                                                        <?php endif; ?>

                                                        <?php if (!empty($this->data['tz_id'])): ?>
                                                            <input class="tz-id" type="hidden" name="tz_id"
                                                                   value="<?= $this->data['tz_id'] ?>">
                                                        <?php endif; ?>

                                                        <?php if (!empty($this->data['selected'])): ?>
                                                            <input class="selected" type="hidden" name="selected" value="selected">
                                                        <?php endif; ?>

                                                        <label class="switch d-inline-block">
                                                            <input class="form-check-input edit-results"
                                                                   name="edit_results"
                                                                   type="checkbox"
                                                                <?= $val['EDIT_RESULTS'] ? 'checked' : '' ?>
                                                                   onchange="form.submit()">
                                                            <span class="slider"></span>
                                                        </label>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                <?php if ($this->data['is_may_invalid']): ?>
                                                    <?php if ($val['protocol_is_invalid']): ?>
                                                        <span title="Для признания протокола недействительным проверте выбран ли протокол, является ли протокол действительным и есть номер у протокола">
                                                            <label class="switch d-inline-block <?= $val['INVALID'] ? 'checkbox-disabled' : '' ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                    <?= $val['INVALID'] ? 'checked' : '' ?> disabled>
                                                                <span class="slider pe-none <?= $val['INVALID'] ? '' : 'bg-light-gray' ?>"></span>
                                                            </label>
                                                        </span>
                                                    <?php else: ?>
                                                        <form class="form form-create-protocol" method="post"
                                                              action="<?= URI ?>/result/protocolIsInvalid/<?= $val['ID'] ?>">
                                                            <?php if (!empty($this->data['deal_id'])): ?>
                                                                <input class="deal-id" type="hidden" name="deal_id"
                                                                       value="<?= $this->data['deal_id'] ?>">
                                                            <?php endif; ?>

                                                            <?php if (!empty($this->data['tz_id'])): ?>
                                                                <input class="tz-id" type="hidden" name="tz_id"
                                                                       value="<?= $this->data['tz_id'] ?>">
                                                            <?php endif; ?>
                                                            <label class="switch d-inline-block <?= $val['INVALID'] ? 'checkbox-disabled' : '' ?>">
                                                                <input class="form-check-input protocol-is-invalid"
                                                                       name="protocol_is_invalid"
                                                                       type="checkbox"
                                                                    <?= $val['INVALID'] ? 'checked' : '' ?>>
                                                                <span class="slider"></span>
                                                            </label>
                                                        </form>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div title="Признать протокол недействительным могут Администраторы и Руководители ИЦ">
                                                        <?= $val['INVALID'] == 1 ? 'Да' : 'Нет' ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!--./protocols-wrapper-->

                    <form class="form form-create-protocol" method="post" action="<?= URI ?>/result/createProtocol/">
                        <?php if (!empty($this->data['deal_id'])): ?>
                            <input class="deal-id" type="hidden" name="deal_id" value="<?= $this->data['deal_id'] ?>">
                        <?php endif; ?>

                        <?php if (!empty($this->data['tz_id'])): ?>
                            <input class="tz-id" type="hidden" name="tz_id" value="<?= $this->data['tz_id'] ?>">
                        <?php endif; ?>
                        <div class="row protocol-button-wrapper">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-create-protocol"
                                        name="btn_create_protocol">
                                    Создать протокол
                                </button>
                            </div>
                        </div>
                    </form>
                    <!--./form-create-protocol-->
                </div>
                <!--./panel-body-->
            </div>
        </div>
    </div>


    <form class="form form-result" id="formResult" method="post" action="<?= URI ?>/result/updateResult/">
        <?php if (!empty($this->data['requirement']['tz_id'])): ?>
            <input class="tz-id" type="hidden" name="tz_id" value="<?= $this->data['requirement']['tz_id'] ?>">
        <?php endif; ?>

        <?php if (!empty($this->data['deal_id'])): ?>
            <input class="deal-id" type="hidden" name="deal_id" value="<?= $this->data['deal_id'] ?>">
        <?php endif; ?>

        <?php if (!empty($this->data['selected_protocol'])): ?>
            <input class="protocol-id" type="hidden" name="protocol_id"
                   value="<?= $this->data['selected_protocol'] ?: '' ?>">
        <?php endif; ?>

        <?php if (!empty($this->data['selected'])): ?>
            <input class="selected" type="hidden" name="selected" value="selected">
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <header class="panel-heading">
                        Таблица результатов испытаний (<?= $this->data['scheme'] ? $this->data['scheme'] : 'Нет схемы'?>)
                        <span class="tools float-end">
                            <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                            <a href="#" class="fa fa-chevron-up"></a>
                         </span>
                    </header>
                    <div class="panel-body position-relative">
                        <input type="hidden" id="deal_id" value="<?= $this->data['deal_id'] ?>">

                        <div class="row flex-nowrap">

                            <div class="col-auto">
                                <div class="js-sticky-widget2">
                                    <div class="col-auto mb-3">
                                        <a href="#" class="btn-flex btn_start btn btn-success w125 btn-sm disabled" title="Начать/возобновить испытание">
                                            <i class="fa-solid fa-play"></i> <span>Старт</span>
                                        </a>
                                    </div>

                                    <div class="col-auto mb-3">
                                        <a href="#" class="btn-flex btn_pause btn btn-success w125 btn-sm disabled" title="Приостановить испытание">
                                            <i class="fa-solid fa-pause"></i> <span>Пауза</span>
                                        </a>
                                    </div>

                                    <div class="col-auto mb-3">
                                        <a href="#" class="btn-flex btn_stop btn btn-success w125 btn-sm disabled" title="Завершить испытание">
                                            <i class="fa-solid fa-stop"></i> <span>Завершить</span>
                                        </a>
                                    </div>

                                    <div class="col-auto mb-3 d-none">
                                        <a href="#" class="btn-flex btn_create_protocol btn btn-primary w125 btn-sm disabled" title="Создать протокол">
                                            <i class="fa-solid fa-plus icon-fix"></i> <span>Протокол</span>
                                        </a>
                                    </div>

                                    <div class="col-auto mb-3">
                                        <a href="#" class="btn-flex btn_group_measurement_sheet btn btn-primary w125 btn-sm disabled" title="Лист измерений">
                                            <i class="fa-solid fa-calculator"></i> <span>Лист измерений</span>
                                        </a>
                                    </div>

                                    <div class=" col-auto mb-3">
                                        <a href="#unbound-protocol-form" class="btn-flex btn_unbound_protocol popup-with-form btn btn-primary w125 btn-sm disabled" title="Отвязать протокол">
                                            <i class="fa-regular fa-file-excel"></i> <span>Отвязать протокол</span>
                                        </a>
                                    </div>

                                    <div class="col-auto mb-3">
                                        <button type="submit" class="btn btn-primary w125 btn-sm" title="Сохранить результаты">Сохранить</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col" style="max-width: 100%; overflow-x: hidden;">
                                <div class="row mb-2">
                                    <div class="col text-center">
                                        Испытания
                                    </div>
                                    <div class="col text-center">
                                        Объекты испытаний
                                    </div>
                                    <div class="col text-center">
                                        Пробы
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">Показать: </span>
                                            <select id="filter-methods" class="form-control select2 filter" multiple data-placeholder="Выбрать испытания (мультивыбор)">
                                                <?php foreach ($this->data['tz_methods_list'] as $item): ?>
                                                    <option value="<?=$item['id']?>"><?=$item['view_gost']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <select id="filter-material" class="form-control select2 filter" multiple data-placeholder="Выбрать материалы (мультивыбор)">
                                            <?php foreach ($this->data['tz_material_list'] as $item): ?>
                                                <option value="<?=$item['id']?>"><?=$item['name']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select id="filter-probe" class="form-control select2 filter" multiple data-placeholder="Выбрать пробы (мультивыбор)">
                                            <?php $mid = 0; foreach ($this->data['tz_probe_list'] as $item): ?>
                                                <?php if ( $mid !== $item['material_id'] ): $mid = $item['material_id']; ?>
                                                    <option disabled><b><?=$item['material_name']?></b></option>
                                                <?php endif; ?>
                                                <option value="<?=$item['id']?>"><?=$item['cipher']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table -table-striped journal table-hover table-sm table-light row-border"
                                           id="journal_methods" style="font-size: 14px; width: 100%;"
                                    >
                                        <thead>
                                            <tr class="table-secondary align-middle">
                                                <th scope="col">Методика</th>
                                                <th scope="col" class="text-nowrap">
                                                    <label class=""><input class="form-check-input all-check" type="checkbox"> Всё</label>
                                                </th>
                                                <th scope="col" style="min-width: 80px;">Материал</th>
                                                <th scope="col" style="min-width: 80px;">Проба</th>
                                                <th scope="col" style="width: 40px;">Испытание</th>
                                                <th scope="col" style="width: 40px;">Лист<br> измерения</th>
                                                <th scope="col" style="width: 40px;">Ед. изм.</th>
                                                <th scope="col" style="min-width: 180px;">Нормативная документация</th>
                                                <th scope="col" style="min-width: 100px;">Нормативное значение</th>
                                                <th scope="col" style="min-width: 120px;">Фактическое значение</th>
                                                <th scope="col" style="min-width: 140px;">Соответствие требованиям</th>
                                                <th scope="col" style="width: 40px;">В ОА</th>
                                                <th scope="col" style="min-width: 40px;">Номер протокола</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!--./panel-body-->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-primary save" form="formResult" name="save">
                    Сохранить
                </button>
            </div>
        </div>
    </form>
    <!--./form-result-->

    <div id="alert_modal" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
        <div class="title mb-3 h-2 alert-title"></div>

        <div class="line-dashed-small"></div>

        <div class="mb-3 alert-content"></div>
    </div>
    <!--./alert_modal-->

    <form id="measurementModalForm" class="bg-light mfp-hide col-md-10 m-auto p-3 position-relative"
          action="<?= URI ?>/result/saveMeasurementData/" method="post">

        <input type="hidden" name="deal_id" value="<?=$this->data['deal_id']?>">

        <div class="measurement_content">

        </div>
    </form>
    <!--./averageModalForm-->

    <form id="roomsModalForm" class="bg-light mfp-hide col-md-6 m-auto p-3 position-relative"
          action="<?= URI ?>/result/index/" method="post">
        <div class="title mb-3 h-2"></div>

        <div class="line-dashed-small"></div>
    </form>
    <!--./roomsModalForm-->

    <form id="protocolInformation" class="bg-light mfp-hide col-md-10 m-auto p-3 position-relative"
          action="/ulab/result/updateProtocol/" method="post">
        <div class="title mb-3 h-2"></div>
        <div class="line-dashed-small"></div>

        <div class="information-wrapper mx-3">
            <div class="row mb-3">
                <div class="col bg-white border border-light-gray px-3 py-3 me-3">
                    <strong class="d-block mb-3">Общая информация</strong>

                    <div class="form-group row">
                        <div class="col">
                            <label for="protocolType">Тип протокола</label>
                            <select class="form-select w-100 protocol-template d-none" name="protocol[id_template]">
                                <?php foreach ($this->data['template_list'] as $item): ?>
                                    <option <?= $this->data['protocol']['id_template'] === $item['id'] ? 'selected' : '' ?>
                                            value="<?=$item['id']?>"><?=$item['name']?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="form-select protocol-type w-100" name="protocol[PROTOCOL_TYPE]">
                                <option value='0'>Стандартный</option>
                                <option value="1">Стандартный с ЭЦП</option>
                                <option value='33'>Упрощенный</option>
                                <option value="43">Форма заказчика</option>
							</select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label for="verify">Подпись в протоколе</label>
                            <select class="form-select verify w-100 select2" name="protocol[VERIFY][]" multiple="multiple"></select>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <label class="switch">
                                    <input class="form-check-input no-evaluate" name="protocol[NO_COMPLIANCE]"
                                           type="checkbox" value="1">
                                    <span class="slider"></span>
                                </label>
                                <div class="ms-2">Не оцен. на соотв. нормам</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col bg-white border border-light-gray px-3 py-3">
                    <strong class="d-block mb-3">Информация об испытаниях</strong>

                    <div class="form-group row">
                        <div class="col">
                            <lable for="dateBegin">Дата начала</lable>
                            <input type="date" class="form-control date-begin <?=$this->data['is_check'] ? 'date-trials' : ''?>"
                                   name="protocol[DATE_BEGIN]" value=""
                                   title="Редактирование доступно Администраторам и Руководителям ИЦ">
                        </div>
                        <div class="col">
                            <lable for="dateEnd">Дата окончания</lable>
                            <input type="date" class="form-control date-end <?=$this->data['is_check'] ? 'date-trials' : ''?>"
                                   name="protocol[DATE_END]" value=""
                                   title="Редактирование доступно Администраторам и Руководителям ИЦ">
                        </div>
                    </div>
                    <div class="form-group row conditions-wrapper">
                        <div class="col">
                            <lable>Температура</lable>
                            <div class="row">
                                <div class="col">
                                    <input type="number" class="form-control condition_input w-100 temp1"
                                           name="protocol[TEMP_O]" step="any" placeholder="От" value=""
                                           title="Редактирование доступно Администраторам и Руководителям ИЦ">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control condition_input w-100 temp2"
                                           name="protocol[TEMP_TO_O]" step="any" placeholder="До" value=""
                                           title="Редактирование доступно Администраторам и Руководителям ИЦ">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <lable>Влажность</lable>
                            <div class="row">
                                <div class="col">
                                    <input type="number" class="form-control condition_input w-100 wet1"
                                           name="protocol[VLAG_O]" step="any" placeholder="От" value=""
                                           title="Редактирование доступно Администраторам и Руководителям ИЦ">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control condition_input w-100 wet2"
                                           name="protocol[VLAG_TO_O]" step="any" placeholder="До" value=""
                                           title="Редактирование доступно Администраторам и Руководителям ИЦ">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($this->data['may_edit_conditions']): ?>
                        <div class="form-group row">
                            <div class="col mb-0">
                                <div>Изменить дату испытаний</div>
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input class="form-check-input change-trials-date"
                                               name="protocol[CHANGE_TRIALS_DATE]" type="checkbox" value="1">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col mb-0">
                                <div>Изменить условия испытаний</div>
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input class="form-check-input change-trials-conditions"
                                               name="protocol[CHANGE_TRIALS_CONDITIONS]" type="checkbox" value="1">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="line-dashed"></div>
                    <div class="form-group row mb-0">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <label class="switch">
                                    <input class="form-check-input output-in-protocol"
                                           name="protocol[OUTPUT_IN_PROTOCOL]" type="checkbox" value="1">
                                    <span class="slider"></span>
                                </label>
                                <div class="ms-2">Вывод условий в протокол</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col bg-white border border-light-gray px-3 py-3 me-3">
                    <strong class="d-block mb-3">Информация об оборудовании</strong>

                    <div class="row align-items-end min-h-180">
                        <div class="form-group col">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Используемое оборудование</span>
                                <button type="button" class="bg-transparent border-0 revert-default" data-protocol-id="">
                                    <ins>Вернуть по умолчанию</ins>
                                </button>
                            </div>
                            
                            <select class="equipment-used" name="equipment_used" style="display: none;" multiple="multiple">
                            </select>

                            <div class="custom-equipment-list form-select min-h-180">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="equipmentIds" name="oborud[equipment_ids]" value="[]">

                    <div class="row">
                        <div class="form-group col">
                            <select class="form-select equipment select2" id="equipment" name="equipment"></select>
                        </div>
                    </div>
                </div>
                <div class="col bg-white border border-light-gray px-3 py-3">
                    <strong class="d-block mb-3">Данные объекта испытаний</strong>

                    <div class="row">
                        <div class="form-group col">
                            <lable for="objectDescription">Описание объекта</lable>
                            <textarea class="form-control mw-100 object-description"
                                      id="objectDescription" name="protocol[DESCRIPTION]"></textarea>
                        </div>
                        <div class="form-group col">
                            <lable for="object">Объект строительства</lable>
                            <textarea class="form-control mw-100 object" id="object" name="protocol[OBJECT]"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <lable for="placeProbe">Место отбора проб</lable>
                            <textarea class="form-control mw-100 place-probe"
                                      id="placeProbe" name="protocol[PLACE_PROBE]"></textarea>
                        </div>
                        <div class="form-group col">
                            <lable for="dateProbe">Дата отбора проб</lable>
                            <input type="date" class="form-control date-probe" name="protocol[DATE_PROBE]" value="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <lable for="additionalInformation">Дополнительная информация</lable>
                            <textarea class="form-control mw-100 additional-information"
                                      id="additionalInformation" name="protocol[DOP_INFO]"></textarea>
                        </div>
                    </div>

                    <strong class="d-block mb-3">Дополнительная информация</strong>

                    <div class="row">
                        <div class="col">
                            <div>Протокол выдается вне системы</div>
                            <label class="switch">
                                <input class="form-check-input protocol-outside-lis"
                                       name="protocol[PROTOCOL_OUTSIDE_LIS]" type="checkbox" value="1">
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="col">
                            <div>C аттестатом аккредитации</div>
                                <label class="switch">
                                    <input class="form-check-input attestat-in-protocol"
                                           name="protocol[ATTESTAT_IN_PROTOCOL]" type="checkbox" value="1">
                                    <span class="slider"></span>
                                </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input name="protocol_id" value="" type="hidden">
        <input name="selected" value="" type="hidden">
        <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
        <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

        <div class="line-dashed-small"></div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
    <!--./protocolInformation-->


    <form id="gost_room_form" action="/ulab/result/saveRoomStart/" method="post" class="bg-light mfp-hide col-md-7 m-auto p-3 position-relative">
        <div class="title mb-3 h-2">
            Помещение
        </div>

        <div class="line-dashed-small"></div>

        <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
        <input name="selected_protocol_id" value="<?=$this->data['selected_protocol']?>" type="hidden">

        <div class="gost_room_container">

        </div>

        <button type="submit" class="btn btn-primary">Старт</button>
    </form>


    <form id="unbound-protocol-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" method="post" action="<?=URI?>/result/unboundProtocol">
        <div class="title mb-3 h-2">
            Отвязать пробы от протокола
        </div>

        <div class="line-dashed-small"></div>

        <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
        <input class="probe-id-list" name="probe_id_list" value="" type="hidden">
        <input class="unbound-protocol" name="protocol_id" value="" type="hidden">

        <div class="row mb-3">
            <label for="inputEmail3" class="col col-form-label">Выбрано проб: <span class="count-selected-probe"></span></label>
        </div>

        <div class="line-dashed-small"></div>

        <button type="submit" class="btn btn-primary">Отвязать</button>
    </form>
</div>
<!--./wrapper-card-->
