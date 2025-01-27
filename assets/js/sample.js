let methodList = null
let conditionList = null

// $.ajax({
//     method: 'POST',
//     url: '/ulab/sample/getMethodsAjax',
//     dataType: 'json',
//     success: function (data) {
//         methodList = data
//     }
// })
//
// $.ajax({
//     method: 'POST',
//     url: '/ulab/sample/getTechCondListAjax',
//     dataType: 'json',
//     success: function (data) {
//         conditionList = data
//     }
// })

function formatState(state) {
    const option = $(state.element)
    const color = option.data("color")
    const bgColor = option.data("bg-color")
    const count = option.data("count")
    let style = ''
    let text = state.text

    if ( color ) {
        style += `color: ${color};`
    }

    if ( bgColor ) {
        style += `background-color: ${bgColor};`
    }

    if ( count !== undefined ) {
        style += `display:flex;justify-content: space-between;`
        text = `<div>${state.text}</div><div>(${count})</div>`
    }

    if (style === '') {
        return text
    }

    return $(`<div style="${style}">${text}</div>`)
}

function toggleGroup() {
    let checkedCount = $('.probe-check:checked').length

    if ( checkedCount > 0 ) {
        $('.group-button').removeClass('disabled')
        $('.newAct').removeClass('disabled')
        // $('#accordionFlushGroup .collapse').collapse('show')
    } else {
        $('.group-button').addClass('disabled')
        $('.newAct').addClass('disabled')
        // $('#accordionFlushGroup .collapse').collapse('hide')
    }
}


