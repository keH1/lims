$(function ($) {
	$('.waterSaturation-Form .change-trigger-ws').on('input', function () {
		let $parent = $(this).closest('.measurement-wrapper')
		let arr = []
		for (let i = 1; i <= 3; i++) {
			let m_sample_air_weighted = $parent.find('.m_sample_air_weighted_' + i).val();
			let m_sample_water_weighted = $parent.find('.m_sample_water_weighted_' + i).val();
			let m_sample_soaked_in_water_air_weighted = $parent.find('.m_sample_soaked_in_water_air_weighted_' + i).val();
			let m_sample_water_saturation_air_weighted = $parent.find('.m_sample_water_saturation_air_weighted_' + i).val();
			if (m_sample_air_weighted === '' || m_sample_water_weighted === '' || m_sample_soaked_in_water_air_weighted === '' || m_sample_water_saturation_air_weighted === '' || m_sample_soaked_in_water_air_weighted == m_sample_water_weighted) {
				continue;
			}
			let sample_water_saturation = (m_sample_water_saturation_air_weighted - m_sample_air_weighted) / (m_sample_soaked_in_water_air_weighted - m_sample_water_weighted) * 100;
			arr.push(sample_water_saturation);
			$parent.find('.sample_water_saturation_' + i).val(round(sample_water_saturation, 1));
		}
		$parent.find('.water_saturation').val(round(average(arr), 1));
	})
})