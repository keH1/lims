 <div class="wrapper-determination-with-emulsifier">
     <em class="info d-block mb-4">
         <strong>Определение содержания вяжущего с эмульгатором ГОСТ P 58952.5</strong>
     </em>

     <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-bordered list_data mb-3">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="2">№ чашки</th>
            <th class="text-center align-middle">Масса чашки с палочкой, г</th>
            <th class="text-center align-middle">Масса чашки с палочкой и эмульсией, г</th>
            <th class="text-center align-middle">Масса чашки с палочкой и остатком после выпаривания воды из эмульсии, г</th>
            <th class="text-center align-middle" rowspan="2" nowrap>g<sub>3</sub> - g<sub>1</sub></th>
            <th class="text-center align-middle" rowspan="2" nowrap>g<sub>2</sub> - g<sub>1</sub></th>
            <th class="text-center align-middle" rowspan="2">Содержание вяжущего с эмульгатором, % по массе</th>
        </tr>
        <tr>
            <th class="text-center align-middle">g<sub>1</sub></th>
            <th class="text-center align-middle">g<sub>2</sub></th>
            <th class="text-center align-middle">g<sub>3</sub></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle">1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][m_cup_stick][0]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['m_cup_stick'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][m_cup_stick_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['m_cup_stick_emulsion'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][m_cup_stick_evaporation_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['m_cup_stick_evaporation_emulsion'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][diff_g3_g1][0]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['diff_g3_g1'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][diff_g2_g1][0]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['diff_g2_g1'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][content_binder_emulsifier][0]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['content_binder_emulsifier'][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][m_cup_stick][1]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['m_cup_stick'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][m_cup_stick_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['m_cup_stick_emulsion'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][m_cup_stick_evaporation_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['m_cup_stick_evaporation_emulsion'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][diff_g3_g1][1]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['diff_g3_g1'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][diff_g2_g1][1]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['diff_g2_g1'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][content_binder_emulsifier][1]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['content_binder_emulsifier'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle" style="text-align: right;" colspan="6">Среднее значение</td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][binder_emulsifier][average_binder_emulsifier]"
                       value="<?= $this->data['measuring']['form']['binder_emulsifier']['average_binder_emulsifier'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="binderEmulsifier" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>