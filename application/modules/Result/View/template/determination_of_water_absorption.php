<div class="wrapper-water-absorption">
    <em class="info d-block mb-4">
        <strong>Определение водопоглощения</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_water_absorption">

    <table class="table water-absorption-table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Масса мерной пробы щебня (гравия) в насыщенном водой состоянии на воздухе, г</th>
            <th scope="col" class="border-0">Масса высушенной в сушильном шкафу мерной пробы щебня (гравия), г</th>
            <th scope="col" class="border-0">Водопоглощение щебня (гравия)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="number" class="form-control mass-of-measured"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_of_measured]"
                       value="<?= $this->data['measuring']['form']['mass_of_measured'] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control sample-of-crushed"
                       data-trial="1" step="any" min="0"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][sample_of_crushed]"
                       value="<?= $this->data['measuring']['form']['sample_of_crushed'] ?? '' ?>">
            </td>
            <td class="text-center align-middle">
                <input type="number" class="form-control result-water-absorption"
                       step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                       value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateContentWaterAbsorption" class="btn btn-primary calculate-content-water-absorption"
                    name="calculate_content_water_absorption">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][water_absorption][save]">Сохранить</button>
        </div>
    </div>
</div>