$(function ($) {

    $('.popup-with-form').magnificPopup({
        type: 'inline',
        closeBtnInside:true,
        closeOnBgClick: false,
        fixedContentPos: false
    })

    let $body = $('#workarea-content')

    $('select.select2').select2({
        theme: 'bootstrap-5',
        templateResult: formatState,
        templateSelection: formatState,
    })

    $body.on('change', '.material-check', function () {
        $(this).parents('.material-item').find('.probe-check').prop("checked", $(this).prop("checked"))
        toggleGroup()
    })

    $body.on('change', '.probe-check', function () {

        let parent = $(this).parents('.material-item'),
            all = true,
            curState = $(this).prop("checked"),
            creatAct = $body.find('#btn-create-act')

        parent.find('.probe-check').each(function () {
            return all = ($(this).prop("checked") === curState)
        })

        // if (curState) {
        //     creatAct.removeClass('disabled')
        // }

        if ( all ) {
            parent.find('.material-check').prop({
                indeterminate: false,
                checked: curState
            })
            // creatAct.addClass('disabled')
        } else {
            parent.find('.material-check').prop({
                indeterminate: true,
                checked: false
            })

        }
        toggleGroup()
    })

    let countMaterial = $body.find('.material-item').length
    // добавление материала
    $('.add-new-material').click(function () {
        const materialId = $('#new-material').val()
        const materialName = $('#new-material option:selected').text()
        let probeCount = $('#new-count-probe').val()
        let htmlMethod = ''
        let htmlProbe = ''

        if ( materialId == '' ) {
            return false
        }
        if ( !(probeCount >= 1) ) {
            probeCount = 1
        }

        for ( let i = 0; i < probeCount; i++ ) {
            htmlMethod = getHtmlMethod(materialId, 'new_' + i)
            htmlProbe += getHtmlProbe(materialId, countMaterial, i, htmlMethod)
        }

        let htmlMaterial = getHtmlMaterial(materialId, countMaterial, materialName, htmlProbe)

        $('.material-block').append(htmlMaterial)

        $body.find('.material-item:last-child .select2').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,
            templateSelection: formatState,
        })

        countMaterial++
    })

    // развернуть материалы
    $body.on('click', '.expand-all-material', function () {
        $('.material-item > .collapse').collapse('show')
    })
    // свернуть пробы
    $body.on('click', '.collapse-all-material', function () {
        $('.material-item > .collapse').collapse('hide')
    })

    // свернуть пробы
    $body.on('click', '.expand-all', function () {
        // const $block = $(this).parents('div.accordion-body').find('.accordion').find('.collapse.show');
        // $.each($block, function (i, item) {
        //     $(item).collapse('hide')
        // })
        $(this).parents('div.accordion-body').find('.accordion').find('.show').collapse('hide')
    })
    // развернуть пробы
    $body.on('click', '.collapse-all', function () {
        // const $block = $(this).parents('div.accordion-body').find('.accordion').find('.collapse');
        // $.each($block, function (i, item) {
        //     $(item).collapse('show')
        // })
        $(this).parents('div.accordion-body').find('.accordion').find('.collapse').collapse('show')
    })

    // добавить пробу в материал
    $body.on('click', '.add-probe', function () {
        const $materialItem   = $(this).parents('.material-item')
        const materialId      = $materialItem.data('material_id')
        const $probeItem      = $materialItem.find('.probe-item')

        let numberMaterial = $materialItem.data('number-material'),
            countNewProbe = $materialItem.find('.new-count-probe').val()

        let num = $probeItem.map(function() {
            return $(this).data('probe_number');
        }).get();

        let countProbe = Math.max.apply(Math, num) + 1;

        for (let i = 0; i < countNewProbe; i++) {

            $materialItem.find('.probe-item:last-child').after(getHtmlProbe(materialId, numberMaterial, countProbe, getHtmlMethod(materialId, 'new_' + countProbe)))

            $materialItem.find('.probe-item:last-child').find('.select2').select2({
                theme: 'bootstrap-5',
                templateResult: formatState,
                templateSelection: formatState,
            })

            countProbe++
        }

    })

    // Sticky
    let $workArea = $("#workarea-content")
    let $accordionItem = $("#accordionFlushGroup .accordion-item")
    let $displayButtonProbe = $("#accordionFlushGroup").next()
    
    let workAreaOffset = $workArea.offset().top
    let accordionItemOffset = $accordionItem.offset().top
    let mainWidth = $("#accordionFlushGroup").width()
    let mainHeight = $("#accordionFlushGroup").height()


    $body.on('click', '#accordionFlushGroup', function () {
        let accordionOpen = $("#flush-heading-group .accordion-button").attr("aria-expanded")

        if (accordionOpen != "false") {
            $("#flush-heading-group .accordion-button").css("color", "#0c63e4")
            //element.removeAttr("style")
        } else {
            $("#flush-heading-group .accordion-button").css("color", "#fff")
            //element.css("width", mainWidth)
            //element.css("height", mainHeight)
        }
    }) 
    
    $(window).scroll(function() {
        let accordionOpen = $("#flush-heading-group .accordion-button").attr("aria-expanded")

        if($(window).scrollTop() + accordionItemOffset > workAreaOffset) {
            $accordionItem.css("display", "block")

            $accordionItem.addClass("fixed")
            $accordionItem.css("width", mainWidth)
            $accordionItem.css("height", mainHeight)
            if (accordionOpen != "false") {
                $displayButtonProbe.addClass("smooth-offset")
            } else {
                $displayButtonProbe.removeClass("smooth-offset")
            }
        } else {
            $accordionItem.css("display", "none")
            $accordionItem.removeClass("fixed")
            $accordionItem.removeAttr("style")
            $displayButtonProbe.removeClass("smooth-offset")
        }
    })

    // добавить испытание к пробе
    $body.on('click', '.add-method-to-probe', function () {
        const $probeItem = $(this).parents('.probe-item')
        const materialId = $(this).parents('.material-item').data('material_id')
        const probeId = $probeItem.data('probe_id')

        let num = $probeItem.find('.method-block').map(function() {
            return $(this).data('gost_number')
        }).get();

        let countMethod = Math.max.apply(Math, num) + 1;

        $probeItem.find('.method-block:last-child').after(getHtmlMethod(materialId, probeId, countMethod))
        $probeItem.find('.select2').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,
            templateSelection: formatState,
        })
    })

    // добавление методики группе
    $body.on('click', '.add-group-method', function () {
        const $curBlock = $(this).parents('.method-block')
        const $methodSelect = $curBlock.find('.method-select')
        const methodId = $methodSelect.val()

        // $(`.method-select option[value="${methodId}"]`).detach()

        const $checkProbe = $body.find('.probe-check:checked')

        let validateResult = true
        // валидация
        $.each(methodId, function (i, val) {
            let count = $(`.method-select option[value="${val}"]`).data('count')
            count -= $checkProbe.length

            if ( count < 0 ) {
                validateResult = false
            }
        })

        if ( !validateResult ) {
            $('.error-msg-block').append(getErrorMessage("Невозможно добавить."))
            return false
        }

        $.each(methodId, function (i, val) {
            let count = $(`.method-select option[value="${val}"]`).data('count')
            count -= $checkProbe.length
            $(`.method-select option[value="${val}"]`).data('count', count)
            $(`span[id$="-${val}"] > div > div:last-child`).text(`(${count})`)

            for (let i = 0; i < $checkProbe.length; i++) {
                const $probeItem = $($checkProbe.get(i)).parents('.probe-item')
                const $methodContainer = $probeItem.find('.method-container')
                const $methodBlock = $methodContainer.find('.method-block')
                const materialId = $($checkProbe.get(i)).parents('.material-item').data('material_id')
                const probeId = $probeItem.data('probe_id')

                let countMethod = 0


                if ( $methodBlock.length === 1 && $methodBlock.find('.empty-methods').length === 1 ) {
                    $methodBlock.find('.empty-methods').remove()
                    $methodBlock.remove()
                } else if ($methodBlock.length >= 1) {
                    $methodBlock.find('.empty-methods').remove()
                    let num = $methodBlock.map(function () {
                        return $(this).data('gost_number')
                    }).get();
                    countMethod = Math.max.apply(Math, num) + 1;
                }

                //const htmlMethod = getHtmlMethod(materialId, probeId, countMethod, methodId, tuId, userId, price) // !!!!!!!!!!!!!
                const htmlMethod = getHtmlMethod(materialId, probeId, 'new_' + countMethod, countMethod, val)



                $methodContainer.append(htmlMethod)

                $probeItem.find('.method-block:last-child').find('.select2').select2({
                    theme: 'bootstrap-5',
                    templateResult: formatState,
                    templateSelection: formatState,
                })
            }
        })
        $methodSelect.val([]).change()
        // $curBlock.find('ul').empty()

        // alert("Добавлено")
    })

    $('body').on('click', '.btn-create-act', function() {
        let $checkProbe = $body.find('.probe-check:checked'),
            deal_id = $body.find('.deal-id').val(),
            tz_id = $body.find('.tz-id').val(),
            $arrObjects = new Object(),
            tableAct = $body.find('.table_act'),
            $dateTimeInput = $(this).parents('#actCreatInformation').find('.deliveryDate'),
            $dateTime = $dateTimeInput.val(),
            $deliveryInput = $(this).parents('#actCreatInformation').find('.deliveryman'),
            $delivery = $deliveryInput.val()



        for (let i = 0; i < $checkProbe.length; i++) {
            let $probeId = $($checkProbe.get(i)).parents('.probe-item').data('probe_id'),
                $probeName = $($checkProbe.get(i)).parents('.probe-item').find('.probe-name').text(),
                $delivDateProbe = $($checkProbe.get(i)).parents('.probe-item').find('.date-delivery'),
                $materialId = $($checkProbe.get(i)).parents('.material-item').data('material_id'),
                $haveAct = $($checkProbe.get(i)).parents('.probe-item').attr('data-act_id')

            console.log($($checkProbe.get(i)).parents('.probe-item').attr('data-act_id'))
            if ($haveAct !== '') {
                showErrorMessage(`Проба ${$probeName} уже включена в акт`)
                continue
            }
            if ($arrObjects[$materialId] === undefined) {
                $arrObjects[$materialId] = []
            }
                $delivDateProbe.val($dateTime)
                $arrObjects[$materialId].push($probeId)
        }

        $.ajax({
            method: 'POST',
            url: '/ulab/probe/createActAjax',
            data: {
                arrProbe: $arrObjects,
                tz_id: tz_id,
                deal_id: deal_id,
                dateTime: $dateTime,
                delivery: $delivery
            },
            dataType: 'json',
            success: function (data) {

                $.each(data.actBase, function (id_act, act) {
                    let actHtml = `<tr class="act-tr">
                                   <td>
                                        <a href="#"
                                           class="text-dark text-decoration-none text-nowrap fw-bold">
                                        ${act.ACT_NUM}
                                        </a>
                                   </td>
                                   <td>
                                        ${act.new_date}
                                   </td>
                                   <td>
                                        ${act.material}
                                   </td>                                    
                                   <td>
                                        <a class="generate-act"
                                            href="/protocol_generator/probe.php?idAct=${id_act}&ID=${deal_id}"
                                            title="Cкачать акт приемки проб">
                                            <svg class="icon" width="30" height="30">
                                            <use xlink:href="/ulab/assets/images/icons.svg#doc-send"/>
                                            </svg>
                                        </a>
                                   </td>
                                   <td>
                                        <button class="btn btn-danger mt-0 delete-act btn-square"
                                            type="button" data-id-act="${id_act}">
                                            <i class="fa-solid fa-minus icon-fix"></i>
                                        </button>
                                   </td>
                                   <td>
                                        ${act.creator}
                                   </td>
                                </tr>`

                    tableAct.append(actHtml)

                    $.each(data.cipher, function (i, val) {
                        $(`[data-probe_id="${val.id}"]`).find('.probe-name').text(`${val.cipher} Акт № ${act.ACT_NUM} от ${act.new_date}`)
                        $(`[data-probe_id="${val.id}"]`).attr('data-act_id', id_act)
                    })
                    showSuccessMessage(`Акт № ${act.ACT_NUM} от ${act.new_date} успешно создан`)

                })
                $dateTimeInput.val('')
                $deliveryInput.val('')
                $.magnificPopup.close();
            }
        })

    })

    $body.on('click', '.add-group-data', function() {
       
        const place = $(".input-place").val()
        const sampleDate = $(".input-date-sample").val()
        const deliveryDate = $(".input-date-delivery").val()

        let $checkProbe = $body.find('.probe-check:checked')

        for (let i = 0; i < $checkProbe.length; i++) {
            let $probeItem = $($checkProbe.get(i)).parents('.probe-item')
            let $inputBlock = $($probeItem).find(".header-inputs")
            $inputBlock.find(".place").val(place)
            $inputBlock.find(".date-sample").val(sampleDate)
            $inputBlock.find(".date-delivery").val(deliveryDate)
        }

        // let changeInputVal = $(this).val()
        // inputFullName = $(this).attr("class").split(' ')
        // inputFullClassName = inputFullName[1].split("-")
        // let input = inputFullClassName[1]
        // let checkProbe = $body.find('.probe-check:checked')

        // for (let i = 0; i < checkProbe.length; i++) {

        //     let probeItem = $(checkProbe.get(i)).parents('.probe-item')
        //     let inputBlock = $(probeItem).find(".header-inputs")
        //     let inputs = inputBlock.find("input")

        //     $(inputs).each(function() {
        //         className = $(this).attr("class")
        //         if (className.includes(input)) {
        //             $(this).val(changeInputVal)
        //         }
        //     })
        // }
    })

    $body.on('change', '.clear_confirm_change', function () {
        $('#clear_confirm').val('1')
    })

    // скопировать пробу и методики
    $body.on('click', '.copy-probe', function () {
        const $materialItem       = $(this).parents('.material-item')
        const $parentProbeItem    = $(this).parents('.probe-item')
        const $probeItem          = $materialItem.find('.probe-item')
        const $methodBlock        = $parentProbeItem.find('.method-block')
        const materialId          = $materialItem.data('material_id')

        const numberMaterial = $materialItem.data('number-material')

        let num = $probeItem.map(function() {
            return $(this).data('probe_number')
        }).get();

        let countProbe = Math.max.apply(Math, num) + 1;

        let htmlMethods = ``
        for (let i = 0; i < $methodBlock.length; i++) {
            const $curBlock = $($methodBlock.get(i))

            const methodId = $curBlock.find('.method-select').val()
            const tuId = $curBlock.find('.tu-select').val()
            const userId = $curBlock.find('.user-select').val()
            const price = $curBlock.find('.price-input').val()

            htmlMethods += getHtmlMethod(materialId, `new_${countProbe}`, i, methodId, tuId, userId, price)
        }

        $materialItem.find('.probe-item:last-child').after(getHtmlProbe(materialId, numberMaterial, countProbe, htmlMethods))

        $materialItem.find('.probe-item:last-child').find('.select2').select2({
            theme: 'bootstrap-5',
            templateResult: formatState,
            templateSelection: formatState,
        })

        updateTotalPrice()
    })

    // удалить добавленый скриптом материал
    $body.on('click', '.delete-new-material', function () {
        if ( confirm("Удалить объект испытаний и все пробы?") ) {
            $(this).parents('.material-item').remove()

            updateTotalPrice()
        }
    })

    // удалить добавленную скриптом пробу
    $body.on('click', '.delete-new-probe', function () {
        if ( confirm("Удалить пробу?") ) {
            const $probeBlock = $(this).parents('.probe-block')
            const $probeItem = $(this).parents('.probe-item')
            let countProbe = $probeBlock.find('.probe-item').length

            if ( countProbe == 1 ) {
                alert("Невозможно удалить единственную пробу объекта испытаний.")
                return false
            }

            $probeItem.remove()

            updateTotalPrice()
        }
    })

    // удалить добавленый скриптом метод
    $body.on('click', '.del-new-method', function () {
        if ( confirm("Удалить испытание?") ) {
            const methodBlock = $(this).parents('.method-block')
            const methodContainer = methodBlock.parents('.method-container')
            const probeItem = methodBlock.parents('.probe-item')
            const probeId = probeItem.data('probe_id')
            const materialId = $(this).parents('.material-item').data('material_id')

            methodBlock.remove()

            if (methodContainer.find('.method-block').length == 0) {
                methodContainer.append(getHtmlMethod(materialId, probeId))

                methodContainer.find('.select2').select2({
                    theme: 'bootstrap-5',
                    templateResult: formatState,
                    templateSelection: formatState,
                })
            }

            updateTotalPrice()
        }
    })

    // удалить из базы испытание у пробы
    $body.on('click', '.del-permanent-material-gost', function () {
        if ( confirm("Удалить испытание (необратимо)?") ) {
            const methodBlock = $(this).parents('.method-block')
            const methodContainer = methodBlock.parents('.method-container')
            const probeItem = methodBlock.parents('.probe-item')
            const probeId = probeItem.data('probe_id')
            const materialId = $(this).parents('.material-item').data('material_id')

            const ugtpId = $(this).data('gtp_id')
            const tzId = $('#tz_id').val()
            const dealId = $('#deal_id').val()

            const $methodSelect = $('.method-select')

            if ($.type(ugtpId) === 'number' /*&& ugtpId.indexOf("new_") === -1*/) {

                $.ajax({
                    method: 'POST',
                    url: '/ulab/sample/deleteProbeMethodAjax',
                    data: {
                        id: ugtpId,
                        tz_id: tzId
                    },
                    dataType: 'json',
                    success: function (data) {
                        if ( data.success ) {

                            $.ajax({
                                method: 'POST',
                                url: '/ulab/sample/getGostRequestAjax',
                                data: {
                                    deal_id: dealId
                                },
                                dataType: 'json',
                                success: function (data) {
                                    let htmlOption = `<option value=""></option>`
                                    let materialName = ""

                                    $.each(data, function (i, item) {
                                        if ( item.material_name != '' && item.material_name != materialName ) {
                                            materialName = item.material_name
                                            htmlOption += `<option disabled data-bg-color="#cae6f3a3">${item.material_name}</option>`
                                        }

                                        if ( item.count_result <= 0 ) { return }

                                        let color = item.date_color? item.date_color : ''

                                        htmlOption += `<option data-color="${color}" data-count="${item.count_result}" value="${item.ID}" >${item.view_gost}</option>`
                                    })

                                    $methodSelect.empty()
                                    $methodSelect.append(htmlOption)
                                }
                            })

                            methodBlock.remove()

                            if (methodContainer.find('.method-block').length == 0) {
                                // methodContainer.append(getHtmlMethod(materialId, probeId))
                                methodContainer.append(`<div class="row justify-content-between method-block mb-2" data-gost_number="">
                                                <div class="empty-methods">Нет методик</div>
                                            </div>`)

                                methodContainer.find('.select2').select2({
                                    theme: 'bootstrap-5',
                                    templateResult: formatState,
                                    templateSelection: formatState,
                                })
                            }

                            updateTotalPrice()
                        } else {
                            alert(data.error)
                        }
                    }
                })
            } else {
                let methodId = $(this).parents('.input-group').find(".idMethod").val()

                let count = $(`.method-select option[value="${methodId}"]`).data('count')
                    count++
                $(`.method-select option[value="${methodId}"]`).data('count', count)
                $(`span[id$="-${methodId}"] > div > div:last-child`).text(`(${count})`)

                $(`.method-select option[value="${methodId}"]`).data('count', count)
                $(`span[id$="-${methodId}"] > div > div:last-child`).text(`(${count})`)

                methodBlock.remove()

                if (methodContainer.find('.method-block').length == 0) {
                    // methodContainer.append(getHtmlMethod(materialId, probeId))
                    methodContainer.append(`<div class="row justify-content-between method-block mb-2" data-gost_number="">
                                                <div class="empty-methods">Нет методик</div>
                                            </div>`)

                    methodContainer.find('.select2').select2({
                        theme: 'bootstrap-5',
                        templateResult: formatState,
                        templateSelection: formatState,
                    })
                }
            }
        }
    })


    // удалить из базы пробу и методики
    $body.on('click', '.del-permanent-some-probe', function () {
        if ( confirm("Удалить пробу с испытаниями (необратимо)?") ) {

            const delaId = $('#deal_id').val()
            let $checkProbe = $body.find('.probe-check:checked')
            // let countProbe = $probeBlock.find('.probe-item').length
            //
            // if ( countProbe == 1 ) {
            //     alert("Невозможно удалить единственную пробу объекта испытаний.")
            //     return false
            // }
            for (let i = 0; i < $checkProbe.length; i++) {
                let $probeItem = $($checkProbe.get(i)).parents('.probe-item')
                const probeId = $probeItem.data('probe_id')
                $.ajax({
                    method: 'POST',
                    url: '/ulab/sample/deleteProbeAjax',
                    data: {
                        id: probeId,
                        deal_id: delaId,
                    },
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            $probeItem.remove()

                            updateTotalPrice()
                        } else {
                            alert(data.error)
                        }
                    }
                })
            }
        }
    })

    // удалить из базы пробу и методики
    $body.on('click', '.del-permanent-probe', function () {
        if ( confirm("Удалить пробу с испытаниями (необратимо)?") ) {
            const $probeBlock = $(this).parents('.probe-block')
            const $probeItem = $(this).parents('.probe-item')
            const $materialItem = $(this).parents('.material-item')
            const probeId = $probeItem.data('probe_id')
            const delaId = $('#deal_id').val()
            let countProbe = $probeBlock.find('.probe-item').length

            if ( countProbe == 1 ) {
                alert("Невозможно удалить единственную пробу объекта испытаний.")
                return false
            }

            $.ajax({
                method: 'POST',
                url: '/ulab/requirement/deleteProbeAjax',
                data: {
                    id: probeId,
                    deal_id: delaId,
                },
                dataType: 'json',
                success: function (data) {
                    if ( data.success ) {
                        $probeItem.remove()

                        updateMaterialPrice($materialItem)
                    } else {
                        alert(data.error)
                    }
                }
            })
        }
    })

    // удалить материал
    $body.on('click', '.delete-material', function () {
        if ( confirm("Удалить объект испытаний и все пробы (необратимо)?") ) {

            const $materialItem = $(this).parents('.material-item')
            const materialId = $materialItem.data('material_id')
            const delaId = $('#deal_id').val()

            $.ajax({
                method: 'POST',
                url: '/ulab/sample/deleteMaterial',
                data: {
                    id: materialId,
                    deal_id: delaId,
                },
                dataType: 'json',
                success: function (data) {
                    if ( data.success ) {
                        $materialItem.remove()

                        updateTotalPrice()
                    } else {
                        alert(data.error)
                    }
                }
            })
        }
    })


    $body.on('change', '.change-material', function () {
        const $parentBlock = $(this).parents('.material-item')
        const textMaterial = $(this).find('option:selected').text()

        $parentBlock.find('.msg-change-material').text(' (после сохранения объект испытаний сменится на: ' + textMaterial + ')')
    })


    $body.on('change', '.method-select', function () {
        const id = $(this).val()
        const $parent = $(this).parents('.method-block')

        $.ajax({
            method: 'POST',
            async: false,
            data: {
                id: id
            },
            url: '/ulab/sample/getMethodDataAjax',
            dataType: 'json',
            success: function (data) {
                $parent.find('.price-input').val(parseFloat(data.PRICE)).trigger('input')

                let options = `<option value="">Исполнитель</option>`
                // $.each(data.assigned, function (i, item) {
                //     options += `<option value="${item.user_id}">${item.short_name}</option>`
                // })
                $.each(data.assigned_data, function (i, item) {
                    options += `<option value="${item.id}">${item.data_name}</option>`
                })

                $parent.find('.user-select').html(options)
            }
        })

        $parent.find('.method-link')
            .removeClass('disabled')
            .attr('href', `/obl_acc.php?ID=${id}`)

    })

    $body.on('change', '.tu-select', function () {
        const id = $(this).val()
        $(this).parents('.method-block').find('.tu-link')
            .removeClass('disabled')
            .attr('href', `/ulab/techCondition/edit/${id}`)
    })

    $body.on('input', '.probe-item .price-input', function () {
        updateTotalPrice()
    })

    // применить скидку
    $body.on('click', '.discount-apply', function () {
        updateTotalPrice()
    })

    // отправить тз на проверку
    $body.on('click', '.sent_tz', function () {
        const delaId = $('#deal_id').val()
        const $block = $('.head-user-block')
        const $btn = $(this)

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $btn.addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                deal_id: delaId
            },
            url: '/ulab/sample/confirmTzSentAjax',
            dataType: 'json',
        }).always( function (data) {
            $block.find('i').removeClass().addClass('fa-solid fa-hourglass-half').attr('title', 'Ожидание проверки')
            $btn.html(`<i class="fa-regular fa-paper-plane"></i> Передано`)
        })
    })

    //удалить акт приемки
    $body.on('click', '.delete-act', function () {
        let id_act = $(this).data('id-act'),
            tr = $(this).parents('.act-tr'),
            probeInAct = $(`[data-act_id="${id_act}"]`).find('.probe-name'),
            dataActId = $(`[data-act_id="${id_act}"]`)
        if (confirm(`Уверены, что хотите удалить акт? Данное действие необратимо`)) {
            $.ajax({
                method: 'POST',
                data: {
                    id_act: id_act
                },
                url: '/ulab/probe/deleteActNewAjax',
                dataType: 'json',
                success: function (data) {
                    tr.remove()
                    probeInAct.text('Не присвоен шифр')
                    dataActId.attr('data-act_id', '')
                    showSuccessMessage('Акт успешно удален')
                }
            })
        }
    })


    // одобрить тз
    $body.on('click', '.approve_tz', function () {
        const tzId = $('#tz_id').val()
        const $block = $('.curr_user')
        const $btn = $(this)

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $btn.addClass('disable')
        $('.not_approve_tz').addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                tz_id: tzId
            },
            url: '/ulab/sample/confirmTzApproveAjax',
            dataType: 'json',
        }).always( function (data) {
            $block.find('.icon').addClass('text-green').find('i').removeClass().addClass('fa-regular fa-circle-check').attr('title', 'ТЗ потверждено')
            $btn.find('i').removeClass().addClass('fa-regular fa-circle-check')
        })
    })

    // отправить и одобрить тз
    $body.on('click', '.sent_approve_tz', function () {
        const tzId = $('#tz_id').val()
        const delaId = $('#deal_id').val()
        const $headBlock = $('.head-user-block')
        const $block = $('.curr_user')
        const $btn = $(this)

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $btn.addClass('disable')
        $('.not_approve_tz').addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                tz_id: tzId,
                deal_id: delaId,
            },
            url: '/ulab/sample/confirmTzSentApproveAjax',
            dataType: 'json',
        }).always( function (data) {
            $headBlock.find('i').removeClass().addClass('fa-solid fa-hourglass-half').attr('title', 'Ожидание проверки')
            $block.find('.icon').addClass('text-green').find('i').removeClass().addClass('fa-regular fa-circle-check').attr('title', 'ТЗ потверждено')
            $btn.find('i').removeClass().addClass('fa-regular fa-circle-check')
        })
    })

    // не одобрить тз
    $('body').on('click', '.not_approve_tz_btn', function () {
        const tzId = $('#tz_id').val()
        const $block = $('.curr_user')
        const $btn = $(this)
        const desc = $('#desc_return').val()

        $btn.find('i').removeClass().addClass('fa-solid fa-arrows-rotate spinner-animation')
        $('.not_approve_tz').addClass('disable')
        $('.approve_tz').addClass('disable')

        $.ajax({
            method: 'POST',
            data: {
                tz_id: tzId,
                desc: desc
            },
            url: '/ulab/sample/confirmTzNotApproveAjax',
            dataType: 'json',
        }).always( function (data) {
            $block.find('.icon').addClass('text-red').find('i').removeClass().addClass('fa-regular fa-circle-xmark')
            $btn.find('i').removeClass().addClass('fa-regular fa-circle-xmark')
            $.magnificPopup.close()
            location.reload()
        })
    })



    // обновляет цену
    function updateTotalPrice() {
        const $priceInput = $body.find('.probe-item .price-input')
        let discountVal = parseFloat($('.discount-input').val())
        let discountType = $('.discount-type').val()

        let totalPrice = 0
        let discountPrice = 0

        for (let i = 0; i < $priceInput.length; i++) {
            let val = parseFloat($($priceInput.get(i)).val())
            if ( val > 0 ) {
                totalPrice += val
            }
        }

        if ( discountType == 'percent' ) {
            if ( discountVal < 0 ) {
                discountVal = 0
            } else if ( discountVal > 100 ) {
                discountVal = 100
            }

            discountPrice = totalPrice - totalPrice * discountVal / 100
        } else if ( discountType == 'rub' && discountVal >= 0 ) {
            discountPrice = totalPrice - discountVal
        }

        if (discountPrice < 0) {
            discountPrice = 0
        }

        $('#price-total').val(totalPrice.toFixed(2))
        $('.total').text(discountPrice.toFixed(2) + ' руб.')

        $('#clear_confirm').val('1')
    }
})

