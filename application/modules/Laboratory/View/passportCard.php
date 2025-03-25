<header class="header-secondment mb-4">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link link-back" href="/ulab/laboratory/passportJournal" title="Вернуться назад">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#back"></use>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<div class="row">
    <div class="col-6">
        <?php if (!empty($this->data["cardInfo"]["ulab_title"])): ?>
            <h3>Ulab: <?= $this->data["cardInfo"]["ulab_title"] ?></h3>
        <?php endif; ?>
        <h3>№ заказа: <?= $this->data["cardInfo"]["order_number"] ?></h3>

        <div class="d-flex align-items-center mb-1">
            <h3 style="margin-bottom: 0 !important;">№ партии:</h3>
            <input id="batch_number" class="form-control wd-100 ml-4 text-left" style="font-size: 1.75rem; padding: 0; background: transparent; border: none; font-weight: 500;" type="text" value="<?= $this->data["cardInfo"]["batch_number"] ?>">
        </div>
        <h3>Материал: <?= $this->data["cardInfo"]["material_name"] ?></h3>
        <?php if (!empty($this->data["cardInfo"]["fraction_name"])): ?>
            <h3>Фракция: <?= $this->data["cardInfo"]["fraction_name"] ?></h3>
        <?php endif; ?>

        <div class="d-flex gap-3">
            <h3>Поставщик: <?= $this->data["cardInfo"]["manufacturer"] ?></h3>
            <div>
                <div class="form-group col-sm-5 d-flex align-items-end gap-3" data-js-btn-group>

                    <?php foreach ($this->data["files"]["cert"] as $cert):  ?>
                        <div data-js-file-wrap class="position-relative" style="transition: 0.3s">
                            <a
                                    class="btn btn-primary position-relative rounded ml-4 fs-16"
                                    href="/laboratory/upload/lab/passportCert/<?= $this->data["cardInfo"]["id"] ?>/<?= $cert ?>"
                                    
                                    title="<?= $cert ?>"
                                    data-js-file-download

                            ><i class="fa-solid fa-file"></i></a>
                            <button
                                    data-js-delete-file="/laboratory/upload/lab/passportCert/<?= $this->data["cardInfo"]["id"] ?>/<?= $cert ?>"
                                    type="button" class="position-absolute btn-xmark fa-solid fa-xmark">
                            </button>
                        </div>
                    <?php endforeach; ?>

<!--                    <div data-js-upload-wrap class="position-relative" style="transition: 0.3s">-->
<!--                        <label class="p-0 text-center" title="Загрузить сертификат">-->
<!--                            <div class="btn btn-primary position-relative rounded fs-16">-->
<!--                                <i class="fa-solid fa-plus"></i>-->
<!--                                <span data-js-input-count-->
<!--                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger rounded"-->
<!--                                      style="z-index: 100; display: none"-->
<!--                                >-->
<!--                      </span>-->
<!--                            </div>-->
<!--                            <input-->
<!--                                    multiple-->
<!--                                    class="form-control d-none"-->
<!--                                    type="file"-->
<!--                                    id="cert"-->
<!--                                    name="cert[]"-->
<!--                                    data-js-upload-->
<!--                            >-->
<!--                        </label>-->
<!--                    </div>-->

                </div>
            </div>
        </div>
        <?php if (!empty($this->data["composition"]["code"])): ?>
            <h3>№ состава: <?= $this->data["composition"]["code"] ?></h3>
        <?php endif; ?>

        <?php if (!empty($this->data["cardInfo"]["day_to_test"]) && $this->data["cardInfo"]["deadline"]): ?>
            <h3 class="<?= $this->data["background"] ?>">Срок до: <?= $this->data["cardInfo"]["deadline"] ?></h3>
        <?php endif; ?>
    </div>
    <?php if ($this->data["cardInfo"]["deal_id"]): ?>
        <div class="col-6">
            <input hidden type="number" id="ulab_comment_id" value="<?= $this->data["cardInfo"]["comment_id"] ?>">
            <input hidden type="number" id="deal_id" value="<?= $this->data["cardInfo"]["deal_id"] ?>">
            <div class="form-group">
                <label for="ulab_comment" class="mb-2 fw-bold">Комментарий</label>
                <textarea class="form-control" id="ulab_comment" rows="8"><?= $this->data["cardInfo"]["comment_text"] ?? "" ?></textarea>
            </div>
        </div>
    <?php endif; ?>

</div>

<input type="number" hidden id="tz_id" value="<?= $this->data["cardInfo"]["id"] ?>">
<input type="number" hidden id="ba_tz_id" value="<?= $this->data["cardInfo"]["ba_tz_id"] ?>">
<input type="text"  hidden id="file_delete">

<div class="d-flex justify-content-between mt-3 mb-3">
    <div class="d-flex gap-3">
        <button class="btn btn-success" id="save">Сохранить</button>
        <a  href="<?= URI ?>/laboratory/dashboard/<?= $this->data["cardInfo"]["scheme_id"] ?>/" class="btn btn-primary popup-with-form mw-100 mt-0 ml-4">
            Все испытания
        </a>
    </div>


    <?php if ($this->data["is_admin"]): ?>
        <button class="btn btn-danger" id="delete-btn">Удалить</button>
    <?php endif; ?>
</div>

<div class="scroll mt-3 mb-3">
    <div class="table-wrap">

        <table id="table" class="table table-striped journal" style="min-width: 100%">
            <thead>
            <tr>
                <!--                    <th scope="col"></th>-->
                <!--                    <th scope="col"></th>-->
                <!--                    <th scope="col"></th>-->
                <th>Гост</th>
                <th>Характеристика</th>
                <th>От</th>
                <th>До</th>
                <th>Значение</th>

            </tr>
            </thead>
            <tbody>

            <?php foreach ($this->data["ulabGost"] as $gost) : ?>
                <tr>
                    <td class="text-center"><?= $gost["gost"] ?> (<?= $gost["gost_punkt"] ?>)</td>
                    <td class="text-center"><?= $gost["title"] ?> (<?= $gost["unit_char"] ?>)</td>
                    <td class="text-center"><?= $gost["range_from"] ?></td>
                    <td class="text-center"><?= $gost["range_before"] ?></td>
<!--                    <td class="text-center --><?//= $gost["background"] ?><!--">--><?//= $gost["value"] ?><!--</td>-->
                    <td class="text-center <?= $gost["background"] ?>"><?= $gost["actual_value"] ?></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach ($this->data["ozGost"] as $gost): ?>
                <tr>
                    <td class="text-center"><?= $gost["gost"] ?> <i title="Своя лабаратория" class="fa-solid fa-star text-primary"></i></td>
                    <td class="text-center">
                        <?= $gost["spec"] ?> <br>
                        <?= $gost["param"] ?>
                    </td>
                    <td class="text-center"><?= $gost["range_from"] ?></td>
                    <td class="text-center"><?= $gost["range_before"] ?></td>
                    <td class="text-center <?= $gost["background"] ?>">
                        <input
                            data-js-tz-gost-id="<?= $gost["oz_tz_gost_id"] ?>"
                            style="background: transparent; border: none; text-align: center; width: 100%;"
                            type="number"

                            value="<?= $gost["value"] ?>">
                    </td>
                </tr>
            <?php endforeach; ?>


            </tbody>
        </table>
    </div>
</div>

<form id="delete-modal" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Удалить заявку?
    </div>
    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="delete" class="btn btn-danger">Удалить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>
</form>