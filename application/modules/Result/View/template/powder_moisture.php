<div class="powder_moisture_wrapper">
    <em class="info d-block mb-4">
        <strong>Влажность ГОСТ 32762</strong>
    </em>

    <input type="hidden" value="<?= $this->data['ugtp_id'] ?>" id="pm_ugtp">
    
    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th class="align-middle text-center">Испытание</th>
            <th>Масса чашки с мерной пробой до высушивания, г</th>
            <th>Масса чашки с мерной пробой после высушивания, г</th>
            <th class="align-middle text-center">Масса чашки, г</th>
            <th>Влажность минерального порошка, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="align-middle text-center">Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][before_drying_mass_cup][0]"
                       value="<?= $this->data['measuring']['humidity_32761']['before_drying_mass_cup'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][after_drying_mass_cup][0]"
                       value="<?= $this->data['measuring']['humidity_32761']['after_drying_mass_cup'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][mass_cup][0]"
                       value="<?= $this->data['measuring']['humidity_32761']['mass_cup'][0] ?? '' ?>">
            </td>
            <td class="align-middle text-center" rowspan="2">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][humidity]"
                       value="<?= $this->data['measuring']['humidity_32761']['humidity'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle text-center">Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][before_drying_mass_cup][1]"
                       value="<?= $this->data['measuring']['humidity_32761']['before_drying_mass_cup'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][after_drying_mass_cup][1]"
                       value="<?= $this->data['measuring']['humidity_32761']['after_drying_mass_cup'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][humidity_32761][mass_cup][1]"
                       value="<?= $this->data['measuring']['humidity_32761']['mass_cup'][1] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="humidity_32761" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>

</div>
