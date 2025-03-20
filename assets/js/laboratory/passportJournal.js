$(document).ready(function () {
    searchParams = new URLSearchParams(window.location.search)
    let compositionCode = "";

    let journalRequests = $('#table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
      //  colReorder: true,
        order: [[0, "desc"]],
        lengthMenu: [[10, 25, 50, 100, -1], [10,25, 50, 100, "Все"]],
        pageLength: 50,
        dom: 'frt<"bottom"lip>',
        bSortCellsTop: true,
        scrollX: true,
        // searching: false,
        fixedHeader: true,
        ajax: {
            type : 'POST',
            dataType: 'json',
            url: `/ulab/laboratory/getJournalAjax/`,
            data: function (d) {
                d.token = TOKEN,
                d.dateStart = $("#dateStart").val(),
                d.dateEnd = $("#dateEnd").val()
             //   d.hidden = $("#hide-row").attr("visible") == 1 ? 0 : 1
            },
            dataSrc: function (json) {
                console.log(json)

                return json.data
            }
        },
        createdRow: function (row, data, dataIndex) {
            $(row).find('td').css('text-align', 'center')
            $(row).find('td').eq(3).css('text-align', 'left')
            //
            // if (searchParams.get('view') != "products") {
            //     $(row).find('td:eq(3)').css("display", "none")
            // }

        },
        columns: [
            {
                data: '',
                defaultContent: "",
                orderable: false,
                render: function (data, type, item) {
                    //  return `<div class="stage rounded m-auto ${item['bgStage']}" title="${item['titleStage']}"></div>`
                    let status = 0
                    let gostArr = JSON.parse(item.custom_gosts)
                    let checkGost = gostArr?.includes(null)

                    //   console.log(checkGost)
                    if (item.ba_tz_id == 0 ) {
                        if (!checkGost) {
                            status = 1
                        }
                //   } else if (item.stage_id == "WON") {
                    } else if (!item.ulab_suitable_status?.includes(null)) {
                        if (!checkGost) {
                            status = 1
                        }
                    }

                    let bg = "bg-warning"
                    let title = "Проверяется"

                    let suitableBg = ""
                    let suitableTitle = ""
                    let suitableStatusHtml = ""
                    let ozStatus = true
                    let ulabStatus = true

                    if (status === 1) {
                        bg = "bg-success"
                        title = "Завершена"

                        if (item.custom_gosts) {
                            ozStatus = Boolean(item.oz_suitable_status?.every(elem => elem === true))
                        }

                        if (item.deal_id) {
                            ulabStatus = Boolean(item.ulab_suitable_status?.every(elem => elem === true || elem == 1))
                        }
                        // console.log(item.id + " ulab: " + ulabStatus + " oz: " + ozStatus + " result: ")
                        // console.log(ulabStatus && ozStatus)

                        if (ulabStatus && ozStatus) {
                            suitableBg = "bg-success"
                            suitableTitle = "Без ошибок"
                        } else {
                            suitableBg = "bg-danger"
                            suitableTitle = "Есть ошибки"
                            if (Boolean(item.ulab_suitable_status?.every(elem => elem == 2))) {
                                suitableBg = "bg-orange-2"
                                suitableTitle = "Обратить внимание"
                            }
                        }

                        suitableStatusHtml = `<div class="stage rounded ${suitableBg}" title="${suitableTitle}"></div>`
                        console.log()
                    }

                    let schemeMethods = JSON.parse(item.scheme_methods)
                    let ulabMethods = JSON.parse(item.ulab_methods)

                    if (schemeMethods?.length > 0 && ulabMethods?.length > 0 ) {
                        let arrDiff = ulabMethods?.filter(x => !schemeMethods?.includes(x))
                        if (arrDiff.length > 0) {
                            bg = "bg-danger"
                            title = "Заявка изменена"
                        }
                    }

                    if (item.stage_id == "LOSE") {
                        bg = "bg-danger"
                        title = "Заявка отменена"
                    }

                    return `
                            <div class="d-flex justify-content-start gap-2">
                                <div class="stage rounded ${bg}" title="${title}"></div>
                                ${suitableStatusHtml}
                            </div>`

                }
            },
            {
                data: 'order_number',
                defaultContent: "",
                render: function (data, type, item) {
                    let number = data ? data : "n/a";
                    return `<a href="${URI}/laboratory/passportCard/${item.id}" title="Карточка">
                                ${item.id}
                            </a>`
                }
            },
            {
                data: 'batch_number',
                defaultContent: ""
            },
            {
                data: 'material_name',
                defaultContent: "",
                render: function (data, type, item) {
                    // let shortName = data;

                    // if (data != undefined && data.length > 14) {
                    //     shortName = data.slice(0, 14) + "..."
                    // }

                    return `<span title="${data}"><a target="_blank" href="/ulab/LabScheme/card/${item.scheme_id}/">${data}</a></span>`
                }
            },
            {
                data: 'composition_code',
                defaultContent: ""
            },
            {
                data: 'quantity',
                defaultContent: ""
            },
            {
                data: 'date',
                defaultContent: ""
            },
            {
                data: 'client',
                defaultContent: "",
                render: function (data, type, item) {
                    let shortName = data;

                    if (data != undefined && data.length > 14) {
                        shortName = data.slice(0, 14) + "..."
                    }

                    return `<span title="${data}">${shortName}</span>`
                }
            },
            // {
            //     data: 'assigned',
            //     defaultContent: ""
            // },
            {
                data: '',
                defaultContent: "",
                orderable: false,
                render: function (data, type, item) {
                    // return `<a target="_blank" href="${URI}/laboratory/dashboard/${item.scheme_id}/" disabled><i class="fa-solid fa-circle-info text-dark"></i></a>`
                    return `<a href="#"><i class="fa-solid fa-circle-info text-dark"></i></a>`
                }
            }

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
    $('#add-entry').magnificPopup({

        items: {
            src: '#add-entry-modal-form',
            type: 'inline'
        },
        fixedContentPos: false,
        modal: true
    })

    $("#search-btn").click(function (e) {
        journalRequests.ajax.reload();
    })

    $("#add-entry-modal-btn").click(function (e) {
        console.log(111)

        let materialArr = [];

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
            $("#loader").removeClass("d-none");
            $.ajax({
                url: `/ulab/laboratory/insertAjax/`,
                //  url: `/api/request/simpleTest/`,
                type: "POST", //метод отправки
                dataType: 'json', // data type
                data: {
                  //  "token": TOKEN,
                    "material_id": $("#material").val(),
                    "scheme_id": $("#scheme").val(),
                    "batch_number": $("#batch_number").val(),
                   // "assigned_name": $("#assigned_name").val(),
                    "quantity": $("#quantity").val(),
                    "order_number": $("#order_number").val(),
                    "b_product_id": $("#b_product_id").val(),
                    "client": $("#client").val(),
                 //   "composition_code": compositionCode,
                    "hidden": $('#hidden').is(':checked') ? 1 : 0,
                   // "assigned_id": $("#assigned_id").val(), // Третьяков
                    "assigned_id": 94, // Третьяков
                    //"batch_number": $("#batch_number").val()
                },
                success: function (result) {
                    console.log(result)
                    $("#loader").addClass("d-none");
                    // console.log(result);
                    $.magnificPopup.close();
                    journalRequests.ajax.reload();
                },
                error: function (xhr, resp, text) {
                    $("#loader").addClass("d-none");
                    console.log(xhr, resp, text);
                }
            });
        }
    })

    let productData = [];

    $("#material").change(function (e) {

        $.ajax({
            //  url: `/api/request/insertAjax/`,
            url: `/ulab/LabScheme/getListAjax/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                // "token": TOKEN,
                "material_id": $(this).val()
            },
            success: function (result) {
                //console.log(result);
                let optionList = "";
                let methodsList = "";

                let methods_material = $(`span[id=${result.id}]`)

                result.forEach(function (scheme) {
                    optionList += `<option data-js-gost-list="${scheme.gost_list}" value="${scheme.scheme_id}">${scheme.scheme_name}</option>`
                })
                $("#scheme").html(optionList);

                let jsonGostList = $("#scheme").find("option:selected").attr("data-js-gost-list")

                if (jsonGostList == undefined) {
                    $("#gost_list").html("")
                } else {
                    renderGostList(jsonGostList)
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    })

    $("#test").click(function () {
        console.log(productData)
    })

    $('#product').select2({
        dropdownParent: $('#add-entry-modal-form'),
        ajax: {
            url: `${URI}/product/getListAjax`,
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                console.log(params)
                return {
                    order_number: params.term || '*'
                }
            },
            processResults: function (response) {
                productData = response;
                return {
                    results: $.map(response, function (item) {
                        return {
                            id: item.id_b,
                            text: item.order_number + " - " + item.name
                        }
                    })
                };
            },
            cache: true
        }
    }).on('select2:select', function (e) {
        let productId = $(this).val();
        let orderNumber = productData[productId]["order_number"]
        let companyName = productData[productId]["company_name"]

        $("#order_number").val(orderNumber)
        $("#b_product_id").val(productId)
        $("#client").val(companyName)

        // тут посчитать № состава по id (b_id)
        getCompositionCode(productId)

    });

    $("body").on("change", "#scheme", function (e){
        renderGostList($(this).find("option:selected").attr("data-js-gost-list"))
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

    function getCompositionData(productIdArr) {
        return $.ajax({
            url: `/api/passport/insertAjax/`,
            //  url: `/api/request/simpleTest/`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "token": TOKEN,
                "material_id": $("#material").val(),
                "scheme_id": $("#scheme").val(),
                "batch_number": $("#batch_number").val(),
                // "assigned_name": $("#assigned_name").val(),
                "quantity": $("#quantity").val(),
                "order_number": $("#order_number").val(),
                "b_product_id": $("#b_product_id").val(),
                "client": $("#client").val(),
                "assigned_id": 94, // Третьяков
                //"batch_number": $("#batch_number").val()
            },
            success: function (result) {
                console.log(result)
                $("#loader").addClass("d-none");
                // console.log(result);
                $.magnificPopup.close();
                journalRequests.ajax.reload();
            },
            error: function (xhr, resp, text) {
                $("#loader").addClass("d-none");
                console.log(xhr, resp, text);
            }
        })
    }

    function renderGostList(json) {
        let jsonData = JSON.parse(json)

        let gostList = "";
        // console.log(jsonData)

        if (jsonData != undefined) {

            jsonData.forEach(function (gost, index) {

                let icon = gost.status === 1 ? "<i title='Своя лабаратория' class=\"fa-solid fa-star text-primary\"></i>" : ""

                gostList += `<span data-js-gost-status="${gost.status}">${index + 1}. ${gost.gost} ${gost.spec} ${icon}</span> <br>`
            })
        }

        $("#gost_list").html(gostList)
    }


    $(document).scroll(function () {
        $(".dtfh-floatingparenthead th")
            .css("padding-inline", "0px")
            .css("font-size", "16px")
    })

    $('#hidden').click(function(){
        if ($(this).is(':checked')) {
            $("body").find("[data-js-gost-status=0]").addClass("text-decoration-line-through")
        } else {
            $("body").find("[data-js-gost-status=0]").removeClass("text-decoration-line-through")
        }
    })

    $("#hide-row").click(function (e) {
        $(this).attr('visible', function (index, attr) {
            return attr == 1 ? 0 : 1;
        });

        let visible = parseInt($(this).attr('visible'))

        let icon = visible === 1
            ? '<i class="fa-solid fa-eye"></i>'
            : '<i class="fa-solid fa-eye-slash"></i>'

        $(this).html(icon)

        journalRequests.ajax.reload();
    })

    function getCompositionCode(bxProductId) {
        compositionCode = ""
        $.ajax({
            //  url: `/api/request/insertAjax/`,
            url: `${URI}/classifier/getCompositionCodeByBxProductId`,
            type: "POST", //метод отправки
            dataType: 'json', // data type
            data: {
                "b_product_id": bxProductId
            },
            success: function (result) {
                //console.log(result);
                compositionCode = result
                if (result) {
                    $("#composition_code").text(compositionCode)
                } else {
                    $("#composition_code").text("Не прикреплен")
                }

                console.log(compositionCode)
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    }




});
