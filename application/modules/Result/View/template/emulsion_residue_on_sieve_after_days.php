<div class="wrapper-resiude-sieve-after-days">
    <em class="info d-block mb-4">
        <strong>Остаток на сите 0,14 после 7 суток ГОСТ P 58952.8</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-bordered list_data mb-3">
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="2">№ чашки</th>
            <th class="text-center align-middle">Масса сита и чашки, г</th>
            <th class="text-center align-middle">Масса стакана, г</th>
            <th class="text-center align-middle">Масса эмульсии, г</th>
            <th class="text-center align-middle">Масса стакана с остатком эмульсии, г</th>
            <th class="text-center align-middle">Масса сита, чашки с остатком эмульсии после сушки, г</th>
            <th class="text-center align-middle" rowspan="2">Остаток на сите, % по массе</th>
        </tr>
        <tr>
            <th class="text-center align-middle">m<sub>1</sub></th>
            <th class="text-center align-middle">m<sub>2</sub></th>
            <th class="text-center align-middle">m<sub>3</sub></th>
            <th class="text-center align-middle">m<sub>4</sub></th>
            <th class="text-center align-middle">m<sub>5</sub></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle">1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_sieve_cup][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_sieve_cup'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_glasses][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_glasses'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_emulsion'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_glass_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_glass_emulsion'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_sieve_cup_emulsion_after_drying][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_sieve_cup_emulsion_after_drying'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][sieve_residue_percent][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['sieve_residue_percent'][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_sieve_cup][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_sieve_cup'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_glasses][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_glasses'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_emulsion'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_glass_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_glass_emulsion'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][m_sieve_cup_emulsion_after_drying][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['m_sieve_cup_emulsion_after_drying'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][sieve_residue_percent][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['sieve_residue_percent'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle" style="text-align: right;" colspan="6">Среднее значение</td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue_0_14_7][average_sieve_residue]"
                       value="<?= $this->data['measuring']['form']['sieve_residue_0_14_7']['average_sieve_residue'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="sieveResidue_0_14_7" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>