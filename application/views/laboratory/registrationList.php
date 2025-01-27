<div class="d-flex gap-3" style="width: 100%">
    <div class="col-auto">
        <button type="button" id="add-entry" class="btn btn-primary popup-with-form btn-add-entry w-100 mw-100 mt-0">
            Добавить запись в журнал
        </button>
    </div>
    <div class="col-md-2">
        <input data-js-date type="date" id="dateStart" class="form-control filter filter-date-end" value=""
               placeholder="Введите дату окончания:">
    </div>
    <div data-js-date class="col-md-2">
        <input type="date" id="dateEnd" class="form-control filter filter-date-end" value=""
               placeholder="Введите дату окончания:">
    </div>
    <div class="col-auto">
        <button type="button" id="search-btn" name="add_entry"
                class="btn btn-primary popup-with-form btn-add-entry w-100 mw-100 mt-0">
            Найти
        </button>
    </div>
    <div class="col-auto d-flex align-items-center justify-content-center p-0">
        <a title="Редактор схем" href="<?= URI ?>/laboratory/editor/" class="rounded"><i class="fa-solid fa-gear text-primary"></i></i></a>
<!--        <a title="Редактор схем" href="--><?//= URI ?><!--/laboratory/editor/" class="rounded"><i class="fa-solid fa-gears text-primary"></i></i></a>-->

    </div>

</div>

<div class="scroll mt-3 mb-3" style="position: relative">
    <div class="table-wrap">
        <table id="table" class="table table-striped journal" style="min-width: 100%">
            <thead>
            <tr class="table-light">
                <th class="wd-80 text-center" data-js-header></th>
                <th class="wd-80 text-center" data-js-header>Заявка</th>
                <th class="wd-100 text-center" data-js-header>№&nbsp;партии</th>
                <th class="wd-400 text-center" data-js-header>Материал</th>
                <th class="wd-80 text-center" data-js-header>Фракция</th>
                <th class="wd-100 text-center" data-js-header>Тоннаж</th>
                <th class="wd-100 text-center" data-js-header>Дата</th>
                <th class="wd-150 text-center" data-js-header>Поставщик</th>
<!--                <th class="wd-120 text-center" data-js-header>Ответственный</th>-->
                <th class="wd-40 text-center" data-js-header></th>
<!--                <th class="wd-200 text-center" data-js-header></th>-->
                <!--                    <th class="text-center" data-js-header>ТЗ</th>-->
            </tr>

            <tr class="table-light" data-js-search>
                <th scope="col"></th>
                <th scope="col"><input type="text" class="form-control search"></th>
                <th scope="col"><input type="text" class="form-control search"></th>
                <th scope="col"><input type="text" class="form-control search"></th>
                <th scope="col"><input type="text" class="form-control search"></th>
                <th scope="col"></th>
                <th scope="col"><input type="text" class="form-control search"></th>
                <th scope="col"><input type="text" class="form-control search"></th>
<!--                <th scope="col"><input disabled type="text" class="form-control search"></th>-->
                <th scope="col"></th>
<!--                <th></th>-->
                <!--                    <th scope="col">-->
                <!--                        <input type="text" class="form-control search">-->
                <!--                    </th>-->
            </tr>
            </thead>
        </table>
    </div>
</div>

<form id="add-entry-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/secondment/insertUpdateInfo/" method="post">
    <div class="title mb-3 h-2">
        Данные регистрации
    </div>

    <div class="line-dashed-small"></div>

    <div class="row mb-3">
        <div class="col" style="width: 400px">
            <label for="user">Выбрать материал<span class="redStars">*</span></label>
            <select
                    name="material"
                    class="form-control h-auto user mt-1"
                    id="material"
                    required
                    style="width: 100%"
                    data-js
            >
                <option value="" selected disabled></option>
                <?php foreach ($this->data["materials"] as $material): ?>
                    <option value="<?= $material["id"] ?>"><?= $material["name"] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

<!--    <div class="row mb-3">-->
<!--        <div class="col" style="width: 400px">-->
<!--            <label for="assigned_id">Ответственный</span></label>-->
<!--            <select-->
<!--                    name="material"-->
<!--                    class="form-control h-auto user mt-1"-->
<!--                    id="assigned_id"-->
<!--                    required-->
<!--                    style="width: 100%"-->
<!--                    data-js-->
<!--            >-->
<!--                <option value="18">Богусевич</option>-->
<!--                <option value="94">Третьяков</option>-->
<!---->
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->

    <div class="row mb-3">
        <div class="col">
            <label for="batch_number">№ партии</label>
            <input type="text" class="form-control mt-1" id="batch_number" placeholder="">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="repeat">
                <label class="form-check-label" for="repeat">
                    Повторная заявка
                </label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="fraction_name">Фракция</label>
            <input type="text" class="form-control mt-1" id="fraction_name" placeholder="" style="min-width: 70% !important; width: 70% !important;  padding: 0 !important;">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="tonnage">Тоннаж</label>
            <input type="number" class="form-control mt-1" id="tonnage" placeholder="" style="min-width: 70% !important; width: 70% !important;  padding: 0 !important;">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label for="scheme">Выбрать схему</label>
            <div class="row mt-1" style="min-width: 100%">
                <div class="col-10">
                    <select
                            name="scheme_id"
                            class="form-control h-auto user"
                            id="scheme"
                            required
                            data-js
                            style="width: 100%"
                    >

                    </select>
                </div>

<!--                <button type="button" class="btn btn-primary rounded col-sm-1 col-md-offset-2" data-js-toggle-scheme><i-->
<!--                            class="fa-solid fa-plus"></i></button>-->
                <div class="col-1 d-flex align-items-center justify-content-center p-0">
                    <a href="<?= URI ?>/laboratory/editor/" class="rounded"><i class="fa-solid fa-gear text-primary"></i></i></a>

                </div>
            </div>

            <div class="mt-3" id="gost_list"></div>

        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="add-entry-modal-btn" class="btn btn-primary">Отправить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>

    <div class="d-none" id="loader"><?= TemplateHelper::addLoader() ?></div>

</form>

<form id="update-form" class="bg-light mfp-hide col-md-6 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Изменить запись
    </div>

    <div class="line-dashed-small"></div>

    <input type="number" data-js class="form-control d-none" id="update_id">

    <div class="row mb-3">
        <div class="col">
            <label for="update_batch_number">№ партии</label>
            <input type="text" data-js class="form-control" id="update_batch_number" placeholder="Партия">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="update_fraction_name">Фракция</label>
            <input type="text" data-js class="form-control" id="update_fraction_name" placeholder="Фракция">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label for="update_tonnage">Тоннаж</label>
            <input type="number" data-js class="form-control" id="update_tonnage" placeholder="Тоннаж">
        </div>
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="update" class="btn btn-primary">Сохранить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>

</form>