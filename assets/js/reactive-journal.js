$(function ($) {

    /*recipe journal*/
    let mainTable = $('#main_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = $('#inputIdWhichFilter').val()
            },
            url: '/ulab/reactive/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'number'
            },
            {
                data: 'name',
                render: function (data, type, item) {
                    if (item.is_precursor == 1) {
                        return `<div class="alert alert-danger" title="Прекурсор">` + data + `</div>`
                    } else return data
                }
            },
            {
                data: 'aggregate_name'
            },
            {
                data: 'short_name'
            },
            {
                data: 'doc_name'
            },
            {
                data: 'doc_receive_full_name'
            },
            {
                data: 'date_receive_dateformat'
            },
            {
                data: 'number_batch'
            },
            {
                data: 'full_quantity'
            },
            {
                data: 'date_production_dateformat'
            },
            {
                data: 'date_expired_dateformat',
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

    })

    mainTable.columns().every(function () {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('input', function () {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function () {
                mainTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#main_table').width()


    let $body = $("body")
    let $containerScroll = $body.find('.dataTables_scroll')
    let $thead = $('.journal thead tr:first-child')

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
    $('.select-reactive').select2({
        theme: 'bootstrap-5',
        placeholder: $(this).data('placeholder'),
    })
    $("body").on('change', '.all-reactive', function () {
        let unit = $(".all-reactive option:selected").data('unit')
        let number = $(".all-reactive option:selected").data('number')
        let numberReceive = $(".all-reactive option:selected").data('numberreceive')
        let idLibraryReactive = $(".all-reactive option:selected").data('idlibraryreactive')
        $('.quantity-reactive').html(unit);
        $('.number-reactive').html(number + " -");
        if ($('#add-entry-modal-form-third').attr('action') == '/ulab/reactive/updateReceiveReactive/') {
            $('.number-receive').val(numberReceive);
        } else $('.number-receive').val(numberReceive + 1);

        $('.idlibraryreactive').val(idLibraryReactive);
    })

    $('#selectReactiveUpdate').on('change', function () {
        let selectedID = $(".reactive-update option:selected").data('idreceive')
        $('#add-entry-modal-form-second').attr('action', '/ulab/reactive/updateReactive/')
        $('#add-entry-modal-form-third').attr('action', '/ulab/reactive/updateReceiveReactive/')

        $('.btn-add-entry').each(function () {
            $(this).prop('disabled', true)
        })
        $('#reactiveUpdate').prop('disabled', false)

        if (typeof (selectedID) !== "string") {
            $('#receiveUpdate').prop('disabled', false)
        }
    })
    $('#inputIdWhichFilter').on('change', function () {
        $('#selectReactiveUpdate').prop('disabled', true)
    })

    $('.btn-update').on("click", function () {
        let idButton = $(this).attr('id')
        let selectedID;
        let typeOfUpdate

        if (idButton === 'reactiveUpdate') {
            typeOfUpdate = 'reactive'
            selectedID = $(".reactive-update").val()
        } else {
            typeOfUpdate = 'reactive_receive'
            selectedID = $(".reactive-update option:selected").data('idreceive')
        }
        let whichSelectID = [typeOfUpdate, selectedID]


        $.ajax({
            url: '/ulab/reactive/setReactiveUpdate/',
            method: 'post',
            data: {
                which_select_id: whichSelectID
            },
            success: function (json) {
                let data = JSON.parse(json)

                $('[name^="reactive[' + whichSelectID[0] + ']"]').each(function () {
                        let name = $(this).attr("name")
                        let type = $(this).prop('tagName')

                        let regularExp = /[^\[\]]+/g
                        let nameForSearchInData = name.match(regularExp)[2]
                        let inputValue = data[0][nameForSearchInData]

                        if (type === "SELECT") {
                            $(this).val(inputValue).trigger('change')
                        } else if (type === "INPUT") {
                            $(this).val(inputValue)
                        }
                    }
                );
            }
        })
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        mainTable.ajax.reload()
    })

    function reportWindowSize() {
        mainTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

})
