<div class="clay-particles-wrapper">
    <em class="info d-block mb-4">
        <strong>Определение содержания пылевидных и глинистых частиц ГОСТ 33055-2014</strong>
        (2 параллельных испытания с расхождением не более 0,5 %; результат - среднее арифмитическое значение с точностью до первого знака после запятой)
    </em>

    <input type="hidden" id="dust_ugtp" value="<?= $this->data['ugtp_id'] ?>">
    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="d_dust">

    <div class="form-group row">
        <div class="col-sm-6">
            <table class="table mb-5">
                <thead>
                <tr class="table-secondary text-center align-middle">
                    <th scope="col" class="border-0">Массы до постоянной (испытание 1), г</th>
                    <th scope="col" class="border-0 w-1"></th>
                </tr>
                </thead>
                <tbody class="rec-wrapper">
                <tr>
                    <td>
                        <input type="number" class="form-control"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][mass_to_constant][1][0]"
                               step="any" min="0"
                               value="<?= $this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][1][0] ?? '' ?>">
                    </td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary mt-0 add-del-mass-1 add-mass-1" type="button">
                            <svg class="icon align-middle" width="15" height="15">
                                <use xlink:href="/production_laboratory/assets/icon/icons.svg#add"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                <?php if (isset($this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][1]) &&
                    count($this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][1]) > 1): ?>
                    <?php $i=0; ?>
                    <?php foreach ($this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][1] as $key => $val): ?>
                        <?php
                        if ($key === 0) {
                            continue;
                        }

                        $i++;
                        ?>
                        <tr class="rec">
                            <td>
                                <input type="number" class="form-control"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][mass_to_constant][1][<?=$i?>]"
                                       step="any" min="0"
                                       value="<?=$val?>">
                            </td>
                            <td class="align-middle text-center">
                                <button type="button" class="btn btn-danger add-del-mass-1 remove-this mb-0">
                                    <svg class="icon align-middle" width="15" height="15">
                                        <use xlink:href="/production_laboratory/assets/icon/icons.svg#del"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <table class="table table-rubble measuring-sheet mb-5">
                <thead>
                <tr class="table-secondary text-center align-middle">
                    <th scope="col" class="border-0">Массы до постоянной (испытание 2), г</th>
                    <th scope="col" class="border-0 w-1"></th>
                </tr>
                </thead>
                <tbody class="rec-wrapper">
                <tr>
                    <td>
                        <input type="number" class="form-control"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][mass_to_constant][2][0]"
                               step="any" min="0"
                               value="<?= $this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][2][0] ?? '' ?>">
                    </td>
                    <td class="align-middle text-center">
                        <button class="btn btn-primary mt-0 add-del-mass-2 add-mass-2" type="button">
                            <svg class="icon align-middle" width="15" height="15">
                                <use xlink:href="/production_laboratory/assets/icon/icons.svg#add"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                <?php if (isset($this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][2]) &&
                    count($this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][2]) > 1): ?>
                    <?php $i=0; ?>
                    <?php foreach ($this->data['measuring']['form']['clay_particle_content']['mass_to_constant'][2] as $key => $val): ?>
                        <?php
                        if ($key === 0) {
                            continue;
                        }

                        $i++;
                        ?>
                        <tr class="rec">
                            <td>
                                <input type="number" class="form-control"
                                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][mass_to_constant][2][<?=$i?>]"
                                       step="any" min="0"
                                       value="<?=$val?>">
                            </td>
                            <td class="align-middle text-center">
                                <button type="button" class="btn btn-danger add-del-mass-2 remove-this mb-0">
                                    <svg class="icon align-middle" width="15" height="15">
                                        <use xlink:href="/production_laboratory/assets/icon/icons.svg#del"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <table class="table mb-4">
        <thead>
        <tr class="table-secondary text-center align-middle">
            <th scope="col" class="border-0">Испытание</th>
            <th scope="col" class="border-0">Масса мерной пробы до промывки, г</th>
            <th scope="col" class="border-0">Масса мерной пробы после промывки, г</th>
            <th scope="col" class="border-0">Содержание пылевидных и глинистых частиц, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row" class="text-center align-middle">1</th>
            <td>
                <input type="number" class="form-control sample-mass-before sample-mass-before-1"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][sample_mass_before][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['clay_particle_content']['sample_mass_before'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control sample-mass-after sample-mass-after-1"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][sample_mass_after][1]"
                       data-trial="1" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['clay_particle_content']['sample_mass_after'][1] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control clay-particle-content clay-particle-content-1"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][clay_particle_content][1]"
                       data-trial="1" step="any"
                       value="<?= $this->data['measuring']['form']['clay_particle_content']['clay_particle_content'][1] ?? '' ?>" readonly>
            </td>
        </tr>
        <tr>
            <th scope="row" class="text-center align-middle">2</th>
            <td>
                <input type="number" class="form-control sample-mass-before sample-mass-before-2"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][sample_mass_before][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['clay_particle_content']['sample_mass_before'][2] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control sample-mass-after sample-mass-after-2"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][sample_mass_after][2]"
                       data-trial="2" min="0" step="any"
                       value="<?= $this->data['measuring']['form']['clay_particle_content']['sample_mass_after'][2] ?? '' ?>">
            </td>
            <td>
                <input type="number" class="form-control clay-particle-content clay-particle-content-2"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][clay_particle_content][clay_particle_content][2]" data-trial="2" step="any"
                       value="<?= $this->data['measuring']['form']['clay_particle_content']['clay_particle_content'][2] ?? '' ?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="form-group row mb-4">
        <div class="col">
            <label for="averageParticleContent">Cодержания пылевидных и глинистых, cреднее арифметическое значение</label>
            <input type="number" id="averageParticleContent" class="form-control average-particle-content"
                   name="form_data[<?= $this->data['ugtp_id'] ?>][form][result_value]" data-trial="2" step="any"
                   value="<?= $this->data['measuring']['form']['result_value'] ?? '' ?>" readonly>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col flex-grow-0">
            <button type="button" id="particleContentCalculate" class="btn btn-primary particle-content-calculate"
                    name="particle_content_calculate">Рассчитать</button>
        </div>
        <div class="col flex-grow-0">
            <button type="submit" class="btn btn-primary" name="form_data[<?= $this->data['ugtp_id'] ?>][form][grain_composition][save]">Сохранить</button>
        </div>
    </div>
</div>