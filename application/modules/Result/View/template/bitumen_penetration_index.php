<div class="wrapper-penetration-index">
    <em class="info d-block mb-4">
        <strong>Индекс пенетрации ГОСТ 33134</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Глубина проникания иглы при 25°С, 0.1 мм</th>
            <th>Температура размягчения битума, °С</th>
            <th>Коэффициент</th>
            <th>Индекс пенетрации</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][penetration_index_33134][penetration_depth_25]"
                       value="<?= $this->data['measuring']['form']['penetration_index_33134']['penetration_depth_25'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][penetration_index_33134][temperature_RaB]"
                       value="<?= $this->data['measuring']['form']['penetration_index_33134']['temperature_RaB'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][penetration_index_33134][coefficient]"
                       value="<?= $this->data['measuring']['form']['penetration_index_33134']['coefficient'] ?? '' ?>">
            </td>
            <td>
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][penetration_index_33134][penetration_index_value]"
                       value="<?= $this->data['measuring']['form']['penetration_index_33134']['penetration_index_value'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="penetrationIndex" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
