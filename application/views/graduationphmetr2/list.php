<div class="mb-3">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                Добавить измерение
            </button>
        </div>
        <div class="col-auto">
            <select class="form-control h-auto filtered select-equip">
                <option value="" selected disabled>Выберите оборудование для сортировки</option>
                <option value="all">Всё</option>
                <?php foreach ($this->data['equipment'] as $item): ?>
                    <option value="<?= $item['ID'] ?>"><?= $item['OBJECT'] . " | " . $item['FACTORY_NUMBER'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="date" class="form-control filtered select-date">
        </div>
    </div>
</div>

<table id="graduation_journal" class="table table-striped text-center">
    <thead>
    <tr>
        <th rowspan="2" class="align-middle">Дата</th>
        <th rowspan="2" class="align-middle">Наименование оборудования</th>
        <th rowspan="2" class="align-middle">Заводской номер</th>
        <th colspan="9">Измерения</th>
        <th rowspan="2" class="align-middle">Ответственный</th>
    </tr>
    <tr class="table-light">
        <th scope="col" class="align-middle">Результат измерения 1, ед рН</th>
        <th scope="col">Допустимая погрешность 1, Дельта = ±0,05</th>
        <th scope="col" class="align-middle">Вывод 1</th>
        <th scope="col" class="align-middle">Результат измерения 2, ед рН</th>
        <th scope="col" class="align-middle">Погрешность 2</th>
        <th scope="col" class="align-middle">Вывод 2</th>
        <th scope="col" class="align-middle">Результат измерения 3, ед рН</th>
        <th scope="col" class="align-middle">Погрешность 3</th>
        <th scope="col" class="align-middle">Вывод 3</th>
    </tr>
    <!-- <tr class="header-search">
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
    </tr> -->
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative graduation"
      action="/ulab/graduationphmetr/addGraduation/" method="post">

    <div class="measure">
        <div class="measure-block" style="margin-bottom: 16px;">
            <div class="measure-title">
                <span>Добавление измерений</span>
            </div>
        </div>

        <div class="measure-block">
            <div style="margin-right: 5px;">
                <span>Расширенные измерения</span>
            </div>
            <div>
                <input id="changeMeasuring"
                       class="form-check-input"
                       type="checkbox"
                       name="change_measuring">
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="form-label">Выбор оборудования</label>
        <div class="col">
            <select name="graduation[equipment]" class="form-control " required>
                <option value="" selected disabled></option>
                <?php foreach ($this->data['equipment'] as $item): ?>
                    <option value="<?= $item['ID'] ?>"><?= $item['OBJECT'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="form-label">Дата внесения</label>
        <div class="col">
            <input name="graduation[date]" type="date" class="form-control" value="">
        </div>
    </div>

    <div class="row mb-3 measuring-3">
        <label class="form-label">Ввод измерений</label>

        <table id="measure" class="table table-striped text-center">
            <thead>
            <tr>
                <th rowspan="2">рН буферного раствора</th>
                <th colspan="3">Измерения</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
            </tr>
            </thead>
            <tbody>
            <tr class="hidden-measure">
                <td>
                    1.65
                </td>
                <td>
                    <input name="graduation[result][1][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="1"
                           value=""
                           required
                           disabled
                    >
                </td>
                <td>
                    <input name="graduation[result][1][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="1"
                           value=""
                           required
                           disabled
                    >
                </td>
                <td>
                    <input name="graduation[result][1][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="1"
                           value=""
                           required
                           disabled
                    >
                </td>
            </tr>
            <tr>
                <td>
                    4.01
                </td>
                <td>
                    <input name="graduation[result][2][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="2"
                           value=""
                           required
                    >
                </td>
                <td>
                    <input name="graduation[result][2][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="2"
                           value=""
                           required
                    >
                </td>
                <td>
                    <input name="graduation[result][2][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="2"
                           value=""
                           required
                    >
                </td>
            </tr>
            <tr>
                <td>
                    6.86
                </td>
                <td>
                    <input name="graduation[result][3][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="3"
                           value=""
                           required
                    >
                </td>
                <td>
                    <input name="graduation[result][3][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="3"
                           value=""
                           required
                    >
                </td>
                <td>
                    <input name="graduation[result][3][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="3"
                           value=""
                           required
                    >
                </td>
            </tr>
            <tr>
                <td>
                    9.18
                </td>
                <td>
                    <input name="graduation[result][4][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="4"
                           value=""
                           required
                    >
                </td>
                <td>
                    <input name="graduation[result][4][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="4"
                           value=""
                           required
                    >
                </td>
                <td>
                    <input name="graduation[result][4][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="4"
                           value=""
                           required
                    >
                </td>
            </tr>
            <tr class="hidden-measure">
                <td>
                    12.43
                </td>
                <td>
                    <input name="graduation[result][5][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="5"
                           value=""
                           required
                           disabled
                    >
                </td>
                <td>
                    <input name="graduation[result][5][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="5"
                           value=""
                           required
                           disabled
                    >
                </td>
                <td>
                    <input name="graduation[result][5][]"
                           type="number" step="any"
                           class="form-control"
                           data-id="5"
                           value=""
                           required
                           disabled
                    >
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>

</form>