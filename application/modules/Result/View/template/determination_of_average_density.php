<div class="wrapper-average-density">
    <em class="info d-block mb-4">
        <strong>Определение средней плотности</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_average_density">

    <table class="table average-density-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Плотность воды, принимаемая равной 1 г/см3</th>
            <th scope="col" class="border-0">Масса высушенной в сушильном шкафу мерной пробы, г</th>
            <th scope="col" class="border-0">Масса мерной пробы в насыщенном водой состоянии на воздухе, г</th>
            <th scope="col" class="border-0">Масса сетчатой (перфорированной) корзины и мерной пробы в насыщенном водой состоянии,
                в воде, г</th>
            <th scope="col" class="border-0">Масса пустой сетчатой (перфорированной) корзины в воде, г</th>
            <th scope="col" class="border-0">Средняя плотность щебня (гравия), г/см3</th>
            <th scope="col" class="border-0">Ср. средняя плотность щебня (гравия), г/см3</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="number" class="form-control density-water"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][density_water]"
                       value="<?= $this->data['measuring']['form']['density_water'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control oven-dried-volumetric"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][oven_dried_volumetric]"
                       value="<?= $this->data['measuring']['form']['oven_dried_volumetric'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control water-saturated-state"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][water_saturated_state]"
                       value="<?= $this->data['measuring']['form']['water_saturated_state'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control basket-water-saturated"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][basket_water_saturated]"
                       value="<?= $this->data['measuring']['form']['basket_water_saturated'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-mesh"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][empty_mesh]"
                       value="<?= $this->data['measuring']['form']['empty_mesh'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-average-density"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value0]"
                       value="<?= $this->data['measuring']['form']['result_value0'] ?? '' ?>" readonly>
            </td>
            <td class="text-center align-middle" rowspan="2">
                <input type="number" class="form-control avg-result-average-density"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value][result]"
                       value="<?= $this->data['measuring']['form']['result_value']['result'] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <td>
                <input type="number" class="form-control density-water1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][density_water1]"
                       value="<?= $this->data['measuring']['form']['density_water1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control oven-dried-volumetric1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][oven_dried_volumetric1]"
                       value="<?= $this->data['measuring']['form']['oven_dried_volumetric1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control water-saturated-state1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][water_saturated_state1]"
                       value="<?= $this->data['measuring']['form']['water_saturated_state1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control basket-water-saturated1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][basket_water_saturated1]"
                       value="<?= $this->data['measuring']['form']['basket_water_saturated1'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control empty-mesh1"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][empty_mesh1]"
                       value="<?= $this->data['measuring']['form']['empty_mesh1'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-average-density1"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value1]"
                       value="<?= $this->data['measuring']['form']['result_value1'] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateContentAverageDensity" class="btn btn-primary calculate-content-average-density"
                    name="calculate_content_average_density">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][average_density][save]">Сохранить</button>
        </div>
    </div>
</div>