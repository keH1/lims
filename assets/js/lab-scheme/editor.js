$(document).ready(function () {
    searchParams = new URLSearchParams(window.location.search)

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
          //  dataType: 'json',
            dataType: 'json',
            //  url: `https://uniis.space/local/factory/resource/test`,
            // url: `${URI}/request/getKdListBdAjax/`,
            url: `/ulab/LabScheme/getSchemeEditorDataAjax`,
            data: function (d) {
              //  d.token = TOKEN,
                    d.type = $("#filter-type").val(),
                    d.dateStart = $("#dateStart").val(),
                    d.dateEnd = $("#dateEnd").val()
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
                data: 'material_name',
                defaultContent: "",
                render: function (data, type, item) {
                    //  return `<a href="${URI}/laboratory/materialCard/${item.material_id}/">${data}</a>`;
                    return `<div class="text-center">${data}</div>`;
                }
            },
            {
                data: 'manufacturer',
                defaultContent: "",
                render: function (data, type, item) {
                    //  return `<a href="${URI}/laboratory/materialCard/${item.material_id}/">${data}</a>`;
                    return `<div class="text-center">${data}</div>`;
                }
            },
            {
                data: 'scheme_list',
                defaultContent: "",
                render: function (data, type, item) {
                    let str = "";

                    if (data) {
                        let arr = JSON.parse(data)
                        arr.forEach(function (scheme) {
                            str += `
                                <a href="/ulab/LabScheme/card/${scheme.id}/">
                                    ${scheme.name ? scheme.name : 'Без названия'}
                                </a>
                                <br>`;
                        })
                    }

                    return str;
                }
            },
            {
                data: '',
                render: function (data, type, item) {
                    return `<div class="btn-group">
                                <button data-js-add-gost data-js-material-id="${item.material_id}" class="btn"><i class="fa-solid fa-plus"></i></button>
                                <button data-js-update="${item.material_id}" data-js-manufacturer="${escapeHtml(item.manufacturer)}" data-js-material-name="${item.material_name}" class="btn"><i class="fa-solid fa-pen"></i></button>
                                <button data-js-delete="${item.material_id}" class="btn"><i class="fa-solid fa-trash-can"></i></button>
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
        $(this.header()).closest('thead').find('.search:eq(' + this.index() + ')').on('keyup change clear', function () {
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


        $("#material_id").val($(this).attr("data-js-update"))
        $("#material_name").val($(this).attr("data-js-material-name"))
        $("#manufacturer").val($(this).attr("data-js-manufacturer"))

        $.magnificPopup.open({

            items: {
                src: '#add-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            modal: true
        })

    })

    $("body").on("click", "[data-js-delete]", function (e) {
        $("#material_id").val($(this).attr("data-js-delete"))

        $.magnificPopup.open({

            items: {
                src: '#del-entry-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            modal: true
        })
    })

    $("body").on("click", "[data-js-add-gost]", function (e) {


        $("#material_id").val($(this).attr("data-js-material-id"))
        $("#scheme_name").val($(this).attr("data-js-scheme-name"))

        $.magnificPopup.open({

            items: {
                src: '#add-scheme-modal-form',
                type: 'inline'
            },
            fixedContentPos: false,
            modal: true
        })

    })

    $("body").on("click", "#add-scheme-modal-btn", function (e) {
        //deleteMaterialAjax
        $.ajax({
            url: `/ulab/LabScheme/addAjax`,
            //  url: `https://ulab.niistrom.pro/api/request/simpleTest/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
              //  "token": TOKEN,
                "material_id": $("#material_id").val(),
                "scheme_name": $("#scheme_name").val(),
            },
            success: function (result) {
                console.log(result);
                $.magnificPopup.close();
                journalRequests.ajax.reload();
                // window.open(
                //     `${URI}/labscheme/schemeCard/${result.id}/`,
                //     '_self' // <- This is what makes it open in a new window.
                // );
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("body").on("click", "#del-entry-modal-btn", function (e) {
        //deleteMaterialAjax
        $.ajax({
            url: `/ulab/LabScheme/deleteMaterialAjax`,
            //  url: `https://ulab.niistrom.pro/api/request/simpleTest/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
               // "token": TOKEN,
                "material_id": $("#material_id").val()
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

    $("#add-entry-modal-btn").click(function (e) {

        let materialArr = [];

        console.log("addMat")

        // $("#material").val().forEach(function (id) {
        //  [...$("#material option:selected")].forEach(function (item) {
        //      materialArr.push({
        //          material_id: $(item).val(),
        //          name: $(item).text()
        //      })
        //  })

        let check = true;

        // $("[data-js]").each(function (index) {
        //     if (!$(this).val()) {
        //         $(this).css("background-color", "#F08080")
        //         $(this).parent().find(".select2-selection").css("background", "#F08080")
        //         check = false;
        //     } else {
        //         $(this).css("background-color", "#FFF")
        //         $(this).parent().find(".select2-selection").css("background", "#FFF")
        //
        //     }
        // })

        if (check) {
            console.log("test")
            $.ajax({
                url: `/ulab/LabScheme/addMaterialAjax`,
                //  url: `https://ulab.niistrom.pro/api/request/simpleTest/`,
                type: "POST", //метод отправки
                dataType: 'json', // data type
                data: {
                   // "token": TOKEN,
                    "material_name": $("#material_name").val(),
                    "material_id": $("#material_id").val(),
                    "manufacturer": $("#manufacturer").val(),
                    "type": $("#filter-type").val(),
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
        }


    })

    $("#save-scheme").click(function (e) {
        $.ajax({
            //  url: `https://ulab.niistrom.pro/api/request/insertAjax/`,
            url: `/ulab/labscheme/addSchemeAjax/`,
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
            url: `/ulab/labscheme/getListAjax/`,
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
            url: `/ulab/labscheme/getListSchemeAjax/`,
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

    $("#filter-type").change(function (e) {
        //  $("[data-js-filter-type]").val($(this).val()).change()
        // journalRequests.ajax.reload();

        if ($(this).val() == 1) {
            document.location.href = `/ulab/LabScheme/editor?type=1`
        } else {
            document.location.href = `/ulab/LabScheme/editor`
        }
    })

    // $("[data-js-filter-type]").change(function (e) {
    //     $("#filter-type").val($(this).val()).change()
    // })


});