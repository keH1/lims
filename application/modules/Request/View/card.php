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
                <a class="nav-link" href="<?=URI?>/request/edit/<?=$this->data['deal_id']?>" title="Редактировать заявку">
                    <svg class="icon" width="20" height="20">
                        <use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
                    </svg>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="disabled nav-link disable-after-click <?=empty($this->data['request']['ID'])? 'disabled' : ''?>" href="<?=URI?>/request/copy/<?=$this->data['deal_id']?>" title="Скопировать">
                    <svg class="icon" width="20" height="20">
                        <use xlink:href="<?=URI?>/assets/images/icons.svg#docs"/>
                    </svg>
                </a>
            </li>
            <li class="nav-item me-2">
            	<a class="nav-link popup-help" href="/ulab/help/LIMS_Manual_Stand/Request_card/Request_card.html" title="Техническая поддержка">
                    <i class="fa-solid fa-question"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>

<h2 class="d-flex mb-3">
    <div class="stage-block rounded <?=$this->data['stage']['color']?> me-1 mt-1" title="<?=$this->data['stage']['title']?>"></div>
    Заявка <?=$this->data['deal_title']?>
</h2>

<div class="panel panel-default">
    <header class="panel-heading">
        Общая информация
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
    </header>
    <div class="panel-body">
        <div class="row mb-3">
            <div class="col-4">
                <div>Общая стоимость</div>
                <div><strong><?=$this->data['request']['price_ru']?></strong></div>
            </div>
            <div class="col-4">
                <div>Ответственный</div>
                <div><strong><?=$this->data['assigned']?></strong></div>
            </div>
            <div class="col-4">
                <div>
                    Заказчик
                    <?php if ($this->data['is_good_company']): ?>
                        <img src="/ulab/assets/images/confirmed.png" width="25" height="25" alt="confirm" title="Добросовестный плательщик">
                    <?php endif; ?>
                </div>
                <div>
                    <strong><?=$this->data['company_title']?></strong>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div>Контактное лицо</div>
                <div><strong><?=$this->data['contact']?></strong></div>
            </div>
            <div class="col-4">
                <div>Телефон</div>
                <div><strong><?=$this->data['phone']?></strong></div>
            </div>
            <div class="col-4">
                <div>Почта</div>
                <div><strong><?=$this->data['head_email']?></strong></div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Документы
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
    </header>
    <div class="panel-body">
        <table class="table align-middle table-card">
            <thead>
            <tr class="table-light">
                <th scope="col" style="width: 25%">Тип документа</th>
                <th scope="col">Номер</th>
                <th scope="col">Дата</th>
                <th scope="col">Дата отправки</th>
                <th scope="col" colspan="4">Операции</th>
            </tr>
            </thead>
            <tbody>
            <tr class="<?=$this->data['tz']['check']? 'table-green' : ''?>">
                <td><strong>Техническое задание</strong></td>
                <td>
                    <?php if ( $this->data['tz']['check'] ): ?>
                        <a href="<?=$this->data['tz']['tz_link']?>"><?=$this->data['tz']['number']?></a>
                    <?php else: ?>
                        Не сформировано
                    <?php endif; ?>
                </td>
                <td>
                    <?=$this->data['tz']['date']?>
                </td>
                <td>
                    <?=$this->data['tz']['date_send']?>
                </td>
                <td class="w30">
                    <?php if ( $this->data['tz']['check'] ): ?>
                        <a class="no-decoration me-1 disabled" href="#" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1" href="<?=URI.'/requirement/card_new/'.$this->data['tz_id']?>" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td class="w30">
                    <a class="no-decoration me-1 <?=!empty($this->data['mail_list']) ? 'popup-mail' : ''?>"  data-type="<?=$this->data['deal_id']?>" data-type="1" data-title="<?=$this->data['deal_title']?>"
					   href="<?= empty($this->data['mail_list']) ? "/mail.php?ID={$this->data['deal_id']}&TYPE=1&EMAIL={$this->data['email']}&TITLE={$this->data['deal_title']}" : "#email-check"?>"
					   title="Отправить клиенту бланк заявки">
                        <svg class="icon" width="35" height="35">
                            <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                        </svg>
                    </a>
                </td>
                <td class="w30"></td>
                <td class="w30"></td>
            </tr>

            <tr class="<?=$this->data['proposal']['check']? 'table-green' : ''?>">
                <td><strong>Коммерческое предложение</strong></td>
                <td>
                    <?=$this->data['proposal']['number']?>
                </td>
                <td>
                    <?=$this->data['proposal']['date']?>
                </td>
                <td>
                    <?=$this->data['proposal']['date_send']?>
                </td>
                <td>
                    <?php if ($this->data['proposal']['is_disable_form']): ?>
                        <a class="no-decoration me-1 disabled" href="#" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1 reloadS" data-href="<?=$this->data['proposal']['link']?>" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($this->data['proposal']['is_disable_mail']): ?>
                        <a class="no-decoration me-1 disabled" href="#" title="Отправить клиенту">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1 <?=!empty($this->data['mail_list']) ? 'popup-mail' : ''?>" data-id="<?=$this->data['deal_id']?>" data-type="2" data-title="<?=$this->data['deal_title']?>" data-attach="<?=$this->data['proposal']['attach']?>"
                           href="<?= empty($this->data['mail_list']) ? "/mail.php?ID={$this->data['deal_id']}&TZ_ID={$this->data['request']['ID']}&TYPE=2&EMAIL={$this->data['email']}&TITLE={$this->data['deal_title']}&ATTACH={$this->data['proposal']['attach']}" : "#email-check"?>"
                           title="Отправить клиенту"
                        >
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td></td>
                <td></td>
            </tr>

            <tr class="<?=$this->data['order']['check']?>">
                <td><strong>Договор</strong></td>
                <td>
                    <?php if ($this->data['order']['is_generated']): ?>
                        <a href="/ulab/order/card/<?=$this->data['order']['id']?>"><?=$this->data['order']['number']?></a>
                    <?php else: ?>
                        Не сформирован
                    <?php endif; ?>
                </td>
                <td>
                    <?=$this->data['order']['date']?>
                </td>
                <td>
                    <?=$this->data['order']['date_send']?>
                </td>
                <td>
                    <?php if ($this->data['order']['is_disable_form']): ?>
                        <a class="no-decoration me-1 disabled" href="#" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1 reloadS" data-href="/protocol_generator/dogovor.php?ID=<?=$this->data['deal_id']?>&TZ_ID=<?=$this->data['tz_id']?>" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ( !$this->data['order']['is_disable_mail'] ): ?>
                        <a class="no-decoration me-1 <?=!empty($this->data['mail_list']) ? 'popup-mail' : ''?>" data-id="<?=$this->data['order']['id']?>" data-type="3" data-title="<?=$this->data['deal_title']?>" data-attach="<?=$this->data['order']['attach']?>"
                           href="<?= empty($this->data['mail_list']) ? "/mail.php?ID={$this->data['order']['id']}&TZ_ID={$this->data['tz_id']}&TYPE=3&EMAIL={$this->data['email']}&NAME={$this->data['user']['name']}&ATTACH={$this->data['order']['attach']}&TITLE={$this->data['deal_title']}" : "#email-check"?>"
                           title="Отправить клиенту"
                        >
                            <svg class="icon me-1" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration disabled me-1" href="" title="Отправить клиенту">
                            <svg class="icon me-1" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <input type="checkbox" data-text="Договор" class="form-check-input check-mail" <?=$this->data['order']['check']? 'checked' : 'disabled'?>>
                </td>
                <td></td>
            </tr>

            <tr class="<?=$this->data['attach']['check']?>">
                <td><strong>Прил. к договору (ТЗ)</strong></td>
                <td>
                    <?=$this->data['attach']['number']?>
                </td>
                <td>
                    <?=$this->data['attach']['date']?>
                </td>
                <td>
                    <?=$this->data['attach']['date_send']?>
                </td>
                <td>
