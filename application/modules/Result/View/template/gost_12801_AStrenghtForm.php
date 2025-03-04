<div class="measurement-wrapper">
	<b>Определение предела прочности при сжатии ГОСТ 12801-98</b><br>
	<b><?=$this->data['probe']['cipher']?></b>
	<div class="row">
		<div class="col-lg-10">
			<p>(3 параллельных испытания; результат - округленное до первого десятичного знака
				среднеарифметическое значение трех определений)</p>
		</div>
	</div>
	<div class="AStrenghtForm">
		<table id="form-f-4" class="table table-bordered numerable">
			<thead>
			<tr role="row">
				<th>Номер определения</th>
				<th>P - разрушающая нагрузка, Н;</th>
				<th>F - первоначальная площадь поперечного сечения образца, см;</th>
				<th>R<sub>сж</sub> Предел прочности при сжатии, МПа</th>
				<th>R<sub>сж ср</sub> Предел прочности при сжатии, МПа</th>
			</tr>
			</thead>
			<tbody>
				<?php if($this->data['measuring_property'] == 1565):?>
					<?php $j = 0?>
					<tr>
						<td colspan="5"><h4>0° C</h4></td>
					</tr>
				<?php elseif ($this->data['measuring_property'] == 2057):?>
					<?php $j = 20?>
					<tr>
						<td colspan="5"><h4>20° C</h4></td>
					</tr>
				<?php elseif($this->data['measuring_property'] == 1414):?>
					<?php $j = 50?>
					<tr>
						<td colspan="5"><h4>50° C</h4></td>
					</tr>
				<?php endif?>
				<?php for ($i = 0, $k = 1; $i < 3; $i++, $k++): ?>
				<tr>
					<td class="num_table"><?=$this->data['probe']['cipher_number']?>.<?=$k?></td>
					<td>
						<input type='number' step="any" min="0"
							   class="form-control list-input breaking_load_<?=$k?> change-trigger-asf" name="form_data[<?=$this->data['ugtp_id']?>][form][P<?=$j?>][]"
							   value="<?=$this->data['measuring']['form']['P'.$j][$i] ?? ''?>">
					</td>
					<td>
						<input type='number' step="any" min="0"
							   class="form-control list-input square_<?=$k?> change-trigger-asf" name="form_data[<?=$this->data['ugtp_id']?>][form][S<?=$j?>][]"
							   value="<?=$this->data['measuring']['form']['S'.$j][$i] ?? ''?>">
					</td>
					<td>
						<input type='number' step="any"
							   class="form-control list-input Strength_<?=$k?> change-trigger-asf"
							   name="form_data[<?=$this->data['ugtp_id']?>][form][Str<?=$j?>][]"
							   value="<?=$this->data['measuring']['form']['Str'.$j][$i] ?? ''?>">
					</td>
					<?php if ($i === 0): ?>
					<td rowspan="3" class="align-middle">
						<input readonly type='number' step="any"
							   class="form-control list-input Average_Strength" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
							   value="<?= $this->data['measuring']['result_value'] ?? '' ?>">
					</td>
					<?php endif; ?>
					<?php if ($i === 3): ?>
					<td rowspan="3" class="align-middle">
						<input readonly type='number' step="any"
							   class="form-control list-input Average_Strength<?=$j?>" name="form_data[<?=$this->data['ugtp_id']?>][result_value][<?=$j?>]"
							   value="<?= $this->data['measuring']['result_value'][$j] ?? '' ?>">
					</td>
					<?php endif; ?>
					<?php if ($i === 6): ?>
					<td rowspan="3" class="align-middle">
						<input readonly type='number' step="any"
							   class="form-control list-input Average_Strength<?=$j?>" name="form_data[<?=$this->data['ugtp_id']?>][result_value][<?=$j?>]"
							   value="<?= $this->data['measuring']['result_value'][$j] ?? '' ?>">
					</td>
					<?php endif; ?>
				</tr>
			<?php endfor;?>
<!--			--><?php //$i++ ?>
<!--			<tr>-->
<!--				<td class="num_table">2</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_2"-->
<!--						   class="form-control list-input" name="P0[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P0'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_2"-->
<!--						   class="form-control list-input" name="S0[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S0'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_2"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str0[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str0'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			--><?php //$i++ ?>
<!--			<tr>-->
<!--				<td class="num_table">3</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_3"-->
<!--						   class="form-control list-input" name="P0[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P0'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_3"-->
<!--						   class="form-control list-input" name="S0[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S0'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_3"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str0[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str0'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td colspan="5"><h4>20 C</h4></td>-->
<!--			</tr>-->
<!--			--><?php //$i = 0 ?>
<!--			<tr>-->
<!--				<td class="num_table">1</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_4"-->
<!--						   class="form-control list-input" name="P20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_4"-->
<!--						   class="form-control list-input" name="S20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_4"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td rowspan="3">-->
<!--					<input readonly type='number' step="any" id="Average_Strength20"-->
<!--						   class="form-control list-input" name="Average_Strength20"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength['average_strength20'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			--><?php //$i++ ?>
<!--			<tr>-->
<!--				<td class="num_table">2</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_5"-->
<!--						   class="form-control list-input" name="P20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_5"-->
<!--						   class="form-control list-input" name="S20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_5"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str20'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			--><?php //$i++ ?>
<!--			<tr>-->
<!--				<td class="num_table">3</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_6"-->
<!--						   class="form-control list-input" name="P20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_6"-->
<!--						   class="form-control list-input" name="S20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S20'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_6"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str20[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str20'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<td colspan="5"><h4>50 C</h4></td>-->
<!--			</tr>-->
<!--			--><?php //$i = 0 ?>
<!--			<tr>-->
<!--				<td class="num_table">1</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_7"-->
<!--						   class="form-control list-input" name="P50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_7"-->
<!--						   class="form-control list-input" name="S50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_7"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td rowspan="3">-->
<!--					<input readonly type='number' step="any" id="Average_Strength50"-->
<!--						   class="form-control list-input" name="Average_Strength50"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength['average_strength50'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			--><?php //$i++ ?>
<!--			<tr>-->
<!--				<td class="num_table">2</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_8"-->
<!--						   class="form-control list-input" name="P50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_8"-->
<!--						   class="form-control list-input" name="S50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_8"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str50'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
<!--			--><?php //$i++ ?>
<!--			<tr>-->
<!--				<td class="num_table">3</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="breaking_load_9"-->
<!--						   class="form-control list-input" name="P50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['P50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="square_9"-->
<!--						   class="form-control list-input" name="S50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['S50'] : '' ?><!--">-->
<!--				</td>-->
<!--				<td>-->
<!--					<input type='number' step="any" id="Strength_9"-->
<!--						   class="form-control list-input"-->
<!--						   name="Str50[]"-->
<!--						   value="--><?//= $averageStrength ? $averageStrength[$i]['Str50'] : '' ?><!--">-->
<!--				</td>-->
<!--			</tr>-->
			</tbody>
		</table>
		<div class="row mb-3">
			<div class="col flex-grow-0">
				<button type="button" class="calculate btn btn-primary" name="calculate">Рассчитать</button>
			</div>

			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary">Сохранить</button>
			</div>
		</div>
	</div>
	<div class="line-dashed-small"></div>
</div>
