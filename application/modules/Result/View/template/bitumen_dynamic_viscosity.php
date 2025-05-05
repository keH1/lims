<div class="wrapper-bitumen-dynamic-viscosity">
    <em class="info d-block mb-4">
        <strong>Динамическая вязкость ГОСТ 33137</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th style="vertical-align: middle;">Динамическая вязкость Условие 1, Па*с</th>
            <th>Изменение динамической вязкости в результате сдвигового воздействия Условие 2, %</th>
            <th style="vertical-align: middle;">Динамическая вязкость после старения Условие 1, Па*с</th>
            <th>Изменение динамической вязкости в результате сдвигового воздействия после старения Условие 2, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][dynamic_viscosity][dynamic_viscosity_simple]"
                       value="<?= $this->data['measuring']['form']['dynamic_viscosity']['dynamic_viscosity_simple'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][dynamic_viscosity][dynamic_viscosity_shift]"
                       value="<?= $this->data['measuring']['form']['dynamic_viscosity']['dynamic_viscosity_shift'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][dynamic_viscosity][dynamic_viscosity_aging]"
                       value="<?= $this->data['measuring']['form']['dynamic_viscosity']['dynamic_viscosity_aging'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][dynamic_viscosity][dynamic_viscosity_shift_aging]"
                       value="<?= $this->data['measuring']['form']['dynamic_viscosity']['dynamic_viscosity_shift_aging'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
