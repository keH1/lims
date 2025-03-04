<!-- Асфальтобетон ГОСТ Р 58401.23 Стекание вяжущего -->
<div class="measurement-wrapper" id="runoffWrapper">
    <h3 class="mb-3">Стекание вяжущего, %</h3>

    <div id="runoff_formSMA " class="mb-3" style="display: none">
        <em class="info d-block mb-4">
            SMA Стекание вяжущего, %
        </em>
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info">
                <th scope="">Испытания</th>
                <th scope="col">Масса сетчатой корзины со смесью до начала испытания, г</th>
                <th scope="col">Масса пустой сетчатой корзины до начала испытания, г</th>
                <th scope="col">Масса поддона с вытекшим вяжущим, г</th>
                <th scope="col">Первоначальная масса поддона, г</th>
                <th scope="col">Стекание вяжущего В, %</th>
            </tr>
            </thead>
            <tbody>
            <tr class="definition">
                <td></td>
                <td>
                    <input type="number" class="form-control mA change-trigger-rnf" name="form_data[<?=$this->data['ugtp_id']?>][form][aaaaa]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['aaaaa'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mB change-trigger-rnf" name="form_data[<?=$this->data['ugtp_id']?>][form][bbbbb]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['bbbbb'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mC change-trigger-rnf" name="form_data[<?=$this->data['ugtp_id']?>][form][ccccc]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['ccccc'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mD change-trigger-rnf" name="form_data[<?=$this->data['ugtp_id']?>][form][ddddd]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['ddddd'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control result" name="form_data[<?=$this->data['ugtp_id']?>][form][eeeee]" step="0.001" value="<?=$this->data['measuring']['form']['eeeee'] ?? ''?>" readonly>
                </td>
            </tr>
            <tr class="definition">
                <td></td>
                <td>
                    <input type="number" class="form-control mA change-trigger-rnf-1" name="form_data[<?=$this->data['ugtp_id']?>][form][fffff]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['fffff'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mB change-trigger-rnf-1" name="form_data[<?=$this->data['ugtp_id']?>][form][ggggg]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['ggggg'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mC change-trigger-rnf-1" name="form_data[<?=$this->data['ugtp_id']?>][form][hhhhh]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['hhhhh'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mD change-trigger-rnf-1" name="form_data[<?=$this->data['ugtp_id']?>][form][iiiii]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['iiiii'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control result" name="form_data[<?=$this->data['ugtp_id']?>][form][jjjjj]" step="0.001" value="<?=$this->data['measuring']['form']['jjjjj'] ?? ''?>" readonly>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="form-group row mb-3">
            <div class="col">
                <label for="aver">Среднее арифметическое, %</label>
                <input type="number" id="averSMA" class="form-control fineness-module change-trigger-rnf-1" name="form_data[<?=$this->data['ugtp_id']?>][form][kkkkk]" step="0.001" value="<?=$this->data['measuring']['form']['kkkkk'] ?? ''?>" >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="calculateSMA" class="btn btn-primary" name="calculate">Расчитать</button>
            </div>
        </div>
    </div>

    <div id="runoff_formShMA" class="mb-3">
        <em class="info d-block mb-1">
            ЩМА Стекание вяжущего, %
        </em>
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info text-center align-middle">
                <th scope="">Испытания</th>
                <th scope="col">Масса пустого стакана, г</th>
                <th scope="col">Масса стакана со смесью, г</th>
                <th scope="col">Масса стакана после удаления смеси, г</th>
                <th scope="col">Стекание вяжущего В, %</th>
            </tr>
            </thead>
            <tbody>
            <tr class="definition">
                <td class="text-center align-middle">1</td>
                <td>
                    <input type="number" class="form-control mA change-trigger-rnf-2" name="form_data[<?=$this->data['ugtp_id']?>][form][lllll]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['lllll'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mB change-trigger-rnf-2" name="form_data[<?=$this->data['ugtp_id']?>][form][mmmmm]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['mmmmm'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mC change-trigger-rnf-2" name="form_data[<?=$this->data['ugtp_id']?>][form][nnnnn]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['nnnnn'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control result" name="form_data[<?=$this->data['ugtp_id']?>][form][ooooo]" value="<?=$this->data['measuring']['form']['ooooo'] ?? ''?>" readonly>
                </td>
            </tr>
            <tr class="definition">
                <td class="text-center align-middle">2</td>
                <td>
                    <input type="number" class="form-control mA change-trigger-rnf-2" name="form_data[<?=$this->data['ugtp_id']?>][form][ppppp]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['ppppp'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mB change-trigger-rnf-2" name="form_data[<?=$this->data['ugtp_id']?>][form][qqqqq]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['qqqqq'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control mC change-trigger-rnf-2" name="form_data[<?=$this->data['ugtp_id']?>][form][rrrrr]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['rrrrr'] ?? ''?>">
                </td>
                <td>
                    <input type="number" class="form-control result" name="form_data[<?=$this->data['ugtp_id']?>][form][sssss]" value="<?=$this->data['measuring']['form']['sssss'] ?? ''?>" readonly>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="form-group row mb-3">
            <div class="col">
                <label for="aver">Среднее арифметическое, %</label>
                <input type="number" id="averShMA" class="form-control fineness-module" min="0" step="0.001" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" value="<?=$this->data['measuring']['result_value'] ?? ''?>" >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="calculateShMA" class="btn btn-primary" name="calculate">Расчитать</button>
            </div>
			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
			</div>
        </div>
    </div>

</div>
