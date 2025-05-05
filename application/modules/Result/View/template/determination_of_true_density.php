<div class="wrapper-true-density">
    <em class="info d-block mb-4">
        <strong>Определение истинной плотности: - пикнометрический метод А и Б</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_true_density">

    <table class="table humidity-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Масса пикнометра с материалом, г</th>
            <th scope="col" class="border-0">Масса пустого пикнометра, г</th>
            <th scope="col" class="border-0">Плотность дистиллированной воды, равная 1 г/см3</th>
            <th scope="col" class="border-0">Масса пикнометра с дистиллированной водой, г</th>
            <th scope="col" class="border-0">Масса пикнометра с материалом и дистиллированной водой наполненного до отметки, г</th>
            <th scope="col" class="border-0">Истинная плотность щебня (гравия), г/см3</th>
            <th scope="col" class="border-0">Среднее арифметическое значение</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="number" class="form-control pycnometer-material"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][pycnometer_material]"
                       value="<?= $this->data['measuring']['form']['pycnometer_material'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-mass"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][empty_mass]"
                       value="<?= $this->data['measuring']['form']['empty_mass'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control density-of-distilled-water"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][density_of_distilled_water]"
                       value="<?= $this->data['measuring']['form']['density_of_distilled_water'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control pycnometer-distilled-water"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][pycnometer_distilled_water]"
                       value="<?= $this->data['measuring']['form']['pycnometer_distilled_water'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control pycnometer-distilled-water-material"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][pycnometer_distilled_water_material]"
                       value="<?= $this->data['measuring']['form']['pycnometer_distilled_water_material'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-true-density"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value0]"
                       value="<?= $this->data['measuring']['form']['result_value0'] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle" rowspan="2">
                <input type="number" class="form-control result-avg-value"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][result_value]"
                       value="<?= $this->data['measuring']['result_value'] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <td>
                <input type="number" class="form-control pycnometer-material1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][pycnometer_material1]"
                       value="<?= $this->data['measuring']['form']['pycnometer_material1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-mass1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][empty_mass1]"
                       value="<?= $this->data['measuring']['form']['empty_mass1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control density-of-distilled-water1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][density_of_distilled_water1]"
                       value="<?= $this->data['measuring']['form']['density_of_distilled_water1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control pycnometer-distilled-water1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][pycnometer_distilled_water1]"
                       value="<?= $this->data['measuring']['form']['pycnometer_distilled_water1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control pycnometer-distilled-water-material1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][pycnometer_distilled_water_material1]"
                       value="<?= $this->data['measuring']['form']['pycnometer_distilled_water_material1'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-true-density1"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value1]"
                       value="<?= $this->data['measuring']['form']['result_value1'] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateContentTrueDensity" class="btn btn-primary calculate-content-humidity"
                    name="calculate_content_grains">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][true_density][save]">Сохранить</button>
        </div>
    </div>
</div>