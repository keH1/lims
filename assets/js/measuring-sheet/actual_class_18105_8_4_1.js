// Неразрушающий контроль (Фактический класса бетона ГОСТ 18105 п. 8.4.1)
$(function ($) {
    const body = $('body');

    /** Сбросить значения */
    function resetValue(items) {
        items.each(function (index, item) {
            $(this).val("");
        });
    }

    /** Выбор данные конструкции */
    body.on('change', '.actual_class_18105_8_4_1 #designCalculation', function (e) {
        e.preventDefault();
        const calculationDataWrapper = $('.actual_class_18105_8_4_1 #calculationDataWrapper');

        let ugtpId = +$(this).val();

        // Очищаем лист измерения
        $('.actual_class_18105_8_4_1 #calculationData').remove();

        if (ugtpId) {
            $.ajax({
                method: 'POST',
                url: '/ulab/result/getUgtpAjax/',
                dataType: 'json',
                data: {
                    ugtp_id: ugtpId,
                },
                success: function (result) {
                    let count = Object.keys(result).length;

                    if (!count) {
                        return false;
                    }

                    if (result['measuring_sheet'] && result['measuring_sheet']['scheme'] === 'v') {
                        let countMean =  Object.keys(result['measuring_sheet']['mean']).length;
                        let trConstruct = '';

                        if (!countMean) {
                            return false;
                        }

                        for (let i = 0; i < countMean; i++) {
                            trConstruct += getTrConstructForSchemeVactualClass18105_8_4_1(result['measuring_sheet'], i, countMean)
                        }

                        let htmlForSchemeV = getHtmlForSchemeVactualClass18105_8_4_1(result['gradation'], result['measuring_sheet'], trConstruct);
                        calculationDataWrapper.append(htmlForSchemeV);
                        console.log('calculationDataWrapper', calculationDataWrapper);
                    }
                },
                error: function (jqXHR, exception) {
                    let msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status === 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status === 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.error(msg)
                }
            });
        }
    });

    let designCalculation = $('.actual_class_18105_8_4_1 #designCalculation').val();

    $('.actual_class_18105_8_4_1 #designCalculation').val(designCalculation).trigger('change');

    /** Блокировать кнопку при сохранении */
    $('#measurementModalForm').on('submit', function() {
        const frostWrapper =  $('#frostWrapper');

        frostWrapper.find('.calculate').prop('disabled', true);
        $(this).find('.save').replaceWith(
            `<button class="btn btn-primary" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm spinner-save" role="status" aria-hidden="true"></span>
                Сохранение...
            </button>`
        );
    });

    /** Редактирование */
    body.on("input", ".actual_class_18105_8_4_1 #calculationData input:not('.do-not-clean')", function () {
        const inputReadonly = $(".actual_class_18105_8_4_1 #calculationData input[readonly]");
        resetValue(inputReadonly);
        $('.actual_class_18105_8_4_1 #measurementList option:first').prop('selected', true);
        $('.actual_class_18105_8_4_1 #scheme option:first').prop('selected', true);
    });
});

/**
 * Получить html для схемы "В"
 * @param gradation
 * @param data
 * @param trConstruct
 * @returns {string}
 */
