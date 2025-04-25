<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-1">
                <a class="nav-link" href="<?=URI?>/nk/graduation/" title="Добавить лист измерений градуировочной зависимости">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>


<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start bg-transparent"
                   value="" placeholder="Введите дату начала:">
        </div>
        <div class="col">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end bg-transparent"
                   value="" placeholder="Введите дату окончания:">
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>


<table id="graduationJournal" class="table table-striped journal text-center">
    <thead>
    <tr class="table-light align-middle">
        <th scope="col">№</th>
        <th scope="col">Объект строительства</th>
        <th scope="col">Дата</th>
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
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>