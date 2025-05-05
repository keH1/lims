$(function ($) {
    const body = $('body')

    /** Получить объект значений элементов с ключом data атрибута
     * items - элементы
     * dataAttr - наименование data аттрибута
     */
    function getValuesObjectWithDataAttrKey(items, dataAttr) {
        let data = {}

        if ( (typeof obj !== 'object' && $.isEmptyObject(items)) || !dataAttr ) {
            return data
        }

        items.each(function (index, item) {
            let attr = $(item).data(dataAttr)

            data[attr] = +$(this).val()
        });

        return data
    }

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

    /**
     * Среднее арифметическое значение по испытанию
     * @param data
     * @param amountData - кол-во данных
     * @param afterPoint - кол-во цифр после запятой
     * @returns {{}}
     */
    function getArithmeticMeanByTrial(data, amountData, afterPoint) {
        let response = {}

        if (typeof data !== 'object' || $.isEmptyObject(data) || amountData <= 0) {
            return response
        }

        let sum = 0

        for (let trial in data) {
            if ( isNaN(data[trial]) ) {
                return response
            }

            sum += data[trial]
        }

        let arithmeticMean = sum / amountData

        return round(arithmeticMean, afterPoint)
    }

    //Марка по истираемости
    function  getAbrasionBrand(number) {
        number = +number

        if (number <= 10) {
            return 'МД1'
        } else if(number > 10 && number <= 15) {
            return 'МД2'
        } else if(number > 15 && number <= 20) {
            return 'МД3'
        } else if(number > 20 && number <= 25) {
            return 'МД4'
        } else if(number > 25 && number <= 35) {
            return 'МД5'
        } else if(number > 35) {
            return 'МД6'
        } else {
            return 'Не соответствует'
        }
    }

    //Истираемость
    function getAbrasion(sampleMassBefore, massOfResidues) {
        let response = {}

        if ( $.isEmptyObject(sampleMassBefore) || $.isEmptyObject(massOfResidues) ) {
            return response
        }

        for (let trial in sampleMassBefore) {
            let abrasion =
                ((sampleMassBefore[trial] - massOfResidues[trial]) / sampleMassBefore[trial]) * 100

            response[trial] = round(abrasion, 1)
        }

        return response
    }

    /* Сбросить значения */
    function resetValue(items) {
        items.each(function (index, item) {
            $(this).val("");
        });
    }

    //Определение сопротивления истираемости по показателю микро-Деваль ГОСТ 33024-2014 (2 параллельных определения с расхождением не более 1%; результат - среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateAbrasion", function () {
        const ONE = 1,
            TWO = 2

        const abrasionWrapper = $('.abrasion-wrapper'),
            inputAbrasionMixture = abrasionWrapper.find('.abrasion-mixture'),
            inputAbrasion = abrasionWrapper.find('.abrasion'),
            inputArithmeticMean = abrasionWrapper.find('.arithmetic-mean'),
            inputBrand = abrasionWrapper.find('.brand'),
            inputSampleMassBefore = abrasionWrapper.find('.sample-mass-before'),
            inputMassOfResidues = abrasionWrapper.find('.mass-of-residues'),
            inputReadonly = abrasionWrapper.find("input[readonly]:visible:not('.fraction')")


        let sampleMassBefore = getValuesObjectWithDataAttrKey(inputSampleMassBefore, 'trial'),
            massOfResidues = getValuesObjectWithDataAttrKey(inputMassOfResidues, 'trial')


        abrasionWrapper.find(".messages").remove();


        let selectedFractionEmpty = abrasionWrapper.find('.fraction').filter(function () {
            return $(this).val() === '' || $(this).val() === null
        })

        if (selectedFractionEmpty.length) {
            let messageError =
                "Внимание! Для расчета значений выбирете фракцию!"

            let messageErrorContent = getMessageErrorContent(messageError)

            abrasionWrapper.prepend(messageErrorContent)

            resetValue(inputReadonly)
            return false
        }

        let abrasion = getAbrasion(sampleMassBefore, massOfResidues)

        for (let el of inputAbrasion) {
            let trial = $(el).data('trial')

            if ( isNaN(abrasion[trial]) ) {
                continue
            }

            $(el).val(abrasion[trial].toFixed(1))
        }


        let arithmeticMean = getArithmeticMeanByTrial(abrasion, 2, 1)


        let tbody = inputArithmeticMean.closest('tbody')

        let difference = abrasion[ONE] - abrasion[TWO]

        //Расхождение результатов двух параллельных испытаний не должно превышать 1%,
        if (Math.abs(round(difference, 1)) > 1) {
            let fractionName = tbody.find('.fraction').find('option:selected').text()
            let messageError =
                `Расхождение между результатами двух параллельных испытаний более 1%, 
        фракция ${fractionName}. Испытание необходимо повторить`

            let messageErrorContent = getMessageErrorContent(messageError)

            abrasionWrapper.prepend(messageErrorContent)

            inputArithmeticMean.val('')
            return false
        }

        inputArithmeticMean.val(round(arithmeticMean, 1).toFixed(1))


        //Марка
        if (inputArithmeticMean.val() !== '') {
            let wearResistanceBrand = getAbrasionBrand(round(arithmeticMean, 1))

            inputBrand.val(wearResistanceBrand)
        }
    })
})