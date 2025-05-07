<div class="wrapper-bitumen-change-in-sample-mass">
    <em class="info d-block mb-4">
        <strong>Изменение массы образца после старения 33140</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Контейнер</th>
            <th>Масса стеклянного контейнера, г</th>
            <th>Масса стеклянного контейнера с битумом до старения, г</th>
            <th>Масса стеклянного контейнера с битумом после старения, г</th>
            <th>Изменение массы образца в контейнере после старения, %</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>А</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_glass_container][0]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_glass_container'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_glass_container_with_bitumen_before_aging][0]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_glass_container_with_bitumen_before_aging'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_glass_container_with_bitumen_after_aging][0]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_glass_container_with_bitumen_after_aging'][0] ?? '' ?>">
            </td>
            <td class="text-center align-middle" rowspan="2">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_change_after_aging]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_change_after_aging'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Б</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_glass_container][1]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_glass_container'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_glass_container_with_bitumen_before_aging][1]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_glass_container_with_bitumen_before_aging'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][mass_change][mass_glass_container_with_bitumen_after_aging][1]"
                       value="<?= $this->data['measuring']['form']['mass_change']['mass_glass_container_with_bitumen_after_aging'][1] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="massChange" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
