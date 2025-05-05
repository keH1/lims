// Методика с расчетом среднего значения
$(function ($) {
    const body = $('body');

    body.on('click', '#frostGostCalculate', function () {
        const wrapperFrostGost = $('#frost_gost'),
            inputBefore = wrapperFrostGost.find('.mass-before'),
            inputAfter = wrapperFrostGost.find('.mass-after'),
            inputMassLoss = wrapperFrostGost.find('.mass-loss'),
            inputContentOfFraction = wrapperFrostGost.find('.content-of-fraction'),
            inputResultFrostGost= wrapperFrostGost.find('.result-frost-gost')

        let lossRes = []

        inputBefore.each(function (i) {
            if(inputAfter[i].value && $(this).val()) {
                let before = $(this).val()
                let after = inputAfter[i].value

                let currentLossRes = ((before - after) * 100) / before

                inputMassLoss[i].value = currentLossRes

                lossRes.push(currentLossRes)
            }else {
                lossRes.push(inputMassLoss[i].value)
            }
        })

        let top = 0
        let bottom = 0

        inputContentOfFraction.each(function (i) {
            top += Number(lossRes[i] * inputContentOfFraction[i].value)
            bottom += Number(inputContentOfFraction[i].value)
        })

        let result = (top / bottom).toFixed(1)

        inputResultFrostGost.val(result)
    })

    body.on('click', '.add-frost', function () {
        let currentNum = Number($($('.frost_block')[$('.frost_block').length - 1]).data('frost-id'))
        let frostList = $('.frost_list')
        let ugtpId = $('#ugtp_id_frost').val()

        let nextNumber = currentNum + 1

        let span = $('.span-input')

        let spanCount = Number(span.attr('rowspan')) + 1

        span.attr('rowspan', spanCount)

        frostList.append(`
        <tr class="frost_block" data-frost-id="${nextNumber}">
                <td>
                    <input type="number" id="mass-before-${ugtpId}-${nextNumber}" class="form-control mass-before" name="form_data[${ugtpId}][form][mass_before][]">
                </td>
                <td>
                    <input type="number" id="mass-after-${ugtpId}-${nextNumber}" class="form-control mass-after" name="form_data[${ugtpId}][form][mass_after][]">
                </td>
                <td>
                    <input type="number" id="mass-loss-${ugtpId}-${nextNumber}" class="form-control mass-loss" name="form_data[${ugtpId}][form][mass_loss][]">
                </td>
                <td>
                    <input type="number" id="content-of-fraction-${ugtpId}-${nextNumber}" class="form-control content-of-fraction" name="form_data[${ugtpId}][form][content_of_fraction][]">
                </td>
                <td>
                    <button class="btn btn-success add-frost" type="button" style="border-radius: 10px">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.2502 6C11.2502 5.58579 11.586 5.25 12.0002 5.25C12.4145 5.25 12.7502 5.58579 12.7502 6V11.2502H18.0007C18.4149 11.2502 18.7507 11.586 18.7507 12.0002C18.7507 12.4145 18.4149 12.7502 18.0007 12.7502H12.7502V18.0007C12.7502 18.4149 12.4145 18.7507 12.0002 18.7507C11.586 18.7507 11.2502 18.4149 11.2502 18.0007V12.7502H6C5.58579 12.7502 5.25 12.4145 5.25 12.0002C5.25 11.586 5.58579 11.2502 6 11.2502H11.2502V6Z" fill="#ffffff"/>
                        </svg>
                    </button>
                    <button class="btn btn-danger del-frost" type="button" style="border-radius: 10px">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.25 12C5.25 11.5858 5.58579 11.25 6 11.25H18.0007C18.4149 11.25 18.7507 11.5858 18.7507 12C18.7507 12.4142 18.4149 12.75 18.0007 12.75H6C5.58579 12.75 5.25 12.4142 5.25 12Z" fill="#ffffff"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `)
    })

    body.on('click', '.del-frost', function () {
        let span = $('.span-input')

        let spanCount = Number(span.attr('rowspan')) - 1

        span.attr('rowspan', spanCount)

        $(this).parent().parent().remove()
    })
});