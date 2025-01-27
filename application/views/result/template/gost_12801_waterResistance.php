<div class="measurement-wrapper">
    <h3 class="mb-3">Определение водостойкости ГОСТ 12801-98</h3><br>
	<b><?=$this->data['probe']['cipher']?></b>
    <div class="water-resistance">
        <table class="table-Rw table table-bordered numerable">
            <tbody>
            <tr>
                <td colspan="5"><p>R<sup>в</sup><sub>сж</sub> - предел прочности при сжатии при температуре
                        (20±2) °С
                        водонасыщенных в вакууме образцов, МПа;
                    </p></td>
            </tr>
            <tr>
            <tr>
                <th>№</th>
                <th>P - разрушающая нагрузка, Н;</th>
                <th>F - первоначальная площадь поперечного сечения образца, см;</th>
                <th>R<sub>сж</sub> Предел прочности при сжатии, МПа</th>
            </tr>

            <?php for ($i = 0, $k = 1; $i < 3; $i++, $k++): ?>
                <tr>
                    <td class="num_table"><?=$this->data['probe']['cipher_number']?>.<?=$k?></td>
                    <td>
                        <input type='number' step="any"
                               class="blRw_<?=$k?> form-control list-input change-trigger-wrs" name="form_data[<?=$this->data['ugtp_id']?>][form][PRw][]"
                               value="<?=$this->data['measuring']['form']['PRw'][$i] ?? ''?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="sRw_<?=$k?> form-control list-input change-trigger-wrs" name="form_data[<?=$this->data['ugtp_id']?>][form][SRw][]"
                               value="<?=$this->data['measuring']['form']['SRw'][$i] ?? ''?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="StrRw_<?=$k?> form-control list-input change-trigger-wrs-res"
                               name="form_data[<?=$this->data['ugtp_id']?>][form][StrRw][]"
                               value="<?=$this->data['measuring']['form']['StrRw'][$i] ?? ''?>">
                    </td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
        <table class="table-Raw table table-bordered numerable">
            <tbody>
            <tr>
                <td colspan="5"><p>R<sup>20</sup><sub>сж</sub> - предел прочности при сжатии при температуре (20±2) °С
                        образцов до водонасыщения, МПа;
                    </p></td>
            </tr>
            <tr>
            <tr>
                <th>№</th>
                <th>P - разрушающая нагрузка, Н;</th>
                <th>F - первоначальная площадь поперечного сечения образца, см;</th>
                <th>R<sub>сж</sub> Предел прочности при сжатии, МПа</th>
            </tr>
            <?php for ($i = 0, $k = 1; $i < 3; $i++, $k++): ?>
                <tr>
                    <td class="num_table"><?=$k + 3?></td>
                    <td>
                        <input type='number' step="any"
                               class="blRaw_<?=$k?> form-control list-input change-trigger-wrs" name="form_data[<?=$this->data['ugtp_id']?>][form][PRaw][]"
                               value="<?= empty($this->data['measuring']['form']['PRaw'][$i]) ? $this->data['measuring_property']['P20'][$i] ?? '' : $this->data['measuring']['form']['PRaw'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="sRaw_<?=$k?> form-control list-input change-trigger-wrs" name="form_data[<?=$this->data['ugtp_id']?>][form][SRaw][]"
                               value="<?= empty($this->data['measuring']['form']['SRaw'][$i]) ? $this->data['measuring_property']['S20'][$i] ?? '' : $this->data['measuring']['form']['SRaw'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="StrRaw_<?=$k?> form-control list-input change-trigger-wrs-res"
                               name="form_data[<?=$this->data['ugtp_id']?>][form][StrRaw][]"
                               value="<?= empty($this->data['measuring']['form']['StrRaw'][$i]) ? $this->data['measuring_property']['Str20'][$i] ?? '' : $this->data['measuring']['form']['StrRaw'][$i]?>">
                    </td>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>
        <table class="table table-bordered numerable wr">
            <thead>
            <tr role="row">
                <th>R<sup>в</sup><sub>сж</sub> - предел прочности при сжатии при температуре (20±2) °С
                    водонасыщенных в вакууме образцов, МПа;
                </th>
                <th>R<sup>20</sup><sub>сж</sub> - предел прочности при сжатии при температуре (20±2) °С
                    образцов до водонасыщения, МПа;
                </th>
                <th>К<sub>в</sub> Водостойкость</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input readonly type='number' step="any"
                           class="form-control list-input Average_StrengthRw" name="form_data[<?=$this->data['ugtp_id']?>][form][Average_StrengthRw]"
                           value="<?=$this->data['measuring']['form']['Average_StrengthRw'] ?? ''?>"></td>
                <td><input readonly type='number' step="any"
                           class="form-control list-input Average_StrengthRaw" name="form_data[<?=$this->data['ugtp_id']?>][form][Average_StrengthRaw]"
                           value="<?=$this->data['measuring']['form']['Average_StrengthRaw'] ?? ''?>"></td>
                <td><input readonly type='number' step="any"
                           class="form-control list-input water_resistance" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
                           value="<?=$this->data['measuring']['result_value'] ?? ''?>"></td>
            </tr>
            </tbody>
        </table>

        <div class="row mb-3">
<!--            <div class="col flex-grow-0">-->
<!--                <button type="button" class="calculate btn btn-primary"-->
<!--                        name="calculate">Рассчитать-->
<!--                </button>-->
<!--            </div>-->

            <div class="col flex-grow-0">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
	<div class="line-dashed-small"></div>
</div>

