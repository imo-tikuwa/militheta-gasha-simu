<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$this->assign('title', "ミリシタ ガシャシミュレータ");
?>
<div class="d-none" id="gasha-data" data-json="<?= h($gasha_json_data); ?>"></div>
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
        <?php // スマホのときだけ表示 ?>
        <div class="col-sm-12 d-block d-lg-none">
          <div class="form-group current_gasha_info">
            <div class="input">
              <button type="button" class="btn btn-sm btn-secondary rounded-0 display-provision-ratio-modal">提供割合</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 pick-gasha" data-pick-type="tanpatsu">単発</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 pick-gasha" data-pick-type="jyuren">10連</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 gasha-result-modal-open">集計詳細</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 clear-aggregate">クリア</button>
            </div>
          </div>
        </div>
        <?php // スマホは非表示 ?>
        <div class="col-md-5 col-sm-12 d-none d-lg-block">
          <div class="form-group current_gasha_info">
            <label>選択中のガシャ情報</label>
            <div class="input">
              <label for="start_date">ガシャ開始日</label>：<span id="start_date"></span><br />
              <label for="end_date">ガシャ終了日</label>：<span id="end_date"></span><br />
              <label for="ssr_rate">SSRレート</label>：<span id="ssr_rate"></span>%<br />
              <label for="sr_rate">SRレート</label>：<span id="sr_rate"></span>%<br />
              <label for="r_rate">Rレート</label>：<span id="r_rate"></span>%<br />
              <button type="button" class="btn btn-sm btn-secondary rounded-0 display-provision-ratio-modal">提供割合</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 pick-gasha" data-pick-type="tanpatsu">単発ガシャを引く</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 pick-gasha" data-pick-type="jyuren">10連ガシャを引く</button>
            </div>
          </div>
        </div>
        <?php // スマホは非表示 ?>
        <div class="col-md-7 col-sm-12 d-none d-lg-block">
          <div class="form-group current_gasha_info">
            <label>ガシャ結果</label>
            <div id="gasha-result"></div>
          </div>
        </div>
        <?php // スマホのときだけ表示 ?>
        <div class="col-sm-12 d-block d-lg-none">
          <ul id="sp-toggle-tab" class="nav nav-tabs">
            <li class="nav-item">
              <a href="#sp_gasha_info" class="nav-link active" data-toggle="tab">ガシャ情報</a>
            </li>
            <li class="nav-item">
              <a href="#sp_gasha_aggregate" class="nav-link" data-toggle="tab">集計情報</a>
            </li>
            <li class="nav-item">
              <a href="#sp_gasha_results" class="nav-link" data-toggle="tab" style="position:relative;">ガシャ結果 <span id="sp-gasha-result-pill" class="badge badge-pill badge-danger" style="position:absolute;right:0;top:3px;font-size:60% !important;"></span></a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="sp_gasha_info" class="tab-pane active">
              <div class="col-sm-12">
                <div class="form-group current_gasha_info">
                  <div class="input">
                    <table>
                      <tr>
                        <th><label for="start_date_sp">ガシャ開始日</label></th>
                        <td><span id="start_date_sp"></span></td>
                      </tr>
                      <tr>
                        <th><label for="end_date_sp">ガシャ終了日</label></th>
                        <td><span id="end_date_sp"></span></td>
                      </tr>
                      <tr>
                        <th><label for="ssr_rate_sp">SSRレート</label></th>
                        <td><span id="ssr_rate_sp"></span>%</td>
                      </tr>
                      <tr>
                        <th><label for="sr_rate_sp">SRレート</label></th>
                        <td><span id="sr_rate_sp"></span>%</td>
                      </tr>
                      <tr>
                        <th><label for="r_rate_sp">Rレート</label></th>
                        <td><span id="r_rate_sp"></span>%</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div id="sp_gasha_aggregate" class="tab-pane">
              <div class="col-sm-12">
                <div class="form-group current_gasha_info">
                  <div class="input">
                    <table>
                      <tr>
                        <th><label for="picked_count_sp">ガシャカウント</label></th>
                        <td><span id="picked_count_sp">0</span></td>
                      </tr>
                      <tr>
                        <th><label for="picked_ssr_count_sp">SSR枚数</label></th>
                        <td><span id="picked_ssr_count_sp">0</span></td>
                      </tr>
                      <tr>
                        <th><label for="picked_ssr_rate_sp">SSR率</label></th>
                        <td><span id="picked_ssr_rate_sp">0</span>%</td>
                      </tr>
                      <tr>
                        <th><label for="picked_sr_count_sp">SR枚数</label></th>
                        <td><span id="picked_sr_count_sp">0</span></td>
                      </tr>
                      <tr>
                        <th><label for="picked_sr_rate_sp">SR率</label></th>
                        <td><span id="picked_sr_rate_sp">0</span>%</td>
                      </tr>
                      <tr>
                        <th><label for="picked_r_count_sp">R枚数</label></th>
                        <td><span id="picked_r_count_sp">0</span></td>
                      </tr>
                      <tr>
                        <th><label for="picked_r_rate_sp">R率</label></th>
                        <td><span id="picked_r_rate_sp">0</span>%</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div id="sp_gasha_results" class="tab-pane">
              <div class="col-sm-12">
                <div class="form-group">
                  <div id="gasha-result-sp"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php // スマホは非表示 ?>
        <div class="col-md-5 col-sm-12 d-none d-lg-block">
          <div class="form-group current_gasha_info">
            <label>集計情報</label>
            <div class="input">
              <label for="picked_count">ガシャカウント</label>：<span id="picked_count">0</span><br />
              <label for="picked_ssr_count">SSR枚数</label>：<span id="picked_ssr_count">0</span><br />
              <label for="picked_ssr_rate">SSR率</label>：<span id="picked_ssr_rate">0</span>%<br />
              <label for="picked_sr_count">SR枚数</label>：<span id="picked_sr_count">0</span><br />
              <label for="picked_sr_rate">SR率</label>：<span id="picked_sr_rate">0</span>%<br />
              <label for="picked_r_count">R枚数</label>：<span id="picked_r_count">0</span><br />
              <label for="picked_r_rate">R率</label>：<span id="picked_r_rate">0</span>%<br />
              <button type="button" class="btn btn-sm btn-secondary rounded-0 clear-aggregate">クリア</button>
              <button type="button" class="btn btn-sm btn-secondary rounded-0 gasha-result-modal-open">ガシャ結果表示</button>
            </div>
          </div>
        </div>

        <div class="privacy-policy">
          <a href="javascript:void(0);" data-toggle="modal" data-target="#privacy-modal">プライバシーポリシー</a>
          |
          <?= $this->Html->link('新しいやつ', ['action' => 'targetPick']) ?>
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
        <label><small>※ヘッダー行クリックでソートできます。</small></label>
        <div class="table-responsive" id="gasha-result-sort-table">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary rounded-0" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

<?= $this->element('modal_privacy'); ?>

<?= $this->Html->script('front/index', ['block' => true, 'charset' => 'UTF-8']) ?>