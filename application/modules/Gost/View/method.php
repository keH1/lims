<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/gost/edit/<?=$this->data['form']['gost_id']?>" title="Вернуться к ГОСТу">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link" href="/validation_card.php?ID=<?=$this->data['form']['id']?>" title="Сформировать отчет о валидации и верификации методики">
                    Сформировать отчет
                </a>
            </li>
        </ul>
    </nav>
</header>

<form class="form-horizontal" method="post" action="<?=URI?>/gost/updateMethod/">
    <div class="panel panel-default">
        <header class="panel-heading">
            Основные характеристики
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <input id="method_id" type="hidden" value="<?=$this->data['form']['id']?>" name="id">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Номер документа</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?=$this->data['form']['reg_doc'] ?? ''?>" readonly>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Пункт документа</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="form[clause]" value="<?=$this->data['form']['clause'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Определяемая характеристика / показатель</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="form[name]" value="<?=htmlentities($this->data['form']['name'] ?? '')?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Определяемая характеристика / показатель</label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="form[measured_properties_id]">
                        <option value="">Выбрать показатель</option>
                        <?php foreach ($this->data['measured_properties'] as $item): ?>
                            <option value="<?=$item['id']?>" <?=$this->data['form']['measured_properties_id'] == $item['id']? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <a class="btn btn-square btn-outline-secondary" href="/ulab/reference/measuredPropertiesList/<?=$this->data['form']['id']?>" title="Журнал">
                        <i class="fa-solid fa-list icon-fix-2"></i>
                    </a>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Метод <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="form[test_method_id]" required>
                        <option value="">Выбрать метод</option>
                        <?php foreach ($this->data['test_method_list'] as $item): ?>
                            <option value="<?=$item['id']?>" <?=$this->data['form']['test_method_id'] == $item['id']? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2">

                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Номер в OA</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control appearance-none" name="form[num_oa]" step="1" value="<?=$this->data['form']['num_oa'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Методика не для испытаний</label>
                <div class="col-sm-8">
                    <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="form[is_selection]"
                        <?=$this->data['form']['is_selection'] == 1? 'checked' : ''?>
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Единицы измерения и нормы
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">

            <div class="result-block">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Единица измерения</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" name="form[unit_id]" required>
                            <option value="">Выбрать</option>
                            <?php foreach ($this->data['unit_list'] as $unit): ?>
                                <option value="<?=$unit['id']?>" <?=$this->data['form']['unit_id'] == $unit['id'] ? 'selected' : ''?>><?=htmlentities($unit['unit_rus'])?> | <?=htmlentities($unit['name'])?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="col-sm-2">
                            <a class="btn btn-square btn-outline-secondary" href="/ulab/reference/unitList/<?=$this->data['form']['id']?>" title="Журнал">
                                <i class="fa-solid fa-list icon-fix-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Знаков после запятой</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="form[decimal_places]" value="<?=$this->data['form']['decimal_places'] ?? ''?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Диапазон определения</label>
                    <div class="col-sm-8">
                        <div class="input-group" id="definition-range-block">
                            <span class="input-group-text text-def-1"><?=$this->data['form']['definition_range_type'] == 2? 'до' : 'от'?></span>
                            <input type="number" class="form-control" step="0.001" name="form[definition_range_1]" value="<?=$this->data['form']['definition_range_1'] ?? ''?>">
                            <span class="input-group-text text-def-2"><?=$this->data['form']['definition_range_type'] == 2? 'от' : 'до'?></span>
                            <input type="number" class="form-control" step="0.001" name="form[definition_range_2]" value="<?=$this->data['form']['definition_range_2'] ?? ''?>">
                            <div class="input-group-text">
                                <input
                                    id="r1"
                                    class="form-check-input mt-0 me-1"
                                    type="radio" value="1"
                                    name="form[definition_range_type]"
                                    <?=($this->data['form']['definition_range_type'] == 1 || empty($this->data['form']['definition_range_type']))? 'checked' : ''?>
                                >
                                <label class="form-check-label" for="r1">
                                    Внутренний диапазон
                                </label>
                            </div>
                            <div class="input-group-text">
                                <input id="r2" class="form-check-input mt-0 me-1" type="radio" value="2" name="form[definition_range_type]" <?=$this->data['form']['definition_range_type'] == 2? 'checked' : ''?>>
                                <label class="form-check-label" for="r2">
                                    Внешний диапазон
                                </label>
                            </div>
                            <div class="input-group-text">
                                <input id="r3" class="form-check-input mt-0 me-1" type="radio" value="3" name="form[definition_range_type]" <?=$this->data['form']['definition_range_type'] == 3? 'checked' : ''?>>
                                <label class="form-check-label" for="r3">
                                    Не нормируется
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Текст диапазона</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-text">
                                <input
                                        class="form-check-input mt-0 is_range_check"
                                        type="checkbox"
                                        value="1"
                                        aria-label=""
                                        name="form[is_range_text]"
                                    <?=$this->data['form']['is_range_text'] == 1? 'checked' : ''?>
                                >
                            </div>
                            <span class="input-group-text text-def-1 <?=$this->data['form']['is_range_text'] == 1? '' : 'd-none'?> range_text_elem">Текст более</span>
                            <input name="form[range_text_out]" type="text" class="form-control <?=$this->data['form']['is_range_text'] == 1? '' : 'd-none'?> range_text_elem" value="<?=$this->data['form']['range_text_out'] ?? ''?>" maxlength="64" aria-label="">
                            <span class="input-group-text text-def-1 <?=$this->data['form']['is_range_text'] == 1? '' : 'd-none'?> range_text_elem">Текст менее</span>
                            <input name="form[range_text_in]" type="text" class="form-control <?=$this->data['form']['is_range_text'] == 1? '' : 'd-none'?> range_text_elem" value="<?=$this->data['form']['range_text_in'] ?? ''?>" maxlength="64" aria-label="">
                        </div>
                    </div>
                </div>

                <!--<div class="form-group row">
                    <label class="col-sm-2 col-form-label">Нормы текстом?</label>
                    <div class="col-sm-8">
                        <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                name="form[is_text_norm]"
                            <?/*=$this->data['form']['is_text_norm'] == 1? 'checked' : ''*/?>
                        >
                    </div>
                    <div class="col-sm-2"></div>
                </div>-->

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Факт. значения текстом?</label>
                    <div class="col-sm-8">
                        <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                name="form[is_text_fact]"
                            <?=$this->data['form']['is_text_fact'] == 1? 'checked' : ''?>
                        >
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Лист измерения</label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="form[measurement_id]">
                        <option value="">Выбрать лист</option>
                        <?php foreach ($this->data['measurement_list'] as $item): ?>
                            <option value="<?=$item['id']?>" <?=$this->data['form']['measurement_id'] == $item['id']? 'selected' : ''?>><?=$item['name_ru']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2">
                </div>
            </div>

            <div class="line-dashed"></div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Два результата?</label>
                <div class="col-sm-8">
                    <input
                            id="is_two_results"
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="form[is_two_results]"
                        <?=$this->data['form']['is_two_results'] == 1? 'checked' : ''?>
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div id="two_result_block" style="display: <?=$this->data['form']['is_two_results'] == 1? 'block' : 'none'?>;">

                <div class="line-dashed"></div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Единица измерения 2</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" name="form[unit_id_2]">
                            <option value="">Выбрать</option>
                            <?php foreach ($this->data['unit_list'] as $unit): ?>
                                <option value="<?=$unit['id']?>" <?=$this->data['form']['unit_id_2'] == $unit['id'] ? 'selected' : ''?>><?=$unit['unit_rus']?> | <?=$unit['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Знаков после запятой 2</label>
                    <div class="col-sm-8">
                        <input type="number" step="1" class="form-control" name="form[decimal_places_2]" value="<?=$this->data['form']['decimal_places_2'] ?? ''?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Диапазон определения 2</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <span class="input-group-text">от</span>
                            <input type="number" class="form-control" step="0.001" name="form[definition_range_1_2]" value="<?=$this->data['form']['definition_range_1_2'] ?? ''?>">
                            <span class="input-group-text">до</span>
                            <input type="number" class="form-control" step="0.001" name="form[definition_range_2_2]" value="<?=$this->data['form']['definition_range_2_2'] ?? ''?>">
                            <div class="input-group-text">
                                <input
                                        id="r1_2"
                                        class="form-check-input mt-0 me-1"
                                        type="radio" value="1"
                                        name="form[definition_range_type_2]"
                                    <?=($this->data['form']['definition_range_type_2'] == 1 || empty($this->data['form']['definition_range_type_2']))? 'checked' : ''?>
                                >
                                <label class="form-check-label" for="r1_2">
                                    Внутренний диапазон
                                </label>
                            </div>
                            <div class="input-group-text">
                                <input id="r2_2" class="form-check-input mt-0 me-1" type="radio" value="2" name="form[definition_range_type_2]" <?=$this->data['form']['definition_range_type_2'] == 2? 'checked' : ''?>>
                                <label class="form-check-label" for="r2_2">
                                    Внешний диапазон
                                </label>
                            </div>
                            <div class="input-group-text">
                                <input id="r3_2" class="form-check-input mt-0 me-1" type="radio" value="3" name="form[definition_range_type_2]" <?=$this->data['form']['definition_range_type_2'] == 3? 'checked' : ''?>>
                                <label class="form-check-label" for="r3_2">
                                    Не нормируется
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Нормы 2 текстом?</label>
                    <div class="col-sm-8">
                        <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                name="form[is_text_norm_2]"
                            <?=$this->data['form']['is_text_norm_2'] == 1? 'checked' : ''?>
                        >
                    </div>
                    <div class="col-sm-2"></div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Факт. значения 2 текстом?</label>
                    <div class="col-sm-8">
                        <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                name="form[is_text_fact_2]"
                            <?=$this->data['form']['is_text_fact_2'] == 1? 'checked' : ''?>
                        >
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>

            <div class="line-dashed"></div>

            <button class="btn btn-success add-result d-none" type="button">Добавить результат</button>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Лаборатории и сотрудники
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Лаборатории</label>
                <div class="col-sm-8">
                    <select id="select-lab" class="form-control select2" name="form[lab][]" multiple="multiple">
                        <?php foreach ($this->data['lab_list'] as $item): ?>
                            <option value="<?=$item['ID']?>" <?=in_array($item['ID'], $this->data['lab'])? 'selected' : ''?>><?=$item['NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Помещения</label>
                <div class="col-sm-8">
                    <select id="select-room" class="form-control select2" name="form[room][]" multiple="multiple">
                        <?php if (empty($this->data['room_list'])): ?>
                            <option value="" disabled>Сначала выберите лаборатории</option>
                        <?php endif; ?>

                        <?php foreach ($this->data['room_list'] as $item): ?>
                            <option value="<?=$item['ID']?>" <?=in_array($item['ID'], $this->data['room'])? 'selected' : ''?>><?=$item['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Ответственные</label>
                <div class="col-sm-8">
                    <select id="select-assigned" class="form-control select2" name="form[assigned][]" multiple="multiple">
                        <?php foreach ($this->data['user_list'] as $user): ?>
                            <option value="<?=$user['user_id']?>" <?=in_array($user['user_id'], $this->data['assigned'])? 'selected' : ''?>><?=$user['last_name']?> <?=$user['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Условия применения
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Температура (°С)
                </label>
                <div class="col-sm-8">
                    <div class="input-group condition-group">
                        <span class="input-group-text">от</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control range-from"
                                name="form[cond_temp_1]"
                                value="<?=$this->data['form']['cond_temp_1'] ?? ''?>"
                            <?=$this->data['form']['is_not_cond_temp'] != 1? '' : 'readonly'?>
                        >
                        <span class="input-group-text">до</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control range-to"
                                name="form[cond_temp_2]"
                                value="<?=$this->data['form']['cond_temp_2'] ?? ''?>"
                            <?=$this->data['form']['is_not_cond_temp'] != 1? '' : 'readonly'?>
                        >
                        <div class="input-group-text">
                            <input
                                    id="ch1"
                                    class="form-check-input mt-0 me-1"
                                    type="checkbox"
                                    value="1"
                                    name="form[is_not_cond_temp]"
                                <?=$this->data['form']['is_not_cond_temp'] == 1? 'checked' : ''?>
                            >
                            <label class="form-check-label" for="ch1">
                                Не нормируется
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Влажность (%)
                </label>
                <div class="col-sm-8">
                    <div class="input-group condition-group">
                        <span class="input-group-text">от</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control range-from"
                                name="form[cond_wet_1]"
                                value="<?=$this->data['form']['cond_wet_1'] ?? ''?>"
                            <?=$this->data['form']['is_not_cond_wet'] != 1? '' : 'readonly'?>
                        >
                        <span class="input-group-text">до</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control range-to"
                                name="form[cond_wet_2]"
                                value="<?=$this->data['form']['cond_wet_2'] ?? ''?>"
                            <?=$this->data['form']['is_not_cond_wet'] != 1? '' : 'readonly'?>
                        >
                        <div class="input-group-text">
                            <input
                                    id="ch2"
                                    class="form-check-input mt-0 me-1"
                                    type="checkbox"
                                    value="1"
                                    name="form[is_not_cond_wet]"
                                <?=$this->data['form']['is_not_cond_wet'] == 1? 'checked' : ''?>
                            >
                            <label class="form-check-label" for="ch2">
                                Не нормируется
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Атм. давление (КПа)
                </label>
                <div class="col-sm-8">
                    <div class="input-group condition-group">
                        <span class="input-group-text">от</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control range-from"
                                name="form[cond_pressure_1]"
                                value="<?=$this->data['form']['cond_pressure_1'] ?? ''?>"
                            <?=$this->data['form']['is_not_cond_pressure'] != 1? '' : 'readonly'?>
                        >
                        <span class="input-group-text">до</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control range-to"
                                name="form[cond_pressure_2]"
                                value="<?=$this->data['form']['cond_pressure_2'] ?? ''?>"
                            <?=$this->data['form']['is_not_cond_pressure'] != 1? '' : 'readonly'?>
                        >
                        <div class="input-group-text">
                            <input
                                    id="ch3"
                                    class="form-check-input mt-0 me-1"
                                    type="checkbox"
                                    value="1"
                                    name="form[is_not_cond_pressure]"
                                <?=$this->data['form']['is_not_cond_pressure'] == 1? 'checked' : ''?>
                            >
                            <label class="form-check-label" for="ch3">
                                Не нормируется
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Дополнительные характеристики
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Стоимость (руб.)</label>
                <div class="col-sm-8">
                    <input type="number" step="0.01" class="form-control" name="form[price]" value="<?=$this->data['form']['price'] ?? '0.00'?>">
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Контроль
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Актуально</label>
                <div class="col-sm-8">
                    <?=$this->data['form']['is_actual'] == 1 ? 'Да' : 'Нет'?>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Подтверждено</label>
                <div class="col-sm-8">
                    <?=$this->data['form']['is_confirm'] == 1 ? 'Да' : 'Нет'?>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">В области аккредитации?</label>
                <div class="col-sm-8">
                    <input
                            id="in_field"
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="form[in_field]"
                        <?=$this->data['form']['in_field'] == 1? 'checked' : ''?>
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Расширенная область?</label>
                <div class="col-sm-8">
                    <input
                            id="extended_field"
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="form[is_extended_field]"
                        <?=$this->data['form']['is_extended_field'] == 1? 'checked' : ''?>
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Расчет неопределенности
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <div class="form-group row uncertainty-block">
                <label class="col-lg-12 col-xl-2 col-form-label">
                    Неопределенность
                </label>
                <div class="col-lg-11 col-xl-8">
                    <div class="input-group uncertainty-group">
                        <span class="input-group-text">от</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control uncertainty-from"
                                name="uncertainty[0][uncertainty_1]"
                                value="<?=$this->data['uncertainty'][0]['uncertainty_1'] ?? ''?>"
                        >
                        <span class="input-group-text">до</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control uncertainty-to"
                                name="uncertainty[0][uncertainty_2]"
                                value="<?=$this->data['uncertainty'][0]['uncertainty_2'] ?? ''?>"
                        >
                        <span class="input-group-text">U(w)</span>
                        <input
                                type="number"
                                step="0.001"
                                class="form-control"
                                name="uncertainty[0][uncertainty_3]"
                                value="<?=$this->data['uncertainty'][0]['uncertainty_3'] ?? ''?>"
                        >
                        <span class="input-group-text">Rл</span>
                        <input
                                type="number"
                                step="any"
                                class="form-control"
                                name="uncertainty[0][Rl]"
                                value="<?=$this->data['uncertainty'][0]['Rl'] ?? ''?>"
                        >
                        <span class="input-group-text">r</span>
                        <input
                                type="number"
                                step="any"
                                class="form-control"
                                name="uncertainty[0][r]"
                                value="<?=$this->data['uncertainty'][0]['r'] ?? ''?>"
                        >
                        <span class="input-group-text">Кт</span>
                        <input
                                type="number"
                                step="any"
                                class="form-control"
                                name="uncertainty[0][Kt]"
                                value="<?=$this->data['uncertainty'][0]['Kt'] ?? ''?>"
                        >
                    </div>
                </div>
                <div class="col-lg-1 col-xl-2">
                    <button
                            id="add_uncertainty"
                            type="button"
                            class="btn btn-primary btn-square"
                            title="Добавить Неопределенность">
                        <i class="fa-solid fa-plus icon-fix"></i>
                    </button>
                </div>
            </div>

            <?php
            foreach ($this->data['uncertainty'] as $k => $item):
                if ($k == 0) { continue; }
            ?>
                <div class="form-group row uncertainty-block">
                    <label class="col-sm-2 col-form-label">
                    </label>
                    <div class="col-sm-8">
                        <div class="input-group uncertainty-group">
                            <span class="input-group-text">от</span>
                            <input
                                    type="number"
                                    step="0.001"
                                    class="form-control uncertainty-from"
                                    name="uncertainty[<?=$k?>>][uncertainty_1]"
                                    value="<?=$item['uncertainty_1']?>"
                            >
                            <span class="input-group-text">до</span>
                            <input
                                    type="number"
                                    step="0.001"
                                    class="form-control uncertainty-to"
                                    name="uncertainty[<?=$k?>][uncertainty_2]"
                                    value="<?=$item['uncertainty_2']?>"
                            >
                            <span class="input-group-text">U(w)</span>
                            <input
                                    type="number"
                                    step="0.001"
                                    class="form-control"
                                    name="uncertainty[<?=$k?>][uncertainty_3]"
                                    value="<?=$item['uncertainty_3']?>"
                            >
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button
                                type="button"
                                class="btn btn-danger btn-square remove_uncertainty"
                                title="Удалить Неопределенность">
                            <i class="fa-solid fa-minus icon-fix"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Оборудование
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body overflow-auto">
            <table class="table table-striped">
                <thead>
                <tr class="table-light">
                    <th scope="col">Оборудование ГОСТ</th>
                    <th scope="col">Оборудование ИЦ</th>
                    <th scope="col" style="width: 50px;"></th>
                    <th scope="col" style="width: 50px;">Тип</th>
                    <th scope="col">Время использования, ч</th>
                    <th scope="col">Заключение</th>
                    <th scope="col" style="width: 50px;">
                        <button
                                id="add_oborud"
                                type="button"
                                class="btn btn-primary btn-square"
                                title="Добавить оборудование">
                            <i class="fa-solid fa-plus icon-fix"></i>
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr class="align-middle oborud-block">
                    <td colspan="7"></td>
                </tr>

                <tr class="align-middle oborud-block">
                    <td>
                        <input
                                type="text"
                                class="form-control"
                                name="oborud[0][gost]"
                                value="<?=$this->data['method_oborud_list'][0]['gost'] ?? ''?>"
                        >
                    </td>
                    <td>
                        <select class="form-control select2 oborud-select" name="oborud[0][id_oborud]">
                            <option value="">Выбрать оборудование</option>
                            <option value="0">Нет оборудования</option>
                            <?php foreach ($this->data['oborud'] as $item): ?>
                                <option value="<?=$item['ID']?>" <?=$this->data['method_oborud_list'][0]['id_oborud'] == $item['ID']? 'selected' : ''?>><?=$item['OBJECT']?> | <?=$item['FACTORY_NUMBER']?> | <?=$item['REG_NUM']?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td class="link-place">
                        <?php if (isset($this->data['method_oborud_list'][0]['id_oborud'])): ?>
                            <a class="text-dark fs-4"  title="Перейти в оборудование" target="_blank" href="/ulab/oborud/edit/<?=$this->data['method_oborud_list'][0]['id_oborud']?>">
                                <i class="fa-regular fa-clipboard"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="ident-place">
                        <?=$this->data['method_oborud_list'][0]['ident'] ?? ''?>
                    </td>
                    <td>
                        <input type="number" step="0.1" min="0" class="form-control usage-time" name="oborud[0][usage_time]" value="<?=$this->data['method_oborud_list'][0]['usage_time'] ?? ''?>">
                    </td>
                    <td>
                        <input
                                type="text"
                                class="form-control"
                                name="oborud[0][comment]"
                                value="<?=$this->data['method_oborud_list'][0]['comment'] ?? ''?>"
                        >
                    </td>
                    <td>
                        <button
                                type="button"
                                class="btn btn-danger btn-square remove_oborud"
                                title="Удалить оборутование">
                            <i class="fa-solid fa-minus icon-fix"></i>
                        </button>
                    </td>
                </tr>
                <?php foreach ($this->data['method_oborud_list'] as $key => $item):
                    if ( $key == 0 ) { continue; }
                ?>
                    <tr class="align-middle oborud-block">
                        <td>
                            <input
                                    type="text"
                                    class="form-control"
                                    name="oborud[<?=$key?>][gost]"
                                    value="<?=$item['gost']?>"
                            >
                        </td>
                        <td>
                            <select class="form-control select2 oborud-select" name="oborud[<?=$key?>][id_oborud]">
                                <option value="">Выбрать оборудование</option>
                                <option value="0">Нет оборудования</option>
                                <?php foreach ($this->data['oborud'] as $oborud): ?>
                                    <option value="<?=$oborud['ID']?>" <?=$item['id_oborud'] == $oborud['ID']? 'selected' : ''?>><?=$oborud['OBJECT']?> | <?=$oborud['FACTORY_NUMBER']?> | <?=$oborud['REG_NUM']?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="link-place">
                            <a class="text-dark fs-4"  title="Перейти в оборудование" target="_blank" href="/ulab/oborud/edit/<?=$item['id_oborud']?>">
                                <i class="fa-regular fa-clipboard"></i>
                            </a>
                        </td>
                        <td class="ident-place">
                            <?=$item['ident']?>
                        </td>
                        <td>
                            <input type="number" step="0.1" min="0" class="form-control usage-time" name="oborud[<?=$key?>][usage_time]" value="<?=$item['usage_time']?>">
                        </td>
                        <td>
                            <input
                                    type="text"
                                    class="form-control"
                                    name="oborud[<?=$key?>][comment]"
                                    value="<?=$item['comment']?>"
                            >
                        </td>
                        <td>
                            <button
                                    type="button"
                                    class="btn btn-danger btn-square remove_oborud"
                                    title="Удалить оборудование">
                                <i class="fa-solid fa-minus icon-fix"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <button class="btn btn-primary me-3" type="submit" title="Сохранение методики. Снимает статус 'Подтвержден'">Сохранить</button>

    <a class="btn btn-success me-3" href="<?=URI?>/gost/confirmMethod/<?=$this->data['form']['id']?>">Проверено</a>

    <a class="btn btn-danger me-3" href="<?=URI?>/gost/nonActualMethod/<?=$this->data['form']['id']?>">Не актуально</a>
</form>
