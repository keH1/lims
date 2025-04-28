<div class="mb-3">
    <div class="row">
        <div class="col-auto ">
            <div class="two-button">
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Добавить оборудование
                </button>
            </div>
        </div>
    </div>
</div>


<table id="fridge_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Тип холодильника</th>
        <th scope="col" class="text-nowrap">Наименование</th>
        <th scope="col" class="text-nowrap">Тип оборудования</th>
        <th scope="col" class="text-nowrap">Заводской номер</th>
        <th scope="col" class="text-nowrap">Инвентарный номер</th>
        <th scope="col" class="text-nowrap">Диапазон</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
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
      action="/ulab/fridge/addFridge/" method="post">
    <div class="title mb-3 h-2">
        Добавьте холодильник
    </div>
    <div class="row mb-3">
        <label class="form-label">Тип холодильника</label>
        <div class="col">
            <select name="fridge[id_unit_fridge]" class="form-control " required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['type_fridge'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>


    <div class="row mb-3">
        <label class="form-label">Оборудование</label>
        <div class="col">
            <select name="fridge[id_ba_oborud]"" class="form-control select-oborud" required>
            <option value="" selected disabled></option>
            <?php
            foreach ($this->data['oborud'] as $val): ?>
                <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
            <?php
            endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Начальная температура</label>
            <div class="input-group">
                <input type="number" name="fridge[first_range]" step="0.1" min="-40" max="100"
                       class="form-control bg-white" required>
                <span class="input-group-text">°C</span>
            </div>
        </div>
        <div class="col">
            <label class="form-label">Конечная температура</label>
            <div class="input-group">
                <input type="number" name="fridge[last_range]" step="0.1" min="-40" max="100"
                       class="form-control bg-white" required>
                <span class="input-group-text">°C</span>
            </div>

        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>

