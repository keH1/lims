<!-- Определение предела прочности на растяжение при расколе ГОСТ 12801-98 п.16 -->
<div class="measurement-wrapper">
    <h3 class="mb-3"> Определение предела прочности на растяжение при расколе ГОСТ 12801-98</h3><br>
	<b><?=$this->data['probe']['cipher']?></b>
    <div class="gost_12801_tensileStrengthForm">
        <table id="form-f-4" class="table table-bordered numerable">
            <thead>
            <tr role="row">
                <th>Номер определения</th>
                <th>Р — разрушающая нагрузка, Н</th>
                <th>h — высота образца, см</th>
                <th>d — диаметр образца, см</th>
                <th>Rp - предел прочности на растяжение при расколе, МПа</th>
                <th>Rp<sub>ср</sub> - предел прочности на растяжение при расколе, МПа</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0, $k = 1; $i < 3; $i++, $k++): ?>
                <tr>
                    <td class="num_table"><?=$this->data['probe']['cipher_number']?>.<?=$k?></td>
                    <td>
                        <input type='number' step="any"
                               class="P-<?=$k?> form-control list-input change-trigger-tsf" name="form_data[<?=$this->data['ugtp_id']?>][p][]"
                               value="<?= $this->data['measuring']['p'][$i] ?? '' ?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="h-<?=$k?> form-control list-input change-trigger-tsf" name="form_data[<?=$this->data['ugtp_id']?>][h][]"
                               value="<?= $this->data['measuring']['h'][$i] ?? '' ?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="d-<?=$k?> form-control list-input change-trigger-tsf"
                               name="form_data[<?=$this->data['ugtp_id']?>][d][]"
                               value="<?= $this->data['measuring']['d'][$i] ?? '' ?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="r-<?=$k?> form-control list-input bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][r][]"
                               value="<?=$this->data['measuring']['r'][$i] ?? ''?>" readonly>
                    </td>
                    <?php if ($i === 0): ?>
                        <td rowspan="3" class="align-middle">
                            <input type='number' step="any"
                                   class="form-control list-input bg-light-secondary r-avr" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
                                   value="<?=$this->data['measuring']['result_value'] ?? ''?>" readonly>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <div class="row">
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
