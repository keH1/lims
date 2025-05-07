$(function ($) {

        let mainTable = $('#main_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                type: 'POST',
                data: function (d) {
                    d.idWhichFilter = $('#inputIdWhichFilter').val()
                    d.dateStart = $('#inputDateStart').val() || "0001-01-01"
                    d.dateEnd = $('#inputDateEnd').val() || "9999-12-31"
                },
                url: '/ulab/parasite/getListProcessingAjax/',
                dataSrc: function (json) {
                    return json.data
                },
            },
            columns: [
                {
                    data: 'sample_conform',
                    orderable: false,
                    render: function (data) {
                        if (data == 0) {
                            return `<span class="cursor-pointer not-conformity" title="Обнаружено">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 text-danger" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                        </svg>
                                    </span>`;
                        } else if (data == 1) {
                            return `<span class="cursor-pointer not-conformity" title="Не обнаружено">
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
                    data: 'select_datetime_dateformat'
                },
                {
                    data: 'name_type_control'
                },
                {
                    data: 'name_location'
                },
                {
                    data: 'name_solution_sampling'
                },
                {
                    data: 'number_sample_point'
                },
                {
                    data: 'sample_user'
                },
                {
                    data: 'porg_datetime_start_dateformat'
                },
                {
                    data: 'porg_doc_name'
                },
                {
                    data: 'porg_preparation_type'
                },
                {
                    data: 'porg_parameter_preparation'
                },
                {
                    data: 'porg_datetime_finish_dateformat'
                },
                {
                    data: 'porg_result_full'
                },
                {
                    data: 'porg_user'
                },
                {
                    data: 'sorg_datetime_start_dateformat'
                },
                {
                    data: 'sorg_doc_name'
                },
                {
                    data: 'sorg_datetime_finish_dateformat'
                },
                {
                    data: 'sorg_result_full'
                },
                {
                    data: 'sorg_user'
                },
                {
                    data: 'dot_is_conform_full'
                },
                {
                    data: 'sample_conform_full'
                }

            ],

            columnDefs: [{
                className: 'control',
            },

            ],
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
            rowCallback: function (Row, data) {
                $("td:contains('Обнаружено')", Row).addClass('text-danger')
                $("td:contains('Не соответствует')", Row).addClass('text-danger')
            }
        })

        mainTable.columns().every(function () {
            $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('input', function () {
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

        $('[name="toSQL[parasite_result][id_parasite_type_method_for_parasite]"]').on("change", function () {

            if ($(this).val() == 1) {
                $('[name="toSQL[parasite_result][id_parasite_type_preparation]"]').val(1).change();
                $(".parameter_preparation").html("Флотационный раствор, плотность, г/л")
            } else {
                $('[name="toSQL[parasite_result][id_parasite_type_preparation]"]').val(2).change();
                $(".parameter_preparation").html("Cкорость и время центрифугирования")
            }
        })

        $('[name="toSQL[jn_lbf_psorg_smpl][rb_lbf_cont_obj_id]"]').on("change", function () {

            let object = $(`.object_${$(this).val()}`)

            $("div").find(".object_lbf").css("display", "none")

            $(".object_lbf").find("select").attr("disabled", true)
            $(".object_lbf").find("input").attr("disabled", true)

            $(object).css("display", "block")

            $(object).find("select").attr("disabled", false)
            $(object).find("input").attr("disabled", false)
        })
        $('[name="toSQL[jn_lbf_psorg_porg_data][rb_lbf_psorg_doc_id]"]').on("change", function () {

            let object

            if ($(this).val() == 1) {
                object = $(`.centrifuge`)
            } else object = $(`.flot`)


            $("div").find(".object_lbf").css("display", "none")

            $(".object_lbf").find("select").attr("disabled", true)
            $(".object_lbf").find("input").attr("disabled", true)

            $(object).css("display", "block")

            $(object).find("select").attr("disabled", false)
            $(object).find("input").attr("disabled", false)
        })

        $('[name="toSQL[jn_lbf_psorg_dot][jn_lbf_psorg_smpl_id]"]').on("change", function () {

            $(".result-parasite").empty()
            let selectedID = $(this).find(":selected").data().id
            let selectedQuantity = $(this).find(":selected").data().quantity
            let parasite = "";
            for (let i = 0; i < Number(selectedQuantity); i++) {
                parasite += "<div class='row'>";
                parasite += "<div class='col-sm-6'>";
                parasite += "<div class='main_block'>";
                parasite += "<div class='secondary_block'>";
                parasite += "<label class='form-label'>" + selectedID + "-" + (i + 1) + "</label>";
                parasite += "</div>";
                parasite += "<div class='secondary_block out_block'>";
                parasite += "<div><label class='form-check-label'>Обнаружено</label></div>";
                parasite += "<div><input name='toSQL[jn_lbf_psorg_porg_reg][" + (i + 1) + "][result]' class='form-control' type='number' value='0' hidden></div>";
                parasite += "<div><input name='toSQL[jn_lbf_psorg_porg_reg][" + (i + 1) + "][result]' class='form-check-input' type='checkbox' value='1'></div>";
                parasite += "</div>";
                parasite += "</div>";
                parasite += "</div>";
                parasite += "</div>";
            }
            $(".result-parasite").append(parasite)

        })
        $('[name="toSQL[jn_lbf_psorg_smpl_id]"]').on("change", function () {

            $(".result-simple").empty()
            let selectedID = $(this).find(":selected").data().id
            let selectedQuantity = $(this).find(":selected").data().quantity
            let simple = "";
            for (let i = 0; i < Number(selectedQuantity); i++) {
                simple += "<div class='row'>";
                simple += "<div class='col-sm-6'>";
                simple += "<label class='form-label'>" + selectedID + "-" + (i + 1) + "</label>";
                simple += "<input name='toSQL[jn_lbf_psorg_sorg_reg][" + (i + 1) + "][result]' class='form-control sorg' type='number' value='0' hidden>";
                simple += "<input name='toSQL[jn_lbf_psorg_sorg_reg][" + (i + 1) + "][result]' class='form-check-input sorg'  type='checkbox' value='1'>";
                simple += "<label class='form-check-label'>Обнаружено</label>";
                simple += "</div>";
                simple += "</div>";
            }
            $(".result-simple").append(simple)
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
        $("#sample_copy").on("change", function () {
            if ($(this).prop("checked") == true) {
                var id= $('[name="toSQL[microb_sampling][sampling_id]"]').val()
                console.log(id)
            } else {

            }
        })
    }
)