<!--                    --><?php //if ($this->data['attach']['is_disable_form']): ?>
<!--                        <a class="no-decoration me-1 disabled" href="#" title="Сформировать">-->
<!--                            <svg class="icon" width="35" height="35">-->
<!--                                <use xlink:href="--><?//=URI?><!--/assets/images/icons.svg#form"/>-->
<!--                            </svg>-->
<!--                        </a>-->
<!--                    --><?php //else: ?>
						<a class="no-decoration me-1 reload" data-href="<?=$this->data['attach']['link']?>" title="Сформировать">
							<svg class="icon" width="35" height="35">
								<use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
							</svg>
						</a>
<!--                    --><?php //endif; ?>
                </td>
                <td>
                    <?php if ($this->data['attach']['is_disable_mail']): ?>
                        <a class="no-decoration disabled me-1" href="#" title="Отправить клиенту">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1 <?=!empty($this->data['mail_list']) ? 'popup-mail' : ''?>" data-id="<?=$this->data['deal_id']?>" data-type="6" data-title="<?=$this->data['deal_title']?>" data-attach="<?=$this->data['attach']['actual_ver']?>"
                           href="<?= empty($this->data['mail_list']) ? "/mail.php?ID={$this->data['deal_id']}&TZ_ID={$this->data['tz_id']}&TYPE=6&EMAIL={$this->data['email']}&ATTACH={$this->data['attach']['actual_ver']}&TITLE={$this->data['deal_title']}" : "#email-check"?>"
                           title="Отправить клиенту"
                        >
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <input type="checkbox" data-text="ТЗ" class="form-check-input check-mail" <?=$this->data['attach']['check']? 'checked' : 'disabled'?>>
                </td>
                <td>
                    <a class="no-decoration <?=($this->data['attach']['check'] || $this->data['order']['check'] || $this->data['invoice']['check'])? '' : 'disabled'?> me-1 btnOverall"
                       href="#"
                       title="Отправить клиенту"
                       data-order-id="<?=$this->data['doc_id']?>"
                       data-deal-id="<?=$this->data['deal_id']?>"
                       data-tz-id="<?=$this->data['tz_id']?>"
                       data-email="<?=$this->data['email']?>"
                       data-name="<?=$this->data['main_assigned']?>"
                       data-attach1="<?=$this->data['attach1']?>"
                       data-attach2="<?=$this->data['attach2']?>"
                       data-attach3="<?=$this->data['attach3']?>"
                       data-title="<?=$this->data['deal_title']?>"
                    >
                        <svg class="icon" width="35" height="35">
                            <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                        </svg>
                    </a>
                </td>
            </tr>

            <tr class="<?=$this->data['invoice']['check']? 'table-green' : ''?>">
                <td><strong>Счет</strong></td>
                <td><?=$this->data['invoice']['number']?></td>
                <td><?=$this->data['invoice']['date']?></td>
                <td><?=$this->data['invoice']['date_send']?></td>
                <td>
                    <?php if ($this->data['invoice']['is_disable_form']): ?>
                        <a class="no-decoration disabled me-1" href="" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1 reloadS" data-href="<?=$this->data['invoice']['link']?>" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($this->data['invoice']['is_disable_mail']): ?>
                        <a class="no-decoration disabled me-1" href="" title="Отправить клиенту">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1 <?=!empty($this->data['mail_list']) ? 'popup-mail' : ''?>" data-id="<?=$this->data['deal_id']?>" data-type="4" data-title="<?=$this->data['deal_title']?>" data-attach="<?=$this->data['invoice']['attach']?>"
						   href="<?= empty($this->data['mail_list']) ? "/mail.php?ID={$this->data['deal_id']}&TZ_ID={$this->data['tz_id']}&TYPE=4&EMAIL={$this->data['email']}&TITLE={$this->data['deal_title']}&ATTACH={$this->data['invoice']['attach']}" : "#email-check"?>"
						   title="Отправить клиенту">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <input type="checkbox" data-text="Счет" class="form-check-input check-mail" <?=$this->data['invoice']['check']? 'checked' : 'disabled'?>>
                </td>
                <td></td>
            </tr>

            <tr class="<?=$this->data['payment']['check']? 'table-green' : ''?>">
                <td><strong>Оплата</strong></td>
                <td><?=$this->data['payment']['status']?></td>
                <td><?=$this->data['payment']['datePayment']?></td>
                <td>--</td>
                <td>
                    <a class="no-decoration me-1 popup-with-form <?=$this->data['payment']['check'] ? 'disabled' : ''?>" href="#pay-modal-form" title="Заполнить данные по оплате">
                        <svg class="icon" width="35" height="35">
                            <use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
                        </svg>
                    </a>
                </td>
                <td colspan="3">Остаток:<br><?=$this->data['payment']['surcharge']?> ₽</td>
            </tr>

            <tr class="<?=$this->data['sample']['check']? 'table-green' : ''?>">
                <td><strong>Акт приемки проб</strong></td>
                <td>
                    <?php if ($this->data['sample']['check']): ?>
                        <a href="/ulab/probe/card/<?=$this->data['deal_id']?>"><?=$this->data['sample']['number']?></a>
                    <?php else: ?>
                        Не сформировано
                    <?php endif; ?>
                </td>
                <td>
                    <?=$this->data['sample']['date']?>
                </td>
                <td>
                    <?=$this->data['sample']['date_send']?>
                </td>
                <td>
                    <?php if ($this->data['sample']['is_disable_form']): ?>
                        <a class="no-decoration disabled me-1 popup-with-form" href="#" title="Заполнить данные акта">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1" href="/ulab/probe/new/<?=$this->data['deal_id']?>" title="Заполнить данные акта">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($this->data['tz']['check'] && $this->data['sample']['check']): ?>
                        <a class="no-decoration me-1" href="<?=$this->data['sample']['link']?>" title="Скачать акт приемки проб">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#doc-send"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration disabled me-1" href="#" title="Скачать акт приемки проб">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#doc-send"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td></td>
                <td></td>
            </tr>

			<tr class="<?=$this->data['results']['check']? 'table-green' : ''?>">
				<td><strong>Результаты испытаний</strong></td>
				<td>
					<?php if ($this->data['results']['check']): ?>
                        <a href="<?= URI.'/result/card_oati/'.$this->data['deal_id']?>"><?=$this->data['deal_id']?></a>
                    <?php else: ?>
						Не внесены
					<?php endif; ?>
				</td>
				<td>
					<?=$this->data['results']['date']?>
				</td>
				<td>--</td>
				<td>
                    <?php if (!$this->data['results']['is_disabled']) :?>
                        <a class="no-decoration me-1" href="<?=URI.'/result/card_oati/'.$this->data['deal_id']?>" title="Внести результаты">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#enter"/>
                            </svg>
                        </a>
                    <?php else:?>
                        <a class="no-decoration me-1 disabled" href="#" title="Внести результаты">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#enter"/>
                            </svg>
                        </a>
                    <?php endif;?>
				</td>
				<td></td>
				<td></td>
				<td></td>
			</tr>

            <?php if ( empty($this->data['protocol']) ): ?>
                <tr>
                    <td><strong>Протокол</strong></td>
                    <td>
                        Не сформирован
                    </td>
                    <td>--</td>
                    <td>
                        Не отправлен
                    </td>
                    <td>
                        <a class="no-decoration disabled me-1" href="" title="Сформировать">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a class="no-decoration disabled me-1" href="" title="Отправить клиенту">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a class="no-decoration disabled me-1" href="" title="ЭЦП">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#ecp"/>
                            </svg>
                        </a>
                    </td>
                    <td>
                        <a class="no-decoration disabled me-1" href="" title="xml">
                            <img src="<?=URI?>/assets/images/xml_icon_2.png" alt="xml" width="35">
                        </a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($this->data['protocol'] as $protocol): ?>
                    <tr class="<?=$protocol['check']? 'table-green' : ''?>">
                        <td><strong>Протокол</strong></td>
                        <td>
                            <?=$protocol['title']?>
                        </td>
                        <td>
                            <?=$protocol['date']?>
                        </td>
                        <td>
                            <?=$protocol['date_send']?>
                        </td>
                        <td>
                            <?php if ($protocol['is_disable_form']): ?>
                                <a class="no-decoration disabled me-1" href="#" title="Сформировать">
                                    <svg class="icon" width="35" height="35">
                                        <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a class="no-decoration me-1 validate-protocol"
                                   data-protocol_id="<?=$protocol['id']?>"
                                   href="<?=$protocol['link']?>"
                                   title="Сформировать"
                                >
                                    <svg class="icon" width="35" height="35">
                                        <use xlink:href="<?=URI?>/assets/images/icons.svg#form"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($protocol['is_disable_mail']): ?>
                                <a class="no-decoration disabled me-1" href="#" title="Отправить клиенту">
                                    <svg class="icon" width="35" height="35">
                                        <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a class="no-decoration me-1 <?=!empty($this->data['mail_list']) ? 'popup-mail' : ''?>" data-id="<?=$protocol['number']?>" data-type="5" data-title="<?=$this->data['deal_title']?>" data-attach="<?=$protocol['actual_version']?>" data-sig="<?=$protocol['sig']?>" data-pdf="<?=$protocol['pdf']?>" data-year="<?=$protocol['year']?>" data-id_p="<?=$protocol['id']?>"
                                   href="<?=empty($this->data['mail_list']) ? "/mail.php?ID={$protocol['number']}&TZ_ID={$this->data['tz_id']}&TYPE=5&EMAIL={$this->data['email']}&NAME={$this->data['user']['name']}&ATTACH={$protocol['actual_version']}&TITLE={$this->data['deal_title']}&SIG={$protocol['sig']}&PDF={$protocol['pdf']}&YEAR={$protocol['year']}&ID_P={$protocol['id']}" : "#email-check"?>"
                                   title="Отправить клиенту"
                                >
                                    <svg class="icon" width="35" height="35">
                                        <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$protocol['is_enable_ecp']): ?>
                                <a class="no-decoration disabled me-1" href="#" title="ЭЦП">
                                    <svg class="icon" width="35" height="35">
                                        <use xlink:href="<?=URI?>/assets/images/icons.svg#ecp"/>
                                    </svg>
                                </a>
                            <?php else: ?>
                                <a class="no-decoration me-1"
                                   href="/ulab/protocol/sig/<?=$protocol['id']?>"
                                   title="ЭЦП"
                                >
                                    <svg class="icon" width="35" height="35">
                                        <use xlink:href="<?=URI?>/assets/images/icons.svg#ecp"/>
                                    </svg>
                                </a>
                            <?php endif; ?>

                        </td>
                        <td>
                            <a class="no-decoration me-1" href="<?=URI?>/fsa/protocol/<?=$protocol['id']?>" title="Страница создания XML протокола">
                                <img src="<?=URI?>/assets/images/xml_icon_2.png" alt="xml" width="35">
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <tr class="<?=!empty($this->data['is_end_test'])? 'table-green' : ''?>">
                <td><strong>Завершение испытаний</strong></td>
                <td><?=$this->data['is_end_test']? 'Завершено' : 'Не завершено'?></td>
                <td><?=$this->data['complete']['date']?></td>
                <td>--</td>
                <td>
                    <?php if ($this->data['is_end_test']): ?>
                        <?php if ($this->data['complete']['may_return']): ?>
                            <a class="no-decoration me-1" href="<?=URI?>/request/setStage/<?=$this->data['deal_id']?>?stage=0" title="Вернуть испытание на стадию формирования ТЗ">
                                <img src="<?=URI?>/assets/images/lockicon.png" alt="lock" width="35">
                            </a>
                        <?php else: ?>
                            <span title="Испытание завершено">
                                <a class="no-decoration disabled me-1" href="#">
                                    <img src="<?=URI?>/assets/images/lockicon.png" alt="lock" width="35">
                                </a>
                            </span>
                        <?php endif; ?>
                    <?php elseif ($this->data['complete']['is_disabled']): ?>
                        <span title="Отсутствует протокол, невозможно завершить испытание">
                            <a class="no-decoration me-1 disabled" href="#">
                                <img src="<?=URI?>/assets/images/openlock.png" alt="unlock" width="35">
                            </a>
                        </span>
                    <?php elseif ($this->data['complete']['may_complete']): ?>
                        <a class="no-decoration me-1" href="<?=URI?>/request/complete/<?=$this->data['deal_id']?>" title="Завершить испытание">
                            <img src="<?=URI?>/assets/images/openlock.png" alt="unlock" width="35">
                        </a>
                    <?php else:?>
                        <span title="Завершить испытание невозможно, не для всех проб сформирован протокол или присвоен номер">
                            <a class="no-decoration me-1 disabled" href="#">
                                <img src="<?=URI?>/assets/images/openlock.png" alt="unlock" width="35">
                            </a>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td></td>
            </tr>

            <tr class="<?=$this->data['act_complete']['check']? 'table-green' : ''?>">
                <td><strong>Акт выполненных работ</strong></td>
                <td>
                    <?php if ($this->data['act_complete']['check']): ?>
                        <?=$this->data['act_complete']['number']?>
                    <?php else: ?>
                        Не сформирован
                    <?php endif; ?>
                </td>
                <td>
                    <?=$this->data['act_complete']['date']?>
                </td>
                <td>
                    <?=$this->data['act_complete']['date_send']?>
                </td>
                <td>
				<?php if($this->data['act_complete']['is_disable_form']):?>
                    <span title="Заполнить данные акта возможно только на стадии 'Испытания завершины'(Работы в лаборатории завершены)">
                        <a class="no-decoration me-1 popup-with-form disabled" href="#act-work-modal-form">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
                            </svg>
                        </a>
                    </span>
				<?php else:?>
					<a class="no-decoration me-1 popup-with-form" href="#act-work-modal-form" title="Заполнить данные акта">
						<svg class="icon" width="35" height="35">
							<use xlink:href="<?=URI?>/assets/images/icons.svg#edit"/>
						</svg>
					</a>
				<?php endif;?>
                </td>
                <td>
                    <?php if ($this->data['act_complete']['is_disable_mail']): ?>
                        <a class="no-decoration disabled me-1" href="#" title="Отправить клиенту">
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <a class="no-decoration me-1"
                           href="/mail.php?ID=<?=$this->data['deal_id']?>&TZ_ID=<?=$this->data['tz_id']?>&TYPE=8&EMAIL=<?= !empty($this->data['acc_email']) ? $this->data['acc_email'] : $this->data['email']?>&NAME=<?=$this->data['user']['name']?>&ATTACH=<?=$this->data['act_complete']['attach']?>"
                           title="Отправить клиенту"
                        >
                            <svg class="icon" width="35" height="35">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#mail"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <form action="<?=URI?>/request/addComment/<?=$this->data['deal_id']?>" method="post">
            <div class="row">
                <div class="col-10">
                    <textarea style="min-width: 100%" name="comment" class="form-control" placeholder="Комментарий" required><?=$this->data['comment']?></textarea>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary m-0" type="submit" id="button-addon2">Добавить</button>
                </div>
            </div>
        </form>
    </div>
