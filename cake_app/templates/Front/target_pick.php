<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$this->assign('title', "ミリシタ ガシャシミュレータ(ピック指定)");
?>
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
			  |
			  <?= $this->Html->link('古いやつ', ['action' => 'index']) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->element('modal_privacy'); ?>

<?= $this->Html->script('front/target_pick', ['block' => true, 'charset' => 'UTF-8']) ?>