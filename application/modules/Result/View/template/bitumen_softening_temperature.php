<div class="wrapper-bitumen-softening-temperature">
    <em class="info d-block mb-4">
        <strong>Температура размягчения по кольцу и шару ГОСТ 11506</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <span>Расхождения результатов определений не должно превышать 1°С</span>

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Испытание</th>
            <th>Температура по КиШ, °С</th>
            <th>Среднее арифметическое</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][temperatureRaB][temperature][0]"
                       value="<?= $this->data['measuring']['form']['temperatureRaB']['temperature'][0] ?? '' ?>">
            </td>
            <td class="text-center align-middle" rowspan="3">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][temperatureRaB][temperature_average]"
                       value="<?= $this->data['measuring']['form']['temperatureRaB']['temperature_average'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Определение 2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][temperatureRaB][temperature][1]"
                       value="<?= $this->data['measuring']['form']['temperatureRaB']['temperature'][1] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="temperatureRaB" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