</div>

<a id="close_app" class="btn btn-primary popup-with-form" href="#finish-modal-form">Завершить заявку</a>

<div class="line-dashed"></div>

<div class="panel panel-default">
    <header class="panel-heading">
        Документы клиента
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-down"></a>
         </span>
    </header>
    <div class="panel-body" style="display: none;">
        <div class="row file-preview-container">
            <?php foreach ($this->data['user_files'] as $file): ?>
                <div class="col-2 file-preview-block d-flex flex-column">
                    <div class="file-preview-img">
                        <img src="<?=$file['img']?>" alt="ico" width="90">
                    </div>
                    <div class="file-preview-title align-center">
                        <a class="text-decoration-none" href="/ulab/upload/request/<?=$this->data['deal_id']?>/<?=$file['name']?>" ><?=$file['name']?></a>
                    </div>
                    <?php if ($this->data['is_managers']): ?>
                        <div class="file-preview-back flex-column">
                            <a class="btn btn-danger" href="/ulab/request/deleteFile/<?=$this->data['deal_id']?>?file=<?=$file['name']?>">Удалить</a>
                            <a download class="btn btn-success" href="/ulab/upload/request/<?=$this->data['deal_id']?>/<?=$file['name']?>">Скачать</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="line-dashed"></div>

        <div class="dropzone-msg">

        </div>

        <div>
            <form id="dropzone-example" class="dropzone dz-clickable" action="/ulab/request/uploadUserFileAjax/<?=$this->data['deal_id']?>">
                <div class="dropzone-previews"></div>
                <div class="dz-default dz-message"><span>Добавьте файлы для загрузки</span></div>
            </form>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Списки версий
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-down"></a>
         </span>
    </header>
    <div class="panel-body" style="display: none;">
        <table class="table align-middle table-card">
            <thead>
            <tr class="table-light">
                <th scope="col">Протокол</th>
                <th scope="col">КП</th>
                <th scope="col">Договор</th>
                <th scope="col">Прил. к договору (тз)</th>
                <th scope="col">Счет</th>
                <th scope="col">Акт ВР</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="align-top">
                    <?php foreach ($this->data['file']['protocol'] as $item): ?>
                        <span><strong>Данные протокола №<?=$item['number']?></strong></span>
                        <br>
                        <?php foreach ($item['files'] as $file): ?>
                            <a  href="<?=$item['dir']?><?=$file?>?v=<?=rand()?>"><?=$file?></a><br>
                        <?php endforeach; ?>
                        <br>
                    <?php endforeach; ?>
                </td>
                <td class="align-top">
                    <?php foreach ($this->data['file']['kp']['files'] as $item): ?>
                        <a  href="<?=$this->data['file']['kp']['dir']?><?=$item?>"><?=$item?></a><br>
                    <?php endforeach; ?>
                </td>
                <td class="align-top">
                    <?php foreach ($this->data['file']['order']['files'] as $item): ?>
                        <a  href="<?=$this->data['file']['order']['dir']?><?=$item?>"><?=$item?></a><br>
                    <?php endforeach; ?>
                </td>
                <td class="align-top">
                    <?php foreach ($this->data['file']['tz']['files'] as $item): ?>
                        <a  href="<?=$this->data['file']['tz']['dir']?><?=$item?>"><?=$item?></a><br>
                    <?php endforeach; ?>
                </td>
                <td class="align-top">
                    <?php foreach ($this->data['file']['invoice']['files'] as $item): ?>
                        <a  href="<?=$this->data['file']['invoice']['dir']?><?=$item?>"><?=$item?></a><br>
                    <?php endforeach; ?>
                </td>
                <td class="align-top">
                    <?php foreach ($this->data['file']['act']['files'] as $item): ?>
                        <a  href="<?=$this->data['file']['act']['dir']?><?=$item?>"><?=$item?></a><br>
                    <?php endforeach; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="line-dashed"></div>