function getHtmlMaterial(materialId, countMaterial, materialName, htmlProbe) {
    return `<div class="accordion-item material-item" data-number-material="${countMaterial}" data-material_id="${materialId}">
                <h2 class="accordion-header" id="flush-heading${countMaterial}">
                    <div class="accordion-button ps-0 collapsed" data-bs-toggle="collapse" data-bs-target="#flush-collapse${countMaterial}" aria-expanded="false" aria-controls="flush-collapse${countMaterial}">
                        <input class="form-check-input ms-3 me-3 material-check" type="checkbox" data-bs-toggle="collapse" data-bs-target="#qq">
                        ${materialName}
                    </div>
                </h2>
                <div id="flush-collapse${countMaterial}" class="accordion-collapse collapse" aria-labelledby="flush-heading${countMaterial}" >
                    <div class="accordion-body">
                    
                        <div class="row justify-content-end mb-3">
                            <div class="col-auto">
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Кол-во проб</span>
                                    <input type="number" class="form-control" min="1" id="new-count-probe" value="1">
                                    <button type="button" class="btn btn-success btn-square add-probe" title="Добавить пробу">
                                        <i class="fa-solid fa-plus icon-fix"></i>
                                    </button>
                                </div>
                            </div>
    
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary btn-square expand-all" title="Свернуть все пробы">
                                    <i class="fa-solid fa-angles-up"></i>
                                </button>
                            </div>
    
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary btn-square collapse-all" title="Развернуть все пробы">
                                    <i class="fa-solid fa-angles-down"></i>
                                </button>
                            </div>
    
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger btn-square delete-new-material" title="Удалить объект испытаний и все пробы">
                                    <i class="fa-solid fa-minus icon-fix"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="line-dashed"></div>
    
                        <div class="accordion probe-block" id="accordionPanelsStayOpen${countMaterial}">
                            ${htmlProbe}
                        </div>
                    </div>
                </div>
            </div>`
}

