<style>
    .header-menu, ul.nav {
        width: 100%;
    }

    .header-menu .nav-item {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="rooms-wrapper import">
    <header class="header-requirement mb-4 pt-0">
        <nav class="header-menu">
            <ul class="nav">
<!--                <li class="nav-item me-3">-->
<!--                    <a class="nav-link fa-solid icon-nav fa-arrow-left disabled" id="back-button" style="font-size: 22px;" title="Назад" data-bs-toggle="tooltip">-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li class="nav-item me-3">-->
<!--                    <a class="nav-link fa-solid icon-nav fa-rectangle-list" href="--><?//= URI ?><!--/import/list" style="font-size: 22px;" title="Профиль лаборатории" data-bs-toggle="tooltip">-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li class="nav-item me-2">-->
<!--                    <a class="nav-link fa-solid icon-nav fa-flask" href="--><?//=URI?><!--/import/lab/" title="Отделы" data-bs-toggle="tooltip" style="font-size: 22px; margin: 2px 0 0 1px;">-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li class="nav-item me-2">-->
<!--                    <a class="nav-link fa-solid icon-nav fa-door-closed disabled" href="--><?//=URI?><!--/import/rooms/" title="Помещения" data-bs-toggle="tooltip" style="font-size: 22px; margin: 2px 0 0 1px;">-->
<!--                    </a>-->
<!--                </li>-->

                <li class="nav-item ms-auto d-flex gap-2">
                    <?php if (!empty($this->data['form_room'])): ?>
                        <a class="btn btn-gradient rounded"
                           href="/ulab/import/dowloadForm/<?= $this->data['lab_id'] ?>?type=form"
                           title="Скачать форму № 6"
                           style="text-transform: none;">
                            Скачать форму №6
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($this->data['lab_id'])): ?>
                        <button class="btn btn-gradient popup-with-form rounded"
                                type="button"
                                title="Добавить новое помещение"
                                data-bs-toggle="tooltip"
                                style="text-transform: none;">
                            Добавить помещение
                        </button>
                    <?php endif; ?>
                </li>

             </ul>
        </nav>
    </header>

    <div class="filters mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <header class="panel-heading">
                        Отдел
                        <span class="tools float-end">
                                <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                                <a href="#" class="fa fa-chevron-up"></a>
                            </span>
                    </header>
                    <div class="panel-body">
                        <div class="form-group row mb-0">
                            <div class="col">
                                <?php if ( !empty($this->data['labs']) ): ?>
                                    <select class="form-select filter filter-lab" id="labs">
                                        <option value="" style="color: #878787">Выберите отдел</option>
                                        <?php foreach ($this->data['labs'] as $lab): ?>
                                            <option value="<?= $lab['ID'] ?>" <?= $this->data['lab_id'] == $lab['ID'] ? 'selected' : '' ?>><?= $lab['NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <div>Отсутствуют отделы, создайте отдел для привязки помещений к отделам</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default rooms-block">
                <header class="panel-heading">
                    Помещения
                    <span class="tools float-end">
                        <a href="javascript:;" class="fa fa-star-of-life bg-transparent text-danger d-none"></a>
                        <a href="#" class="fa fa-chevron-up"></a>
                    </span>
                </header>
                <div class="panel-body">
<!--                    --><?php //if (!empty($this->data['form_room'])): ?>
<!--                        <div class="d-flex justify-content-end mb-3">-->
<!--                            <a class="btn btn-gradient rounded" href="/ulab/import/dowloadForm/--><?//= $this->data['lab_id'] ?><!--?type=form"-->
<!--                                title="Скачать форму № 6"-->
<!--                                style="text-transform: none;"-->
<!--                            >-->
<!--                                Скачать форму №6-->
<!--                            </a>-->
<!--                        </div>-->
<!--                    --><?php //endif; ?>

                    <div class="table-responsive">
                        <table id="rooms-table" class="table table-striped journal">
                            <thead class="align-middle">
                                <tr class="table-light">
                                    <th scope="col">Номер</th>
                                    <th scope="col">Наименование</th>
                                    <th scope="col">Тип</th>
                                    <th scope="col">Назначение</th>
                                    <th scope="col">Площадь</th>
                                    <th scope="col">Контролируемые параметры</th>
                                    <th scope="col">Специальное оборудование</th>
                                    <th scope="col">Право собственности</th>
                                    <th scope="col">Местонахождение</th>
                                    <th scope="col">Примечание</th>
                                    <th scope="col"></th>
                                </tr>
                                <tr class="header-search">
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
                                    <th scope="col">
                                        <input type="text" class="form-control search">
                                    </th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <div class='arrowLeft'>
                            <svg class="bi" width="40" height="40">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#arrow-left"/>
                            </svg>
                        </div>
                        <div class='arrowRight'>
                            <svg class="bi" width="40" height="40">
                                <use xlink:href="<?=URI?>/assets/images/icons.svg#arrow-right"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="room-modal-form" class="bg-light mfp-hide col-md-5 m-auto p-3 position-relative">
        <div id="title-type" class="title mb-3 h-2">
            Редактировать помещение
        </div>

        <div class="line-dashed-small"></div>

        <input type="hidden" id="roomId" name="form_room[room_id]" value="">
        <input type="hidden" id="labId" name="form_room[LAB_ID]" value="">

        <div class="mb-3">
            <label class="form-label mb-1">Номер</label>
            <input type="number" class="form-control" id="number" name="form_room[NUMBER]" step="1"
                   value="<?= $this->data['form_room']['NUMBER'] ?? '' ?>" placeholder="Введите номер помещения">
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Наименование <span class="redStars">*</span></label>
            <input type="text" name="form_room[NAME]" class="form-control" id="name"
                   value="<?= $this->data['form_room']['NAME'] ?? '' ?>"
                   required placeholder="Введите наименование помещения">
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Тип <span class="redStars">*</span></label>
            <select class="form-control" id="spec" name="form_room[SPEC]" required>
                <option disabled>Выберите тип помещения</option>
                <option value="0" <?= isset($this->data['form_room']['SPEC']) && $this->data['form_room']['SPEC'] === 0 ? 'selected' : '' ?>>
                    Специальное
                </option>
                <option value="1" <?= isset($this->data['form_room']['SPEC']) && $this->data['form_room']['SPEC'] === 1 ? 'selected' : '' ?>>
                    Приспособленное
                </option>
            </select>
        </div>

        <div class="mb-3 select_lab_block">
            <label class="form-label mb-1" for="select_lab">Отдел <span class="redStars">*</span></label>
            <?php if ( !empty($this->data['labs']) ): ?>
                <select class="form-control" id="select_lab" name="form_room[LAB_ID]" required>
                    <option value="" style="color: #878787">Выберите отдел</option>
                    <?php foreach ($this->data['labs'] as $lab): ?>
                        <option value="<?= $lab['ID'] ?>" <?= $this->data['lab_id'] == $lab['ID'] ? 'selected' : '' ?>><?= $lab['NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <div>Отсутствуют отделы, создайте отдел для привязки помещений к отделам</div>
            <?php endif; ?>
        </div>


        <div class="mb-3">
            <label class="form-label mb-1">Назначение <span class="redStars">*</span></label>
            <textarea name="form_room[PURPOSE]" class="form-control" id="purpose" placeholder="Назначение помещения"
                      required><?= $this->data['form_room']['PURPOSE'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Площадь м<sup>2</sup> <span class="redStars">*</span></label>
            <input type="number" class="form-control" id="area" name="form_room[AREA]" step="any" placeholder="Площадь помещения"
                   value="<?= $this->data['form_room']['AREA'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Контролируемые параметры <span class="redStars">*</span></label>
            <textarea name="form_room[PARAMS]" class="form-control" id="params" placeholder="Контролируемые параметры помещения"
                      required><?= $this->data['form_room']['PARAMS'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Хранимое оборудование </label>
            <select class="form-control select2" id="equipment_storaged" name="form_room[equipment_storaged][]" multiple>
            </select>
        </div>

<!--        <div class="mb-3">-->
<!--            <label class="form-label mb-1">Используемое оборудование <span class="redStars">*</span></label>-->
<!--            <select class="form-control select2" id="equipment_operating" name="form_room[equipment_operating][]" required multiple>-->
<!--            </select>-->
<!--        </div>-->

        <div class="mb-3">
            <label class="form-label mb-1">Право собственности <span class="redStars">*</span></label>
            <textarea name="form_room[DOCS]" class="form-control" id="docs" placeholder="Право собственности помещения"
                      required><?= $this->data['form_room']['DOCS'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Местонахождение <span class="redStars">*</span></label>
            <textarea name="form_room[PLACEMENT]" class="form-control" id="placement" placeholder="Место нахождения помещения"
                      required><?= $this->data['form_room']['PLACEMENT'] ?? '' ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label mb-1">Примечание</label>
            <textarea name="form_room[COMMENT]" placeholder="Примечание помещения"
                      class="form-control" id="comment"><?= $this->data['form_room']['COMMENT'] ?? '' ?></textarea>
        </div>

        <div class="line-dashed-small"></div>

        <button type="submit" class="btn btn-primary form-button">Сохранить</button>
    </form>
    <!--./room-modal-form-->

    <div id="alert_modal" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
        <div class="title mb-3 h-2 alert-title"></div>

        <div class="line-dashed-small"></div>

        <div class="mb-3 alert-content"></div>
    </div>
    <!--./alert_modal-->
</div>
