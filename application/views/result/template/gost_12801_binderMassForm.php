<!-- Определение массовой доли вяжущего ГОСТ 12801-98 п.23.3 -->
<div class="measurement-wrapper">
    <h3 class="mb-3">Определение массовой доли вяжущего ГОСТ 12801-98</h3>
	<br>
	<b><?=$this->data['probe']['cipher']?></b>
    <div class="gost_12801_binderMassForm">
        <div class="form-group row mb-3">
            <div class="col">
                <label for="bb-select-method">Дозировка вяжущего</label>
                <select name="form_data[<?=$this->data['ugtp_id']?>][binder_amount]" id="binderAmount">
                    <option value="100" <?=$this->data['measuring']['binder_amount'] == '100'? 'selected' : ''?>>100 %</option>
                    <option value="over_100" <?=$this->data['measuring']['binder_amount'] == 'over_100'? 'selected' : ''?>>Сверх 100 %</option>
                </select>
            </div>
        </div>

        <table id="form-f-4" class="table table-bordered numerable">
            <thead>
            <tr role="row">
                <th>Номер определения</th>
                <th>G — масса лотка, г</th>
                <th>G<sub>1</sub> - масса лотка с навеской смеси до выжигания, г</th>
                <th>G<sub>2</sub> - масса лотка с навеской смеси после выжигания, г</th>
                <th>q - массовая доля вяжущего, %</th>
                <th>q<sub>ср</sub> - массовая доля вяжущего, %</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0, $k = 1; $i < 2; $i++, $k++): ?>
                <tr>
                    <td class="num_table"><?=$this->data['probe']['cipher_number']?>.<?=$k?></td>
                    <td>
                        <input type='number' step="any"
                               class="g-<?=$k?> form-control list-input change-trigger-bmf" name="form_data[<?=$this->data['ugtp_id']?>][form][g][]"
                               value="<?= empty($this->data['measuring']['form']['g'][$i]) ? $this->data['measuring_property']['g'][$i] ?? '' : $this->data['measuring']['form']['g'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="g1-<?=$k?> form-control list-input change-trigger-bmf" name="form_data[<?=$this->data['ugtp_id']?>][form][g1][]"
                               value="<?= empty($this->data['measuring']['form']['g1'][$i]) ? $this->data['measuring_property']['g1'][$i] ?? '' : $this->data['measuring']['form']['g1'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="g2-<?=$k?> form-control list-input change-trigger-bmf"
                               name="form_data[<?=$this->data['ugtp_id']?>][form][g2][]"
                               value="<?= empty($this->data['measuring']['form']['g2'][$i]) ? $this->data['measuring_property']['g2'][$i] ?? '' : $this->data['measuring']['form']['g2'][$i]?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="q-<?=$k?> form-control list-input bg-light-secondary" name="form_data[<?=$this->data['ugtp_id']?>][form][q][]"
                               value="<?=$this->data['measuring']['form']['q'][$i] ?? ''?>" readonly>
                    </td>
                    <?php if ($i === 0): ?>
                        <td rowspan="3" class="align-middle">
                            <input type='number' step="any"
                                   class="form-control list-input bg-light-secondary q-avr" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
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
