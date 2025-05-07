<div class="wrapper-bitumen-flash-point">
    <em class="info d-block mb-4">
        <strong>Температура вспышки ГОСТ 33141</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <label for="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][actual_barometric_pressure]">Фактическое барометрическое давление
        во время испытания битума, кПа
    </label>
    <input class="form-control"
           type="number" step="any"
           name="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][actual_barometric_pressure]"
           value="<?= $this->data['measuring']['form']['flash_point_33133']['actual_barometric_pressure'] ?? '' ?>">

    <table class="table table-fixed list_data mb-3 hide-list-data" style="display: none;">
        <thead>
        <tr>
            <th style="vertical-align: middle;">Испытание</th>
            <th>Температура вспышки, определенная
                при испытании битума при фактическом
                барометрическом давлении, °С
            </th>
            <th style="vertical-align: middle;">Температуру вспышки</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][actual_flash_point][0]"
                       value="<?= $this->data['measuring']['form']['flash_point_33133']['actual_flash_point'][0] ?? '' ?>">
            </td>
            <td class="text-center align-middle" rowspan="2">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][flash_point_trial]"
                       value="<?= $this->data['measuring']['form']['flash_point_33133']['flash_point_trial'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Определение 2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][actual_flash_point][1]"
                       value="<?= $this->data['measuring']['form']['flash_point_33133']['actual_flash_point'][1] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <input type="hidden"
           name="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][true_flash_point]"
           value="<?= $this->data['measuring']['form']['flash_point']['true_flash_point'] ?? '' ?>">

    <div class="t_block mb-3">
        <label for="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][flash_point_final]">Температура вспышки</label>
        <input class="form-control"
               type="number" step="any"
               name="form_data[<?= $this->data['ugtp_id'] ?>][form][flash_point_33133][flash_point_final]"
               value="<?= $this->data['measuring']['form']['flash_point_33133']['flash_point_final'] ?? '' ?>">
    </div>

    <div>
        <button type="button" id="flashPoint" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
