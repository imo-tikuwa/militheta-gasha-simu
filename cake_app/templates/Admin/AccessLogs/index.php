<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', "アクセスログ");
?>

<?php if (isset($params['search']) && empty($graph_data_all)) { ?>
<?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
alert("データが見つかりませんでした。");
<?= $this->Html->scriptEnd() ?>
<?php } ?>

<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-header bg-body">
      <?= $this->Form->create(null, ['type' => 'GET']) ?>
        <div class="row">
          <div class="col-lg-1 col-md-3 col-sm-3">
            <div class="form-group">
              <?= $this->Form->control('target_date', ['type' => 'text', 'id' => 'target_date-datepicker', 'class' => 'form-control form-control-sm rounded-0 ', 'label' => '検索対象日', 'data-toggle' => 'datetimepicker', 'data-target' => '#target_date-datepicker', 'value' => @$params['target_date']]); ?>
            </div>
          </div>
          <div class="col-lg-1 col-md-3 col-sm-3">
            <div class="form-group">
              <?= $this->Form->control('date_type', ['type' => 'select', 'class' => 'form-control form-control-sm', 'label' => '集計間隔', 'options' => _code('OperationLogs.date_type_jp'), 'value' => @$params['date_type']]); ?>
            </div>
          </div>
          <div class="col-md-1 col-md-3 col-sm-3">
            <label>　</label>
            <?= $this->Form->hidden('search'); ?>
            <?= $this->Form->submit('検索', ['class' => "btn btn-sm btn-flat btn-outline-secondary"]) ?>
          </div>
        </div>
      <?= $this->Form->end(); ?>
    </div>
    <div class="card-body">
      <?php if (isset($graph_data_all) && !empty($graph_data_all)) { ?>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label>全体</label>
          <div class="chart">
            <canvas id="chart_all" style="height:300px; min-height:300px"></canvas>
          </div>
          <hr />
        </div>
      </div>
      <?php } ?>
      <?php if (isset($graph_data_ip) && !empty($graph_data_ip)) { ?>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label>IPアドレス別<small>（アクセス数の多い順に上位9件を表示、10件目以降はその他のグループでまとめて表示）</small></label>
          <div class="chart">
            <canvas id="chart_ip" style="height:300px; min-height:300px"></canvas>
          </div>
          <hr />
        </div>
      </div>
      <?php } ?>
      <?php if (isset($graph_data_ua) && !empty($graph_data_ua)) { ?>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label>ユーザーエージェント別<small>（アクセス数の多い順に上位9件を表示、10件目以降はその他のグループでまとめて表示）</small></label>
          <div class="chart">
            <canvas id="chart_ua" style="height:300px; min-height:300px"></canvas>
          </div>
          <hr />
        </div>
      </div>
      <?php } ?>
      <?php if (isset($graph_data_url) && !empty($graph_data_url)) { ?>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label>リクエストURL別<small>（アクセス数の多い順に上位9件を表示、10件目以降はその他のグループでまとめて表示）</small></label>
          <div class="chart">
            <canvas id="chart_url" style="height:300px; min-height:300px"></canvas>
          </div>
          <hr />
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<?php if (isset($graph_data_all) && !empty($graph_data_all)) { ?>
<input type="hidden" id="graph_data" data-all='<?= $graph_data_all ?>' data-ip='<?= $graph_data_ip ?>' data-ua='<?= $graph_data_ua ?>' data-url='<?= $graph_data_url ?>' />
<?php } ?>
<?= $this->Html->script('admin/access_logs_index', ['block' => true, 'charset' => 'UTF-8']) ?>
