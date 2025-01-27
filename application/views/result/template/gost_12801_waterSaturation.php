<div class="measurement-wrapper">
    <h3 class="mb-3">Определение водонасыщения ГОСТ 12801-98</h3><br>
	<b><?=$this->data['probe']['cipher']?></b>
    <div class="waterSaturation-Form">
        <table id="form-f-4" class="table table-bordered numerable">
            <thead>
            <tr role="row">
                <th>Номер определения</th>
                <th> &#103; - масса образца, взвешенного на воздухе, г</th>
                <th>&#103;<sub>1</sub> - масса образца, взвешенного в воде, г</th>
                <th>&#103;<sub>2</sub> - масса образца, выдержанного в течение 30 мин в воде и вторично
                    взвешенного на воздухе, г
                </th>
                <th>&#103;<sub>5</sub> - масса насыщенного водой образца, взвешенного на воздухе, г</th>
                <th>W - водонасыщение, %</th>
                <th>W<sub>ср</sub> - водонасыщение, %</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0, $k = 1; $i < 3; $i++, $k++): ?>
                <tr>
                    <td class="num_table"><?=$this->data['probe']['cipher_number']?>.<?=$k?></td>
                    <td>
                        <input type='number' step="any"
                               class="m_sample_air_weighted_<?=$k?> form-control list-input change-trigger-ws" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                               value="<?= empty($this->data['measuring']['form']['g'][$i]) ? $this->data['measuring_property']['g'][$i] ?? '' : $this->data['measuring']['form']['g'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="m_sample_water_weighted_<?=$k?> form-control list-input change-trigger-ws" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                               value="<?= empty($this->data['measuring']['form']['g1'][$i]) ? $this->data['measuring_property']['g1'][$i] ?? '' : $this->data['measuring']['form']['g1'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="m_sample_soaked_in_water_air_weighted_<?=$k?> form-control list-input change-trigger-ws"
                               name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                               value="<?= empty($this->data['measuring']['form']['g2'][$i]) ? $this->data['measuring_property']['g2'][$i] ?? '' : $this->data['measuring']['form']['g2'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="m_sample_water_saturation_air_weighted_<?=$k?> form-control list-input change-trigger-ws"
                               name="form_data[<?=$this->data['ugtp_id']?>][form][g5][]"
                               value="<?=$this->data['measuring']['form']['g5'][$i] ?? ''?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="sample_water_saturation_<?=$k?> form-control list-input" name="form_data[<?=$this->data['ugtp_id']?>][form][sw][]"
                               value="<?=$this->data['measuring']['form']['sw'][$i] ?? ''?>" readonly>
                    </td>
                    <?php if ($i === 0): ?>
                        <td rowspan="3" class="align-middle">
                            <input type='number' step="any"
                                   class="form-control list-input water_saturation" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
                                   value="<?=$this->data['measuring']['result_value'] ?? ''?>" readonly>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" class="calculate btn btn-primary"
                        name="calculate">Рассчитать
                </button>
            </div>

            <div class="col flex-grow-0">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
	<div class="line-dashed-small"></div>
</div>
