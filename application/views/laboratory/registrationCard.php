<header class="header-secondment mb-4">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link link-back" href="<?= URI ?>/laboratory/registrationList/" title="Вернуться назад">
                    <svg class="icon" width="25" height="25">
                        <use xlink:href="<?= URI ?>/assets/images/icons.svg#back"></use>
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</header>

<h3>Заявка: <?= $this->data["cardInfo"]["title"] ?></h3>
<h3>Материал: <?= $this->data["cardInfo"]["material_name"] ?></h3>
<h3>Фракция: <?= $this->data["cardInfo"]["fraction_name"] ?></h3>
<h3>Поставщик: <?= $this->data["cardInfo"]["manufacturer"] ?></h3>

<div class="d-flex justify-content-between mt-3 mb-3">
    <button class="btn btn-success" id="save">Сохранить</button>
<!--    <button class="btn btn-danger" id="delete">Удалить</button>-->
</div>

<div class="scroll mt-3 mb-3">
    <div class="table-wrap">

        <table id="table" class="table table-striped journal" style="min-width: 100%">
            <thead>
                <tr>
<!--                    <th scope="col"></th>-->
<!--                    <th scope="col"></th>-->
<!--                    <th scope="col"></th>-->
                    <?php foreach ($this->data["table"] as $value) : ?>
                        <th scope="col" class="wd-250"><?= $value["gost"] ?> (<?= $value["gost_punkt"] ?>)</th>
                    <?php endforeach; ?>
                    <?php foreach ($this->data["gostList"] as $gost): ?>
                        <th><?= $gost["gost"] ?> <i title="Своя лабаратория" class="fa-solid fa-star text-primary"></i></th>
                    <?php endforeach; ?>
                </tr>
                <tr>
<!--                    <th scope="col">Дата</th>-->
<!--                    <th scope="col">Проба</th>-->
<!--                    <th scope="col"class="wd-200">Материал</th>-->
                    <?php foreach ($this->data["table"] as $value) : ?>
                        <th><?= $value["title"] ?> (<?= $value["bgm_ed"] ?>)</th>
                    <?php endforeach; ?>
                    <?php foreach ($this->data["gostList"] as $gost): ?>
                        <th>
                            <?= $gost["spec"] ?> <br>
                            <?= $gost["param"] ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <?php foreach ($this->data["table"] as $spec): ?>
                        <td class="text-center <?= $spec["background"] ?>"><?= $spec["value"] ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($this->data["gostList"] as $gost): ?>
                        <td class="text-center <?= $gost["background"] ?>">
                            <input
                                    data-js-tz-gost-id="<?= $gost["oz_tz_gost_id"] ?>"
                                    style="background: transparent; border: none; text-align: center"
                                    type="number"

                                    value="<?= $gost["value"] ?>">
                        </td>
                    <?php endforeach; ?>
                </tr>

            </tbody>
        </table>
    </div>
</div>