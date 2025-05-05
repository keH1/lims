<div class="wrapper-cement-determination-of-compressive">
    <em class="info d-block mb-4">
        <strong>Определение прочности на сжатие</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Разрушающая нагрузка, Н</th>
            <th>Размер стороны квадратного сечения образца-балочки, мм</th>
            <th>Расстояние между осями опор, мм</th>
            <th>Прочность при изгибе, МПа</th>
            <th>Среднее арифметическое</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control breaking-load-0"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][breaking_load][0]"
                       value="<?= $this->data['measuring']['form']['compressive']['breaking_load'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control size-of-side-0"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][size_of_side][0]"
                       value="<?= $this->data['measuring']['form']['compressive']['size_of_side'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control between-support-0"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][between_support][0]"
                       value="<?= $this->data['measuring']['form']['compressive']['between_support'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control compressive-result-0" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][compressive_result][0]"
                       value="<?= $this->data['measuring']['form']['compressive']['compressive_result'][0] ?? '' ?>">
            </td>
            <td class="align-middle" rowspan="3">
                <input class="form-control result" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][result]"
                       value="<?= $this->data['measuring']['form']['compressive']['result'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-1"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][breaking_load][1]"
                       value="<?= $this->data['measuring']['form']['compressive']['breaking_load'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control size-of-side-1"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][size_of_side][1]"
                       value="<?= $this->data['measuring']['form']['compressive']['size_of_side'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control between-support-1"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][between_support][1]"
                       value="<?= $this->data['measuring']['form']['compressive']['between_support'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control compressive-result-1" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][compressive_result][1]"
                       value="<?= $this->data['measuring']['form']['compressive']['compressive_result'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-2"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][breaking_load][2]"
                       value="<?= $this->data['measuring']['form']['compressive']['breaking_load'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control size-of-side-2"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][size_of_side][2]"
                       value="<?= $this->data['measuring']['form']['compressive']['size_of_side'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control between-support-2"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][between_support][2]"
                       value="<?= $this->data['measuring']['form']['compressive']['between_support'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control compressive-result-2" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][compressive][compressive_result][2]"
                       value="<?= $this->data['measuring']['form']['compressive']['compressive_result'][2] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="determinationOfCementCompressive" class="btn btn-primary">Рассчитать</button>
    </div>
</div>

<div class="wrapper-cement-determination-of-bending">
    <em class="info d-block mb-4">
        <strong>Определение прочности на изгибе</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr>
            <th>Разрушающая нагрузка, Н</th>
            <th>Площадь рабочей поверхности нажимной пластинки, мм2</th>
            <th>Прочность на сжатие, МПа</th>
            <th>Среднее арифметическое</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input class="form-control breaking-load-0"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][breaking_load][0]"
                       value="<?= $this->data['measuring']['form']['bending']['breaking_load'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control working-surface-0"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][working_surface][0]"
                       value="<?= $this->data['measuring']['form']['bending']['working_surface'][0] ?? '' ?>">
            </td>
            <td>
                <input class="form-control bending-result-0" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][bending_result][0]"
                       value="<?= $this->data['measuring']['form']['bending']['bending_result'][0] ?? '' ?>">
            </td>
            <td class="align-middle" rowspan="6">
                <input class="form-control result" readonly
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][result]"
                       value="<?= $this->data['measuring']['form']['bending']['result'] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-1"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][breaking_load][1]"
                       value="<?= $this->data['measuring']['form']['bending']['breaking_load'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control working-surface-1"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][working_surface][1]"
                       value="<?= $this->data['measuring']['form']['bending']['working_surface'][1] ?? '' ?>">
            </td>
            <td>
                <input class="form-control bending-result-1"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][bending_result][1]"
                       value="<?= $this->data['measuring']['form']['bending']['bending_result'][1] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-2"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][breaking_load][2]"
                       value="<?= $this->data['measuring']['form']['bending']['breaking_load'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control working-surface-2"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][working_surface][2]"
                       value="<?= $this->data['measuring']['form']['bending']['working_surface'][2] ?? '' ?>">
            </td>
            <td>
                <input class="form-control bending-result-2"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][bending_result][2]"
                       value="<?= $this->data['measuring']['form']['bending']['bending_result'][2] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-3"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][breaking_load][3]"
                       value="<?= $this->data['measuring']['form']['bending']['breaking_load'][3] ?? '' ?>">
            </td>
            <td>
                <input class="form-control working-surface-3"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][working_surface][3]"
                       value="<?= $this->data['measuring']['form']['bending']['working_surface'][3] ?? '' ?>">
            </td>
            <td>
                <input class="form-control bending-result-3"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][bending_result][3]"
                       value="<?= $this->data['measuring']['form']['bending']['bending_result'][3] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-4"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][breaking_load][4]"
                       value="<?= $this->data['measuring']['form']['bending']['breaking_load'][4] ?? '' ?>">
            </td>
            <td>
                <input class="form-control working-surface-4"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][working_surface][4]"
                       value="<?= $this->data['measuring']['form']['bending']['working_surface'][4] ?? '' ?>">
            </td>
            <td>
                <input class="form-control bending-result-4"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][bending_result][4]"
                       value="<?= $this->data['measuring']['form']['bending']['bending_result'][4] ?? '' ?>">
            </td>
        </tr>
        <tr>
            <td>
                <input class="form-control breaking-load-5"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][breaking_load][5]"
                       value="<?= $this->data['measuring']['form']['bending']['breaking_load'][5] ?? '' ?>">
            </td>
            <td>
                <input class="form-control working-surface-5"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][working_surface][5]"
                       value="<?= $this->data['measuring']['form']['bending']['working_surface'][5] ?? '' ?>">
            </td>
            <td>
                <input class="form-control bending-result-5"
                       type="number" step="any"
                       name="form_data[<?= $this->data['ugtp_id'] ?>][form][bending][bending_result][5]"
                       value="<?= $this->data['measuring']['form']['bending']['bending_result'][5] ?? '' ?>">
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <button type="button" id="determinationOfCementBending" class="btn btn-primary">Рассчитать</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>
