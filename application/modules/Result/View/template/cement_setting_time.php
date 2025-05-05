<div class="wrapper-cement-setting-time">
    <em class="info d-block mb-4">
        <strong>Определение сроков схватывания (на приборе Вика)</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_water_absorption">

    <table class="table water-absorption-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col">Время начала затворения</th>
            <th scope="col">Время начала схватывания</th>
            <th scope="col">Время конца схватывания</th>
            <th scope="col">Начало схватывания, мин</th>
            <th scope="col">Конец схватывания, мин</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="time" class="form-control start-time-closing"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][start_time_closing]"
                       value="<?= $this->data['measuring']['form']['start_time_closing'] ?? '' ?>">
            </td>
            <td>
                <input type="time" class="form-control setting-time"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][setting_time]"
                       value="<?= $this->data['measuring']['form']['setting_time'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="time" class="form-control end-setting-time"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][end_setting_time]"
                       value="<?= $this->data['measuring']['form']['end_setting_time'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control start-setting" readonly
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][start_setting]"
                       value="<?= $this->data['measuring']['form']['start_setting'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control end-setting" readonly
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][end_setting]"
                       value="<?= $this->data['measuring']['form']['end_setting'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="calculateContentCementSettingTime" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>