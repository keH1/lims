<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <input type="date" id="inputDateStart"
                   class="form-control filter filter-date-start"
                   value=""
                   placeholder="Введите дату начала:">
        </div>

        <div class="col">
            <input type="date" id="inputDateEnd"
                   class="form-control filter filter-date-end"
                   value="" placeholder="Введите дату окончания:">
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>

<table id="journal_history" class="table table-striped journal">
    <thead>
        <tr class="table-light">
            <th scope="col" class="text-nowrap">Заявка</th>
            <th scope="col" class="text-nowrap">Протокол</th>
            <th scope="col" class="text-nowrap">ТЗ</th>
            <th scope="col" class="text-nowrap">Дата</th>
            <th scope="col" class="text-nowrap">Тип изменений</th>
            <th scope="col" class="text-nowrap">Пользователь</th>
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
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>