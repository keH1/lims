<header class="header-requirement mb-4">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?= URI ?>/request/card/<?=$this->data['deal_id']?>" title="Вернуться в карточку">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link" href="<?= URI ?>/request/list/" title="Вернуться в журнал заявок">
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
                <a class="nav-link popup-help" href="/ulab/help/LIMS_Manual_Stand/Technical_spec_int/Tec_spec_int.html" title="ПОМОГИТЕ">
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

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<header class="panel-heading">
					Таблица актов приемки проб
					<span class="tools float-end">
                            <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                            <a href="#" class="fa fa-chevron-up"></a>
                         </span>
				</header>
				<div class="panel-body">
					<div class="protocols-wrapper">
						<div class="table-responsive mb-2">
							<table class="table text-center table-hover align-middle table_act">
								<thead>
								<tr class="table-secondary align-middle">
									<th class="border-0">Номер акта</th>
									<th class="border-0">Дата акта</th>
									<th class="border-0">Объект испытаний</th>
									<th class="border-0">Скачать акт</th>
									<th class="border-0">Удалить акт</th>
									<th class="border-0">Оформитель</th>
								</tr>
								</thead>
								<tbody>
								<?php if (!empty($this->data['probe_acts'])): ?>
									<?php foreach ($this->data['probe_acts'] as $val): ?>
										<tr class="<?= $val['table_green'] ?> act-tr">
											<td>
												<a href="#"
												   class="text-dark text-decoration-none text-nowrap fw-bold">
													<?= $val['ACT_NUM'] ?>
												</a>
											</td>
											<td><?= $val['ACT_DATE'] ?></td>
											<td><?= $val['material'] ?></td>
											<td>
												<a class="generate-act"
												   href="/protocol_generator/probe.php?idAct=<?= $val['ID'] ?>&ID=<?= $this->data['deal_id'] ?>"
												   title="Cкачать акт приемки проб">
													<svg class="icon" width="30" height="30">
														<use xlink:href="<?= URI ?>/assets/images/icons.svg#doc-send"/>
													</svg>
												</a>
											</td>
											<td>
												<button class="btn btn-danger mt-0 delete-act btn-square"
														type="button" data-id-act="<?= $val['ID'] ?>">
													<i class="fa-solid fa-minus icon-fix"></i>
												</button>
											</td>
											<td>
												<?= $val['creator'] ?>
											</td>

										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>

					</div>
					<!--./protocols-wrapper-->

					<div class="form form-create-act">
						<?php if (!empty($this->data['deal_id'])): ?>
							<input class="deal-id" type="hidden" name="deal_id" value="<?= $this->data['deal_id'] ?>">
						<?php endif; ?>

						<?php if (!empty($this->data['tz_id'])): ?>
							<input class="tz-id" type="hidden" name="tz_id" value="<?= $this->data['tz_id'] ?>">
						<?php endif; ?>
						<div class="row protocol-button-wrapper">
							<div class="col">
								<a class="btn btn-primary disabled popup-with-form newAct" id="btn-create-act"
										name="btn-create-act" href="#actCreatInformation">
									Создать акт приемки
								</a>
							</div>
						</div>
					</div>
					<!--./form-create-protocol-->
				</div>
				<!--./panel-body-->
			</div>
		</div>
	</div>


    <form class="form form-sample" id="form_sample" method="post" action="<?=URI?>/sample/updateTz/">

        <input type="hidden" id="tz_id" name="tz_id" value="<?= $this->data['tz_id'] ?>">
        <input type="hidden" id="deal_id" name="deal_id" value="<?= $this->data['deal_id'] ?>">
        <input type="hidden" id="clear_confirm" name="clear_confirm" value="0">

        <div class="panel panel-default">
            <div class="panel-heading">
                Объект испытаний
                <span class="tools float-end">
                    <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="javascript:;" class="fa fa-chevron-up"></a>
                </span>
            </div>
            <div class="panel-body">

                <div class="accordion accordion-flush mb-3 material-block-group" id="accordionFlushGroup">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading-group">
                            <div class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#flush-collapse-group" aria-expanded="false" aria-controls="flush-collapse-group">
                                Групповые действия
                            </div>
                        </h2>
                        <div id="flush-collapse-group" class="accordion-collapse collapse" aria-labelledby="flush-heading" data-bs-parent="#accordionFlushGroup">
                            <div class="accordion-body">
                                <div class="error-msg-block"></div>
                                
                                <div class="row mb-3 d-none">
                                    <div class="col">
                                        <label class="form-label mb-1">Групповое применение схемы</label>
                                        <div class="input-group">
                                            <select class="form-control select2" name="" id="">
                                                <option value="1">Нет схемы / ручной ввод</option>
                                            </select>

                                            <button type="button" class="btn btn-primary disabled group-button">Применить</button>
                                        </div>
                                    </div>
                                </div>


                                <label class="form-label mb-1">Групповое добавление испытания</label>

                                <div class="row">
                                    <div class="col-4">
                                        <label class="form-label mb-1">Методика испытаний</label>
                                    </div>
                                </div>

                                <div class="row justify-content-between mb-3 method-block">
                                    <div class="col-4">
                                        <div class="input-group">
                                            <select class="form-control select2 method-select" name="" multiple>
                                                <option value=""></option>
                                                <?php $materialName = ""; ?>
                                                <?php foreach ($this->data['method_list'] as $method): ?>
                                                    <?php if ( !empty($method['material_name']) && $materialName != $method['material_name'] ): ?>
                                                        <?php $materialName = $method['material_name']; ?>
                                                        <option disabled data-bg-color="#cae6f3a3"><?=$method['material_name']?></option>
                                                    <?php endif; ?>

                                                    <?php if ($method['count_result'] <= 0) { continue; } ?>
                                                    <option
                                                        <?=isset($method['date_color'])? 'data-color="'.$method['date_color'].'"' : ''?>
                                                            data-count="<?=$method['count_result']?>"
                                                            value="<?=$method['ID']?>"
                                                    ><?=$method['view_gost']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <a class="btn btn-outline-secondary method-link disabled" target="_blank" title="Перейти в методику" href="">
                                                <i class="fa-solid fa-right-to-bracket"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <button
                                                class="btn btn-primary mt-0 btn-square float-end disabled group-button add-group-method"
                                                type="button"
                                        >
                                            <i class="fa-solid fa-plus icon-fix"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <label class="form-label mb-1">Место отбора</label>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label mb-1">Дата отбора</label>
                                    </div>
                                    <div class="col">
                                        <label class="form-label mb-1">Дата доставки проб</label>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input class="form-control input-place" type="text">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input class="form-control input-date-sample" type="date">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <input class="form-control input-date-delivery" type="datetime-local">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button
                                                class="btn btn-primary mt-0 btn-square float-end disabled group-button add-group-data"
                                                type="button"
                                        >
                                            <i class="fa-solid fa-plus icon-fix"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row justify-content-between">
									<div class="col-auto">
										<a class="btn btn-primary disabled popup-with-form newAct" id="btn-create-act"
										   name="btn-create-act" href="#actCreatInformation">
											Создать акт приемки
										</a>
									</div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-danger del-permanent-some-probe" title="Удалить пробу и испытания данной пробы">
                                            Удалить пробы
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row justify-content-end mb-3">

                    <div class="col-auto">
                        <button type="button" class="btn btn-primary btn-square collapse-all-material" title="Свернуть все материалы">
                            <i class="fa-solid fa-angles-up"></i>
                        </button>
                    </div>

                    <div class="col-auto">
                        <button type="button" class="btn btn-primary btn-square expand-all-material" title="Развернуть все материалы">
                            <i class="fa-solid fa-angles-down"></i>
                        </button>
                    </div>

                </div>

                <div class="accordion mb-3 material-block" id="accordionFlush">
