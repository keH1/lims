<!-- Асфальтобетон ГОСТ Р 58406.2 Пустоты в минеральном заполнителе (ПМЗ) -->
<div class="measurement-wrapper" id="formPMZWrapper">
    <h3 class="mb-3">Пустоты в минеральном заполнителе (ПМЗ)</h3>

    <div id="formPMZ" class="mb-3">

        <em class="info d-block mb-1">
            Общая объемная плотность минерального заполнителя
        </em>
        <div class="mineral-true-density-wrapper">
            <table class="table table-fixed list_data mb-4">
                <thead>
                    <tr class="table-info text-center align-middle">
                        <th scope="col" class="border-0">Кол-во минеральных заполнителей</th>
                        <th scope="col" class="border-0">Массовая доля отдельных минеральных заполнителей, %</th>
                        <th scope="col" class="border-0">Истинная плотность отдельных минеральных заполнителей,
                            г/см<sup>3</sup></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row" class="text-center align-middle">1</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][0]"
                                    data-number="1" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][0] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][0]"
                                    data-number="1" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][0] ?? '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center align-middle">2</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][1]"
                                    data-number="2" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][1] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][1]"
                                    data-number="2" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][1] ?? '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center align-middle">3</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][2]"
                                    data-number="3" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][2] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][2]"
                                    data-number="3" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][2] ?? '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center align-middle">4</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][3]"
                                    data-number="4" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][3] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][3]"
                                    data-number="4" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][3] ?? '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center align-middle">5</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][4]"
                                    data-number="5" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][4] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][4]"
                                    data-number="5" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][4] ?? '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center align-middle">6</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][5]"
                                    data-number="5" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][5] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][5]"
                                    data-number="5" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][5] ?? '' ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-center align-middle">7</th>
                        <td>
                            <input type="number" class="form-control mass-fraction-mineral-materials change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mass_fraction_mineral_materials][6]"
                                    data-number="5" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mass_fraction_mineral_materials'][6] ?? '' ?>">
                        </td>
                        <td>
                            <input type="number" class="form-control mineral-materials-true-density change-trigger-pmz"
                                    name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_materials_true_density][6]"
                                    data-number="5" min="0" step="any"
                                    value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_materials_true_density'][6] ?? '' ?>">
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group row mb-4">
                <div class="col">
                    <label for="mineralPartTrueDensity">Общая объемная плотность минерального заполнителя G<sub>sb</sub>,
                        г/см<sup>3</sup></label>
                    <input type="number" class="form-control mineral-part-true-density"
                            id="mineralPartTrueDensity"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_58406][mineral_part_true_density]"
                            data-number="4" min="0" step="any"
                            value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_part_true_density'] ?? '' ?>"
                            readonly>
                </div>
            </div>

        </div>

        <em class="info d-block mb-1">
            ПМЗ, %
        </em>
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info">
                <th scope="col">Объемная плотность уплотненного образца, г/см<sup>3</sup></th>
                <th scope="col">Количество минерального заполнителя в асфальтобетонной смеси, %</th>
                <th scope="col">Общая объемная плотность минерального заполнителя, г/см<sup>3</sup></th>
                <th scope="col">ПМЗ, %</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="number" id="pmz_bulk_density"
                           class="form-control col-sm-10 change-trigger-pmz"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_bulk_density]"
                           min="0" step="0.001"
                           value="<?=$this->data['measuring']['form']['pmz_bulk_density'] ?? ''?>"
                    >
                </td>
                <td>
                    <input type="number" id="pmz_amount_mineral"
                           class="form-control col-sm-10 change-trigger-pmz"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_amount_mineral]"
                           min="0" step="0.001"
                           value="<?=$this->data['measuring']['form']['pmz_amount_mineral'] ?? ''?>"
                    >
                </td>
                <td>
                    <input type="number"
                           id="pmz_total_bulk_density"
                           class="form-control col-sm-10 change-trigger-pmz"
                           name="form_data[<?=$this->data['ugtp_id']?>][form][pmz_total_bulk_density]"
                           min="0" step="0.001"
                           value="<?= $this->data['measuring']['form']['pmz_58406']['mineral_part_true_density'] ?? '' ?>">
                </td>
                <td>
                    <input type="number" id="pmz_result" class="form-control" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" step="0.001" value="<?=$this->data['measuring']['result_value'] ?? ''?>">
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="pmz_calculate" class="btn btn-primary">Рассчитать</button>
            </div>
			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
			</div>
        </div>
    </div>

</div>
