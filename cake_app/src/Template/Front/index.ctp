<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$this->assign('title', "ミリシタ ガシャシミュレータ");
?>

<?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
$(function(){
	var gasha_data = <?php echo $gasha_json_data; ?>;
	var gasha_results = [];
	$('#gasha_id').on('change', function(){
		let per_gasha_data = gasha_data[$(this).val()];
		$('#start_date').text(per_gasha_data.start_date);
		$('#end_date').text(per_gasha_data.end_date);
		$('#ssr_rate').text(per_gasha_data.ssr_rate);
		$('#sr_rate').text(per_gasha_data.sr_rate);
		$('#r_rate').text(100 - parseInt(per_gasha_data.ssr_rate) - parseInt(per_gasha_data.sr_rate));
	});
	$('#gasha_id').trigger('change');

	$('#display-provision-ratio-modal').on('click', function(){
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
			html += "<table class=\"table table-sm\">";
			html += "<tr><th>ID</th><th>タイプ</th><th>カード名</th><th>レアリティ</th></tr>";
			$.each(result, function(index, card){
				html += "<tr>";
				html += "<td>" + card.id + "</td>";
				html += "<td>" + card.type + "</td>";
				html += "<td>" + card.name + "</td>";
				html += "<td>" + card.rarity + "</td>";
				html += "</tr>";
				gasha_results.push(card);
			});
			html += "</table>";
			$("#gasha-result").empty().html(html);
			calc_aggregate(result);
		}).fail(function(jqxhr, status, error){
			alert(error);
		});
	});

	$('#clear-aggregate').on('click', function(){
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

		$('#picked_count').text(picked_count);
		$('#picked_ssr_count').text(picked_ssr_count);
		$('#picked_ssr_rate').text(picked_ssr_rate);
		$('#picked_sr_count').text(picked_sr_count);
		$('#picked_sr_rate').text(picked_sr_rate);
		$('#picked_r_count').text(picked_r_count);
		$('#picked_r_rate').text(picked_r_rate);
	}

	// 集計クリア
	function clear_aggregate() {
		$('#picked_count').text(0);
		$('#picked_ssr_count').text(0);
		$('#picked_ssr_rate').text(0);
		$('#picked_sr_count').text(0);
		$('#picked_sr_rate').text(0);
		$('#picked_r_count').text(0);
		$('#picked_r_rate').text(0);
		gasha_results = [];
	}

	// ガシャ結果表示
	$("#gasha-result-modal-open").on("click", function(){
		if (gasha_results.length <= 0) {
			alert("ガシャ結果が存在しませんでした。");
			return false;
		}
		let html = "";
		html += "<table class=\"table table-sm\">";
		html += "<tr><th>タイプ</th><th>カード名</th><th>レアリティ</th><th>ピック数</th></tr>";
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
		$.each(gasha_reduce_data, function(index, card){
			html += "<tr>";
			html += "<td>" + card.type + "</td>";
			html += "<td>" + card.name + "</td>";
			html += "<td>" + card.rarity + "</td>";
			html += "<td>" + card.count + "</td>";
			html += "</tr>";
		});
		html += "</table>";
		$("#gasha-result-modal .modal-body").html(html);
		$('#gasha-result-modal').modal('show');
	});
});
<?= $this->Html->scriptEnd() ?>

<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">

      <div class="row">
        <div class="col-md-7 col-sm-12">
          <div class="form-group">
            <label>ガシャ</label>
            <?php echo $this->Form->control('gasha', ['type' => 'select', 'class' => 'form-control form-control-sm rounded-0', 'id' => 'gasha_id', 'label' => false, 'options' => $gasha_selections]); ?>
          </div>
        </div>
        <div class="col-md-5 col-sm-12">
          <div class="form-group" id="current_gasha_info">
            <label>選択中のガシャ情報</label>
            <div class="input">
              <label for="start_date">ガシャ開始日</label>：<span id="start_date"></span><br />
              <label for="end_date">ガシャ終了日</label>：<span id="end_date"></span><br />
              <label for="ssr_rate">SSRレート</label>：<span id="ssr_rate"></span>%<br />
              <label for="sr_rate">SRレート</label>：<span id="sr_rate"></span>%<br />
              <label for="r_rate">Rレート</label>：<span id="r_rate"></span>%<br />
              <button type="button" class="btn btn-sm btn-secondary rounded-0" id="display-provision-ratio-modal">提供割合</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 pick-gasha" data-pick-type="tanpatsu">単発ガシャを引く</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 pick-gasha" data-pick-type="jyuren">10連ガシャを引く</button>
            </div>
          </div>
        </div>
        <div class="col-md-7 col-sm-12">
          <div class="form-group" id="current_gasha_info">
            <label>ガシャ結果</label>
            <div id="gasha-result"></div>
          </div>
        </div>
        <div class="col-md-5 col-sm-12">
          <div class="form-group" id="current_gasha_info">
            <label>集計情報</label>
            <div class="input">
              <label for="picked_count">ガシャカウント</label>：<span id="picked_count">0</span><br />
              <label for="picked_ssr_count">SSR枚数</label>：<span id="picked_ssr_count">0</span><br />
              <label for="picked_ssr_rate">SSR率</label>：<span id="picked_ssr_rate">0</span>%<br />
              <label for="picked_sr_count">SR枚数</label>：<span id="picked_sr_count">0</span><br />
              <label for="picked_sr_rate">SR率</label>：<span id="picked_sr_rate">0</span>%<br />
              <label for="picked_r_count">R枚数</label>：<span id="picked_r_count">0</span><br />
              <label for="picked_r_rate">R率</label>：<span id="picked_r_rate">0</span>%<br />
              <button type="button" class="btn btn-sm btn-secondary rounded-0" id="clear-aggregate">クリア</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0" id="gasha-result-modal-open">ガシャ結果表示</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="provision-ratio-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">提供割合</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary rounded-0" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="gasha-result-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ガシャ結果</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary rounded-0" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>
