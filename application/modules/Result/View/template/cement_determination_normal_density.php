<div class="wrapper-cement-determination-normal-density">
    <em class="info d-block mb-4">
        <strong>Определение нормальной густоты цементного теста</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_water_absorption">

    <table class="table water-absorption-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col">Масса цемента, г</th>
            <th scope="col">Масса воды, г</th>
            <th scope="col">В/Ц, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="number" class="form-control mass-of-cement"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_of_cement]"
                       value="<?= $this->data['measuring']['form']['mass_of_cement'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-of-water"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_of_water]"
                       value="<?= $this->data['measuring']['form']['mass_of_water'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                       value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="calculateContentCementDeterminationNormalDensity" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>