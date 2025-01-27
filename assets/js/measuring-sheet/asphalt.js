$(function ($) {
	let body = $('body')

	$('.material-group').select2({
		theme: 'bootstrap-5',
		width: 'resolve',
	});

	body.on('change', '.material-group', function () {
		let $parent = $(this).closest('.measurement-wrapper')
		let groupId = $(this).val(),
			ugtp_id = $parent.find('.ugtp').val()
		$('.zern-link').attr('href', '/zern.php?ID='+groupId)
		$('.table_block').empty();

		console.log('ok2', groupId)

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
				let fp = ``
				let r = ``
				let norm = ``

				$.each(data.sieve, function (i, val) {
					header += `<th class="text-center">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="${i}" id="checkbox_${i}" 
											name="form_data[${ugtp_id}][in_protocol][${i}]" checked>
										<label class="form-check-label" for="checkbox_${i}">
										${val}
									</label> 
									<input type="hidden" name="form_data[${ugtp_id}][title][${i}]" value="${val}">
								</th>`

					if (i == 0) {
						m += `<td>
								<input class="form-control calculate first m_${i}" data-col="${i}" type="number" step="any" name="form_data[${ugtp_id}][m][${i}]" value="">
							</td>`

						a += `<td>
								<input class="form-control first a_${i}" data-col="${i}" type="number" step="any" name="form_data[${ugtp_id}][a][${i}]" value="">
							</td>`

						p += `<td>
								<input class="form-control first p_${i}" data-col="${i}"type="number" step="any" name="form_data[${ugtp_id}][p][${i}]" value="">
							</td>`

						fp += `<td>
								<input class="form-control first fp_${i}" data-col="${i}"type="number" step="0.01" name="form_data[${ugtp_id}][fp][${i}]" value="">
							</td>`

						r += `<td>
								<input class="form-control first r_${i}" data-col="${i}"type="text" step="any" name="form_data[${ugtp_id}][r][${i}]" value="">
							</td>`
					} else {
						m += `<td>
								<input class="form-control calculate m_${i}" data-col="${i}" type="number" step="any" name="form_data[${ugtp_id}][m][${i}]" value="">
							</td>`

						a += `<td>
								<input class="form-control a_${i}" data-col="${i}" type="number" step="any" name="form_data[${ugtp_id}][a][${i}]" value="">
							</td>`

						p += `<td>
								<input class="form-control p_${i}" data-col="${i}"type="number" step="any" name="form_data[${ugtp_id}][p][${i}]" value="">
							</td>`

						fp += `<td>
								<input class="form-control fp_${i}" data-col="${i}"type="number" step="0.01" name="form_data[${ugtp_id}][fp][${i}]" value="">
							</td>`

						r += `<td>
								<input class="form-control r_${i}" data-col="${i}"type="text" step="any" name="form_data[${ugtp_id}][r][${i}]" value="">
							</td>`
					}

					if (data.norm_to[i] == '' || data.norm_to[i] === data.norm_from[i]) {
						norm += `<td>
								<input class="form-control" type="text" step="any" name="form_data[${ugtp_id}][norm][${i}]" value="${data.norm_from[i]}">
							</td>`
					} else {
						norm += `<td>
								<input class="form-control" type="text" step="any" name="form_data[${ugtp_id}][norm][${i}]" value="${data.norm_from[i]}-${data.norm_to[i]}">
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
								<td class="text-center"><input class="form-check-input a" type="checkbox" value="1" id="" name="form_data[${ugtp_id}][a_in_protocol]" checked></td>
								${a}
							</tr>
							<tr>
								<td class="text-center">П<sub>i</sub></td>
								<td class="text-center"><input class="form-check-input p" type="checkbox" value="1" id="" name="form_data[${ugtp_id}][p_in_protocol]" checked></td>
								${p}
							</tr>
							<tr>
								<td class="text-center">Полный проход</td>
								<td class="text-center"><input class="form-check-input fp" type="checkbox" value="1" id="" name="form_data[${ugtp_id}][fp_in_protocol]" checked></td>
								${fp}
							</tr>
							<tr>
								<td class="text-center">Рецепт заказчика</td>
								<td class="text-center"><input class="form-check-input recept" type="checkbox" value="1" id="" name="form_data[${ugtp_id}][recept_in_protocol]"></td>
								${r}
							</tr>
							<tr>
								<td class="text-center">Требования</td>
								<td class="text-center"><input class="form-check-input req" type="checkbox" value="1" id="" name="form_data[${ugtp_id}][req_in_protocol]" checked></td>
								${norm}
							</tr>
						</tbody>
					</table>`

				$('.table_block').html(table)
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
		if (isNaN(x) || isNaN(n)) return false;
		let m = Math.pow(10, n);
		return Math.round(x * m) / m;
	}

	body.on('change', '.calculate', function(){
		let equal = $(this).data('col')
		let average_mass = $(".initial_mass").val();

		if (equal == 0) {
			console.log()
			a_i = roundPlus((($(this).val() / average_mass) * 100), 2);

			$(`.a_${equal}`).val(a_i);
			$(`.p_${equal}`).val(a_i);
		} else {
			a_i = roundPlus((($(`.m_${equal}`).val() / average_mass) * 100), 1);

			$(`.a_${equal}`).val(a_i.toFixed(1));

			let parentTD = $($(`.p_${equal}`)).parent();
			let adjacentTD = $(parentTD).prev();

			let adjacentInput = Number($(adjacentTD).children().val());
			P_i = roundPlus((adjacentInput + a_i), 1);

			$(`.p_${equal}`).val(P_i.toFixed(1));
		}
		f_p = roundPlus(100 - $(`.p_${equal}`).val(), 1);
		$(`.fp_${equal}`).val(f_p.toFixed(1))
	});

})
