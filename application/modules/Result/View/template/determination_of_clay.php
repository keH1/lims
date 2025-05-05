<div class="wrapper-clay-lumps">
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

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_clay">

    <em class="info d-block mb-4">
        <strong>Определение содержания глины в комках ГОСТ 33026-2014</strong>
        (2 параллельных испытания с расхождением не более 0.1%; результат - среднее арифметическое значение с точностью до второго знака после запятой)
    </em>

    <table class="table clay-content-in-lumps table-fixed mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Фракция, мм</th>
            <th scope="col" class="border-0">Испытание</th>
            <th scope="col" class="border-0">Масса мерной пробы до испытания, г.</th>
            <th scope="col" class="border-0">Масса выделенной глины в комках, высушенной до постоянной массы, г</th>
            <th scope="col" class="border-0">Содержание комков глины в щебне, %</th>
            <th scope="col" class="border-0">Среднее арифметическое значение</th>
        </tr>
        </thead>
        <?php foreach ($this->data['fraction_consist'] as $key => $fraction): ?>
            <tbody>
            <tr>
                <td scope="row" class="text-center align-middle"
                    rowspan="<?=count(current($this->data['measuring']['form']['content_clay_lumps']['dried_sample_mass'] ?? []) ? current($this->data['measuring']['form']['content_clay_lumps']['dried_sample_mass'] ?? []) : [])?>">
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
                    <input type="number" class="form-control mass-before"
                           data-trial="1" data-fraction="<?= $key ?>" step="any" min="0"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][mass_before][<?= $key ?>][1]"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['mass_before'][$key][1] ?? '' ?>">
                </td>
                <td>
                    <input type="number"
                           class="form-control dried-sample-mass"
                           data-trial="1" data-fraction="<?= $key ?>" step="any" min="0"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][dried_sample_mass][<?= $key ?>][1]"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['dried_sample_mass'][$key][1] ?? '' ?>">
                </td>
                <td>
                    <input type="number" class="form-control clay-lump-content"
                           data-trial="1" data-fraction="<?= $key ?>" step="any"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][clay_lump_content][<?= $key ?>][1]"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['clay_lump_content'][$key][1] ?? '' ?>" readonly>
                </td>
                <td class="text-center align-middle"
                    rowspan="<?=count(current($this->data['measuring']['form']['content_clay_lumps']['dried_sample_mass'] ?? []) ? current($this->data['measuring']['form']['content_clay_lumps']['dried_sample_mass'] ?? []) : [])?>">
                    <input type="number" class="form-control average-clay-content"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][result_value][<?=$key?>]"
                           data-fraction="<?= $key ?>" step="any"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['result_value'][$key] ?? '' ?>" readonly>
                </td>
            </tr>
            <tr>
                <th scope="row" class="text-center align-middle border-bottom">2</th>
                <td class="border-bottom">
                    <input type="number" class="form-control mass-before"
                           data-trial="2" data-fraction="<?= $key ?>" step="any" min="0"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][mass_before][<?= $key ?>][2]"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['mass_before'][$key][2] ?? '' ?>">
                </td>
                <td class="border-bottom">
                    <input type="number"
                           class="form-control dried-sample-mass"
                           data-trial="2" data-fraction="<?= $key ?>" step="any" min="0"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][dried_sample_mass][<?= $key ?>][2]"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['dried_sample_mass'][$key][2] ?? '' ?>">
                </td>
                <td class="border-bottom">
                    <input type="number" class="form-control clay-lump-content"
                           data-trial="2" data-fraction="<?= $key ?>" step="any"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][content_clay_lumps][clay_lump_content][<?= $key ?>][2]"
                           value="<?= $this->data['measuring']['form']['content_clay_lumps']['clay_lump_content'][$key][2] ?? '' ?>" readonly>
                </td>
            </tr>
            </tbody>
        <? endforeach; ?>
    </table>

    <?php if ( count($this->data['fraction_consist']) > 1 ): ?>
        <div class="form-group row mb-4">
            <div class="col">
                <label for="clayMixtureContent">Содержание комков глины в смеси фракций, %</label>
                <input type="number" id="clayMixtureContent"
                       class="form-control clay-mixture-content" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][content_clay_lumps][clay_mixture_content]"
                       value="<?= $this->data['measuring']['form']['content_clay_lumps']['clay_mixture_content'] ?? '' ?>" readonly>
            </div>
        </div>
    <? endif; ?>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="calculateClayContent" class="btn btn-primary calculate-clay-content"
                    name="calculate_clay_content">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form[grain_composition][save]">Сохранить</button>
        </div>
    </div>
</div>