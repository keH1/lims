<div class="water_resistance_wrapper">
    <em class="info d-block mb-4">
        <strong>Водостойкость ГОСТ 32765</strong>
    </em>

    <input id="pwr_ugtp" type="hidden" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th class="align-middle text-center">Испытание</th>
            <th>Разрушающая нагрузка после насыщения водой и термостатирования образцов, Н</th>
            <th>Разрушающая нагрузка образцов, выдержанных перед испытанием в воде, Н</th>
            <th>Первоначальная площадь поперечного сечения образца, см<sup>2</sup></th>
            <th>Предел прочности при сжатии образцов после насыщения водой и термостатирования, МПа</th>
            <th>Предел прочности при сжатии образцов, выдержанных перед испытанием в воде, МПа</th>
            <th class="align-middle">Водостойкость образцов</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="align-middle text-center">Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][breaking_load_after_saturation_water_and_temperature][0]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['breaking_load_after_saturation_water_and_temperature'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][breaking_load_aged_before_testing_water][0]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['breaking_load_aged_before_testing_water'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][initial_cross_area][0]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['initial_cross_area'][0] ?? '' ?>">
            </td>
            <td class="align-middle text-center" rowspan="3">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][compressive_strength_with_water_temperature]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['compressive_strength_with_water_temperature'] ?? '' ?>">
            </td>
            <td class="align-middle text-center" rowspan="3">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][compressive_strength_before_water]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['compressive_strength_before_water'] ?? '' ?>">
            </td>
            <td class="align-middle text-center" rowspan="3">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][water_resistance]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['water_resistance'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle text-center">Определение 2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][breaking_load_after_saturation_water_and_temperature][1]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['breaking_load_after_saturation_water_and_temperature'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][breaking_load_aged_before_testing_water][1]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['breaking_load_aged_before_testing_water'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][initial_cross_area][1]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['initial_cross_area'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle text-center">Определение 3</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][breaking_load_after_saturation_water_and_temperature][2]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['breaking_load_after_saturation_water_and_temperature'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][breaking_load_aged_before_testing_water][2]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['breaking_load_aged_before_testing_water'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][water_resistance_32761][initial_cross_area][2]"
                       value="<?= $this->data['measuring']['water_resistance_32761']['initial_cross_area'][2] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="water_resistance_32761" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>

</div>