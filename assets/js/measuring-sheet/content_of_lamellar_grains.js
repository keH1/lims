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

    /* Сбросить значения */
    function resetValue(items) {
        items.each(function (index, item) {
            $(this).val("");
        });
    }

    //Содержание в каждой фракции щебня зерен пластинчатой и игловатой форм, %
    function getContentLamellarGrains(sampleMass, massLamellarGrains) {
        let response = {}

        if ( $.isEmptyObject(sampleMass) || $.isEmptyObject(massLamellarGrains) ) {
            return response
        }

        for (let fraction in massLamellarGrains) {
            if ( $.isEmptyObject(massLamellarGrains[fraction]) || $.isEmptyObject(sampleMass[fraction]) ) {
                return response
            }

            for (let trial in massLamellarGrains[fraction]) {
                let result =
                    (massLamellarGrains[fraction][trial] / sampleMass[fraction][trial]) * 100

                if (response[fraction] === undefined) {
                    response[fraction] = {}
                }

                response[fraction][trial] = round(result, 1)
            }
        }

        return response
    }

    //Определение содержания зерен пластинчатой (лещадной) и игловатой формы ГОСТ 33053-2014 (2 параллельных определения с расхождением не более 1%; результат среднее арифметическое значение с точностью до первого знака после запятой)
    body.on("click", "#calculateContentGrains", function () {
        const ONE = 1,
            TWO = 2

        const wrapperLamellarGrains = $('.wrapper-lamellar-grains'),
            inputContentGrainsMixture = wrapperLamellarGrains.find('.content-grains-mixture'),
            inputSampleMass = wrapperLamellarGrains.find('.sample-mass'),
            inputMassLamellarGrains = wrapperLamellarGrains.find('.mass-lamellar-grains'),
            inputContentLamellarGrains = wrapperLamellarGrains.find('.content-lamellar-grains'),
            inputAverageGrainsContent = wrapperLamellarGrains.find('.average-grains-content'),
            inputAveragePrivateRemainder = wrapperLamellarGrains.find('.average-private-remainder'),
            inputReadonly = wrapperLamellarGrains.find("input[readonly]:visible:not('.fraction')")

        let averagePrivateRemainder = getValuesObjectWithDataAttrKey(inputAveragePrivateRemainder, 'fraction')

        let sampleMass = getElementValuesByFractionAndTrial(inputSampleMass),
            massLamellarGrains = getElementValuesByFractionAndTrial(inputMassLamellarGrains)


        wrapperLamellarGrains.find(".messages").remove()

        if (inputContentGrainsMixture.length) {
            let emptyAveragePrivateRemainder = inputAveragePrivateRemainder.filter(function () {
                return $(this).val() === ''
            })

            if (emptyAveragePrivateRemainder.length) {
                let messageError = "Внимание! Отсутствует расчет зернового состава!"

                let messageErrorContent = getMessageErrorContent(messageError)

                wrapperLamellarGrains.prepend(messageErrorContent)

                resetValue(inputReadonly)
                return false
            }
        }

        let selectedFractionEmpty = wrapperLamellarGrains.find('.fraction').filter(function () {
            return $(this).val() === '' || $(this).val() === null
        })

        if (selectedFractionEmpty.length) {
            let messageError =
                "Внимание! Для расчета значений выбирете фракцию!"

            let messageErrorContent = getMessageErrorContent(messageError)

            wrapperLamellarGrains.prepend(messageErrorContent)

            resetValue(inputReadonly)
            return false
        }


        let contentLamellarGrains = getContentLamellarGrains(sampleMass, massLamellarGrains)

        for (let el of inputContentLamellarGrains) {
            let trial = $(el).data('trial');
            let fraction = $(el).data('fraction');

            $(el).val(contentLamellarGrains[fraction][trial].toFixed(1))
        }


        let averageGrainsContent = getArithmeticMeanByFractionAndTrial(contentLamellarGrains, 2, 1)

        for (const el of inputAverageGrainsContent) {
            let fraction = $(el).data('fraction'),
                tbody = $(el).closest('tbody')

            let difference = contentLamellarGrains[fraction][ONE] - contentLamellarGrains[fraction][TWO]

            //Расхождение результатов двух параллельных испытаний не должно превышать 1%,
            if (Math.abs(round(difference, 1)) > 1) {
                let fractionName = tbody.find('.fraction').find('option:selected').text()
                let messageError =
                    `Расхождение между результатами двух параллельных испытаний более 1%, 
            фракция ${fractionName}. Испытание необходимо повторить`

                let messageErrorContent = getMessageErrorContent(messageError)

                wrapperLamellarGrains.prepend(messageErrorContent)

                inputAverageGrainsContent.val('')
                inputContentGrainsMixture.val('')
                return false
            }

            $(el).val(round(averageGrainsContent[fraction], 1).toFixed(1))
        }


        let emptyAverageGrainsContent = $('.wrapper-lamellar-grains .average-grains-content').filter(function () {
            return $(this).val() === ''
        })

        if (inputContentGrainsMixture.length && !emptyAverageGrainsContent.length) {
            let contentGrainsMixture = getFractionsMixtureByFraction(averageGrainsContent, averagePrivateRemainder)

            inputContentGrainsMixture.val(round(contentGrainsMixture, 1).toFixed(1))
        }
    })
})