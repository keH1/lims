<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Зарегистрировать температуру
            </button>
        </div>

        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter">
                <?php foreach ($this->data['fridgePlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?? '' ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start"
                   value="" title="Введите дату начала">
        </div>
        <div class="col-auto">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end"
                   value="" title="Введите дату окончания">
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset" title="Сбросить фильтр">Сбросить</button>
        </div>

		<div class="col-auto">
			<a class="nav-link auto-fill " href="#" title="Автозаполнение" style="color: black;">
				<i class="fa-solid fa-gauge-high icon-big"></i>
			</a>
		</div>
    </div>
</div>

<table id="fridgecontrol_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col" class="text-nowrap">Дата и время</th>
        <th scope="col" class="text-nowrap">Холодильник</th>
        <th scope="col" class="text-nowrap">Температура, °C</th>
        <th scope="col" class="text-nowrap">Диапазон температур</th>
        <th scope="col" class="text-nowrap">Вывод</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
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

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/fridgecontrol/addFridgeControl/" method="post">
    <div class="title mb-3 h-2">
        Температура в холодильнике
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Выберите холодильник </label>
            <select name="fridge_control[id_fridge]" class="form-control  h-auto"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['fridge'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?? '' ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата</label>
            <input name="fridge_control[date_time]" type="datetime-local"
                   class="form-control "
                   value="<?= $this->data['current_date'] ?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Количество</label>
            <div class="input-group">
                <input type="number" name="fridge_control[temperature]" step="0.01" min="-100" max="100"
                       class="form-control bg-white" required>
                <span class="input-group-text">°C</span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="auto-fill" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative auto-fill-form"
	  action="/ulab/fridgecontrol/autoFill/" method="post">

	<div class="title mb-3 h-2">
		Параметры автозаполнения
	</div>

	<div class="line-dashed-small"></div>

	<div class="mb-3">
		<label class="form-label" for="date">Дата начала заполнения</label>
		<input type="datetime-local" class="form-control w-100" id="dateFrom" name="formAutoFill[dateFrom]" step="any"
			   value=""
			   required>
	</div>

	<div class="mb-3">
		<label class="form-label" for="date">Дата окончания заполнения</label>
		<input type="datetime-local" class="form-control w-100" id="dateTo" name="formAutoFill[dateTo]" step="any"
			   value="<?= date('Y-m-d H:i') ?>"
			   required>
	</div>

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Заполнить</button>
</form>

