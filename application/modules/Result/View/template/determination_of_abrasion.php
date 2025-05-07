<div class="abrasion-wrapper">
    <em class="info d-block mb-4">
        <strong>Определение сопротивления истираемости по показателю микро-Деваль ГОСТ 33024-2014</strong>
        (2 параллельных определения с расхождением не более 1%; результат - среднее арифметическое значение с точностью до первого знака после запятой)
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_abrasion">

    <table class="table table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Фракция, мм</th>
            <th scope="col" class="border-0">Испытание</th>
            <th scope="col" class="border-0">Масса мерной пробы щебня (гравия) до испытания М<sup>1</sup>, г</th>
            <th scope="col" class="border-0">Объединенная масса остатков на сите с размером ячеек 1,6 мм и 8 мм М<sup>2</sup>, г, высушенная до постоянной массы</th>
            <th scope="col" class="border-0">Истираемость щебня (гравия) по показателю микро-Деваль МД, %</th>
            <th scope="col" class="border-0">Среднее арифметическое значение</th>
            <th scope="col" class="border-0">Марка</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle" rowspan="2">
                <select class="form-select fraction fraction-abrasion" name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][fraction]">
                    <option value="">Выберите фракцию</option>
                    <option value="10_14" <?= $this->data['measuring']['form']['abrasion']['fraction'] === '10_14' ? 'selected' : '' ?>>
                        от 10 до 14
                    </option>
                    <option value="4_6.3"
                        <?= $this->data['measuring']['form']['abrasion']['fraction'] === '4_6.3' ? 'selected' : '' ?>>
                        от 4 до 6.3
                    </option>
                    <option value="6.3_10"
                        <?= $this->data['measuring']['form']['abrasion']['fraction'] === '6.3_10' ? 'selected' : '' ?>>
                        от 6.3 до 10
                    </option>
                    <option value="8_11.2"
                        <?= $this->data['measuring']['form']['abrasion']['fraction'] === '8_11.2' ? 'selected' : '' ?>>
                        от 8 до 11.2
                    </option>
                    <option value="11.2_16"
                        <?= $this->data['measuring']['form']['abrasion']['fraction'] === '11.2_16' ? 'selected' : '' ?>>
                        от 11.2 до 16
                    </option>
                </select>
            </td>
            <th scope="row" class="text-center align-middle">1</th>
            <td>
                <input type="number" class="form-control sample-mass-before"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][sample_mass_before][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['sample_mass_before'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control mass-of-residues"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][mass_of_residues][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['mass_of_residues'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control abrasion"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][abrasion][1]"
                       data-trial="1" step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['abrasion'][1] ?? '' ?>" readonly>
            </td>
            <td rowspan="2" class="align-middle">
                <input type="number" class="form-control arithmetic-mean"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]"
                       step="any"
                       value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
            </td>
            <td rowspan="2" class="align-middle">
                <input type="text" class="form-control brand"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][brand]"
                       step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['brand'] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row" class="text-center align-middle border-bottom">2</th>
            <td class="border-bottom">
                <input type="number" class="form-control sample-mass-before"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][sample_mass_before][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['sample_mass_before'][2] ?? '' ?>">
            </td>
            <td class="border-bottom">
                <input type="number" class="form-control mass-of-residues"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][mass_of_residues][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['mass_of_residues'][2] ?? '' ?>">
            </td>
            <td class="border-bottom">
                <input type="number" class="form-control abrasion"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][abrasion][2]"
                       data-trial="2" step="any"
                       value="<?= $this->data['measuring']['form']['abrasion']['abrasion'][2] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateAbrasion" class="btn btn-primary">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][abrasion][save]">Сохранить</button>
        </div>
    </div>
</div>