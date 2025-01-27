$('.AStrenghtForm .change-trigger-asf').on('input', function (event) {
	let $parent = $(this).closest('.measurement-wrapper')
	let arr0 = []
	let arr20 = []
	let arr50 = []
	let str = ''
	for (let i = 1; i <= 3; i++) {
		let breaking_load = $parent.find('.breaking_load_' + i).val();
		let square = $parent.find('.square_' + i).val();
		if (breaking_load === '' || square === '' || square === 0) {
			str = Number($parent.find('.Strength_' + i).val())
		} else {
			str = (breaking_load / square) * 0.01;
			$parent.find('.Strength_' + i).val(round(str, 2));
		}
		console.log(arr0)
		// if (i <= 3) {
			arr0.push(str)
		// } else if (i > 3 && i <= 6) {
		// 	arr20.push(str)
		// } else if (i > 6 && i <= 9) {
		// 	arr50.push(str)
		// }
	}

	$parent.find('.Average_Strength').val(round(average(arr0), 2));
	// $parent.find('.Average_Strength').val(round(average(arr20), 2));
	// $parent.find('.Average_Strength').val(round(average(arr50), 2));
});
