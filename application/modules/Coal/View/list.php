<div class="filters mb-4">
    <div class="row">
        <div class="col-auto">
            <button type="button" name="add_entry"
                    class="btn btn-primary popup-first btn-add-entry w-100 mw-100 m-0 btn-reactive">
                Добавить запись
            </button>
        </div>

		<div class="col-auto">
			<button type="button" name="add_entry"
					class="btn btn-primary popup-two btn-add-entry w-100 mw-100 m-0 btn-reactive">
				Добавить измерения с пустым БДБ-13
			</button>
		</div>

		<div class="col-auto">
			<button type="button" name="add_entry"
					class="btn btn-primary popup-three btn-add-entry w-100 mw-100 m-0 btn-reactive">
				Добавить измерения с загруженным БДБ-13
			</button>
		</div>
    </div>
</div>

<table id="coal_journal" class="table table-striped text-center table-bordered">
    <thead>
    <tr class="table-light">
        <th scope="col" rowspan="4"></th>
        <th scope="col" rowspan="4">Дата и время окончания регенерации</th>
		<th scope="col" rowspan="4" class="text-nowrap">№ БДБ-13</th>
        <th scope="col" rowspan="4">Дата и время измерения</th>
        <th scope="col" colspan="10">С пустым БДБ-13</th>
		<th scope="col" rowspan="4">Дата и время измерения</th>
		<th scope="col" colspan="10">С загруженным БДБ-13</th>
		<th scope="col" rowspan="4" class="text-nowrap">A_bф, Бк</th>
		<th scope="col" rowspan="4" class="text-nowrap">Требуемый диапазон</th>
		<th scope="col" rowspan="4">Соответствует/не соответствует</th>
		<th scope="col" rowspan="4">Подпись ответственного лица</th>
    </tr>
    <tr class="table-light">
        <th scope="col" colspan="3">Кол-во импульсов</th>
        <th scope="col" colspan="3">t, изм, с </th>
        <th scope="col" colspan="3">Скорость счета импульсов, c&#713&sup1</th>
        <th scope="col" rowspan="3">Среднее значение скорости счета импульсов, B_b, c&#713&sup1</th>
		<th scope="col" colspan="3">Кол-во импульсов</th>
		<th scope="col" colspan="3">t, изм, с </th>
		<th scope="col" colspan="3">Скорость счета импульсов, с<sup>-1</sup></th>
		<th scope="col" rowspan="3">Среднее значение скорости счета импульсов (загр.), N_b, c&#713&sup1</th>
    </tr>
	<tr>
		<th scope="col" colspan="9">Измерение</th>
		<th scope="col" colspan="9">Измерение</th>
	</tr>
	<tr>
		<th scope="col">1</th>
		<th scope="col">2</th>
		<th scope="col">3</th>
		<th scope="col">1</th>
		<th scope="col">2</th>
		<th scope="col">3</th>
		<th scope="col">1</th>
		<th scope="col">2</th>
		<th scope="col">3</th>
		<th scope="col">1</th>
		<th scope="col">2</th>
		<th scope="col">3</th>
		<th scope="col">1</th>
		<th scope="col">2</th>
		<th scope="col">3</th>
		<th scope="col">1</th>
		<th scope="col">2</th>
		<th scope="col">3</th>
	</tr>
    </thead>
    <tbody>
    </tbody>
</table>

