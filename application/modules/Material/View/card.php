<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-1">
                <a class="nav-link" href="<?= URI ?>/material/list/" title="Вернуться к списку">
                    <svg class="icon" width="20" height="20">
                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#list"/>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<form class="form-horizontal" action="<?= URI ?>/material/insertUpdate/" method="post">
    <div class="d-flex align-items-center gap-2">
        <div class="title-block">
            <h2 class="mb-3" id="material-name" style="margin-bottom: 0 !important;">
                <?= $this->data['name'] ?>
            </h2>
        </div>
        <button class="edit-name" style="border: none" type="button">
            <svg width="24px" height="24px" viewBox="0 0 16 16" fill="#9D4CF7" x="128" y="128" role="img" style="display:inline-block;vertical-align:middle" xmlns="http://www.w3.org/2000/svg"><g fill="#9D4CF7"><g fill="currentColor"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></g></g></svg>
        </button>
    </div>


    <input type="text" class="form-control visually-hidden disabled" value="<?= $this->data['name'] ?>" name="name"
           id="name">

    <div class="form-group row">
        <?php if (empty($this->data['id'])): ?>
            <div class="col-sm-12">
                <input type="text" name="NAME" class="form-control"
                       placeholder="Имя материала" value="" required>
            </div>
        <?php endif; ?>
    </div>

    <div class="panel panel-default">
        <header class="panel-heading">
            Привязка методик
            <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
        </header>
        <div class="panel-body">

            <input type="hidden" name="id" value="<?= $this->data['id'] ?>">
            <div class="form-group">
                <label for="select-gost" class="form-label">Выберите методики для связи с материалом:</label>
                <br>
                <select id="select-gost" class="form-control" style="width: 100%;">
                    <option value="0" selected disabled>Выберите</option>
                    <?php foreach ($this->data['gost'] as $optionGost): ?>
                        <option value="<?= $optionGost['id'] ?>" data-id="<?= $optionGost['id'] ?>"
                                data-gost="<?= $optionGost['reg_doc'] ?>"
                                data-spec="<?= $optionGost['name'] ?>"
                                data-price="<?= $optionGost['price'] ?>">
                            <?= $optionGost['view_gost'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <table class="table table-hover" id="table-gost">
                    <thead>
                    <tr>
                        <th scope="col">ГОСТ</th>
                        <th scope="col">Определяемая характеристика</th>
                        <th scope="col"></th>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <input class="form-control" type="text"
                                   placeholder="Поиск по номеру или характеристике" id="search-text"
                                   onkeyup="tableSearch()">
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->data['gost_to_material'] as $arrGost): ?>
                            <tr class="trGost">
                                <input type="hidden" class="gost-to-material-id" value="<?= $arrGost['id'] ?>">
                                <td>
                                    <a href="/ulab/gost/method/<?= $arrGost['id'] ?>"
                                    ><?= $arrGost['view_gost'] ?></a>
                                    <input class="gostId"
                                        type="hidden" value="<?= $arrGost['id'] ?>"
                                        name="arrGost[]">
                                </td>
                                <td><?= $arrGost['name'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger del-gost btn-square-new">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="method-not-found" <?= !empty($this->data['gost_to_material']) ? 'style="display: none;"' : '' ?>>
                            <td colspan="3" class="text-center">
                                Нет методик
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>

        </div>

    </div>
</form>

<div class="panel panel-default">
    <header class="panel-heading">
        Группы материала
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-down"></a>
         </span>
    </header>
    <div class="panel-body" style="display: none">
        <?php if (!empty($this->data['id'])): ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-8">
                    <a href="#add-group-modal-form" class="popup-with-form btn btn-success">Добавить группу</a>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <form action="<?= URI ?>/material/updateGroups/" method="post">
                <input type="hidden" name="material_id" value="<?= $this->data['id'] ?>">
                <?php foreach ($this->data['groups'] as $groupId => $row): ?>
                    <div class="group-block">
                        <div class="line-dashed"></div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">
                                Группа
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="group[<?= $groupId ?>][name]" class="form-control"
                                       value="<?= $row['name'] ?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger btn-square delete-group"
                                        title="Удалить группу" data-group_id="<?= $groupId ?>">
                                    <i class="fa-solid fa-minus icon-fix"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">
                                Тех. условия
                            </label>
                            <div class="col-sm-10">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="col-6">Нормативная документация</th>
                                        <th scope="col">Значение 1</th>
                                        <th scope="col">Значение 2</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    <?php foreach ($row['tu'] as $tu): ?>
                                        <?php if (empty($tu['id'])) {
                                            break;
                                        } ?>
                                        <tr data-count="<?= $i ?>">
                                            <td>
                                                <div class="input-group">
                                                    <select class="form-control select2 tu-select"
                                                            name="group[<?= $groupId ?>][tu][<?= $i ?>][norm_doc_method_id]">
                                                        <option value="">--</option>
                                                        <?php foreach ($this->data['condition_list'] as $tc): ?>
                                                            <option value="<?= $tc['id'] ?>" <?= $tu['norm_doc_method_id'] == $tc['id'] ? 'selected' : '' ?>><?= $tc['view_gost'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <a class="btn btn-outline-secondary tu-link" 
                                                       title="Перейти в ту"
                                                       href="/ulab/normDocGost/edit/<?= $tu['norm_doc_method_id'] ?>">
                                                        <i class="fa-solid fa-right-to-bracket"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <select class="form-select"
                                                            name="group[<?= $groupId ?>][tu][<?= $i ?>][comparison_val_1]">
                                                        <option value="more" <?= $tu['comparison_val_1'] == 'more' ? 'selected' : '' ?>>
                                                            &gt;
                                                        </option>
                                                        <option value="more_or_equal" <?= $tu['comparison_val_1'] == 'more_or_equal' ? 'selected' : '' ?>>
                                                            &ge;
                                                        </option>
                                                        <option value="less" <?= $tu['comparison_val_1'] == 'less' ? 'selected' : '' ?>>
                                                            &lt;
                                                        </option>
                                                        <option value="less_or_equal" <?= $tu['comparison_val_1'] == 'less_or_equal' ? 'selected' : '' ?>>
                                                            &le;
                                                        </option>
                                                    </select>
                                                    <input type="number" step="any" class="form-control"
                                                           name="group[<?= $groupId ?>][tu][<?= $i ?>][val_1]"
                                                           value="<?= $tu['val_1'] ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <select class="form-select"
                                                            name="group[<?= $groupId ?>][tu][<?= $i ?>][comparison_val_2]">
                                                        <option value="more" <?= $tu['comparison_val_2'] == 'more' ? 'selected' : '' ?>>
                                                            &gt;
                                                        </option>
                                                        <option value="more_or_equal" <?= $tu['comparison_val_2'] == 'more_or_equal' ? 'selected' : '' ?>>
                                                            &ge;
                                                        </option>
                                                        <option value="less" <?= $tu['comparison_val_2'] == 'less' ? 'selected' : '' ?>>
                                                            &lt;
                                                        </option>
                                                        <option value="less_or_equal" <?= $tu['comparison_val_2'] == 'less_or_equal' ? 'selected' : '' ?>>
                                                            &le;
                                                        </option>
                                                    </select>
                                                    <input type="number" step="any" class="form-control"
                                                           name="group[<?= $groupId ?>][tu][<?= $i ?>][val_2]"
                                                           value="<?= $tu['val_2'] ?>">
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-danger btn-square delete-tu"
                                                        title="Удалить ">
                                                    <i class="fa-solid fa-minus icon-fix"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-success btn-square add-tu"
                                                    title="Добавить " data-group_id="<?= $groupId ?>">
                                                <i class="fa-solid fa-plus icon-fix"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        <?php else: ?>
            <div class="form-group row">
                <label class="col-sm-2 col-form-label"></label>
                <div class="col-sm-8">
                    Для добавления группы, сохраните материал
                </div>
                <div class="col-sm-2"></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Схема для материала
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-down"></a>
         </span>
    </header>
    <div class="panel-body" style="display: none;">
        <div class="row">
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-3" style="width: 10%;" id="v-pills-tab" role="tablist"
                     aria-orientation="vertical">
                    <?php foreach ($this->data['scheme'] as $item): ?>
                        <button class="btn btn-outline-secondary nav-link mw-100 mt-0 mb-1"
                                id="v-pills-<?= $item['id'] ?>-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-<?= $item['id'] ?>" type="button" role="tab"
                                aria-controls="v-pills-<?= $item['id'] ?>"
                                aria-selected="true"><?= $item['name'] ?></button>
                    <?php endforeach; ?>
                    <button class="btn btn-primary mw-100 mt-0" type="button" id="v-pills-<?= $item['id'] ?>-tab"
                            data-bs-toggle="pill"
                            data-bs-target="#v-pills-new" role="tab"
                            aria-controls="v-pills-new"
                            aria-selected="true"><i class="fa-solid fa-plus"></i></button>
                </div>
                <div class="tab-content  w-100" id="v-pills-tabContent">
                    <?php foreach ($this->data['scheme'] as $scheme): ?>
                        <div class="tab-pane fade scheme-info" id="v-pills-<?= $scheme['id'] ?>" role="tabpanel"
                             aria-labelledby="v-pills-<?= $scheme['id'] ?>-tab" tabindex="0">
                            <input type="hidden" name="scheme_id" value="<?= $scheme['id'] ?>">
                            <input type="hidden" name="material_id" value="<?= $this->data['id'] ?>">
                            <div class="mb-3">
                                <button type="button" class="btn btn-danger float-end mb-1 delete-scheme">
                                    Удалить схему
                                </button>
                                <label for="scheme-name-<?= $scheme['id'] ?>" class="form-label">Название схемы: <span class="redStars">*</span></label>
                                <input type="text" name="scheme-param" class="form-control"
                                       id="scheme-name-<?= $scheme['id'] ?>" placeholder="Введите название схемы"
                                       value="<?= $scheme['name'] ?>">
                            </div>

                            <div class="line-dashed-small"></div>
                            <div class="row justify-content-between">
                                <div class="col-5">
                                    <label class="form-label mb-1">Методика испытаний</label>
                                </div>
                                <div class="col-5">
                                    <label class="form-label mb-1">Нормативная документация</label>
                                </div>
                                <div class="col-auto">
                                    <!--							<button-->
                                    <!--									class="btn btn-danger mt-0 del-new-method btn-square float-end"-->
                                    <!--									type="button"-->
                                    <!--							>-->
                                    <!--								<i class="fa-solid fa-minus icon-fix"></i>-->
                                    <!--							</button>-->
                                </div>
                            </div>
                            <div class="method-container mb-3">
                                <?php foreach ($scheme['param'] as $i => $val): ?>

                                    <div class="row justify-content-between method-block mb-2"
                                         data-gost_number="<?=$i?>">
                                        <div class="col-5">
                                            <div class="input-group">
                                                <select class="form-control select2 method-select"
                                                        name="form[<?=$i?>][new_method_id]" required>
                                                    <option value="">--</option>
                                                    <?php foreach ($this->data['method_list'] as $method): ?>
                                                        <option value="<?= $method['id'] ?>" <?= $method['id'] == $val['method_id'] ? 'selected' : '' ?>><?= $method['view_gost'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <a class="btn btn-outline-secondary method-link"
                                                   
                                                   title="Перейти в методику" href="/ulab/gost/method/<?= $method['id'] ?>">
                                                    <i class="fa-solid fa-right-to-bracket"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="input-group">
                                                <select class="form-control select2 tu-select"
                                                        name="form[<?=$i?>][norm_doc_method_id]">
                                                    <option value="">--</option>
                                                    <?php foreach ($this->data['condition_list'] as $method): ?>
                                                        <option value="<?= $method['id'] ?>" <?= $method['id'] == $val['nd_id'] ? 'selected' : '' ?>><?= $method['view_gost'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <a class="btn btn-outline-secondary tu-link" 
                                                   title="Перейти в ТУ" href="/ulab/normDocGost/edit/<?= $val['nd_id'] ?>">
                                                    <i class="fa-solid fa-right-to-bracket"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button
                                                    class="btn btn-danger mt-0 del-new-method btn-square float-end"
                                                    type="button"
                                            >
                                                <i class="fa-solid fa-minus icon-fix"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-success w150 add-new-method">
                                        <i class="fa-solid fa-plus icon-fix"></i> Методику
                                    </button>
                                </div>
                            </div>
                            <div class="line-dashed-small"></div>
                            <button class="btn btn-primary edit-scheme">Сохранить</button>
                        </div>
                    <?php endforeach; ?>

                    <div class="tab-pane fade scheme-info" id="v-pills-new" role="tabpanel"
                         aria-labelledby="v-pills-new-tab" tabindex="0">
                        <input type="hidden" name="scheme_id" value="">
                        <input type="hidden" name="material_id" value="<?= $this->data['id'] ?>">
                        <div class="mb-3">
                            <label for="scheme-name-new" class="form-label">Название схемы: <span class="redStars">*</span></label>
                            <input type="text" class="form-control" id="scheme-name-new" name="scheme-param"
                                   placeholder="Введите название схемы">
                        </div>
                        <div class="line-dashed-small"></div>
                        <div class="row justify-content-between">
                            <div class="col-5">
                                <label class="form-label mb-1">Методика испытаний</label>
                            </div>
                            <div class="col-5">
                                <label class="form-label mb-1">Нормативная документация</label>
                            </div>
                            <div class="col-auto">
                                <!--							<button-->
                                <!--									class="btn btn-danger mt-0 del-new-method btn-square float-end"-->
                                <!--									type="button"-->
                                <!--							>-->
                                <!--								<i class="fa-solid fa-minus icon-fix"></i>-->
                                <!--							</button>-->
                            </div>
                        </div>
                        <div class="method-container mb-3">
                            <div class="row justify-content-between method-block mb-2" data-gost_number="<?=$i?>">
                                <div class="col-5">
                                    <div class="input-group">
                                        <select class="form-control select2 method-select"
                                                name="form[<?=$i?>][new_method_id]" required>
                                            <option value="">--</option>
                                            <?php foreach ($this->data['method_list'] as $method): ?>
                                                <option value="<?= $method['id'] ?>"><?= $method['view_gost'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <a class="btn btn-outline-secondary method-link disabled" 
                                           title="Перейти в методику" href="">
                                            <i class="fa-solid fa-right-to-bracket"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="input-group">
                                        <select class="form-control select2 tu-select"
                                                name="form[<?=$i?>][norm_doc_method_id]">
                                            <option value="">--</option>
                                            <?php foreach ($this->data['condition_list'] as $tc): ?>
                                                <option value="<?= $tc['id'] ?>"><?= $tc['view_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <a class="btn btn-outline-secondary tu-link disabled" 
                                           title="Перейти в ТУ" href="">
                                            <i class="fa-solid fa-right-to-bracket"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button
                                            class="btn btn-danger mt-0 del-new-method btn-square float-end"
                                            type="button"
                                    >
                                        <i class="fa-solid fa-minus icon-fix"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-start">
                            <div class="col-auto">
                                <button type="button" class="btn btn-success w150 add-new-method">
                                    <i class="fa-solid fa-plus icon-fix"></i> Методику
                                </button>
                            </div>
                        </div>
                        <div class="line-dashed-small"></div>
                        <button class="btn btn-primary edit-scheme">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<form id="add-group-modal-form" class="bg-light mfp-hide col-md-8 m-auto p-3 position-relative"
      action="/ulab/material/addGroup/" method="post">
    <div class="title mb-3 h-2">
        Добавление группы
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="material_id" value="<?= $this->data['id'] ?>">

    <div class="mb-3">
        <label class="form-label">Название группы <span class="redStars">*</span></label>
        <input type="text" name="name" class="form-control" maxlength="256" value="" required>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th scope="col" class="col-6">Нормативная документация</th>
            <th scope="col">Значение 1</th>
            <th scope="col">Значение 2</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end">
                <button type="button" class="btn btn-success btn-square add-tu" title="Добавить ">
                    <i class="fa-solid fa-plus icon-fix"></i>
                </button>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>