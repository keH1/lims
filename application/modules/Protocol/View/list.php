<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start" value="" placeholder="Введите дату начала:">
        </div>

        <div class="col">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end" value="" placeholder="Введите дату окончания:">
        </div>

        <div class="col">
            <select id="selectLab" class="form-control filter filter-lab">
                <option value='0' selected>Bсе лаборатории</option>
                <?php if ($this->data['lab']): ?>
                    <?php foreach ($this->data['lab'] as $lab): ?>
                        <option value="<?= $lab['ID'] ?? '' ?>"><?= $lab['NAME'] ?? '' ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>

<table id="journal_protocol" class="table table-striped journal">
    <thead>
    <tr class="table-light">
        <th scope="col"></th>
        <th scope="col" class="text-nowrap">ОА</th>
        <th scope="col" class="text-nowrap">Протокол</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Клиент</th>
        <th scope="col" class="text-nowrap">Объект исп.</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
        <th scope="col" class="text-nowrap">Заявка</th>
        <th scope="col" class="text-nowrap">ТЗ</th>
        <th scope="col" class="text-nowrap">Рез-ты исп.</th>
        <th scope="col" class="text-nowrap">DOC/PDF</th>
        <th scope="col" class="text-nowrap">Последнее изменение</th>
    </tr>
    <tr class="header-search">
        <th scope="col"></th>
        <th scope="col"></th>
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
        </th>
        <th scope="col">
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
        <use xlink:href="<?=URI?>/assets/images/icons.svg#arrow-left"/>
    </svg>
</div>
<div class='arrowRight'>
    <svg class="bi" width="40" height="40">
        <use xlink:href="<?=URI?>/assets/images/icons.svg#arrow-right"/>
    </svg>
</div>

<div class="line-dashed"></div>
