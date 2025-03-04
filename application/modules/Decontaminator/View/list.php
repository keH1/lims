<div class="mb-3">
    <div class="row">
        <div class="col-auto ">
            <div class="two-button">
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Добавить обеззараживатель
                </button>
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Добавить лампы
                </button>
            </div>
        </div>
    </div>
</div>


<table id="fridge_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Тип обеззараживателя</th>
        <th scope="col" class="text-nowrap">Наименование</th>
        <th scope="col" class="text-nowrap">Тип оборудования</th>
        <th scope="col" class="text-nowrap">Заводской номер</th>
        <th scope="col" class="text-nowrap">Инвентарный номер</th>
        <th scope="col" class="text-nowrap">Диапазон</th>
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
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/decontaminator/addDecontaminator/" method="post">
    <div class="title mb-3 h-2">
        Добавьте обеззараживатель
    </div>
    <div class="row mb-3">
        <label class="form-label">Тип обеззараживателя</label>
        <div class="col">
            <select name="toSQL[decontaminator][id_decontaminator_type]" class="form-control " required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['decontaminator_type'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>


    <div class="row mb-3">
        <label class="form-label">Оборудование</label>
        <div class="col">
            <select name="toSQL[decontaminator][id_ba_oborud]"" class="form-control select-oborud" required>
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
            <label class="form-label">Количество ламп</label>
            <div class="input-group">
                <input type="number" name="toSQL[decontaminator][lamp_quantity]" step="1" min="1" max="10"
                       class="form-control bg-white" value="2" required>
                <span class="input-group-text">шт.</span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>

<form id="add-entry-modal-form-second" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/decontaminator/addDecontaminator/" method="post">
    <div class="title mb-3 h-2">
        Добавьте лампы
    </div>

    <div class="row mb-3">
        <label class="form-label">Обезараживатель</label>
        <div class="col">
            <select name="toSQL[fridge][id_ba_oborud]"" class="form-control select-oborud" required>
            <option value="" selected disabled></option>
            <?php
            foreach ($this->data['decontaminator'] as $val): ?>
                <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
            <?php
            endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <select name="toSQL[fridge][id_ba_oborud]"" class="form-control select-lamp" required>
            <option value="" selected disabled></option>
            <?php
            foreach ($this->data['decontaminator'] as $val): ?>
                <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
            <?php
            endforeach; ?>
            </select>
        </div>
        <div class="col">
            <select name="toSQL[fridge][id_ba_oborud]"" class="form-control select-lamp" required>
            <option value="" selected disabled></option>
            <?php
            foreach ($this->data['decontaminator'] as $val): ?>
                <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
            <?php
            endforeach; ?>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>