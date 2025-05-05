<?php
$arrType = [
    0 => "Выдержала (отсутствие распада Эмульсии на воду и битумное вяжущее)",
    1 => "Не выдержала (наличие распада Эмульсии на воду и битумное вяжущее)"
];
?>

<div class="wrapper-stability-during">
    <em class="info d-block mb-4">
        <strong>Устойчивость при транспортировании ГОСТ P 58952.11</strong>
    </em>

    <div class="mb-3">
        <label>Устойчивость при транспортировке</label>
        <select id="stability" name="form_data[<?= $this->data['ugtp_id'] ?>][form][transport_stability][stability_select]" style="max-width: 580px;">
            <option disabled="true" <?= empty($this->data['measuring']['form']['transport_stability']['stability_select']) ? "selected": "" ?>>Укажите результат</option>
            <?php foreach($arrType as $keyType => $type): ?>
                <option value="<?= ($keyType + 1) ?>" <?= ($this->data['measuring']['form']['transport_stability']['stability_select'] == ($keyType + 1)) ? "selected": "" ?>>
                    <?= $type ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input id="stability_val" type="hidden"
               name="form_data[<?= $this->data['ugtp_id'] ?>][form][transport_stability][stability]"
               value="<?= $this->data['measuring']['form']['transport_stability']['stability'] ?>"
        >
    </div>

    <div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>