<div class="wrapper-bitumen-maximum-tensile">
    <em class="info d-block mb-4">
        <strong>Максимальное усилие при растяжении ГОСТ 33138</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th></th>
            <th style="vertical-align: middle;">Максимальное усилие при растяжении, Н</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                0 °С
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][max_stretching][stretching_0]"
                       value="<?= $this->data['measuring']['form']['max_stretching']['stretching_0'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                25 °С
            </td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][max_stretching][stretching_25]"
                       value="<?= $this->data['measuring']['form']['max_stretching']['stretching_25'] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
