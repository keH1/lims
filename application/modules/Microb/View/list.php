<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <div class="two-button">
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Отбор
                </button>
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Посев
                </button>
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-third btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Результаты посева
                </button>
            </div>
        </div>
        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter">
                <?php
                foreach ($this->data['microb_type_controlPlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateStart"
                   class="form-control filter filter-date-start"
                   value="" title="Введите дату начала">
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateEnd"
                   class="form-control filter filter-date-end"
                   value=""
                   title="Введите дату окончания">
        </div>
        <div class="col-auto">
            <button type="button"
                    class="btn btn-outline-secondary filter-btn-reset"
                    title="Сбросить фильтр">Сбросить
            </button>
        </div>
    </div>
</div>

<div class='arrowLeft'>
    <svg class="bi" width="40" height="40">
        <use xlink:href="<?= URI ?>/assets/images/icons.svg#arrow-left"/>
    </svg>
</div>
<div class='arrowRight'>
    <svg class="bi" width="40" height="40">
        <use xlink:href="<?= URI ?>/assets/images/icons.svg#arrow-right"/>
    </svg>
</div>

<table id="main_table" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col" class="text-nowrap">Идент. № пробы</th>
        <th scope="col" class="text-nowrap">Дата отбора</th>
        <th scope="col" class="text-nowrap">Показатель</th>
        <th scope="col" class="text-nowrap">Объект контроля</th>
        <th scope="col" class="text-nowrap">Точка отбора</th>
        <th scope="col" class="text-nowrap">Номер пробы</th>
        <th scope="col" class="text-nowrap">Св-во отбора</th>
        <th scope="col" class="text-nowrap">Время экспозиции</th>
        <th scope="col" class="text-nowrap">Среда</th>
        <th scope="col" class="text-nowrap">Объём воздуха</th>
        <th scope="col" class="text-nowrap">Место отбора</th>
        <th scope="col" class="text-nowrap">Дата посева</th>
        <th scope="col" class="text-nowrap">Пит. среда</th>
        <th scope="col" class="text-nowrap">№ подпартии</th>
        <th scope="col">Требуемая температура инкубации, °C</th>
        <th scope="col">Требуемое время инкубации, час</th>
        <th scope="col" class="text-nowrap">№ термостата</th>
        <th scope="col" class="text-nowrap">Дата снятия результатов</th>
        <th scope="col" class="text-nowrap">Результат посева</th>
        <th scope="col">Требуемые диапазоны</th>
        <th scope="col">Результат контрольного посева</th>
        <th scope="col" class="text-nowrap">Вывод</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
    </tr>
    <tr class="header-search">
        <th scope="col">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
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
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/microb/addSamplingMediumControl/" method="post">
    <div class="title mb-3 h-2">
        Добавьте отбор пробы
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Номер отбора</label>
            <input type="number" name="toSQL[microb_sampling][sample_number]"
                   step="1" min="1" max="10000"
                   class="form-control sample-readonly"
                   value="" required>
        </div>
        <div class="col">
            <div class="col">
                <label class="form-label">Дублировать отбор</label>
            </div>
            <input  class="form-check-input" type="checkbox" value="1"
                   id="sample_copy">
            <label class="form-check-label" for="sample_copy">
                Да
            </label>
        </div>
        <div class="col">
            <label class="form-label">Дата конца отбора</label>
            <input name="toSQL[microb_sampling][datetime_finish]"
                   type="datetime-local"
                   class="form-control sample-readonly"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Объект контроля</label>
            <select name="toSQL[microb_sampling][id_microb_type_control]"
                    class="form-control sample-readonly" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['microb_type_control'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label class="form-label">Исследуемый показатель</label>
            <select name="toSQL[microb_sampling][id_microb_type_microb]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['microb_type_microb'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="object_lbf object_1">
        <div class="title mb-3 h-2">
            Воздух в боксах
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Номер бокса</label>
                <select name="toSQL[microb_control_air_in_box][id_microb_unit_box]"
                        class="form-control sample-readonly" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_unit_box'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Количество точек</label>
                <input type="number"
                       name="toSQL[microb_sampling][quantity_sample_point]"
                       step="1" min="1" max="10000"
                       class="form-control sample-readonly"
                       value=""
                       disabled required
                >
            </div>
            <div class="col">
                <label class="form-label">Время экспозиции</label>
                <div class="input-group">
                    <input type="number"
                           name="toSQL[microb_control_air_in_box][exposition_time_min]"
                           step="0.1"
                           min="1" max="160"
                           class="form-control sample-readonly" required>
                    <span class="input-group-text">мин</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Тип питательной среды</label>
                <select name="toSQL[microb_medium_grow][id_microb_type_medium_grow]"
                        class="form-control sample-readonly"
                        disabled required
                >
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_type_medium_grow'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">№ Подпартии питательной среды</label>
                <input type="text"
                       name="toSQL[microb_medium_grow][number_batch]"
                       class="form-control sample-readonly"
                       disabled required
                >
            </div>
        </div>
    </div>

    <div class="object_lbf object_2">
        <div class="title mb-3 h-2">
            Воздух в помещениях
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Номер помещения</label>
                <select name="toSQL[microb_control_air_in_room][id_microb_unit_room]"
                        class="form-control sample-readonly" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_unit_room'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Количество точек</label>
                <input type="number"
                       name="toSQL[microb_sampling][quantity_sample_point]"
                       step="1" min="1" max="10000"
                       class="form-control sample-readonly"
                       value=""
                       disabled
                       required
                >
            </div>
            <div class="col">
                <label class="form-label">Объем исследуемого воздуха</label>
                <div class="input-group">
                    <input type="number"
                           name="toSQL[microb_control_air_in_room][volume_air_litre]"
                           step="0.1"
                           min="0.1" max="1000"
                           class="form-control sample-readonly" required>
                    <span class="input-group-text">л</span>
                </div>
            </div>
        </div>
    </div>
    <div class="object_lbf object_3">
        <div class="title mb-3 h-2">
            Поверхности
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Номер помещения</label>
                <select name="toSQL[microb_control_surface][id_microb_unit_room]"
                        class="form-control sample-readonly" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_unit_room'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">Количество точек</label>
                <input type="number"
                       name="toSQL[microb_sampling][quantity_sample_point]"
                       step="1" min="1" max="10000"
                       class="form-control sample-readonly"
                       value=""
                       disabled
                       required
                >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Тип транспортной среды</label>
                <select name="toSQL[microb_medium_transport][id_microb_type_medium_transport]"
                        class="form-control sample-readonly" disabled required
                >
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_type_medium_transport'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">№ Подпартии транспортной среды</label>
                <input type="text"
                       name="toSQL[microb_medium_transport][number_batch]"
                       class="form-control name-recipe sample-readonly"
                       disabled
                       required
                >
            </div>
        </div>
    </div>
    <div class="object_lbf object_4">
        <div class="title mb-3 h-2">
            Фильтровальная установка
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Номер установки</label>
                <select name="toSQL[microb_control_filter_equipment][id_microb_unit_filter_equipment]"
                        class="form-control sample-readonly" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_unit_filter_equipment'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Серия мембранных фильтро</label>
                <input type="text"
                       name="toSQL[microb_control_filter_equipment][number_batch_filter]"
                       class="form-control  sample-readonly" required
                >
            </div>
            <div class="col">
                <label class="form-label">№ Подпартии дист. воды</label>
                <input type="text"
                       name="toSQL[microb_control_filter_equipment][number_batch_water]"
                       class="form-control sample-readonly" required
                >
            </div>
        </div>
    </div>
    <div class="object_lbf object_5">
        <div class="title mb-3 h-2">
            Сотрудники
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Сотрудник</label>
                <select name="toSQL[microb_control_employee][id_employee]"
                        class="form-control sample-readonly" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['employee'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Тип транспортной среды</label>
                <select name="toSQL[microb_medium_transport][id_microb_type_medium_transport]"
                        class="form-control sample-readonly" disabled required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['microb_type_medium_transport'] as $val): ?>
                        <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">№ Подпартии транспортной среды</label>
                <input type="text"
                       name="toSQL[microb_medium_transport][number_batch]"
                       class="form-control sample-readonly"
                       disabled required
                >
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-second"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/microb/addSowing/" method="post">
    <div class="title mb-3 h-2">
        Добавьте посев проб
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Отобранная проба</label>
            <select name="toSQL[microb_sowing][id_microb_sampling]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php foreach ($this->data['microb_sampling_without_sowing'] as $val): ?>
                    <option <?= ($val['id_microb_type_control'] == 1) ? "data-id='1'" : "" ?>
                            data-grow="<?= $val['id_microb_type_medium_grow'] ?>"
                            data-name="<?= $val['number_batch'] ?>"
                            data-medium="<?= $val['id_microb_medium_grow'] ?>"
                            value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden"
                   name="toSQL[microb_sowing][id_microb_medium_grow]" disabled>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Тип питательной среды</label>
            <select name="toSQL[microb_medium_grow][id_microb_type_medium_grow]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['microb_type_medium_grow'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label class="form-label">№ Подпартии пит. среды</label>
            <input type="text"
                   name="toSQL[microb_medium_grow][number_batch]"
                   class="form-control name-recipe" required
            >
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата и время начала инкубации</label>
            <input name="toSQL[microb_sowing][datetime_start]"
                   type="datetime-local"
                   class="form-control "
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
        <div class="col">
            <label class="form-label">№ термостата</label>
            <select name="toSQL[microb_sowing][id_microb_unit_thermostat]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['microb_unit_thermostat'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Требуемая температура инкубации</label>
            <div class="input-group">
                <input type="number"
                       name="toSQL[microb_sowing][temperature_inсubation]"
                       step="0.1"
                       min="1" max="160"
                       class="form-control bg-white" required>
                <span class="input-group-text">±</span>
                <input type="number"
                       name="toSQL[microb_sowing][temperature_inсubation_range]"
                       step="0.1"
                       min="0" max="160"
                       class="form-control bg-white" required>
                <span class="input-group-text">°C</span>

            </div>
        </div>
        <div class="col">
            <label class="form-label">Требуемое время инкубации</label>
            <div class="input-group">
                <input type="number"
                       name="toSQL[microb_sowing][time_inсubation_hour]"
                       step="0.1"
                       min="1" max="160"
                       class="form-control bg-white" required>
                <span class="input-group-text">±</span>
                <input type="number"
                       name="toSQL[microb_sowing][time_inсubation_hour_range]"
                       step="0.1"
                       min="0" max="160"
                       class="form-control bg-white" required>
                <span class="input-group-text">час</span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>

<form id="add-entry-modal-form-third"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/microb/addResultSowing/" method="post">
    <div class="title mb-3 h-2">
        Добавьте результат посева проб
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Отобранная проба</label>
            <select name="toSQL[microb_result_sowing][id_microb_sampling]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['microb_sampling_without_result_sowing'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-id="<?= $val['sample_number'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата и время снятие результата</label>
            <input name="toSQL[microb_result_sowing][datetime_finish]"
                   type="datetime-local" required
                   class="form-control"
                   value="<?= $this->data['current_date'] ?>">
        </div>

        <div class="col">
            <div class="col">
                <label class="form-label">Результат контрольного посева</label>
            </div>
            <input name="toSQL[microb_result_sowing][is_grow_positive]"
                   class="form-check-input" type="checkbox" value="1"
                   id="is_grow_positive">
            <label class="form-check-label" for="is_grow_positive">
                Присутствует рост
            </label>
        </div>
    </div>

    <div class="title mb-3 h-2">
        Количество выросших колоний на чашке,шт
    </div>

    <div class="row mb-3 points">
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>