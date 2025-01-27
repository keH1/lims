<div class="filters mb-4">
    <div class="row">
<!--        <div class="col-auto filter-search" id="filter_search">-->
<!--            <button class="filter-btn-search">-->
<!--                <svg class="bi" width="20" height="20">-->
<!--                    <use xlink:href="--><?//=URI?><!--/assets/images/icons.svg#icon-search"/>-->
<!--                </svg>-->
<!--            </button>-->
<!--            <div id="journal_filter" class="dataTables_filter">-->
<!--                <label>-->
<!--                    <input id="filter_everywhere" type="search" class="form-control filter" placeholder="Поиск..." aria-controls="journal_requests">-->
<!--                </label>-->
<!--            </div>-->
<!--        </div>-->
        <div class="col">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start" value="<?=$this->data['date_start'] ?? '2010-01-01'?>" placeholder="Введите дату начала:">
        </div>

        <div class="col">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end" value="<?=date('Y-m-d')?>" placeholder="Введите дату окончания:">
        </div>

<!--        <div class="col">-->
<!--            <select id="selectStage" class="form-control filter filter-stage">-->
<!--                <option value='0' selected>Все стадии</option>-->
<!--                <option value="1">Пробы не поступили</option>-->
<!--                <option value='2'>Пробы поступили</option>-->
<!--                <option value='3'>Проводятся испытания</option>-->
<!--                <option value='4'>Испытания завершены</option>-->
<!--                <option value='5'>Заявка неуспешна</option>-->
<!--                <option value='6'>Заявка не оплачена</option>-->
<!--                <option value='7'>Заявка оплачена не полностью</option>-->
<!--                <option value='8'>По заявке переплата</option>-->
<!--                <option value='9'>Заявка оплачена полностью</option>-->
<!--                <option value='10'>Все кроме новых и неуспешных</option>-->
<!--                <option value='11'>Успешно завершенные</option>-->
<!--            </select>-->
<!--        </div>-->

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
<!--        <th scope="col" class="text-nowrap">Договор</th>-->
<!--        <th scope="col" class="text-nowrap">Стоимость</th>-->
<!--        <th scope="col" class="text-nowrap">Счет</th>-->
<!--        <th scope="col" class="text-nowrap">Дата оплаты</th>-->
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
<!--        <th scope="col">-->
<!--            <input type="text" class="form-control search">-->
<!--        </th>-->
<!--        <th scope="col">-->
<!--            <input type="text" class="form-control search">-->
<!--        </th>-->
<!--        <th scope="col">-->
<!--            <input type="text" class="form-control search">-->
<!--        </th>-->
<!--        <th scope="col">-->
<!--            <input type="text" class="form-control search">-->
<!--        </th>-->
        <th scope="col">
            <input type="text" class="form-control search" disabled>
        </th>
        <th scope="col">
            <input type="text" class="form-control search" disabled>
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

<!--<a href="/protocols_test_binding_multiple_protocols.php">Вернуться на старый дизайн</a>-->