<!--					<pre>--><?//print_r($this->data['material_probe_list'])?><!--</pre>-->
                    <?php $materialNumber = 0; ?>
                    <?php foreach ($this->data['material_probe_list'] as $materialId => $material): ?>
                        <div class="accordion-item material-item" data-number-material="<?=$materialNumber?>" data-material_id="<?=$materialId?>">
                            <h2 class="accordion-header" id="flush-heading<?=$materialNumber?>">
                                <div class="accordion-button ps-0 collapsed" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$materialNumber?>" aria-expanded="false" aria-controls="flush-collapse<?=$materialNumber?>">
                                      <input class="form-check-input ms-3 me-3 material-check" type="checkbox" data-bs-toggle="collapse" data-bs-target="#qq">
                                    <?=$material['material_name']?>
                                    <span class="ms-3 msg-change-material"></span>
                                </div>
                            </h2>

                            <div id="flush-collapse<?=$materialNumber?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?=$materialNumber?>">
                                <div class="accordion-body">

                                    <div class="row justify-content-end mb-3">
                                        <div class="col-auto">
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Кол-во проб</span>
                                                <input type="number" class="form-control new-count-probe" min="1" value="1">
                                                <button type="button" class="btn btn-success btn-square add-probe" title="Добавить пробу">
                                                    <i class="fa-solid fa-plus icon-fix"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <button type="button" class="btn btn-primary btn-square expand-all" title="Свернуть все пробы">
                                                <i class="fa-solid fa-angles-up"></i>
                                            </button>
                                        </div>

                                        <div class="col-auto">
                                            <button type="button" class="btn btn-primary btn-square collapse-all" title="Развернуть все пробы">
                                                <i class="fa-solid fa-angles-down"></i>
                                            </button>
                                        </div>

                                        <div class="col-auto">
                                            <button type="button" class="btn btn-danger btn-square delete-material" title="Удалить объект испытаний и все пробы">
                                                <i class="fa-solid fa-minus icon-fix"></i>
                                            </button>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="line-dashed"></div>

                                    <div class="accordion probe-block" id="accordionPanelsStayOpen<?=$materialNumber?>">

                                        <?php $probeNumber = 0; ?>
                                        <?php foreach ($material['probe'] as $probeId => $probe): ?>
                                            <div class="accordion-item probe-item" data-probe_number="<?=$probeNumber?>" data-probe_id="<?=$probeId?>" data-act_id="<?=$probe['act_id']?>">
                                                <h2 class="accordion-header" id="panelsStayOpen-heading<?=$materialNumber?>-<?=$probeNumber?>">
                                                    <div class="accordion-button ps-0 collapsed bg-pele-green" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?=$materialNumber?>-<?=$probeNumber?>" aria-expanded="false" aria-controls="panelsStayOpen-collapse<?=$materialId?>-<?=$probeNumber?>">
                                                        <input class="form-check-input ms-3 me-3 probe-check" type="checkbox" data-bs-toggle="collapse" data-bs-target="#qq">
														<span class="probe-name"><?=$probe['cipher']?> <?=$probe['act']?></span>
                                                    </div>
                                                </h2>
                                                <div class="header-inputs">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <label class="form-label mb-1">Шифр заказчика</label>
                                                        </div>
                                                        <div class="col-3">
                                                            <label class="form-label mb-1">Место отбора</label>
                                                        </div>
                                                        <div class="col-3">
                                                            <label class="form-label mb-1">Дата отбора</label>
                                                        </div>
                                                        <div class="col">
                                                            <label class="form-label mb-1">Дата доставки проб</label>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-3">
                                                            <div class="input-group">
                                                                <input class="form-control cipher" type="text"
                                                                       name="material[<?=$materialId?>][probe][<?=$probeId?>][cipher_customer]"
                                                                       value="<?= htmlentities($probe['cipher_customer']) ?>"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="input-group">
                                                                <input class="form-control place" type="text"
                                                                       name="material[<?=$materialId?>][probe][<?=$probeId?>][probe_place]"
                                                                       value="<?= htmlentities($probe['probe_place']) ?>"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="input-group">
                                                                <input class="form-control date-sample" type="date"
                                                                       name="material[<?=$materialId?>][probe][<?=$probeId?>][date_sample]"
                                                                       value="<?= $probe['date_sample'] ?>"
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <input class="form-control date-delivery" type="datetime-local"
                                                                       name="material[<?=$materialId?>][probe][<?=$probeId?>][date_delivery]"
                                                                       value="<?= $probe['date_delivery'] ?>" readonly
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="panelsStayOpen-collapse<?=$materialNumber?>-<?=$probeNumber?>" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading<?=$materialNumber?>-<?=$probeNumber?>">
                                                    <div class="accordion-body">

                                                        <div class="row justify-content-end mb-3">
