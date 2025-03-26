<div class="fs-14">
    <div>
        <a class="d-block w-max-content"  href="<?= URI ?>/laboratory/schemeCard/<?= $this->data["scheme_id"] ?>/"><h2>Материал: <?= $this->data["scheme"]["material_name"] ?></h2></a>
    </div>

    <div class="timeline-wrap">
        <div class="timeline-group d-flex" style="height: 40px">
            <?php foreach ($this->data["scheme_list"] as $scheme): ?>
                <a class="timeline-elem <?= $scheme["id"] === $this->data["scheme_id"] ? "selected-date" : "" ?>" href="<?= URI ?>/laboratory/dashboard/<?= $scheme["id"] ?>/"><?= $scheme["name"] ?></a>
            <?php endforeach; ?>
            <button type="button" class="btn d-none" id="hide-row" visible="0">
                <i class="fa-solid fa-eye-slash"></i>
            </button>
        </div>
    </div>

    <input type="number" id="scheme_id" value="<?= $this->data["scheme_id"] ?>" hidden>

    <div class="table-wrap">
        <table class="table table-striped journal" id="table">
            <thead>
            <tr class="table-light">
                <th scope="col" rowspan="2" class="wd-40 text-center">#</th>
                <th scope="col" rowspan="2" class="wd-500 text-center">Характеристика</th>
                <th scope="col" rowspan="2" class="wd-400 text-center">Параметр</th>
                <th scope="col" rowspan="2" class="wd-60 text-center">От</th>
                <th scope="col" rowspan="2" class="wd-60 text-center">До</th>
                <?php foreach ($this->data["test_list"] as $item): ?>
                    <th scope="col" class="wd-100 text-center" data-js-test-id="<?= $item["id"] ?>">
                        <a  href="<?= URI ?>/laboratory/<?= $this->data["type"] ?>/<?= $item["id"] ?>/"><?= DateHelper::setPointFormat($item["created_date"]) ?></a>
                    </th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($this->data["test_list"] as $item): ?>
                    <th scope="col" class="wd-100 text-center cell-overflow" title="<?=  $item["batch_number"] ?>">
                        <?=  $item["batch_number"] ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            </thead>
        </table>
    </div>
</div>

<div class="btn-group-toolbar">
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