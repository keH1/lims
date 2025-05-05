<div class="wrapper-humidity">
        <em class="info d-block mb-4">
            <strong>Определение влажности</strong>
        </em>

        <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_humidity">

        <table class="table humidity-table table-fixed mb-4">
            <thead>
            <tr class="table-secondary text-center align-middle">
                <th scope="col" class="border-0">Масса мерной пробы щебня (гравия) во влажном состоянии, г</th>
                <th scope="col" class="border-0">Масса мерной пробы щебня (гравия), высушенного до постоянной массы, г</th>
                <th scope="col" class="border-0">Влажность щебня (гравия), %</th>
            </tr>
            </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="number" class="form-control first-hum-mass"
                               data-trial="1" step="any" min="0"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][first_mass]"
                               value="<?= $this->data['measuring']['form']['first_mass'] ?? '' ?>">
                    </td>
                    <td>
                        <input type="number" class="form-control second-hum-mass"
                               data-trial="1" step="any" min="0"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][second_mass]"
                               value="<?= $this->data['measuring']['form']['second_mass'] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle">
                        <input type="number" class="form-control result-mass"
                               step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                               value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
                    </td>
                </tr>
                </tbody>
        </table>

        <div class="row mb-4">
            <div class="col flex-grow-0">
                <button type="button" id="calculateContentHumidity" class="btn btn-primary calculate-content-humidity"
                        name="calculate_content_grains">Рассчитать</button>
            </div>
            <div class="col flex-grow-0">
                <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][humidity][save]">Сохранить</button>
            </div>
        </div>
</div>