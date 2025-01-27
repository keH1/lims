$(function () {
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: 'resolve',
    })

    $('.condition-group input[type=checkbox]').click(function () {
        $(this).parents('.condition-group').find('input[type=number]').prop('readonly', this.checked)
    })

    $('#r1').click(function () {
        $('.text-def-1').text('от')
        $('.text-def-2').text('до')
    })

    $('#r2').click(function () {
        $('.text-def-1').text('до')
        $('.text-def-2').text('от')
    })

    $('.is_range_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('.range_text_elem').removeClass('d-none')
        } else {
            $('.range_text_elem').addClass('d-none')
        }
    })

    $('.is_output_check').change(function () {
        if ( $(this).prop('checked') ) {
            $('.norm_comment_elem').removeClass('d-none')
        } else {
            $('.norm_comment_elem').addClass('d-none')
        }
    })

    $('.delete-nd').on('click', function () {
        let groupId = $(this).data('id')
        let $block = $(this).closest('tr')

        if (confirm("Удалить связь группы материала с методикой?")) {
            $.ajax({
                url: '/ulab/normDocGost/deleteMethodGroupAjax/',
                data: {material_group_id : groupId},
                method: 'POST',
                success: function (json) {
                    $block.remove()
                }
            })
        }
    })
})
