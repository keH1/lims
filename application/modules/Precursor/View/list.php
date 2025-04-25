<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Фактический остаток
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Расход прекурсора
            </button>
        </div>
        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter">
                <?php
                foreach ($this->data['precursorPlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start"
                   value="" title="Введите дату начала">
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end"
                   value="" title="Введите дату окончания">
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset" title="Сбросить фильтр">Сбросить
            </button>
        </div>
    </div>
</div>
<!--./filters-->
<table id="precursor_journal" class="table table-striped text-center  ">
    <thead>
    <tr>
        <th rowspan="2">Реактив</th>
        <th rowspan="2">Месяц</th>
        <th rowspan="2">Остаток на 1 число</th>
        <th colspan="4">Приход</th>
        <th rowspan="2">Всего с остатком</th>
        <th colspan="3">Расход</th>
        <th rowspan="2">Расход за месяц</th>
        <th rowspan="2">Остаток по журналу</th>
        <th rowspan="2">Фактический остаток</th>
        <th rowspan="2">Ответственный</th>
    </tr>

    <tr class="table-light">
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Документ</th>
        <th scope="col" class="text-nowrap">Кол-во</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
        <th scope="col" class="text-nowrap">Вид расхода</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Кол-во</th>
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

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/precursor/addRemain/" method="post">
    <div class="title mb-3 h-2">
        Остаток прекурсоров
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Выберите реактив </label>
            <select name="toSQL[reactive_remain][id_library_reactive]" class="form-control  select-reactive h-auto"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['precursor'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-unit="<?= $val['unit'] ?>"
                            data-lastdate="<?= $val['last_date'] ?>"
                    ><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата</label>
            <input name="toSQL[reactive_remain][date]" type="date"
                   class="form-control select-month"
                   value="">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Количество</label>
            <div class="input-group">
                <input type="number" name="toSQL[reactive_remain][quantity]" step="0.01" min="0" max="10000"
                       class="form-control bg-white" required>
                <span class="input-group-text quantity-reactive"></span>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>

</form>
<form id="add-entry-modal-form-second" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/precursor/addConsume/" method="post">
    <div class="title mb-3 h-2">
        Остаток прекурсоров
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Выберите реактив </label>
            <select name="toSQL[reactive_consume][id_library_reactive]" class="form-control  select-reactive h-auto"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['precursor'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-unit="<?= $val['unit'] ?>"
                            data-lastdate="<?= $val['last_date'] ?>"
                    ><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата</label>
            <input name="toSQL[reactive_consume][date]" type="date"
                   class="form-control "
                   value="">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Назначение расхода</label>
            <input type="text" name="toSQL[reactive_consume][type]" class="form-control" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Количество</label>
            <div class="input-group">
                <input type="number" name="toSQL[reactive_consume][quantity]" step="0.01" min="0" max="10000"
                       class="form-control bg-white" required>
                <span class="input-group-text quantity-reactive"></span>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>

</form>
