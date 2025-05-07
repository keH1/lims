<div class="wrapper-determination-of-conditional-viscosity">
    <em class="info d-block mb-4">
        <strong>Определение условной вязкости эмульсии при температуре 40°С ГОСТ P 58952.6</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th class="text-center align-middle">№ испытания</th>
            <th class="text-center align-middle">Время истечения 50 мл эмульсии из вискозиметра через сточное отверстие диаметром 4 мм</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle">1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][emulsion_viscosity][expiration_emulsion][0]"
                       value="<?= $this->data['measuring']['form']['emulsion_viscosity']['expiration_emulsion'][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][emulsion_viscosity][expiration_emulsion][1]"
                       value="<?= $this->data['measuring']['form']['emulsion_viscosity']['expiration_emulsion'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">3</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][emulsion_viscosity][expiration_emulsion][2]"
                       value="<?= $this->data['measuring']['form']['emulsion_viscosity']['expiration_emulsion'][2] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle" style="text-align: right;">Среднее значение</td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][emulsion_viscosity][expiration_emulsion_average]"
                       value="<?= $this->data['measuring']['form']['emulsion_viscosity']['expiration_emulsion_average'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="emulsionViscosity" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>