<div class="wrapper-weak-breeds">
    <div class="measurement_content">
        <div class="wrapper_fraction">
            <div class="col">
                <label for="fraction"> Фракция</label>
                <select class="form-select fraction w-100 mw-100" id="fraction" name="form_data[<?= $this->data['ugtp_id'] ?>][form][fraction]" required>
                    <option value="">Выберите фракцию</option>
                    <?php foreach ($this->data['fractions'] as $key => $val): ?>
                        <option value="<?= $key ?>"
                            <?= $this->data['fraction'] == $key ? 'selected' : '' ?>>
                            <?= $val['title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-4 d-none">
        <div class="form-group row">
            <?php foreach ($this->data['measuring']['grain_composition']['average_private_remainder'] as $key => $val): ?>
                <div class="col">
                    <input type="number" class="form-control average-private-remainder"
                           step="any" data-fraction="<?= $key ?>" value="<?= $val ?>" readonly hidden>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_soft_rock">

    <?php if ( !empty($this->data['fraction']) ): ?>
        <em class="info d-block mb-4">
            <strong>Определение содержания зерен слабых пород в щебне (гравии) ГОСТ 33054-2014</strong>
            (2 параллельных испытания с расхождением не более 1.0%; результат среднее арифметическое значение с точностью до второго знака после запятой)
        </em>

        <table class="table table-fixed mb-4">
            <thead>
            <tr class="table-secondary text-center align-middle">
                <th scope="col" class="border-0">Фракция, мм</th>
                <th scope="col" class="border-0">Испытание</th>
                <th scope="col" class="border-0">Масса мерной пробы до испытания М<sup>1</sup>, г</th>
                <th scope="col" class="border-0">Масса зерен слабых пород M, г</th>
                <th scope="col" class="border-0">Содержание зерен слабых пород С<sup>п</sup>, %</th>
                <th scope="col" class="border-0">Среднее арифметическое значение</th>
            </tr>
            </thead>
            <?php foreach ($this->data['fraction_consist'] as $key => $fraction): ?>
                <tbody>
                <tr>
                    <td scope="row" class="text-center align-middle"
                        rowspan="<?=count(current($this->data['measuring']['form']['grains_weak_breeds']['sample_mass_before'] ?? []) ? current($this->data['measuring']['form']['grains_weak_breeds']['sample_mass_before'] ?? []) : [])?>">
                        <select class="form-select fraction" disabled>
                            <?php foreach ($this->data['fractions'][$key]['fraction'] as $k => $val): ?>
                                <option value="<?= $k ?>" <?= $key === $k ? 'selected' : '' ?>>
                                    <?= $val ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <th scope="row" class="text-center align-middle">1</th>
                    <td>
                        <input type="number" class="form-control sample-mass-before"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][sample_mass_before][<?= $key ?>][1]"
                               data-trial="1" data-fraction="<?= $key ?>" step="any" min="0"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['sample_mass_before'][$key][1] ?? '' ?>">
                    </td>
                    <td>
                        <input type="number" class="form-control mass-weak-breeds"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][mass_weak_breeds][<?= $key ?>][1]"
                               data-trial="1" data-fraction="<?= $key ?>" step="any" min="0"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['mass_weak_breeds'][$key][1] ?? '' ?>">
                    </td>
                    <td>
                        <input type="number" class="form-control grains-weak-breeds"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][grains_weak_breeds][<?= $key ?>][1]"
                               data-trial="1" data-fraction="<?= $key ?>" step="any"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['grains_weak_breeds'][$key][1] ?? '' ?>" readonly>
                    </td>
                    <td class="text-center align-middle"
                        rowspan="<?=count(current($this->data['form']['grains_weak_breeds']['sample_mass_before'] ?? []) ? current($this->data['form']['grains_weak_breeds']['sample_mass_before'] ?? []) : [])?>">
                        <input type="number" class="form-control grain-average-value"
                               data-fraction="<?= $key ?>" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][result_value][<?= $key ?>]"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['result_value'][$key] ?? '' ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-center align-middle border-bottom">2</th>
                    <td class="border-bottom">
                        <input type="number" class="form-control sample-mass-before"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][sample_mass_before][<?= $key ?>][2]"
                               data-trial="2" data-fraction="<?= $key ?>" step="any" min="0"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['sample_mass_before'][$key][2] ?? '' ?>">
                    </td>
                    <td class="border-bottom">
                        <input type="number" class="form-control mass-weak-breeds"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][mass_weak_breeds][<?= $key ?>][2]"
                               data-trial="2" data-fraction="<?= $key ?>" step="any" min="0"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['mass_weak_breeds'][$key][2] ?? '' ?>">
                    </td>
                    <td class="border-bottom">
                        <input type="number" class="form-control grains-weak-breeds"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][grains_weak_breeds][<?= $key ?>][2]"
                               data-trial="2" data-fraction="<?= $key ?>" step="any"
                               value="<?= $this->data['measuring']['form']['grains_weak_breeds']['grains_weak_breeds'][$key][2] ?? '' ?>" readonly>
                    </td>
                </tr>
                </tbody>
            <? endforeach; ?>
        </table>

        <?php if ( count($this->data['fraction_consist']) > 1 ): ?>
            <div class="form-group row mb-4">
                <div class="col">
                    <label for="mixtureWeakBreeds">Смеси фракций содержание зерен слабых, %</label>
                    <input type="number" id="mixtureWeakBreeds" class="form-control mixture-weak-breeds"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][mixture_weak_breeds]" step="any"
                           value="<?= $this->data['measuring']['form']['grains_weak_breeds']['mixture_weak_breeds'] ?? '' ?>" readonly>
                </div>
            </div>
        <? endif; ?>

    <?php else: ?>
        <span>Для продолжение расчетов, выберите фракцию</span>
    <? endif; ?>
    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateWeakBreeds" class="btn btn-primary"
                    name="calculate_weak_breeds">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][grains_weak_breeds][save]">Сохранить</button>
        </div>
    </div>
</div>