function getHtmlProbe(materialId, countMaterial, countProbe, htmlMethod) {
    return `<div class="accordion-item probe-item" data-probe_number="${countProbe}" data-probe_id="new_${countProbe}">
                <h2 class="accordion-header" id="panelsStayOpen-heading${countMaterial}-${countProbe}">
                    <div class="accordion-button ps-0 collapsed bg-pele-green" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse${countMaterial}-${countProbe}" aria-expanded="false" aria-controls="panelsStayOpen-collapse${countMaterial}-${countProbe}">
                        <input class="form-check-input ms-3 me-3 probe-check" type="checkbox" data-bs-toggle="collapse" data-bs-target="#qq">
                        Не присвоен шифр #${countProbe+1}
                    </div>
                </h2>
                <div class="header-inputs">
                    <div class="row">
                        <div class="col-3">
                            <label class="form-label mb-1">Шифр заказчика</label>
                        </div>
                        <div class="col-3">
                            <label class="form-label mb-1">Место отбора</label>
                        </div>
                        <div class="col-3">
                            <label class="form-label mb-1">Дата отбора</label>
                        </div>
                        <div class="col">
                            <label class="form-label mb-1">Дата доставки проб</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="input-group">
                                <input class="form-control cipher" type="text" name="material[${materialId}][probe][new_${countProbe}][cipher_customer]">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <input class="form-control place" type="text" name="material[${materialId}][probe][new_${countProbe}][probe_place]">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <input class="form-control date-sample" type="date" name="material[${materialId}][probe][new_${countProbe}][date_sample]">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <input class="form-control date-delivery" type="datetime-local" name="material[${materialId}][probe][new_${countProbe}][date_delivery]">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="panelsStayOpen-collapse${countMaterial}-${countProbe}" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading${countMaterial}-${countProbe}">
                    <div class="accordion-body method-block-block">
                    
                        <div class="row justify-content-end mb-3">
<!--                            <div class="col-auto">-->
<!--                                <button type="button" class="btn btn-success btn-square add-method-to-probe" title="Добавить испытание">-->
<!--                                    <i class="fa-solid fa-plus icon-fix"></i>-->
<!--                                </button>-->
<!--                            </div>-->
<!--                        -->
                            <div class="col-auto">
                                <button type="button" class="btn btn-success btn-square copy-probe" title="Скопировать пробу и все методикы">
                                    <i class="fa-regular fa-copy icon-fix"></i>
                                </button>
                            </div>
                        
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger btn-square delete-new-probe" title="Удалить пробу и методики">
                                    <i class="fa-solid fa-minus icon-fix"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="line-dashed"></div>
                        
                        <div class="row mb-3">

                            <input type="hidden" name="material[${materialId}][probe][new_${countProbe}][probe_number]" value="${countProbe}" class="probe-number-input">
                            <input type="hidden" name="material[${materialId}][probe][new_${countProbe}][material_number]" value="${countMaterial}" class="material-number-input">

                        </div>
                        
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label mb-1">Методика испытаний</label>
                            </div>
                        </div>
                        
                        <div class="method-container">
                            <div class="row justify-content-between method-block mb-2">
                                <div class="empty-methods">Нет методик</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`
}


