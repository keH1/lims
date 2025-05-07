<div class="wrapper-density-and-absorption">
    <em class="info d-block mb-4">
        <strong>Определение плотности и абсорбции щебня</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_density_and_absorption">

    <table class="table density-and-absorption-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Масса пробы щебня на воздухе, высушенного до постоянной массы, г</th>
            <th scope="col" class="border-0">Масса пробы щебня на воздухе после выдерживания его в воде в течение (17±2) ч, г</th>
            <th scope="col" class="border-0">Масса пробы щебня в воде после выдерживания его в воде в течение (17±2) ч. г</th>
            <th scope="col" class="border-0">Плотность воды при температуре 23 °С, равная 0.997 г/см3</th>
            <th scope="col" class="border-0">Объемная плотность щебня, г/см3</th>
            <th scope="col" class="border-0">Процент абсорбции, %</th>
            <th scope="col" class="border-0">Cр. процент абсорбции, %</th>
            <th scope="col" class="border-0">Ср. Объемная плотность щебня, г/см3</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="number" class="form-control mass-in-air"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_in_air]"
                       value="<?= $this->data['measuring']['form']['mass_in_air'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-in-air-water"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_in_air_water]"
                       value="<?= $this->data['measuring']['form']['mass_in_air_water'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-in-water"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_in_water]"
                       value="<?= $this->data['measuring']['form']['mass_in_water'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control water-density"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][water_density]"
                       value="<?= $this->data['measuring']['form']['water_density'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-water-density"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value0]"
                       value="<?= $this->data['measuring']['form']['result_value0'] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-water-absorption"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_absorption]"
                       value="<?= $this->data['measuring']['form']['result_absorption'] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle" rowspan="2">
                <input type="number" class="form-control avg-water-density"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value][result]"
                       value="<?= $this->data['measuring']['form']['result_value']['result'] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle" rowspan="2">
                <input type="number" class="form-control avg-water-absorption"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value][absorption]"
                       value="<?= $this->data['measuring']['form']['result_value']['absorption'] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <td>
                <input type="number" class="form-control mass-in-air1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_in_air1]"
                       value="<?= $this->data['measuring']['form']['mass_in_air1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-in-air-water1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_in_air_water1]"
                       value="<?= $this->data['measuring']['form']['mass_in_air_water1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-in-water1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_in_water1]"
                       value="<?= $this->data['measuring']['form']['mass_in_water1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control water-density1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][water_density1]"
                       value="<?= $this->data['measuring']['form']['water_density1'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-water-density1"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value1]"
                       value="<?= $this->data['measuring']['form']['result_value1'] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-water-absorption1"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_absorption1]"
                       value="<?= $this->data['measuring']['form']['result_absorption1'] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateContentDensityAndAbsorption" class="btn btn-primary calculate-content-density-and-absorption"
                    name="calculate_content_water_absorption">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][density_and_absorption][save]">Сохранить</button>
        </div>
    </div>
</div>