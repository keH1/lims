<!-- Асфальтобетон ГОСТ Р 58401.10 Объемная плотность -->
<div class="measurement-wrapper" id="formE">
    <h3 class="mb-3">Объемная плотность асфальтобетонной смеси, г/см<sup>3</sup></h3>

    <div class="line-dashed"></div>

    <table class="table table-fixed list_data mb-3">
        <thead>
        <tr class="table text-center align-middle">
            <th>Определение</th>
            <th scope="col">Масса сухого образца на воздухе, г</th>
            <th scope="col">Масса образца на воздухе после выдерживания его в воде в течение (4±1) мин, г</th>
            <th scope="col">Масса образца в воде после выдерживания его в воде в течение (4±1) мин, г</th>
            <th scope="col">Объемная плотность асфальтобетонной смеси, г/см<sup>3</sup></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-center align-middle">1</td>
            <td>
                <input type="number" id="formEmassA1" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][aa]" min="0" step="0.001" value="<?=$this->data['measuring']['aa'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEmassB1" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][bb]" min="0" step="0.001" value="<?=$this->data['measuring']['bb'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEmassC1" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][cc]" step="0.001" value="<?=$this->data['measuring']['cc'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEdensity1" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][dd]" step="0.001" value="<?=$this->data['measuring']['dd'] ?? ''?>" readonly>
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">2</td>
            <td>
                <input type="number" id="formEmassA2" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][ee]" min="0" step="0.001" value="<?=$this->data['measuring']['ee'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEmassB2" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][ff]" min="0" step="0.001" value="<?=$this->data['measuring']['ff'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEmassC2" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][gg]" step="0.001" value="<?=$this->data['measuring']['gg'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEdensity2" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][hh]" step="0.001" value="<?=$this->data['measuring']['hh'] ?? ''?>" readonly>
            </td>
        </tr>
        <tr>
            <td class="text-center align-middle">3</td>
            <td>
                <input type="number" id="formEmassA3" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][ii]" min="0" step="0.001" value="<?=$this->data['measuring']['ii'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEmassB3" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][jj]" min="0" step="0.001" value="<?=$this->data['measuring']['jj'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEmassC3" class="form-control col-sm-10 change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][kk]" step="0.001" value="<?=$this->data['measuring']['kk'] ?? ''?>">
            </td>
            <td>
                <input type="number" id="formEdensity3" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][ll]" step="0.001" value="<?=$this->data['measuring']['ll'] ?? ''?>" readonly>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="row mb-3">
        <div class="col">
            <label for="average">Среднее арифметическое, г/см<sup>3</sup></label>
            <input type="number" id="formEaverage" class="form-control fineness-module change-trigger-bd" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" step="0.001" value="<?=$this->data['measuring']['result_value'] ?? ''?>">
        </div>
        <div class="col">
            <label for="average">Разница результатов (до 0,010 г/см<sup>3</sup>)</label>
            <input type="number" id="formEdifference" class="form-control fineness-module" name="form_data[<?=$this->data['ugtp_id']?>][nn]" step="0.001" value="<?=$this->data['measuring']['nn'] ?? ''?>" readonly>
        </div>
    </div>


    <button type="button" id="formEcalculate" class="btn btn-primary me-2" name="calculate">Расчитать</button>

    <button type="submit" class="btn btn-primary save-average">Сохранить</button>

</div>
