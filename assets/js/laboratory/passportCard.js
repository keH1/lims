
$("#save").click(function (e) {

    let url = "/ulab/laboratory/updatePassportGostAjax/";

    let commentFormData = new FormData()
  //  commentFormData.append("token", TOKEN);
    commentFormData.append("ulab_comment", $("#ulab_comment").val());
    commentFormData.append("ulab_comment_id", $("#ulab_comment_id").val());
    commentFormData.append("deal_id", $("#deal_id").val());

    saveAjax(url, commentFormData);

    [...$("[data-js-tz-gost-id]")].forEach(function (elem, index, arr) {
        // let data = {
        //     token: TOKEN,
        //     id: $(elem).attr("data-js-tz-gost-id"),
        //     value: $(elem).val(),
        // }

        let formData = new FormData()

        formData.append("token", TOKEN);
        formData.append("id", $(elem).attr("data-js-tz-gost-id"));
        formData.append("value", $(elem).val());
        formData.append("tz_id", $("#tz_id").val());
        formData.append("batch_number", $("#batch_number").val());
        formData.append("file_delete", $("#file_delete").val());


        if ($("#cert").val() != undefined) {
            [...$("#cert").val()].forEach(function (cert, index) {
                formData.append(`cert[]`, $("#cert")[0].files[index]);
            })
        }

        saveAjax(url, formData);

        console.log(formData)
        if (index === arr.length - 1) {
          window.location.reload()
        }
    })

})

$("body").on('change', "[data-js-upload]", function () {
    let countElem = $(this).parent().find("[data-js-input-count]");
    let input = $(this).parent().find("input");
    let count = $(input).prop('files').length;
    $(countElem).text(count).show(300)
})

$("[data-js-delete-file]").click(function (e) {
    e.stopImmediatePropagation();
    let inputDelete = $("#file_delete");

    $(inputDelete).val($(inputDelete).val() + "," + $(this).attr("data-js-delete-file"))

    let filesWrap = $(this).closest("[data-js-file-wrap]").hide();

})

$("[data-js-close-modal]").click(function (e) {
    $.magnificPopup.close();
})

$("#delete-btn").click(function (e) {
    e.stopImmediatePropagation();

    $.magnificPopup.open({
        items: {
            src: '#delete-modal',
            type: 'inline'
        },
        fixedContentPos: false,
        modal: true
    })
})

$("#delete").click(function (e) {
    $.ajax({
        url: "https://ulab.niistrom.pro/api/passport/deleteAjax",
        dataType: 'json', // data type
        type: 'POST',
        data: { "token": TOKEN, "id": $("#tz_id").val(), "ba_tz_id": $("#ba_tz_id").val() },
        success: function (result) {
            console.log(result)
            document.location.href = `${URI}/laboratory/passportJournal/`
            //window.location.reload()
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
        }
    })
})



function saveAjax(url, data) {
    $.ajax({
        async:false,
        url: url,
      //  type: "POST", //метод отправки
        dataType: 'json', // data type
        method: 'POST',
        processData: false,
        contentType: false,
        data: data,
        success: function (result) {
            console.log(result)
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
        }

    })



}