<!--<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"-->
<!--      action="/ulab/scale/addScaleCalibration/" method="post">-->
<!--    <div class="title mb-3 h-2">-->
<!--        Данные юстировки весов-->
<!--    </div>-->
<!---->
<!--	<div class="row mb-3">-->
<!--		<div class="col">-->
<!--			<label class="form-label">Дата измерения</label>-->
<!--			<input name="toSQL[scale_calibration][date_calibration]" type="date"-->
<!--				   class="form-control "-->
<!--				   value="--><?//= $this->data['current_date'] ?><!--">-->
<!--		</div>-->
<!--	</div>-->
<!---->
<!--    <div class="row mb-3">-->
<!--        <div class="col">-->
<!--            <label class="form-label">Выберите весы</label>-->
<!--            <select name="toSQL[scale_calibration][id_scale]" class="form-control h-auto scale"-->
<!--                    required>-->
<!--                <option value="" selected disabled></option>-->
<!--                --><?php
//                foreach ($this->data['scale'] as $val): ?>
<!--                    <option value="--><?//= $val['id']?><!--">--><?//= $val['name']?><!--</option>-->
<!--                --><?php
//                endforeach; ?>
<!--            </select>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--	<div class="row mb-3">-->
<!--		<div class="col">-->
<!--			<label class="form-label">Погрешность весов в взвешиваемом диапазоне</label>-->
<!--			<div class="input-group">-->
<!--				<span class="input-group-text">&#177</span>-->
<!--				<input type="number" name="toSQL[scale_calibration][scale_error]" step="0.01"-->
<!--					   class="form-control bg-white scale_error" readonly>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!---->
<!--	<div class="row mb-3">-->
<!--		<div class="col">-->
<!--			<label class="form-label">Выберите средство калибровки</label>-->
<!--			<select name="toSQL[scale_calibration][id_weight]" class="form-control h-auto weight"-->
<!--					required>-->
<!--				<option value="" selected disabled></option>-->
<!--				--><?php
//				foreach ($this->data['weight'] as $val): ?>
<!--					<option value="--><?//= $val['id']?><!--">--><?//= $val['name']?><!--</option>-->
<!--				--><?php
//				endforeach; ?>
<!--			</select>-->
<!--		</div>-->
<!--	</div>-->
<!---->
<!--	<div class="row mb-3">-->
<!--		<div class="col">-->
<!--			<label class="form-label">Номинальное значение массы гири</label>-->
<!--			<div class="input-group">-->
<!--				<input type="number" name="toSQL[scale_calibration][mass_weight]" step="0.01"-->
<!--				class="form-control bg-white mass_weight" readonly>-->
<!--				<span class="input-group-text">г</span>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!---->
<!--    <div class="row mb-3">-->
<!--        <div class="col">-->
<!--            <label class="form-label">Результат взвешивания калибровочной гири</label>-->
<!--            <div class="input-group">-->
<!--                <input type="number" name="toSQL[scale_calibration][weight_result]" step="0.01" min="0"-->
<!--                       class="form-control bg-white" required>-->
<!--                <span class="input-group-text">г</span>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <button type="submit" class="btn btn-primary">Отправить</button>-->
<!--</form>-->

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
	  action="/ulab/coal/addCoalEndRegeneration/" method="post">
	<div class="title mb-3 h-2">
		Добавить запись
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Дата и время окончания регенерации активированного угля</label>
			<input name="toSQL[coal_regeneration][date_regeneration_end]" type="datetime-local"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-two" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
	  action="/ulab/coal/addEmptyBdb/" method="post">
	<div class="title mb-3 h-2">
		Добавить запись
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Дата и время окончания регенерации</label>
			<select name="toSQL[empty_bdb][id_cr]" class="form-control h-auto"
					required>
				<option value="" selected disabled>Выберите</option>
				<?php foreach ( $this->data['coal_empty'] as $val):?>
					<option value="<?=$val['id']?>"><?=$val['date_regeneration_end']?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Дата и время измерения</label>
			<input name="toSQL[empty_bdb][date_test]" type="datetime-local" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Выберите тип БДБ-13</label>
			<select name="toSQL[empty_bdb][type_bdb]" class="form-control h-auto"
					required>
				<option value="" selected disabled>Выберите</option>
				<option value="1033">1033</option>
				<option value="1034">1034</option>
				<option value="1035">1035</option>
				<option value="1036">1036</option>
				<option value="976">976</option>
				<option value="975">975</option>
				<option value="654">654</option>
				<option value="653">653</option>
			</select>
		</div>
	</div>

	<div class="line-dashed-small"></div>
	<h5 class="text-center">Измерение кол-ва импульсов</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<label class="form-label"><strong>1</strong></label>
			<input name="toSQL[empty_bdb][impuls_1]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>2</strong></label>
			<input name="toSQL[empty_bdb][impuls_2]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>3</strong></label>
			<input name="toSQL[empty_bdb][impuls_3]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">Измерение t, изм, с</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<label class="form-label text-center"><strong>1</strong></label>
			<input name="toSQL[empty_bdb][t_1]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>2</strong></label>
			<input name="toSQL[empty_bdb][t_2]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>3</strong></label>
			<input name="toSQL[empty_bdb][t_3]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">Измерение Скорость счета импульсов, c&#713&sup1</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<label class="form-label text-center"><strong>1</strong></label>
			<input name="toSQL[empty_bdb][speed_1]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>2</strong></label>
			<input name="toSQL[empty_bdb][speed_2]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>3</strong></label>
			<input name="toSQL[empty_bdb][speed_3]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">Среднее значение скорости счета импульсов, B_b, c&#713&sup1</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<input name="toSQL[empty_bdb][average]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Отправить</button>
</form>

<form id="add-entry-modal-form-three" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
	  action="/ulab/coal/addFullBdb/" method="post">
	<div class="title mb-3 h-2">
		Добавить запись
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Дата и время окончания регенерации</label>
			<select name="toSQL[full_bdb][id_cr]" class="form-control h-auto"
					required>
				<option value="" selected disabled>Выберите</option>
				<?php foreach ( $this->data['coal_full'] as $val):?>
					<option value="<?=$val['id']?>"><?=$val['date_regeneration_end']?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col">
			<label class="form-label">Дата и время измерения</label>
			<input name="toSQL[full_bdb][date_test]" type="datetime-local" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>
	<h5 class="text-center">Измерение кол-ва импульсов</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<label class="form-label"><strong>1</strong></label>
			<input name="toSQL[full_bdb][impuls_1]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>2</strong></label>
			<input name="toSQL[full_bdb][impuls_2]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>3</strong></label>
			<input name="toSQL[full_bdb][impuls_3]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">Измерение t, изм, с</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<label class="form-label text-center"><strong>1</strong></label>
			<input name="toSQL[full_bdb][t_1]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>2</strong></label>
			<input name="toSQL[full_bdb][t_2]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>3</strong></label>
			<input name="toSQL[full_bdb][t_3]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">Измерение Скорость счета импульсов, c&#713&sup1</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<label class="form-label text-center"><strong>1</strong></label>
			<input name="toSQL[full_bdb][speed_1]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>2</strong></label>
			<input name="toSQL[full_bdb][speed_2]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
		<div class="col text-center">
			<label class="form-label text-center"><strong>3</strong></label>
			<input name="toSQL[full_bdb][speed_3]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">Среднее значение скорости счета импульсов (загр.), N_b, c&#713&sup1</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<input name="toSQL[full_bdb][average]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<h5 class="text-center">A_bф, Бк</h5>
	<div class="row mb-3">
		<div class="col text-center">
			<input name="toSQL[full_bdb][A_b]" type="number" step="0.001" class="bg-white form-control"
				   class="form-control"
				   value="" required>
		</div>
	</div>

	<div class="line-dashed-small"></div>

	<button type="submit" class="btn btn-primary">Отправить</button>
</form>


