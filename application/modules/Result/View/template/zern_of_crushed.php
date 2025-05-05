<div class="grain-composition-wrapper">
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

    <em class="info d-block mb-4">
        <strong>Определение зернового состава ГОСТ 33029-2014 </strong>
        (2 параллельных определение; сумма частных остатков не отличается более чем на 1% от массы пробы; результат -среднее арифметическое значение с точностью до 0,1%)
    </em>

    <input type="hidden" id="zrn_ugtp" value="<?= $this->data['ugtp_id'] ?>">
    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="grain_crushed">

    <?php if ( !empty($this->data['fraction']) ): ?>
        <div class="row mb-4">
            <div class="col">
                <label for="sampleMass">Масса мерной пробы в сухом состоянии, г.</label>
                <input type="number" id="sampleMass" class="form-control sample-mass bg-white"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][sample_mass][1]" data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['grain_composition']['sample_mass'][1] ?? '' ?>">
            </div>
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <?php if ( !empty($this->data['fraction']) ): ?>
            <table class="table table-rubble table-fixed mb-4">
                <thead>
                <tr class="table-secondary text-center align-middle">
                    <th scope="col" class="border-0" colspan="2">Диаметр отверстий сит, мм</th>
                    <th scope="col" class="border-0">Испытание</th>
                    <th scope="col" class="border-0">Масса частного остатка на данном сите, г</th>
                    <th scope="col" class="border-0">ЧО на ситах, % по массе</th>
                    <th scope="col" class="border-0">ПО на ситах, % по массе</th>
                    <th scope="col" class="border-0">Проход</th>
                    <th scope="col" class="border-0">Норм. значения</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->data['fractions_sizes'] as $val): ?>
                    <?php if ( isset($this->data['measuring']['form']['grain_composition']['fraction'][$val]) ): ?>
                        <tr class="tr-<?=$val?> tr-trial-1">
                            <td class="align-middle" >
                                <input type="text" class="form-control text-center name name-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][name][<?=$val?>]" data-fraction="<?=$val?>" data-trial="1"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['name'][$val] ?? '' ?>" readonly>
                            </td>
                            <td class="align-middle" >
                                <input type="text" class="form-control text-center fraction fraction-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][fraction][<?=$val?>]" data-fraction="<?=$val?>" data-trial="1"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['fraction'][$val] ?? '' ?>" readonly>
                            </td>
                            <th class="text-center align-middle">1</th>
                            <td>
                                <input type="number" class="form-control private-remainder private-remainder-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][private_remainder][1][<?=$val?>]"
                                       data-fraction="<?=$val?>" data-trial="1" step="any" min="0"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['private_remainder'][1][$val] ?? '' ?>">
                            </td>
                            <td>
                                <input type="number" class="form-control private-remainders-by-mass private-remainders-by-mass-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][private_remainder_by_mass][1][<?=$val?>]"
                                       data-fraction="<?=$val?>" data-trial="1" step="any"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['private_remainder_by_mass'][1][$val] ?? '' ?>" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control total-remainder-by-mass total-remainder-by-mass-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][total_remainder_by_mass][1][<?=$val?>]"
                                       data-fraction="<?=$val?>" data-trial="1" step="any"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['total_remainder_by_mass'][1][$val] ?? '' ?>" readonly>
                            </td>
                            <td class="align-middle" >
                                <input type="number" class="form-control passed passed-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][passed][<?=$val?>]"
                                       data-fraction="<?=$val?>" data-trial="1" step="any"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['passed'][$val] ?? '' ?>" <?=!empty($this->data['measuring']['form']['grain_composition']['edit_form']) ? '' : 'readonly'?>>
                            </td>
                            <td class="align-middle" >
                                <input type="number" class="form-control norm norm-<?=$val?>"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][norm][<?=$val?>]"
                                       data-fraction="<?=$val?>" data-trial="1" step="any"
                                       value="<?= $this->data['measuring']['form']['grain_composition']['norm'][$val] ?? '' ?>">
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <span class="message mb-4 d-block">Для продолжение расчетов, выберите фракцию</span>
        <?php endif; ?>
    </div>

    <?php if ( !empty($this->data['fraction']) ): ?>
        <div class="form-group col-sm-6 row">
            <div class="col-sm-3">
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <label class="switch">
                            <input class="form-check-input edit-form" name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][edit_form]" type="checkbox"
                                <?= !empty($this->data['measuring']['form']['grain_composition']['edit_form']) ? 'checked' : '' ?>>
                            <span class="slider taken-popup popup-with-form"></span>
                        </label>
                    </div>
                    <span>Редактировать</span>
                </div>
            </div>
        </div>

        <div class="row mb-4 wrapper-btn">
            <div class="col flex-grow-0">
                <button type="button" id="calculateGrainComposition" class="btn btn-primary"
                        name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][calculate_grain_composition]">Рассчитать</button>
            </div>
            <div class="col flex-grow-0">
                <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][save]">Сохранить</button>
            </div>
        </div>
    <?php endif; ?>
</div>