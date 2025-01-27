$(function ($) {
	let body = $('body')

	$('.material-group').select2({
		theme: 'bootstrap-5',
		width: 'resolve',
	});

	body.on('change', '.checkbox_grain', function () {
		$(".measurement-wrapper").find(".result_value").toggleClass('visually-hidden')
		$(".measurement-wrapper").find("input[name='result_value']").toggleClass('disabled')
		$(".measurement-wrapper").find(".zern").toggleClass('visually-hidden')
	})

	body.on('change', '.material-group', function () {
		let groupId = $(this).val()

		$('.list_data').remove();


		console.log('ok')


		$.ajax({
			method: 'POST',
			url: '/ulab/material/getGroupMaterialAjax',
			data: {
				groupId: groupId
			},
			dataType: 'json',
			success: function (data) {
				let header = ``
				let m = ``
				let a = ``
				let p = ``
				let norm = ``

				$.each(data.sieve, function (i, val) {
					header += `<th class="text-center">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="${i}" id="checkbox_${i}" 
											name="in_protocol[${i}]" checked>
										<label class="form-check-label" for="checkbox_${i}">
										${val}
									</label> 
									<input type="hidden" name="title[${i}]" value="${val}">
								</th>`

					if (i == 0) {
						m += `<td>
								<input class="form-control calculate first" data-col="${i}" type="number" step="any" name="m[${i}]" value="">
							</td>`

						a += `<td>
								<input class="form-control first" data-col="${i}" type="number" step="any" name="a[${i}]" value="">
							</td>`

						p += `<td>
								<input class="form-control first" data-col="${i}"type="number" step="any" name="p[${i}]" value="">
							</td>`
					} else {
						m += `<td>
								<input class="form-control calculate" data-col="${i}" type="number" step="any" name="m[${i}]" value="">
							</td>`

						a += `<td>
								<input class="form-control" data-col="${i}" type="number" step="any" name="a[${i}]" value="">
							</td>`

						p += `<td>
								<input class="form-control" data-col="${i}"type="number" step="any" name="p[${i}]" value="">
							</td>`
					}

					if (data.norm_to[i] == '' || data.norm_to[i] === data.norm_from[i]) {
						norm += `<td>
								<input class="form-control" type="text" step="any" name="norm[${i}]" value="${data.norm_from[i]}">
							</td>`
					} else {
						norm += `<td>
								<input class="form-control" type="text" step="any" name="norm[${i}]" value="${data.norm_from[i]}-${data.norm_to[i]}">
							</td>`
					}


				})

				let table =
					`<table class="table list_data graincomposition">
						<thead>
							<tr>
								<th class="text-center">Размер сит, мм</th>
								<th class="text-center"></th>
								${header}
							</tr>
							</thead>
							<tbody>
							<tr>
								<td class="text-center">m<sub>i</sub></td>
								<td class="text-center"></td>
								${m}
							</tr>
							<tr>
								<td class="text-center">a<sub>i</sub></td>
								<td class="text-center"><input class="form-check-input" type="checkbox" value="1" id="a" name="a_in_protocol" checked></td>
								${a}
							</tr>
							<tr>
								<td class="text-center">П<sub>i</sub></td>
								<td class="text-center"><input class="form-check-input" type="checkbox" value="1" id="p" name="p_in_protocol" checked></td>
								${p}
							</tr>
							<tr>
								<td class="text-center">Требования</td>
								<td class="text-center"><input class="form-check-input" type="checkbox" value="1" id="req" name="req_in_protocol" checked></td>
								${norm}
							</tr>
						</tbody>
					</table>`

				$('.initial_mass').after(table)
			},
			error: function (jqXHR, exception) {
				let msg = '';
				if (jqXHR.status === 0) {
					msg = 'Not connect.\n Verify Network.';
				} else if (jqXHR.status === 404) {
					msg = 'Requested page not found. [404]';
				} else if (jqXHR.status === 500) {
					msg = 'Internal Server Error [500].';
				} else if (exception === 'parsererror') {
					msg = 'Requested JSON parse failed.';
				} else if (exception === 'timeout') {
					msg = 'Time out error.';
				} else if (exception === 'abort') {
					msg = 'Ajax request aborted.';
				} else {
					msg = 'Uncaught Error.\n' + jqXHR.responseText;
				}
				console.error(msg)
			}
		})
	})

	function roundPlus(x, n) {
		if(isNaN(x) || isNaN(n)) return false;
		let m = Math.pow(10, n);
		return Math.round(x * m) / m;
	}

	body.on('change', '.calculate', function(){
		let equal = $(this).data('col')
		let average_mass = $("[name='initial_mass']").val();

		if(equal == 0) {
			console.log()
			a_i = roundPlus((($(this).val() / average_mass) * 100), 2);

			$('[name^=a].first').val(a_i);
			$(`[name^=p].first`).val(a_i);
		}
		else{
			a_i = roundPlus((($(`[name='m[${equal}]']`).val() / average_mass) * 100), 2);

			$(`[name='a[${equal}]']`).val(a_i);

			let parentTD = $($(`[name='p[${equal}]']`)).parent();
			let adjacentTD = $(parentTD).prev();

			let adjacentInput = Number($(adjacentTD).children().val());

			P_i = roundPlus((adjacentInput + a_i), 2);
			$(`[name='p[${equal}]']`).val(P_i);
		}
	});

})