<!--                                                            <div class="col-auto">-->
<!--                                                                <button type="button" class="btn btn-success btn-square add-method-to-probe" title="Добавить испытание">-->
<!--                                                                    <i class="fa-solid fa-plus icon-fix"></i>-->
<!--                                                                </button>-->
<!--                                                            </div>-->
<!---->
                                                            <div class="col-auto">
                                                                <button type="button" class="btn btn-success btn-square copy-probe" title="Скопировать пробу и все испытания данной пробы">
                                                                    <i class="fa-regular fa-copy icon-fix"></i>
                                                                </button>
                                                            </div>

                                                            <div class="col-auto">
                                                                <button type="button" class="btn btn-danger btn-square del-permanent-probe" title="Удалить пробу и испытания данной пробы">
                                                                    <i class="fa-solid fa-minus icon-fix"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="line-dashed"></div>

                                                        <div class="row mb-3">

                                                            <input type="hidden" name="material[<?=$materialId?>][probe][<?=$probeId?>][probe_number]" value="<?=$probeNumber?>" class="probe-number-input">
                                                            <input type="hidden" name="material[<?=$materialId?>][probe][<?=$probeId?>][material_number]" value="<?=$material['material_number']?>" class="material-number-input">

                                                        </div>

                                                        <div class="method-container">
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <label class="form-label mb-1">Методики испытаний</label>
                                                                </div>
                                                            </div>
                                                            <?php foreach ($probe['method'] as $methodKey => $probeMethod): ?>
                                                                <div class="row justify-content-between method-block mb-2" data-gost_number="<?=$probeMethod['gost_number']?>">
                                                                    <?php if (empty($probeMethod['view_gost'])): ?>
                                                                        <div class="empty-methods">Нет методик</div>
                                                                    <?php else:?>
                                                                        <div class="col-6">
                                                                            <div class="input-group mb-1">
                                                                                <input type="text" class="form-control"
                                                                                        value="<?=$probeMethod['GOST'] . '-' . $probeMethod['GOST_YEAR'] . ' | ' . $probeMethod['SPECIFICATION']?>" readonly>
                                                                                <input type="hidden" class="gost-number-input" name="probe[<?=$probeId?>][method][<?=$probeMethod['ugtp_id']?>][gost_number]" value="<?=$probeMethod['gost_number']?>">
                                                                                <a class="btn btn-outline-secondary method-link <?=$probeMethod['ID'] > 0? '' : 'disabled'?>" target="_blank" title="Перейти в методику" href="/obl_acc.php?ID=<?=$probeMethod['ID']?>">
                                                                                    <i class="fa-solid fa-right-to-bracket"></i>
                                                                                </a>
                                                                                <div class="col-auto">
                                                                                    <button
                                                                                            class="btn btn-danger mt-0 del-permanent-material-gost btn-square float-end clear_confirm_change"
                                                                                            data-gtp_id="<?= $probeMethod['ugtp_id'] ?>"
                                                                                            type="button"
                                                                                    >
                                                                                        <i class="fa-solid fa-minus icon-fix"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif;?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $probeNumber++ ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $materialNumber++; ?>
                    <?php endforeach; ?>
                </div>