function getHtmlMethod(materialId = 'new', probeId = 'new', methodId = 'new', gostNumber = 0, defaultMethod = 0, defaultTu = 0, defaultUser = 0, defaultPrice = 0) {
    let optionMethod = ``
    let optionCondition = ``
    let optionAssigned = ``
    let htmlInput = ``

    let disabledMethod = 'disabled'
    let disabledTu = 'disabled'

    // $.each(methodList, function (i, item) {
    //     let dataColor = ''
    //     let selected = ``
    //     // if ( item.is_confirm == 0 ) {
    //     //     dataColor = `data-color="#dfdf11"`
    //     // }
    //
    //     // if ( item.is_actual == 0 ) {
    //     //     dataColor = `data-color="#F00"`
    //     // }
    //
    //     if (item.ID == defaultMethod) {
    //
    //         disabledMethod = ''
    //         selected = 'selected'
    //
            $.ajax({
                method: 'POST',
                async: false,
                data: {
                    id: defaultMethod
                },
                url: '/ulab/sample/getMethodDataAjax',
                dataType: 'json',
                success: function (data) {
                    // $.each(data.assigned, function (i, item) {
                    //     let selectedUser = ''

                    //     if ( item.user_id == defaultUser ) {
                    //         selectedUser = 'selected'
                    //     }

                    //     optionAssigned += `<option value="${item.user_id}" ${selectedUser}>${item.short_name}</option>`
                    // })
                    htmlInput = `<div class="row justify-content-between method-block mb-2" data-gost_number="${gostNumber}">
                                    <div class="col-6">
                                        <div class="input-group mb-1">
                                            <input type="text" class="form-control" value="${data.GOST + '-' + data.GOST_YEAR + ' | ' + data.SPECIFICATION}" readonly>
                                            <input type="hidden" name="material[${materialId}][probe][${probeId}][method][${methodId}][new_method_id]" class ="idMethod" value="${defaultMethod}">       
                                            <a class="btn btn-outline-secondary method-link ${defaultMethod}" target="_blank" title="Перейти в методику" href="/obl_acc.php?ID=${defaultMethod}">
                                                <i class="fa-solid fa-right-to-bracket"></i>
                                            </a>
                                            <div class="col-auto">
                                                <button
                                                        class="btn btn-danger mt-0 del-permanent-material-gost btn-square float-end clear_confirm_change"
                                                        data-gtp_id="${methodId}"
                                                        type="button"
                                                >
                                                    <i class="fa-solid fa-minus icon-fix"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>    
                                </div>`
                    // $.each(data.assigned_data, function (i, item) {
                    //     let selectedUser = ''
                    //
                    //     if (item.id == defaultUser) {
                    //         selectedUser = 'selected'
                    //     }
                    //
                    //     optionAssigned += `<option value="${item.id}" ${selectedUser}>${item.data_name}</option>`
                    // })
                }
            })
    //     }
    //
    //     optionMethod += `<option value="${item.ID}" ${dataColor} ${selected}>${item.GOST + '-' + item.GOST_YEAR + ' | ' + item.SPECIFICATION}</option>`
    // })

    if ( isNaN(Number(methodId)) ) { // new
        delBtnClass = 'del-new-method'
    } else {
        delBtnClass = 'del-permanent-material-gost clear_confirm_change'
    }

    return htmlInput
    // return `<div class="row justify-content-between method-block mb-2" data-gost_number="${gostNumber}">
    //             <input type="hidden" class="gost-number-input" name="material[${materialId}][probe][${probeId}][method][${methodId}][gost_number]" value="${gostNumber}">
    //             <div class="col-4">
    //                 <div class="input-group">
    //                     <select class="form-control select2 method-select" name="material[${materialId}][probe][${probeId}][method][${methodId}][new_method_id]">
    //                         <option value=""></option>
    //                         ${optionMethod}
    //                     </select>
    //                     <a class="btn btn-outline-secondary method-link ${disabledMethod}" target="_blank" title="Перейти в методику" href="/ulab/gost/method/${defaultMethod}">
    //                         <i class="fa-solid fa-right-to-bracket"></i>
    //                     </a>
    //                 </div>
    //             </div>
    //             <div class="col-4">
    //                 <div class="input-group">
    //                     <select class="form-control select2 tu-select" name="material[${materialId}][probe][${probeId}][method][${methodId}][tech_condition_id]">
    //                         <option value="">--</option>
    //                         ${optionCondition}
    //                     </select>
    //                     <a class="btn btn-outline-secondary tu-link ${disabledTu}" target="_blank" title="Перейти в ТУ" href="/ulab/techCondition/edit/${defaultTu}">
    //                         <i class="fa-solid fa-right-to-bracket"></i>
    //                     </a>
    //                 </div>
    //             </div>
    //             <div class="col-2">
    //                 <select class="form-control user-select" name="material[${materialId}][probe][${probeId}][method][${methodId}][assigned_id]">
    //                     <option value="">Исполнитель</option>
    //                     ${optionAssigned}
    //                 </select>
    //             </div>
    //             <div class="col">
    //                 <div class="input-group">
    //                     <input class="form-control price-input" name="material[${materialId}][probe][${probeId}][method][${methodId}][price]" type="number" min="0" step="0.01" value="${defaultPrice}">
    //                     <span class="input-group-text">₽</span>
    //                 </div>
    //             </div>
    //             <div class="col-auto">
    //                 <button
    //                         class="btn btn-danger mt-0 ${delBtnClass} btn-square float-end"
    //                         data-gtp_id="${methodId}"
    //                         type="button"
    //                 >
    //                     <i class="fa-solid fa-minus icon-fix"></i>
    //                 </button>
    //             </div>
    //         </div>`
}



