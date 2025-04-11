searchParams = new URLSearchParams(window.location.search)

let table = $('#table').DataTable({
    dom: 'frt<"bottom"lip>',
    pageLength: 50,
    // ordering: false,
    order: [[0, 'desc']],
    //  data: data,
    ajax: {
        type : 'POST',
        url: `/ulab/project/getListAjax`,
        data: function (d) {
            d.project_id = $("#project_id").val(),
                d.date = searchParams.get("date")
        },
        dataSrc: function (data) {
            console.log("project: ")
            console.log(data)
            return data
        }
    },
    createdRow: function (row, data, dataIndex) {
        $(row).find('td').css('text-align', 'center')
       // $(row).find('td').eq(0).attr("data-js-plan-expenses", round(data?.plan_expenses, 2))
    },
    columnDefs: [{
        targets: [3],  // Disable sorting for columns 2 and 3
        orderable: false
    }],
    columns: [
        {
            data: 'id',
            defaultContent: '',
            render: function (data, type, item) {
                return `<a href="/ulab/project/dashboard/${data}"  class="fw-bold text-dark text-decoration-none">${data}</a>`
            }
        },
        {
            data: 'name',
            defaultContent: ''
        },
        {
            data: 'plan_expenses',
            defaultContent: '',
            render: function (data, type, item) {
                return (data * 1).toLocaleString();
            }
        },
        {
            data: '',
            defaultContent: '',
            render: function (data, type, item) {
                return `<div class="btn-group d-flex">
                                <button open-row-modal="${item.id}"
                                        data-js-plan-expenses="${item.plan_expenses}"
                                        data-js-name="${item.name}"
                                        class="btn"
                                >
                                        <i class="fa-solid fa-pen"></i>
                                </button>
                           </div>`
            }
        },
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
})

$("[data-js-close-modal]").click(function (e) {
    $.magnificPopup.close();
})

$("body").on("click", "[open-row-modal]", function (e) {
    e.stopImmediatePropagation();

    let id = $(this).attr("open-row-modal")

    $("[name='id']").val(id)
    $("[name='name']").val($(this).attr("data-js-name"))
    $("[name='plan_expenses']").val($(this).attr("data-js-plan-expenses"))

    $.magnificPopup.open({
        items: {
            src: '#row-modal',
            type: 'inline'
        },
        fixedContentPos: false,
        closeOnBgClick: false,
        modal: true,
    })
})

$("#update-row").click(function (e) {
    $.ajax({
        url: '/ulab/project/insertUpdateAjax/',
        data: $("#row-modal").serialize(),
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            console.log(data)
            table.ajax.reload()
            $.magnificPopup.close();
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

