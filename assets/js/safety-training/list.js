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
                d.dateStart = $('#inputDateStart').val() || "0001-01-01";
                d.dateEnd = $('#inputDateEnd').val() || "9999-12-31";
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
                    return meta.row + meta.settings._iDisplayStart + 1;
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
        buttons: dataTablesSettings.buttonPrint,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
        pageLength: 25,
    });

    journalDataTable.columns().every(function () {
        let timeout
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('input', function () {
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

    /**
     * фильтры журнала
     */
    $('.filter').on('change', function () {
        journalDataTable.ajax.reload();
    })

    function reportWindowSize() {
        journalDataTable
            .columns.adjust()
    }

    window.onresize = reportWindowSize

    $('.filter-btn-reset').on('click', function () {
        location.assign(location.pathname);
    });
});
