<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Добавить измерение
            </button>
        </div>

        <div class="col">
<!--            <div class="form-check" style="margin: auto;display: inline-block;vertical-align: -webkit-baseline-middle;">-->
<!--                <input class="form-check-input is-all" type="checkbox" value="" id="isAll">-->
<!--                <label class="form-check-label" for="isAll">-->
<!--                    Сокращенный анализ-->
<!--                </label>-->
<!--            </div>-->
        </div>

        <div class="col-auto">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start"
                   value="<?= date("Y") . '-01-01' ?>"title="Введите дату начала">
        </div>

        <div class="col-auto">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end"
                   value="<?= date("Y-m-d") ?>" title="Введите дату окончания">
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


<table id="main_table" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col" class="text-nowrap"></th>
        <th scope="col" class="text-nowrap">Дата замера</th>
        <th scope="col" class="text-nowrap">pH, ед.pH</th>
        <th scope="col" class="text-nowrap">УЭП,мкСм/см</th>
        <th scope="col" class="text-nowrap">Ионы аммония, мг/л</th>
        <th scope="col" class="text-nowrap">Нитрат-ионы, мг/л</th>
        <th scope="col" class="text-nowrap">Хлорид-ионы, мг/л</th>
        <th scope="col" class="text-nowrap">Алюминий, мг/л</th>
        <th scope="col" class="text-nowrap">Железо, мг/л</th>
        <th scope="col" class="text-nowrap">Кальций, мг/л</th>
        <th scope="col" class="text-nowrap">Медь, мг/л</th>
        <th scope="col" class="text-nowrap">Свинец, мг/л</th>
        <th scope="col" class="text-nowrap">Цинк, мг/л</th>
        <th scope="col" class="text-nowrap">В-ва вост. KMnO₄, мг/л</th>
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
        <use xlink:href="<?= URI ?>/assets/images/icons.svg#arrow-left"/>
    </svg>
</div>
<div class='arrowRight'>
    <svg class="bi" width="40" height="40">
        <use xlink:href="<?= URI ?>/assets/images/icons.svg#arrow-right"/>
    </svg>
</div>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/water/addAnalysis/" method="post">
    <div class="title mb-3 h-2">
        Добавьте измерение
    </div>
    <div class="row mb-3">
        <label class="form-label">Дата анализа</label>
        <div class="col">
            <input name="toSQL[water][date_check]" type="date" class="form-control"
                   value="<?= $this->data['current_date'] ?>" required>
        </div>
        <div class="col">
            <input name="check" class="form-check-input is-full" type="checkbox"
                   id="isFull" >
            <label class="form-check-label" for="isFull">
                Полный анализ
            </label>
        </div>
    </div>
    <div class="title mb-3 h-2">
        Краткий анализ
    </div>
    <div class="row mb-3">
        <div class="col">
            <label class="form-label">pH, ед</label>
            <div class="input-group">
                <input type="number" name="toSQL[water][ph]" step="0.001" min="0" max="10"
                       class="form-control bg-white" value="7" required>
                <span class="input-group-text">ед. pH</span>
            </div>
        </div>
        <div class="col">
            <label class="form-label">УЭП</label>
            <div class="input-group">
                <input type="number" name="toSQL[water][uep]" step="0.001" min="0" max="10"
                       class="form-control bg-white" value="0.1" required>
                <span class="input-group-text">мкСм/см</span>
            </div>

        </div>
    </div>
    <div id="Full" hidden="">
        <div class="title mb-3 h-2">
            Полный анализ
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Ионы аммония</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][nh4]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Нитрат-ионы</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][no3]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Сульфат-ионы</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][so4]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Хлорид-ионы</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][cl]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Алюминий</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][al]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Железо</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][fe]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Кальций</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][ca]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Медь</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][cu]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Свинец</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][pb]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Цинк</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][zn]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
            <div class="col">
                <label class="form-label">Вещества, восстанавливающих KMnO₄</label>
                <div class="input-group">
                    <input type="number" name="toSQL[water][kmno4]" step="0.01" min="0" max="10000"
                           class="form-control bg-white" value="">
                    <span class="input-group-text">мг/л</span>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>

</form>

<form id="auto-fill" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative auto-fill-form"
	  action="/ulab/water/autoFill/" method="post">

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

