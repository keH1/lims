<div class="filters mb-4">
    <div class="row">
        <div class="col-auto ">
            <div class="two-button">
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Отбор
                </button>
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Паразиты
                </button>
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-third btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Простейшие
                </button>
            </div>
        </div>

        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter">
                <?php
                foreach ($this->data['control_objectsPlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateStart"
                   class="form-control filter filter-date-start"
                   value="<?= date("Y") . '-01-01' ?>" title="Введите дату начала">
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateEnd"
                   class="form-control filter filter-date-end"
                   value="<?= date("Y-m-d") ?>"
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
    <tr>
        <th rowspan="2" class="text-nowrap"></th>
        <th rowspan="2" class="text-nowrap">Номер отбора</th>
        <th rowspan="2" class="text-nowrap">Дата отбора</th>
        <th rowspan="2" class="text-nowrap">Объект контроля</th>
        <th rowspan="2" class="text-nowrap">Точка отбора</th>
        <th rowspan="2" class="text-nowrap">Раствор для отбора</th>
        <th rowspan="2" class="text-nowrap">Место отбора</th>
        <th rowspan="2" class="text-nowrap">Ответственный</th>
        <th colspan="7" class="text-nowrap">Исследование смывов на яйца
            гельминтов
        </th>
        <th colspan="5" class="text-nowrap">Исследование смывов на цисты
            (ооцисты) патогенных простейших
        </th>
        <th rowspan="2" class="text-nowrap">Вывод по точке</th>
        <th rowspan="2" class="text-nowrap">Вывод по пробе</th>
    </tr>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Дата начала</th>
        <th scope="col" class="text-nowrap">НД исследования</th>
        <th scope="col" class="text-nowrap">Тип подготовки</th>
        <th scope="col" class="text-nowrap">Св-во подготовки</th>
        <th scope="col" class="text-nowrap">Дата окончания</th>
        <th scope="col" class="text-nowrap">Результат</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
        <th scope="col" class="text-nowrap">Дата начала</th>
        <th scope="col" class="text-nowrap">Метод</th>
        <th scope="col" class="text-nowrap">Дата окончания</th>
        <th scope="col" class="text-nowrap">Результат</th>
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
      action="/ulab/parasite/addSampling/" method="post">
    <div class="title mb-3 h-2">
        Добавьте отбор пробы
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Номер отбора</label>
            <input type="number" name="toSQL[jn_lbf_psorg_smpl][number]"
                   step="1" min="1" max="10000"
                   class="form-control bg-white"
                   value="" required>
        </div>
        <div class="col">
            <label class="form-label">Дата конца отбора</label>
            <input name="toSQL[jn_lbf_psorg_smpl][select_datetime]"
                   type="datetime-local"
                   class="form-control "
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Объект контроля</label>
            <select name="toSQL[jn_lbf_psorg_smpl][rb_lbf_cont_obj_id]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['control_objects'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="object_lbf object_3">
        <div class="title mb-3 h-2">
            Поверхности
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Номер помещения</label>
                <select name="toSQL[jn_lbf_psorg_smpl][jn_lbf___pntr_id_id]"
                        class="form-control" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['surfaces'] as $val): ?>
                        <option value="<?= $val['id_id'] ?? '' ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">Раствор для отбора</label>
                <select name="toSQL[jn_lbf_psorg_smpl][rb_lbf_psorg_flush_id]"
                        class="form-control" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['solutions_flush'] as $val): ?>
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
                       name="toSQL[jn_lbf_psorg_smpl][quantity_sample_point]"
                       step="1" min="1" max="10000"
                       class="form-control bg-white"
                       value=""
                       disabled
                       required
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
                <select name="toSQL[jn_lbf_psorg_smpl][jn_lbf___pntr_id_id]"
                        class="form-control" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['employees'] as $val): ?>
                        <option value="<?= $val['id_id'] ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">Раствор для отбора</label>
                <select name="toSQL[jn_lbf_psorg_smpl][rb_lbf_psorg_flush_id]"
                        class="form-control" required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['solutions_flush'] as $val): ?>
                        <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col" hidden="">
            <label class="form-label">Количество точек</label>
            <input type="number"
                   name="toSQL[jn_lbf_psorg_smpl][quantity_sample_point]"
                   class="form-control bg-white" value="2"
            >
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-second"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/parasite/addResultParasite/" method="post">
    <div class="title mb-3 h-2">
        Результаты исследования смывов на паразитологические показатели
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Отобранная проба</label>
            <select name="toSQL[jn_lbf_psorg_dot][jn_lbf_psorg_smpl_id]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['sample_parasite'] as $val): ?>
                    <option value="<?= $val['id'] ?>"
                            data-id="<?= $val['number'] ?>"
                            data-quantity="<?= $val['quantity_sample_point'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label class="form-label">Дата начала </label>
            <input name="toSQL[jn_lbf_psorg_porg_data][datetime_start]"
                   type="datetime-local" required
                   class="form-control"
                   value="">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">НД исследования</label>
            <select name="toSQL[jn_lbf_psorg_porg_data][rb_lbf_psorg_doc_id]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['doc_parasite'] as $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label class="form-label">Дата окончания </label>
            <input name="toSQL[jn_lbf_psorg_porg_data][datetime_finish]"
                   type="datetime-local" required
                   class="form-control"
                   value="">
        </div>
    </div>
    <div class="object_lbf centrifuge">
        <div class="row mb-3">
            <div class="title mb-3 h-2">
                Центрифугирование
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Скорость центрифуги,
                        об/мин</label>
                    <input type="number"
                           name="toSQL[jn_lbf_psorg_porg_data][centrifuge_speed]"
                           step="1" min="1" max="100000"
                           class="form-control bg-white"
                           value=""
                           required
                           disabled
                    >
                </div>
                <div class="col">
                    <label class="form-label">Время работы, мин</label>
                    <input type="number"
                           name="toSQL[jn_lbf_psorg_porg_data][centrifuge_time_min]"
                           step="1" min="1" max="100"
                           class="form-control bg-white"
                           value=""
                           required
                           disabled
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="object_lbf flot">
        <div class="row mb-3">
            <div class="title mb-3 h-2">
                Флотация
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Флотационный раствор</label>
                    <select name="toSQL[jn_lbf_psorg_porg_data][rb_lbf_psorg_flot_id]"
                            class="form-control" required disabled>
                        <option value="" selected disabled></option>
                        <?php
                        foreach ($this->data['flot_liquid'] as $val): ?>
                            <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                        <?php
                        endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Плотность, г/л</label>
                    <input type="number"
                           name="toSQL[jn_lbf_psorg_porg_data][flot_density]"
                           step="0.01" min="0.01" max="100"
                           class="form-control bg-white"
                           value=""
                           required
                           disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="panel panel-default">
            <header class="panel-heading">
                Результаты
                <span class="tools float-end">
                    <a href="javascript:;"
                       class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="#" class="fa fa-chevron-up"></a>
                </span>
            </header>
            <div class="panel-body panel-hidden">
                <div class="wrapper-info-header result-parasite">
                    Не выбрана проба
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-third"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/parasite/addResultSimple/" method="post">
    <div class="title mb-3 h-2">
        Исследование смывов на цисты (ооцисты) патогенных простейших
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Отобранная проба</label>
            <select name="toSQL[jn_lbf_psorg_smpl_id]"
                    class="form-control" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['sample_simple'] as $val): ?>
                    <option value="<?= $val['id'] ?>"
                            data-id="<?= $val['number'] ?>"
                            data-quantity="<?= $val['quantity_sample_point'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <div class="col">
                <label class="form-label">Исследование не проводиться</label>
            </div>
            <input name="toSQL[is_sorg_reg]"
                   class="form-control" type="number" value="1" hidden
            >
            <input name="toSQL[is_sorg_reg]"
                   class="form-check-input" type="checkbox" value="0"
                   id="is_sorg_reg">
            <label class="form-check-label" for="is_sorg_reg">
                не проводиться
            </label>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата начала </label>
            <input name="toSQL[jn_lbf_psorg_sorg_data][datetime_start]"
                   type="datetime-local" required
                   class="form-control sorg"
                   value="">
        </div>
        <div class="col">
            <label class="form-label sorg">Дата окончания </label>
            <input name="toSQL[jn_lbf_psorg_sorg_data][datetime_finish]"
                   type="datetime-local" required
                   class="form-control sorg"
                   value="">
        </div>
    </div>

    <div class="mb-3">
        <div class="panel panel-default">
            <header class="panel-heading">
                Результаты
                <span class="tools float-end">
                    <a href="javascript:;"
                       class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                    <a href="#" class="fa fa-chevron-up"></a>
                </span>
            </header>
            <div class="panel-body panel-hidden">
                <div class="wrapper-info-header result-simple">
                    Не выбрана проба
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3 points">
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>