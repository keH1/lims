$(document).ready(function () {
    searchParams = new URLSearchParams(window.location.search)
    console.log($("#scheme_id").val())
    let journalRequests = $('#table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        colReorder: true,
        //  order: [[0, "desc"]],
        order: false,
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 50,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        scrollX: true,
        // searching: false,
        fixedHeader: false,
        ajax: {
            type : 'POST',
            dataType: 'json',
            url: `/ulab/LabScheme/getSchemeCardDataAjax/`,
            data: function (d) {
             //   d.token = TOKEN,
                d.scheme_id = $("#scheme_id").val()
            },
            dataSrc: function (json) {
                console.log(json)
                return json.data
            },
        },
        createdRow: function (row, data, dataIndex) {
            $(row).find('td').css('text-align', 'center')
            //
            // if (searchParams.get('view') != "products") {
            //     $(row).find('td:eq(3)').css("display", "none")
            // }

        },
        columnDefs: [ {
            'targets': "_all", /* table column index */
            'orderable': false, /* true or false */
        }],
        columns: [
            {
                data: 'title',
                defaultContent: "",
                render: function (data, type, item) {

                    let title = data;

                    if (item.item !== "") {
                        title += ` (${item.item})`
                    }

                    return title;
                }
            },
            {
                data: 'spec',
                defaultContent: ""
            },
            {
                data: 'param',
                defaultContent: ""
            },
            {
                data: 'range_from',
                defaultContent: ""
            },
            {
                data: 'range_before',
                defaultContent: ""
            },
            {
                data: 'laboratory_status',
                defaultContent: "",
                render: function (data, type, item) {
                    return data == 1 ? "Да" : "Нет";
                }
            },
            {
                data: '',
                render: function (data, type, item) {
                    return `<div class="btn-group">
                                <button 
                                    data-js-update="${item.scheme_gost_id}" 
                                    data-js-from="${item.range_from}" 
                                    data-js-before="${item.range_before}"
                                    data-js-status="${item.laboratory_status}"
                                    data-js-title="${item.title}" 
                                    data-js-param="${item.param}" 
                                    class="btn"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button 
                                    data-js-delete="${item.scheme_gost_id}" 
                                    class="btn"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
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

    });

    journalRequests.columns().every(function () {
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('input', function () {
            journalRequests
                .column($(this).parent().index())
                .search(this.value)
                .draw();
        })
    });
    //
    // $('[data-js-filter]').click(function (e) {
    //     $("[data-js-search-elem]").val($(this).attr("data-js-filter"));
    //     journalRequests.ajax.reload();
    // });
    //
    // $("[data-js-switcher]").click(function (e) {
    //     console.log("===")
    //     console.log($(this).val())
    //     // journalRequests.ajax.reload();
    // });
    // console.log("test!!")
    $("body").on("click", "[data-js-update]", function (e) {

        $("#scheme_gost_id").val($(this).attr("data-js-update"))

        $("#range_from").val($(this).attr("data-js-from"))
        $("#range_before").val($(this).attr("data-js-before"))
        $("#laboratory_status").val($(this).attr("data-js-status"))
        $("#param").val($(this).attr("data-js-param"))
        $("#gost").val("").trigger('change')


        if ($(this).attr("data-js-update")) {
            $("#gost").parent().parent().hide()
            $("#add-entry-modal-form [data-js-title]").text("Изменить гост")
        } else {
            $("#gost").parent().parent().show()
            $("#add-entry-modal-form [data-js-title]").text("Добавить гост")
            $("#laboratory_status").val(0)
        }

        $.magnificPopup.open({

            items: {
                src: '#add-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            closeOnBgClick: false,
            modal: true
        })

    })

    $("body").on("click", "[data-js-delete]", function (e) {
        $("#scheme_gost_id").val($(this).attr("data-js-delete"))

        $.magnificPopup.open({

            items: {
                src: '#del-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            closeOnBgClick: false,
            modal: true
        })
    })

    // $("body").on("click", "[data-js-add-gost]", function (e) {
    //     $("#material_id").val($(this).attr("data-js-material-id"))
    //     $("#scheme_name").val($(this).attr("data-js-scheme-name"))
    //
    //     $.magnificPopup.open({
    //
    //         items: {
    //             src: '#add-scheme-modal-form',
    //             type: 'inline'
    //         },
    //         fixedContentPos: false,
    //         modal: true
    //     })
    //
    // })

    $("body").on("click", "[data-js-del-scheme]", function (e) {


        //  $("#scheme_id").val($(this).attr("data-js-scheme-id"))

        $.magnificPopup.open({

            items: {
                src: '#del-scheme-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            closeOnBgClick: false,
            modal: true
        })

    })

    $("body").on("click", "#add-entry-modal-btn", function (e) {
        //deleteMaterialAjax
        $.ajax({
            url: `/ulab/LabScheme/addGostToSchemeAjax/`,
            //  url: `https://ulab.niistrom.pro/api/request/simpleTest/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "scheme_id": $("#scheme_id").val(),
                "gost_id": $("#gost").val(),
                "range_from": $("#range_from").val(),
                "range_before": $("#range_before").val(),
                "laboratory_status": $("#laboratory_status").val(),
                "scheme_gost_id": $("#scheme_gost_id").val(),
                "param": $("#param").val(),
            },
            success: function (result) {
                console.log("===")
                console.log(this.data)
                console.log(result);
                $.magnificPopup.close();
                journalRequests.ajax.reload();
                // window.open(
                //     `${URI}/laboratory/schemeCard/${result.id}/`,
                //     '_self' // <- This is what makes it open in a new window.
                // );
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("body").on("click", "#del-scheme-modal-btn", function (e) {
        //deleteMaterialAjax
        $.ajax({
            url: `/ulab/LabScheme/deleteSchemeAjax/`,
          //  url: `https://ulab.niistrom.pro/api/scheme/deleteSchemeAjax/`,
            //  url: `https://ulab.niistrom.pro/api/request/simpleTest/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
             //   "token": TOKEN,
                "scheme_id": $("#scheme_id").val()
            },
            success: function (result) {
                console.log(result);
                $.magnificPopup.close();
                journalRequests.ajax.reload();

                document.location.href = `/ulab/LabScheme/editor?type=1`;
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("body").on("click", "#del-entry-modal-btn", function (e) {
        //deleteMaterialAjax
        $.ajax({
          //  url: `https://ulab.niistrom.pro/api/scheme/deleteGostFromSchemeAjax/`,
            url: `/ulab/LabScheme/deleteGostFromSchemeAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "scheme_gost_id": $("#scheme_gost_id").val()
            },
            success: function (result) {
                console.log(result);
                $.magnificPopup.close();
                journalRequests.ajax.reload();
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })


    $("#search-btn").click(function (e) {
        journalRequests.ajax.reload();
    })

    // $("#add-entry-modal-btn").click(function (e) {
    //
    //     let materialArr = [];
    //
    //     console.log("addMat")
    //
    //     // $("#material").val().forEach(function (id) {
    //     //  [...$("#material option:selected")].forEach(function (item) {
    //     //      materialArr.push({
    //     //          material_id: $(item).val(),
    //     //          name: $(item).text()
    //     //      })
    //     //  })
    //
    //     let check = true;
    //
    //     // $("[data-js]").each(function (index) {
    //     //     if (!$(this).val()) {
    //     //         $(this).css("background-color", "#F08080")
    //     //         $(this).parent().find(".select2-selection").css("background", "#F08080")
    //     //         check = false;
    //     //     } else {
    //     //         $(this).css("background-color", "#FFF")
    //     //         $(this).parent().find(".select2-selection").css("background", "#FFF")
    //     //
    //     //     }
    //     // })
    //
    //     if (check) {
    //         console.log("test")
    //         $.ajax({
    //             url: `https://ulab.niistrom.pro/api/scheme/addMaterialAjax/`,
    //             //  url: `https://ulab.niistrom.pro/api/request/simpleTest/`,
    //             type: "POST", //метод отправки
    //             dataType: 'json', // data type
    //             data: {
    //                 "material_name": $("#material_name").val(),
    //                 "material_id": $("#material_id").val()
    //             },
    //             success: function (result) {
    //                 console.log(result);
    //                 $.magnificPopup.close();
    //                 journalRequests.ajax.reload();
    //             },
    //             error: function (xhr, resp, text) {
    //                 console.log(xhr, resp, text);
    //             }
    //         });
    //     }
    //
    //
    // })

    $("#save-scheme").click(function (e) {
        $.ajax({
            //  url: `https://ulab.niistrom.pro/api/request/insertAjax/`,
            url: `https://ulab.niistrom.pro/api/request/addSchemeAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "material_id": $("#material").val(),
                "gost": $("#gost").val(),
                "scheme_name": $("#scheme_name").val(),
            },
            success: function (result) {
                console.log(result);
                $("#scheme").append(`
                    <option value="${result.id}">${result.name}</option>
                `)
                $("#scheme").val(result.id)
                // $("[data-js-form-scheme]").hide(500)
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("#material").change(function (e) {
        console.log($(this).val())
        $.ajax({
            //  url: `https://ulab.niistrom.pro/api/request/insertAjax/`,
            url: `https://ulab.niistrom.pro/api/scheme/getListAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "material_id": $(this).val()
            },
            success: function (result) {
                console.log(result);
                let optionList = "";
                let methodsList = "";

                let methods_material = $(`span[id=${result.id}]`)

                result.forEach(function (scheme) {
                    optionList += `<option value="${scheme.sub_param_id}">${scheme.scheme_name}</option>`
                })
                $("#scheme").html(optionList);

                result.forEach(function (scheme){
                    if(scheme.methods != null && scheme.gost != null && $('#scheme').val() == scheme.sub_param_id)
                        methodsList += `<div id="${scheme.id}" style="margin: 5px 5px 5px 0;"><span class="" style="border: 1px solid #828282;border-radius: 4px;padding: 2px;" data-js-methods>${scheme.methods}||${scheme.gost} ${scheme.gost_punkt}   
                        </div>`;
                })
                console.log($('#scheme').val())
                $("#methods_scheme_list").html(methodsList);
                // let last = $('[data-js-methods]').last();
                // $(`<button type="button" style="border: none; background: transparent;" id="add_gost_in_scheme"><span class="fa-solid fa-plus"></span></button>`).insertAfter(last);

                // $("#scheme").append(`
                //     <option value="${result.id}">${result.name}</option>
                // `)
                // $("#scheme").val(result.id)
                // $("[data-js-form-scheme]").hide(500)
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })
    // $(document).on('click touchstart','#add_gost_in_scheme',function (e){
    //     $('add_methods_scheme').css('display', 'block');
    // })
    $('#scheme').change(function (e){
        $.ajax({
            //  url: `https://ulab.niistrom.pro/api/request/insertAjax/`,
            url: `https://ulab.niistrom.pro/api/scheme/getListSchemeAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "scheme_id": $(this).val(),
                "material_id": $("#material").val()
            },
            success: function (result) {
                console.log(result);
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })
    $("#material").select2({
        dropdownParent: $('#add-entry-modal-form'),
    })

    $("#gost").select2({
        dropdownParent: $('#add-entry-modal-form'),
    })
    $("#gost_scheme_list").select2({
        dropdownParent: $('#add-entry-modal-form'),
    })

    $("#material-select").select2({
        dropdownParent: $('#copy-form'),
    })


    $("[data-js-close-modal]").click(function (e) {
        $.magnificPopup.close();
    })

    $("[data-js-add-scheme]").click(function (e) {
        $().append(`
            <div class="row mb-3" style="z-index: 1500">
                <div class="col" style="width: 400px">
                    <label for="scheme" class="form-label">Название схемы</label>
                    <input type="text" class="form-control" name="scheme" id="scheme" aria-describedby="scheme">
                </div>
            </div>
        
            <div class="row mb-3" style="z-index: 1500">
                <div class="col" style="width: 400px">
                    <label for="user">Выбрать материал<span class="redStars">*</span></label>
                    <select
                        name="scheme[0][]"
                        class="form-control h-auto user"
                        id="material"
                        required
                        style="width: 100%"
                    >
                        <option value="1">Материал №1</option>
                        <option value="2">Материал №2</option>
                        <option value="3">Материал №3</option>
                    </select>
                </div>
            </div>
        
            <div class="row mb-3" style="z-index: 1500">
                <div class="col" style="width: 400px" class="custom-select2">
                    <label for="user">Выбрать Гост<span class="redStars">*</span></label>
                    <select
                            name="gost_id[]"
                            class="form-control h-auto user"
                            id="gost"
                            multiple="multiple"
                            required
                            style="width: 100%"
                    >
                        <option value="" disabled></option>
                        <?php foreach ($this->data["gostArr"] as $gost) : ?>
                            <option value="<?= $gost["ID"] ?>"><?= $gost["GOST"] ?> || <?= $gost["SPECIFICATION"] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="line-dashed-small"></div>
        `)
    })

    //
    $("[data-js-toggle-scheme]").click(function (e) {
        $("[data-js-form-scheme]").toggle(500)
    })

    $("[data-js-copy-modal]").click(function (e) {
        $.magnificPopup.open({
            items: {
                src: '#copy-form',
                type: 'inline'
            },
            fixedContentPos: false,
            closeOnBgClick: false,
            modal: true
        })
    })

    $("[data-js-copy]").click(function (e) {
        $.magnificPopup.close();
        $.ajax({
            url: `https://ulab.niistrom.pro/api/scheme/copyAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "scheme_id": $("#scheme_id").val(),
                "scheme_name": $("#scheme_name").val(),
                "material_id": $("#material-select").val(),
                "type": $("#material_type").val(),
            },
            success: function (result) {
                console.log("===")
                console.log(result);
                document.location.href = `${URI}/laboratory/schemeCard/${result?.scheme_id}/`;

            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })



});

$("#gost").select2({
    // dropdownParent: $('#add-entry-modal-form'),
})
