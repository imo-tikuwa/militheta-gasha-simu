<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$button_name = (!empty($gasha) && !$gasha->isNew()) ? "更新" : "登録";
$this->assign('title', "ガシャ{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($gasha) ?>
      <div class="row">
        <div class="col-md-2 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('start_date', ['type' => 'text', 'id' => 'start_date-datepicker', 'class' => 'form-control rounded-0 ', 'label' => 'ガシャ開始日', 'value' => $this->formatDate($gasha->start_date, 'yyyy-MM-dd')]); ?>
          </div>
        </div>
        <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
        $(function(){
          $('#start_date-datepicker').bootstrapMaterialDatePicker({
            lang: 'ja',
            nowButton: true,
            clearButton: true,
            format: 'YYYY-MM-DD',
            time: false,
          });
        });
        <?= $this->Html->scriptEnd() ?>
        <div class="col-md-2 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('end_date', ['type' => 'text', 'id' => 'end_date-datepicker', 'class' => 'form-control rounded-0 ', 'label' => 'ガシャ終了日', 'value' => $this->formatDate($gasha->end_date, 'yyyy-MM-dd')]); ?>
          </div>
        </div>
        <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
        $(function(){
          $('#end_date-datepicker').bootstrapMaterialDatePicker({
            lang: 'ja',
            nowButton: true,
            clearButton: true,
            format: 'YYYY-MM-DD',
            time: false,
          });
        });
        <?= $this->Html->scriptEnd() ?>
        <div class="col-md-8 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('title', ['class' => 'form-control rounded-0 ', 'label' => 'ガシャタイトル']); ?>
          </div>
        </div>
        <div class="col-md-2 col-sm-12">
          <div class="form-group">
            <div class="input number">
              <label for="ssr_rate">SSRレート</label>
              <div class="input-group">
                <?= $this->Form->text('ssr_rate', ['type' => 'number', 'id' => 'ssr_rate', 'class' => 'form-control rounded-0', 'label' => 'SSRレート', 'min' => '0', 'max' => '100', 'step' => '1']); ?>
                <div class="input-group-append"><span class="input-group-text rounded-0">%</span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-12">
          <div class="form-group">
            <div class="input number">
              <label for="sr_rate">SRレート</label>
              <div class="input-group">
                <?= $this->Form->text('sr_rate', ['type' => 'number', 'id' => 'sr_rate', 'class' => 'form-control rounded-0', 'label' => 'SRレート', 'min' => '0', 'max' => '100', 'step' => '1']); ?>
                <div class="input-group-append"><span class="input-group-text rounded-0">%</span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <?= $this->Form->button($button_name, ['class' => "btn btn-flat btn-outline-secondary"]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>
