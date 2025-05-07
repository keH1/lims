$(function () {
    const $body = $('body')
    const $chartBarBlock = $("#chart-bar-block")

    let chartBar

    $('.select2').select2({
        theme: 'bootstrap-5',
    })

    let journalDataTable = null
    const $journal = $('#journal_all')
    const $trHeaderTitle = $journal.find('.header-title')
    const $trHeaderSearch = $journal.find('.header-search')

    let columns = []
    let chartData = []

    $('#select_entities').change(function () {
        if ( $.fn.DataTable.isDataTable('#journal_all') ) {
            $journal.DataTable().clear().destroy();
        }

        $journal.find('.header-title').empty()
        $journal.find('.header-search').empty()

        $('.chart-donut').empty()
        destroyChartBar()
        columns = []
    })


    $('#generate_journal').click(function () {
        const entity = $('#select_entities').val()

        if ( $.fn.DataTable.isDataTable('#journal_all') ) {
            $journal.DataTable().clear().destroy();
        }

        $journal.find('.header-title').empty()
        $journal.find('.header-search').empty()

        let defaultOrder = [ 0, 'asc' ]

        $.ajax({
            method: 'POST',
            url: "/ulab/statistic/getStatisticColumnAjax/",
            data: {
                entity: entity,
            },
            dataType: "json",
            success: function (data) {
                let index = 0
                $.each(data, function (key, val) {
                    $trHeaderTitle.append(`<th scope="col">${val.title}</th>`)

                    if ( val.filter === false ) {
                        $trHeaderSearch.append(
                            `<th scope="col"></th>`
                        )
                    } else {
                        $trHeaderSearch.append(
                            `<th scope="col">
                                <input type="text" class="form-control search">
                            </th>`
                        )
                    }

                    if ( val.default_order !== undefined ) {
                        defaultOrder = [ index, val.default_order ]
                    }

                    columns.push(
                        {
                            data: key,
                            orderable: val.order !== false,
                            render: function (data, type, item) {
                                if ( val.link !== undefined ) {
                                    let link = val.link
                                    let tmp = Array.from(val.link.matchAll(/.*?{([\w\d]+)}/g))
                                    tmp.map(function (m) {
                                        link = link.replace(`{${m[1]}}`, item[m[1]])
                                    })

                                    return link
                                } else {
                                    return item[key]
                                }
                            }
                        }
                    )

                    index++
                })

                /*journal requests*/
                journalDataTable = $journal.DataTable({
                    destroy : true,
                    retrieve: true,
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
                        type : 'POST',
                        data: function ( d ) {
                            d.dateStart = $('#inputDateStart').val() || "0001-01-01"
                            d.dateEnd = $('#inputDateEnd').val() || "9999-12-31"
                            d.entity = entity
                        },
                        url : '/ulab/statistic/getStatisticConstructorJournal/',
                        dataSrc: function (json) {
                            // $('.chart-donut').empty()
                            // chartData = []
                            //
                            // if (Object.keys(json['chart']).length &&
                            //     Object.keys(json['chart']['donut']).length) {
                            //     let donuts = json['chart']['donut']
                            //
                            //     $.each(donuts, function (k, donut) {
                            //         $.each(json.data, function (key, val) {
                            //             if ( val[donut['label']] === undefined || val[donut['label']] === null ) {
                            //                 val[donut['label']] = ''
                            //             }
                            //
                            //             if (donut['formatted'] === undefined ||
                            //                 val[donut['value']] == 0 ||
                            //                 val[donut['value']]== null) {
                            //                 return
                            //             }
                            //
                            //             let formatted = donut['formatted'].replace('{value}', val[donut['value']])
                            //
                            //             if (!chartData[k]) {
                            //                 chartData[k] = []
                            //             }
                            //
                            //             chartData[k].push(
                            //                 {
                            //                     value: val[donut['value']],
                            //                     label: val[donut['label']],
                            //                     formatted: formatted
                            //                 }
                            //             )
                            //         })
                            //     })
                            //
                            //     $.each(chartData, function (key, val) {
                            //         Morris.Donut({
                            //             element: `chart-donut-${key+1}`,
                            //             data: val,
                            //             backgroundColor: false,
                            //             colors: [
                            //                 '#4acacb', '#fe8676', '#6a8bc0', '#808080',
                            //                 '#ff8c00', '#ffd700', '#ba55d3', '#008000',
                            //                 '#ff69b4', '#4682b4', '#ff7f50', '#bdb76b',
                            //                 '#000080', '#800080', '#bc8f8f', '#d2691e',
                            //                 '#ff0000', '#adff2f', '#5ab6df', '#9400d3',
                            //                 '#2f4f4f', '#00ffff', '#708090', '#ffff00',
                            //                 '#ff0000',
                            //             ],
                            //             formatter: function (x, data) { return data.formatted }
                            //         }).select(0)
                            //     })
                            // }

                            return json.data
                        },
                    },
                    columns: columns,
                    language: dataTablesSettings.language,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
                    pageLength: 25,
                    order: [defaultOrder],
                    dom: 'frt<"bottom"lip>',
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
                });
            }
        })
    })

    $body.on('change', '.filter', function () {
        if ( $.fn.DataTable.isDataTable('#journal_all') ) {
            journalDataTable.ajax.reload()
        }
        destroyChartBar()
    })

    function reportWindowSize() {
        if ( $.fn.DataTable.isDataTable('#journal_all') ) {
            journalDataTable.columns.adjust()
        }
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    /**
     * Отобразить график bar
     */
    $body.on('click', '.chart_link', function (e) {
        e.preventDefault()

        const ctx = $('#chart-bar')

        let entity = $(this).data('entity'),
            id = $(this).data('id')

        destroyChartBar()

        $.ajax({
            method: 'POST',
            url: '/ulab/statistic/getStatisticEntityAjax',
            data: {
                entity: entity,
                id: id,
            },
            dataType: 'json',
            success: function (data) {
                $chartBarBlock.removeClass('d-none')

                $([document.documentElement, document.body]).animate({
                    scrollTop: $chartBarBlock.offset().top - 20
                }, 500)

                if (Object.keys(data).length) {
                    const colors = ['54, 162, 235', '255, 99, 132']
                    let formattedY = data['formatted'].length > 1 ? '' : data['formatted']

                    let datasets = []
                    for (const i in data['value']) {
                        datasets.push({
                            label: data['formatted'][i],
                            data: data['value'][i],
                            borderColor: `rgb(${colors[i]})`,
                            backgroundColor: `rgba(${colors[i]}, 0.2)`,
                            borderWidth: 1
                        })
                    }

                    chartBar = new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
                            datasets: datasets
                        },
                        options: {
                            plugins: {
                                legend: {
                                    display: true,
                                    onClick : function(e, legendItem) {
                                        let index = legendItem.datasetIndex
                                        let ci = this.chart

                                        ci.data.datasets.forEach(function(e, i) {
                                            var meta = ci.getDatasetMeta(i)

                                            if (i !== index) {
                                                meta.hidden = true
                                            } else if (i === index) {
                                                meta.hidden = null
                                            }
                                        });

                                        ci.update()
                                    }
                                },
                                title: {
                                    display: true,
                                    text: data['label'],
                                    font: {
                                        size: 25
                                    },
                                },
                            },
                            scales: {
                                y: {
                                    title: {
                                        display: true,
                                        text: [formattedY],
                                        font: {
                                            size: 18
                                        }
                                    },
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: ['За текущий год'],
                                        font: {
                                            size: 18
                                        }
                                    },
                                }
                            },
                        },
                    });

                    if (!formattedY) {
                        chartBar.getDatasetMeta(1).hidden=true
                    }

                    // Функция для добавления или удаления горизонтальной линии в зависимости от значения ID
                    function updateHorizontalLine(toCheck) {
                        let annotationPlugin = chartBar.options.plugins.annotation

                        // Удаление всех текущих аннотаций
                        annotationPlugin.annotations = []

                        // Проверка условия по ID и добавление аннотации, если условие выполняется
                        if (data.label === toCheck) {
                            //let maxY = Math.max.apply(Math,data['value'][0])

                            annotationPlugin.annotations.push({
                                type: 'line',
                                mode: 'horizontal',
                                scaleID: 'y',
                                value: 210, // Значение y, на котором будет нарисована линия
                                borderColor: 'red', // Цвет линии
                                borderWidth: 2, // Ширина линии
                                label: {
                                    content: 'Предельная линия',
                                    enabled: true,
                                    position: 'right',
                                },
                                drawTime: 'beforeDatasetsDraw',
                            });
                        }

                        // Обновление графика
                        chartBar.update()
                    }
                    updateHorizontalLine("Спектрофотометр  № 17.1-25")
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

    /**
     * Очистить график bar
     */
    function destroyChartBar() {
        if (chartBar) {
            chartData = []
            $chartBarBlock.addClass('d-none')
            chartBar.destroy();
        }
    }
})