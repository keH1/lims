<div class="mb-4">
    <div class="row flex-nowrap">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0">
                Тип реактива
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0">
                Квал. и НД реактива
            </button>
        </div>
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-third btn-add-entry w-100 mw-100 mt-0">
                Провести реактив
            </button>
        </div>
<!--        <div class="col-auto">-->
<!--            <select id="selectReactiveUpdate" class="form-control h-auto select-reactive reactive-update" data-placeholder="Выберите реактив">-->
<!--                --><?php
//                foreach ($this->data['reactive_receive'] as $val): ?>
<!--                    <option value="" selected disabled></option>-->
<!--                    <option value="--><?//= $val['id'] ?><!--"-->
<!--                            data-idreceive="--><?//= $val['id_receive'] ?><!--">-->
<!--                        --><?//= $val['name'] ?><!--</option>-->
<!--                --><?php
//                endforeach; ?>
<!--            </select>-->
<!--        </div>-->
<!--        <div class="col-auto">-->
<!--            <button id="reactiveUpdate" type="button" name="add_entry"-->
<!--                    class="btn btn-primary popup-second btn-add-entry w-100 mw-100 mt-0 btn-update" disabled>-->
<!--                Ред. реактив-->
<!--            </button>-->
<!--        </div>-->
<!--        <div class="col-auto">-->
<!--            <button id="receiveUpdate" type="button" name="add_entry"-->
<!--                    class="btn btn-primary popup-third btn-add-entry w-100 mw-100 mt-0 btn-update" disabled>-->
<!--                Ред. проводку-->
<!--            </button>-->
<!--        </div>-->
    </div>
</div>
<!--./filters-->
<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter select-reactive">
                <?php
                foreach ($this->data['reactivePlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset" title="Сбросить фильтр">Сбросить
            </button>
        </div>
    </div>
</div>


<table id="main_table" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap">Рег. №</th>
        <th scope="col" class="text-nowrap">Имя реактива</th>
        <th scope="col" class="text-nowrap">Агрег.(eд)</th>
        <th scope="col" class="text-nowrap">Квал.</th>
        <th scope="col" class="text-nowrap">НД Реактива</th>
        <th scope="col" class="text-nowrap">Закуп. док.</th>
        <th scope="col" class="text-nowrap">Дата пост.</th>
        <th scope="col" class="text-nowrap">№ партии</th>
        <th scope="col" class="text-nowrap">Количество</th>
        <th scope="col" class="text-nowrap">Дата произв.</th>
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
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/reactive/addReactiveModel/" method="post">
    <div class="title mb-3 h-2">
        Добавьте реактив
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="nameReactive">Название реактива</label>
            <input type="text" name="toSQL[reactive_model][name]" class="form-control" id="nameReactive" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Агрегатное состояние - ед. изм. </label>
            <select name="toSQL[reactive_model][id_aggregate_state]" class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['aggregate_full'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="line-dashed-red"></div>
    <div class="row mb-3">
        <div class="col">
            <input name="toSQL[reactive_model][is_precursor]" class="form-check-input" type="checkbox" value="1"
                   id="is_precursor">
            <label class="form-check-label" for="is_precursor">
                Реактив является прекурсором или подлежит особому контролю
            </label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-second" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/reactive/addReactive/" method="post">
    <div class="title mb-3 h-2">
        Добавьте НД и квалификацию
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Реактив</label>
            <select name="toSQL[reactive][id_reactive_model]" class="form-control h-auto select-reactive" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['reactive_type'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>">
                        <?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Нормативный документ реактива</label>
            <input type="text" name="toSQL[reactive][doc_name]" class="form-control name-recipe"
                   required>
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Квалификация</label>
                <select name="toSQL[reactive][id_pure]" class="form-control " required>
                    <option value="" selected disabled></option>
                    <?php
                    foreach ($this->data['pure'] as $val): ?>
                        <option
                                value="<?= $val['id'] ?>"><?= $val['name'] ?></option>
                    <?php
                    endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Регистрационный номер</label>
                <input type="number" name="toSQL[reactive][number]" step="1" min="1" max="10000"
                       class="form-control bg-white"
                       value="" required>
            </div>
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <input type="number" name="toSQL[reactive][id]"
                   value="" hidden="">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-third" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/reactive/addReceive/" method="post">
    <div>
        <label class="title mb-3 h-2">Закупочная документация</label>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Реактив</label>
            <select name="toSQL[reactive_receive][id_reactive]"
                    class="form-control select-reactive all-reactive h-auto"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['reactive'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-unit="<?= $val['unit'] ?>"
                            data-number="<?= $val['number'] ?>"
                            data-numberreceive="<?= $val['number_receive'] ?>"
                            data-idlibraryreactive="<?= $val['id_library_reactive'] ?>"
                    ><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Регистрационный номер входящего реактива</label>
            <div class="input-group">
                <span class="input-group-text number-reactive"></span>
                <input type="number" name="toSQL[reactive_receive][number]" step="1" min="1" max="100"
                       class="form-control bg-white number-receive" required readonly>
                <input type="number" name="toSQL[reactive_receive][id_library_reactive]"
                       class="form-control bg-white idlibraryreactive" hidden="">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Номер вход. документа</label>
            <input type="text" name="toSQL[reactive_receive][doc_receive_name]" class="form-control">
        </div>
        <div class="col">
            <label class="form-label">Дата вход. документа</label>
            <input name="toSQL[reactive_receive][doc_receive_date]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата поступления</label>
            <input name="toSQL[reactive_receive][date_receive]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Номер партии/лот</label>
            <input type="text" name="toSQL[reactive_receive][number_batch]" class="form-control" required>
        </div>
        <div class="col">
            <label class="form-label">Количество</label>
            <div class="input-group">
                <input type="number" name="toSQL[reactive_receive][quantity]" step="0.01" min="0" max="1000000"
                       class="form-control bg-white" required>
                <span class="input-group-text quantity-reactive"></span>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата производства</label>
            <input name="toSQL[reactive_receive][date_production]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
        <div class="col">
            <label class="form-label">Срок годности</label>
            <input name="toSQL[reactive_receive][date_expired]" type="date"
                   class="form-control filter filter-date-start"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
    </div>
    <div>
        <div class="row mb-3">
            <input type="number" name="toSQL[reactive_receive][id]"
                   value="" hidden="">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>