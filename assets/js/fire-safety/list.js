$(function ($) {
    const body = $('body'),
        journal = $('#fireSafetyLog')

    body.on('click', '.add-instruction', function () {
        const fireSafetyModalForm = $('#fireSafetyModalForm');

        $.magnificPopup.open({
            items: {
                src: fireSafetyModalForm,
                type: 'inline',
                fixedContentPos: false
            },
            closeOnBgClick: false,
            callbacks: {
                open: function() {
                    $('#instructedName').select2({
                        theme: 'bootstrap-5',
                        width: 'resolve',
                    });
                },
                beforeOpen: function() {
                    fireSafetyModalForm[0].reset();

                    const currentDate = new Date().toISOString().split('T')[0];
                    $('#theoryDate').val(currentDate);
                    $('#practiceDate').val(currentDate);

                    this.st.focus = '.select2';
                }
            },
        });
    });
   
    let sortMode = 'maxDate'
    let journalDataTable = journal.DataTable({
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
                d.dateStart = $('#inputDateStart').val();
                d.dateEnd = $('#inputDateEnd').val();
                
                if (sortMode === 'maxDate') {
                    d.sortByMaxDate = 1
                } else {
                    d.sortByMaxDate = 0
                }
            },
            url: '/ulab/fireSafety/getFireSafetyLogAjax/',
            dataSrc: function (json) {
                return json.data;
            }
        },
        columns: [
            {
                data: 'theory_date',
                render: function (data, type, item) {
                    return item.ru_theory_date;
                }
            },
            {
                data: 'instruction_type',
            },
            {
                data: 'instructed_name',
            },
            {
                data: 'instructed_position',
            },
            {
                data: 'theory_instructor_fio_doc',
                orderable: false,
            },
            {
                data: 'practice_date',
                render: function (data, type, item) {
                    return item.ru_practice_date;
                }
            },
            {
                data: 'practice_instructor_name_doc',
                orderable: false,
            },
        ],
        order: [[ 0, "desc" ]],
        language: dataTablesSettings.language,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
    });

    journal.on('order.dt', function() {
        if (sortMode === 'maxDate') {
            sortMode = 'normal'
        }
    })

    journalDataTable.columns().every(function () {
        let timeout
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            clearTimeout(timeout)
            const searchValue = this.value
            timeout = setTimeout(function () {
                journalDataTable
                    .column($(this).parent().index())
                    .search(searchValue)
                    .draw()
            }.bind(this), 1000)
        })
    });

    $('.filter').on('change', function () {
        journalDataTable.ajax.reload();
        journalDataTable.draw();
    })

    $('.filter-btn-reset').on('click', function () {
        location.assign(location.pathname);
    });
});