<form id="act-probe-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="<?=URI?>/probe/insertUpdateActProbe/<?=$this->data['deal_id']?>" method="post">
    <div class="title mb-3 h-2">
        Данные акта приемки проб
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="act[act_id]" value="<?=$this->data['sample']['act_id']?>">
    <input type="hidden" name="act[deal_id]" value="<?=$this->data['deal_id']?>">
    <input type="hidden" name="act[tz_id]" value="<?=$this->data['tz_id']?>">

	<div class="mb-3">
		<label class="form-label">Тип</label>
		<select type="date" class="form-control" name="act[actType]">
			<option value="1" <?=$this->data['sample']['act_type'] == 1? 'selected' : ''?>>Акт приемки проб</option>
			<option value="2" <?=$this->data['sample']['act_type'] == 2? 'selected' : ''?>>Контрольный лист</option>
		</select>
	</div>

    <div class="mb-3">
        <label class="form-label">Дата акта ПП</label>
        <input type="date" class="form-control" name="act[actDate]" value="<?=$this->data['sample']['date_act'] ?: date('Y-m-d')?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Место отбора проб</label>
        <input type="text" class="form-control" name="act[samplePlace]" value="<?=htmlentities($this->data['sample']['place_probe'])?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Дата отбора проб</label>
        <input type="date" class="form-control" name="act[sampleDate]" value="<?=$this->data['sample']['date_probe']?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Описание объекта</label>
        <input type="text" class="form-control" name="act[description]" value="<?=$this->data['sample']['description']?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Привязка к карьеру</label>
        <select type="text" class="form-control" name="act[quarry]">
            <option value=''>Выберите карьер</option>
            <?php foreach($this->data['quarry'] as $key => $value): ?>
                <option value='<?=$value['ID']?>' <?=$this->data['sample']['quarry_id'] == $value['ID']? 'selected' : ''?>><?=$value['NAME']?></option>
            <?php endforeach;?>
        </select>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input act-manual-edit" name='act[exampleCheck1]' id="exampleCheck1" <?=$this->data['sample']['selection_type'] == 1 ? 'checked' : ''?>>
        <label class="form-check-label" for="exampleCheck1">Пробы отобраны не заказчиком</label>
    </div>
    <div class="<?=$this->data['sample']['selection_type'] != 1 ? 'visually-hidden' : ''?> act-manual-block">
        <div class="mb-3">
            <label class="form-label">Отбор проб произвел</label>
            <input type="text" class="form-control" name="act[sampleMaker]" value="<?=$this->data['sample']['PROBE_PROIZV']?>">
        </div>
    </div>
	<div class="mb-3">
		<label class="form-label">Пробы предоставил</label>
		<input type="text" class="form-control" name="act[deliveryman]" value="<?=$this->data['sample']['deliveryman']?>">
	</div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary submit-act-probe">Сохранить</button>