// function getHtmlMethod(materialId = 'new', probeId = 'new', gostNumber = 0, defaultMethod = 0, defaultTu = 0, defaultUser = 0, defaultPrice = 0) {
//     let optionMethod = ``
//     let optionCondition = ``
//     let optionAssigned = ``

//     let disabledMethod = 'disabled'
//     let disabledTu = 'disabled'
//     $.each(methodList, function (i, item) {
//         let dataColor = ''
//         let selected = ``

//         if ( item.is_confirm == 0 ) {
//             dataColor = `data-color="#dfdf11"`
//         }

//         if ( item.is_actual == 0 ) {
//             dataColor = `data-color="#F00"`
//         }
//         if ( item.id == defaultMethod ) {
//             disabledMethod = ''
//             selected = 'selected'

//             $.ajax({
//                 method: 'POST',
//                 async: false,
//                 data: {
//                     id: defaultMethod
//                 },
//                 url: '/ulab/sample/getMethodDataAjax',
//                 dataType: 'json',
//                 success: function (data) {
//                     $.each(data.assigned, function (i, item) {
//                         let selectedUser = ''

//                         if ( item.user_id == defaultUser ) {
//                             selectedUser = 'selected'
//                         }

//                         optionAssigned += `<option value="${item.user_id}" ${selectedUser}>${item.short_name}</option>`
//                     })
//                 }
//             })
//         }

