<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$button_name = (!empty($gasha) && !$gasha->isNew()) ? "更新" : "登録";
$this->assign('title', "ガシャ{$button_name}");
if ($gasha->hasErrors()) {
  $this->assign('validation_error', $this->makeValidationErrorHtml($gasha->getErrors()));
}
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($gasha) ?>
      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'start_date-datepicker', 'label' => 'ガシャ開始日', 'require' => true]); ?>
            <?= $this->Form->control('start_date', ['type' => 'text', 'id' => 'start_date-datepicker', 'required' => false, 'error' => false, 'class' => 'form-control rounded-0 ', 'label' => false, 'data-toggle' => 'datetimepicker', 'data-target' => '#start_date-datepicker', 'value' => $this->formatDate($gasha->start_date, 'yyyy-MM-dd')]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'end_date-datepicker', 'label' => 'ガシャ終了日', 'require' => true]); ?>
            <?= $this->Form->control('end_date', ['type' => 'text', 'id' => 'end_date-datepicker', 'required' => false, 'error' => false, 'class' => 'form-control rounded-0 ', 'label' => false, 'data-toggle' => 'datetimepicker', 'data-target' => '#end_date-datepicker', 'value' => $this->formatDate($gasha->end_date, 'yyyy-MM-dd')]); ?>
          </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'title', 'label' => 'ガシャタイトル', 'require' => true]); ?>
            <?= $this->Form->control('title', ['type' => 'text', 'class' => 'form-control rounded-0 ', 'label' => false, 'required' => false, 'error' => false]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="form-group">
            <div class="input number">
              <?= $this->element('Parts/label', ['field' => 'ssr_rate', 'label' => 'SSRレート', 'require' => true]); ?>
              <div class="input-group">
                <?= $this->Form->text('ssr_rate', ['type' => 'number', 'id' => 'ssr_rate', 'class' => 'form-control rounded-0', 'label' => false, 'min' => '0', 'max' => '100', 'step' => '1', 'required' => false, 'error' => false]); ?>
                <div class="input-group-append"><span class="input-group-text rounded-0">%</span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="form-group">
            <div class="input number">
              <?= $this->element('Parts/label', ['field' => 'sr_rate', 'label' => 'SRレート', 'require' => true]); ?>
              <div class="input-group">
                <?= $this->Form->text('sr_rate', ['type' => 'number', 'id' => 'sr_rate', 'class' => 'form-control rounded-0', 'label' => false, 'min' => '0', 'max' => '100', 'step' => '1', 'required' => false, 'error' => false]); ?>
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

<?= $this->Html->script('admin/gashas_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
