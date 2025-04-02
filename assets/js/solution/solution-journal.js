
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
                d.dateStart = $('#inputDateStart').val()
                d.dateEnd = $('#inputDateEnd').val()
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

    solutionJournal.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            solutionJournal
                .column($(this).parent().index())
                .search(this.value)
                .draw()
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
                console.log(result)

                reactive = result.reactives;
                solution = result.solution;
                let helper = new SolutionHelper(reactive,solution);

                prepareBlock();
                $('#reactive_journal tbody').append(helper.createBlockReactive());
                $('#solvent_journal tbody').append(helper.createBlockSolution());


                if ($('tr').hasClass('disabled_save_button')) {
                    $('.send_form').prop('disabled', true)
                } else {
                    $('.send_form').prop('disabled', false)
                }

                $('.send_form').prop('disabled', true)

            },
            error: function (error) {
                console.log(error);
            }
        })

    })
    $('.send_form').on('click', function () {

        $('#add-entry-modal-form').find('input[type="checkbox"]:not(:checked)').closest('tr').find('input').prop('disabled', true);
        if ($('[data-checkbox-check="Лаб. реактив Дистиллированная вода ГОСТ Р 58144-2018"]')) {
            $('[data-checkbox-check="Лаб. реактив Дистиллированная вода ГОСТ Р 58144-2018"]').prop('disabled', false);
        }
        if ($('[data-checkbox-check="Лаб. реактив Бидистиллированная вода"]')) {
            $('[data-checkbox-check="Лаб. реактив Бидистиллированная вода"]').prop('disabled', false);
        }


    });

    //

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

    /** modal */
    $('.first-modal').magnificPopup({
        items: {
            src: '#add-entry-modal-form',
            type: 'inline'
        },
        fixedContentPos: false
    })

    $('.select-recipe').select2({
        placeholder: 'Выберите рецепт',
        width: '100%'
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
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.reload()
    })

    $('body').on('change', 'input#reactive, input#solvent', function () {
        let name = $(this).closest('tr').data('name');
        let isChecked = $(this).prop('checked');
        $(`tr[data-name="${name}"] td:first-child input[type="checkbox"]:not(:checked)`).prop('disabled', isChecked);
        toggleSaveButtonButton();
    });



    function prepareBlock(){
        $('#reactive_block, #solvent_block').css('display', 'inline-flex');
        $('#reactive_header').text('Реактивы')
        $('#solvent_header').text('Растворители')
    }

    function toggleSaveButtonButton(){
        const reactiveInputs = $('input#reactive');
        const solutionInputs = $('input#solvent');
        const sendFormButton = $('.send_form');

        const isReactiveChecked = reactiveInputs.is(':checked');
        const isSolutionChecked = solutionInputs.is(':checked');



        // Enable or disable the send form button based on the checked inputs
        if (isReactiveChecked && isSolutionChecked) {
            sendFormButton.prop('disabled', false);
        } else {
            sendFormButton.prop('disabled', true);
        }
    }


})