//         optionMethod += `<option value="${item.id}" ${dataColor} ${selected}>${item.view_gost}</option>`
//     })

//     $.each(conditionList, function (i, item) {
//         let selected = ``

//         if ( item.id == defaultTu ) {
//             disabledTu = ''
//             selected = 'selected'
//         }

//         optionCondition += `<option value="${item.id}" ${selected}>${item.view_name}</option>`
//     })

//     return `<div class="row justify-content-between method-block mb-2" data-gost_number="${gostNumber}">
//                 <input type="hidden" class="gost-number-input" name="material[${materialId}][probe][${probeId}][method][new_${gostNumber}][gost_number]" value="${gostNumber}">
//                 <div class="col-4">
//                     <div class="input-group">
//                         <select class="form-control select2 method-select clear_confirm_change" name="material[${materialId}][probe][${probeId}][method][new_${gostNumber}][new_method_id]">
//                             <option value=""></option>
//                             ${optionMethod}
//                         </select>
//                         <a class="btn btn-outline-secondary method-link ${disabledMethod}" target="_blank" title="Перейти в методику" href="/ulab/gost/method/${defaultMethod}">
//                             <i class="fa-solid fa-right-to-bracket"></i>
//                         </a>
//                     </div>
//                 </div>
//                 <div class="col-4">
//                     <div class="input-group">
//                         <select class="form-control select2 tu-select clear_confirm_change" name="material[${materialId}][probe][${probeId}][method][new_${gostNumber}][tech_condition_id]">
//                             <option value="">--</option>
//                             ${optionCondition}
//                         </select>
//                         <a class="btn btn-outline-secondary tu-link ${disabledTu}" target="_blank" title="Перейти в ТУ" href="/ulab/techCondition/edit/${defaultTu}">
//                             <i class="fa-solid fa-right-to-bracket"></i>
//                         </a>
//                     </div>
//                 </div>
//                 <div class="col-2">
//                     <select class="form-control user-select" name="material[${materialId}][probe][${probeId}][method][new_${gostNumber}][assigned_id]">
//                         <option value="">Исполнитель</option>
//                         ${optionAssigned}
//                     </select>
//                 </div>
//                 <div class="col">
//                     <div class="input-group">
//                         <input class="form-control price-input clear_confirm_change" name="material[${materialId}][probe][${probeId}][method][new_${gostNumber}][price]" type="number" min="0" step="0.01" value="${defaultPrice}">
//                         <span class="input-group-text">₽</span>
//                     </div>
//                 </div>
//                 <div class="col-auto">
//                     <button
//                             class="btn btn-danger mt-0 del-new-method btn-square float-end"
//                             type="button"
//                     >
//                         <i class="fa-solid fa-minus icon-fix"></i>
//                     </button>
//                 </div>
//             </div>`
// }
