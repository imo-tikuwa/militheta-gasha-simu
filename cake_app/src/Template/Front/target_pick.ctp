<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$this->assign('title', "ミリシタ ガシャシミュレータ(ピック指定)");
?>
<?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript', 'defer' => true]) ?>

// AjaxのPOSTとGETでPace.jsのプログレスバーを出す（初期値はGETだけの模様）
Pace.options.ajax.trackMethods = ['GET', 'POST'];

$(function(){

	var current_fs, next_fs, previous_fs; //fieldsets
	var opacity;
	$(".next").click(function() {
		current_fs = $(this).parent();
		next_fs = $(this).parent().next();

		// ガシャ選択→ピック対象選択のとき
		if ($(this).data("current-tab") == "gasha-select") {

			Pace.restart();

			// 提供割合を取得
			let request_url = "/api/get-provision-ratio/" + $('#gasha_id').val();
			$.ajax({
				type: "GET",
				url: request_url,
				contentType: 'application/json',
				dataType: 'json',
				async: false,
				cache: false,
			}).done(function(json, status, jqxhr){
				$("#pick-target-table").empty();
				let html = "";
				html += "<p><small>※チェックボックス以外の場所もクリックできます。</small></p>";
				$.each(json, function(rarity, cards){
					html += "<span>" + rarity + "：全" + cards.length + "種</span>";
					html += "<div class=\"table-responsive\">";
					html += "<table class=\"table table-sm\">";
					html += "<tr><th><input type=\"checkbox\" class=\"m-0 pick-target-toggle\" /></th><th>カード</th><th>レート</th></tr>";
					$.each(cards, function(index, card){
						html += "<tr>";
						html += "<td><input type=\"checkbox\" name=\"target_card_ids[]\" value=\"" + card.id + "\" class=\"m-0\" data-card-type=\"" + card.type + "\" data-card-name=\"" + card.name + "\" /></td>";
						html += "<td><img src=\"/img/millitheta/" + card.type + ".png\" /> " + card.name + "</td>";
						html += "<td>" + card.rate + "%</td>";
						html += "</tr>";
					});
					html += "</table>";
					html += "</div>";
				});
				$("#pick-target-table").html(html);
				$("#pick-target-table input[name='target_card_ids\[\]'][type='checkbox']").click(function(e) {
				    e.stopPropagation();
				}).parents('tr').click(function(){
				    $(this).find("input[type='checkbox']").prop('checked', !$(this).find("input[type='checkbox']").prop('checked'));
				});
				// 一括でチェックつけたり消したりする処理
				$("#pick-target-table").find(".pick-target-toggle").on("click", function(){
					let $toggle_targets = $(this).parents("table").find("input[name='target_card_ids\[\]'][type='checkbox']");
					$toggle_targets.prop('checked', $(this).prop('checked'));
				});
			}).fail(function(jqxhr, status, error){
				alert(error);
			});
		}
		// ピック対象選択→ピック方法選択のとき
		else if ($(this).data("current-tab") == "pick-target-select") {

			// チェック処理
			if ($("#pick-target-table input[type='checkbox']:checked").length == 0) {
				alert("1枚以上のカードを選択してください。");
				return false;
			}

			// ピック方法未選択
			$("#pick-type").find(".selected").removeClass("selected");
		}
		// ピック方法選択→結果のとき
		else if ($(this).data("current-tab") == "pick-gasha") {

			// チェック処理
			if ($("#pick-type").find(".selected").length == 0) {
				alert("ピック方法で単発、10連のどちらかを選択してください。");
				return false;
			}

			Pace.restart();

			// ガシャ処理
			let request_url = "/api/target-pick-gasha/",
			target_card_ids = [];

			// リクエスト情報を作成
			request_url += $("#pick-type").find(".selected").data('pick-type');
			$("#pick-target-table input[name='target_card_ids\[\]'][type='checkbox']:checked").map(function(){
				target_card_ids.push($(this).val());
			});

			// GETだとピック対象をたくさん選択したときにリクエストパラメータが長くなりすぎてエラー？になってしまう模様
			// POSTに変更
			let csrf_token = $("#dummy-form").find("input[name='_csrfToken']").val();
			$.ajax({
				type: "POST",
				url: request_url,
				beforeSend: function(xhr){
					xhr.setRequestHeader('X-CSRF-Token', csrf_token);
				},
				data: {
					gasha_id: $('#gasha_id').val(),
					target_card_ids: target_card_ids
				},
				dataType: 'json',
				async: false,
				cache: false,
			}).done(function(results, status, jqxhr){

				// reduceによる集計を行う
				let gasha_reduce_data = results.reduce(function (result, current) {
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
				// ピック数順番にソート
				gasha_reduce_data.sort(function(a, b) {
					if (a.count < b.count) {
						return 1;
					} else {
						return -1;
					}
				})

				let html = "",
				target_card_names = [],
				ssr_pick_count = 0,
				sr_pick_count = 0,
				r_pick_count = 0,
				ssr_pick_rate = 0,
				sr_pick_rate = 0,
				r_pick_rate = 0;
				$.each(gasha_reduce_data, function(index, card){
					switch (card.rarity) {
						case 'R':
							r_pick_count += card.count;
							break;
						case 'SR':
							sr_pick_count += card.count;
							break;
						case 'SSR':
							ssr_pick_count += card.count;
							break;
					}
				});
				ssr_pick_rate = Math.round(ssr_pick_count / results.length * 10000) / 100;
				sr_pick_rate = Math.round(sr_pick_count / results.length * 10000) / 100;
				r_pick_rate = Math.round(r_pick_count / results.length * 10000) / 100;
				$("#pick-target-table input[name='target_card_ids\[\]'][type='checkbox']:checked").map(function(){
					target_card_names.push("<img src=\"/img/millitheta/" + $(this).data("card-type") + ".png\" /> " + $(this).data("card-name"));
				});
				html += "<div class=\"table-responsive\">";
				html += "<table class=\"table table-sm\">";
				html += "<tr><th>ガシャ選択</th><td colspan=\"3\">" + $("#gasha_id option:selected").text() +"</td></tr>";
				html += "<tr><th>ピック対象</th><td colspan=\"3\">" + target_card_names.join('<br />') +"</td></tr>";
				html += "<tr><th>ピック方法</th><td colspan=\"3\">" + $("#pick-type").find(".selected").text() +"</td></tr>";
				html += "<tr><th>ピック回数</th><td colspan=\"3\">" + results.length +"</td></tr>";
				html += "<tr><th>SSR枚数</th><td>" + ssr_pick_count +"</td><th>SSR率</th><td>" + ssr_pick_rate +"%</td></tr>";
				html += "<tr><th>SR枚数</th><td>" + sr_pick_count +"</td><th>SR率</th><td>" + sr_pick_rate +"%</td></tr>";
				html += "<tr><th>R枚数</th><td>" + r_pick_count +"</td><th>R率</th><td>" + r_pick_rate +"%</td></tr>";
				html += "</table>";
				html += "</div>";
				$("#gasha-result-info").empty().html(html);

				html = "";
				html += "<small>※ヘッダー行を選択することでソートできます。</small>";
				html += "<div class=\"table-responsive\">";
				html += "<table class=\"table table-sm\">";
				html += "<thead><tr><th>■</th><th>カード</th><th>レアリティ</th><th>ピック数</th></tr></thead>";
				html += "<tbody>";
				$.each(gasha_reduce_data, function(index, card){
					html += "<tr>";
					html += "<td><span class=\"d-none\">" + card.type + "</span><img src=\"/img/millitheta/" + card.type + ".png\" /></td>";
					html += "<td>" + card.name + "</td>";
					html += "<td>" + card.rarity + "</td>";
					html += "<td>" + card.count + "</td>";
					html += "</tr>";
				});
				html += "</tbody>";
				html += "</table>";
				html += "</div>";
				$("#gasha-result-table").empty().html(html);
				$("#gasha-result-table table").tablesorter({
					theme: 'bootstrap4'
				});
			}).fail(function(jqxhr, status, error){
				alert(error);
			});
		}

		// 背景色変える
		let gradient_from = next_fs.data("gradient-from"),
		gradient_to = next_fs.data("gradient-to");
		$("body").css("background-image", "linear-gradient(120deg, " + gradient_from + ", " + gradient_to + ")");

		$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
		next_fs.show();
		// hide the current fieldset with style
		current_fs.animate({
			opacity: 0
		}, {
			step: function(now) {
				// for making fielset appear animation
				opacity = 1 - now;
				current_fs.css({
					'display': 'none',
					'position': 'relative'
				});
				next_fs.css({
					'opacity': opacity
				});
			},
			duration: 600
		});
		next_fs.scrollTop(0);
	});
	$(".previous").click(function() {
		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();

		// 背景色変える
		let gradient_from = previous_fs.data("gradient-from"),
		gradient_to = previous_fs.data("gradient-to");
		$("body").css("background-image", "linear-gradient(120deg, " + gradient_from + ", " + gradient_to + ")");

		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
		//show the previous fieldset
		previous_fs.show();
		//hide the current fieldset with style
		current_fs.animate({
			opacity: 0
		}, {
			step: function(now) {
				// for making fielset appear animation
				opacity = 1 - now;
				current_fs.css({
					'display': 'none',
					'position': 'relative'
				});
				previous_fs.css({
					'opacity': opacity
				});
			},
			duration: 600
		});
		previous_fs.scrollTop(0);
	});
	$('.radio-group .radio').click(function() {
		$(this).parent().find('.radio').removeClass('selected');
		$(this).addClass('selected');
	});
	$(".submit").click(function() {
		return false;
	});

	// filedsetウィンドウの高さを設定
	$(window).resize(function(){
		let height = $(window).height() - 252;
		$("fieldset").css({"height": height + "px", "max-height": height + "px"});
	});
	$(window).trigger('resize');
});
<?= $this->Html->scriptEnd() ?>

<?= $this->Form->create(null, ['id' => 'dummy-form']); ?>
<?= $this->Form->end(); ?>
<div class="container">
  <div class="row justify-content-center mt-0">
    <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-2 mb-2">
      <div class="card px-0 pt-3 pb-2">
        <h2><strong>ミリシタガシャシミュレータ</strong></h2>
        <div class="row">
          <div class="col-md-12 mx-0" id="multi-step-form">
            <ul id="progressbar">
              <li class="active" id="step1"><strong>①ガシャ選択</strong></li>
              <li id="step2"><strong>②ピック対象選択</strong></li>
              <li id="step3"><strong>③ピック方法選択</strong></li>
              <li id="step4"><strong>④結果</strong></li>
            </ul>
            <fieldset data-gradient-from="#ff4081" data-gradient-to="#81d4fa">
              <h2 class="fs-title text-center">ガシャ選択</h2>
              <div class="form-card">
                <?php echo $this->Form->control('gasha', ['type' => 'select', 'class' => 'form-control form-control-sm rounded-0', 'id' => 'gasha_id', 'label' => false, 'options' => $gasha_selections]); ?>
              </div>
              <input type="button" name="next" class="next action-button" value="ピック対象選択" data-current-tab="gasha-select" />
              <div class="form-card">
                <p>
                  <small>
                    アイドルマスター ミリオンライブ！シアターデイズのガシャシミュレータです<br />
                    ①ピックするガシャを選択<br />
                    ②ピックするカードを選択<br />
                    ③ピックする方法（単発or10連）を選択<br />
                    ④ガシャ結果を確認<br />
                    ※現在、最大で<?= TARGET_PICK_ALLOW_MAX_NUM ?>連まで回せるようになっています。<br />
                    ※天井に関する処理は存在しません。<br />
                    ※復刻限定ガシャで復刻カードについて日ごとのピック確率アップはできていません。一律でピックアップとなってます。
                  </small>
                </p>
              </div>
            </fieldset>
            <fieldset data-gradient-from="#3b51f7" data-gradient-to="#86fc86">
              <h2 class="fs-title text-center">ピック対象選択</h2>
              <input type="button" name="previous" class="previous action-button-previous" value="ガシャ選択" />
              <input type="button" name="next" class="next action-button" value="ピック方法選択" data-current-tab="pick-target-select" />
              <div class="form-card">
                <div id="pick-target-table"><?php // ここにピック対象をテーブル表示、1件以上の選択を必須 ?></div>
              </div>
              <input type="button" name="previous" class="previous action-button-previous" value="ガシャ選択" />
              <input type="button" name="next" class="next action-button" value="ピック方法選択" data-current-tab="pick-target-select" />
            </fieldset>
            <fieldset data-gradient-from="#39ed90" data-gradient-to="#f7e683">
              <h2 class="fs-title text-center">ピック方法選択</h2>
              <div class="form-card">
                <div class="radio-group text-center" id="pick-type">
                  <div class="radio rounded-0 text-center" data-pick-type="tanpatsu"><img src="/img/millitheta/tanpatsu.png" /><br />単発</div>
                  <div class="radio rounded-0 text-center" data-pick-type="jyuren"><img src="/img/millitheta/jyuren.png" /><br />10連</div>
                </div>
              </div>
              <input type="button" name="previous" class="previous action-button-previous" value="ピック対象選択" />
              <input type="button" name="make_payment" class="next action-button" value="ガシャを引く" data-current-tab="pick-gasha" />
            </fieldset>
            <fieldset data-gradient-from="#e6d137" data-gradient-to="#f58d7f">
              <h2 class="fs-title text-center">結果</h2>
              <div class="form-card mb-0">
                <div id="gasha-result-info"><?php // ここにガシャ結果をテーブル表示、1件以上の選択を必須 ?></div>
              </div>
              <input type="button" class="action-button-previous" onclick="location.reload();" value="最初からやり直す" />
              <div class="form-card mb-0">
                <div id="gasha-result-table"><?php // ここにガシャ結果をテーブル表示、1件以上の選択を必須 ?></div>
              </div>
              <input type="button" class="action-button-previous" onclick="location.reload();" value="最初からやり直す" />
            </fieldset>
            <div class="privacy-policy mt-2">
              <a href="javascript:void(0);" data-toggle="modal" data-target="#privacy-modal">プライバシーポリシー</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->element('modal_privacy'); ?>