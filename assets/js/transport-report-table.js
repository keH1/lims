$(function ($) {
    /*journal requests*/
    let tableJournal = $('#tableJournal').DataTable({
        // processing: true,
        // serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.report_id = $("#reportId").val()
            },
            url : '/ulab/transport/getReportTableAjax/',
            dataSrc: function (json) {
                console.log(json)
                return json.data;
            },
        },
        columnDefs: [{"targets": 0, "type":"de_date"}],
        columns: [
            {
                data: 'date',
                render: function (data, type, item) {
                    return item.date_str
                }
            },
            {
                data: 'time_start'
            },
            {
                data: 'time_end'
            },
            {
                data: 'km'
            },
            {
                data: 'gsm'
            },
            {
                data: 'price'
            },
            {
                data: 'sum'
            },
            {
                data: 'place'
            },
            {
                data: '',
                render: function (data, type, item) {
                    return `<div class="btn-group">
                                <button data-js-update="${item.id}" class="btn"><i class="fa-solid fa-pen"></i></button>
                                <button data-js-delete="${item.id}" class="btn"><i class="fa-solid fa-trash-can"></i></button>
                           </div>`
                }
            },

        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 100,
        order: [[ 0, "desc" ]],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        // scrollX:       true,
        // fixedHeader:   true,
        //autoWidth: false

    });

    tableJournal.columns().every( function () {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'keyup change clear', function () {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function () {
                tableJournal
                    .column( $(this).parent().index() )
                    .search(searchValue)
                    .draw();
            }.bind(this), 1000)
        })
    })

    /*journal filters*/
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        tableJournal.ajax.reload()
    })

    function reportWindowSize() {
        tableJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    $('#user').select2();
    $('#transport').select2();

    // Добавить чек
    $("body").on('click', '[data-js-update-check]', function () {
        $.magnificPopup.open({
            items: {
                src: '#add-check-modal-form',
                type: 'inline'
            },
            fixedContentPos: false
        });

        let id = $(this).attr("data-js-update-check")
        $("#report_check_id").val(id)

        if (id) {
            $("#check_date").val($(this).attr("data-js-check-date"))
            $("#check_sum").val($(this).attr("data-js-check-sum"))
            $("#check_place").val($(this).attr("data-js-check-place"))
            $("#check_number").val($(this).attr("data-js-check-number"))
        } else {
            let userId = $("#currentUser").val();

            $("#date").val("")
            $(`#user`).val(userId).change();
            $(`#transport`).val("").change();
            $("#time_start").val("")
            $("#time_end").val("")
            $("#km").val("")
            $("#gsm").val("")
            $("#price").val("")
            $("#place").val("")
        }

    })

    $("#add-check-modal-btn").click(function (e) {

        let check = true;
        console.log("t")

        // $("[data-js]").each(function (index) {
        //     if ($(this).val() == "") {
        //         $(this).css("background", "#F08080")
        //         check = false;
        //     } else {
        //         $(this).css("background", "#FFF")
        //     }
        // })

        if (check) {
            $.ajax({
                url: '/ulab/transport/addReportCheckAjax/',
                data: {
                    date: $("#check_date").val(),
                    sum: $("#check_sum").val(),
                    place: $("#check_place").val(),
                    number: $("#check_number").val(),
                    report_id: $("#reportId").val(),
                    report_check_id: $("#report_check_id").val()
                },
                method: 'POST',
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                    if (data) {
                        tableCheck.ajax.reload()
                        $('.mfp-close').click();
                    }

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
        }


    })

    // Добавить транспорт
    $("body").on('click', '[data-js-update]', function () {
        $.magnificPopup.open({
            items: {
                src: '#add-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false
        });

        let id = $(this).attr("data-js-update")
        $("#report_id").val(id)

        if (id) {
            let $tr = $(this).closest("tr")
            let values = $tr.find("td")

            let dateText = $(values).eq(0).text()
            let dateArr = dateText.split(".")
            let dateValue = `${dateArr[2]}-${dateArr[1]}-${dateArr[0]}`
            $("#date").val(dateValue)

            $("#time_start").val($(values).eq(1).text())
            $("#time_end").val($(values).eq(2).text())
            $("#km").val($(values).eq(3).text())
            $("#gsm").val($(values).eq(4).text())
            $("#price").val($(values).eq(5).text())
            $("#place").val($(values).eq(7).text())
        } else {
            let userId = $("#currentUser").val();

            $("#date").val("")
            $(`#user`).val(userId).change();
            $(`#transport`).val("").change();
            $("#time_start").val("")
            $("#time_end").val("")
            $("#km").val("")
            $("#gsm").val("")
            $("#price").val("")
            $("#place").val("")
        }

    })

    $("#add-entry-modal-btn").click(function (e) {

        let check = true;

        // $("[data-js]").each(function (index) {
        //     if ($(this).val() == "") {
        //         $(this).css("background", "#F08080")
        //         check = false;
        //     } else {
        //         $(this).css("background", "#FFF")
        //     }
        // })

        if (check) {
            $.ajax({
                url: '/ulab/transport/addReportRowAjax/',
                data: $("#add-entry-modal-form").serialize(),
                method: 'POST',
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                    if (data) {
                        tableJournal.ajax.reload()
                        $('.mfp-close').click();
                    }

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
        }


    })

    $("body").on('click', '[data-js-delete]', function () {
        $.magnificPopup.open({
            items: {
                src: '#delete-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false
        });

        let id = $(this).attr("data-js-delete")
        $("#delete_transport").val(id)
    })

    $("body").on('click', '[data-js-delete-check]', function () {
        $.magnificPopup.open({
            items: {
                src: '#delete-check-modal-form',
                type: 'inline'
            },
            fixedContentPos: false
        });

        $("#delete_check").val($(this).attr("data-js-delete-check"))
    })

    $("#delete-check-modal-btn").click(function (e) {
        $.ajax({
            url: '/ulab/transport/deleteReportCheckAjax/',
            data: { report_check_id:  $("#delete_check").val() },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                console.log(data)
                if (data) {
                    tableCheck.ajax.reload()
                    $('.mfp-close').click();
                }

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

    $("#delete-entry-modal-btn").click(function (e) {
        $.ajax({
            url: '/ulab/transport/deleteAjax/',
            data: $("#delete-entry-modal-form").serialize(),
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                console.log(data)
                if (data) {
                    tableJournal.ajax.reload()
                    $('.mfp-close').click();
                }

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

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#tableJournal').width()

    $('.btnRightTable, .arrowRight').hover(function() {
            container.animate(
                {
                    scrollLeft: scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function() {
            container.stop();
        })

    $('.btnLeftTable, .arrowLeft').hover(function() {
            container.animate(
                {
                    scrollLeft: -scroll
                },
                {
                    duration: 4000, queue: false
                }
            )
        },
        function() {
            container.stop();
        })

    $(document).scroll(function() {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height()

        if (positionScroll > 265 && positionScroll < tableScrollBody) {
            $('.arrowRight').css('transform',`translateY(${positionScroll-260}px)`);
            $('.arrowLeft').css('transform',`translateY(${positionScroll-250}px)`);
        }
    })

    let gsmCounter = 0;

    $("body").on('click', '#gsm-report-add', function (e) {
        const $tbody = $("#gsm-report-table").find("tbody");

        $tbody.append(`
        <tr>
            <td><input type="date" name="gsmText[${gsmCounter}][date]" class="form-control"></td>
            <td><input type="time" name="gsmText[${gsmCounter}][time_start]" class="form-control"></td>
            <td><input type="time" name="gsmText[${gsmCounter}]time_end]" class="form-control"></td>
            <td><input type="number" data-js-km="${gsmCounter}" name="gsmText[${gsmCounter}][km]" class="form-control"></td>
            <td><input type="number" data-js-gsm="${gsmCounter}" name="gsmText[${gsmCounter}][gsm]" class="form-control"></td>
            <td><input type="number" name="gsmText[${gsmCounter}][price]" class="form-control"></td>
            <td><input type="text" name="gsmText[${gsmCounter}][object]" class="form-control"></td>
            <td class="d-flex justify-content-center">
                <button data-js-remove-row type="button" class="btn btn-danger rounded fa-solid fa-minus"></button>
            </td>
        </tr>
    `);

        gsmCounter++;
    })

    $("body").on('click', '[data-js-remove-row]', function () {
        $(this).closest("tr").remove()
    })

    $("body").on("keyup", "[data-js-km]", function (e) {
      //  let index = $(this).attr("data-js-km")
        let km = $(this).val();
        let consumptionRate = $("[data-js-consumption-rate]").text()

        console.log(km)
        let gsm = km * consumptionRate / 100
        gsm = Math.round((gsm + Number.EPSILON) * 100) / 100
        $(`[data-js-gsm]`).val(gsm)
    })

    $("body").on("click", "[data-js-memo-doc]", function (e) {
        $("[data-js-spinner=\"memo\"]").removeClass("d-none")
        $("[data-js-file-wrap=\"memo\"]").removeClass("d-none")
        $("[data-js-file-wrap=\"memo\"]").hide()
        let href = $("[data-js-file-wrap]").find("a").attr("href")

        console.log("test")
        console.log(href)
        $.ajax({
            url: `/ulab/transport/generateMemoDocAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "report_id": $(this).attr("data-js-memo-doc")
            },
            success: function (result) {
                console.log(result);
                $("[data-js-spinner]").addClass("d-none")
                $("[data-js-file-wrap=\"memo\"]").show()
                $("[data-js-file-wrap=\"memo\"]").find("a").attr("href","/ulab/upload/transport/memo/" + result["id"] + "/" + result["file_name"])
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("body").on("click", "[data-js-report-doc]", function (e) {
        $("[data-js-spinner=\"report\"]").removeClass("d-none")
        $("[data-js-file-wrap=\"report\"]").removeClass("d-none")
        $("[data-js-file-wrap=\"report\"]").hide()
        let href = $("[data-js-file-wrap]").find("a").attr("href")

        console.log("test")
        console.log(href)
        $.ajax({
            url: `/ulab/transport/generateReportDocAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "report_id": $(this).attr("data-js-report-doc")
            },
            success: function (result) {
                console.log(result);
                $("[data-js-spinner]").addClass("d-none")
                $("[data-js-file-wrap=\"report\"]").show()
                $("[data-js-file-wrap=\"report\"]").find("a").attr("href","/ulab/upload/transport/report/" + result["id"] + "/" + result["file_name"])
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("body").on("click", "[data-js-compensation-doc]", function (e) {
        $("[data-js-spinner=\"compensation\"]").removeClass("d-none")
        $("[data-js-file-wrap=\"compensation\"]").removeClass("d-none")
        $("[data-js-file-wrap=\"compensation\"]").hide()
        let href = $("[data-js-file-wrap]").find("a").attr("href")

        console.log(href)
        $.ajax({
            url: `/ulab/transport/generateCompensationDocAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "report_id": $(this).attr("data-js-compensation-doc")
            },
            success: function (result) {
                console.log(result);
                $("[data-js-spinner]").addClass("d-none")
                $("[data-js-file-wrap=\"compensation\"]").show()
                $("[data-js-file-wrap=\"compensation\"]").find("a").attr("href","/ulab/upload/transport/compensation/" + result["id"] + "/" + result["file_name"])
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

//    Таблица с чеками

    let tableCheck = $('#tableCheck').DataTable({
        // processing: true,
        // serverSide: true,
        ajax: {
            type : 'POST',
            data: function ( d ) {
                d.report_id = $("#reportId").val()
            },
            url : '/ulab/transport/getCheckTableAjax/',
            dataSrc: function (json) {
                console.log(json)
                return json.data;
            },
        },
        columns: [
            {
                data: 'date_str'
            },
            {
                data: 'number',
                render: function (data, type, item) {
                    return data
                }
            },
            {
                data: 'sum'
            },
            {
                data: 'place'
            },
            {
                data: '',
                render: function (data, type, item) {
                    return `<div class="btn-group">
                                <button class="btn" 
                                        data-js-update-check="${item.id}" 
                                        data-js-check-number="${item.number}"
                                        data-js-check-place="${item.place}"
                                        data-js-check-date="${item.date}"
                                        data-js-check-sum="${item.sum}"
                                ><i class="fa-solid fa-pen"></i></button>
                                <button data-js-delete-check="${item.id}" class="btn"><i class="fa-solid fa-trash-can"></i></button>
                           </div>`
                }
            },

        ],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 0, "desc" ]],
        colReorder: true,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        // scrollX:       true,
        // fixedHeader:   true,
        //autoWidth: false

    });

    $("#tableJournal_wrapper .bottom").hide();
    $("#tableCheck_wrapper .bottom").hide();


})