</form>

<form id="act-work-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/request/card/<?=$this->data['deal_id']?>" method="post">
    <div class="title mb-3 h-2">
        Данные акта выполненных работ
    </div>
    
    <div class="line-dashed-small"></div>

    <div class="mb-3">
        <label class="form-label">Номер акта</label>
        <input type="number" name="actNumber" step="1" class="form-control" value="<?=$this->data['act_vr']['NUMBER'] ?? ''?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Дата акта</label>
        <input type="date" name="actDate" class="form-control" value="<?=$this->data['act_vr']['DATE'] ?? ''?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Руководитель</label>
        <select name="lead" class="form-control" required>
            <option value="" <?=$this->data['act_vr']['LEAD'] == '' ? 'selected' : ''?> disabled>Выберите руководителя</option>
            <?php foreach ($this->data['act_complete']['assigned_users'] as $user): ?>
                <option <?=$this->data['act_vr']['LEAD'] == $user['id'] ? 'selected' : ''?> value="<?=$user['id']?>"><?=$user['short_name']?></option>
            <?php endforeach; ?>
        </select>
    </div>

	<div class="mb-3">
		<label class="form-label">Email отправки:</label>
		<input type="text" name="Email" list="mail_list" class="form-control" value="<?=$this->data['act_complete']['email']?>">
		<datalist id="mail_list">
			<?php foreach ($this->data['list_email'] as $email): ?>
				<option value="<?= $email ?>"><?= $email ?></option>
			<?php endforeach; ?>
		</datalist>
	</div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>

