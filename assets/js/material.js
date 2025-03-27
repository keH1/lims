let methodList = null
let conditionList = null
let normDocList = null

$.ajax({
    method: 'POST',
    url: '/ulab/requirement/getMethodsAjax',
    dataType: 'json',
    success: function (data) {
        methodList = data
    }
})

$.ajax({
    method: 'POST',
    url: '/ulab/requirement/getTechCondListAjax',
    dataType: 'json',
    success: function (data) {
        conditionList = data
    }
})

$.ajax({
    method: 'POST',
    url: '/ulab/normDocGost/getNormDocListAjax',
    dataType: 'json',
    success: function (data) {
        normDocList = data
    }
})


$('.select2').select2({
    theme: 'bootstrap-5'
})

$(function ($) {
    let body = $('body')

    $('#add-group').click(function () {
        let div = `<div class="input-group mb-3 group_mat">
                        <span class="input-group-text">Группа материала</span>
                        <input type="text" class="form-control" name="GROUP_VAL[]" value="">
                        <button class="btn btn-outline-secondary del-group-mat" type="button">X</button>
                    </div>`

        if ($('.group_mat').length !== 0) {
            $('.group_mat:last').after(div)
        } else {
            $('.name-group').after(div)
        }

    })

    body.on('click', '.del-group-mat', function () {
        $(this).parent('.group_mat').remove()
    })

    $('#select-gost').select2({
        theme: 'bootstrap-5'
    })

    $('#select-gost').on('change', function () {
        let gostId = $('#select-gost option:selected').data('id')
        let gost = $('#select-gost option:selected').data('gost')
        let spec = $('#select-gost option:selected').data('spec')

        $('.method-not-found').hide()

        let tr = `<tr>
                    <td><a href="/obl_acc.php?ID=${gostId}" >${gost}</a><input type="hidden" value="${gostId}" name="arrGost[]"></td>
                    <td>${spec}</td>
                    <td><button type="button" class="btn btn-outline-danger del-gost btn-square-new"><i class="fa-solid fa-xmark"></i></button></td>
                </tr>`

        if ($('#table-gost tbody tr').length !== 0) {
            $('#table-gost tbody tr:first').before(tr)
        } else {
            $('#table-gost tbody').append(tr)
        }
    })

    body.on('click', '.del-gost', function () {
        $(this).closest('tr').remove()

        let tbody = document.querySelector('#table-gost tbody'),
            rows = tbody.getElementsByTagName('tr'),
            hasVisibleRows = false
    
        for (let i = 0; i < rows.length; i++) {
            if (rows[i].style.display !== "none" && !rows[i].classList.contains('method-not-found')) {
                hasVisibleRows = true
                break
            }
        }
    
        let notFoundRow = document.querySelector('#table-gost .method-not-found')
        if (notFoundRow) {
            notFoundRow.style.display = hasVisibleRows ? "none" : ""
        }
    })

    body.on('click', '#a', function () {
        let name = $('#material-name').text().trim()
        let h2 = $('#material-name')
        let inputName = $('#NAME')

        h2.toggleClass('visually-hidden')
        inputName.removeClass('visually-hidden')
        inputName.removeClass('disabled')
    })

    $('#NAME').on('change', function () {
        let materialId = $('input[name=id]').val()
        let name = $(this).val()
        let inputName = $(this)
        let h2 = $('#material-name')

        $.ajax({
            method: 'POST',
            url: '/ulab/material/updateMaterialAjax',
            data: {
                id: materialId,
                name: name
            },
            dataType: 'html',
            success: function (data) {
                console.log(data)
                inputName.addClass('visually-hidden')
                inputName.addClass('disabled')
                h2.html(`${data} <a class="ms-5" id="a" style="color: black"><i class="fa-solid fa-pencil"></i>`)
                h2.removeClass('visually-hidden')
            }
        })
    })

    $('.IS_DEFAULT').on('change', function () {
        let gostId = $(this).parents('.trGost').find('.gostId').val(),
            is_default = 0
        if($(this).is(':checked')) {
            is_default = 1
        }


        $.ajax({
            method: 'POST',
            url: '/ulab/gost/isDefaultGostAjax',
            data: {
                id: gostId,
                is_default: is_default
            },
            dataType: 'html',
            success: function (data) {
            }
        })

    })

    body.on('click', '.сhange-price-gost', function () {
        let gtmId = $(this).parents('.trGost').find('.gost-to-material-id').val(),
            price = $(this).parents('.trGost').find('.gost-to-material-price').val(),
            inputPrice = $(this).parents('.trGost').find('.gost-to-material-price')
        $.ajax({
            method: 'POST',
            url: '/ulab/gost/setGostToMaterialPriceAjax',
            data: {
                id: gtmId,
                price: price
            },
            dataType: 'html',
            success: function (data) {
                if (data == 1) {
                    inputPrice.addClass('is-valid')
                } else {
                    inputPrice.addClass('is-invalid')
                }
            }
        })
    })


    body.on('input', 'input[list]', function() {
        let $input = $(this),
            $options = $('#' + $input.attr('list') + ' option'),
            $hiddenInput = $input.next(),
            label = $input.val().trim()
        for (let i = 0; i < $options.length; i++) {
            let $option = $options.eq(i)
            if ($option.text().trim() === label) {
                $hiddenInput.val( $option.attr('data-value') )
                break
            } else {
                $hiddenInput.val('')
            }
        }
    })


    body.on('click', '.add-new-gost', function () {
        let tr = `<tr class="tr-gost">
                    <td class="align-middle">
                        <input class="form-control method" type="text" list="gost-arr">
                        <input type="hidden" class="hidden-gost" name="gost_id[]">
                    </td>
                    <td></td>
                    <td></td>
                    <td class="align-middle">
                         <button type="button" class="btn btn-outline-danger del-tr btn-square-new">
                        <i class="fa-solid fa-xmark"></i>
                        </button>
                    </td>
                  </tr>`,
            last_tr = $(this).parents('.table-gost').find('tr:last')

        last_tr.before(tr)
    })

    body.on('click', '.del-tr', function () {
        $(this).parents('.tr-gost').remove()
    })

    body.on('click', '.edit-scheme', function () {

        let form = $(this).parents('.scheme-info'),
            name = form.find('[name=scheme-param]').val(),
            id_scheme = form.find('input[name=scheme_id]').val(),
            id_material = form.find('input[name=material_id]').val(),
            scheme_param = [],
            msg = ''

        let block = form.find('.method-block')

        $.each(block, function (i, item) {
            let gost = $(item).find('.method-select').val(),
                tu = $(item).find('.tu-select').val()

            scheme_param.push({method_id: gost, nd_id: tu})
        })

        if (name == '') {
            msg = `<div class="alert alert-danger mb-3" role="alert">
                                Не указано название схемы
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                               </div>`
            form.prepend(msg)
            return false
        }

        console.log(scheme_param)

        $.ajax({
            method: 'POST',
            url: '/ulab/material/setSchemeAjax',
            data: {
                name: name,
                id_scheme: id_scheme,
                gosts: scheme_param,
                id_material: id_material
            },
            dataType: 'text',
            success: function (data) {
                location.reload()
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
        })

    })

    body.on('click', '.delete-scheme', function () {
        let form = $(this).parents('.scheme-info'),
            id_scheme = form.find('input[name=scheme_id]').val()

        if (confirm('Удалить схему?')) {
            $.ajax({
                method: 'POST',
                url: '/ulab/material/deleteSchemeAjax',
                data: {
                    id_scheme: id_scheme
                },
                dataType: 'json',
                success: function (data) {
                    location.reload()
                }
            })
        }

    })

    body.on('click', '.add-new-method', function () {
        let $methodContainer = $(this).parents('.tab-pane').find('.method-container')
        const $MethodBlock = $methodContainer.find('.method-block')
        let countMethod = 0
        console.log($methodContainer)
        if ( $MethodBlock.length > 0 ) {
            let num = $methodContainer.find('.method-block').map(function() {
                return $(this).data('gost_number');
            }).get();

            countMethod = Math.max.apply(Math, num) + 1;
        }

        $methodContainer.append(getHtmlMethod(methodList, normDocList, countMethod))

        $methodContainer.find('.method-block:last-child').find('.select2').select2({
            theme: 'bootstrap-5'
        })
    })

    // удаляем строчку с методикой в модальном окне добавление методик
    body.on('click', '.del-new-method', function () {
        $(this).parents('.method-block').remove()
    })


    body.on('click', '.add-tu', function () {
        let thisTr = $(this).closest('tr')
        let groupId = $(this).data('group_id')
        let count = thisTr.prev().data('count')

        if ( groupId === undefined ) {
            groupId = 'new'
        }
        if ( count === undefined ) {
            count = -1
        }

        count++

        thisTr.before(getHtmlGroupRowTu(groupId, count))

        thisTr.prev().find('.select2').select2({
            theme: 'bootstrap-5'
        })
    })

    body.on('click', '.delete-tu', function () {
        let thisTr = $(this).closest('tr')

        thisTr.remove()
    })

    body.on('click', '.delete-group', function () {
        let groupId = $(this).data('group_id')
        let $block = $(this).closest('.group-block')

        if ( confirm("Подтверждаете удаление группы?") ) {
            $.ajax({
                url: '/ulab/material/deleteGroupAjax/',
                data: {
                    group_id: groupId
                },
                method: 'POST',
                success: function (json) {
                    $block.remove()
                }
            })
        }
    })

    // выбираем методику
    body.on('change', '.method-select', function () {
        const id = $(this).val()
        $(this).closest('.input-group').find('.method-link')
            .removeClass('disabled')
            .attr('href', `/ulab/gost/method/${id}`)
    })

    // выбираем ТУ
    body.on('change', '.tu-select', function () {
        const id = $(this).val()
        $(this).closest('.input-group').find('.tu-link')
            .removeClass('disabled')
            .attr('href', `/ulab/normDocGost/method/${id}`)
    })

    body.on('click', '.edit-name', function () {
        let data = $('#material-name')[0].innerText


        $('.title-block')[0].innerHTML =
            `
            <input id="material-name" class="form-control" value="${data}" type="text">
            `;

        $('.edit-name')[0].innerHTML =
            `
            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="#7CC504" x="128" y="128" role="img" style="display:inline-block;vertical-align:middle" xmlns="http://www.w3.org/2000/svg"><g fill="#7CC504"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12l6 6L20 6"/></g></svg>
            `;

        $(this).removeClass('edit-name')
        $(this).addClass('save-name')
    })

    body.on('click', '.save-name', function () {
        let data = $('#material-name').val()
        let id_material = $('input[name=material_id]').val()

        $.ajax({
            method: 'POST',
            url: '/ulab/material/setNewName',
            data: {
                id_material: id_material,
                name: data
            },
            dataType: 'json',
            success: function (data) {

            }
        })

        $('.save-name')[0].innerHTML =
            `
                        <svg width="24px" height="24px" viewBox="0 0 16 16" fill="#9D4CF7" x="128" y="128" role="img" style="display:inline-block;vertical-align:middle" xmlns="http://www.w3.org/2000/svg"><g fill="#9D4CF7"><g fill="currentColor"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></g></g></svg>
                    `;

        $(this).removeClass('save-name')
        $(this).addClass('edit-name')

        $('.title-block')[0].innerHTML =
            `
                     <h2 class="mb-3" id="material-name" style="margin-bottom: 0 !important;">
                        ${data}
                    </h2>
                    `;
    })

    $('#search-text').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault()
            tableSearch()
        }
    })
})

