<div class="wrapper-emulsion-disintegration">
    <em class="info d-block mb-4">
        <strong>Индекс распада ГОСТ P 58952.4</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-bordered list_data mb-3">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="2">№ чашки</th>
            <th class="text-center align-middle">Масса чашки с шпателем, г</th>
            <th class="text-center align-middle">Масса чашки со шпателем и эмульсией, г</th>
            <th class="text-center align-middle">Масса чашки со шпателем, эмульсией и наполнителем, г</th>
            <th class="text-center align-middle" rowspan="2" nowrap>m<sub>3</sub> - m<sub>2</sub></th>
            <th class="text-center align-middle" rowspan="2" nowrap>m<sub>2</sub> - m<sub>1</sub></th>
            <th class="text-center align-middle" rowspan="2">Индекс распада эмульсии</th>
        </tr>
        <tr>
            <th class="text-center align-middle">m<sub>1</sub></th>
            <th class="text-center align-middle">m<sub>2</sub></th>
            <th class="text-center align-middle">m<sub>3</sub></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle">1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][cup_spatula][0]"
                       value="<?= $this->data['measuring']['form']['decay_index']['cup_spatula'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][cup_spatula_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['decay_index']['cup_spatula_emulsion'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][cup_spatula_emulsion_filler][0]"
                       value="<?= $this->data['measuring']['form']['decay_index']['cup_spatula_emulsion_filler'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][diff_m3_m2][0]"
                       value="<?= $this->data['measuring']['form']['decay_index']['diff_m3_m2'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][diff_m2_m1][0]"
                       value="<?= $this->data['measuring']['form']['decay_index']['diff_m2_m1'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][emulsion_index][0]"
                       value="<?= $this->data['measuring']['form']['decay_index']['emulsion_index'][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][cup_spatula][1]"
                       value="<?= $this->data['measuring']['form']['decay_index']['cup_spatula'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][cup_spatula_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['decay_index']['cup_spatula_emulsion'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][cup_spatula_emulsion_filler][1]"
                       value="<?= $this->data['measuring']['form']['decay_index']['cup_spatula_emulsion_filler'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][diff_m3_m2][1]"
                       value="<?= $this->data['measuring']['form']['decay_index']['diff_m3_m2'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][diff_m2_m1][1]"
                       value="<?= $this->data['measuring']['form']['decay_index']['diff_m2_m1'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][emulsion_index][1]"
                       value="<?= $this->data['measuring']['form']['decay_index']['emulsion_index'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle" colspan="6" style="text-align: right;">Среднее значение</td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][decay_index][emulsion_index_average]"
                       value="<?= $this->data['measuring']['form']['decay_index']['emulsion_index_average'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="decayIndex" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>