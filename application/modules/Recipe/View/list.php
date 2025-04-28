<div class="filters mb-3">
    <div class="row">
        <div class="col-auto">
            <div class="two-button">
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-first btn-add-entry mt-0 btn-reactive">
                    Добавить рецепт
                </button>
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-second btn-add-entry mt-0 btn-reactive">
                    Раствор как реактив
                </button>
            </div>
        </div>
    </div>
</div>
<!--./filters-->

<table id="recipe_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light align-middle">
        <th scope="col" class="text-nowrap">Имя</th>
        <th scope="col" class="text-nowrap">Концентрация</th>
        <th scope="col" class="text-nowrap">Тип раствора</th>
        <th scope="col" class="text-nowrap">Нормативный документ</th>
        <th scope="col" class="text-nowrap">Реактивы и их количества</th>
        <th scope="col" class="text-nowrap">Растворитель</th>
        <th scope="col" class="text-nowrap">К-во раствора</th>
        <th scope="col" class="text-nowrap">Срок годности, сутки</th>
        <th scope="col" class="text-nowrap">Частота проверки, сутки</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
    </tr>
    <tr class="header-search">
        <th scope="col">
            <input type="text" id="param" class="form-control search" value="<?= $this->data['param'] ?>">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div class='arrowLeft'>
    <svg class="bi" width="40" height="40">
        <use xlink:href="<?= URI ?>/assets/images/icons.svg#arrow-left"/>
    </svg>
</div>
<div class='arrowRight'>
    <svg class="bi" width="40" height="40">
        <use xlink:href="<?= URI ?>/assets/images/icons.svg#arrow-right"/>
    </svg>
</div>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/recipe/addModelRecipe/" method="post">
    <div class="title mb-3 h-2">
        Добавить запись
    </div>
    <div class="line-dashed-small"></div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="nameRecipe">Название рецепта</label>
            <input type="text" name="recipe_model[name]" class="form-control name-recipe" id="nameRecipe"
                   value="" required>
        </div>
    </div>
    <div class="row mb-3">
        <label class="form-label">Тип раствора</label>
        <div class="col">
            <select name="recipe_model[id_recipe_type]" class="form-control recipe-type" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['recipe_type'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col">
            <input name="recipe_model[is_accurate]" class="form-check-input recipe-is-accurate" type="checkbox"
                   value="1"
                   id="isAccurate" checked disabled>
            <label class="form-check-label" for="isAccurate">
                Точная концентрация
            </label>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Концентрация </label>
            <input type="number" name="recipe_model[concentration]" step="0.0001" min="0" max="100000"
                   class="form-control bg-white"
                   value="">
        </div>
        <div class="col">
            <label class="form-label">Ед. измерения</label>
            <select name="recipe_model[id_unit_of_concentration]" class="form-control " required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['unit_of_concentration'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3 ">
        <div class="col">
            <label class="form-label">Срок годности, сутки </label>
            <input type="number" name="recipe_model[storage_life_in_day]" step="1" min="0" max="10000"
                   class="form-control bg-white"
                   value="" required>
        </div>
        <div class="col recipe-check">
            <label class="form-label">Частота проверки, сутки </label>
            <input type="number" name="recipe_model[check_in_day]" step="1" min="0" max="10000"
                   class="form-control bg-white"
                   value="">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label" for="nameRecipe">Индивидуальные особенности годности</label>
            <input type="text" name="recipe_model[check_property]" class="form-control" value="" >
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Нормативный документ</label>
            <select name="recipe_model[id_doc]" class="form-control bg-white select2" data-placeholder="Выберете документ" required >
                <option value="" selected disabled></option>
                <?php foreach ($this->data['doc'] as $val): ?>
                    <option value="<?= $val['id'] ?>"><?= $val['view_gost'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="line-dashed-small"></div>
    <div>
        <button type="button" class="btn btn-secondary add-reactive">Добавить реактив</button>
        <button type="button" class="btn btn-secondary del-reactive">Удалить реактив</button>
    </div>
    <div class="line-dashed-red"></div>
    <div class="reactives" data-id="1" reactives-id ="1">
        <div class="reactive-unit">
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Реактив</label>
                    <select name="reactives[unit_reactive_id1][id_library_reactive]"
                            class="form-control select2 select-reactive" data-placeholder="Выберете реактив" required>
                        <option value="" selected disabled></option>
                        <?php
                        foreach ($this->data['reactive'] as $val): ?>
                            <option value="<?= $val['id'] ?? '' ?>"
                                    data-unit="<?= $val['unit'] ?>"><?= $val['name'] ?? '' ?></option>
                        <?php
                        endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Количество реактива</label>
                    <div class="input-group">
                        <input type="number" name="reactives[unit_reactive_id1][quantity]" step="0.0001" min="0"
                               max="1000000"
                               class="form-control bg-white" required>
                        <span class="input-group-text quantity-reactive"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="line-dashed-red"></div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Растворитель</label>
            <select name="solvent[id_library_reactive]" class="form-control select2" data-placeholder="Выберете растворитель" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['solvent'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"
                            data-unit="<?= $val['unit'] ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Количество растворителя</label>
            <div class="input-group">
                <input type="number" name="solvent[quantity]" step="0.01" min="0" max="10000"
                       class="form-control bg-white" required>
                <span class="input-group-text quantity-solvent"></span>
            </div>
        </div>
        <div class="col">
            <label class="form-label">Количество раствора</label>
            <div class="input-group">
                <input type="number" name="recipe_model[quantity_solution]" step="0.01" min="0" max="10000"
                       class="form-control bg-white" required>
                <span class="input-group-text quantity-solvent"></span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-second" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/recipe/addSolutionAsReactive/" method="post">
    <div class="title mb-3 h-2">
        Раствор как реактив
    </div>

    <div class="line-dashed-small"></div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Название реактива</label>
            <input type="text" name="reactive_lab[name]" class="form-control name-recipe"
                   value="" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Рецепт</label>
            <select name="reactive_lab[id_recipe_model]" class="form-control select2" data-placeholder="Выберете рецепт" required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['recipe'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val["name"] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<!--./add-entry-modal-form-->