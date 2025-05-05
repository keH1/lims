<div class="wrapper-soil-determination-of-plasticity-index">
    <em class="info d-block mb-4">
        <strong>Определение числа пластичности</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Влажность на границе текучести</th>
            <th>Влажность на границе раскатывания</th>
            <th>Число пластичности</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control content-at-yield-point"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][content_at_yield_point]]"
                       value="<?= $this->data['measuring']['form']['content_at_yield_point'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control rolling-boundary"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][rolling_boundary]]"
                       value="<?= $this->data['measuring']['form']['rolling_boundary'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control result" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result]"
                       value="<?= $this->data['measuring']['form']['result'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="determinationOfMPlasticityIndex" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
