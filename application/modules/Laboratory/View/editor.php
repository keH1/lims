<header class="header-secondment mb-4">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link link-back" href="<?= $this->data["path"] ?>" title="Вернуться назад">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#back"></use>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<div class="d-flex gap-3" style="width: 100%">
    <div class="col-auto">
        <button type="button" data-js-update="" data-js-manufacturer="<?= $this->data["manufacturer"] ?>" id="add-entry" class="btn btn-primary popup-with-form btn-add-entry w-100 mw-100 mt-0">
            Добавить материал
        </button>
    </div>
    <div class="col-auto">
        <select class="form-select" id="filter-type" data-js-filter-type>
            <option selected value="0">Входной контроль</option>
            <option <?= $_GET["type"] == 1 ? "selected" : "" ?> value="1">Паспортизация</option>
        </select>
    </div>
</div>

<div class="scroll mt-3 mb-3" style="position: relative">
    <div class="table-wrap">
        <table id="table" class="table table-striped journal" style="min-width: 100%">
            <thead>
            <tr class="table-light">
                <th class="text-center">Материал</th>
                <th class="text-center">Производитель</th>
                <th class="text-center">Схемы</th>
                <th class="text-center"></th>
            </tr>

            <tr class="table-light">
                <th scope="col">
                    <input type="text" class="form-control search">
                </th>
                <th scope="col">
                    <input type="text" class="form-control search">
                </th>
                <th scope="col">
                    <input type="text" class="form-control search">
                </th>
                <th></th>

            </tr>
            </thead>
        </table>
    </div>
</div>

<input type="number" id="material_id" hidden>

<form id="add-entry-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Добавить материал
    </div>

    <div class="line-dashed-small"></div>

<!--    <div class="row mb-3">-->
<!--        <div class="col">-->
<!--            <select class="form-select" data-js-filter-type>-->
<!--                <option value="0">Входной контроль</option>-->
<!--                <option value="1">Выходной контроль</option>-->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->


    <div class="row mb-3">
        <div class="col">
            <label for="material_name">Название материала</label>
            <input type="text" data-js class="form-control" id="material_name" placeholder="Введите название материала">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="manufacturer">Производитель</label>
            <input type="text" data-js class="form-control" id="manufacturer" value="<?= $this->data["manufacturer"] ?>" placeholder="Введите название производителя">
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="add-entry-modal-btn" class="btn btn-primary">Сохранить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>

</form>

<form id="add-scheme-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Добавить схему
    </div>

    <div class="line-dashed-small"></div>

    <div class="row mb-3">
        <div class="col">
            <!--            <label for="probe">№ пробы</label>-->
            <input type="text" data-js class="form-control" id="scheme_name" placeholder="Введите название схемы">
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="add-scheme-modal-btn" class="btn btn-primary">Сохранить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>

</form>

<form id="del-entry-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Удалить материал?
    </div>
    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="del-entry-modal-btn" class="btn btn-danger">Удалить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>
</form>