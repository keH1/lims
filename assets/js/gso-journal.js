$(function ($) {
    /*recipe journal*/
    let recipeJournal = $('#recipe_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
            },
            url: '/ulab/gso/getListProcessingAjax/',
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
                data: 'doc'
            },
            {
                data: 'aggregate_name'
            },
            {
                data: 'name_specification_full'
            },
            {
                data: 'gso_purpose'
            },
            {
                data: 'doc_receive_full'
            },
            {
                data: 'date_receive_dateformat'
            },
            {
                data: 'number_batch'
            },
            {
                data: 'quantity_full'
            },
            {
                data: 'specification'
            },
            {
                data: 'concentration_full'
            },
            {
                data: 'certificate'
            },
            {
                data: 'passport'
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
        dom: 'fBrt<"bottom"lip>',
        buttons: [
            {
                extend: 'colvis',
                titleAttr: 'Выбрать'
            },
            {
                extend: 'copy',
                titleAttr: 'Копировать',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend: 'excel',
                titleAttr: 'excel',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            },
            {
                extend: 'print',
                titleAttr: 'Печать',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                }
            }
        ],
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        "rowCallback": function (nRow, data) {
            if (data['is_precursor'] == 1) {
                $('td', nRow).css('background-color', '#dacfcf')
            }
        }
    })

    recipeJournal.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            recipeJournal
                .column($(this).parent().index())
                .search(this.value)
                .draw()
        })
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#recipe_journal').width()
    $('.btnRightTable, .arrowRight').hover(function () {
            container.animate(
                {
                    scrollLeft: scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function () {
            container.stop()
        })

    $('.btnLeftTable, .arrowLeft').hover(function () {
            container.animate(
                {
                    scrollLeft: -scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function () {
            container.stop()
        })


    let $body = $("body")
    let $containerScroll = $body.find('.dataTables_scroll')
    let $thead = $('.journal thead tr:first-child')

    $(document).scroll(function () {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height(),
            positionTop = $containerScroll.offset().top

        if (positionScroll >= positionTop) {
            $thead.attr('style', 'position:fixed;top:0;z-index:99')
        } else {
            $thead.attr('style', '')
        }

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform', `translateY(${positionScroll - 260}px)`)
            $('.arrowLeft').css('transform', `translateY(${positionScroll - 250}px)`)
        }
    })

    $("body").on('click', '.del-reactive', function () {
        let countReactive = $('.reactives').length
        if (countReactive > 1) $(`.reactives[data-id="${countReactive}"]`).remove()
        else alert("Нельзя удалить единственный реактив")
    })

    /** modal */
    $('.popup-first').magnificPopup({
        items: {
            src: '#add-entry-modal-form-first',
            type: 'inline'
        },
        fixedContentPos: false
    })
    $('.popup-second').magnificPopup({
        items: {
            src: '#add-entry-modal-form-second',
            type: 'inline'
        },
        fixedContentPos: false
    })
    $('.popup-third').magnificPopup({
        items: {
            src: '#add-entry-modal-form-third',
            type: 'inline'
        },
        fixedContentPos: false
    })
    $('.select-specification').select2({
        placeholder: 'Выберите характеристику',
        width: '100%',
    })
    $('.select-gso').select2({
        placeholder: 'Выберете ГСО',
        width: '100%',
    })
    $("body").on('change', '.select-gso', function () {
        let unit = $('option:selected', this).data('unit')
        let number = $('option:selected', this).data('number')
        let numberReceive = $("option:selected", this).data('numberreceive')
        let idLibraryReactive = $("option:selected", this).data('idlibraryreactive')
        $('.quantity-gso').html(unit)
        if (number != 'undefined') {
            $('.number-gso').html(number + " -");
        }
        $('.number-receive').val(numberReceive + 1);
        if ($('#add-entry-modal-form-second').attr('action') == '/ulab/gso/updateReceiveGso/') {
            $('.number-receive').val(numberReceive);
        } else $('.number-receive').val(numberReceive + 1);
        $('.idlibraryreactive').val(idLibraryReactive);

    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        recipeJournal.ajax.reload()
    })

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })


    $('#selectGsoUpdate').on('change', function () {
        let selectedID = $(".gso-update option:selected").data('id_receive')
        $('#add-entry-modal-form-first').attr('action', '/ulab/gso/updateGso/')
        $('#add-entry-modal-form-second').attr('action', '/ulab/gso/updateReceiveGso/')

        $('.btn-add-entry').each(function () {
            $(this).prop('disabled', true)
        })
        $('#gsoUpdate').prop('disabled', false)
        if (typeof (selectedID) !== "string") {
            $('#receiveUpdate').prop('disabled', false)
        }
    })
    $('#inputIdWhichFilter').on('change', function () {
        $('#selectGsoUpdate').prop('disabled', true)
    })

    $('body').on('click', '#gsoUpdate', function () {
        $('.edit-gso-form-name').html('Редактировать ГСО');

        let id_gso = $('#selectGsoUpdate').val();

        $.ajax({
            url: `/ulab/gso/setGsoUpdate`,
            type: "POST",
            dataType: 'json',
            data: {
                type: "gso",
                which_select_id: id_gso,
            },
            success: function (result) {
                $("[name*='gso[id]']").val(result.gso.id)
                $("[name*='gso_specification[id]']").val(result.gso_specification.id)

                $("[name*='gso[name]']").val(result.gso.name)
                $("[name*='gso[number]']").val(result.gso.number)
                $("[name*='gso[doc]']").val(result.gso.doc)
                $("[name*='gso[id_gso_purpose]']").val(result.gso.id_gso_purpose).change()
                $("[name*='gso[id_aggregate_state]']").val(result.gso.id_aggregate_state).change()
                $("[name*='gso[id_unit_of_quantity]']").val(result.gso.id_unit_of_quantity).change()
                $("[name*='gso_specification[approximate_concentration]']").val(result.gso_specification.approximate_concentration)
                $("[name*='gso_specification[name]']").val(result.gso_specification.name).change()
                $("[name*='gso_specification[id_unit_of_concentration]']").val(result.gso_specification.id_unit_of_concentration).change()
                if (result.gso.is_precursor === "1") {
                    $("[name*='gso[is_precursor]']").prop('checked', true)
                } else {
                    $("[name*='gso[is_precursor]']").prop('checked', false)
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $('body').on('click', '#receiveUpdate', function () {
        $('.edit-receive-form-name').html('Редактировать проводку');

        let idReceive = $(".gso-update option:selected").data('idreceive')

        $.ajax({
            url: `/ulab/gso/setGsoUpdate`,
            type: "POST",
            dataType: 'json',
            data: {
                type: "receive",
                which_select_id: idReceive
            },
            success: function (result) {
                $("#id_gso").select2('destroy').val(result.receive.id_gso).select2().change();
                $("[name*='receive[id]']").val(result.receive.id)
                $("[name*='receive_specification[id]']").val(result.receive_specification.id)

                $("[name*='receive[doc_receive_name]']").val(result.receive.doc_receive_name);
                $("[name*='receive[doc_receive_date]']").val(result.receive.doc_receive_date);
                $("[name*='receive[date_receive]']").val(result.receive.date_receive);
                $("[name*='receive[number_batch]']").val(result.receive.number_batch);
                $("[name*='receive[quantity]']").val(result.receive.quantity);
                $("[name*='receive_specification[specification]']").val(result.receive_specification.specification);
                $("[name*='receive_specification[concentration]']").val(result.receive_specification.concentration);
                $("[name*='receive_specification[id_unit_of_concentration]']").val(result.receive_specification.id_unit_of_concentration).change();
                $("[name*='receive_specification[inaccuracy]").val(result.receive_specification.inaccuracy);
                $("[name*='receive[certificate]").val(result.receive.certificate);
                $("[name*='receive[certificate_date_expired]").val(result.receive.certificate_date_expired);
                $("[name*='receive[passport]").val(result.receive.passport);
                $("[name*='receive[id_gso_manufacturer]").val(result.receive.id_gso_manufacturer).change();
                $("[name*='receive[date_production]").val(result.receive.date_production);
                $("[name*='receive[storage_life_in_year]").val(result.receive.storage_life_in_year);

            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })


    // let postAjaxRequest = function (data,url,success,error){
    //
    //     $.ajax({
    //         url: `${url}`,
    //         type: "POST", //метод отправки
    //         dataType: 'json', // data type
    //         data: data,
    //         success: function (result) {
    //
    //         },
    //         error: function (xhr, resp, text) {
    //             console.log(xhr, resp, text);
    //         }
    //     });
    // }
})
