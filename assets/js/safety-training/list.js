$(function ($) {
    const $body = $('body'),
        $journal = $('#safetyTrainingLog')

    $body.on('click', '.add-training', function () {
        const safetyTrainingModalForm = $('#safetyTrainingModalForm');

        $.magnificPopup.open({
            items: {
                src: safetyTrainingModalForm,
                type: 'inline',
                fixedContentPos: false
            },
            closeOnBgClick: false,
            callbacks: {
                beforeOpen: function() {
                    safetyTrainingModalForm[0].reset();

                    const currentDate = new Date().toISOString().split('T')[0];
                    $('#trainingDate').val(currentDate);

                    this.st.focus = '.select2';
                }
            },
        });
    });

    let journalDataTable = $journal.DataTable({
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
            },
            url: '/ulab/safetyTraining/getSafetyTrainingLogAjax/',
            dataSrc: function (json) {
                return json.data;
            }
        },
        columns: [
            {
                data: 'number',
                orderable: false,
                className: 'no-sort',
                render: function (data, type, item, meta) {
                    // Порядковый номер
                    let displayIndex = meta.row + meta.settings._iDisplayStart + 1;

                    return displayIndex;
                }
            },
            {
                data: 'fio',
            },
            {
                data: 'training_type',
            },
            {
                data: 'training_date',
                render: function (data, type, item) {
                    return item.ru_training_date;
                }
            },
        ],
        order: [[ 3, "desc" ]],
        language: dataTablesSettings.language,
        dom: 'frtB<"bottom"lip>',
        buttons: dataTablesSettings.buttons,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
    });

    journalDataTable.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
            journalDataTable
                .column($(this).parent().index())
                .search(this.value)
                .draw();
        })
    });

    /**
     * фильтры журнала
     */
    $('.filter').on('change', function () {
        journalDataTable.ajax.reload();
        journalDataTable.draw();
    })

    $('.filter-btn-reset').on('click', function () {
        location.assign(location.pathname);
    });
});
