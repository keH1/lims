<style>
    .rotate-0 {
        transform: rotate(0deg);
        transition: 0.5s;
    }

    .rotate-180 {
        transform: rotate(180deg);
        transition: 0.5s;
    }
</style>

<div class="filters mb-3">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-with-form btn-add-entry w-100 mw-100 mt-0">
                Добавить раствор
            </button>
        </div>

        <div class="col-auto">
            <input type="month" id="inputDateStart"
                   class="form-control filter filter-date-start"
                   value="<?= date("Y") . '-01' ?>" title="Введите дату начала">
        </div>
        <div class="col-auto">
            <input type="month" id="inputDateEnd"
                   class="form-control filter filter-date-end"
                   value="<?= date("Y") . '-12' ?>" title="Введите дату окончания">
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset"
                    title="Сбросить фильтр">Сбросить
            </button>
        </div>
    </div>
</div>
<!--./filters-->

<table id="solution_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light align-middle">
        <th scope="col">Наименование раствора</th>
        <th scope="col">Нормативный документ</th>
        <th scope="col">Количество</th>
        <th scope="col">Дата приготовления</th>
        <th scope="col">Срок годности</th>
        <th scope="col" class="text-nowrap">Реактивы, используемые для
            приготовления
        </th>
        <th scope="col">Ответственный</th>
    </tr>
    <tr class="header-search">
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
        <th scope="col">
            <input type="text" class="form-control search ">
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>


<form id="add-entry-modal-form"
      class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      method="post"
    action="<?=URI?>/solution/addSolutionAndConsume/">
    <div class="title mb-3 h-2">
        Данные регистрации
    </div>

    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Имя реактива</label>
            <input type="text" name="toSQL[name]"
                   class="form-control name-solution"
                   value="" required>
        </div>
        <div class="col">
            <label class="form-label">Дата приготовления раствора</label>
            <input type="date" class="form-control probe-date bg-white"
                   name="toSQL[date_preparation]"
                   value="<?= date('Y-m-d') ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Выберите рецепт </label>
            <select name="toSQL[id_recipe_model]"
                    class="form-control select-recipe h-auto"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['recipe'] as $val): ?>
                    <option value="<?= $val['id'] ?>"
                            data-name="<?= $val['name'] ?>">
                        <?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Реактивы</label>
            <table id="reactive_journal"
                   style="width: 100% !important; text-align: center !important;display: none; vertical-align: middle"
                   class="table table-striped text-center w-auto">
                <thead>
                <tr class="table-light align-middle">
                    <th scope="col" class="text-nowrap"></th>
                    <th scope="col" class="text-nowrap">Наименование реактива</th>
                    <th scope="col" class="text-nowrap">В наличии</th>
                    <th scope="col" class="text-nowrap">Расход</th>
                    <th scope="col" class="text-nowrap">Остаток</th>
                    <th scope="col" class="text-nowrap">Срок годности до</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Растворитель</label>
            <table id="solvent_journal"
                   style="width: 100% !important; text-align: center !important;display: none; vertical-align: middle"
                   class="table table-striped text-center w-auto">
                <thead>
                <tr class="table-light align-middle">
                    <th scope="col" class="text-nowrap"></th>
                    <th scope="col" class="text-nowrap">Наименование реактива</th>
                    <th scope="col" class="text-nowrap">В наличии</th>
                    <th scope="col" class="text-nowrap">Расход</th>
                    <th scope="col" class="text-nowrap">Остаток</th>
                    <th scope="col" class="text-nowrap">Срок годности до</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-primary send_form">Приготовить реактив</button>
</form>
<!--./add-entry-modal-form-->