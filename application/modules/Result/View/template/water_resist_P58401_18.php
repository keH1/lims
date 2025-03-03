<!-- Асфальтобетон ГОСТ Р 58401.18 Коэффициент водостойкости -->
<div class="measurement-wrapper" id="formSaturationWrapper">
    <h3 class="mb-3">Коэффициент водостойкости, %</h3>

    <div id="formSaturation" class="mb-3">
        <table class="table table-fixed list_data mb-3">
            <thead>
            <tr class="table-info text-center">
                <th style="width: 50px">№</th>
                <th style="width: 70px">Группа</th>
                <th>Масса сухого образца на воздухе, г (A)</th>
                <th>Масса образца на воздухе после выдерживания его в воде, г (B)</th>
                <th>Масса образца в воде, г (C)</th>
                <th>Масса образца после насыщения водой на воздухе, г (B<sub>1</sub>)</th>
                <th>B - C = E</th>
                <th>Объемная плотность, г/см<sup>3</sup> (G<sub>mb</sub>)</th>
                <th>Кол-во воздушных пустот, % (P<sub>a</sub>)</th>
                <th>Объем воздушных пустот, см<sup>3</sup> (V<sub>a</sub>)</th>
                <th>Объем поглощенной воды, см<sup>3</sup> (J)</th>
                <th>Степень насыщения (от 70 до 80%) (W)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center align-middle">
                    1
                    <input
                            class="saturation-group"
                            type="hidden"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][group]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['group'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle type-group">
                    <?=$this->data['measuring']['form']['saturation'][0]['group'] ?? ''?>
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-a change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][a]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['a'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][b]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['b'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-c change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][c]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['c'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b1 change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][b1]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['b1'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-e"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][e]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['e'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][gmb]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-pa"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][pa]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['pa'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-va"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][va]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['va'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-j"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][j]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['j'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-w"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][0][w]"
                            value="<?=$this->data['measuring']['form']['saturation'][0]['w'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
            </tr>

            <tr>
                <td class="text-center align-middle">
                    2
                    <input
                            class="saturation-group"
                            type="hidden"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][group]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['group'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle type-group">
                    <?=$this->data['measuring']['form']['saturation'][1]['group'] ?? ''?>
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-a change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][a]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['a'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][b]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['b'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-c change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][c]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['c'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b1 change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][b1]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['b1'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-e"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][e]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['e'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][gmb]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-pa"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][pa]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['pa'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-va"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][va]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['va'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-j"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][j]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['j'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-w"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][1][w]"
                            value="<?=$this->data['measuring']['form']['saturation'][1]['w'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
            </tr>

            <tr>
                <td class="text-center align-middle">
                    3
                    <input
                            class="saturation-group"
                            type="hidden"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][group]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['group'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle type-group">
                    <?=$this->data['measuring']['form']['saturation'][2]['group'] ?? ''?>
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-a change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][a]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['a'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][b]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['b'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-c change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][c]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['c'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b1 change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][b1]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['b1'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-e"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][e]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['e'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][gmb]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-pa"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][pa]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['pa'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-va"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][va]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['va'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-j"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][j]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['j'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-w"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][2][w]"
                            value="<?=$this->data['measuring']['form']['saturation'][2]['w'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
            </tr>

            <tr>
                <td class="text-center align-middle">
                    4
                    <input
                            class="saturation-group"
                            type="hidden"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][group]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['group'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle type-group">
                    <?=$this->data['measuring']['form']['saturation'][3]['group'] ?? ''?>
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-a change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][a]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['a'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][b]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['b'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-c change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][c]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['c'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b1 change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][b1]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['b1'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-e"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][e]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['e'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][gmb]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-pa"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][pa]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['pa'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-va"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][va]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['va'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-j"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][j]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['j'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-w"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][3][w]"
                            value="<?=$this->data['measuring']['form']['saturation'][3]['w'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
            </tr>

            <tr>
                <td class="text-center align-middle">
                    5
                    <input
                            class="saturation-group"
                            type="hidden"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][group]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['group'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle type-group">
                    <?=$this->data['measuring']['form']['saturation'][4]['group'] ?? ''?>
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-a change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][a]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['a'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][b]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['b'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-c change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][c]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['c'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b1 change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][b1]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['b1'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-e"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][e]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['e'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][gmb]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-pa"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][pa]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['pa'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-va"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][va]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['va'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-j"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][j]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['j'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-w"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][4][w]"
                            value="<?=$this->data['measuring']['form']['saturation'][4]['w'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
            </tr>

            <tr>
                <td class="text-center align-middle">
                    6
                    <input
                            class="saturation-group"
                            type="hidden"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][group]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['group'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle type-group">
                    <?=$this->data['measuring']['form']['saturation'][5]['group'] ?? ''?>
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-a change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][a]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['a'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][b]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['b'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-c change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][c]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['c'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-b1 change-trigger-wrs-1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][b1]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['b1'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-e"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][e]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['e'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][gmb]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-pa"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][pa]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['pa'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-va"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][va]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['va'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-j"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][j]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['j'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none saturation-w"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][5][w]"
                            value="<?=$this->data['measuring']['form']['saturation'][5]['w'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-end align-middle">Максимальная плотность, г/см<sup>3</sup> (G<sub>mm</sub>)</td>
                <td>
                    <input
                            class="form-control appearance-none saturation-maxden change-trigger-wrs-1"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][maxden]"
                            value="<?=$this->data['measuring']['form']['saturation']['maxden'] ?? ''?>"
                    >
                </td>
                <td class="text-center align-middle">Среднее</td>
                <td>
                    <input
                            class="form-control appearance-none saturation-aver_gmb"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][saturation][aver_gmb]"
                            value="<?=$this->data['measuring']['form']['saturation']['aver_gmb'] ?? ''?>"
                            tabindex="-1" readonly
                    >
                </td>
                <td colspan="4"></td>
            </tr>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="formSaturationCalculate" class="btn btn-primary">Расчитать</button>
            </div>
        </div>
    </div>

    <div id="formWaterResist" class="mb-3">
        <table class="table table-responsive list_data mb-3">
            <thead>
            <tr class="table-info text-center">
                <th colspan="5">Образцы первой группы (сухой)</th>
            </tr>
            <tr class="table-info text-center">
                <th style="width: 50px">№</th>
                <th>Толщина образца t, мм</th>
                <th>Диаметр образца D, мм</th>
                <th>Максимальная нагрузка P, H</th>
                <th>Предел прочности при непрямом растяжении S<sub>1</sub>, кПа</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center align-middle">1</td>
                <td>
                    <input
                            class="form-control appearance-none water-t change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][0][t]"
                            value="<?=$this->data['measuring']['form']['water'][0]['t'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-d change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][0][d]"
                            value="<?=$this->data['measuring']['form']['water'][0]['d'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-p change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][0][p]"
                            value="<?=$this->data['measuring']['form']['water'][0]['p'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-s1"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][0][s1]"
                            value="<?=$this->data['measuring']['form']['water'][0]['s1'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            <tr>
                <td class="text-center align-middle">2</td>
                <td>
                    <input
                            class="form-control appearance-none water-t change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][1][t]"
                            value="<?=$this->data['measuring']['form']['water'][1]['t'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-d change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][1][d]"
                            value="<?=$this->data['measuring']['form']['water'][1]['d'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-p change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][1][p]"
                            value="<?=$this->data['measuring']['form']['water'][1]['p'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-s1"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][1][s1]"
                            value="<?=$this->data['measuring']['form']['water'][1]['s1'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            <tr>
                <td class="text-center align-middle">3</td>
                <td>
                    <input
                            class="form-control appearance-none water-t change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][2][t]"
                            value="<?=$this->data['measuring']['form']['water'][2]['t'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-d change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][2][d]"
                            value="<?=$this->data['measuring']['form']['water'][2]['d'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-p change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][2][p]"
                            value="<?=$this->data['measuring']['form']['water'][2]['p'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-s1"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][2][s1]"
                            value="<?=$this->data['measuring']['form']['water'][2]['s1'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-end align-middle">Среднее значение</td>
                <td>
                    <input
                            class="form-control appearance-none water-aver_s1"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][aver_s1]"
                            value="<?=$this->data['measuring']['form']['water']['aver_s1'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            </tbody>
        </table>

        <table class="table table-responsive list_data mb-3">
            <thead>
            <tr class="table-info text-center">
                <th colspan="5">Образцы второй группы (подвергнутые заморажыванию-оттаиванию)</th>
            </tr>
            <tr class="table-info text-center">
                <th style="width: 50px">№</th>
                <th>Толщина образца t, мм</th>
                <th>Диаметр образца D, мм</th>
                <th>Максимальная нагрузка P, H</th>
                <th>Предел прочности при непрямом растяжении S<sub>1</sub>, кПа</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="text-center align-middle">1</td>
                <td>
                    <input
                            class="form-control appearance-none water-t change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][3][t]"
                            value="<?=$this->data['measuring']['form']['water'][3]['t'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-d change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][3][d]"
                            value="<?=$this->data['measuring']['form']['water'][3]['d'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-p change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][3][p]"
                            value="<?=$this->data['measuring']['form']['water'][3]['p'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-s2"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][3][s2]"
                            value="<?=$this->data['measuring']['form']['water'][3]['s2'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            <tr>
                <td class="text-center align-middle">2</td>
                <td>
                    <input
                            class="form-control appearance-none water-t change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][4][t]"
                            value="<?=$this->data['measuring']['form']['water'][4]['t'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-d change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][4][d]"
                            value="<?=$this->data['measuring']['form']['water'][4]['d'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-p change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][4][p]"
                            value="<?=$this->data['measuring']['form']['water'][4]['p'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-s2"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][4][s2]"
                            value="<?=$this->data['measuring']['form']['water'][4]['s2'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            <tr>
                <td class="text-center align-middle">3</td>
                <td>
                    <input
                            class="form-control appearance-none water-t change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][5][t]"
                            value="<?=$this->data['measuring']['form']['water'][5]['t'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-d change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][5][d]"
                            value="<?=$this->data['measuring']['form']['water'][5]['d'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-p change-trigger-wrs-2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][5][p]"
                            value="<?=$this->data['measuring']['form']['water'][5]['p'] ?? ''?>"
                    >
                </td>
                <td>
                    <input
                            class="form-control appearance-none water-s2"
                            type="number" step="0.01"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][5][s2]"
                            value="<?=$this->data['measuring']['form']['water'][5]['s2'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-end align-middle">Среднее значение</td>
                <td>
                    <input
                            class="form-control appearance-none water-aver_s2"
                            type="number" step="0.001"
                            name="form_data[<?=$this->data['ugtp_id']?>][form][water][aver_s2]"
                            value="<?=$this->data['measuring']['form']['water']['aver_s2'] ?? ''?>"
                            readonly
                    >
                </td>
            </tr>
            </tbody>
        </table>

        <div class="form-group row mb-3">
            <div class="col">
                <label for="water-resist-result">Коэффицент водостойкости TSR</label>
                <input type="number" id="water-resist-result" class="form-control appearance-none" name="form_data[<?=$this->data['ugtp_id']?>][result_value]" value="<?=$this->data['measuring']['result_value'] ?? ''?>" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col flex-grow-0">
                <button type="button" id="formWaterResistCalculate" class="btn btn-primary">Расчитать</button>
            </div>
			<div class="col flex-grow-0">
				<button type="submit" class="btn btn-primary save-average">Сохранить</button>
			</div>
        </div>
    </div>
</div>