<form id="pay-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/request/setOplata/" method="post">
    <div class="title mb-3 h-2">
        Данные оплаты
    </div>

    <div class="line-dashed-small"></div>

    <div class="mb-3">
        <label class="form-label">Оплата</label>
        <input type="number" name="pay" step="0.01" min="0.01" max="<?=$this->data['payment']['surcharge']?>" class="form-control" value="<?=$this->data['payment']['surcharge']?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Дата оплаты</label>
        <input type="date" name="payDate" class="form-control" value="<?=date('Y-m-d')?>">
    </div>

    <input name="deal_id" value="<?=$this->data['deal_id']?>" type="hidden">
    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="email-check" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/mail.php" method="get">
	<input name="ID" id="ID" value="" type="hidden">
	<input name="TZ_ID" value="<?=$this->data['tz_id']?>" type="hidden">
	<input name="TYPE" id="TYPE" value="" type="hidden">
	<div class="mb-3">
		<input type="checkbox" name="EMAIL[]" value="<?=$this->data['email']?>" checked>
		<label class="form-label"><?=$this->data['email']?></label>
	</div>
	<?php foreach ($this->data['mail_list'] as $key => $email): ?>
		<div class="mb-3">
			<input type="checkbox" name="EMAIL[]" value='<?=$email?>'>
			<label class="form-label"><?=$email?></label>
		</div>
	<?php endforeach; ?>
	<input name="NAME" value="<?=$this->data['user']['name']?>" type="hidden">
	<input name="ATTACH" id="ATTACH" value="" type="hidden">
	<input name="TITLE" id="TITLE" value="" type="hidden">
	<input name="SIG" id="SIG" value="" type="hidden">
	<input name="PDF" id="PDF" value="" type="hidden">
	<input name="YEAR" id="YEAR" value="" type="hidden">
	<input name="ID_P" id="ID_P" value="" type="hidden">

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Отправить</button>
</form>

