$(function ($) {
    const body = $('body')

    let ugtpid = $("#dust_ugtp").val()

    //Содержание пылевидных и глинистых частиц, %
    function getClayParticleContent(sampleMassBefore, sampleMassAfter) {
        return ((sampleMassBefore - sampleMassAfter) / sampleMassBefore) * 100
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

    function getArithmeticMean(data, amountData) {
        if (!$.isArray(data) || data.length === 0 || amountData <= 0) {
            return false;
        }

        let sumData = data.reduce(function (acc, val) {
            return acc + val;
        }, 0);

        return sumData / amountData;
    }

    //Определение содержания пылевидных и глинистых частиц ГОСТ 33055-2014 (2 параллельных испытания с расхождением не более 0,5 %; результат - среднее арифмитическое значение с точностью до первого знака после запятой)
    body.on("click", "#particleContentCalculate", function () {
        const clayParticlesWrapper = $('.clay-particles-wrapper'),
            inputClayParticleContent1 = clayParticlesWrapper.find('.clay-particle-content-1')
        inputClayParticleContent2 = clayParticlesWrapper.find('.clay-particle-content-2')
        inputAverageParticleContent = clayParticlesWrapper.find('.average-particle-content'),
            inputReadonly = clayParticlesWrapper.find('input[readonly]:visible')

        let sampleMassBefore1 = +clayParticlesWrapper.find('.sample-mass-before-1').val(),
            sampleMassBefore2 = +clayParticlesWrapper.find('.sample-mass-before-2').val(),
            sampleMassAfter1 = +clayParticlesWrapper.find('.sample-mass-after-1').val(),
            sampleMassAfter2 = +clayParticlesWrapper.find('.sample-mass-after-2').val()

        clayParticlesWrapper.find(".messages").remove()


        let clayParticleContent1 = getClayParticleContent(sampleMassBefore1, sampleMassAfter1)
        let clayParticleContent2 = getClayParticleContent(sampleMassBefore2, sampleMassAfter2)

        inputClayParticleContent1.val(round(clayParticleContent1, 1).toFixed(1))
        inputClayParticleContent2.val(round(clayParticleContent2, 1).toFixed(1))


        let emptyClayParticleContent = clayParticlesWrapper.find('.clay-particle-content').filter(function () {
            return $(this).val() === ''
        })

        //Расхождение результатов двух параллельных испытаний не должно превышать 0,5%
        let diff = Math.abs(clayParticleContent1 - clayParticleContent2)

        if (diff > 0.5 && !emptyClayParticleContent.length) {
            let messageError = 'Расхождение результатов двух параллельных испытаний не должно превышать 0.5%, повторите испытание'

            let messageErrorContent = getMessageErrorContent(messageError)

            clayParticlesWrapper.prepend(messageErrorContent)

            inputAverageParticleContent.val('')
            return false
        }

        if (!emptyClayParticleContent.length) {
            let averageParticleContent = getArithmeticMean([clayParticleContent1, clayParticleContent2], 2)

            inputAverageParticleContent.val(round(averageParticleContent, 1).toFixed(1))
        }
    })

    body.on('click', '.clay-particles-wrapper .remove-this', function () {
        let container = $(this).closest('.rec')

        container.remove()
    })

    //Добавление строки (Содержание пылевидных и глинистых)
    let countAddDelMass1 = $('.add-del-mass-1').length
    body.on('click', 'button.add-mass-1', function () {
        let container = $(this).parents('.rec-wrapper')
        countAddDelMass1++

        container.append(
            `<tr class="rec">
                <td>
                    <input type="number" class="form-control"
                           name="form_data[${ugtpid}][form][clay_particle_content][mass_to_constant][1][${countAddDelMass1}]"
                           step="any" min="0" value="">
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-danger add-del-mass-1 remove-this mb-0">
                        <svg class="icon align-middle" width="15" height="15">
                            <use xlink:href="/production_laboratory/assets/icon/icons.svg#del"/>
                        </svg>
                    </button>
                </td>
            </tr>`
        )
    })

    let countAddDelMass2 = $('.add-del-mass-2').length
    body.on('click', 'button.add-mass-2', function () {
        let container = $(this).parents('.rec-wrapper')
        countAddDelMass2++

        container.append(
            `<tr class="rec">
                <td>
                    <input type="number" class="form-control"
                           name="form_data[${ugtpid}][form][clay_particle_content][mass_to_constant][2][${countAddDelMass2}]"
                           step="any" min="0" value="">
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-danger add-del-mass-2 remove-this mb-0">
                        <svg class="icon align-middle" width="15" height="15">
                            <use xlink:href="/production_laboratory/assets/icon/icons.svg#del"/>
                        </svg>
                    </button>
                </td>
            </tr>`
        )
    })
})