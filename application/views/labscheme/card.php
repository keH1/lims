<header class="header-secondment mb-4">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link link-back" href="<?= URI ?>/LabScheme/editor<?= $this->data["card"]["scheme"]["material_type"] == 1 ? "?type=1" : "" ?>" title="Вернуться назад">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#back"></use>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<h3>Материал: <?= $this->data["card"]["scheme"]["material_name"] ?></h3>
<h3>Схема: <?= $this->data["card"]["scheme"]["name"] ?></h3>

<input id="scheme_id" type="number" value="<?= $this->data["card"]["scheme"]["id"] ?>" hidden>
<input id="gost_id" type="number" hidden>

<div class="d-flex justify-content-between gap-3" style="width: 100%">
    <div>
        <button type="button" data-js-update="" id="add-entry" class="btn btn-primary popup-with-form btn-add-entry mw-100 mt-0">
            Добавить гост
        </button>
<!--        <button type="button" data-js-copy-modal="" class="btn btn-primary popup-with-form mw-100 mt-0 ml-4">-->
<!--            Копировать схему-->
<!--        </button>-->
<!--        <a target="_blank" href="--><?//= URI ?><!--/laboratory/dashboard/--><?//= $this->data["card"]["scheme"]["id"] ?><!--/" class="btn btn-primary popup-with-form mw-100 mt-0 ml-4">-->
<!--            Все испытания-->
<!--        </a>-->

        </button>
    </div>
    <button class="btn btn-danger" data-js-del-scheme id="deleteScheme">Удалить</button>
</div>

<div class="scroll mt-3 mb-3" style="position: relative">
    <div class="table-wrap">
        <table id="table" class="table table-striped journal" style="min-width: 100%">
            <thead>
            <tr class="table-light">
                <th class="text-center">Гост</th>
                <th class="text-center">Характеристика</th>
                <th class="text-center">Параметр</th>
                <th class="text-center wd-80">От</th>
                <th class="text-center wd-80">До</th>
                <th class="text-center wd-80">Свой</th>
                <th class="text-center wd-100"></th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<input type="number" id="material_id" value="<?= $this->data["card"]["scheme"]["material_id"] ?>" hidden>
<input type="number" id="material_type" value="<?= $this->data["card"]["scheme"]["material_type"] ?>" hidden>
<input type="number" id="scheme_gost_id" hidden>


<form id="add-entry-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div data-js-title class="title mb-3 h-2">
        Добавить гост
    </div>

    <div class="line-dashed-small"></div>

    <div class="row mb-3">
        <div class="col custom-select2" style="width: 600px">
            <select
                name="gost_id"
                class="form-control h-auto user"
                id="gost"
                required
                style="width: 100%"
            >
                <option value="">Выбрать гост</option>
                <?php foreach ($this->data["card"]["methodList"] as $method): ?>
                    <option value="<?= $method["id"] ?>"><?= $method["reg_doc"] ?> <?= $method["name"] ?> <?= $method["clause"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col" style="width: 600px">
            <label for="range_from">Значение от</label>
            <input class="form-control" type="number" id="range_from" placeholder="">
        </div>


    </div>
    <div class="row mb-3">
        <div class="col" style="width: 600px">
            <label for="range_before">Значение до</label>
            <input class="form-control" type="number" id="range_before" placeholder="">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col" style="width: 600px">
            <label for="laboratory_status">Своя лаборатория?</label>
            <select class="form-control w-100" id="laboratory_status">
                <option value="0" selected>Нет</option>
                <option value="1">Да</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col" style="width: 600px">
            <label for="param">Параметр</label>
            <input class="form-control" type="text" id="param" placeholder="">
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="add-entry-modal-btn" class="btn btn-primary">Сохранить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>

</form>


<form id="del-entry-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Удалить гост из схемы?
    </div>
    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="del-entry-modal-btn" class="btn btn-danger">Удалить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>
</form>

<form id="del-scheme-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Удалить схему?
    </div>
    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="del-scheme-modal-btn" class="btn btn-danger">Удалить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>
</form>

<form id="copy-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Копировать схему?
    </div>
    <div class="line-dashed-small"></div>

    <div class="row mb-3">
        <div class="col">
            <label for="scheme_name">Название схемы</label>
            <input
                type="text"
                class="form-control"
                id="scheme_name"
                placeholder="Введите название схемы"
                value="<?= $this->data["card"]["scheme"]["name"] ?> (Копия)"
            >
        </div>
    </div>

    <div class="row mb-3">
        <div class="col custom-select2" style="width: 600px">
            <label for="material-select">Материал</label>
            <select
                class="form-control h-auto user"
                id="material-select"
                required
                style="width: 100%"
            >
                <?php foreach ($this->data["card"]["materials"] as $material): ?>
                    <option <?= $this->data["card"]["scheme"]["material_id"] == $material["id"] ? "selected" : "" ?> value="<?= $material["id"] ?>"><?= $material["name"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="d-flex">
        <button type="button" data-js-copy class="btn btn-primary">Копировать</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>
</form>