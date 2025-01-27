<!-- Определение характеристик сдвигоустойчивости ГОСТ 12801-98 п.18 -->
<div class="measurement-wrapper">
    <h3 class="mb-3">Определение характеристик сдвигоустойчивости ГОСТ 12801-98</h3><br>
	<b><?=$this->data['probe']['cipher']?></b>
    <div class="gost_12801_shearResistanceForm">
        <table id="form-f-4" class="table table-bordered numerable caption-top">
            <caption>Испытание на одноосное сжатие</caption>
            <thead>
            <tr role="row">
                <th>Р — Разрушающая нагрузка, кН</th>
                <th>l — предельная деформация, мм.</th>
                <th>F — первоначальная площадь поперечного сечения образца, см2</th>
                <th>Работа Ас, Дж</th>
                <th>Аc<sub>ср</sub> - работа, Дж</th>
                <th>Rc — предел прочности при одноосном сжатии</th>
                <th>Rc<sub>ср</sub> — предел прочности при одноосном сжатии</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0, $k = 1; $i < 3; $i++, $k++): ?>
            <tr>
                <td>
                    <input type='number' step="any"
                           class="pc-<?=$k?> form-control list-input change-trigger-srf" name="form_data[<?=$this->data['ugtp_id']?>][pc][]"
                           value="<?= $this->data['measuring']['pc'][$i] ?? '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="lc-<?=$k?> form-control list-input change-trigger-srf" name="form_data[<?=$this->data['ugtp_id']?>][lc][]"
                           value="<?= $this->data['measuring']['lc'][$i] ?? '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="fc-<?=$k?> form-control list-input change-trigger-srf" name="form_data[<?=$this->data['ugtp_id']?>][fc][]"
                           value="<?= $this->data['measuring']['fc'][$i] ?? '' ?>">
                </td>
                <td>
                    <input type='number' step="any"
                           class="ac-<?=$k?> form-control list-input change-trigger-srf-res" name="form_data[<?=$this->data['ugtp_id']?>][ac][]"
                           value="<?= $this->data['measuring']['ac'][$i] ?? '' ?>">
                </td>
                <?php if ($i === 0): ?>
                    <td rowspan="3" class="align-middle">
                        <input type='number' step="any"
                               class="form-control list-input bg-light-secondary ac-avr" name="form_data[<?=$this->data['ugtp_id']?>][ac_avr]"
                               value="<?=$this->data['measuring']['ac_avr'] ?? ''?>" readonly>
                    </td>
                <?php endif; ?>
                <td>
                    <input type='number' step="any"
                           class="rc-<?=$k?> form-control list-input" name="form_data[<?=$this->data['ugtp_id']?>][rc][]"
                           value="<?=$this->data['measuring']['rc'][$i] ?? ''?>">
                </td>
                <?php if ($i === 0): ?>
                    <td rowspan="3" class="align-middle">
                        <input type='number' step="any"
                               class="form-control list-input bg-light-secondary rc-avr" name="form_data[<?=$this->data['ugtp_id']?>][rc_avr]"
                               value="<?=$this->data['measuring']['rc_avr'] ?? ''?>" readonly>
                    </td>
                <?php endif; ?>
            </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <table id="form-f-4" class="table table-bordered numerable caption-top">
            <caption>Испытание по схеме Маршалла</caption>
            <thead>
            <tr role="row">
                <th>Р — Разрушающая нагрузка, кН</th>
                <th>l — предельная деформация, мм.</th>
                <th>Работа Аm, Дж</th>
                <th>Аm<sub>ср</sub> - работа, Дж</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($im = 0, $km = 1; $im < 3; $im++, $km++): ?>
                <tr>
                    <td>
                        <input type='number' step="any"
                               class="pm-<?=$km?> form-control list-input change-trigger-srf-1" name="form_data[<?=$this->data['ugtp_id']?>][pm][]"
                               value="<?= $this->data['measuring']['pm'][$im] ?? '' ?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="lm-<?=$km?> form-control list-input change-trigger-srf-1" name="form_data[<?=$this->data['ugtp_id']?>][lm][]"
                               value="<?= $this->data['measuring']['lm'][$im] ?? '' ?>">
                    </td>
                    <td>
                        <input type='number' step="any"
                               class="am-<?=$km?> form-control list-input change-trigger-srf-1-res" name="form_data[<?=$this->data['ugtp_id']?>][am][]"
                               value="<?= $this->data['measuring']['am'][$im] ?? '' ?>">
                    </td>
                    <?php if ($im === 0): ?>
                        <td rowspan="3" class="align-middle">
                            <input type='number' step="any"
                                   class="form-control list-input bg-light-secondary am-avr" name="form_data[<?=$this->data['ugtp_id']?>][am_avr]"
                                   value="<?=$this->data['measuring']['am_avr'] ?? ''?>" readonly>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endfor; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="form-group col">
                <label for="tg">Коэффициент внутреннего трения</label>
                <input type='number' step="any" class="form-control bg-light-secondary" id="tg"
                       name="form_data[<?=$this->data['ugtp_id']?>][tg]"
                       value="<?= $this->data['measuring']['tg'] ?>" readonly>
            </div>
        </div>

        <div class="row">
            <div class="form-group col">
                <label for="c">Лабораторный показатель сцепления при сдвиге</label>
                <input type='number' step="any" class="form-control bg-light-secondary" id="c"
                       name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
                       value="<?= $this->data['measuring']['result_value'] ?>" readonly>
            </div>
        </div>

        <div class="row">
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
