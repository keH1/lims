11111


<div class="measurement-wrapper" id="grainWrapper">
    <h3 class="mb-3">Зерновой состав общий</h3>
    <input type="hidden" name="type" value="grain">
    <input type="hidden" name="method_id" value="<?=$this->data['measuring_property']['method_id']?>">
    <div class="form-group col-sm-6">
        <label for="materialGroup">Выберите сита</label>
        <select class="form-select material-group" name="zern"
        <option value="0" selected>Выбрать</option>
        <?php foreach ($this->data['measuring_property']['sieve'] as $val): ?>
            <option value="<?= $val['ID'] ?>"
                <?= $this->data['measuring']['zern'] === "{$val['ID']}" ? 'selected' : '' ?>>
                <?= $val['NAME'] ?>
            </option>
        <?php endforeach; ?>
        </select>
    </div>
    <label for="initial_mass">Масса пробы, г</label>
    <input class="form-control initial_mass" type="number" name="initial_mass" value="<?=$this->data['measuring']['initial_mass']?>">

    <?php if (!empty($this->data['measuring'])):?>
        <table class="table list_data graincomposition">
            <thead>
            <tr>
                <th class="text-center">Размер сит, мм</th>
                <?php foreach ($this->data['measuring']['title'] as $k => $title):?>
                    <th class="text-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="<?=$k?>" id="checkbox_<?=$k?>"
                                   name="in_protocol[<?=$k?>]" <?=$this->data['measuring']['in_protocol'][$k] || $this->data['measuring']['in_protocol'][$k] == '0' ? 'checked' : ''?>>
                            <label class="form-check-label" for="checkbox_<?=$k?>">
                                <?=$title?>
                            </label>
                            <input type="hidden" name="title[<?=$k?>]" value="<?=$title?>">
                        </div>
                    </th>
                <?php endforeach;?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center">m<sub>i</sub></td>
                <?php foreach ($this->data['measuring']['m'] as $k => $m):?>
                    <td>
                        <input class="form-control calculate <?=$k == 0 ? 'first' : ''?>" data-col="<?=$k?>" type="number" step="any" name="m[<?=$k?>]" value="<?=$m?>">
                    </td>
                <?php endforeach;?>
            </tr>
            <tr>
                <td class="text-center">a<sub>i</sub></td>
                <?php foreach ($this->data['measuring']['a'] as $k => $a):?>
                    <td>
                        <input class="form-control <?=$k == 0 ? 'first' : ''?>" data-col="<?=$k?>" type="number" step="any" name="a[<?=$k?>]" value="<?=$a?>">
                    </td>
                <?php endforeach;?>
            </tr>
            <tr>
                <td class="text-center">П<sub>i</sub></td>
                <?php foreach ($this->data['measuring']['p'] as $k => $p):?>
                    <td>
                        <input class="form-control <?=$k == 0 ? 'first' : ''?>" data-col="<?=$k?>" type="number" step="any" name="p[<?=$k?>]" value="<?=$p?>">
                    </td>
                <?php endforeach;?>
            </tr>
            <tr>
                <td class="text-center">
                    <div class="form-check">
                        <label class="form-check-label" for="checkbox_<?= $k ?>">
                            <?= $title ?>
                        </label>
                        <input class="form-check-input" type="checkbox"
                               name="in_protocol[fp]" <?= $this->data['measuring']['in_protocol']['fp'] || $this->data['measuring']['in_protocol']['fp'] == '0' ? 'checked' : '' ?>>


                    </div>
                </td>
                <?php foreach ($this->data['measuring']['fp'] as $k => $fp):?>
                    <td>
                        <input class="form-control <?=$k == 0 ? 'first' : ''?>" data-col="<?=$k?>" type="number" step="0.01" name="fp[<?=$k?>]" value="<?=$fp?>">
                    </td>
                <?php endforeach;?>
            </tr>
            <tr>
                <td class="text-center">Требования</td>
                <?php foreach ($this->data['measuring']['norm'] as $k => $norm):?>
                    <td>
                        <input class="form-control" type="text" step="any" name="norm[<?=$k?>]" value="<?=$norm?>">
                    </td>
                <?php endforeach;?>
            </tr>
            </tbody>
        </table>
    <?php endif;?>
    <button type="submit" class="btn btn-primary save-average">Сохранить</button>
</div>
