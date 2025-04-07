$(function ($) {

	$('.select2').select2({
		theme: 'bootstrap-5',
	})

	$('#place_all').on('change', function (){
		let place = $(this).val()
		$('.place').val(place)
	})

	$('#date_all').on('change', function (){
		let date = $(this).val()
		$('.date').val(date)
	})
})