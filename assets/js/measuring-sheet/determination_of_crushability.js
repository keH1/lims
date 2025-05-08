$(function ($) {
    const body = $('body')

    let brands = ['М 1200', 'М 1000', 'М 800', 'М 600', 'М 400', 'М 300', 'М 200']

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

    function crushabilityBrand(crushability) {
        if(crushability <= 11) {
            return 'М 1200'
        }else if(crushability > 11 && crushability <= 13) {
            return 'М 1000'
        }else if(crushability > 13 && crushability <= 15) {
            return 'М 800'
        }else if(crushability > 15 && crushability <= 19) {
            return 'М 600'
        }else if(crushability > 19 && crushability <= 24) {
            return 'М 400'
        }else if(crushability > 24 && crushability <= 28) {
            return 'М 300'
        }else if(crushability > 28 && crushability <= 35) {
            return 'М 200'
        }else {
            return '--'
        }
    }

    //Получить значения элементов с помощью сита и испытания
    function getElementValuesByFractionAndTrial(items) {
        let data = {}

        if ( typeof obj !== 'object' && $.isEmptyObject(items) ) {
            return data
        }

        items.each(function (index, item) {
            let trial = $(item).data('trial'),
                fraction = $(item).data('fraction')

            if (data[fraction] === undefined) {
                data[fraction] = {}
            };

            data[fraction][trial] = +$(this).val()
        })

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

    //Дробимость, %
    function getCrushability(sampleMass, residueMass) {
        let response = {}

        if ( $.isEmptyObject(sampleMass) || $.isEmptyObject(residueMass) ) {
            return response
        }

        for (let fraction in sampleMass) {
            if ( $.isEmptyObject(sampleMass[fraction]) || $.isEmptyObject(residueMass[fraction]) ) {
                return response
            }

            for (let trial in sampleMass[fraction]) {
                let crushability =
                    ((sampleMass[fraction][trial] - residueMass[fraction][trial]) / sampleMass[fraction][trial]) * 100

                if (response[fraction] === undefined) {
                    response[fraction] = {}
                }

                response[fraction][trial] = round(crushability, 1)
            }
        }

        return response
    }

    /**
     * Среднее арифметическое значение по фракции и испытанию
     * @param data
     * @param amountData - кол-во данных
     * @param afterPoint - кол-во цифр после запятой
     * @returns {{}}
     */
    function getArithmeticMeanByFractionAndTrial(data, amountData, afterPoint) {
        let response = {}

        if (typeof data !== 'object' || $.isEmptyObject(data) || amountData <= 0) {
            return response
        }

        for (let fraction in data) {
            let sum = 0

            if ($.isEmptyObject(data[fraction])) {
                continue
            }

            for (let trial in data[fraction]) {
                sum += data[fraction][trial]
            }

            if (response[fraction] === undefined) {
                response[fraction] = {}
            }

            let arithmeticMean = sum / amountData

            response[fraction] = round(arithmeticMean, afterPoint)
        }

        return response;
    }

    //Cредневзвешенное значение определяемого показателя в соответствии с содержанием фракции в смеси
    function getFractionsMixtureByFraction(average, privateRemaindersByMass) {
        if ( $.isEmptyObject(average) ) {
            return false
        }

        let numerator = 0,
            sumPrivateRemaindersByMass = 0,
            fraction

        for (const index in average) {
            //TODO: Исправить костыль
            switch (index) {
                case '4_5.6':
                    fraction = '4'
                    break
                case '5.6_8':
                    fraction = '5_6'
                    break
                case '8_11.2':
                    fraction = '8'
                    break
                case '11.2_16':
                    fraction = '11_2'
                    break
                case '16_22.4':
                    fraction = '16'
                    break
                case '22.4_31.5':
                    fraction = '22_4'
                    break
                case '31.5_45':
                    fraction = '31_5'
                    break
                case '45_63':
                    fraction = '63'
                    break
                case '63_90':
                    fraction = '90'
                    break
            }

            numerator += (average[index] * privateRemaindersByMass[fraction])

            sumPrivateRemaindersByMass += privateRemaindersByMass[fraction]
        }

        if (sumPrivateRemaindersByMass <= 0) {
            return false
        }

        return (numerator / sumPrivateRemaindersByMass)
    }

    //Определение дробимости ГОСТ 33030-2014 (2 параллельных определения с расхождением не более 2%; результат - среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#сalculateСrushability", function () {
        const ONE = 1,
            TWO = 2

        console.log('HELLO WORLD')

        const crushabilityWrapper = $('.crushability-wrapper'),
        inputAveragePrivateRemainder = crushabilityWrapper.find('.average-private-remainder'),
            inputSampleMass = crushabilityWrapper.find('.sample-mass'),
            inputResidueMass = crushabilityWrapper.find('.residue-mass'),
            inputCrushability = crushabilityWrapper.find('.crushability'),
            inputCrushabilityBrand = crushabilityWrapper.find('.crushability-brand'),
            inputArithmeticMean = crushabilityWrapper.find('.arithmetic-mean'),
            inputCrushabilityMixture = crushabilityWrapper.find('.crushability-mixture'),
            inputStrengthGrade = crushabilityWrapper.find('.strength-grade'),
            inputReadonly = crushabilityWrapper.find("input[readonly]:visible:not('.fraction')")

        let averagePrivateRemainder = getValuesObjectWithDataAttrKey(inputAveragePrivateRemainder, 'fraction')

        let sampleMass = getElementValuesByFractionAndTrial(inputSampleMass),
            residueMass = getElementValuesByFractionAndTrial(inputResidueMass)

        crushabilityWrapper.find(".messages").remove();

        if ($('.crushability-wrapper .breed-condition').val() === null) {
            let messageError =
                "Внимание! Для расчета значений выбирете сотояние породы!";

            let messageErrorContent = getMessageErrorContent(messageError)

            crushabilityWrapper.prepend(messageErrorContent)

            resetValue(inputReadonly)
            return false
        }


        if (inputCrushabilityMixture.length) {
            let emptyAveragePrivateRemainder = inputAveragePrivateRemainder.filter(function () {
                return $(this).val() === ''
            })

            if (emptyAveragePrivateRemainder.length) {
                let messageError = "Внимание! Отсутствует расчет зернового состава!"

                let messageErrorContent = getMessageErrorContent(messageError)

                crushabilityWrapper.prepend(messageErrorContent)

                resetValue(inputReadonly)
                return false
            }
        }

        let selectedFractionEmpty = crushabilityWrapper.find('.fraction').filter(function () {
            return $(this).val() === '' || $(this).val() === null
        })

        if (selectedFractionEmpty.length) {
            let messageError =
                "Внимание! Для расчета значений выбирете фракцию!"

            let messageErrorContent = getMessageErrorContent(messageError)

            crushabilityWrapper.prepend(messageErrorContent)

            resetValue(inputReadonly)
            return false
        }


        let crushability = getCrushability(sampleMass, residueMass)

        for (const element of inputCrushability) {
            let trial = $(element).data("trial")
            let fraction = $(element).data("fraction")

            $(element).val(round(crushability[fraction][trial], 1).toFixed(1))
        }


        let averageCrushability = getArithmeticMeanByFractionAndTrial(crushability, 2, 1)

        let currentIndex = 0
        let crushArray = []

        for (const element of inputArithmeticMean) {
            let fraction = $(element).data("fraction"),
                tbody = $(element).closest('tbody')

            let difference = crushability[fraction][ONE] - crushability[fraction][TWO]

            //Расхождение результатов двух параллельных испытаний не должно превышать 2%,
            if (Math.abs(round(difference, 1)) > 2) {
                let fractionName = tbody.find('.fraction').find('option:selected').text()
                let messageError =
                    `Расхождение между результатами двух параллельных испытаний более 2%, 
            фракция ${fractionName}. Испытание необходимо повторить`

                let messageErrorContent = getMessageErrorContent(messageError)

                crushabilityWrapper.prepend(messageErrorContent)

                inputArithmeticMean.val('')
                inputCrushabilityMixture.val('')
                inputCrushabilityBrand.val('')
                inputStrengthGrade.val('')
                return false
            }

            $(element).val(round(averageCrushability[fraction], 1).toFixed(1))

            $('.crushability-brand')[currentIndex].value = crushabilityBrand($(element).val())

            crushArray.push(brands.indexOf(crushabilityBrand($(element).val())))

            currentIndex++
        }

        if($('#strengthGrade')) {
            let avgCrush = average(crushArray)

            $('#strengthGrade').val(brands[avgCrush])
        }

        //Марка по дробимости (TODO: Доработать, добавить щебень из осадочных и метаморфических попрод И щебень из изверженных пород)
        // if (!inputCrushabilityMixture.length) {
        //     for (const element of inputCrushabilityBrand) {
        //         let fraction = $(element).data("fraction");
        //
        //         let crushabilityBrand = getCrushabilityBrand(round(averageCrushability[fraction], 1))
        //
        //         $(element).val(crushabilityBrand)
        //     }
        // }


        let emptyCrushability = $('.crushability-wrapper .crushability').filter(function () {
            return $(this).val() === ''
        })

        //Щебень (гравий), состоящего из смеси фракций
        if (inputCrushabilityMixture.length && !emptyCrushability.length) {
            let crushabilityMixture = getFractionsMixtureByFraction(averageCrushability, averagePrivateRemainder)

            inputCrushabilityMixture.val(round(crushabilityMixture, 1).toFixed(1))

            //Марка по дробимости (TODO: Доработать, добавить щебень из осадочных и метаморфических попрод И щебень из изверженных пород)
            // let crushabilityBrand = getCrushabilityBrand(fractionsMixtureCrushability)
            //
            // inputStrengthGrade.val(crushabilityBrand)
        }
    })
})