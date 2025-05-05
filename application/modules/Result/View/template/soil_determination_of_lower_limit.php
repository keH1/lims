<div class="wrapper-soil-determination-of-lower-limit">
    <em class="info d-block mb-4">
        <strong>Определение нижнего предела пластичности-влажности на границе раскатывания</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Масса влажного грунта с бюксом, г</th>
            <th>Масса высушенного грунта с бюксом, г</th>
            <th>Масса пустого бюкса, г</th>
            <th>Влажность грунта w, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control mass-with-bottle"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_with_bottle]]"
                       value="<?= $this->data['measuring']['form']['mass_with_bottle'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control mass-dried-soil"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_dried_soil]]"
                       value="<?= $this->data['measuring']['form']['mass_dried_soil'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control mass-empty-bottle"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_empty_bottle]"
                       value="<?= $this->data['measuring']['form']['mass_empty_bottle'] ?? '' ?>">
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
        <button type="button" id="determinationOfLowerLimit" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
