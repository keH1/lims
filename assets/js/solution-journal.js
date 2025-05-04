$(function ($) {
    var reactive = [];
    var solution = [];
    /*solution journal*/
    let solutionJournal = $('#solution_journal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            data: function (d) {
                d.idWhichFilter = -1
                d.dateStart = $('#inputDateStart').val() || "0001-01-01"
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31"
            },
            url: '/ulab/solution/getListProcessingAjax/',
            dataSrc: function (json) {
                console.log(json.data)
                return json.data
            },
        },
        createdRow: function (row, data, dataIndex) {
            $(row).attr('data-recipe-id', `${data.id}`);
        },
        columns: [
            {
                data: 'name_recipe',
                /*render: function (data, type, item) {
                    // return `<a href="/ulab/recipe/list?param=1"
                    return `<a href="/ulab/recipe/list/${item['name_recipe']}"
                                >${item['name_recipe']}
                            </a>`
                }*/
            },
            {
                data: 'gost'
            },
            {
                data: 'quantity_full'
            },
            {
                data: 'date_receive_dateformat'
            },
            {
                data: 'date_expiry_dateformat',
                render: function (data, type, item) {
                    if (item.is_expired == 0) {
                        return data
                    } else return `<div class="alert alert-danger" title="Срок годности иcтек">` + data + `</div>`
                }
            },
            {
                data: 'names'
            },
            {
                data: 'global_assigned_name'
            }
        ],
        'columnDefs': [{
            'targets': [],
            'orderable': false,
        }],
        language: dataTablesSettings.language,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
        order: [],
        colReorder: true,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttonPrint,
        bSortCellsTop: false,
        scrollX: true,
        fixedHeader: false,
    })

    solutionJournal.columns().every(function() {
        let timeout
        $(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function() {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function() {
                solutionJournal
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    })


    $('.select-recipe').change(function () {
        // reactiveJournal.ajax.reload()
        // solventJournal.ajax.reload()
        $('#reactive_journal').fadeIn();
        $('#reactive_journal tbody tr').remove();
        $('#solvent_journal').fadeIn();

        $('#solvent_journal tbody tr').remove();
        const name = $('option:selected', this).data('name')
        $('[name="toSQL[name]"]').val(name)
        idRecipe = $('.select-recipe option:selected').val();


        $.ajax({
            type: "POST",
            dataType: 'json',
            url: '/ulab/solution/getListReactivesNew/',
            data: {idRecipe: idRecipe},
            success: function (result) {
                reactive = result.reactives;
                solution = result.solution;

                result.solution.forEach(item => {
                    console.log(item)
                    let solutionName = Object.keys(item)[0];
                    let solutionDataArray = item[solutionName];
                    console.log(solutionDataArray)
                    const createRow = (solutionData, solutionName) => {
                        let content = "";
                        content += `
                            <tr class="solution_dropdown" data-checkbox-check="${solutionName}">
                                <td>
                                <input type="checkbox" name="solution[solution_choose]" id="solution"></td>
                                <input type="hidden" name="toSQL[solution[${solutionData.id_library_reactive}][id_receive]]" value="${solutionData.id_receive}">
                                <input type="hidden" name="solution[date_expired_dateformat]" value="${solutionData.date_expired_dateformat}">
                                <input type="hidden" name="toSQL[solution[${solutionData.id_library_reactive}][id_library_reactive]]" value="${solutionData.id_library_reactive}">
                                <input type="hidden" name="solution[name]" value="${solutionData.name}">
                                <input type="hidden" name="solution[quantity_consume]" value="${solutionData.quantity_consume}">
                                <input type="hidden" name="solution[quantity_consume_full]" value="${solutionData.quantity_consume_full}">
                                <input type="hidden" name="solution[quantity_full]" value="${solutionData.quantity_full}">
                                <input type="hidden" name="solution[total_full]" value="${solutionData.total_full}">
                                </td>
                                <td>${solutionData.name}</td>
                                <td>${solutionData.quantity_full}</td>
                                <td>${solutionData.quantity_consume_full}</td>
                                <td>${solutionData.total_full}</td>
                                <td>${solutionData.date_expired_dateformat}</td>
                            </tr>`
                        return content
                    };
                    const emptyRow = (key) => {
                        return `
                            <tr data-tr-id="${key}">
                                <td></td>
                                <td>${key}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>`;
                    };

                    if (solutionDataArray.length > 0) {
                        solutionDataArray.forEach(solutionData => {
                            $('#solvent_journal tbody').append(createRow(solutionData, solutionName));
                        });
                    } else {
                        $('#solvent_journal tbody').append(emptyRow(solutionName));
                    }
                });

                result.reactives.forEach(item => {
                    let reactiveName = Object.keys(item)[0];
                    let reactiveDataArray = item[reactiveName];

                    let flag = 0;
                    if (reactiveDataArray.length > 0) {
                    reactiveDataArray.forEach((reactiveData, i) => {
                        console.log(reactiveData,i)
                        let content = createReactive(reactiveData, reactiveName, flag, i);
                        $('#reactive_journal tbody').append(content);
                        flag = 1;
                    });
                    } else {
                        flag = 1;
                        $('#reactive_journal tbody').append(createRow(defaultReactiveData, reactiveName, flag, i));
                    }


                });

            },
            error: function (error) {
                console.log(error);
            }
        })

    })
    $('.send_form').on('click', function () {

        $('#add-entry-modal-form').find('input[type="checkbox"]:not(:checked)').closest('tr').find('input').prop('disabled', true);

        //     let selectedData = selectedRadio.closest('tr');
        //     let form_data = selectedData.find('input').serializeArray();
        //     let date = $("[name*='toSQL[date_preparation]']").val();
        //     let id_recipe_model = $("[name*='toSQL[id_recipe_model]']").val();
        //     let recipe_name = $("[name*='toSQL[name]']").val();
        //
        //     var result = {
        //         name: recipe_name,
        //         date_preparation: date,
        //         id_recipe_model: id_recipe_model,
        //         reactives: [],
        //         solution: [],
        //     }
        //     var currentReactive = null;
        //     var currentSolution = null;
        //
        //     for (var i = 0; i < form_data.length; i++) {
        //         var name = form_data[i].name;
        //         var value = form_data[i].value;
        //
        //         if (name === 'reactive[reactive_choose]' || name === 'solution[solution_choose]' && value === 'on') {
        //             if (currentReactive) {
        //                 result.reactives.push(currentReactive);
        //             }
        //             if (currentSolution) {
        //                 result.solution.push(currentSolution);
        //             }
        //             currentReactive = null;
        //             currentSolution = null;
        //         } else if (name === 'reactive[id_library_reactive]') {
        //             currentReactive = currentReactive || {id_library_reactive: '', id_receive: ''};
        //             currentReactive.id_library_reactive = value;
        //         } else if (name === 'reactive[id_receive]') {
        //             currentReactive = currentReactive || {id_library_reactive: '', id_receive: ''};
        //             currentReactive.id_receive = value;
        //         } else if (name === 'solution[id_receive]') {
        //             currentSolution = currentSolution || {id_library_reactive: '', id_receive: ''};
        //             currentSolution.id_receive = value;
        //         } else if (name === 'solution[id_library_reactive]') {
        //             currentSolution = currentSolution || {id_library_reactive: '', id_receive: ''};
        //             currentSolution.id_library_reactive = value;
        //         }
        //     }
        //     if (currentReactive) {
        //         result.reactives.push(currentReactive);
        //     }
        //     if (currentSolution) {
        //         result.solution.push(currentSolution);
        //     }
        //
        //     $.ajax({
        //         type: "POST",
        //         dataType: 'json',
        //         url: '/ulab/solution/addSolutionAndConsume/',
        //         data: {toSQL: result},
        //         success: function (response) {
        //             console.log(response);
        //             // window.location.reload();
        //         },
        //         error: function (error) {
        //             console.log(error);
        //             // window.location.reload();
        //         }
        //     });
    });

    function createReactive(reactiveData, key, flag, i) {
        console.log(i);
        let content = "";
        if (flag == 0) {
            content += `
            <tr class="main-reactive" data-tr-id="${key}">
            <td></td>
            <td>${key}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>`;
        }
        content += `
        <tr class="reactive_dropdown" data-checkbox-check="${key}" id="${key}">
        <td>
            <input type="checkbox" name="reactive[reactive_choose]" id="reactive">
            <input type="hidden" name="toSQL[reactive[${reactiveData.id_library_reactive}][id_receive]]" value="${reactiveData.id_receive}">
            <input type="hidden" name="reactive[date_expired_dateformat]" value="${reactiveData.date_expired_dateformat}">
            <input type="hidden" name="toSQL[reactive[${reactiveData.id_library_reactive}][id_library_reactive]]" value="${reactiveData.id_library_reactive}">
            <input type="hidden" name="" value="${reactiveData.name}">
            <input type="hidden" name="" value="${reactiveData.quantity_consume}">
            <input type="hidden" name="reactive[quantity_consume_full]" value="${reactiveData.quantity_consume_full}">
            <input type="hidden" name="reactive[quantity_full]" value="${reactiveData.quantity_full}">
            <input type="hidden" name="reactive[total_full]" value="${reactiveData.total_full}">
        </td>
        <td>${reactiveData.name}</td>
        <td>${reactiveData.quantity_full}</td>
        <td>${reactiveData.quantity_consume_full}</td>
        <td>${reactiveData.total_full}</td>
        <td>${reactiveData.date_expired_dateformat}</td>  
        </tr>`;
        return content;
    }

    /*journal buttons*/
    let container = $('div.dataTables_scrollBody'),
        scroll = $('#solution_journal').width()

    let $body = $("body")
    let $containerScroll = $body.find('.dataTables_scroll')
    let $thead = $('.journal thead tr:first-child')

    $('#solution_journal').on("click", "tbody tr", function () {
        let table = $('#solution_journal').DataTable()
        let clickOn = table.columns(this).data()

    })

    $('body').on('click', '.popup-with-form', function () {
        $.magnificPopup.open({
            items: {
                src: '#add-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            closeOnBgClick: false,
        })
    })

    $('.select-recipe').select2({
        theme: 'bootstrap-5',
        placeholder: $(this).data('placeholder'),
    })


    /** journal filters */
    $('.filter-btn-search').on('click', function () {
        $('#journal_requests_filter').addClass('is-open')
        $('.filter-btn-search').hide()
    })

    $('.filter').on('change', function () {
        solutionJournal.ajax.reload()
    })

    function reportWindowSize() {
        solutionJournal
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    $('body').on('change', 'input#reactive', function () {
        let name = $(this).closest('tr').data('checkbox-check')
        if ($(this).prop('checked')) {
            $(`tr[data-checkbox-check="${name}"] td:first-child input[type="checkbox"]:not(:checked)`).prop('disabled', true);
        } else {
            $(`tr[data-checkbox-check="${name}"] td:first-child input[type="checkbox"]:not(:checked)`).prop('disabled', false);
        }
    })
    $('body').on('change', 'input#solution', function () {
        let name = $(this).closest('tr').data('checkbox-check')
        if ($(this).prop('checked')) {
            $(`tr[data-checkbox-check="${name}"] td:first-child input[type="checkbox"]:not(:checked)`).prop('disabled', true);
        } else {
            $(`tr[data-checkbox-check="${name}"] td:first-child input[type="checkbox"]:not(:checked)`).prop('disabled', false);
        }
    })


})
