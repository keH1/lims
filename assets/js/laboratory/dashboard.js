$(document).ready(function () {
    let searchParams = new URLSearchParams(window.location.search)
  //  let test_list = [];
    let values = [];
    let ulab_values = []
    let cellArr = []
    let fixedCol = 1
    let scheme_data = []

    $( "[data-js-test-id]" ).each(function( index ) {
        let testId = $( this ).attr("data-js-test-id");

            let obj = {
                data: '',
                defaultContent: '',
                orderable: false,
                render: function (data, type, item, meta) {
                    let result = ""

                    if (parseInt(item.laboratory_status) === 1) {
                        if (parseInt(scheme_data.material_type) === 1) {
                            result = values.filter((itemValue) =>
                                itemValue.oz_passport_id == testId
                                && itemValue.scheme_gost_id == item.id)[0]?.value
                        } else {
                            result = values.filter((itemValue) =>
                                itemValue.oz_tz_id == testId
                                && itemValue.scheme_gost_id == item.id)[0]?.value
                        }

                    } else {
                        if (parseInt(scheme_data.material_type) === 1) {
                            result = ulab_values.filter((itemValue) =>
                                itemValue.passport_id == testId
                                && itemValue.scheme_gost_id == item.id)[0]?.actual_value
                        } else {
                            if (testId == 412) {
                                console.log("test!!")
                                let test = ulab_values.filter((itemValue) =>
                                    itemValue.tz_id == testId
                                    && itemValue.scheme_gost_id == item.id)[0]?.actual_value
                                console.log(test)
                            }
                            result = ulab_values.filter((itemValue) =>
                                itemValue.tz_id == testId
                                && itemValue.scheme_gost_id == item.id)[0]?.actual_value
                        }
                    }

                    let bg = ""
                    let from = item.range_from ? parseFloat(item.range_from) : -Infinity
                    let before = item.range_before ? parseFloat(item.range_before) : Infinity

                    if (result) {
                        if (isNumeric(result)) {
                            if (result >= from && result <= before) {
                                bg = "bg-light-green-2"
                            } else {
                                bg = "bg-light-red"
                            }
                        } else {
                            bg = "bg-orange-2"
                        }

                    }

                    return `
                        <div class="pos-a ${bg}">
                           <div class="valign">${result ?? ""}</div> 
                        </div>
                    `
                }
            }

            cellArr.push(obj)
    })

    const dataTable = $('#table').DataTable({
        dom: 'frt<"bottom"lip>',
        pageLength: 50,
        order: [[0, 'desc']],
        bSortCellsTop: true,
        scrollX: true,
        scrollY: true,
        fixedHeader: true,
        fixedColumns: {
            leftColumns: fixedCol
        },
        ajax: {
            type : 'POST',
            // url: `${URI}/request/getProductionListAjax/`,
            url: `https://ulab.niistrom.pro/api/scheme/getDashboardAjax`,
            data: function (d) {
                d.token = TOKEN,
              //  d.scheme_id = searchParams.get("scheme_id")
                d.scheme_id = $("#scheme_id").val()
            },
            dataSrc: function (data) {
                console.log("journal")
                console.log(data)
                values = data?.values
                ulab_values = data?.ulab_values
                scheme_data = data?.scheme_data
               // test_list = data?.test_list
                return data?.scheme_gost
            }
        },
        createdRow: function (row, data, dataIndex) {
            $(row).find('td').css('position', 'relative')
            $(row).find('td').css('text-align', 'center')
            // $(row).attr("row-id", data.id)
            $(row).find('td').eq(0).css('text-align', 'left').addClass("pl-10")

            if (parseInt(data.del) === 1) {
                $("#hide-row").removeClass("d-none")
                $(row).find('td').eq(0).addClass("bg-light-red")
                $(row).find('td').eq(1).addClass("bg-light-red")

                $(row).attr("data-js-visible", "").addClass("d-none")
            }

            // let rowLength = $(row).find("td").length
            //
            // $(row).find('td')
            //     .slice(fixedCol, rowLength - 1)
            //     .addClass("border-1")

        },
        columns: [
            {
                data: 'id',
                visible: false
                // render: function (data, type, item, meta) {
                //     console.log(meta.settings.aoData.length)
                //     return `<b>${meta.settings.aoData.length}</b>`;
                // }
            },
            {
                data: 'method_name',
                defaultContent: '',
                orderable: false,
                render: function (data, type, item, meta) {
                    let icon = parseInt(item.laboratory_status) === 1
                        ? '<i title="Своя лабаратория" class="fa-solid fa-star text-primary"></i>'
                        : ''
                    return `${data} ${icon}`
                }
            },
            {
                data: 'param',
                defaultContent: '',
                orderable: false,
            },
            {
                data: 'range_from',
                defaultContent: '',
                orderable: false,
            },
            {
                data: 'range_before',
                defaultContent: '',
                orderable: false,
            },
            ...cellArr,
        ],
        language: {
            processing: '<div class="processing-wrapper">Подождите...</div>',
            search: '',
            searchPlaceholder: "Поиск...",
            lengthMenu: 'Отображать _MENU_  ',
            info: 'Записи с _START_ до _END_ из _TOTAL_ записей',
            infoEmpty: 'Записи с 0 до 0 из 0 записей',
            infoFiltered: '(отфильтровано из _MAX_ записей)',
            infoPostFix: '',
            loadingRecords: 'Загрузка записей...',
            zeroRecords: 'Записи отсутствуют.',
            emptyTable: 'В таблице отсутствуют данные',
            paginate: {
                first: 'Первая',
                previous: 'Предыдущая',
                next: 'Следующая',
                last: 'Последняя'
            },
            aria: {
                sortAscending: ': активировать для сортировки столбца по возрастанию',
                sortDescending: ': активировать для сортировки столбца по убыванию'
            }
        }
        //  data: dataSet
    });

    $(document).scroll(function () {
        $(".dtfh-floatingparenthead th")
            .css("padding-inline", "0px")
            .css("font-size", "14px")
    })

    // Скрол по старнице
    let container = $('div.dataTables_scrollBody')
    let scroll = $("#table").width()

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
            container.stop();
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
            container.stop();
        })

    $(document).scroll(function () {
        let positionScroll = $(window).scrollTop(),
            tableScrollBody = container.height()

        if (positionScroll > 50 && positionScroll < tableScrollBody) {
            // $('.arrowRight').css('transform', `translateY(${positionScroll - 260}px)`);
            // $('.arrowLeft').css('transform', `translateY(${positionScroll - 250}px)`);
            $('.btn-group-toolbar').css('transform', `translateY(${positionScroll - 35}px)`);


        }
    })

    $("#hide-row").click(function (e) {
        $(this).attr('visible', function(index, attr){
            return attr == 1 ? 0 : 1;
        });

        let visible = parseInt($(this).attr('visible'))
        let icon = ""

        if (visible === 1) {
            icon = '<i class="fa-solid fa-eye"></i>'
            $("body").find("[data-js-visible]").removeClass("d-none")
        } else {
            icon = '<i class="fa-solid fa-eye-slash"></i>'
            $("body").find("[data-js-visible]").addClass("d-none")
        }

        $("#hide-row").html(icon)
    })

})