$(function ($) {
	$('.water-resistance .change-trigger-wrs').on('input', function () {
		let $parent = $(this).closest('.measurement-wrapper')

		let arrRw = []
		let arrRaw = []
		for (let i = 1; i <= 3; i++) {
			let breaking_loadRw = $parent.find('.blRw_' + i).val();
			let breaking_loadRaw = $parent.find('.blRaw_' + i).val();
			let squareRw = $parent.find('.sRw_' + i).val();
			let squareRaw = $parent.find('.sRaw_' + i).val();
			if ((breaking_loadRw === '' || squareRw === '' || squareRw === 0) || (breaking_loadRaw === '' || squareRaw === '' || squareRaw === 0)) {
				continue;
			}
			let strRw = (breaking_loadRw / squareRw) * 0.01;
			let strRaw = (breaking_loadRaw / squareRaw) * 0.01;

			$parent.find('.StrRw_' + i).val(round(strRw, 2));
			$parent.find('.StrRaw_' + i).val(round(strRaw, 2));
			arrRw.push(strRw)
			arrRaw.push(strRaw)
		}
		let Average_StrengthRaw = round(average(arrRaw), 2);
		let Average_StrengthRw = round(average(arrRw), 2);
		$parent.find('.Average_StrengthRw').val(Average_StrengthRw);
		$parent.find('.Average_StrengthRaw').val(Average_StrengthRaw);
		if (Average_StrengthRaw !== '' || Average_StrengthRw !== '' || Average_StrengthRaw !== 0) {
			let WS = Average_StrengthRw / Average_StrengthRaw;
			$parent.find('.water_resistance').val(round(WS, 2));
		}
	})

	$('.water-resistance .change-trigger-wrs-res').on('input', function () {
		let $parent = $(this).closest('.measurement-wrapper')

		let arrRw = []
		let arrRaw = []
		for (let i = 1; i <= 3; i++) {
			let rwVal = $parent.find('.StrRw_' + i).val();
			let rawVal = $parent.find('.StrRaw_' + i).val();

			arrRw.push(Number(rwVal))
			arrRaw.push(Number(rawVal))
		}

		let Average_StrengthRaw = round(average(arrRaw), 2);
		let Average_StrengthRw = round(average(arrRw), 2);

		$parent.find('.Average_StrengthRw').val(Average_StrengthRw);
		$parent.find('.Average_StrengthRaw').val(Average_StrengthRaw);

		if (Average_StrengthRaw !== '' || Average_StrengthRw !== '' || Average_StrengthRaw !== 0) {
			let WS = Average_StrengthRw / Average_StrengthRaw;

			$parent.find('.water_resistance').val(round(WS, 2));
		}
	})
})
