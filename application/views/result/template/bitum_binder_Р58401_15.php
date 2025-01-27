<!-- Асфальтобетон ГОСТ Р 58401.15 Содержание битумного вяжущего -->
<div class="measurement-wrapper" id="formBituminousBinderWrapper">
    <h3 class="mb-3">Содержание битумного вяжущего, %</h3>

    <div id="formBituminousBinder" class="mb-3">

        <div class="form-group row mb-3">
            <div class="col">
                <label for="bb-select-method">Метод испытания</label>
                <select name="form[method_bb]" id="bb-select-method">
                    <option value="burning" <?=$this->data['measuring']['form']['method_bb'] != 'extraction'? 'selected' : ''?>>Выжигания</option>
                    <option value="extraction" <?=$this->data['measuring']['form']['method_bb'] == 'extraction'? 'selected' : ''?>>Экстрагирования</option>
                </select>
            </div>
        </div>

        <div class="block-burning block-method" style="display: <?=$this->data['measuring']['form']['method_bb'] != 'extraction'? 'block' : 'none'?>;">
            <em class="info d-block mb-1">
                Содержание битумного вяжущего (метод выжигания)
            </em>
            <table class="table table-fixed list_data mb-3">
                <thead>
                <tr class="table-info text-center align-middle">
                    <th scope="">Испытания</th>
                    <th scope="col">Масса лотка, г (G)</th>
                    <th scope="col">Масса лотка с навесткой смеси до выжигания, г (G<sub>1</sub>)</th>
                    <th scope="col">Масса лотка с навесткой смеси после выжигания, г (G<sub>2</sub>)</th>
                    <th scope="col">Содержание битумного вяжущего, %</th>
                </tr>
                </thead>
                <tbody>
                <tr class="definition">
                    <td class="text-center align-middle">1</td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-burning-g change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_burning_g]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_burning_g'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-burning-g1 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_burning_g1]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_burning_g1'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-burning-g2 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_burning_g2]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_burning_g2'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" tabindex="-1" class="form-control appearance-none bb-burning-r" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_burning_r]" value="<?=$this->data['measuring']['form'][0]['bb_burning_r'] ?? ''?>" readonly>
                    </td>
                </tr>
                <tr class="definition">
                    <td class="text-center align-middle">2</td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-burning-g change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_burning_g]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_burning_g'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-burning-g change-trigger-bb1" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_burning_g1]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_burning_g1'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-burning-g2 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_burning_g2]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_burning_g2'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" tabindex="-1" class="form-control appearance-none bb-burning-r" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_burning_r]" value="<?=$this->data['measuring']['form'][1]['bb_burning_r'] ?? ''?>" readonly>
                    </td>
                </tr>
                </tbody>
            </table>

