<style>
    ._block_vertical {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .vertical-text {
        writing-mode: vertical-lr;
        margin-left: 0px;
        margin-right: 0px;
    }

    .input_style_width input{
        width: 90px;
    }
</style>
<div class="powder_swelling_wrapper" style="overflow-y: auto; width: 100%;">
    <em class="info d-block mb-4">
        <strong>Набухание образцов ГОСТ 32707</strong>
    </em>

    <input type="hidden" value="<?= $this->data['ugtp_id'] ?>" id="ps_ugtp">
    
    <table id="swelling_table" class="table table-bordered list_data mb-3">
        <thead>
        <tr>
            <th rowspan="2">
                <div class="_block_vertical">
                    <p class="vertical-text">№ образца</p>
                </div>
            </th>
            <th class="align-middle text-center">Масса образца на воздухе, г</th>
            <th class="align-middle text-center">Масса образца в воде, г</th>
            <th class="align-middle text-center">Масса образца на воздухе после 30 мин. в воде, г</th>
            <th class="align-middle text-center">Масса образца в воде после 30 мин. в воде, г</th>
            <th rowspan="2">
                <div class="_block_vertical">
                    <p class="vertical-text">Набухание (H)</p>
                </div>
            </th>
        </tr>
        <tr>
            <th class="align-middle text-center">m</td>
            <th class="align-middle text-center">m<sub>1</sub></th>
            <th class="align-middle text-center">m<sub>2</sub></th>
            <th class="align-middle text-center">m<sub>3</sub></th>
        </tr>
        </thead>
        <tbody class="input_style_width">
        <tr>
            <td class="align-middle text-center">1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_air][0][0]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_air'][0][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_water][0][0]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_water'][0][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_air_after_saturation_soaking_water][0][0]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_air_after_saturation_soaking_water'][0][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_water_after_saturation_soaking_water][0][0]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_water_after_saturation_soaking_water'][0][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][swelling][0][0]"
                       value="<?= $this->data['measuring']['swelling_32761']['swelling'][0][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle text-center">2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_air][0][1]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_air'][0][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_water][0][1]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_water'][0][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_air_after_saturation_soaking_water][0][1]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_air_after_saturation_soaking_water'][0][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_water_after_saturation_soaking_water][0][1]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_water_after_saturation_soaking_water'][0][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][swelling][0][1]"
                       value="<?= $this->data['measuring']['swelling_32761']['swelling'][0][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle text-center">3</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_air][0][2]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_air'][0][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_water][0][2]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_water'][0][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_air_after_saturation_soaking_water][0][2]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_air_after_saturation_soaking_water'][0][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][m_suspended_water_after_saturation_soaking_water][0][2]"
                       value="<?= $this->data['measuring']['swelling_32761']['m_suspended_water_after_saturation_soaking_water'][0][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][swelling][0][2]"
                       value="<?= $this->data['measuring']['swelling_32761']['swelling'][0][2] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle" style="text-align: right;" colspan="5">Среднее значение</td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][swelling_average][0]"
                       value="<?= $this->data['measuring']['swelling_32761']['swelling_average'][0] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <input type="hidden"
           name="form_data[<?= $this->data['ugtp_id'] ?>][swelling_32761][swelling_result]"
           value="<?= $this->data['measuring']['swelling_32761']['swelling_result'] ?? '' ?>"
    >

    <div>
        <button type="button" id="swelling_32761" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>