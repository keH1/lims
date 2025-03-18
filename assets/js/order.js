$(function ($) {
	$('#LONGTERM').change(function () {
		if ($(this).prop('checked')) {
			$('#payment').removeClass('visually-hidden')
			$('#contract_summ').prop('disabled', false)
		} else {
			$('#payment').addClass('visually-hidden')
			$('#contract_summ').prop('disabled', true)
		}
	})

	$('#CLIENT_NUMBER').change(function () {
		if ($(this).prop('checked')) {
			$('input[name=CONTRACT_TYPE]').prop('readonly', false)
			$('input[name=NUMBER]').prop('readonly', false)
			$('input[name=DATE]').prop('readonly', false)
		} else {
			$('input[name=CONTRACT_TYPE]').prop('readonly', true)
			$('input[name=NUMBER]').prop('readonly', true)
			$('input[name=DATE]').prop('readonly', true)
		}
	})

	$('body').find('.popup-with-form').magnificPopup({
		type: 'inline',
		closeBtnInside:true,
		fixedContentPos: false
	})

	$('.upload_pdf').change(function () {
		let docId = $(this).data('tz_doc_id')
		let dogovorId = $(this).data('dogovor_id')
		let tzId = $(this).data('tz_id')
		let file = $(this)[0].files[0]

		let formData = new FormData()

		if ( tzId !== undefined ) {
			formData.append("tz_id", tzId)
		}
		if ( docId !== undefined ) {
			formData.append("doc_id", docId)
		}
		if ( dogovorId !== undefined ) {
			formData.append("dogovor_id", dogovorId)
		}
		formData.append("file", file, file.name)
		formData.append("upload_file", true)


		$.ajax({
			url: '/ulab/order/uploadTzDocPdfAjax/',
			data: formData,
			method: 'POST',
			contentType: false,
			processData: false,
			cache: false,
			async: false,
			success: function (json) {
				location.reload()
			},
			error: function () {
				console.log('error')
			}
		})
	})


	let journalDataTable = $('#journal_order').DataTable({
		destroy : true,
		retrieve: true,
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
			type : 'POST',
			data: function ( d ) {
				d.stage = $('#selectStage option:selected').val()
				d.order_id = $('#input_order_id').val()
			},
			url : '/ulab/order/getJournalRequestAjax/',
			dataSrc: function (json) {
				return json.data
			},
		},
		columns: [
			{
				data: 'requestTitle',
				class: 'text-nowrap',
				render: function (data, type, item) {
					return `<a class="request-link"
						   href="/ulab/request/card/${item.ID_Z}" target="_blank">
						   ${item['REQUEST_TITLE']}
						</a>`
				}
			},
			{
				data: 'date',
				width: 100,
			},
			{
				data: 'price_discount',
			},
			{
				data: 'ACCOUNT',
			},
			{
				data: 'ACTUAL_VER',
				orderable: false,
				render: function (data, type, item) {
					return `<a href="/ulab/requirement/card_new/${item['ID']}">${item['ID']}</a>`
				}
			},
			{
				data: 'tz_pdf',
				orderable: false,
				render: function (data, type, item) {
					if ( !item['tz_pdf'] ) {
						return `<label class="upload-pdf cursor-pointer nav-link link-card"
									title="Загрузить PDF-версию">
								<svg class="icon" width="25" height="25">
									<use xlink:href="/ulab/assets/images/icons.svg#upload"/>
								</svg>
							
								<input class="d-none upload_pdf" data-tz_id="${item['ID']}"
									data-tz_doc_id="${item['tz_doc_id']}" type="file" name="upload_pdf">
								</label>`
					} else {
						return `
								<a href="/protocol_generator/archive_tz/${item['ID']}/${item['tz_pdf']}">${item['tz_pdf']}</a>  
								<a data-tz_doc_id="${item['tz_doc_id']}" class="del-tz-pdf"><i class="fa-solid fa-xmark"></i></a>`
					}
				}

			},
			{
				data: 'DISCOUNT',
				render: function (data, type, item) {
					if ( !item['DISCOUNT'] ) {
						return `-`
					}

					return `${item['DISCOUNT']} %`
				}
			},
			{
				data: 'OPLATA',
			},
			{
				data: 'button',
				orderable: false,
				render: function (data, type, item) {
					if ( !item['ACCOUNT'] ) {
						return `<a href="#" class="btn btn-primary disabled">Нет счета</a>`
					}
					const p = parseFloat(item['price_discount']).toFixed(2)
					const o = parseFloat(item['OPLATA']).toFixed(2)
					if ( p === o ) {
						return `<a href="#" class="btn btn-primary disabled">Оплачено</a>`
					}
					if ( p < o ) {
						return `<a href="#" class="btn btn-primary disabled">Переплата</a>`
					}
					if ( p > o ) {
						return `<a href="#" class="btn btn-primary disabled">Не оплачено</a>`
					}

					// if ( !item['is_show_finance'] ) {
					// 	return `<a href="#" class="btn btn-primary disabled">Нет прав</a>`
					// }

					return `<a href="#add-payment" data-deal_id="${item['ID_DEAL']}" data-pay="${item['OPLATA']}" data-price="${item['price_discount']}" class="btn btn-primary popup-with-form">Оплатить</a>`
				}
			},
		],
		language: dataTablesSettings.language,
		lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Все"]],
		pageLength: 25,
		order: [ [ 0, 'asc' ] ],
		dom: 'frtB<"bottom"lip>',
		buttons: dataTablesSettings.buttons,
	})


	journalDataTable.on('click', '.del-tz-pdf', function () {
		let docId = $(this).data('tz_doc_id')
		if (confirm('Вы действительно желаете удалить загруженный файл?')) {

			$.ajax({
				url: '/ulab/order/delTzDocAjax/',
				data: {
					id: docId
				},
				method: 'POST',
				success: function (json) {
					journalDataTable.ajax.reload()
					journalDataTable.draw()
				},
				error: function () {
					console.log('error')
				}
			})
		}
	})


	journalDataTable.on('change', '.upload_pdf', function () {
		let docId = $(this).data('tz_doc_id')
		let dogovorId = $(this).data('dogovor_id')
		let tzId = $(this).data('tz_id')
		let file = $(this)[0].files[0]

		let formData = new FormData()

		if ( tzId !== undefined ) {
			formData.append("tz_id", tzId)
		}
		if ( docId !== undefined ) {
			formData.append("doc_id", docId)
		}
		if ( dogovorId !== undefined ) {
			formData.append("dogovor_id", dogovorId)
		}
		formData.append("file", file, file.name)
		formData.append("upload_file", true)


		$.ajax({
			url: '/ulab/order/uploadTzDocPdfAjax/',
			data: formData,
			method: 'POST',
			contentType: false,
			processData: false,
			cache: false,
			async: false,
			success: function (json) {
				console.log('ok2')
				journalDataTable.ajax.reload()
				journalDataTable.draw()
			},
			error: function () {
				console.log('error')
			}
		})
	})


	journalDataTable.on('click', '.popup-with-form', function () {
		const dealId = $(this).data('deal_id')
		const pay = $(this).data('pay')
		const price = $(this).data('price')
		const need = parseFloat(price) - parseFloat(pay)
		const finance = parseFloat($('#finance').val())

		$.magnificPopup.open({
			items: {
				src: '#add-payment',
			},
			type: 'inline',
			closeBtnInside: true,
			fixedContentPos: false,
			callbacks: {
				beforeOpen: function() {
					if ( !isNaN(need) ) {
						$('#add-payment').find('#deal_id_input').val(dealId)
						$('#add-payment').find('#need-input').val(need)
						$('#add-payment').find('#money-input').val(Math.min(need, finance))
					}
				}
			}
		})
	})

	let timeout
	journalDataTable.columns().every(function () {
		$(this.header()).closest('thead').find('.search:eq('+ this.index() +')').on('keyup change clear', function () {
			clearTimeout(timeout)
			const searchValue = this.value
			timeout = setTimeout(function () {
				journalDataTable
					.column($(this).parent().index())
					.search(searchValue)
					.draw()
			}.bind(this), 1000)
		})
	})

	$('.filter').on('change', function () {
		journalDataTable.ajax.reload()
		journalDataTable.draw()
	})

	$('.search').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault()
        }
    })
})