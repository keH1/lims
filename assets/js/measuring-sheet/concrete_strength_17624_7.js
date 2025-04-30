// Неразрушающий контроль (Прочность на сжатие ГОСТ 17624 п. 7)
$(function ($) {
    const body = $('body');

    /** Сообщение об ошибки */
    function getMessageErrorContent(messageError = "") {
        if (!messageError) {
            return false;
        }

        return `<div class="messages">
              <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show" role="alert">
                  <div>
                      ${messageError}
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          </div>`;
    }

    /** Сбросить значения */
    function resetValue(items) {
        items.each(function (index, item) {
            $(this).val("");
        });
    }

    /**
     * Нахождение среднего арифметического
     * @param nums
     * @returns {number|boolean}
     */
    function average(nums) {
        if ( nums.length === 0 || nums.length === undefined ) {
            return false
        }
        return nums.reduce((a, b) => (a + b)) / nums.length
    }

    /**
     * Округление
     * @param num
     * @param decimalPlaces
     * @returns {number}
     */
    function round(num, decimalPlaces = 0) {
        if (num < 0) {
            return -round(-num, decimalPlaces);
        }
        let p = Math.pow(10, decimalPlaces);
        let n = num * p;
        let f = n - Math.floor(n);
        let e = Number.EPSILON * n;

        return (f >= 0.5 - e) ? Math.ceil(n) / p : Math.floor(n) / p;
    }

    /** Проверить на заполненость полей */
    function checkEmptyFields(fieldsSelector, errorWrapperSelector, resetSelector) {
        const wrapper = $(errorWrapperSelector),
            resetElement = $(resetSelector);

        let valueEmpty = $(fieldsSelector).filter(function () {
            return $(this).val() === null || $(this).val() === '';
        })

        wrapper.find(".messages").remove();

        if (valueEmpty.length) {
            let messageError =
                "Внимание! Не все поля заполнены!";

            let messageErrorContent = getMessageErrorContent(messageError)

            wrapper.prepend(messageErrorContent);
            resetValue(resetElement);
            return false;
        }

        return true;
    }

    /** Выбор схемы испытаний */
    body.on('change', '.concrete_strength_17624_7 #scheme', function (e) {
        const concreteStrength = $('.concrete_strength_17624_7 #concreteStrength'),
            measurementJournal = concreteStrength.find('#measurementJournal'),
            calculationData = $('.concrete_strength_17624_7 #calculationData');

        const schemesAnchor = {
            'v': {
                'journal': '/ulab/nk/graduationList/',
                'measurements': '/ulab/nk/getGraduationListAjax/',
            },
            'g': {
                'journal': '/ulab/nk/matchCoefficientList/',
                'measurements': '/ulab/nk/getMatchCoefficientsAjax/',
            },
        }

        let scheme = $(this).val();

        // Сылка на журнал листов измерений
        if (schemesAnchor[scheme]) {
            measurementJournal.attr('href', schemesAnchor[scheme]['journal']);
            measurementJournal.removeClass('icon-disabled');
        } else {
            measurementJournal.attr('href', '#');
            measurementJournal.addClass('icon-disabled');
        }

        // Очищаем список листов измерений
        $('#measurementList').find('option').remove();
        $('#measurementList').append(`<option value="" selected="" disabled="">Выберите лист измерения</option>`);

        // Очищаем лист измерения
        calculationData.remove();

        if (schemesAnchor[scheme]) {
            $.ajax({
                method: 'POST',
                url: schemesAnchor[scheme]['measurements'],
                dataType: 'json',
                success: function (data) {
                    // Добавляем список листов измерений
                    if (data.length) {
                        $.each(data, function(key, value) {
                            let ruDate = (new Date(value['date'])).toLocaleDateString('ru-RU');
                            $('#measurementList').append(`<option value="${value['id']}" data-scheme="${scheme}">№ ${value['number']} - ${value['object']}, от ${ruDate}</option>`);
                        });
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

    /** Выбор листа измерения */
    body.on('change', '.concrete_strength_17624_7 #measurementList', function (e) {
        const calculationDataWrapper = $('.concrete_strength_17624_7 #calculationDataWrapper'),
            concreteStrength = $('.concrete_strength_17624_7 #concreteStrength'),
            btnWrapper = concreteStrength.find('.btn-wrapper'),
            option = $(this).find('option:selected');

        let measurementId = $(this).val(),
            scheme = option.data('scheme'),
            ugtpId = $(this).data('ugtp');
            //ugtpId = $('#ugtpId').val();

        const schemesAnchor = {
            'v': {
                'measurement': '/ulab/nk/getGraduationAjax/',
            },
            'g': {
                'measurement': '/ulab/nk/getMatchCoefficientAjax/',
            },
        }

        // Очищаем лист измерения
        $('.concrete_strength_17624_7 #calculationData').remove();

        if (schemesAnchor[scheme]) {
            $.ajax({
                method: 'POST',
                url: schemesAnchor[scheme]['measurement'],
                data: {
                    measurement_id: measurementId,
                    ugtp_id: ugtpId,
                },
                dataType: 'json',
                success: function (result) {
                    console.log('result', result)
                    let countList = Object.keys(result).length;

                    if (countList) {
                        if (scheme === 'v') {
                            let htmlForSchemeV = getHtmlForSchemeV(result, ugtpId);
                            calculationDataWrapper.append(htmlForSchemeV);
                        }
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

    /**
     * Добавить конструкцию
     */
    body.on('click', '.concrete_strength_17624_7 .add-construction', function () {
        let constructionRow = $(this).closest('.construction-row'),
            constructionRowFirst = $('.concrete_strength_17624_7 .construction-row:first'),
            cloneConstructionRow = $(constructionRow).clone(true),
            constructionWrapper = constructionRow.closest('.construction-wrapper'),
            constructionRowCount = constructionWrapper.find('.construction-row').length;

        const inputReadonly = cloneConstructionRow.find('input');
        resetValue(inputReadonly);

        constructionRow.find('.name-wrapper').attr('rowspan', ++constructionRowCount);
        constructionRowFirst.find('.construction-strength-wrapper').attr('rowspan', constructionRowCount);
        constructionRowFirst.find('.concrete-class-wrapper').attr('rowspan', constructionRowCount);

        cloneConstructionRow.find('.name-wrapper').remove();
        cloneConstructionRow.find('.construction-strength-wrapper').remove();
        cloneConstructionRow.find('.concrete-class-wrapper').remove();

        cloneConstructionRow.find('.add-construction').replaceWith(
            `<button type="button" class="btn btn-danger del-construction mt-0 btn-square">
                 <i class="fa-solid fa-minus icon-fix"></i>
            </button>`
        );

        constructionWrapper.append(cloneConstructionRow);
    })

    /**
     * Удалить конструкцию
     */
    body.on('click', '.concrete_strength_17624_7 .del-construction', function () {
        let constructionRow = $(this).closest('.construction-row'),
            constructionRowFirst = $('.construction-row:first'),
            constructionWrapper = constructionRow.closest('.construction-wrapper'),
            constructionRowCount = constructionWrapper.find('.construction-row').length;

        constructionRowFirst.find('.name-wrapper').attr('rowspan', --constructionRowCount);
        constructionRowFirst.find('.construction-strength-wrapper').attr('rowspan', constructionRowCount);
        constructionRowFirst.find('.concrete-class-wrapper').attr('rowspan', constructionRowCount);

        constructionRow.remove();
    })

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

    body.on('click', '.concrete_strength_17624_7 .calculate-v', function (e) {
        e.preventDefault();
        const ugtpId = $(this).data('ugtp');

        const calculationData = $('.concrete_strength_17624_7 #calculationData'),
            inputSingleValue1 = calculationData.find('.single-value-1'),
            inputSingleValue2 = calculationData.find('.single-value-2'),
            inputMean = calculationData.find('.mean'),
            inputAreaStrength = calculationData.find('.area-strength'),
            inputResultValue = calculationData.find(`[name="form_data[${ugtpId}][result_value]"]`),
            inputConcreteClass = calculationData.find(`[name="form_data[${ugtpId}][concrete_class]"]`),
            inputS1 = calculationData.find(`[name="form_data[${ugtpId}][S1]"]`),
            inputS4 = calculationData.find(`[name="form_data[${ugtpId}][S4]"]`),
            inputS2 = calculationData.find(`[name="form_data[${ugtpId}][S2]"]`),
            inputSm = calculationData.find(`[name="form_data[${ugtpId}][Sm]"]`),
            inputVm = calculationData.find(`[name="form_data[${ugtpId}][Vm]"]`),
            inputPercent = calculationData.find(`[name="form_data[${ugtpId}][percent]"]`),
            inputR = calculationData.find('#R'),
            inputV = calculationData.find('#V');

        const TWENTY_EIGHT = 28;

        let Kt = +calculationData.find(`[name="form_data[${ugtpId}][Kt]"]`).val(),
            S3 = +calculationData.find(`[name="form_data[${ugtpId}][S3]"]`).val(),
            B = +calculationData.find(`[name="form_data[${ugtpId}][class]"]`).val(),
            dayToTest = +calculationData.find(`[name="form_data[${ugtpId}][day_to_test]"]`).val(),
            r = +calculationData.find(`[name="form_data[${ugtpId}][r]"]`).val(),
            method = calculationData.find(`[name="form_data[${ugtpId}][method]"]`).val();

        let areaStrengthCount = calculationData.find('.area-strength').length;

        //meanCalculation(inputSingleValue1, inputSingleValue2, inputMeans);

        // Проверка заполненость полей "Показания СИ (Среднее значение на контролируемом участке)"
        let checkAfterBefore =
            checkEmptyFields(
                `.mean`,
                `#concreteStrength`,
                `#calculationData input[readonly]:not('.do-not-clean')`
            );
        if (!checkAfterBefore) {
            return false;
        }

        // Прочность бетона (Контролируе-мого участка), МПа
        let arrAreaStrength = [];
        inputMean.each(function (i, item) {
            let mean = +$(item).val(),
                R = +inputR.val(),
                V = +inputV.val();

            let areaStrength = R * mean + V;
            let roundAreaStrength = round(areaStrength, 1);

            arrAreaStrength.push(areaStrength);
            $(inputAreaStrength.get(i)).val(roundAreaStrength.toFixed(1));

        });

        // Прочность бетона (Конструкции), МПа
        let averAreaStrength = average(arrAreaStrength);
        let roundAverAreaStrength = round(averAreaStrength, 2);
        inputResultValue.val(roundAverAreaStrength.toFixed(2));

        // Фактический класс бетона конструкции по прочности на сжатие
        let concreteClass = averAreaStrength / Kt;
        let roundConcreteClass = round(concreteClass, 1);
        inputConcreteClass.val(roundConcreteClass.toFixed(1));

        let S1 = getStandardDeviation(arrAreaStrength);
        inputS1.val(round(S1, 2).toFixed(2));

        let coefficient = method.split('_')[1];
        let S4 = coefficient * averAreaStrength;
        inputS4.val(round(S4, 2).toFixed(2));

        let S2 = Math.sqrt((S3*S3)+(S4*S4));
        inputS2.val(round(S2, 2).toFixed(2));

        let Sm = (S1+(S2/(Math.sqrt(areaStrengthCount-1))))*(1/(0.7 * r + 0.3));
        inputSm.val(round(Sm, 2).toFixed(2));

        let Vm = Sm * 100 / averAreaStrength;
        inputVm.val(round(Vm, 1).toFixed(1));

        // Факт. Прочность бетона в %
        let percent = (concreteClass * 100 / B);
        inputPercent.val(round(percent));
    });


    /**
     * @deprecated
     * Расчитать среднее значение на контролируемом участке
     * @param inputSingleValue1
     * @param inputSingleValue2
     * @param inputMeans
     */
    function meanCalculation(inputSingleValue1, inputSingleValue2, inputMeans)
    {
        inputSingleValue1.each(function (index, item) {
            let singleValue1 = $(item).val(),
                singleValue2 = $(inputSingleValue2[index]).val(),
                inputMean = $(inputMeans[index]);

            const decimalPlaces = 1;

            // Если одно из значений пусто, то расчёт среднего не производим
            if (singleValue1 === '' || singleValue2 === '') {
                return;
            }

            singleValue1 = +singleValue1;
            singleValue2 = +singleValue2;

            let mean = (singleValue1 + singleValue2) / 2;
            inputMean.val(round(mean, decimalPlaces).toFixed(decimalPlaces));
        });
    }

    /**
     * Метод получения стандартного отклонения из массива
     * @param array
     * @returns {number}
     */
    function getStandardDeviation(array) {
        const n = array.length;
        const mean = array.reduce((a, b) => a + b) / n;
        return Math.sqrt(array.map(x => Math.pow(x - mean, 2)).reduce((a, b) => a + b) / (n - 1));
    }


    /** Редактирование */
    body.on("input", ".concrete_strength_17624_7 #calculationData input:not([readonly])", function () {
        const inputReadonly = $(".concrete_strength_17624_7 #calculationData input[readonly]:not('.do-not-clean')");
        resetValue(inputReadonly);
    });

});

/**
 * Получить html для схемы "В"
 * @param result
 * @returns {string}
 */
function getHtmlForSchemeV(result, ugtpId)
{
    return `<div id="calculationData">
            <div class="row mb-3">
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R</div>
                        </div>
                        <input type="number" class="form-control bg-light-secondary do-not-clean" id="round-R" name="form_data[${ugtpId}][round_R]" step="any"
                               value="${result['data']['round_a']}" readonly>
                        <input type="hidden" class="form-control bg-light-secondary do-not-clean" id="R" 
                                name="form_data[${ugtpId}][R]" step="any" value="${result['data']['a']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">V</div>
                        </div>
                        <input type="number" class="form-control bg-light-secondary do-not-clean" id="round-V" name="form_data[${ugtpId}][round_V]" step="any"
                               value="${result['data']['round_b']}" readonly>
                        <input type="hidden" class="form-control bg-light-secondary do-not-clean" id="V" 
                                name="form_data[${ugtpId}][V]" step="any" value="${result['data']['b']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">r</div>
                        </div>
                        <input type="number" class="form-control bg-light-secondary do-not-clean" id="r" name="form_data[${ugtpId}][r]" step="any"
                               value="${result['data']['r']}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <span class="input-group-text">B</span>
                        <input type="text" class="form-control number-only bg-light-secondary do-not-clean" name="form_data[${ugtpId}][class]"
                               value="${result['data']['concrete_class'] ?? ''}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Прибор</div>
                        </div>
                        <input type="text" class="form-control bg-light-secondary do-not-clean" id="measuringDevice" 
                                name="form_data[${ugtpId}][measuring_device]" value="${result['data']['measuring_device'] ?? ''}" readonly>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <select id="method" class="form-control bg-light-secondary do-not-clean pointer-events-none" name="form_data[${ugtpId}][method]" readonly>
                            <optgroup label="Метод отрыва со скалыванием">
                                <option value="separation_0.04" ${'separation_0.04' === result['data']['method'] ? 'selected' : ''}>Глубина 48 мм</option>
                                <option value="separation_0.05" ${'separation_0.05' === result['data']['method'] ? 'selected' : ''}>Глубина 35 мм</option>
                                <option value="separation_0.06" ${'separation_0.06' === result['data']['method'] ? 'selected' : ''}>Глубина 30 мм</option>
                            </optgroup>
                            <optgroup label="Метод скалывания ребра">
                                <option value="chipping_0.04" ${'chipping_0.04' === result['data']['method'] ? 'selected' : ''}>Скалывание ребра</option>
                            </optgroup>
                            <optgroup label="Разрушающий метод">
                                <option value="destructive_0.02" ${'destructive_0.02' === result['data']['method'] ? 'selected' : ''}>Разрушающий</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group col">
                    <div class="input-group">
                        <input type="text" class="form-control number-only bg-light-secondary do-not-clean" id="dayToTest" name="form_data[${ugtpId}][day_to_test]"
                               value="${result['data']['day_to_test'] ?? ''}" readonly>
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
                            <th rowspan="2">+/-</th>
                        </tr>
                        <tr class="align-middle">
                            <th colspan="2">Единичные значения</th>
                            <th>Среднее значение на контролируемом участке</th>
                            <th>Контролируе-мого участка</th>
                            <th>Конструкции</th>
                        </tr>
                        </thead>
                        <tbody class="construction-wrapper">
                        <tr class="construction-row">
                            <td class="name-wrapper">
                                <input type="text" class="form-control bg-light-secondary do-not-clean" name="form_data[${ugtpId}][name_for_protocol]"  
                                       value="${result['name_for_protocol']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-white single-value-1" name="form_data[${ugtpId}][single_value_1][]"
                                       step="any" value="">
                            </td>
                            <td>
                                <input type="number" class="form-control single-value-2 ${result['measuring_device'] === 'ИПС' ? 'bg-light-secondary' : 'bg-white'}"
                                       name="form_data[${ugtpId}][single_value_2][]" step="any" value="" 
                                    ${result['measuring_device'] === 'ИПС' ? 'disabled' : ''}>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-white mean clear" name="form_data[${ugtpId}][mean][]"
                                       step="any" value="">
                            </td>
                            <td>
                                <input type="number" class="form-control bg-white area-strength bg-light-secondary" 
                                        name="form_data[${ugtpId}][single_values][]" tep="any" value="" readonly>
                            </td>
                            <td class="construction-strength-wrapper">
                                <input type="number" class="form-control bg-white bg-light-secondary" 
                                        name="form_data[${ugtpId}][result_value]" step="any" value="" readonly>
                            </td>
                            <td class="concrete-class-wrapper">
                                <input type="number" class="form-control bg-white bg-light-secondary" name="form_data[${ugtpId}][concrete_class]"
                                       step="any" value="" readonly>
                            </td>
                            <td>
                                <button class="btn mt-0 btn-square add-construction btn-primary" type="button">
                                    <i class="fa-solid fa-plus icon-fix"></i>
                                </button>
                            </td>
                        </tr>
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
                                <input type="number" class="form-control bg-light-secondary" name="form_data[${ugtpId}][S1]" 
                                step="any" value="" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary do-not-clean" name="form_data[${ugtpId}][S3]" step="any" 
                                value="${result['data']['S']}" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" name="form_data[${ugtpId}][S4]" 
                                step="any" value="" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" name="form_data[${ugtpId}][S2]" 
                                step="any" value="" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" name="form_data[${ugtpId}][Sm]" 
                                step="any" value="" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" name="form_data[${ugtpId}][Vm]" 
                                step="any" value="" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control bg-white" name="form_data[${ugtpId}][Kt]" step="any" value="1.070">
                            </td>
                            <td>
                                <input type="number" class="form-control bg-light-secondary" name="form_data[${ugtpId}][percent]" step="any" 
                                value="" readonly>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <lable for="comment">Примечание</lable>
                    <textarea class="form-control mw-100"
                              rows="5" id="comment" name="form_data[${ugtpId}][comment]"></textarea>
                </div>
            </div>
            
            <div class="line-dashed-small"></div>

            <div class="row btn-wrapper">
                <div class="col-auto pe-0">
                    <button type="button" class="btn btn-primary calculate-v me-2">Рассчитать</button>
                </div>
                <div class="col-auto ps-0">
                    <button type="submit" class="btn btn-primary save">Сохранить</button>
                </div>
            </div>
        </div>`;
}