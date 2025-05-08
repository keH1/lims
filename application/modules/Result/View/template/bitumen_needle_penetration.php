<div class="wrapper-bitumen-needle-penetration">
    <em class="info d-block mb-4">
        <strong>Глубина проникания иглы ГОСТ 33136</strong>
    </em>
    <span>Глубина проникания иглы 0,1 мм при 0°С</span>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Испытание</th>
            <th>Глубина проникания иглы 0,1 мм</th>
            <th>Среднее арифметическое</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth][0]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth'][0] ?? '' ?>">
            </td>
            <td class="text-center align-middle" rowspan="3">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth_average][0]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth_average'][0] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Определение 2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth][1]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Определение 3</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth][2]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth'][2] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <span>Глубина проникания иглы 0,1 мм при 25°С</span>
    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Испытание</th>
            <th>Глубина проникания иглы 0,1 мм</th>
            <th>Среднее арифметическое</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Определение 1</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth][3]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth'][3] ?? '' ?>">
            </td>
            <td class="text-center align-middle" rowspan="3">
                <input class="form-control" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth_average][1]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth_average'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Определение 2</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth][4]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth'][4] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>Определение 3</td>
            <td>
                <input class="form-control"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][needle_penetration_depth][penetration_depth][5]"
                       value="<?= $this->data['measuring']['form']['needle_penetration_depth']['penetration_depth'][5] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="penetrationDepth" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>