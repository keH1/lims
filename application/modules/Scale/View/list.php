<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Добавить запись
            </button>
        </div>

        <div class="col">
            <select name="" class="form-control h-auto select-scale filter">
                <option value="">Все</option>
                <?php foreach ($this->data['scale'] as $val): ?>
                    <option value="<?= $val['id']?>"><?= $val['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start" value="" title="Введите дату начала">
        </div>

        <div class="col-auto">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end" value="" title="Введите дату окончания">
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset" title="Сбросить фильтр">Сбросить</button>
        </div>
    </div>
</div>

<table id="scales_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col">Наименование оборудования, зав. номер</th>
        <th scope="col">Используемая для калибровки гиря, класс, зав. номер</th>
        <th scope="col">Номинальное значение массы гири, г</th>
        <th scope="col">Результат взвешивания калибровочной гири, г</th>
        <th scope="col">Погрешность весов во взвешиваемом диапазоне</th>
        <th scope="col">Вывод</th>
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
        <th scope="col"></th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>

    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/scale/addScaleCalibration/" method="post">
    <div class="title mb-3 h-2">
        Данные юстировки весов
    </div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Дата измерения</label>
			<input name="toSQL[scale_calibration][date_calibration]" type="date"
				   class="form-control "
				   value="<?= $this->data['current_date'] ?>"
                   max="<?= date('Y-m-d') ?>"
            >
		</div>
	</div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Выберите весы</label>
            <select name="toSQL[scale_calibration][id_scale]" class="form-control h-auto scale"
                    required>
                <option value="" selected disabled></option>
                <?php
                foreach ($this->data['scale'] as $val): ?>
                    <option value="<?= $val['id']?>"><?= $val['name']?></option>
                <?php
                endforeach; ?>
            </select>
        </div>
    </div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Погрешность весов во взвешиваемом диапазоне</label>
			<div class="input-group">
				<span class="input-group-text">&#177</span>
				<input type="number" name="toSQL[scale_calibration][scale_error]" step="0.01"
					   class="form-control bg-white scale_error">
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Выберите средство калибровки</label>
			<select name="toSQL[scale_calibration][id_weight]" class="form-control h-auto weight">
				<option value="" selected disabled></option>
				<option value="1" >Тестовое средство калибровки</option>
				<?php
				foreach ($this->data['weight'] as $val): ?>
					<option value="<?= $val['id']?>"><?= $val['name']?></option>
				<?php
				endforeach; ?>
			</select>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Номинальное значение массы гири</label>
			<div class="input-group">
				<input type="number" name="toSQL[scale_calibration][mass_weight]" step="0.01"
				class="form-control bg-white mass_weight">
				<span class="input-group-text">г</span>
			</div>
		</div>
	</div>

    <div class="row mb-3">
        <div class="col">
            <label class="form-label">Результат взвешивания калибровочной гири</label>
            <div class="input-group">
                <input type="number" name="toSQL[scale_calibration][weight_result]" step="0.0001" min="0"
                       class="form-control bg-white" required>
                <span class="input-group-text">г</span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<!-- <form id="auto-fill" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative auto-fill-form"
	  action="/ulab/scale/autoFill/" method="post">

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
				   value="<?//= date('Y-m-d H:i') ?>"
				   required>
		</div>

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Заполнить</button>
</form> -->