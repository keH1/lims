<div class="wrapper-stability-to-stratification">
    <em class="info d-block mb-4">
        <strong>Устойчивости к расслоению при хранении до 7 суток ГОСТ P 58952.9</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <div class="mb-3">
        <label for="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][capacity_emulsion]">Начальный объем эмульсии, мл</label>
        <input class="form-control"
               type="number" step="any"
               name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][capacity_emulsion]"
               value="<?= $this->data['measuring']['form']['delamination_resistance']['capacity_emulsion'] ?? '' ?>">
    </div>
    <table class="table table-bordered list_data mb-3">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="2">Испытание</th>
            <th class="text-center align-middle" rowspan="2">Наименование эмульсии</th>
            <th class="text-center align-middle" colspan="7">Уровень битумной фазы, мл</th>
            <th class="text-center align-middle" rowspan="2">Расслоение, % масс.</th>
        </tr>
        <tr>
            <th class="text-center align-middle">1 сут.</th>
            <th class="text-center align-middle">2 сут.</th>
            <th class="text-center align-middle">3 сут.</th>
            <th class="text-center align-middle">4 сут.</th>
            <th class="text-center align-middle">5 сут.</th>
            <th class="text-center align-middle">6 сут.</th>
            <th class="text-center align-middle">7 сут.</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle">1</td>
            <td>
                <input class="form-control" type="text"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][name_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['name_emulsion'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][0]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][1]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][2]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][3]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][3] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][4]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][4] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][5]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][5] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][0][6]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][0][6] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][delamination_resistance_value][0]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['delamination_resistance_value'][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">2</td>
            <td>
                <input class="form-control" type="text"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][name_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['name_emulsion'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][0]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][1]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][2]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][3]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][3] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][4]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][4] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][5]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][5] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][day][1][6]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['day'][1][6] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][delamination_resistance_value][1]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['delamination_resistance_value'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle" style="text-align: right;" colspan="9">Среднее значение</td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][delamination_resistance][average_delamination_resistance]"
                       value="<?= $this->data['measuring']['form']['delamination_resistance']['average_delamination_resistance'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="delaminationResistance" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>