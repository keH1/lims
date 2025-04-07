<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Добавить замер
            </button>
        </div>
        <div class="col">
            <select id="inputIdWhichFilter" class="form-control h-auto filter">
                <?php
                foreach ($this->data['roomPlusAll'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <input type="month" id="inputDateStart" class="form-control filter filter-date-start"
                   value="<?= date("Y") . '-01' ?>" title="Введите дату начала">
        </div>
        <div class="col-auto">
            <input type="month" id="inputDateEnd" class="form-control filter filter-date-end"
                   value="<?= date("Y") . '-12' ?>" title="Введите дату окончания">
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset" title="Сбросить фильтр">Сбросить
            </button>
        </div>

		<div class="col-auto">
			<a class="nav-link auto-fill " href="#" title="Автозаполнение" style="color: black;">
				<i class="fa-solid fa-gauge-high icon-big"></i>
			</a>
		</div>
    </div>
</div>

<!--./filters-->

<table id="main_table" class="table table-striped text-center">
    <thead>
    <tr class="table-light align-middle">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col">Дата</th>
        <th scope="col">Помещение</th>
        <th scope="col">Напряжение сети (UA), В</th>
        <th scope="col">Требуемые диапазоны (UA), В</th>
        <th scope="col">Напряжение сети (UВ), В</th>
        <th scope="col">Требуемые диапазоны (UВ), В</th>
        <th scope="col">Напряжение сети (UС), В</th>
        <th scope="col">Требуемые диапазоны (UС), В</th>
        <th scope="col">Частота тока, Гц</th>
        <th scope="col">Требуемые диапазоны частоты тока, Гц</th>
        <th scope="col">Вывод</th>
        <th scope="col">Ответственный</th>
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
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
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

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/electric/addMeasurement/" method="post">
    <div class="title mb-3 h-2">
        Рецепт
    </div>
    <div class="line-dashed-small"></div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Дата замера</label>
            <input name="toSQL[electric_control][date]" type="date" class="form-control"
                   value="<?= $this->data['current_date'] ?>" placeholder="Дата замера">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Помещение</label>
            <select name="toSQL[electric_control][id_room]" class="form-control bg-white" required>
                <option value="" selected disabled></option>
                <?php foreach ($this->data['room'] as $val): ?>
                    <option value="<?= $val['id'] ?? '' ?>"><?= $val['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Напряжение сети (UA), В </label>
            <input type="number" name="toSQL[electric_control][voltage_UA]" step="0.1" min="0" max="10000"
                   class="form-control bg-white"
                   value="220" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Напряжение сети (UB), В </label>
            <input type="number" name="toSQL[electric_control][voltage_UB]" step="0.1" min="0" max="10000"
                   class="form-control bg-white"
                   value="220" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Напряжение сети (UC), В </label>
            <input type="number" name="toSQL[electric_control][voltage_UC]" step="0.1" min="0" max="10000"
                   class="form-control bg-white"
                   value="220" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Частота тока, Гц</label>
            <input type="number" name="toSQL[electric_control][frequency]" step="0.1" min="0" max="10000"
                   class="form-control bg-white"
                   value="50" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="auto-fill" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative auto-fill-form"
	  action="/ulab/electric/autoFill/" method="post">

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

	<div class="mb-3">
		<div class="form-check">
			<input class="form-check-input" type="checkbox" value="1" id="holidayCheck" name="formAutoFill[holyday]">
			<label class="form-check-label" for="holidayCheck">
				Включая выходные дни
			</label>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<div class="mb-3">
		<label class="form-label" for="date">Диапазон внесения результатов</label>
		<div class="input-group mb-3">
			<span class="input-group-text">От</span>
			<input type="float" step="any" class="form-control" name="formAutoFill[autoFrom]" value="210">
			<span class="input-group-text">До</span>
			<input type="float" step="any" class="form-control" name="formAutoFill[autoTo]" value="230">
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Заполнить</button>
</form>

<!--./add-entry-modal-form-->
