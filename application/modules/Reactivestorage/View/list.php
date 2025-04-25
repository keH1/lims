<div class="filters mb-3">
    <div class="row">
        <div class="col-auto w-50">
            <select id="inputIdWhichFilter" class="form-control  select-id-reactive h-auto"
                    required>
                <?php
                foreach ($this->data['reactivePlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                Добавить расход реактива
            </button>
        </div>
    </div>
</div>
<!--./filters-->

<table id="reactive_journal" class="table table-striped text-center  ">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">№</th>
        <th scope="col" class="text-nowrap">Имя реактива</th>
        <th scope="col" class="text-nowrap">№ партии</th>
        <th scope="col" class="text-nowrap">Срок годности</th>
        <th scope="col" class="text-nowrap">Приход</th>
        <th scope="col" class="text-nowrap">Расход</th>
        <th scope="col" class="text-nowrap">Итого</th>
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
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/reactiveconsumption/addReactiveConsume/" method="post">
    <div class="title mb-3 h-2">
        Расход реактива
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <select name="toSQL[reactive_consume][id_merge]" class="form-control select-reactive h-auto"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['reactive'] as $val): ?>
                    <option value="<?= $val['id'] ?>"
                            data-unit="<?= $val['unit'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
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
        <div class="col">
            <label class="form-label">Дата</label>
            <input name="toSQL[reactive_consume][date]" type="date" class="form-control filter filter-date-start"
                   value="">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Назначение списания</label>
            <input type="text" name="toSQL[reactive_consume][type]" class="form-control">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