function tableSearch() {
    let phrase = document.getElementById('search-text'),
        table = document.getElementById('table-gost'),
        regPhrase = new RegExp(phrase.value, 'i'),
        flag = false,
        hasVisibleRows = false

    if (phrase.value.trim() === "") {
        for (let i = 2; i < table.rows.length; i++) {
            if (!table.rows[i].classList.contains('method-not-found')) {
                table.rows[i].style.display = ""
            }
        }

        let notFoundRow = table.querySelector('.method-not-found')
        if (notFoundRow) {
            let hasOtherRows = false
            for (let i = 2; i < table.rows.length; i++) {
                if (!table.rows[i].classList.contains('method-not-found') && table.rows[i].style.display !== "none") {
                    hasOtherRows = true
                    break
                }
            }
            notFoundRow.style.display = hasOtherRows ? "none" : ""
        }
        return
    }

    for (let i = 2; i < table.rows.length; i++) {
        flag = false

        for (let j = table.rows[i].cells.length - 1; j >= 0; j--) {
            flag = regPhrase.test(table.rows[i].cells[j].innerHTML)

            if (flag) {
                break
            }
        }

        if (flag) {
            table.rows[i].style.display = ""
            hasVisibleRows = true
        } else {
            table.rows[i].style.display = "none"
        }
    }

    let notFoundRow = table.querySelector('.method-not-found')
    if (notFoundRow) {
        notFoundRow.style.display = hasVisibleRows ? "none" : ""
    }
}