<div id="finish-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Выберите результат, с которым будет закрыта сделка
    </div>

    <div class="line-dashed-small"></div>

    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="row mb-1">
        <div class="col"><button data-stage="4" style="min-width: 100%" class="akt-finish btn btn-primary">Акты отправлены</button></div>
        <div class="col">
            <a href="#finish-modal-form-2" style="min-width: 91.4%" class="deal-lost btn btn-primary popup-with-form">Сделка проиграна</a>
        </div>
    </div>

    <div>
        <button data-stage="WON" style="min-width: 100%" class="akt-finish btn btn-primary">Акты получены, сделка завершена</button>
    </div>
</div>

<div id="finish-modal-form-2" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Закрытие сделки
    </div>

    <div class="line-dashed-small"></div>

    <input name="tz_id" value="<?=$this->data['tz_id']?>" type="hidden">

    <div class="mb-1">
        <button data-stage="LOSE" style="min-width: 100%" class="akt-finish btn btn-primary">Сделка не состоялась</button>
    </div>
    <div class="mb-1">
        <button data-stage="5" style="min-width: 100%" class="akt-finish btn btn-primary">Не устроила цена</button>
    </div>
    <div class="mb-1">
        <button data-stage="6" style="min-width: 100%" class="akt-finish btn btn-primary">Заказчик не выходит на связь</button>
    </div>
    <div class="mb-1">
        <button data-stage="7" style="min-width: 100%" class="akt-finish btn btn-primary">Не проводим подобные испытания</button>
    </div>
    <div class="mb-1">
        <button data-stage="8" style="min-width: 100%" class="akt-finish btn btn-primary">Создана другая заявка по данному запросу</button>
    </div>
    <div class="mb-1">
        <button data-stage="9" style="min-width: 100%" class="akt-finish btn btn-primary">Заказчик выбрал лабораторию в своем городе</button>
    </div>
    <div class="mb-1">
        <button data-stage="10" style="min-width: 100%" class="akt-finish btn btn-primary">Заказчик решил не проводить испытания</button>
    </div>
    <div class="mb-1">
        <button data-stage="11" style="min-width: 100%" class="akt-finish btn btn-primary">Отказались сами - большая загруженность</button>
    </div>
    <div class="mb-1">
        <button data-stage="12" style="min-width: 100%" class="akt-finish btn btn-primary">Судебная экспертиза</button>
    </div>
    <div>
        <button data-stage="13" style="min-width: 100%" class="akt-finish btn btn-primary">Участие в тендере</button>
    </div>
</div>
