$(function(){

	// 検索対象日
	$('#target_date-datepicker').datetimepicker({
		dayViewHeaderFormat: 'YYYY年 M月',
		locale: 'ja',
		buttons: {
			showClear: true
		},
		icons: {
			time: 'far fa-clock',
			date: 'far fa-calendar-alt',
			up: 'fas fa-arrow-up',
			down: 'fas fa-arrow-down',
			previous: 'fas fa-chevron-left',
			next: 'fas fa-chevron-right',
			today: 'far fa-calendar-alt',
			clear: 'far fa-trash-alt',
			close: 'fas fa-times'
		},
		format: 'YYYY-MM-DD',
	});

	// 集計間隔
	$('#date-type').select2({
		theme: "bootstrap4",
		width: 'auto',
		dropdownAutoWidth: true,
	});

});
