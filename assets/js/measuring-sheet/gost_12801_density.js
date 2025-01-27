$(function ($) {
	$('.change-trigger').on('input', function (event) {
		let parent = $(this).closest('.measurement-wrapper')
		let arr = []
		let difference = 0

		for (let i = 1; i <= 6; i++) {
			let m_sample_air_weighted = parent.find('.m_sample_air_weighted_' + i).val();
			let m_sample_water_weighted = parent.find('.m_sample_water_weighted_' + i).val();
			let m_sample_soaked_in_water_air_weighted = parent.find('.m_sample_soaked_in_water_air_weighted_' + i).val();
			if (m_sample_soaked_in_water_air_weighted === '' || m_sample_air_weighted === '' || m_sample_water_weighted === '' || m_sample_soaked_in_water_air_weighted === m_sample_water_weighted) {
				continue;
			}

			let sample_density = m_sample_air_weighted / (m_sample_soaked_in_water_air_weighted - m_sample_water_weighted);
			arr.push(sample_density)
			parent.find('.sample_density_' + i).val(round(sample_density, 2));
		}

		let max_sample_density = Math.max.apply(null, arr)
		let min_sample_density = Math.min.apply(null, arr)

		difference = round(max_sample_density - min_sample_density, 2)
		console.log(difference)
		parent.find('.difference').val(difference);
		parent.find('.average_density').val(round(average(arr), 2));

		if (arr.length == 3 && difference > 0.03) {
			// alert('Расхождение результатов трех параллельных испытаний превышает 0,03 г/см3, проведите дополнительные испытания!');

			parent.find('.d-none').removeClass('d-none')
			// 	.forEach(e => {
			// 	e.classList.remove('d-none');
			// })
		}
	});
});
