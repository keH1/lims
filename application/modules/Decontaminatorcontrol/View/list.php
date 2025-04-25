<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0">
                Вкл.
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 m-0">
                Выкл.
            </button>
        </div>

        <div class="col">
            <select id="inputIdWhichFilter"
                    class="form-control h-auto select-decontaminator filter"
                    title="Выберите обеззараживатель">
                <?php
                foreach ($this->data['decontaminatorPlusAll'] as $val): ?>
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


<table id="main_table" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Обеззараживатель</th>
        <th scope="col" class="text-nowrap">Объект обеззараживания</th>
        <th scope="col" class="text-nowrap">№ помещения</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Проведена обработка спиртом</th>
        <th scope="col" class="text-nowrap">Вид микроорганизма</th>
        <th scope="col" class="text-nowrap">Режим облучения</th>
        <th scope="col" class="text-nowrap">Время вкл.</th>
        <th scope="col" class="text-nowrap">Ответственный за вкл.</th>
        <th scope="col" class="text-nowrap">Время выкл.</th>
        <th scope="col" class="text-nowrap">Ответственный за выкл.</th>
    </tr>
    <tr class="header-search">
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

<form id="add-entry-modal-form-first"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/decontaminatorcontrol/addSwitch/" method="post">
    <div>
        <input type="text" name="type"
               value="on" hidden="">
    </div>
    <div class="title mb-3 h-2">
        Добавьте время включения
    </div>

    <div class="row mb-3">
        <div class="col">
            <select name="toSQL[dcon_on][rb_eq_dcon_pntr_id]"
                    class="form-control"
                    required>
                <option hidden>Выберите обеззараживатель</option>
                <?php
                foreach ($this->data['decontaminator'] as $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата учета</label>
            <input type="date" name="toSQL[dcon_on][date]"
                   class="form-control" value="" required>
        </div>
        <div class="col">
            <label class="form-label">Время включения</label>
            <input type="time" name="toSQL[dcon_on][time]"
                   class="form-control" value="" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>

<form id="add-entry-modal-form-second"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/decontaminatorcontrol/addSwitch/" method="post">
    <div>
        <input type="text" name="type"
               value="off" hidden="">
    </div>
    <div class="title mb-3 h-2">
        Добавьте время выключения
    </div>

    <div class="row mb-3">
        <div class="col">
            <select name="toSQL[dcon_off][jn_morg_dcon_on_id]"
                    class="form-control"
                    required>
                <option hidden>Выберите обеззараживатель</option>
                <?php
                foreach ($this->data['decontaminator_on'] as $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="col">
                <label class="form-label">Обработка 70% спиртом</label>
            </div>
            <input name="toSQL[dcon_off][is_disinfected]"
                   class="form-check-input" type="checkbox" value="1"
                   id="is_disinfected">
            <label class="form-check-label" for="is_disinfected">
                Проведена
            </label>
        </div>
        <div class="col">
            <label class="form-label">Время выключение</label>
            <input type="time" name="toSQL[dcon_off][time]"
                   class="form-control" value="" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>