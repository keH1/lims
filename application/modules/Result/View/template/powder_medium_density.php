<?php
$arrMP = [
    0 => "МП-1",
    1 => "МП-2"
];

$arrType = [
    0 => "Активированный",
    1 => "Неактивированный"
];
?>
<style>
.panel-hidden {
    display: none;
}
</style>
<div class="panel-body">
        <em class="info d-block mb-4">
            <strong>Средняя плотность и истинная плотность ГОСТ 32761</strong>
        </em>

    <input type="hidden" value="<?= $this->data['ugtp_id'] ?>" id="pmd_ugtp">
    
        <div class="mb-3">
            <div class="mb-3">
                <label>Выбор минерального порошка:</label>
                <select id="select_mp" name="form_data[<?= $this->data['ugtp_id'] ?>][select_mp]">
                    <option value="0" disabled="true" <?= empty($this->data['measuring']['select_mp']) ? "selected": "" ?>>Выберите марку порошка</option>
                    <?php foreach($arrMP as $keyMp => $mp): ?>
                        <option value="<?= ($keyMp + 1) ?>" <?= ($this->data['measuring']['select_mp'] == ($keyMp + 1)) ? "selected": "" ?>>
                            <?= $mp ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mp_1 <?= ($this->data['measuring']['select_mp'] == 1) ? "" : "panel-hidden"?>">
                <label>Выбор типа порошка:</label>
                <select id="select_type" name="form_data[<?= $this->data['ugtp_id'] ?>][select_type]">
                    <option value="0" disabled="true" selected>Выберите тип порошка</option>
                    <?php foreach($arrType as $keyType => $type): ?>
                        <option value="<?= ($keyType + 1) ?>" <?= ($this->data['measuring']['select_type'] == ($keyType + 1)) ? "selected": "" ?>>
                            <?= $type ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="average_density
             <?= ($this->data['measuring']['select_mp'] == 2 ||
        $this->data['measuring']['select_type'] == 1 ||
        $this->data['measuring']['select_type'] == 2 ? "" : "panel-hidden") ?>
        ">
            <h3>Средняя плотность</h3>
            <table class="table table-fixed list_data mb-3">
                <thead>
                <tr>
                    <th class="align-middle text-center">Испытание</th>
                    <th>Масса цилиндра с поддоном и порошком, г</th>
                    <th class="align-middle">Масса цилиндра с поддоном, г</th>
                    <th class="align-middle">Объем минерального порошка, см<sup>3</sup></th>
                    <th class="align-middle">Средняя плотность, г/см<sup>3</sup></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="align-middle text-center">Определение 1</td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][weight_cylinder_with_mineral_dust][0]"
                               value="<?= $this->data['measuring']['density_average_P52129']['weight_cylinder_with_mineral_dust'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][weight_cylinder_with_pallet][0]"
                               value="<?= $this->data['measuring']['density_average_P52129']['weight_cylinder_with_pallet'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][capacity_dust][0]"
                               value="<?= $this->data['measuring']['density_average_P52129']['capacity_dust'][0] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control" readonly
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][average_density]"
                               value="<?= $this->data['measuring']['density_average_P52129']['average_density'] ?? '' ?>">
                    </td>
                </tr>
                <tr>
                    <td class="align-middle text-center">Определение 2</td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][weight_cylinder_with_mineral_dust][1]"
                               value="<?= $this->data['measuring']['density_average_P52129']['weight_cylinder_with_mineral_dust'][1] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][weight_cylinder_with_pallet][1]"
                               value="<?= $this->data['measuring']['density_average_P52129']['weight_cylinder_with_pallet'][1] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][density_average_P52129][capacity_dust][1]"
                               value="<?= $this->data['measuring']['density_average_P52129']['capacity_dust'][1] ?? '' ?>">
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mb-3">
                <button type="button" id="density_average" class="btn btn-primary">Рассчитать</button>
            </div>
        </div>

        <div class="non_ac
             <?= ($this->data['measuring']['select_type'] == 2) ? "" : "panel-hidden"?>
        ">
            <h3>Истинная плотность</h3>
            <table class="table table-fixed table-bordered list_data mb-3">
                <thead>
                <tr>
                    <th class="align-middle text-center" rowspan="2">Испытание</th>
                    <th class="text-center" colspan="4">Неактивированный порошок</th>
                    <th class="align-middle text-center" rowspan="2">ρ МП</th>
                    <th class="align-middle text-center" rowspan="2">Требования МП-1</th>
                    <th class="align-middle text-center" rowspan="2">Требования МП-2</th>
                    <th class="align-middle text-center" rowspan="2">Требования МП-3</th>
                </tr>
                <tr>
                    <th class="align-middle">Масса колбы с порошком, г</th>
                    <th class="align-middle">Масса пустой колбы, г</th>
                    <th class="align-middle">Масса колбы с дистиллированной водой, г</th>
                    <th class="align-middle">Масса колбы с порошком и дистиллированной водой, г</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="align-middle text-center">Определение 1</td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][dust_flask_mass_non_ac][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['dust_flask_mass_non_ac'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][mass_empty_flask_non_ac][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['mass_empty_flask_non_ac'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][mass_flask_with_distilled_water][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['mass_flask_with_distilled_water'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][mass_flask_with_dust_and_distilled_water][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['mass_flask_with_dust_and_distilled_water'][0] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control" readonly
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][true_density_non_ac]"
                               value="<?= $this->data['measuring']['porosity_P52129']['true_density_non_ac'] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][norm_porosity][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['norm_porosity'][0] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][norm_porosity][1]"
                               value="<?= $this->data['measuring']['porosity_P52129']['norm_porosity'][1] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][norm_porosity][2]"
                               value="<?= $this->data['measuring']['porosity_P52129']['norm_porosity'][2] ?? '' ?>">
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mb-3">
                <button type="button" id="non_activ_dust" class="btn btn-primary">Рассчитать</button>
            </div>
        </div>

        <div class="ac
             <?= ($this->data['measuring']['select_type'] == 1) ? "" : "panel-hidden"?>
        ">
            <h3>Истинная плотность</h3>
            <table class="table table-fixed table-bordered list_data mb-3">
                <thead>
                <tr>
                    <th class="align-middle text-center" rowspan="2">Испытание</th>
                    <th class="text-center" colspan="4">Активированный порошок</th>
                    <th class="align-middle text-center" rowspan="2">ρ МП</th>
                    <th class="align-middle text-center" rowspan="2">Требования МП-1</th>
                    <th class="align-middle text-center" rowspan="2">Требования МП-2</th>
                    <th class="align-middle text-center" rowspan="2">Требования МП-3</th>
                </tr>
                <tr>
                    <th class="align-middle">Масса колбы с порошком, г</th>
                    <th class="align-middle">Масса пустой колбы, г</th>
                    <th class="align-middle">Масса колбы с раствором смачивателя, г</th>
                    <th class="align-middle">Масса колбы с порошком и раствором смачивателя, г</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="align-middle text-center">Определение 1</td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][dust_flask_mass_ac][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['dust_flask_mass_ac'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][mass_empty_flask_ac][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['mass_empty_flask_ac'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][mass_flask_with_wetting_agent][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['mass_flask_with_wetting_agent'][0] ?? '' ?>">
                    </td>
                    <td>
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][mass_flask_with_dust_and_wetting_agent][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['mass_flask_with_dust_and_wetting_agent'][0] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control" readonly
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][true_density_ac]"
                               value="<?= $this->data['measuring']['porosity_P52129']['true_density_ac'] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][norm_porosity][0]"
                               value="<?= $this->data['measuring']['porosity_P52129']['norm_porosity'][0] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][norm_porosity][1]"
                               value="<?= $this->data['measuring']['porosity_P52129']['norm_porosity'][1] ?? '' ?>">
                    </td>
                    <td class="text-center align-middle" rowspan="2">
                        <input class="form-control"
                               type="number" step="any"
                               name="form_data[<?= $this->data['ugtp_id'] ?>][porosity_P52129][norm_porosity][2]"
                               value="<?= $this->data['measuring']['porosity_P52129']['norm_porosity'][2] ?? '' ?>">
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mb-3">
                <button type="button" id="activ_dust" class="btn btn-primary">Рассчитать</button>
            </div>
        </div>

        <div>
            <button type="submit" id="porosity_save" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
