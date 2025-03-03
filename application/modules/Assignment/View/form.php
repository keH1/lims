<form class="form-horizontal request-form" method="post" action="<?= URI ?>/assignment/insertUpdate/">
	<div class="panel panel-default">
		<header class="panel-heading">
			Оборудование для контроля градуировки pH-метра
			<span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
         </span>
		</header>
		<div class="panel-body">
			<div class="form-group row">
				<label for="equipment" class="col-sm-2 col-form-label">Оборудование</label>
				<div class="col-sm-8">
					<select class="equipment-multiple" name="graduation-equipment[]" multiple="multiple">
						<option disabled>Выберите оборудование</option>
						<?php foreach ($this->data['equipment'] as $item): ?>
							<option <?= in_array($item['ID'], $this->data['selected_equipment']) ? "selected" : "" ?> value="<?= $item['ID'] ?>"><?= $item['OBJECT'] ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
        </div>
    </div>

	<button class="btn btn-primary submit-form">
		Сохранить
	</button>
</form>