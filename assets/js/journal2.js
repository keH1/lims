function getJournalDataTable($element, columns) {
    return $element.DataTable({
        bAutoWidth: false,
        autoWidth: false,
        fixedColumns: false,
        processing: true,
        serverSide: true,
        bSortCellsTop: true,
        scrollX: true,
        fixedHeader: false,
        colReorder: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.dateStart = $('#inputDateStart:visible').val() || "0001-01-01"
                d.dateEnd = $('#inputDateEnd:visible').val() || "9999-12-31"
                d.stage = $('#selectStage:visible option:selected').val()
                d.lab = $('#selectLab option:selected').val()
                d.type_journal = $('input[name="type_journal"]:checked').val()
            },
            url : '/ulab/request/getListProcessingAjax/',
            dataSrc: function (json) {
                return json.data
            },
        },
        columns: columns,
        createdRow: function(row, data, dataIndex) {
            if ( data.TYPE_ID != 9 ) {
                $(row).find('td:eq(0)').attr('data-lab', data.LABA_ID);
                $('td:eq(10)', row).addClass(data.bgPrice);
                $('td:eq(7)', row).addClass(data.bgOrder);
                $('td:eq(6)', row).addClass(data.bgPdf);
                $(row).addClass(data.bgCheck);
            }
        },
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 25,
        order: [[ 2, "desc" ]],
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        initComplete: function (settings) {
            let api = this.api()
            api.columns().every(function () {
                let timeout
                $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on( 'input', function () {
                    clearTimeout(timeout)
                    const searchValue = this.value
                    timeout = setTimeout(function () {
                        api
                            .column($(this).parent().index())
                            .search(searchValue)
                            .draw()
                    }.bind(this), 1000)
                })
            })
        }
    })
}

