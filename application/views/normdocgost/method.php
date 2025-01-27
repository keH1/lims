<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/normDocGost/edit/<?=$this->data['form']['gost_id']?>" title="Вернуться к ГОСТу">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>

<form class="form-horizontal" method="post" action="<?=URI?>/normDocGost/updateMethod/">
    <div class="panel panel-default">
        <header class="panel-heading">
            Основные характеристики
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">
            <input id="method_id" type="hidden" value="<?=$this->data['form']['id']?>" name="id">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Номер документа</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?=$this->data['form']['reg_doc'] ?? ''?>" readonly>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Пункт документа</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="form[clause]" value="<?=$this->data['form']['clause'] ?? ''?>">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Определяемая характеристика / показатель</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="form[name]" value="<?=htmlentities($this->data['form']['name'] ?? '')?>">
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Единицы измерения и нормы
            <span class="tools float-end">
                <a href="#" class="fa fa-chevron-up"></a>
            </span>
        </header>
        <div class="panel-body">

            <div class="result-block">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Единица измерения</label>
                    <div class="col-sm-8">
                        <select class="form-control select2" name="form[unit_id]" required>
                            <option value="">Выбрать</option>
                            <?php foreach ($this->data['unit_list'] as $unit): ?>
                                <option value="<?=$unit['id']?>" <?=$this->data['form']['unit_id'] == $unit['id'] ? 'selected' : ''?>><?=htmlentities($unit['unit_rus'])?> | <?=htmlentities($unit['name'])?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="col-sm-2">
                            <a class="btn btn-square btn-outline-secondary" href="/ulab/reference/unitList/<?=$this->data['form']['id']?>" title="Журнал">
                                <i class="fa-solid fa-list icon-fix-2"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Знаков после запятой</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="form[decimal_places]" value="<?=$this->data['form']['decimal_places'] ?? ''?>">
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row align-items-center">
                    <label class="col-sm-2 col-form-label">Текст нормативного значения по ГОСТу</label>
                    <div class="col-sm-8">
                        <div class="input-group norm-comment-group">
                            <div class="input-group-text">
                                <input id="ch1" class="form-check-input mt-0 me-1 is_output_check" type="checkbox" value="1" name="form[is_output]"
                                    <?=$this->data['form']['is_output'] == 1 ? 'checked' : ''?>>
                                <label class="form-check-label" for="ch1">
                                    Выводить в протокол
                                </label>
                            </div>
                            <input type="text" class="form-control <?=$this->data['form']['is_output'] == 1? '' : 'd-none'?> norm_comment_elem" name="form[norm_comment]"
                                   value="<?=$this->data['form']['norm_comment'] ?? '-'?>">
                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Ручное управление "соотв/не соотв"?</label>
                    <div class="col-sm-8">
                        <input
                                class="form-check-input"
                                type="checkbox"
                                value="1"
                                name="form[is_manual]"
                            <?=$this->data['form']['is_manual'] == 1? 'checked' : ''?>
                        >
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <header class="panel-heading">
            Типы и марки материала
            <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
        </span>
        </header>
        <div class="panel-body">

            <a href="#add-material-modal-form" class="btn btn-success popup-with-form mb-3" type="button">Добавить материал</a>

            <table class="table table-striped">
                <thead>
                <tr class="table-light align-middle">
                    <th scope="col" class="text-center">Материал</th>
                    <th scope="col" class="text-center">Группа</th>
                    <th scope="col" class="text-center w-25">Значение 1</th>
                    <th scope="col" class="text-center w-25">Значение 2</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->data['group_material_list'] as $row): ?>
                <tr>
                    <td><a href="/ulab/material/card/<?=$row['material_id']?>"><?=$row['material_name']?></a></td>
                    <td><?=$row['group_name']?></td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend w80">
                                <select class="form-select"
                                        name="group[<?= $row['id'] ?>][comparison_val_1]">
                                    <option value="more" <?= $row['comparison_val_1'] == 'more' ? 'selected' : '' ?>>
                                        &gt;
                                    </option>
                                    <option value="more_or_equal" <?= $row['comparison_val_1'] == 'more_or_equal' ? 'selected' : '' ?>>
                                        &ge;
                                    </option>
                                    <option value="less" <?= $row['comparison_val_1'] == 'less' ? 'selected' : '' ?>>
                                        &lt;
                                    </option>
                                    <option value="less_or_equal" <?= $row['comparison_val_1'] == 'less_or_equal' ? 'selected' : '' ?>>
                                        &le;
                                    </option>
                                </select>
                            </div>
                            <input type="number" step="any" class="form-control"
                                   name="group[<?= $row['id'] ?>][val_1]"
                                   value="<?= $row['no_val_1'] == 1 ? '' : number_format($row['val_1'], $this->data['form']['decimal_places']?? 0) ?>">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-prepend w80">
                                <select class="form-select"
                                        name="group[<?= $row['id'] ?>][comparison_val_2]">
                                    <option value="more" <?= $row['comparison_val_2'] == 'more' ? 'selected' : '' ?>>
                                        &gt;
                                    </option>
                                    <option value="more_or_equal" <?= $row['comparison_val_2'] == 'more_or_equal' ? 'selected' : '' ?>>
                                        &ge;
                                    </option>
                                    <option value="less" <?= $row['comparison_val_2'] == 'less' ? 'selected' : '' ?>>
                                        &lt;
                                    </option>
                                    <option value="less_or_equal" <?= $row['comparison_val_2'] == 'less_or_equal' ? 'selected' : '' ?>>
                                        &le;
                                    </option>
                                </select>
                            </div>
                            <input type="number" step="any" class="form-control"
                                   name="group[<?= $row['id'] ?>][val_2]"
                                   value="<?= $row['no_val_2'] == 1 ? '' : number_format($row['val_2'], $this->data['form']['decimal_places']?? 0) ?>">
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-square delete-nd" data-id="<?= $row['id'] ?>" title="Удалить ">
                            <i class="fa-solid fa-minus icon-fix"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>


    <button class="btn btn-primary me-3" type="submit" title="Сохранение методики. Снимает статус 'Подтвержден'">Сохранить</button>

    <a class="btn btn-success me-3" href="<?=URI?>/normDocGost/confirmMethod/<?=$this->data['form']['id']?>">Проверено</a>

    <a class="btn btn-danger me-3" href="<?=URI?>/normDocGost/nonActualMethod/<?=$this->data['form']['id']?>">Не актуально</a>

    <?php if ($_SESSION['SESS_AUTH']['USER_ID'] == 1): ?>
        <a class="btn btn-dark me-3 float-end" href="<?=URI?>/normDocGost/deletePermanentlyMethod/<?=$this->data['form']['id']?>" onclick="confirm('Удаляем?')">
            <i class="fa-solid fa-skull-crossbones"></i>
            Удалить
        </a>
    <?php endif; ?>
