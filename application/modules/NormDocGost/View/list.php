<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/normDocGost/new/" title="Новый ГОСТ">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>

<table id="journal_gost" class="table table-striped journal">
    <thead>
    <tr class="table-light">
        <th scope="col"></th>
        <th scope="col" class="text-nowrap">Номер документа</th>
        <th scope="col" class="text-nowrap">Наименование документа</th>
        <th scope="col" class="text-nowrap">Год</th>
        <th scope="col" class="text-nowrap">Наименование объекта</th>
        <th scope="col">Определяемая характеристика / показатель</th>
        <th scope="col" class="text-nowrap">Пункт документа</th>
    </tr>
    <tr class="header-search">
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
