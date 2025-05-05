<div class="crushability-wrapper">
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

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_crushability">

    <?php if ( !empty($this->data['fraction']) ): ?>
        <em class="info d-block mb-4">
            <strong>Определение дробимости ГОСТ 33030-2014</strong>
            (2 параллельных определения с расхождением не более 2%; результат - среднее арифметическое значение с точностью до первого знака после запятой)
        </em>

        <div class="mb-4">
            <div class="form-group row">
                <div class="col">
                    <label for="breedCondition">Состояние породы</label>
                    <select class="form-select breed-condition mw-100" id="breedCondition"
                            name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][breed_condition]">
                        <option value="" selected disabled>Выберите состояние породы</option>
                        <option value="dry"
                            <?= $this->data['measuring']['form']['crushability']['breed_condition'] === 'dry' ? 'selected' : '' ?>>
                            В сухом состоянии
                        </option>
                        <option value="water"
                            <?= $this->data['measuring']['form']['crushability']['breed_condition'] === 'water' ? 'selected' : '' ?>>
                            В насыщенном водой состоянии
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <table class="table table-fixed mb-4">
            <thead>
            <tr class="table-secondary text-center align-middle">
                <th scope="col" class="border-0">Фракция, мм</th>
                <th scope="col" class="border-0">Испытание</th>
                <th scope="col" class="border-0">Масса испытываемой мерной пробы щебня (гравия), г</th>
                <th scope="col" class="border-0">Масса остатка на контрольном сите, г</th>
                <th scope="col" class="border-0">Дробимость, %</th>
                <th scope="col" class="border-0">Среднее арифметическое значение</th>
                <th scope="col" class="border-0">Марка по дробимости</th>
            </tr>
            </thead>
            <?php foreach ($this->data['fraction_consist'] as $key => $fraction): ?>
                <tbody>
                <tr>
                    <td class="text-center align-middle"
                        rowspan="<?=count(current($this->data['measuring']['form']['crushability']['sample_mass'] ?? []) ? current($this->data['measuring']['form']['crushability']['sample_mass'] ?? []) : [])?>">
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
                        <input type="number" class="form-control sample-mass"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][sample_mass][<?= $key ?>][1]"
                               data-trial="1" data-fraction="<?= $key ?>" min="0" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['sample_mass'][$key][1] ?? '' ?>">
                    </td>
                    <td>
                        <input type="number" class="form-control residue-mass"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][residue_mass][<?= $key ?>][1]"
                               data-trial="1" data-fraction="<?= $key ?>" min="0" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['residue_mass'][$key][1] ?? '' ?>">
                    </td>
                    <td>
                        <input type="number" class="form-control crushability"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][crushability][<?= $key ?>][1]"
                               data-trial="1" data-fraction="<?= $key ?>" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['crushability'][$key][1] ?? '' ?>" readonly>
                    </td>
                    <td rowspan="<?=count(current($this->data['form']['crushability']['sample_mass'] ?? []) ? current($this->data['form']['crushability']['sample_mass'] ?? []) : [])?>" class="align-middle">
                        <input type="number" class="form-control arithmetic-mean"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][result_value][<?= $key ?>]"
                               data-fraction="<?= $key ?>" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['result_value'][$key] ?? '' ?>" readonly>
                    </td>
                    <td rowspan="<?=count(current($this->data['form']['crushability']['sample_mass'] ?? []) ? current($this->data['form']['crushability']['sample_mass'] ?? []) : [])?>" class="align-middle">
                        <input class="form-control crushability-brand bg-white"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][crushability_brand][<?= $key ?>]"
                               data-fraction="<?= $key ?>" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['crushability_brand'][$key] ?? '' ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row" class="text-center align-middle border-bottom">2</th>
                    <td class="border-bottom">
                        <input type="number" class="form-control sample-mass"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][sample_mass][<?= $key ?>][2]"
                               data-trial="2" data-fraction="<?= $key ?>" min="0" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['sample_mass'][$key][2] ?? '' ?>">
                    </td>
                    <td class="border-bottom">
                        <input type="number" class="form-control residue-mass"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][residue_mass][<?= $key ?>][2]"
                               data-trial="2" data-fraction="<?= $key ?>" min="0" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['residue_mass'][$key][2] ?? '' ?>">
                    </td>
                    <td class="border-bottom">
                        <input type="number" class="form-control crushability"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][crushability][<?= $key ?>][2]"
                               data-trial="2" data-fraction="<?= $key ?>" data-sieve="<?= $key ?>" step="any"
                               value="<?= $this->data['measuring']['form']['crushability']['crushability'][$key][2] ?? '' ?>" readonly>
                    </td>
                </tr>
                </tbody>
            <? endforeach; ?>
        </table>

        <?php if ( count($this->data['fraction_consist']) > 1 ): ?>
            <div class="form-group row mb-4 crushability-mixture">
                <div class="col">
                    <label for="crushabilityMixture">Дробимость зерен щебня (гравия), состоящего из смеси фракций, %</label>
                    <input type="number" id="crushabilityMixture"
                           class="form-control crushability-mixture"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][crushability_mixture]"
                           value="<?= $this->data['measuring']['form']['crushability']['crushability_mixture'] ?? '' ?>" readonly>
                </div>
                <div class="col">
                    <label for="strengthGrade">Марка по дробимости</label>
                    <input type="text" id="strengthGrade" class="form-control strength-grade"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][form][crushability][strength_grade]"
                           value="<?= $this->data['measuring']['form']['crushability']['strength_grade'] ?? '' ?>">
                </div>
            </div>
        <? endif; ?>


    <?php else: ?>
        <span>Для продолжение расчетов, выберите фракцию</span>
    <? endif; ?>
    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="сalculateСrushability" class="btn btn-primary"
                    name="сalculate_crushability">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form[crushability][save]">Сохранить</button>
        </div>
    </div>
</div>