<!--                <div class="form-group row mb-3">-->
<!--                    <div class="col">-->
<!--                        <label for="result-bb-burning">-->
<!--                            Среднее арифметическое содержание битумного вяжущего, % (метод выжигания)-->
<!--                        </label>-->
<!--                        <input-->
<!--                                id="result-bb-burning"-->
<!--                                class="form-control appearance-none"-->
<!--                                type="number" step="0.01" name="form[result_bb_burning]"-->
<!--                                value="--><?//=$this->data['measuring']['form']['result_bb_burning'] ?? ''?><!--"-->
<!--                                readonly-->
<!--                        >-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <label for="diff-bb-burning">Разница результатов (не должно превышать 0.2 %)</label>-->
<!--                        <input-->
<!--                                id="diff-bb-burning"-->
<!--                                class="form-control appearance-none"-->
<!--                                name="form[diff_bb_burning]"-->
<!--                                type="number" step="0.001"-->
<!--                                value="--><?//=$this->data['measuring']['form']['diff_bb_burning'] ?? ''?><!--"-->
<!--                                readonly-->
<!--                        >-->
<!--                    </div>-->
<!--                </div>-->
        </div>


        <div class="block-extraction block-method" style="display: <?=$this->data['measuring']['form']['method_bb'] == 'extraction'? 'block' : 'none'?>;">
            <em class="info d-block mb-1">
                Содержание битумного вяжущего (метод экстрагирования)
            </em>
            <table class="table table-fixed list_data mb-3">
                <thead>
                <tr class="table-info text-center align-middle">
                    <th scope="">Испытания</th>
                    <th scope="col">Масса барабана, г (m<sub>1</sub>)</th>
                    <th scope="col">Масса колбы и фильтра, г (m<sub>2</sub>)</th>
                    <th scope="col">Масса барабана с пробой, г (m<sub>3</sub>)</th>
                    <th scope="col">Масса барабана с пробой смеси после отмывки, г (m<sub>4</sub>)</th>
                    <th scope="col">Масса колбы и фильтра с пробой после отмывки, г (m<sub>5</sub>)</th>
                    <th scope="col">Содержание битумного вяжущего, %</th>
                </tr>
                </thead>
                <tbody>
                <tr class="definition">
                    <td class="text-center align-middle">1</td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m1 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_extraction_m1]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_extraction_m1'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m2 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_extraction_m2]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_extraction_m2'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m3 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_extraction_m3]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_extraction_m3'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m4 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_extraction_m4]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_extraction_m4'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m5 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_extraction_m5]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][0]['bb_extraction_m5'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-r" name="form_data[<?=$this->data['ugtp_id']?>][form][0][bb_extraction_r]" value="<?=$this->data['measuring']['form'][0]['bb_extraction_r'] ?? ''?>" readonly>
                    </td>
                </tr>
                <tr class="definition">
                    <td class="text-center align-middle">2</td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m1 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_extraction_m1]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_extraction_m1'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m2 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_extraction_m2]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_extraction_m2'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m3 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_extraction_m3]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_extraction_m3'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m4 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_extraction_m4]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_extraction_m4'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-m5 change-trigger-bb" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_extraction_m5]" min="0" step="0.01" value="<?=$this->data['measuring']['form'][1]['bb_extraction_m5'] ?? ''?>">
                    </td>
                    <td>
                        <input type="number" class="form-control appearance-none bb-extraction-r" name="form_data[<?=$this->data['ugtp_id']?>][form][1][bb_extraction_r]" value="<?=$this->data['measuring']['form'][1]['bb_extraction_r'] ?? ''?>" readonly>
                    </td>
                </tr>
                </tbody>
            </table>

<!--                <div class="form-group row mb-3">-->
<!--                    <div class="col">-->
<!--                        <label for="result-bb-extraction">Среднее арифметическое содержание битумного вяжущего, % (метод экстрагирования)</label>-->
<!--                        <input-->
<!--                                id="result-bb-extraction"-->
<!--                                class="form-control appearance-none"-->
<!--                                type="number" step="0.01" name="form[result_bb_extraction]"-->
<!--                                value="--><?//=$this->data['measuring']['form']['result_bb_extraction'] ?? ''?><!--"-->
<!--                                readonly-->
<!--                        >-->
<!--                    </div>-->
<!--                </div>-->
        </div>

        <div class="form-group row mb-3">
            <div class="col">
                <label for="result-bb">
                    Среднее арифметическое содержание битумного вяжущего, %
                </label>
                <input
                        id="result-bb"
                        class="form-control appearance-none"
                        type="number" step="0.01" name="form_data[<?=$this->data['ugtp_id']?>][result_value]"
                        value="<?=$this->data['measuring']['result_value'] ?? ''?>"
                        readonly
                >
            </div>
            <div class="col">
                <label for="diff-bb">Разница результатов (не должно превышать 0.2 %)</label>
                <input
                        id="diff-bb"
                        class="form-control appearance-none"
                        name="form_data[<?=$this->data['ugtp_id']?>][form][diff_bb]"
                        type="number" step="0.001"
                        value="<?=$this->data['measuring']['form']['diff_bb'] ?? ''?>"
                        readonly
                >
            </div>
        </div>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="calculateBituminousBinder" class="btn btn-primary">Расчитать</button>
            </div>
			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
			</div>
        </div>
    </div>

</div>
