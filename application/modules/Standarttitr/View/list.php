<!--<div class="filters mb-3">-->
<!--    <div class="row">-->
<!--        <div class="col-auto">-->
<!--            <div class="two-button">-->
<!--                <button type="button" name="add_entry"-->
<!--                        class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">-->
<!--                    Добавить Стандарт-титр-->
<!--                </button>-->
<!--                <button type="button" name="add_entry"-->
<!--                        class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-reactive">-->
<!--                    Изготовитель-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!--./filters-->

<div class="mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                Добавить Стандарт-титр
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                Провести Стандарт-титр
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-third btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                Изготовитель
            </button>
        </div>
    </div>
</div>
<div class="mb-4">
    <div class="row">
        <div class="col">
            <select id="selectStandartTitrUpdate"
                    class="form-control h-auto reactive-update select-standart_titr">
                <?php
                foreach ($this->data['standart_titr_receive'] as $val): ?>
                    <option value="" selected disabled></option>
                    <option value="<?= $val['id'] ?>"
                            data-idreceive="<?= $val['id_receive'] ?>"
                    >
                        <?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button id="standartTitrUpdate" type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-update"
                    disabled>
                Ред. Стандарт-титр
            </button>
        </div>
        <div class="col-auto">
            <button id="receiveUpdate" type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-update"
                    disabled>
                Ред. Проводку
            </button>
        </div>
    </div>
</div>
<!--./filters-->
<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <select id="inputIdWhichFilter"
                    class="form-control h-auto filter select-standart_titr">
                <?php
                foreach ($this->data['standart_titr_full_namePlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
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
        <th scope="col" class="text-nowrap">Рег №</th>
        <th scope="col" class="text-nowrap">Наименование</th>
        <th scope="col" class="text-nowrap">Закуп. док.</th>
        <th scope="col" class="text-nowrap">Дата пост.</th>
        <th scope="col" class="text-nowrap">№ партии</th>
        <th scope="col" class="text-nowrap">Количество</th>
        <th scope="col" class="text-nowrap">Объем, мл</th>
        <th scope="col" class="text-nowrap">Коэффициент</th>
        <th scope="col" class="text-nowrap">НД</th>
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
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/standarttitr/addStandartTitr/" method="post">
    <div class="title mb-3 h-2 add-name edit-standarttitr-form-name">
        Добавьте стандарт-титр
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Название стандарт-титра</label>
            <input type="text" name="standart_titr[name]" class="form-control"
                   required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Регистрационный номер</label>
            <div class="input-group">
                <span class="input-group-text">СТ - </span>
                <input type="text" name="standart_titr[number]"
                       class="form-control" required>
            </div>
        </div>
    </div>
    <div class="line-dashed-red"></div>
    <div class="row mb-3">
        <div class="col">
            <input name="standart_titr[is_precursor]" class="form-check-input"
                   type="checkbox" value="1"
                   id="isPrecursor">
            <label class="form-check-label" for="isPrecursor">
                Стандарт-титр является прекурсором или подлежит особому контролю
            </label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>

</form>
<form id="add-entry-modal-form-second"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/standarttitr/addReceive/" method="post">
    <div class="title mb-3 h-2 edit-receive-standarttitr-form-name">
        Провести стандарт-титр
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Стандарт-титр</label>
            <select id="selectFormStandartTitrUpdate"
                    class="form-control h-auto select-standarttitr reactive-update">
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['standart_titr_full_name'] as $val): ?>
                    <option value="<?= $val['id'] ?>"
                            data-number="<?= $val['number'] ?>"
                            data-number-receive="<?= $val['number_receive'] ?>"
                    >
                        <?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Регистрационный номер входящего
                реактива</label>
            <div class="input-group">
                <span class="input-group-text">СТ - </span>
                <span class="input-group-text number-reactive"></span>
                <input type="number" name="receive[number]" step="1" min="1"
                       max="100"
                       class="form-control bg-white number-receive" required
                       readonly>
                <input type="number" name="receive[id_library_reactive]"
                       class="form-control bg-white idlibraryreactive"
                       hidden="">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label" ">Закупочная документация №</label>
            <input type="text" name="receive[doc_receive_name]"
                   class="form-control" required>
        </div>
        <div class="col">
            <label class="form-label">Дата</label>
            <input name="receive[doc_receive_date]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>" required>
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
            <label class="form-label">Количество, ампулы</label>
            <input type="number" name="receive[quantity]" step="1" min="1"
                   max="10000"
                   class="form-control bg-white"
                   value="" required>
        </div>
        <div class="col">
            <label class="form-label">Объем приготовленного раствора</label>
            <select name="receive[volume]" class="form-control bg-white"
                    required>
                <option value="" selected disabled></option>
                <option value="1000">1000 мл</option>
                <option value="500">500 мл</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Нормативный документа на титр</label>
            <input type="text" name="receive[doc_standart_titr]"
                   class="form-control"
                   value="Массовая концентрация" required>
        </div>
        <div class="col">
            <label class="form-label">Коэффициент</label>
            <input type="number" name="receive[coefficient]" step="0.01"
                   min="0.99" max="1.01"
                   class="form-control bg-white"
                   value="1" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Производитель</label>
            <select name="receive[id_standart_titr_manufacturer]"
                    class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['standart_titr_manufacturer'] as $val): ?>
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
    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>
<form id="add-entry-modal-form-third"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/standarttitr/addManufacturer/" method="post">
    <div class="title mb-3 h-2">
        Добавьте Производителя
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Производитель</label>
            <input type="text" name="toSQL[standart_titr_manufacturer][name]"
                   class="form-control" required>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

