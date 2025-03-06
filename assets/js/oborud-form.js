$(function ($) {
    const $body = $('#workarea-content')

    $('.delete_file').click(function () {
        $(this).parents('.input-group').find('input').val('')
        $(this).parents('.input-group').find('input').attr('placeholder', 'Файл удалится после сохранения')
    })

    $('.select2').select2({
        theme: 'bootstrap-5',
        // templateResult: select2FormatState,
        // templateSelection: select2FormatState,
    })

    $('#is_return_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('#is_new_check').prop('checked', false)
            $('#place-moving-block').hide()
            $('#place-moving-block').find('input').val('Возвращено')
        } else {
            $('#place-moving-block').find('input').val('')
            $('#place-moving-block').show()
        }
    })

    $('#is_new_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('#is_return_check').prop('checked', false)
            $('#place-moving-block').hide()
            $('#place-moving-block').find('input').val('Куплено')
        } else {
            $('#place-moving-block').find('input').val('')
            $('#place-moving-block').show()
        }
    })

    $('#select-ident').change(function () {
        if ( $(this).val() === 'IO' ) {
            $('#certified-block-values').show()
        } else {
            $('#certified-block-values').find('input').val('')
            $('#certified-block-values').hide()
        }
    })

    $('.add-inter-oborud').click(function () {
        const $block = $(this).closest('.head-inter-oborud')
        const id = $block.find('select').val()
        const name = $block.find('select option:selected').text()
        const count = $body.find(`#inter-oborud${id}`).length

        if ( id !== '' && count === 0) {
            $block.after(`
                <div id="inter-oborud${id}" class="form-group row block-inter-oborud">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <input type="text" class="form-control" value="${name}">
                            <a class="btn btn-outline-secondary" target="_blank" title="Перейти в оборудование" href="/ulab/oborud/edit/${id}">
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </a>
                        </div>
                        <input type="hidden" name="inter[]" class="form-control" value="${id}">
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-danger btn-square delete-inter-oborud" title="Отвязать оборудование">
                            <i class="fa-solid fa-minus icon-fix"></i>
                        </button>
                    </div>
                </div>
            `)
        }
    })

    $body.on('click', '.delete-inter-oborud', function () {
        $(this).closest('.block-inter-oborud').remove()
    })

    $body.on('click', '.delete-precision', function () {
        $(this).parents('.precision_table--block').remove()
    })

    $body.on('click', '.add-precision', function () {

        let num = $('.precision_table--block').map(function() {
            return $(this).data('number-row');
        }).get();

        let countRow = Math.max.apply(Math, num) + 1;

        $('.precision_table--container').append(
            `<tr class="precision_table--block" data-number-row="${countRow}">
                <td>
                    <input type="text" class="form-control" name="precision_table[${countRow}][name]">
                </td>
                <td>
                    <input type="text" class="form-control" name="precision_table[${countRow}][unit1]">
                </td>
                <td class="precision_table--range-container">
                    <div class="input-group precision_table--range-start-block">
                        <span class="input-group-text">от</span>
                        <input type="text" class="form-control" name="precision_table[${countRow}][ot][]">
                        <span class="input-group-text">до</span>
                        <input type="text" class="form-control" name="precision_table[${countRow}][do][]">
                        <button type="button" class="btn btn-success btn-square add-range" title="Добавить">
                            <i class="fa-solid fa-plus icon-fix"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <div class="input-group precision_table--pg-block">
                        <input type="text" class="form-control" name="precision_table[${countRow}][pg][]">
                    </div>
                </td>
                <td>
                    <div class="input-group precision_table--unit2-block">
                        <input type="text" class="form-control" name="precision_table[${countRow}][unit2][]">
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-square delete-precision" title="Удалить">
                        <i class="fa-solid fa-minus icon-fix"></i>
                    </button>
                </td>
            </tr> `
        )
    })

    $body.on('click', '.delete-range', function () {
        $(this).parents('.precision_table--range-block').remove()
    })

    $body.on('click', '.add-range', function () {
        let $parent = $(this).parents('.precision_table--block')
        let i = $parent.data('number-row')

        $parent.find('.precision_table--range-container').append(
            `<div class="input-group precision_table--range-block pt-2 added_row_${i}">
                <span class="input-group-text">от</span>
                <input type="text" class="form-control" name="precision_table[${i}][ot][]">
                <span class="input-group-text">до</span>
                <input type="text" class="form-control" name="precision_table[${i}][do][]">
                <button type="button" class="btn btn-danger btn-square delete-range" data-number="${i}" title="Удалить">
                    <i class="fa-solid fa-minus icon-fix"></i>
                </button>
            </div>`
        )

        $parent.find('.precision_table--pg-block:last-child').after(
            `<div class="input-group precision_table--pg-block pt-2 added_row_${i}">
                <input type="text" class="form-control" name="precision_table[${i}][pg][]" value="">
            </div>`
        )

        $parent.find('.precision_table--unit2-block:last-child').after(
            `<div class="input-group precision_table--unit2-block pt-2 added_row_${i}">
                <input type="text" class="form-control" name="precision_table[${i}][unit2][]" value="">
            </div>`
        )
    })

    $('#add-certificate-modal-form').on('submit', function(event) {
        event.preventDefault()
        let formData = new FormData(this)

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $.magnificPopup.close()

                    let $lastDashedLine = $('#certificate-block .line-dashed').last(),
                        html = addNewCertificateFields(response.data)

                    $lastDashedLine.after(html)
                }
            },
            error: function(xhr, status, error) {
                alert('Произошла ошибка: ' + error)
            }
        })
    })

    function addNewCertificateFields(data) {
        let html = `
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Актуальный документ
                </label>
                <div class="col-sm-8 pt-2">
                    <input type="checkbox" name="certificate[${data.id}][is_actual]"
                           class="form-check-input" value="1"
                           ${data.is_actual ? 'checked' : ''}
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Дата документа
                </label>
                <div class="col-sm-8">
                    <input type="date" name="certificate[${data.id}][date_start]"
                           class="form-control" value="${data.date_start ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Срок действия
                </label>
                <div class="col-sm-8">
                    <input type="date" name="certificate[${data.id}][date_end]"
                           class="form-control" value="${data.date_end ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Номер документа
                </label>
                <div class="col-sm-8">
                    <input type="text" name="certificate[${data.id}][name]"
                           class="form-control" value="${data.name ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Файл
                </label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="file" name="certificate[${data.id}]" class="form-control" value="">
                        <input type="text" name="certificate[${data.id}][file]"
                               class="form-control" placeholder="Нет сохраненного файла"
                               value="${data.file ?? ''}" readonly
                        >
                        <a class="btn btn-outline-secondary btn-square-2 btn-icon"
                           title="Скачать/Открыть"
                           href="/file_oborud/${data.oborud_id}/${data.file}"
                           download="/file_oborud/${data.oborud_id}/${data.file}"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                        </a>
                        <a class="btn btn-outline-danger btn-square btn-icon delete_file"
                           style="border-color: #ced4da;"title="Удалить">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Ссылка на ФГИС Аршин
                </label>
                <div class="col-sm-8">
                    <input type="text" name="certificate[${data.id}][link_fgis]"
                           maxlength="255" class="form-control" value="${data.link_fgis ?? ''}"
                    >
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">
                    Аттестованные значения
                </label>
                <div class="col-sm-8">
                    <input type="text" name="certificate[${data.id}][certified_values]"
                           maxlength="255" class="form-control" value="${data.certified_values ?? ''}">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <div class="line-dashed"></div>`

        return html
    }
})