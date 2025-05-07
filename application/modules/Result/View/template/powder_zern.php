<div class="powder_zern_wrapper">
    <em class="info d-block mb-4">
        <strong>Зерновой состав</strong>
    </em>

    <input type="hidden" id="pwd_zern" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th class="text-center align-middle">Испытание</th>
            <th class="align-middle">Сито</th>
            <th>Масса остатка на сите, г</th>
            <th>Масса мерной пробы, г</th>
            <th>Частные остатки на сите</th>
            <th>Содержание частиц порошка</th>
            <th>Требования МП-1</th>
            <th>Требования МП-2</th>
            <th>Требования МП-3</th>
        </tr>
        </thead>
        <tdoby>
            <tr>
                <td class="text-center align-middle" rowspan="4">Определение 1</td>
                <td>
                    <div style="visibility: collapse">1</div>
                    С сеткой 2.0 мм
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_measured_sample_2_0][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_measured_sample_2_0'][0] ?? '' ?>">
                </td>
                    <div style="visibility: collapse">1</div>
                <td class="text-center align-middle" rowspan="4">
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_residue][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_residue'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_partial_residue_2_0][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_partial_residue_2_0'][0] ?? '' ?>">
                </td>
                <td>
                    <div>С сеткой 2.0 мм</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][dust_particle_content_2_0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['dust_particle_content_2_0'] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_2_0][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_2_0'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_2_0][1]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_2_0'][1] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_2_0][2]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_2_0'][2] ?? '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <div style="visibility: collapse">1</div>
                    С сеткой 0.125 мм
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_measured_sample_0_125][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_measured_sample_0_125'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_partial_residue_0_125][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_partial_residue_0_125'][0] ?? '' ?>">
                </td>
                <td>
                    <div>С сеткой 0.125 мм</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][dust_particle_content_0_125]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['dust_particle_content_0_125'] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_125][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_125'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_125][1]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_125'][1] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_125][2]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_125'][2] ?? '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <div style="visibility: collapse">1</div>
                    С сеткой 0.063 мм
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_measured_sample_0_063][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_measured_sample_0_063'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_partial_residue_0_063][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_partial_residue_0_063'][0] ?? '' ?>">
                </td>
                <td>
                    <div>С сеткой 0.063 мм</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][dust_particle_content_0_063]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['dust_particle_content_0_063'] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_063][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_063'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_063][1]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_063'][1] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_063][2]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_063'][2] ?? '' ?>">
                </td>
            </tr>
            <tr>
                <td>
                    <div style="visibility: collapse">1</div>
                    С сеткой <
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_measured_sample_0_low][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_measured_sample_0_low'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][m_partial_residue_0_low][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['m_partial_residue_0_low'][0] ?? '' ?>">
                </td>
                <td>
                    <div>С сеткой <</div>
                    <input class="form-control" readonly
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][dust_particle_content_0_low]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['dust_particle_content_0_low'] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_low][0]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_low'][0] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_low][1]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_063'][1] ?? '' ?>">
                </td>
                <td>
                    <div style="visibility: collapse">1</div>
                    <input class="form-control"
                           type="number" step="any"
                           name="form_data[<?= $this->data['ugtp_id'] ?>][grain_composition_32761][norm_0_low][2]"
                           value="<?= $this->data['measuring']['grain_composition_32761']['norm_0_063'][2] ?? '' ?>">
                </td>
            </tr>
            </tdoby>
    </table>

    <div>
        <button type="button" id="grain_32761" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
