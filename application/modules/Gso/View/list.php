<!--./filters-->
<div class="mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-gso">
                Добавить ГСО
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-gso">
                Провести ГСО
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-third btn-add-entry w-100 mw-100 mt-0 btn-gso">
                Изготовитель
            </button>
        </div>
    </div>
</div>
<div class="mb-4">
    <div class="row">
        <div class="col">
            <select id="selectGsoUpdate"
                    class="form-control h-auto select-gso gso-update" style="width: 80% !important;">
                <?php
                foreach ($this->data['gso_receive'] as $val): ?>
                    <option selected disabled></option>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-idreceive="<?= $val['id_receive'] ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button id="gsoUpdate" type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-update"
                    disabled>
                Ред. ГСО
            </button>
        </div>
        <div class="col-auto">
            <button id="receiveUpdate" type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-update"
                    disabled>
                Ред. проводку
            </button>
        </div>
    </div>
</div>
<!--./filters-->
<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <select id="inputIdWhichFilter"
                    class="form-control h-auto filter select-gso">
                <?php
                foreach ($this->data['gso_full_namePlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-unit="<?= $val['unit'] ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="button"
                    class="btn btn-outline-secondary filter-btn-reset"
                    title="Сбросить фильтр">Сбросить
            </button>
        </div>
    </div>
</div>


<table id="recipe_journal" class="table table-striped text-center  ">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Рег. №</th>
        <th scope="col" class="text-nowrap">Наименование ГСО</th>
        <th scope="col" class="text-nowrap">Номер</th>
        <th scope="col" class="text-nowrap">Агрег.</th>
        <th scope="col" class="text-nowrap">Спецификация</th>
        <th scope="col" class="text-nowrap">Назначение</th>
        <th scope="col" class="text-nowrap">Закуп. док.</th>
        <th scope="col" class="text-nowrap">Дата пост.</th>
        <th scope="col" class="text-nowrap">№ партии</th>
        <th scope="col" class="text-nowrap">Количество</th>
        <th scope="col" class="text-nowrap">Наименование</th>
        <th scope="col" class="text-nowrap">Концентрация</th>
        <th scope="col" class="text-nowrap">Свидетельство</th>
        <th scope="col" class="text-nowrap">Паспорт</th>
        <th scope="col" class="text-nowrap">Производитель</th>
        <th scope="col" class="text-nowrap">Дата пр.</th>
        <th scope="col" class="text-nowrap">Срок годности</th>
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
      action="/ulab/gso/addGsoAndSpecification/" method="post">
    <div class="title mb-3 h-2 edit-gso-form-name">
        Добавьте ГСО
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Полное название ГСО</label>
            <input type="text" name="gso[name]" class="form-control" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Регистрационный номер</label>
            <div class="input-group">
                <span class="input-group-text span-gso-number">ГСО - </span>
                <input type="text" name="gso[number]" class="form-control"
                       required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="nameReactive">Номер ГСО</label>
            <input type="text" name="gso[doc]" class="form-control"
                   id="nameReactive" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Назначение</label>
            <select name="gso[id_gso_purpose]" class="form-control bg-white"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['gso_purpose'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Агрегатное состояние</label>
            <select name="gso[id_aggregate_state]"
                    class="form-control bg-white"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['aggregate_state'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label class="form-label">Ед. измерения</label>
            <select name="gso[id_unit_of_quantity]" class="form-control "
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['unit_of_quantity'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Примерная концентрация</label>
            <input type="text"
                   name="gso_specification[approximate_concentration]"
                   class="form-control" required>
        </div>
        <div class="col">
            <label class="form-label">Ед. измерения</label>
            <select name="gso_specification[id_unit_of_concentration]"
                    class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['unit_of_concentration'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Определяемая характеристика</label>
            <select name="gso_specification[name]"
                    class="form-control select-specification h-auto"
                    id="gso_specification"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['specification'] as $val): ?>
                    <option value="<?= $val["name"] ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>

    </div>
    <div class="line-dashed-red"></div>
    <div class="row mb-3">
        <div class="col">
            <input name="gso[is_precursor]" class="form-check-input"
                   type="checkbox" value="1"
                   id="isPrecursor">
            <label class="form-check-label" for="isPrecursor">
                ГСО является прекурсором или подлежит особому контролю
            </label>
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <input type="number" name="gso[id]"
                   value="" hidden="">
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <input type="number" name="gso_specification[id]"
                   value="" hidden="">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-second"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/gso/addGsoReceive/" method="post">
    <div class="title mb-3 h-2 edit-receive-form-name">
        Провести ГСО
    </div>

    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">ГСО</label>
            <select name="receive[id_gso]"
                    class="form-control select-gso h-auto all-gso"
                    id="id_gso"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['gso_full_name'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-number="<?= $val['number'] ?>"
                            data-numberreceive="<?= $val['number_receive'] ?>"
                            data-unit="<?= $val['unit'] ?>"
                            data-idlibraryreactive="<?= $val['id_library_reactive'] ?>"
                            data-name="<?= $val['name'] ?? '' ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Закупочная документация №</label>
            <input type="text" name="receive[doc_receive_name]"
                   class="form-control">
        </div>
        <div class="col">
            <label class="form-label">Дата</label>
            <input name="receive[doc_receive_date]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата поступления</label>
            <input name="receive[date_receive]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
        <div class="col">
            <label class="form-label">Номер партии/лот</label>
            <input type="text" name="receive[number_batch]" class="form-control"
                   required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Регистрационный номер входящего
                реактива</label>
            <div class="input-group">
                <span class="input-group-text">ГСО - </span>
                <span class="input-group-text number-gso"></span>
                <input type="number" name="receive[number]" step="1" min="1" max="100"
                       class="form-control bg-white number-receive" required
                       readonly>
                <input type="number" name="receive[id_library_reactive]"
                       class="form-control bg-white idlibraryreactive" hidden="">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Количество</label>
            <div class="input-group">
                <input type="number" name="receive[quantity]" step="0.01"
                       min="0" max="10000"
                       class="form-control bg-white" required>
                <span class="input-group-text quantity-gso"></span>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Наименование</label>
            <input type="text" name="receive_specification[specification]"
                   class="form-control"
                   value="Массовая концентрация" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Концентрация</label>
            <input type="number" name="receive_specification[concentration]"
                   step="0.0001" min="0.0001" max="100000"
                   class="form-control bg-white"
                   value="" required>
        </div>
        <div class="col">
            <label class="form-label">Ед. измерения</label>
            <select name="receive_specification[id_unit_of_concentration]"
                    class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['unit_of_concentration'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <label class="form-label">Погрешность, %</label>
            <input type="number" name="receive_specification[inaccuracy]"
                   step="0.0001" min="0.0001" max="10000"
                   class="form-control bg-white"
                   value="" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Свидетельство об утверждении типа</label>
            <input type="text" name="receive[certificate]"
                   class="form-control name-recipe"
                   required>
        </div>
        <div class="col">
            <label class="form-label">Дата дейстивительно до</label>
            <input name="receive[certificate_date_expired]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Паспорт ГСО</label>
            <input type="text" name="receive[passport]"
                   class="form-control name-recipe"
                   required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Производитель</label>
            <select name="receive[id_gso_manufacturer]"
                    class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['gso_manufacturer'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Дата производства</label>
                <input name="receive[date_production]" type="date"
                       class="form-control filter filter-date-start"
                       value="<?= $this->data['current_date'] ?>" required>
            </div>
            <div class="col">
                <label class="form-label">Срок годности, год</label>
                <input type="number" name="receive[storage_life_in_year]"
                       step="0.1" min="0.1" max="50"
                       class="form-control bg-white"
                       value="" required>
            </div>
        </div>
    </div>
    <div class="line-dashed-small"></div>
    <div>
        <div class="row mb-3">
            <input type="number" name="receive[id]"
                   value="" hidden="">
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <input type="number" name="receive_specification[id]"
                   value="" hidden="">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-third"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/gso/addManufacturer/" method="post">
    <div class="title mb-3 h-2">
        Добавьте Производителя
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Производитель</label>
            <input type="text" name="gso_manufacturer[name]"
                   class="form-control" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

