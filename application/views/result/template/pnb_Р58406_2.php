<!-- Асфальтобетон ГОСТ Р 58406.2 Пустоты наполненные битумным вяжущим (ПНБ) -->
<div class="measurement-wrapper" id="form_pnbWrapper">
    <h3 class="mb-3">Пустоты наполненные битумным вяжущим (ПНБ)</h3>

    <div id="form_pnb" class="form_pnb mb-3">
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info">
                <th scope="col">ПМЗ, %</th>
                <th scope="col">Содержание пустот, %</th>
                <th scope="col">ПНБ, %</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="number" id="pmz" class="form-control col-sm-10 change-trigger-pnb" name="form_data[<?=$this->data['ugtp_id']?>][form][pmz]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['pmz'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="pa" class="form-control col-sm-10 change-trigger-pnb" name="form_data[<?=$this->data['ugtp_id']?>][form][pa]" min="0" step="0.01" value="<?=$this->data['measuring']['form']['pa'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="pnb_result" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" step="0.001" value="<?=$this->data['measuring']['result_value'] ?? ''?>">
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="pnb_calculate" class="btn btn-primary">Рассчитать</button>
            </div>
			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
			</div>
        </div>
    </div>

</div>
