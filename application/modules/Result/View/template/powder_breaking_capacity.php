<div class="breaking_capacity_wrapper">
    <em class="info d-block mb-4">
        <strong>Битумоемкость ГОСТ 32761</strong>
    </em>

    <input type="hidden" name="form_data[<?= $this->data['ugtp_id'] ?>][type]" value="p_breaking_capacity">
    <input type="hidden" value="<?= $this->data['ugtp_id'] ?>" id="pbc_ugtp">

    <table class="table table-fixed mb-3">
        <thead>
        <tr>
            <th class="align-middle text-center">Испытание</th>
            <th class="align-middle">Масса отвешенной мерной пробы, г</th>
            <th class="align-middle">Масса оставшегося после испытания минерального порошка, г</th>
            <th class="align-middle">Истинная плотность порошка, г/см<sup>3</sup></th>
            <th class="align-middle text-center">ПБ, г</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="align-middle text-center">Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][bitumen_capacity_32761][m_measured_sample][0]"
                       value="<?= $this->data['measuring']['bitumen_capacity_32761']['m_measured_sample'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][bitumen_capacity_32761][m_remaining_after_test][0]"
                       value="<?= $this->data['measuring']['bitumen_capacity_32761']['m_remaining_after_test'][0] ?? '' ?>">
            </td>

            <?php
            if($this->data['measuring']['porosity_32761']['true_density_non_ac'] != "") {
                $prefix = "non_ac";
                $density = $this->data['measuring']['porosity_32761']['true_density_non_ac'];
            }
            elseif($this->data['measuring']['porosity_32761']['true_density_ac'] != "") {
                $prefix = "ac";
                $density = $this->data['measuring']['porosity_32761']['true_density_ac'];
            }
            ?>
            <td class="align-middle text-center true_density" rowspan="2">
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][bitumen_capacity_32761][true_density_<?= $prefix ?>]"
                       value="<?= $this->data['measuring']['bitumen_capacity_32761']["true_density_{$prefix}"] ?? '' ?>">
            </td>

            <?php
            // if($this->data['form']['bitumen_capacity_32761']['bitumen_capacity_non_ac'] != "") {
            //     $capacity = $this->data['form']['bitumen_capacity_32761']['bitumen_capacity_non_ac'];
            // }
            // elseif($this->data['form']['bitumen_capacity_32761']['bitumen_capacity_ac'] != "") {
            //     $capacity = $this->data['form']['bitumen_capacity_32761']['bitumen_capacity_ac'];
            // }
            ?>
            <td class="align-middle text-center capacity" rowspan="2">
                <!-- <input class="form-control" readonly
                           type="number" step="any"
                           name="form[bitumen_capacity_32761][bitumen_capacity]"
                           value="<?= $capacity ?? '' ?>"> -->
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][bitumen_capacity_32761][bitumen_capacity]"
                       value="<?= $this->data['measuring']['bitumen_capacity_32761']['bitumen_capacity'] ?>">
            </td>
        </tr>
        <tr>
            <td class="align-middle text-center">Определение 2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][bitumen_capacity_32761][m_measured_sample][1]"
                       value="<?= $this->data['measuring']['bitumen_capacity_32761']['m_measured_sample'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][bitumen_capacity_32761][m_remaining_after_test][1]"
                       value="<?= $this->data['measuring']['bitumen_capacity_32761']['m_remaining_after_test'][1] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="bitumen_capacity" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
