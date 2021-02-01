$(function(){
    var gasha_data = $('#gasha-data').data('json');
	var gasha_results = [];
	$('#gasha_id').on('change', function(){
		let per_gasha_data = gasha_data[$(this).val()],
		r_rate = 100 - parseInt(per_gasha_data.ssr_rate) - parseInt(per_gasha_data.sr_rate);
		$('#start_date, #start_date_sp').text(per_gasha_data.start_date);
		$('#end_date, #end_date_sp').text(per_gasha_data.end_date);
		$('#ssr_rate, #ssr_rate_sp').text(per_gasha_data.ssr_rate);
		$('#sr_rate, #sr_rate_sp').text(per_gasha_data.sr_rate);
		$('#r_rate, #r_rate_sp').text(r_rate);
	});
	$('#gasha_id').trigger('change');

	$('.display-provision-ratio-modal').on('click', function(){
		let request_url = "/api/get-provision-ratio/" + $('#gasha_id').val();
		$.ajax({
			type: "GET",
			url: request_url,
			contentType: 'application/json',
			dataType: 'json'
		}).done(function(json, status, jqxhr){
			let html = "";
			$.each(json, function(rarity, cards){
				html += "<span>" + rarity + "：全" + cards.length + "種</span>";
				html += "<div class=\"table-responsive\">";
				html += "<table class=\"table table-sm\">";
				html += "<tr><th>タイプ</th><th>カード名</th><th>レート</th></tr>";
				$.each(cards, function(index, card){
					html += "<tr>";
					html += "<td>" + card.type + "</td>";
					html += "<td>" + card.name + "</td>";
					html += "<td>" + card.rate + "%</td>";
					html += "</tr>";
				});
				html += "</table>";
				html += "</div>";
			});
			$("#provision-ratio-modal .modal-body").html(html);
			$('#provision-ratio-modal').modal('show');
		}).fail(function(jqxhr, status, error){
			alert(error);
		});
	});

	$('.pick-gasha').on('click', function(){
		let request_url = "/api/pick-gasha/" + $(this).data('pick-type');
		$.ajax({
			type: "GET",
			url: request_url,
			contentType: 'application/json',
			data: {
				gasha_id: $('#gasha_id').val()
			},
			dataType: 'json'
		}).done(function(result, status, jqxhr){

			let html = "";
			html += "<div class=\"table-responsive\">";
			html += "<table class=\"table table-sm\">";
			html += "<tr><th class=\"d-none d-lg-table-cell\">ID</th><th>タイプ</th><th>カード名</th><th>レアリティ</th></tr>";
			$.each(result, function(index, card){
				html += "<tr>";
				html += "<td class=\"d-none d-lg-table-cell\">" + card.id + "</td>";
				html += "<td>" + card.type + "</td>";
				html += "<td>" + card.name + "</td>";
				html += "<td>" + card.rarity + "</td>";
				html += "</tr>";
				gasha_results.push(card);
			});
			html += "</table>";
			html += "</div>";
			$("#gasha-result, #gasha-result-sp").empty().html(html);
			if (!$("#sp_gasha_results").hasClass('active')) {
				$("#sp-gasha-result-pill").text(result.length);
			}
			calc_aggregate(result);
		}).fail(function(jqxhr, status, error){
			alert(error);
		});
	});

	$('.clear-aggregate').on('click', function(){
		clear_aggregate();
	});

	// 集計処理
	function calc_aggregate(result) {
		let picked_count = parseInt($('#picked_count').text());
		let picked_ssr_count = parseInt($('#picked_ssr_count').text());
		let picked_ssr_rate = parseInt($('#picked_ssr_rate').text());
		let picked_sr_count = parseInt($('#picked_sr_count').text());
		let picked_sr_rate = parseInt($('#picked_sr_rate').text());
		let picked_r_count = parseInt($('#picked_r_count').text());
		let picked_r_rate = parseInt($('#picked_r_rate').text());

		picked_count += result.length;
		$.each(result, function(index, card){
			switch (card.rarity) {
				case 'R':
					picked_r_count++;
					break;
				case 'SR':
					picked_sr_count++;
					break;
				case 'SSR':
					picked_ssr_count++;
					break;
			}
		});
		// 小数点第2位まで表示
		picked_ssr_rate = picked_ssr_count / picked_count;
		picked_ssr_rate = Math.round(picked_ssr_rate * 10000) / 100;
		picked_sr_rate = picked_sr_count / picked_count;
		picked_sr_rate = Math.round(picked_sr_rate * 10000) / 100;
		picked_r_rate = picked_r_count / picked_count;
		picked_r_rate = Math.round(picked_r_rate * 10000) / 100;

		$('#picked_count, #picked_count_sp').text(picked_count);
		$('#picked_ssr_count, #picked_ssr_count_sp').text(picked_ssr_count);
		$('#picked_ssr_rate, #picked_ssr_rate_sp').text(picked_ssr_rate);
		$('#picked_sr_count, #picked_sr_count_sp').text(picked_sr_count);
		$('#picked_sr_rate, #picked_sr_rate_sp').text(picked_sr_rate);
		$('#picked_r_count, #picked_r_count_sp').text(picked_r_count);
		$('#picked_r_rate, #picked_r_rate_sp').text(picked_r_rate);
	}

	// 集計クリア
	function clear_aggregate() {
		$('#picked_count, #picked_count_sp').text(0);
		$('#picked_ssr_count, #picked_ssr_count_sp').text(0);
		$('#picked_ssr_rate, #picked_ssr_rate_sp').text(0);
		$('#picked_sr_count, #picked_sr_count_sp').text(0);
		$('#picked_sr_rate, #picked_sr_rate_sp').text(0);
		$('#picked_r_count, #picked_r_count_sp').text(0);
		$('#picked_r_rate, #picked_r_rate_sp').text(0);
		$('#gasha-result, #gasha-result-sp').empty();
		gasha_results = [];
	}

	// ガシャ結果表示
	$(".gasha-result-modal-open").on("click", function(){
		if (gasha_results.length <= 0) {
			alert("ガシャ結果が存在しませんでした。");
			return false;
		}
		let html = "";
		html += "<table class=\"table table-sm\">";
		html += "<thead><tr><th>タイプ</th><th>カード名</th><th>レアリティ</th><th>ピック数</th></tr></thead>";
		// reduceによる集計を行う
		let gasha_reduce_data = gasha_results.reduce(function (result, current) {
			var element = result.find(function(p) {
				return p.id === current.id
			});
			if (element) {
				element.count++;
			} else {
				current.count = 1;
				result.push(current);
			}
			return result;
		}, []);
		html += "<tbody>";
		$.each(gasha_reduce_data, function(index, card){
			html += "<tr>";
			html += "<td>" + card.type + "</td>";
			html += "<td>" + card.name + "</td>";
			html += "<td>" + card.rarity + "</td>";
			html += "<td>" + card.count + "</td>";
			html += "</tr>";
		});
		html += "</tbody>";
		html += "</table>";
		$("#gasha-result-sort-table").empty().html(html);
		// ガシャ結果モーダルのテーブルのソートを有効化
		$("#gasha-result-sort-table table").tablesorter({
			theme: 'bootstrap4'
		});
		$('#gasha-result-modal').modal('show');
	});

	// SP版のタブを変更したときの処理
	$("#sp-toggle-tab a[data-toggle='tab']").on('shown.bs.tab', function(e) {
		if ($(e.target).attr("href") == "#sp_gasha_results") {
			$(e.target).find("#sp-gasha-result-pill").text("");
		}
	});
});