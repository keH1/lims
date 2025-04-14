<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start" value="<?= $this->data['date_start'] ?? '' ?>" placeholder="Введите дату начала:">
        </div>

        <div class="col">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end" value="<?= date('Y-m-d') ?>" placeholder="Введите дату окончания:">
        </div>

        <div class="col">
            <select id="selectStage" class="form-control filter filter-stage">
                <option value="all" selected>Все счета</option>
                <option value="1">Счет не оплачен</option>
                <option value="2">Счет оплачен не полностью</option>
                <option value="3">Счет оплачен полностью</option>
            </select>
        </div>

        <div class="col">
            <select id="selectLab" class="form-control filter filter-lab">
                <option value='0' selected>Bсе лаборатории</option>
                <?php if ($this->data['lab']): ?>
                    <?php foreach ($this->data['lab'] as $lab): ?>
                        <option value="<?= $lab['DEPARTMENT'] ?? '' ?>"><?= $lab['NAME'] ?? '' ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>

<table id="journal_invoice" class="table table-striped journal">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col" class="text-nowrap">№</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Сумма</th>
        <th scope="col" class="text-nowrap">Контрагент</th>
        <th scope="col" class="text-nowrap">Материал</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
        <th scope="col" class="text-nowrap">Договор</th>
        <th scope="col" class="text-nowrap">Заявка</th>
        <th scope="col" class="text-nowrap">Акт ВР</th>
        <th scope="col" class="text-nowrap">Дата акта ВР</th>
        <th scope="col" class="text-nowrap">Дата отправки акта ВР</th>
    </tr>
    <tr class="header-search">
        <th scope="col"></th>
        <th scope="col">
            <input type="text" class="form-control search" style="width: 80px;">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search number-only">
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