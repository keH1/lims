<!-- Асфальтобетон ГОСТ Р 58401.8 Количество воздушных пустот в асфальтобетоне -->
<div class="measurement-wrapper">
    <h3 class="mb-3">Количество воздушных пустот в асфальтобетоне, %</h3>

    <div class="formA mb-3">
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info">
                <th scope="col">Объемная плотность асфальтобетона, г/см<sup>3</sup></th>
                <th scope="col">Максимальная плотность асфальтобетонной смеси, г/см<sup>3</sup></th>
                <th scope="col">Количество воздушных пустот в асфальтобетоне, %</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="number" id="g1-<?=$this->data['ugtp_id']?>" class="form-control col-sm-10 g1 change-trigger-av" name="form_data[<?=$this->data['ugtp_id']?>][form][a]" min="0" step="0.001" value="<?=$this->data['measuring']['form']['a'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="g2-<?=$this->data['ugtp_id']?>" class="form-control col-sm-10 g2 change-trigger-av" name="form_data[<?=$this->data['ugtp_id']?>][form][b]" min="0" step="0.001" value="<?=$this->data['measuring']['form']['b'] ?? ''?>">
                </td>
                <td>
                    <input type="number" id="result-<?=$this->data['ugtp_id']?>" class="form-control result change-trigger-av"  min="0" step="0.001" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" value="<?=$this->data['measuring']['result_value'] ?? ''?>">
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="calculate-<?=$this->data['ugtp_id']?>" class="calculate btn btn-primary" name="calculate">Рассчитать</button>
            </div>

			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
			</div>
        </div>
    </div>

</div>
