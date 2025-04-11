$(function ($) {

    let $body = $("body")

    /*recipe journal*/
    let recipeJournal = $('#recipe_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
            },
            url: '/ulab/standarttitr/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'number'
            },
            {
                data: 'name'
            },
            {
                data: 'doc_receive_full'
            },
            {
                data: 'doc_receive_date_dateformat'
            },
            {
                data: 'number_batch'
            },
            {
                data: 'quantity_full'
            },
            {
                data: 'volume'
            },
            {
                data: 'coefficient'
            },
            {
                data: 'doc_standart_titr'
            },
            {
                data: 'manufacturer_name'
            },
            {
                data: 'date_production_dateformat'
            },
            {
                data: 'storage_full',
                render: function (data, type, item) {
                    if (item.is_expired == 0) {
                        return data
                    } else return `<div class="alert alert-danger" title="Срок годности иcтек">` + data + `</div>`
                }
            },
            {
                data: 'global_assigned_name'
            }

        ],
        columnDefs: [{
            className: 'control',
            'orderable': false,
        }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        "rowCallback": function (nRow, data) {
            if (data['is_precursor'] == 1) {
                $('td', nRow).css('background-color', '#dacfcf')
            }
        }
    })

    recipeJournal.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                recipeJournal
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
    })
    $('.popup-second').magnificPopup({
        items: {
            src: '#add-entry-modal-form-second',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
    })
    $('.popup-third').magnificPopup({
        items: {
            src: '#add-entry-modal-form-third',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
    })

    $('.select-standart_titr').select2({
        theme: 'bootstrap-5',
        placeholder: 'Выберете стандарт-титр',
        width: '100%',
    })


    $body.on('change', '.reactive-update', function () {
        let number = $('option:selected', this).data('number')
        let numberReceive = $('option:selected', this).data('numberreceive')
        let idLibraryReactive = $(".all-reactive option:selected").data('idlibraryreactive')
        if (number != 'undefined') {
            $('.number-reactive').html(number + " -");
        }
        if ($('#add-entry-modal-form-second').attr('action') == '/ulab/standarttitr/updateStandartTitrReceive/') {
            $('.number-receive').val(numberReceive);
        } else $('.number-receive').val(numberReceive + 1);
        $('.idlibraryreactive').val(idLibraryReactive);
    })

    $('#selectStandartTitrUpdate').on('change', function () {
        let selectedID = $(".reactive-update option:selected").data('idreceive')
        $('#add-entry-modal-form-first').attr('action', '/ulab/standarttitr/updateStandartTitr/')
        $('#add-entry-modal-form-second').attr('action', '/ulab/standarttitr/updateStandartTitrReceive/')

        $('.btn-add-entry').each(function () {
            $(this).prop('disabled', true)
        })
        $('#standartTitrUpdate').prop('disabled', false)
        $('#reactiveUpdate').prop('disabled', false)

        if (typeof (selectedID) !== "string") {
            $('#receiveUpdate').prop('disabled', false)
        }
    })
    $('#inputIdWhichFilter').on('change', function () {
        $('#selectStandartTitrUpdate').prop('disabled', true)
    })

    $body.on('click', '#standartTitrUpdate', function () {
        let id_standarttitr = $('#selectStandartTitrUpdate').val();
        $('.edit-standarttitr-form-name').html('Редактировать стандарт-титр')
        $('#standart_titr_id').val(id_standarttitr)

        $.ajax({
            url: `/ulab/standarttitr/getStandarttitrUpdate`,
            type: "POST",
            dataType: 'json',
            data: {
                type: "standart_titr",
                which_select_id: id_standarttitr
            },
            success: function (result) {
                $("[name*='standart_titr[name]']").val(result[0].name)
                $("[name*='standart_titr[number]']").val(result[0].number)
                if(result[0].is_precursor == 1){
                    $("[name*='standart_titr[is_precursor]']").prop('checked', true)
                }else $("[name*='standart_titr[is_precursor]']").prop('checked', false)

            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $body.on('click', '#receiveUpdate', function () {
        let idreceive = $('#selectStandartTitrUpdate option:selected').data('idreceive')
        $('.edit-receive-standarttitr-form-name').html('Редактировать проводку')
        $('#receive_id').val(idreceive)
        $.ajax({
            url: `/ulab/standarttitr/getStandarttitrUpdate`,
            type: "POST",
            dataType: 'json',
            data: {
                type: "standart_titr_receive",
                which_select_id: idreceive
            },
            success: function (result) {
                $('#receive_id').val(idreceive)
                $('#selectFormStandartTitrUpdate').val(result[0].standart_titr_id)
                $(".number-reactive").text(result[0].standart_titr_number)
                $("[name*='receive[number]']").val(result[0].number)
                $("[name*='receive[doc_receive_name]']").val(result[0].doc_receive_name)
                $("[name*='receive[doc_receive_date]']").val(result[0].doc_receive_date)
                $("[name*='receive[date_receive]']").val(result[0].date_receive)
                $("[name*='receive[number_batch]']").val(result[0].number_batch)
                $("[name*='receive[quantity]']").val(result[0].quantity)
                $("[name*='receive[volume]']").val(result[0].volume)
                $("[name*='receive[doc_standart_titr]']").val(result[0].doc_standart_titr)
                $("[name*='receive[coefficient]']").val(result[0].coefficient)
                $("[name*='receive[id_standart_titr_manufacturer]").val(result[0].id_standart_titr_manufacturer)
                $("[name*='receive[date_production]").val(result[0].date_production)
                $("[name*='receive[storage_life_in_year]").val(result[0].storage_life_in_year)

            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        recipeJournal.ajax.reload()
    })

    function reportWindowSize() {
        recipeJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

})