function getHtmlForSchemeVactualClass18105_8_4_1(gradation, data, trConstruct)
{
    let measurementOption = '';
    if (!data['scheme']) {
        measurementOption += `<option value="" disabled>Сначала выберите схему испытаний</option>`;
    } else {
        measurementOption += `<option value="" disabled></option>`;

        $.each(gradation, function (i, val) {
            measurementOption += `<option value="${val['id']}" 
                ${val['id'] === data['measurement_id'] ? 'selected' : ''}>№ ${val['id']} - ${val['object']}, от ${val['ru_date']}</option>`;
        });
    }

    return `<div id="calculationData">
            <div class="row mb-3">
                <div class="form-group col">
                    <label for="scheme">Схема испытаний</label>
                    <select class="form-select w-100 pointer-events-none bg-light-secondary" id="scheme" readonly>
                        <option value="" selected>Выберите схему испытаний</option>
                        <option value="v" ${'v' === data['scheme'] ? 'selected' : ''}>Схема "В"</option>
                        <option value="g" ${'g' === data['scheme'] ? 'selected' : ''} disabled>Схема "Г"</option>
                    </select>
                </div>
                <div class="form-group col">
                    <label for="measurementList">Список листов расчёта</label>
                    <select class="form-select w-100 pointer-events-none bg-light-secondary" id="measurementList" readonly>
                        ${measurementOption}
                    </select>
                </div>
                <div class="form-group col">
                    <label for="сoncretingDate">Дата бетонирования</label>
                    <input type="date" class="form-control bg-light-secondary" id="сoncretingDate" 
                        value="${data['сoncreting_date']}" readonly>
                </div>
                <div class="form-group col">
                    <label for="cipher">Шифр</label>
                    <input type="text" class="form-control bg-light-secondary" id="cipher" value="${data['cipher']}" readonly>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R</div>
                        </div>
                        <input type="number" class="form-control bg-light-secondary" id="round-R" 
                            step="any" value="${data['round_R']}" readonly>
                        <input type="hidden" class="form-control bg-light-secondary" id="R" 
                                step="any" value="${data['R']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">V</div>
                        </div>
                        <input type="number" class="form-control bg-light-secondary" id="round-V" 
                                step="any" value="${data['round_V']}" readonly>
                        <input type="hidden" class="form-control bg-light-secondary" id="V" 
                                step="any" value="${data['V']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">r</div>
                        </div>
                        <input type="number" class="form-control bg-light-secondary do-not-clean" id="r" step="any"
                               value="${data['r']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <span class="input-group-text">B</span>
                        <input type="text" class="form-control number-only bg-light-secondary" 
                               value="${data['class']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Прибор</div>
                        </div>
                        <input type="text" class="form-control bg-light-secondary" id="measuringDevice" 
                                value="${data['measuring_device']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <select id="method" class="form-control bg-light-secondary do-not-clean pointer-events-none" readonly>
                            <optgroup label="Метод отрыва со скалыванием">
                                <option value="separation_0.04" ${'separation_0.04' === data['method'] ? 'selected' : ''}>Глубина 48 мм</option>
                                <option value="separation_0.05" ${'separation_0.05' === data['method'] ? 'selected' : ''}>Глубина 35 мм</option>
                                <option value="separation_0.06" ${'separation_0.06' === data['method'] ? 'selected' : ''}>Глубина 30 мм</option>
                            </optgroup>
                            <optgroup label="Метод скалывания ребра">
                                <option value="chipping_0.04" ${'chipping_0.04' === data['method'] ? 'selected' : ''}>Скалывание ребра</option>
                            </optgroup>
                            <optgroup label="Разрушающий метод">
                                <option value="destructive_0.02" ${'destructive_0.02' === data['method'] ? 'selected' : ''}>Разрушающий</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <input type="text" class="form-control number-only bg-light-secondary" id="dayToTest" 
                               value="${data['day_to_test']}" readonly>
                        <span class="input-group-text">суток</span>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col">
                    <table class="table text-center align-middle table-bordered">
                        <thead>
                        <tr class="align-middle">
                            <th rowspan="2">Наименование конструкции</th>
                            <th colspan="3">Показания СИ</th>
                            <th colspan="2">Прочность бетона, МПа</th>
                            <th rowspan="2">Фактический класс бетона конструкции по прочности на сжатие</th>
                        </tr>
                        <tr class="align-middle">
                            <th colspan="2">Единичные значения</th>
                            <th>Среднее значение на контролируемом участке</th>
                            <th>Контролируе-мого участка</th>
                            <th>Конструкции</th>
                        </tr>
                        </thead>
                        <tbody class="construction-wrapper">
                            ${trConstruct}
                        </tbody>
                    </table>
                </div>
            </div>
    
            <div class="row">
                <div class="col">
                    <table class="table text-center align-middle table-bordered mb-0">
                        <thead>
                        <tr class="align-middle">
                            <th colspan="5">Расчет характеристик однородности бетона</th>
                            <th rowspan="3">Текущий коэффициент вариации прочности Vm,%</th>
                            <th rowspan="3">Коэффициент Кт</th>
                            <th rowspan="3">Факт. Прочность бетона в %</th>
                        </tr>
                        <tr class="align-middle">
                            <th colspan="4">Среднеквадратическое отклонение прочности, МПа (для группы конструкций</th>
                            <th>Среднеквадратическое отклонение с учетом установленной градуировочной зависимости</th>
                        </tr>
                        <tr class="align-middle">
                            <th>S1</th>
                            <th>S3=Sт.м.н.</th>
                            <th>S4</th>
                            <th>S2</th>
                            <th>Sm</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <input type="number" class="form-control bg-light-secondary"  
                                step="any" value="${data['S1']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" step="any" value="${data['S3']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary"  
                                step="any" value="${data['S4']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary"  
                                step="any" value="${data['S2']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary"  
                                step="any" value="${data['Sm']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary"  
                                step="any" value="${data['Vm']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" step="any" value="${data['Kt']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" step="any" value="${data['percent']}" readonly>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <lable for="comment">Примечание</lable>
                    <textarea class="form-control mw-100 bg-light-secondary"
                              rows="5" id="comment" readonly>${data['comment'] ?? ''}</textarea>
                </div>
            </div>
            
            <div class="line-dashed-small"></div>

            <div class="row btn-wrapper">
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary save">Сохранить</button>
                </div>
            </div>
        </div>`;
}

/**
 * @param data
 * @param key
 * @param count
 * @returns {string}
 */
function getTrConstructForSchemeVactualClass18105_8_4_1(data, key, count)
{
    let tdMaterial = '',
        tdConstructionStrength = '',
        tdConcreteClass = '';
    if (key === 0) {
        tdMaterial = `<td class="name-wrapper" rowspan="${count}">
                        <input type="text" class="form-control bg-light-secondary" 
                               value="${data['name_for_protocol']}" readonly>
                    </td>`;

        tdConstructionStrength = `<td class="construction-strength-wrapper" rowspan="${count}">
                                        <input type="number" class="form-control bg-white bg-light-secondary" 
                                                step="any" value="${data['result_value']}" readonly>
                                    </td>`;

        tdConcreteClass = `<td class="concrete-class-wrapper" rowspan="${count}">
                                <input type="number" class="form-control bg-white bg-light-secondary" 
                                       step="any" value="${data['concrete_class']}" readonly>
                            </td>`;
    }

    return `<tr class="construction-row">
                ${tdMaterial}
                <td>
                    <input type="number" class="form-control bg-light-secondary single-value-1" 
                           step="any" value="${data['single_value_1'][key]}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control single-value-2 bg-light-secondary"
                           step="any" value="${data['single_value_2'] ? data['single_value_2'][key] : ''}" 
                        ${data['measuring_device'] === 'ИПС' ? 'disabled' : 'readonly'}>
                </td>
                <td>
                    <input type="number" class="form-control bg-light-secondary mean clear" 
                           step="any" value="${data['mean'][key]}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control bg-white area-strength bg-light-secondary" 
                            tep="any" value="${data['single_values'][key]}" readonly>
                </td>
                ${tdConstructionStrength}
                ${tdConcreteClass}
            </tr>`;
}