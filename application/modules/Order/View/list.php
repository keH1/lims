<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start" value="<?=$this->data['date_start'] ?? '2010-01-01'?>" placeholder="Введите дату начала:">
        </div>

        <div class="col">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end" value="<?=date('Y-m-d')?>" placeholder="Введите дату окончания:">
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>

<table id="journal_order" class="table table-striped journal">
    <thead>
    <tr class="table-light">
        <th scope="col"></th>
        <th scope="col" class="text-nowrap">№</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Тип договора</th>
        <th scope="col" class="text-nowrap">Контрагент</th>
        <th scope="col" class="text-nowrap">PDF</th>
        <th scope="col" class="text-nowrap">Подписанная версия</th>
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
            <select class="form-select bg-white search">
                <option value="" selected>Все</option>
                <option value="Договор">Договор</option>
                <option value="Счет-оферта">Счет-оферта</option>
            </select>
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col"></th>
        <th scope="col">
			<select class="form-select bg-white search">
				<option value="0" selected>Не выбрано</option>
				<option value="1">Загружена</option>
				<option value="2">Не загружена</option>
			</select>
		</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
