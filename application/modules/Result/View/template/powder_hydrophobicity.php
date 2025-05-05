<?php
$arrLooseness = [
    0 => "Рыхлый",
    1 => "Не рыхлый"
];

$arrFlowability = [
    0 => "Сыпучий",
    1 => "Не сыпучий"
];

$arrAdmixture = [
    0 => "Не содержит загрязняющих примесей",
    1 => "Содержит загрязняющие примеси"
];

$arrHydrophobicity = [
    0 => "Гидрофобный",
    1 => "Не гидрофобный"
];
?>
<style>
    select {
        display: block;
        width: 100%;
        max-width: 500px;
        height: 40px;
        padding: 8px 10px 8px 10px;
        border: 1px solid #ccc;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        background: #f9f9f9;
        -moz-appearance: none;
        -webkit-appearance: none;
        outline: none;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-transition: border-color ease-in-out 0.5s;
        -moz-transition: border-color ease-in-out 0.5s;
        transition: border-color ease-in-out 0.5s;
    }
</style>
<div class="powder_hydrophobicity_wrapper">
        <em class="info d-block mb-4">
            <strong>Гидрофобность</strong>
        </em>

        <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

        <div class="mb-3">
            <label>Определение гидрофобности</label>
            <div class="mb-3">
                <select name="form_data[<?= $this->data['ugtp_id'] ?>][hydrophobicity_32761][looseness]">
                    <option disabled="true" <?= empty($this->data['measuring']['hydrophobicity_32761']['looseness']) ? "selected": "" ?>>Выберите оценку рыхлости</option>
                    <?php foreach($arrLooseness as $looseness): ?>
                        <option value="<?= $looseness ?>" <?= ($this->data['measuring']['hydrophobicity_32761']['looseness'] == $looseness) ? "selected": "" ?>>
                            <?= $looseness ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <select name="form_data[<?= $this->data['ugtp_id'] ?>][hydrophobicity_32761][flowability]">
                    <option disabled="true" <?= empty($this->data['measuring']['hydrophobicity_32761']['flowability']) ? "selected": "" ?>>Выберите оценку сыпучести</option>
                    <?php foreach($arrFlowability as $flowability): ?>
                        <option value="<?= $flowability ?>" <?= ($this->data['measuring']['hydrophobicity_32761']['flowability'] == $flowability) ? "selected": "" ?>>
                            <?= $flowability ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3" style="display: flex; flex-flow: column;">
                <select name="form_data[<?= $this->data['ugtp_id'] ?>][hydrophobicity_32761][admixture]">
                    <option disabled="true" <?= empty($this->data['measuring']['hydrophobicity_32761']['admixture']) ? "selected": "" ?>>Выберите содержание примесей</option>
                    <?php foreach($arrAdmixture as $admixture): ?>
                        <option value="<?= $admixture ?>" <?= ($this->data['measuring']['hydrophobicity_32761']['admixture'] == $admixture) ? "selected": "" ?>>
                            <?= $admixture ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="admixture"
                    <?= ($this->data['measuring']['hydrophobicity_32761']['admixture'] == "Содержит загрязняющие примеси") ? 'style="display: block;"' : 'style="display: none;"' ?>
                >
                    <label>...такие как</label>
                    <textarea class="form-control" placeholder="Введите, какие примеси содержатся"
                              type="text"
                              name="form_data[<?= $this->data['ugtp_id'] ?>][hydrophobicity_32761][admixture_description]"><?= $this->data['measuring']['hydrophobicity_32761']['admixture_description'] ?? '' ?></textarea>
                </div>
            </div>

            <div>
                <select name="form_data[<?= $this->data['ugtp_id'] ?>][hydrophobicity_32761][hydrophobicity]">
                    <option disabled="true" <?= empty($this->data['measuring']['hydrophobicity_32761']['hydrophobicity']) ? "selected": "" ?>>Выберите гидрофобность</option>
                    <?php foreach($arrHydrophobicity as $hydrophobicity): ?>
                        <option value="<?= $hydrophobicity ?>" <?= ($this->data['measuring']['hydrophobicity_32761']['hydrophobicity'] == $hydrophobicity) ? "selected": "" ?>>
                            <?= $hydrophobicity ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>

</div>