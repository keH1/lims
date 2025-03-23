const microbTypeControl = {
    1: {
        1: -1, 2: -1, 3: 4, 4: 3
    },
    2: {
        1: -1, 2: -1, 3: 4, 4: 500
    },
    3: {
        1: 0, 2: 0, 3: 0, 4: 0
    },
    4: {
        1: 0, 2: 0, 3: 0, 4: 0
    },
    5: {
        1: 0, 2: 0, 3: 0, 4: 0
    }
}

$(function ($) {
    let mainTable = $('#main_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.journalType = $("#selected_journal").val()
                d.idWhichFilter = $('#inputIdWhichFilter').val()
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
            },
            url: '/ulab/microb/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: [
            {
                data: 'results',
                orderable: false,
                render: function (data, type, item) {
                    if (item.conform_sampling == 1) {
                        return `<span class="cursor-pointer not-conformity" title="Не соответствует">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-danger" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                </span>`;
                    } else if (item.conform_sampling == 0) {
                        return `<span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-success" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </span>`;
                    } else {
                        return ``
                    }
                }
            },
            {
                data: 'sample_number'
            },
            {
                data: 'datetime_finish_dateformat'
            },
            {
                data: 'name_type_microb'
            },
            {
                data: 'name_type_control'
            },
            {
                data: 'name_control'
            },
            {
                data: 'number_sample_point'
            },
            {
                data: 'property_select'
            },
            {
                data: 'exposition_time'
            },
            {
                data: 'medium'
            },
            {
                data: 'volume_air'
            },
            {
                data: 'place_selection'
            },
            {
                data: 'datetime_start_dateformat'
            },
            {
                data: 'medium_grow_name'
            },
            {
                data: 'number_batch'
            },
            {
                data: 'temperature_inсubation_full'
            },
            {
                data: 'time_inсubation_hour_full'
            },
            {
                data: 'thermostat_name'
            },
            {
                data: 'datetime_finish_result_dateformat'
            },
            {
                data: 'result_full'
            },
            {
                data: 'round',
                orderable: false,
                render: function (data, type, item) {
                    if ( item.conclusion !== undefined && item.conclusion !== '' && item.conclusion !== null ) {
                        const norm = microbTypeControl[item.id_microb_type_control][item.id_microb_type_microb]

                        if ( norm == -1 ) {
                            return `норма не известна`
                        } else if ( norm == 0 ) {
                            return `рост отсутствует`
                        } else if ( norm > 0 ) {
                            if ( item.id_microb_type_control == 2 ) {
                                return `меньше или равно ${norm} КОЕ/м<sup>3</sup>`
                            } else {
                                return `меньше или равно ${norm} КОЕ`
                            }
                        }
                    }
                    
                    return ``
                }
            },
            {
                data: 'is_grow_positive_full'
            },
            {
                data: 'conclusion'
            },
            {
                data: 'global_assigned_name'
            }
        ],
        columnDefs: [{
            visible: false,
            targets: [8, 9, 10, 11]
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

    })

    mainTable.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            mainTable
                .column($(this).parent().index())
                .search(this.value)
                .draw()
        })
    })

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#main_table').width()

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
    $('.select-oborud').select2({
        placeholder: 'Выберете оборудование',
        width: '100%',
    })

    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        let id = ($('#inputIdWhichFilter').val())
        let invisibleColumns = [
            {
                value: -1,
                numbersColumns: [8, 9, 10, 11]
            },
            {
                value: 1,
                numbersColumns: [7, 10, 11]
            },
            {
                value: 2,
                numbersColumns: [7, 8, 9, 11]
            },
            {
                value: 3,
                numbersColumns: [7, 8, 10, 11]
            },
            {
                value: 4,
                numbersColumns: [7, 8, 9, 10, 11]
            },
            {
                value: 5,
                numbersColumns: [7, 8, 10]
            }
        ];
        mainTable.columns([7, 8, 9, 10, 11]).visible(true);
        for (let i = 0; i < invisibleColumns.length; i++) {
            if (invisibleColumns[i].value == id) {
                mainTable.columns(invisibleColumns[i].numbersColumns).visible(false)
                break
            }
        }
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

    $('[name="toSQL[microb_sampling][id_microb_type_control]"]').on("change", function () {

        let object = $(`.object_${$(this).val()}`)

        $("div").find(".object_lbf").css("display", "none")

        $(".object_lbf").find("select").attr("disabled", true)
        $(".object_lbf").find("input").attr("disabled", true) 

        $(object).css("display", "block")

        $(object).find("select").attr("disabled", false)
        $(object).find("input").attr("disabled", false)
    })

    $('[name="toSQL[microb_result_sowing][id_microb_sampling]"]').on("change", function () {
        let selectedID = $(this).val()
        let selectedSamlingId = $(this).find(":selected").data().id
        $.ajax({
            url: '/ulab/microb/setSeedingResult/',
            method: 'post',
            data: {
                sample_number: selectedID
            },
            success: function (json) {
                data = JSON.parse(json)

                $(".points").empty()
                let html = "";

                for (let i = 1; i <= Number(data.quantity_sample_point); i++) {
                    html += "<div class='point_block'>";
                    html += "<div>";
                    html += "<div><label>" + selectedSamlingId + "-" + i + "</label></div>";
                    html += "<input type='number' class='form-control' data-id='" + i + "' name='toSQL[results][" + i + "][result]' required>";
                    html += "<input type='hidden' data-id='" + i + "' name='toSQL[results][" + i + "][result]' value='-1' disabled>";
                    html += "</div>";

                    html += "<div class='point_grow'>";
                    html += "<div><label'>Сплошной рост</label></div>";
                    html += "<div><input type='checkbox' class='form-check-input' data-id='" + i + "'></div>";
                    html += "</div>";
                    html += "</div>";
                }
                $(".points").append(html)
            }
        })
    })

    $("#sample_copy").on("change", function () {
        if ($(this).prop("checked") == true) {
            let sampleNumber= $('[name="toSQL[microb_sampling][sample_number]"]').val()

            $.ajax({
                url: '/ulab/microb/setSampleCopy/',
                method: 'post',
                data: {
                    sample_number: sampleNumber
                },
                success: function (json) {
                    let data = JSON.parse(json)

                    $(".sample-readonly").each(function () {

                            let name = $(this).attr("name")
                        console.log(name)
                            let type = $(this).prop('tagName')
                        console.log(type)
                            let regularExp = /[^\[\]]+/g
                            let nameForSearchInData = name.match(regularExp)[2]
                            let inputValue = data[nameForSearchInData]
                        console.log(inputValue)
                            if (type === "SELECT") {
                                $(this).val(inputValue).trigger('change')
                            } else if (type === "INPUT") {
                                $(this).val(inputValue)
                            }
                        }
                    );
                }
            })
            $(".sample-readonly option:not(:selected)").attr('disabled', true);
            $(".sample-readonly").attr("readonly", true)

        } else {
            $(".sample-readonly").attr("readonly", false)
            $(".sample-readonly option:not(:selected)").attr('disabled', false);
        }
    })


    $("body").on("click", ".point_block input[type='checkbox']", function (event) {
        let dataId = $(this).attr("data-id")
        if ($(this).prop("checked") == true) {
            $(`input[type='number'][data-id=${dataId}]`).attr("disabled", true)
            $(`input[type='hidden'][data-id=${dataId}]`).attr("disabled", false)
        } else {
            $(`input[type='number'][data-id=${dataId}]`).attr("disabled", false)
            $(`input[type='hidden'][data-id=${dataId}]`).attr("disabled", true)
        }
    })

    $(".flextabs__toggle").on("click", function () {

        let dataType = Number($(this).attr("data-type"))
        let journalType = $("#selected_journal")
        $(journalType).val("")

        $(journalType).val(dataType)

        mainTable.ajax.reload()
        mainTable.draw()
    })

    $('[name="toSQL[microb_sowing][id_microb_sampling]"]').on("change", function () {
        let dataId = $(this).find(":selected").data().id
        let dataGrowId = $(this).find(":selected").data().medium
        let dataGrow = $(this).find(":selected").data().grow
        let dataName = $(this).find(":selected").data().name

        let inputBatch = $('[name="toSQL[microb_sowing][id_microb_medium_grow]"]')
        if (dataId == 1) {
            $(inputBatch).val("")
            $(inputBatch).val(dataGrowId)
            $(inputBatch).attr("disabled", false)

            $(`[name="toSQL[microb_medium_grow][id_microb_type_medium_grow]"] option[value=${dataGrow}]`).prop('selected', true);
            $('[name="toSQL[microb_medium_grow][id_microb_type_medium_grow]"]').attr("disabled", true)
            $('[name="toSQL[microb_medium_grow][number_batch]"]').val(dataName)
            $('[name="toSQL[microb_medium_grow][number_batch]"]').attr("readonly", true)
        } else {
            $(inputBatch).val("")
            $('[name="toSQL[microb_medium_grow][number_batch]"]').val("")
            $('[name="toSQL[microb_medium_grow][number_batch]"]').attr("readonly", false)
            $(inputBatch).attr("disabled", true)

            $(`[name="toSQL[microb_medium_grow][id_microb_type_medium_grow]"] option[value=""]`).prop('selected', true);
            $('[name="toSQL[microb_medium_grow][id_microb_type_medium_grow]"]').attr("disabled", false)
        }
    })
})