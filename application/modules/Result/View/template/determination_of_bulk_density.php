<div class="bulk-density-wrapper">
    <em class="info d-block mb-4">
        <strong>Определение насыпной плотности ГОСТ 33047-2014</strong>
        (3 параллельных испытания с наибольшим расхождением не более 0,1 г/см<sup>3</sup>; результат - среднее арифметическое значение с точностью довторого десятичного числа)
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_bulk_density">

    <table class="table mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Испытание</th>
            <th scope="col" class="border-0">Масса сосуда с мерной пробой, г</th>
            <th scope="col" class="border-0">Масса пустого сосуда, г</th>
            <th scope="col" class="border-0">Объем сосуда, см<sup>3</sup></th>
            <th scope="col" class="border-0">Насыпная плотность, г/см<sup>3</sup></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row" class="text-center align-middle">1</th>
            <td>
                <input type="number" class="form-control vessel-with-sample"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][vessel_with_sample][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['vessel_with_sample'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-vessel-mass"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][empty_vessel_mass][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['empty_vessel_mass'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control cylinder-volume"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][cylinder_volume][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['cylinder_volume'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control bulk-density"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][bulk_density][1]" data-trial="1" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['bulk_density'][1] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row" class="text-center align-middle">2</th>
            <td>
                <input type="number" class="form-control vessel-with-sample"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][vessel_with_sample][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['vessel_with_sample'][2] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-vessel-mass"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][empty_vessel_mass][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['empty_vessel_mass'][2] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control cylinder-volume"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][cylinder_volume][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['cylinder_volume'][2] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control bulk-density"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][bulk_density][2]" data-trial="2" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['bulk_density'][2] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row" class="text-center align-middle">3</th>
            <td>
                <input type="number" class="form-control vessel-with-sample"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][vessel_with_sample][3]"
                       data-trial="3" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['vessel_with_sample'][3] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-vessel-mass"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][empty_vessel_mass][3]"
                       data-trial="3" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['empty_vessel_mass'][3] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control cylinder-volume"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][cylinder_volume][3]"
                       data-trial="3" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['cylinder_volume'][3] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control bulk-density"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][bulk_density][3]" data-trial="3" step="any"
                       value="<?= $this->data['measuring']['form']['bulk_density']['bulk_density'][3] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-group row mb-4">
        <div class="col">
            <label for="bulkDensityAverage">Насыпная плотность, cреднее арифметическое значение</label>
            <input type="number" id="bulkDensityAverage" class="form-control bulk-density-average"
                   name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                   value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="сalculateBulkDensity" class="btn btn-primary"
                    name="сalculate_bulk_density">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][bulk_density][save]">Сохранить</button>
        </div>
    </div>
</div>