$(function ($) {

    let journalDataTable = null
    // тип журнала gov - Гос, comm - Коммерческий
    let $radioTypeJournal = $('input[name="type_journal"]')

    let columnsCommJournal = [
        {
            data: 'stage',
            orderable: false,
            render: function (data, type, item) {
                return `<div class="stage rounded ${item['bgStage']}" title="${item['titleStage']}"></div>`
            }
        },
        {
            data: 'requestTitle',
            class: 'text-nowrap',
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return `<a class="request-link"
                               href="/ulab/request/card/${item.ID_Z}">
                               ${item['REQUEST_TITLE']}${item['certificate']}
                            </a>`
                }

                return item.request
            }
        },
        {
            data: 'DATE_CREATE_TIMESTAMP',
            width: 100,
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return item.dateCreateRu
                }
                return data;
            },
        },
        {
            data: 'COMPANY_TITLE',
            width: 300,
            // render: $.fn.dataTable.render.ellipsis(55, true)
        },
        {
            data: 'MATERIAL',
            render: $.fn.dataTable.render.ellipsis(40, true)
        },
        {
            data: 'ASSIGNED',
            orderable: true,
            render: $.fn.dataTable.render.ellipsis(40, true)
        },
        {
            data: 'tz',
            width: '100px',
            render: function (data, type, item) {
                return `<a class="number-tz" href="/ulab/requirement/card/${item['b_tz_id']}" >
                                ${item['b_tz_id']}
                            </a>`
            },
        },
        {
            data: 'DOGOVOR_TABLE',
            //class: 'text-nowrap',
            width: '100px',
            render: $.fn.dataTable.render.ellipsis(40, true)
        },
        {
            data: 'NUM_ACT_TABLE',
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return `<a class="number-act" href="/ulab/probe/card/${item['ID_Z']}" >
                                ${item['NUM_ACT_TABLE']}
                            </a>`
                }

                return item.ACT_NUM
            },
        },
        {
            data: 'ACCOUNT',
            render: function (data, type, item) {
                return item.ACCOUNT
            },
        },
        {
            data: 'price_discount',
            render: $.fn.dataTable.render.intlNumber('ru-RU', { minimumFractionDigits: 2 })
        },
        {
            data: 'DATE_OPLATA',
            width: 100,
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return item.DATE_OPLATA
                }

                return item.dateOplataISO
            },
        },
        {
            data: 'linkResult',
            orderable: false,
            render: function (data, type, item) {
                return `<a class="results-link" href="/ulab/result/card_oati/${item['ID_Z']}" >
                            ${item['linkName']}
                        </a>`
            },
        },
        {
            data: 'PROTOCOLS',
            orderable: false,
            render: function (data, type, item) {
                let i = 0;
                let dataProtocol = `<div class="d-flex flex-column">`

                if (item['PROTOCOLS'].length > 0) {
                    for (const val of item['PROTOCOLS']) {
                        if ( i === 3 ) { break; }
                        i++

                        dataProtocol += `<a class="protocol-link" href="${val['FILES']}" >
                                            ${val['number']}
                                        </a>`
                    }
                }

                dataProtocol += `</div>`

                return dataProtocol
            },
        },
        {
            data: 'DEADLINE_TABLE',
            width: 100,
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return `<span class="${item.textColor}">${item.DEADLINE_TABLE}</span>`
                }

                return item.deadlineISO
            },
        }
    ];
    let theadCommHtml = `
    <thead>
    <tr class="table-light">
        <th scope="col"></th>
        <th scope="col" class="text-nowrap">Заявка</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Клиент</th>
        <th scope="col" class="text-nowrap">Объект испытаний</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
        <th scope="col" class="text-nowrap" style="min-width: 100px;">ТЗ</th>
        <th scope="col" class="text-nowrap">Договор</th>
        <th scope="col" class="text-nowrap">Акт ПП</th>
        <th scope="col" class="text-nowrap">Счет</th>
        <th scope="col" class="text-nowrap">Стоимость</th>
        <th scope="col" class="text-nowrap">Дата опл</th>
        <th scope="col" class="text-nowrap">Рез-ты исп</th>
        <th scope="col" class="text-nowrap">Протокол</th>
        <th scope="col" class="text-nowrap">Cрок до</th>
    </tr>
    <tr class="header-search">
        <th scope="col"></th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <select class="form-control search">
                <option value="">Все</option>
                <option value="n">Не подписано</option>
                <option value="y">Подписано</option>
            </select>
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search" disabled>
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
    </tr>
</thead>
    `
    let theadGovHtml = `
    <thead>
    <tr class="table-light">
        <th></th>
        <th scope="col" class="text-nowrap">Заявка</th>
        <th scope="col" class="text-nowrap">Дата</th>
        <th scope="col" class="text-nowrap">Тип заявки</th>
        <th scope="col">Объект испытаний</th>
        <th scope="col" class="text-nowrap">Ответственный</th>
        <th scope="col" class="text-nowrap" style="min-width: 100px;">ТЗ</th>
        <th scope="col" class="text-nowrap">Акт ПП</th>
        <th scope="col" class="text-nowrap">Организация</th>
        <th scope="col">Плановая дата выезда</th>
        <th scope="col" class="text-nowrap">Протокол</th>
        <th scope="col" class="text-nowrap">Рез-ты испытаний</th>
    </tr>
    <tr class="header-search">
        <th></th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control" disabled>
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <select class="form-control search">
                <option value="">Все</option>
                <option value="n">Не подписано</option>
                <option value="y">Подписано</option>
            </select>
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search" disabled>
        </th>
    </tr>
    </thead>
    `
    let columnsGovJournal = [
        {
            data: 'empty',
            orderable: false,
            render: function (data, type, item) {
                return ``
            }
        },
        {
            data: 'requestTitle',
            class: 'text-nowrap',
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return `<a class="request-link"
                               href="/ulab/request/card/${item.ID_Z}">
                               ${item['REQUEST_TITLE']}${item['certificate']}
                            </a>`
                }

                return item.request
            }
        },
        {
            data: 'DATE_CREATE_TIMESTAMP',
            width: 100,
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return item.dateCreateRu
                }
                return data;
            },
        },
        {
            data: 'type_text',
            orderable: false,
        },
        {
            data: 'object_gov',
            render: $.fn.dataTable.render.ellipsis(40, true)
        },
        {
            data: 'ASSIGNED',
            orderable: true,
            render: $.fn.dataTable.render.ellipsis(40, true)
        },
        {
            data: 'tz',
            width: '100px',
            render: function (data, type, item) {
                return `<a class="number-tz" href="/ulab/requirement/card_new/${item['b_tz_id']}" >
                                ${item['b_tz_id']}
                            </a>`
            },
        },
        {
            data: 'NUM_ACT_TABLE',
            render: function (data, type, item) {
                if (type === 'display' || type === 'filter') {
                    return `<a class="number-act" href="/ulab/probe/card/${item['ID_Z']}" >
                                ${item['NUM_ACT_TABLE']}
                            </a>`
                }

                return item.ACT_NUM
            },
        },
        {
            data: 'COMPANY_TITLE',
            width: 300,
        },
        {
            data: 'departure_date',
            render: function (data, type, item) {
                return item.departure_date?? ''
            },
        },
        {
            data: 'PROTOCOLS',
            orderable: false,
            render: function (data, type, item) {
                let i = 0;
                let dataProtocol = `<div class="d-flex flex-column">`

                if (item['PROTOCOLS']) {
                    for (const val of item['PROTOCOLS']) {
                        if ( i === 3 ) { break; }
                        i++

                        for (const file of val['FILES']) {
                            if (file.indexOf('.pdf') !== -1) {
                                dataProtocol += `<a class="protocol-link" 
                                                        href="/protocol_generator/archive/${item['b_id']}${val['YEAR']}/${val['ID']}/${file}"
                                                        >
                                                        ${val['NUMBER_AND_YEAR']}
                                                    </a>`
                            }
                        }

                        if (val['PROTOCOL_OUTSIDE_LIS'] && val['PDF']) {
                            dataProtocol += `<a class="protocol-link" href="/pdf/${val['ID']}/${val['PDF']}" 
                                                    >
                                                    ${val['NUMBER_AND_YEAR']}
                                                </a>`
                        }
                    }
                } else {
                    if(item['NO_BITRIX']) {
                        dataProtocol += `<a class="protocol-link" href="/pdf/${item['PDF']}" >
                                                ${item['PDF'] && item['NUM_P_TABLE'] ? item['NUM_P_TABLE'] : ''}
                                            </a>`
                    } else if (item['b_actual_ver']) {
                        dataProtocol += `<a class="protocol-link" href="/protocol_generator/archive/${item['b_id']}${item['YEAR_ACT']}/${item['b_actual_ver']}.docx?1&1" >
                                                ${item['RESULTS'] && item['NUM_P_TABLE'] ? item['NUM_P_TABLE'] : ''}
                                            </a>`
                    }
                }
                dataProtocol += `</div>`

                return dataProtocol
            },
        },
        {
            data: 'linkResult',
            className: 'text-center',
            orderable: false,
            render: function (data, type, item) {
                return `<a class="results-link" href="/ulab/result/card_oati/${item['ID_Z']}" >
                            ${item['linkName']}
                        </a>`
            },
        },
    ];

    let $journalTable = $('#journal_table')

    if ($('#notify_leader').length) {
        $.magnificPopup.open({
            items: {
                src: '#notify_leader'
            },
            closeOnBgClick: false,
            type: 'inline'
        })
    }

    /*journal filters*/
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload()
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    /*journal modal window*/
    if (window.location.pathname === '/ulab/request/list/') {

        $.ajax({
            url: '/ulab/request/getCheckTzAjax/',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                let content = `<table class="table table-striped table-modal"><tbody>`;

                data.forEach(function (item) {
                    //TODO: Изменить ссылку на крточку заявки
                    content  += `<tr class="align-middle text-nowrap">
                                    <td>
                                        <span title="${item.REQUEST_TITLE}">${item.cropRequestTitle}</span>
                                    </td>
                                    <td>
                                        <span title="${item.COMPANY_TITLE}">${item.cropCompanyTitle}</span>
                                    </td>
                                    <td class="${item.bgColor}">
                                        <span title="${item.status}">${item.cropStatus}</span>
                                    </td>
                                    <td>
                                        <!--a href="/request_card.php?ID=${item.ID_Z}" >
                                            <div class="card-icon" title="Карточка заявки"></div>
                                        </a-->
                                        <a href="/ulab/request/card/${item.ID_Z}" >
                                            <div class="card-icon" title="Карточка заявки"></div>
                                        </a>
                                    </td>
                                </tr>`;
                })

                content += `</tbody></table>`;

                let modal = $modal({
                    title: 'Техническое задание на рассмотрении',
                    content: content,
                    footerButtons: [
                        {
                            class: 'btn btn__ok',
                            text: 'ОК',
                            handler: 'modalHandlerOk'
                        }
                    ]
                });

                modal.show();
            }
        });
    }

    $('body').on('click', '.accept_probe', function () {
        let idRequest = $(this).data('id'),
            tr = $(this).parents('.probe_row'),
            a = $(this)

        $.ajax({
            url: '/ulab/probe/acceptProbeAjax/',
            method: 'POST',
            data: {
                id: idRequest
            },
            dataType: 'json',
            success: function (data) {
                tr.addClass('table-green')
                a.removeClass('accept_probe')
                a.addClass('remove_accept_probe')
                a.css('color' , 'crimson')
                a.text('Отменить')
            }
        })

    })

    $('body').on('click', '.accept_all', function () {
        let idRequest = $(this).data('id'),
            tr = $(this).parents('.probe_row'),
            a = $(this),
            child = $(`.probe_child_${idRequest}`),
            arrUmtrId = child.find('.accept_probe').map(function () {
            return this.dataset.id
            }).get()

        $.ajax({
            url: '/ulab/probe/acceptProbeAjax/',
            method: 'POST',
            data: {
                id: idRequest,
                arr: arrUmtrId
            },
            dataType: 'json',
            success: function (data) {

                child.find('.accept_probe').addClass('remove_accept_probe')
                child.find('.accept_probe').css('color' , 'crimson')
                child.find('.accept_probe').text('Отменить')
                child.toggleClass('table-green')
                child.find('.accept_probe').removeClass('accept_probe')
                tr.addClass('table-green')
                a.removeClass('accept_all')
                a.addClass('remove_accept_probe_all')
                a.css('color' , 'crimson')
                a.text('Отменить')
            }
        })

    })

    $('body').on('click', '.accept_probe_pay', function () {
        let idRequest = $(this).data('id'),
            tr = $(this).parents('.probe_row_pay'),
            a = $(this)

        $.ajax({
            url: '/ulab/probe/acceptProbeAjax/',
            method: 'POST',
            data: {
                id: idRequest
            },
            dataType: 'json',
            success: function (data) {
                tr.addClass('table-green')
                a.removeClass('accept_probe_pay')
                a.addClass('remove_accept_probe_pay')
                a.css('color' , 'crimson')
                a.text('Отменить')
            }
        })

    })

    $('body').on('click', '.remove_accept_probe', function () {
        let idRequest = $(this).data('id'),
            tr = $(this).parents('.probe_row'),
            a = $(this)

        $.ajax({
            url: '/ulab/probe/removeAcceptProbeAjax/',
            method: 'POST',
            data: {
                id: idRequest
            },
            dataType: 'json',
            success: function (data) {
                tr.removeClass('table-green')
                a.addClass('accept_probe')
                a.removeClass('remove_accept_probe')
                a.css('color' , '')
                a.text('Принять')
            }
        })

    })

    $('body').on('click', '.remove_accept_probe_all', function () {
        let idRequest = $(this).data('id'),
            tr = $(this).parents('.probe_row'),
            a = $(this),
            child = $(`.probe_child_${idRequest}`),
            arrUmtrId = child.find('.remove_accept_probe').map(function () {
                return this.dataset.id
            }).get()

        $.ajax({
            url: '/ulab/probe/removeAcceptProbeAjax/',
            method: 'POST',
            data: {
                id: idRequest,
                arr: arrUmtrId
            },
            dataType: 'json',
            success: function (data) {
                child.find('.remove_accept_probe').css('color' , '')
                child.find('.remove_accept_probe').text('Принять')
                child.find('.remove_accept_probe').addClass('accept_probe')
                child.toggleClass('table-green')
                child.find('.remove_accept_probe').removeClass('remove_accept_probe')
                a.addClass('accept_all')
                a.css('color' , '')
                a.text('Принять все')
                tr.removeClass('table-green')
                a.removeClass('remove_accept_probe_all')
            }
        })

    })

    $('body').on('click', '.remove_accept_probe_pay', function () {
        let idRequest = $(this).data('id'),
            tr = $(this).parents('.probe_row_pay'),
            a = $(this)

        $.ajax({
            url: '/ulab/probe/removeAcceptProbeAjax/',
            method: 'POST',
            data: {
                id: idRequest
            },
            dataType: 'json',
            success: function (data) {
                tr.removeClass('table-green')
                a.addClass('accept_probe_pay')
                a.removeClass('remove_accept_probe_pay')
                a.css('color' , '')
                a.text('Принять')
            }
        })

    })

    $('body').on('click', '.more-probe', function () {
        let reqId = $(this).data('request-id')
        $(`.probe_child_${reqId}`).toggleClass('d-none')
    })

    $radioTypeJournal.change(function () {
        let $radio = $(this)

        if ( $radio.is(':checked') ) {
            let v = $radio.val()

            journalDataTable?.destroy()
            $journalTable.find('.search').val('')
            $journalTable.find('thead').remove()

            let addr = new URL(window.location.href)

            if (v == 'comm') {
                $('.view-comm').show()
                $('.view-gov').hide()

                addr.searchParams.set('type_request', 'commercial')

                $journalTable.find('tbody').empty().before(theadCommHtml)

                journalDataTable = getJournalDataTable($journalTable, columnsCommJournal)
            } else {
                $('.view-comm').hide()
                $('.view-gov').show()

                addr.searchParams.delete('type_request')

                $journalTable.find('tbody').empty().before(theadGovHtml)

                journalDataTable = getJournalDataTable($journalTable, columnsGovJournal)
            }
               
            initTableScrollNavigation()

            window.history.replaceState({}, 'Журнал заявок', addr.href)

            container = $('body').find('div.dataTables_scrollBody')
            scroll = $journalTable.width()
        }
    })

    $radioTypeJournal.trigger('change')
})