/**
 *
 * @param methodList
 * @param normDocList
 * @param gostNumber
 * @returns {string}
 */
function getHtmlMethod(methodList, normDocList, gostNumber = 0) {
    console.log(normDocList)
    let optionMethod = getHtmlOptionsMethod(methodList)
    let optionCondition = getHtmlOptionsNormDoc(normDocList)

    return `<div class="row justify-content-between method-block mb-2" data-gost_number="${gostNumber}">
                <div class="col-5">
                    <div class="input-group">
                        <select class="form-control select2 method-select" name="form[${gostNumber}][new_method_id]" required>
                            <option value="">--</option>
                            ${optionMethod}
                        </select>
                        <a class="btn btn-outline-secondary method-link disabled"  title="Перейти в методику" href="">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </a>
                    </div>
                </div>
                <div class="col-5">
                    <div class="input-group">
                        <select class="form-control select2 tu-select" name="form[${gostNumber}][norm_doc_method_id]">
                            ${optionCondition}
                        </select>
                        <a class="btn btn-outline-secondary tu-link disabled"  title="Перейти в ТУ" href="">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </a>
                    </div>
                </div>
                <div class="col-auto">
                    <button
                            class="btn btn-danger mt-0 del-new-method btn-square float-end"
                            type="button"
                    >
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>                    
                </div>
            </div>`
}


