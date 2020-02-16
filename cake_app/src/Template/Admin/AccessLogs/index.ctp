<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha[]|\Cake\Collection\CollectionInterface $gashas
 */
$this->assign('title', "アクセスログ");
?>


<?= $this->Html->script('/node_modules/admin-lte/plugins/chart.js/Chart.min.js')?>
<?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
$(function(){

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

<?php if (isset($graph_data_all) && !empty($graph_data_all)) { ?>
  var chart_canvas_all = $('#chart_all').get(0).getContext('2d');
  var chart_all = new Chart(chart_canvas_all, {
    type: 'bar',
    data: <?= $graph_data_all; ?>,
    options: chart_options
  });
<?php } ?>

<?php if (isset($graph_data_ip) && !empty($graph_data_ip)) { ?>
  var chart_canvas_ip = $('#chart_ip').get(0).getContext('2d');
  var chart_ip = new Chart(chart_canvas_ip, {
    type: 'bar',
    data: <?= $graph_data_ip; ?>,
    options: chart_options
  });
<?php } ?>

<?php if (isset($graph_data_ua) && !empty($graph_data_ua)) { ?>
  var chart_canvas_ua = $('#chart_ua').get(0).getContext('2d');
  var chart_ua = new Chart(chart_canvas_ua, {
    type: 'bar',
    data: <?= $graph_data_ua; ?>,
    options: chart_options
  });
<?php } ?>

<?php if (isset($graph_data_url) && !empty($graph_data_url)) { ?>
  var chart_canvas_url = $('#chart_url').get(0).getContext('2d');
  var chart_url = new Chart(chart_canvas_url, {
    type: 'bar',
    data: <?= $graph_data_url; ?>,
    options: chart_options
  });
<?php } ?>
});
<?= $this->Html->scriptEnd() ?>


<?php if (isset($params['search']) && empty($graph_data_all)) { ?>
<?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
alert("データが見つかりませんでした。");
<?= $this->Html->scriptEnd() ?>
<?php } ?>

<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-header">
      <?= $this->Form->create(null, ['type' => 'GET']) ?>
        <div class="row">
          <div class="col-lg-1 col-md-3 col-sm-3">
            <div class="form-group">
              <?= $this->Form->control('target_date', ['type' => 'text', 'id' => 'target_date-datepicker', 'class' => 'form-control rounded-0 ', 'label' => '検索対象日', 'data-toggle' => 'datetimepicker', 'data-target' => '#target_date-datepicker', 'value' => @$params['target_date']]); ?>
            </div>
          </div>
          <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
          $(function(){
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
          });
          <?= $this->Html->scriptEnd() ?>
          <div class="col-lg-1 col-md-3 col-sm-3">
            <div class="form-group">
              <?= $this->Form->control('date_type', ['type' => 'select', 'label' => '集計間隔', 'options' => _code('OperationLogs.date_type_jp'), 'value' => @$params['date_type']]); ?>
            </div>
          </div>
          <div class="col-md-1 col-md-3 col-sm-3">
            <label>　</label>
            <?= $this->Form->hidden('search'); ?>
            <?= $this->Form->submit('検索', ['class' => "btn btn-flat btn-outline-secondary"]) ?>
          </div>
        </div>
      <?= $this->Form->end(); ?>
    </div>
    <div class="card-body">
      <div class="row">
      <?php if (isset($graph_data_all) && !empty($graph_data_all)) { ?>
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
