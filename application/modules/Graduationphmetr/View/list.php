<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Добавить замер
            </button>
        </div>
        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter">
                <?php
                foreach ($this->data['ph_metrPlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
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

<table id="main_table" class="table table-striped text-center">
    <thead>
    <tr class="table-light align-middle">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col">Дата</th>
        <th scope="col">Оборудование</th>
        <th scope="col">Буфера, ед pH</th>
        <th scope="col">Измерение 1, ед pH</th>
        <th scope="col">Требуемые диапазоны 1, ед pH</th>
        <th scope="col">Измерение 2, ед pH</th>
        <th scope="col">Требуемые диапазоны 2, ед pH</th>
        <th scope="col">Измерение 3, ед pH</th>
        <th scope="col">Требуемые диапазоны 3, ед pH</th>
        <th scope="col">Вывод</th>
        <th scope="col">Ответственный</th>
    </tr>
    <tr class="header-search">
        <th scope="col"></th>
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
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
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

<svg width="0" height="0" class="hidden">
    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" id="arrow-left">
        <path
                d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" id="arrow-right">
        <path
                d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm4.5 5.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>
    </symbol>
</svg>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/graduationphmetr/addMeasurement/" method="post">
    <div class="title mb-3 h-2">
        Рецепт
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата замера</label>
            <input name="toSQL[ph_metr_graduation][date]" type="datetime-local" class="form-control"
                   value="" placeholder="Дата замера" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Оборудование</label>
            <select name="toSQL[ph_metr_graduation][id_ph_metr]" class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php foreach ($this->data['ph_metr'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="measure-block">
        <div style="margin-right: 5px;">
            <span>Расширенные измерения</span>
        </div>
        <div>
            <input id="changeMeasuring"
                   class="form-check-input"
                   type="checkbox">
        </div>
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3 measuring-3">

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
                    <input name="toSQL[measurements][1][ph_metr_measurement][id_ph_metr_buffer]"
                           type="number" class="form-control" value="1" hidden="" disabled>
                </td>

                <td>
                    <input name="toSQL[measurements][1][ph_metr_measurement][m1]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required disabled>
                </td>
                <td>
                    <input name="toSQL[measurements][1][ph_metr_measurement][m2]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required disabled>
                </td>
                <td>
                    <input name="toSQL[measurements][1][ph_metr_measurement][m3]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required disabled>
                </td>
            </tr>
            <tr>
                <td>
                    4.01
                    <input name="toSQL[measurements][2][ph_metr_measurement][id_ph_metr_buffer]"
                           type="number" class="form-control" value="2" hidden="">
                </td>

                <td>
                    <input name="toSQL[measurements][2][ph_metr_measurement][m1]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
                <td>
                    <input name="toSQL[measurements][2][ph_metr_measurement][m2]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
                <td>
                    <input name="toSQL[measurements][2][ph_metr_measurement][m3]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
            </tr>
            <tr>
                <td>
                    6.86
                    <input name="toSQL[measurements][3][ph_metr_measurement][id_ph_metr_buffer]"
                           type="number" class="form-control" value="3" hidden="">
                </td>

                <td>
                    <input name="toSQL[measurements][3][ph_metr_measurement][m1]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
                <td>
                    <input name="toSQL[measurements][3][ph_metr_measurement][m2]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
                <td>
                    <input name="toSQL[measurements][3][ph_metr_measurement][m3]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
            </tr>
            <tr>
                <td>
                    9.18
                    <input name="toSQL[measurements][4][ph_metr_measurement][id_ph_metr_buffer]"
                           type="number" class="form-control" value="4" hidden="">
                </td>

                <td>
                    <input name="toSQL[measurements][4][ph_metr_measurement][m1]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
                <td>
                    <input name="toSQL[measurements][4][ph_metr_measurement][m2]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
                <td>
                    <input name="toSQL[measurements][4][ph_metr_measurement][m3]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required>
                </td>
            </tr>
            <tr class="hidden-measure">
                <td>
                    12.43
                    <input name="toSQL[measurements][5][ph_metr_measurement][id_ph_metr_buffer]"
                           type="number" class="form-control" value="5" hidden="" disabled>

                <td>
                    <input name="toSQL[measurements][5][ph_metr_measurement][m1]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required disabled>
                </td>
                <td>
                    <input name="toSQL[measurements][5][ph_metr_measurement][m2]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required disabled>
                </td>
                <td>
                    <input name="toSQL[measurements][5][ph_metr_measurement][m3]"
                           type="number" step="0.001" min="0" max="14"
                           class="form-control" value="" required disabled>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<!--./add-entry-modal-form-->