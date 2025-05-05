<div class="wrapper-soil-determination-of-plasticity">
    <em class="info d-block mb-4">
        <strong>Определение плотности грунта (метод режущего кольца)</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Масса грунта с кольцом и пластинками, г</th>
            <th>Масса кольца, г</th>
            <th>Масса пластинок, г</th>
            <th>Внутренний объем кольца, см3</th>
            <th>Плотность грунта р, г/см3</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control mass-with-ring-and-plates"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_with_ring_and_plates]]"
                       value="<?= $this->data['measuring']['form']['mass_with_ring_and_plates'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control mass-ring"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_ring]]"
                       value="<?= $this->data['measuring']['form']['mass_ring'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control mass-plates"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_plates]"
                       value="<?= $this->data['measuring']['form']['mass_plates'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control volume-ring"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][volume-ring]"
                       value="<?= $this->data['measuring']['form']['volume-ring'] ?? '' ?>">
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
        <button type="button" id="determinationOfPlasticity" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
