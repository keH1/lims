<div class="wrapper-cement-determination-of-fineness">
    <em class="info d-block mb-4">
        <strong>Определение тонкости помола цемента по остатку на</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_water_absorption">

    <table class="table water-absorption-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col">№ Определения</th>
            <th scope="col">Масса цемента, г</th>
            <th scope="col">Масса остатка, г</th>
            <th scope="col">Остаток на сите, %</th>
            <th scope="col">Среднеарифм. значение остатка на ситах, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                1
            </td>
            <td>
                <input type="number" class="form-control mass-of-cement-0 hide-tr"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_of_cement][0]"
                       value="<?= $this->data['measuring']['form']['mass_of_cement'][0] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-residue-0 hide-tr"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][residue_residue][0]"
                       value="<?= $this->data['measuring']['form']['residue_residue'][0] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control sieve-residue-0"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue][0]"
                       value="<?= $this->data['measuring']['form']['sieve_residue'][0] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle average-result" rowspan="3">
                <input type="number" class="form-control result"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                       value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <td>
                2
            </td>
            <td>
                <input type="number" class="form-control mass-of-cement-1 hide-tr"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_of_cement][1]"
                       value="<?= $this->data['measuring']['form']['mass_of_cement'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-residue-1 hide-tr"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][residue_residue][1]"
                       value="<?= $this->data['measuring']['form']['residue_residue'][1] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control sieve-residue-1"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue][1]"
                       value="<?= $this->data['measuring']['form']['sieve_residue'][1] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr class="hidden-fineness" <?= $this->data['measuring']['form']['sieve_residue'][2] ? '' : 'hidden'?>>
            <td>
                3
            </td>
            <td>
                <input type="number" class="form-control mass-of-cement-2"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_of_cement][2]"
                       value="<?= $this->data['measuring']['form']['mass_of_cement'][2] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-residue-2"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][residue_residue][2]"
                       value="<?= $this->data['measuring']['form']['residue_residue'][2] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control sieve-residue-2"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sieve_residue][2]"
                       value="<?= $this->data['measuring']['form']['sieve_residue'][2] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="calculateContentCementDeterminationOfFineness" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>