function getHtmlGroupRowTu(groupId = 'new', count = 0) {
    let htmlTu = getHtmlOptionsNormDoc(normDocList)
    return `<tr data-count="${count}">
                    <td>
                        <div class="input-group">
                            <select class="form-control select2 tu-select" name="group[${groupId}][tu][${count}][norm_doc_method_id]">
                                ${htmlTu}
                            </select>
                            <a class="btn btn-outline-secondary tu-link disabled"  title="Перейти" href="">
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </a>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <select class="form-select" name="group[${groupId}][tu][${count}][comparison_val_1]">
                                <option value="more">&gt;</option>
                                <option value="more_or_equal" selected>&ge;</option>
                                <option value="less">&lt;</option>
                                <option value="less_or_equal">&le;</option>
                            </select>
                            <input type="number" step="any" class="form-control" name="group[${groupId}][tu][${count}][val_1]" value="">
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <select class="form-select" name="group[${groupId}][tu][${count}][comparison_val_2]">
                                <option value="more">&gt;</option>
                                <option value="more_or_equal">&ge;</option>
                                <option value="less">&lt;</option>
                                <option value="less_or_equal" selected>&le;</option>
                            </select>
                            <input type="number" step="any" class="form-control" name="group[${groupId}][tu][${count}][val_2]" value="">
                        </div>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-danger btn-square delete-tu" title="Удалить тех. условие">
                            <i class="fa-solid fa-minus icon-fix"></i>
                        </button>
                    </td>
                </tr>`
}


/**
 *
 * @param conditionList
 * @param defaultTu
 * @returns {string}
 */
function getHtmlOptionsTu(conditionList, defaultTu = 0) {
    let optionCondition = '<option value="">--/--</option>'

    $.each(conditionList, function (i, item) {
        optionCondition += `<option value="${item.id}">${item.view_name}</option>`
    })

    return optionCondition
}


/**
 *
 * @param normDocList
 * @param defaultId
 * @returns {string}
 */
function getHtmlOptionsNormDoc(normDocList, defaultId = 0) {
    let optionCondition = '<option value="">--/--</option>'

    $.each(normDocList, function (i, item) {
        let selected = item.id == defaultId ? 'selected' : ''
        optionCondition += `<option value="${item.id}" ${selected}>${item.view_gost}</option>`
    })

    return optionCondition
}


/**
 *
 * @param methodList
 * @param defaultMethod
 * @returns {string}
 */
function getHtmlOptionsMethod(methodList, defaultMethod = 0) {
    let optionMethod = ''

    $.each(methodList, function (i, item) {
        optionMethod += `<option value="${item.id}">${item.view_gost}</option>`
    })

    return optionMethod
}



