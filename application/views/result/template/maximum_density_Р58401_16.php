<!-- Асфальтобетон ГОСТ Р 58401.16 Максимальная плотность -->
<div class="measurement-wrapper" id="formBWrapper">
    <h3 class="mb-3">Максимальная плотность асфальтобетонной смеси, г/см<sup>3</sup></h3>

    <div class="line-dashed"></div>

    <div id="formB" class="mb-3">
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info">
                <th></th>
                <th scope="col">Масса высушенной асфальтобетонной смеси на воздухе, г</th>
                <th scope="col">Масса чаши с асфальтобетонной смесью в воде, г</th>
                <th scope="col">Масса чаши в воде, г</th>
                <th scope="col">Максимальная плотность асфальтобетонной смеси, г/см<sup>3</sup></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Определение 1</td>
                <td>
                    <input type="number" id="formBmassA1" class="form-control col-sm-10" name="form_data[<?=$this->data['ugtp_id']?>][form][aaa]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['aaa'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="formBmassB1" class="form-control col-sm-10" name="form_data[<?=$this->data['ugtp_id']?>][form][bbb]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['bbb'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="formBmassC1" class="form-control col-sm-10" name="form_data[<?=$this->data['ugtp_id']?>][form][ccc]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['ccc'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="formBdensity1" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][form][ddd]" value="<?=$this->data['measuring']['form']['ddd'] ?? ''?>" readonly>
                </td>
            </tr>
            <tr>
                <td>Определение 2</td>
                <td>
                    <input type="number" id="formBmassA2" class="form-control col-sm-10" name="form_data[<?=$this->data['ugtp_id']?>][form][eee]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['eee'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="formBmassB2" class="form-control col-sm-10" name="form_data[<?=$this->data['ugtp_id']?>][form][fff]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['fff'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="formBmassC2" class="form-control col-sm-10" name="form_data[<?=$this->data['ugtp_id']?>][form][ggg]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['ggg'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="formBdensity2" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][form][hhh]" value="<?=$this->data['measuring']['form']['hhh'] ?? ''?>" readonly>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="form-group row mb-3">
            <div class="col-6">
                <label for="average">Среднее арифметическое</label>
                <input type="number" id="formBaverage" class="form-control fineness-module" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" step="0.001" value="<?=$this->data['measuring']['result_value'] ?? ''?>">
            </div>
            <div class="col-6">
                <label for="average">Разница результатов (до 0,020 г/см<sup>3</sup>)</label>
                <input type="number" id="formBdifference" class="form-control fineness-module" name="form_data[<?=$this->data['ugtp_id']?>][form][jjj]" value="<?=$this->data['measuring']['form']['jjj'] ?? ''?>" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="formBcalculate" class="btn btn-primary" name="calculate">Расчитать</button>
            </div>
            <div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
            </div>
        </div>
    </div>

</div>