</form>

<form id="add-material-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="<?=URI?>/normDocGost/addMaterialGroupNormDoc/" method="post">
    <div class="title mb-3 h-2">
        Добавление материала
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="form[norm_doc_method_id]" value="<?=$this->data['form']['id']?>">

    <div class="mb-3">
        <label class="form-label mb-1">Материал - группа <span class="redStars">*</span></label>
        <select class="form-control select2" name="form[materials_groups_id]" required>
            <option value=""></option>
            <?php foreach ($this->data['group_list'] as $item): ?>
                <option value="<?=$item['group_id']?>"><?=$item['material_name']?> - <?=$item['group_name']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label mb-1">Значение 1</label>
        <div class="input-group">
            <div class="input-group-prepend w80">
                <select class="form-select w80"
                        name="form[comparison_val_1]">
                    <option value="more">
                        &gt;
                    </option>
                    <option value="more_or_equal" selected>
                        &ge;
                    </option>
                    <option value="less">
                        &lt;
                    </option>
                    <option value="less_or_equal">
                        &le;
                    </option>
                </select>
            </div>
            <input type="number" step="any" class="form-control" name="form[val_1]" value="">
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label mb-1">Значение 2</label>
        <div class="input-group">
            <div class="input-group-prepend w80">
                <select class="form-select" name="form[comparison_val_2]">
                    <option value="more">
                        &gt;
                    </option>
                    <option value="more_or_equal">
                        &ge;
                    </option>
                    <option value="less">
                        &lt;
                    </option>
                    <option value="less_or_equal" selected>
                        &le;
                    </option>
                </select>
            </div>
            <input type="number" step="any" class="form-control" name="form[val_2]" value="">
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Добавить</button>
</form>
