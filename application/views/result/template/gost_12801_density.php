<!--Средняя плотность-->
<div class="measurement-wrapper">
	<b>Определение средней плотности ГОСТ 12801-98</b><br>
	<b><?=$this->data['probe']['cipher']?></b>
    <div class="row">
        <div class="col-lg-10">
            <p>(3 параллельных испытания с наибольшим расхождением не более 0,03 г/см3; результат -
                среднее арифметическое значение с точностью до второго десятичного числа)</p>
        </div>
    </div>
    <div class="">
        <table id="form-f-4" class="table table-bordered numerable">
            <thead>
            <tr role="row">
                <th>Номер определения</th>
                <th>&#103; - масса образца, взвешенного на воздухе, г</th>
                <th>&#103;<sub>1</sub> - масса образца, взвешенного в воде, г</th>
                <th>&#103;<sub>2</sub> - масса образца, выдержанного в течение 30 мин в воде и вторично
                    взвешенного на воздухе, г
                </th>
                <th>Pн, г/см<sup>3</sup></th>
                <th>Pн<sub>ср</sub>, г/см<sup>3</sup></th>
                <th>Расхождение, г/см<sup>3</sup></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <tr>
                <td class="num_table"><?=$this->data['probe']['cipher_number']?>.1</td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_air_weighted_1 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                           value="<?= $this->data['measuring']['form']['g'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_water_weighted_1 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                           value="<?= $this->data['measuring']['form']['g1'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_soaked_in_water_air_weighted_1 change-trigger"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                           value="<?= $this->data['measuring']['form']['g2'][$i] ?: '' ?>">
                </td>
                <td>
                    <input readonly type='number'
                           class="form-control list-input sample_density_1" name="form_data[<?=$this->data['ugtp_id']?>][form][sd][]"
                           value="<?= $this->data['measuring']['form']['sd'][$i] ?: '' ?>">
                </td>
                <td rowspan="7" class="align-middle">
                    <input readonly type='number' step="any"
                           class="form-control list-input average_density" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
                           value="<?= $this->data['measuring']['result_value'] ?: '' ?>">
                </td>
                <td rowspan="7" class="align-middle">
                    <input readonly type='number' step="any"
                           class="form-control list-input difference" name="form_data[<?=$this->data['ugtp_id']?>][difference]"
                           value="<?= $this->data['measuring']['difference'] != '' ? $this->data['measuring']['difference'] : '' ?>">
                </td>
            </tr>
            <?php $i++; ?>
            <tr>
                <td class="num_table"><?=$this->data['probe']['cipher_number']?>.2</td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_air_weighted_2 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                           value="<?= $this->data['measuring']['form']['g'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_water_weighted_2 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                           value="<?= $this->data['measuring']['form']['g1'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_soaked_in_water_air_weighted_2 change-trigger"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                           value="<?= $this->data['measuring']['form']['g2'][$i] ?: '' ?>">
                </td>
                <td>
                    <input readonly type='number' step="any"
                           class="form-control list-input sample_density_2" name="form_data[<?=$this->data['ugtp_id']?>][form][sd][]"
                           value="<?= $this->data['measuring']['form']['sd'][$i] ?: '' ?>">
                </td>
            </tr>
            <?php $i++; ?>
            <tr>
                <td class="num_table"><?=$this->data['probe']['cipher_number']?>.3</td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_air_weighted_3 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                           value="<?= $this->data['measuring']['form']['g'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_water_weighted_3 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                           value="<?= $this->data['measuring']['form']['g1'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_soaked_in_water_air_weighted_3 change-trigger"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                           value="<?= $this->data['measuring']['form']['g2'][$i] ?: '' ?>">
                </td>
                <td>
                    <input readonly type='number' step="any"
                           class="form-control list-input sample_density_3" name="form_data[<?=$this->data['ugtp_id']?>][form][sd][]"
                           value="<?= $this->data['measuring']['form']['sd'][$i] ?: '' ?>">
                </td>
            </tr>
            <?php $i++; $strDNone = $this->data['measuring']['form']['g'][$i] != '' ? '' : 'd-none';?>
            <tr class="<?=$strDNone?>">
                <td colspan="5">Расхождение результатов трех параллельных испытаний превышает 0,03 г/см3. Необходимы дополнительные испытания.</td>
            </tr>
            <tr class="<?=$strDNone?> additional_tests">
                <td class="num_table"><?=$this->data['probe']['cipher_number']?>.4</td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_air_weighted_4 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                           value="<?= $this->data['measuring']['form']['g'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_water_weighted_4 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                           value="<?= $this->data['measuring']['form']['g1'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_soaked_in_water_air_weighted_4 change-trigger"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                           value="<?= $this->data['measuring']['form']['g2'][$i] ?: '' ?>">
                </td>
                <td>
                    <input readonly type='number' step="any"
                           class="form-control list-input sample_density_4" name="form_data[<?=$this->data['ugtp_id']?>][form][sd][]"
                           value="<?= $this->data['measuring']['form']['sd'][$i] ?: '' ?>">
                </td>
            </tr>
            <?php $i++; ?>
            <tr class="<?=$strDNone?> additional_tests">
                <td class="num_table"><?=$this->data['probe']['cipher_number']?>.5</td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_air_weighted_5 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                           value="<?= $this->data['measuring']['form']['g'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_water_weighted_5 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                           value="<?= $this->data['measuring']['form']['g1'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_soaked_in_water_air_weighted_5 change-trigger"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                           value="<?= $this->data['measuring']['form']['g2'][$i] ?: '' ?>">
                </td>
                <td>
                    <input readonly type='number' step="any"
                           class="form-control list-input sample_density_5 change-tr" name="form_data[<?=$this->data['ugtp_id']?>][form][sd][]"
                           value="<?= $this->data['measuring']['form']['sd'][$i] ?: '' ?>">
                </td>
            </tr>
            <?php $i++; ?>
            <tr class="<?=$strDNone?> additional_tests">
                <td class="num_table"><?=$this->data['probe']['cipher_number']?>.6</td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_air_weighted_6 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                           value="<?= $this->data['measuring']['form']['g'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_water_weighted_6 change-trigger" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                           value="<?= $this->data['measuring']['form']['g1'][$i] ?: '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="form-control list-input m_sample_soaked_in_water_air_weighted_6 change-trigger"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                           value="<?= $this->data['measuring']['form']['g2'][$i] ?: '' ?>">
                </td>
                <td>
                    <input readonly type='number' step="any"
                           class="form-control list-input sample_density_6" name="form_data[<?=$this->data['ugtp_id']?>][form][sd][]"
                           value="<?= $this->data['measuring']['form']['sd'][$i] ?: '' ?>">
                </td>
            </tr>
            </tbody>
        </table>

        <div class="line-dashed-small"></div>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="calculate-<?=$this->data['ugtp_id']?>" class="densityForm btn btn-primary" name="densityForm">Рассчитать</button>
            </div>

            <div class="col flex-grow-0">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>