<!--                <div class="input-group mb-3">-->
<!--                    <span class="input-group-text">Объект испытаний</span>-->
<!--                    <select class="form-control select2" id="new-material">-->
<!--                        <option value="">Выбрать объект испытаний</option>-->
<!--                        --><?php //foreach ($this->data['material_list'] as $material): ?>
<!--                            <option value="--><?//=$material['ID']?><!--">--><?//=$material['NAME']?><!--</option>-->
<!--                        --><?php //endforeach; ?>
<!--                    </select>-->
<!--                    <span class="input-group-text">Кол-во проб</span>-->
<!--                    <input type="number" class="form-control new-count-probe" min="1" value="1">-->
<!--                    <button type="button" class="btn btn-primary add-new-material">Добавить</button>-->
<!--                </div>-->

<!--                <div class="line-dashed"></div>-->

                <!-- <div class="wrapper-discount bg-light-secondary p-2">
                    <div class="row justify-content-end">
                        <div class="col-auto d-flex flex-column">
                            <label class="form-label mb-1">Итого</label>
                            <span class="total mt-2"><?= $this->data['tz']['price_ru'] ?></span>
                            <input id="price-total" type="hidden" name="tz[PRICE]" value="<?= $this->data['tz']['PRICE'] ?>">
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
                </div> -->

            </div>
        </div>

        <!-- <div class="panel panel-default">
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
        </div> -->

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

<!-- <div id="return-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Причина возврата
    </div>

    <div class="line-dashed-small"></div>

    <textarea class="form-control" id="desc_return" rows="5" required></textarea>

    <div class="line-dashed-small"></div>

    <button type="button" class="btn btn-primary not_approve_tz_btn">Отправить</button>
</div>
-->
<div id="actCreatInformation" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/requirement/confirmTzSent/" method="post">
	<h4>Данные акта</h4>
	<div class="line-dashed-small forMsg"></div>
	<div class="mb-3">
		<label>Укажите дату и время поступления проб в лабораторию</label>
		<input type="datetime-local" class="form-control deliveryDate" name="deliveryDate">
	</div>
	<div class="mb-3">
		<label>Укажите кто доставил пробы</label>
		<input type="text" class="form-control deliveryman" name="deliveryman">
	</div>
	<div class="line-dashed-small forMsg"></div>
	<button type="button" class="btn btn-primary btn-create-act">Сформировать</button>
</div>
