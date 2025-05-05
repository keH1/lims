$(function ($) {
    const body = $('body')

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

    //Насыпная плотность, кг/м3
    function getBulkDensity(vesselWithSample, emptyVesselMass, conteinerCapacity) {
        let bulkDensity =
            (vesselWithSample - emptyVesselMass) /
            conteinerCapacity

        return round(bulkDensity, 2)
    }

    function getArithmeticMean(data, amountData) {
        if (!$.isArray(data) || data.length === 0 || amountData <= 0) {
            return false;
        }

        let sumData = data.reduce(function (acc, val) {
            return acc + val;
        }, 0);

        return sumData / amountData;
    }

    /* Сбросить значения */
    function resetValue(items) {
        items.each(function (index, item) {
            $(this).val("");
        });
    }

    // Определение насыпной плотности ГОСТ 33047-2014 (3 параллельных испытания с наибольшим расхождением не более 0,1 г/см3; результат - среднее арифметическое значение с точностью довторого десятичного числа)
    body.on("click", "#сalculateBulkDensity", function () {
        const ONE = 1,
            TWO = 2,
            THREE = 3

        const bulkDensityWrapper = $('.bulk-density-wrapper'),
            inputVesselWithSample = bulkDensityWrapper.find('.vessel-with-sample'),
            inputEmptyVesselMass = bulkDensityWrapper.find('.empty-vessel-mass'),
            inputCylinderVolume = bulkDensityWrapper.find('.cylinder-volume'),
            inputbulkDensityAverage = bulkDensityWrapper.find('.bulk-density-average'),
            inputReadonly = bulkDensityWrapper.find('input[readonly]:visible')

        let vesselWithSample = getValuesObjectWithDataAttrKey(inputVesselWithSample, 'trial'),
            emptyVesselMass = getValuesObjectWithDataAttrKey(inputEmptyVesselMass, 'trial'),
            conteinerCapacity = getValuesObjectWithDataAttrKey(inputCylinderVolume, 'trial')

        bulkDensityWrapper.find(".messages").remove()


        let bulkDensityOne = getBulkDensity(
            vesselWithSample[ONE],
            emptyVesselMass[ONE],
            conteinerCapacity[ONE]
        )

        let bulkDensityTwo = getBulkDensity(
            vesselWithSample[TWO],
            emptyVesselMass[TWO],
            conteinerCapacity[TWO]
        )

        let bulkDensityThree = getBulkDensity(
            vesselWithSample[THREE],
            emptyVesselMass[THREE],
            conteinerCapacity[THREE]
        )

        $(`.bulk-density[data-trial=${ONE}]`).val(bulkDensityOne.toFixed(2))
        $(`.bulk-density[data-trial=${TWO}]`).val(bulkDensityTwo.toFixed(2))
        $(`.bulk-density[data-trial=${THREE}]`).val(bulkDensityThree.toFixed(2))


        let bulkDensityAverage = getArithmeticMean([bulkDensityOne, bulkDensityTwo, bulkDensityThree], 3)

        inputbulkDensityAverage.val(round(bulkDensityAverage, 2).toFixed(2))


        let maxBulkDensity = Math.max(bulkDensityOne,bulkDensityTwo,bulkDensityThree)
        let minBulkDensity = Math.min(bulkDensityOne,bulkDensityTwo,bulkDensityThree)
        let diff = maxBulkDensity - minBulkDensity

        if(diff > 0.1) {
            let messageError = 'Расхождение результатов трех параллельных испытаний превышает 0,1 г/см<sup>3</sup>, повторите испытание!'

            let messageErrorContent = getMessageErrorContent(messageError)

            bulkDensityWrapper.prepend(messageErrorContent)
            resetValue(inputReadonly)
            return false
        }
    })
})