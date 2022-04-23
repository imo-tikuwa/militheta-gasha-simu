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
		theme: "bootstrap-5",
		width: 'auto',
		dropdownAutoWidth: true,
	});

	// チャート表示
	var $graph_data = $("#graph_data");
	if ($graph_data.get(0)) {
		var chart_options = {
			responsive : true,
			maintainAspectRatio : false,
			scales: {
				xAxes: [{
				stacked: true,
			}],
			yAxes: [{
				stacked: true
			}]
			}
		};

		var chart_canvas_all = $('#chart_all').get(0).getContext('2d');
		var chart_all = new Chart(chart_canvas_all, {
			type: 'bar',
			data: $graph_data.data('all'),
			options: chart_options
		});

		var chart_canvas_ip = $('#chart_ip').get(0).getContext('2d');
		var chart_ip = new Chart(chart_canvas_ip, {
			type: 'bar',
			data: $graph_data.data('ip'),
			options: chart_options
		});

		var chart_canvas_ua = $('#chart_ua').get(0).getContext('2d');
		var chart_ua = new Chart(chart_canvas_ua, {
			type: 'bar',
			data: $graph_data.data('ua'),
			options: chart_options
		});

		var chart_canvas_url = $('#chart_url').get(0).getContext('2d');
		var chart_url = new Chart(chart_canvas_url, {
			type: 'bar',
			data: $graph_data.data('url'),
			options: chart_options
		});
	}

});
