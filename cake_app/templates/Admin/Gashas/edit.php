<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$button_name = (!empty($gasha) && !$gasha->isNew()) ? "更新" : "登録";
$this->assign('title', "ガシャ{$button_name}");
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($gasha) ?>
      <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'start-date-datepicker', 'label' => 'ガシャ開始日', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('start_date', [
              'type' => 'text',
              'id' => 'start-date-datepicker',
              'required' => false,
              'error' => false,
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'data-toggle' => 'datetimepicker',
              'data-target' => '#start-date-datepicker',
              'value' => $gasha?->start_date?->i18nFormat('yyyy-MM-dd')
            ]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'end-date-datepicker', 'label' => 'ガシャ終了日', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('end_date', [
              'type' => 'text',
              'id' => 'end-date-datepicker',
              'required' => false,
              'error' => false,
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'data-toggle' => 'datetimepicker',
              'data-target' => '#end-date-datepicker',
              'value' => $gasha?->end_date?->i18nFormat('yyyy-MM-dd')
            ]); ?>
          </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'title', 'label' => 'ガシャタイトル', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('title', [
              'type' => 'text',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false
            ]); ?>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'ssr_rate', 'label' => 'SSRレート', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="input number">
              <div class="input-group input-group-sm">
                <?= $this->Form->text('ssr_rate', [
                  'type' => 'number',
                  'id' => 'ssr_rate',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => false,
                  'min' => '0',
                  'max' => '100',
                  'step' => '1',
                  'required' => false,
                  'error' => false
                ]); ?>
                <div class="input-group-text">%</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'sr_rate', 'label' => 'SRレート', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <div class="input number">
              <div class="input-group input-group-sm">
                <?= $this->Form->text('sr_rate', [
                  'type' => 'number',
                  'id' => 'sr_rate',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => false,
                  'min' => '0',
                  'max' => '100',
                  'step' => '1',
                  'required' => false,
                  'error' => false
                ]); ?>
                <div class="input-group-text">%</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <?= $this->Form->button($button_name, ['class' => "btn btn-sm btn-flat btn-outline-secondary"]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/gashas_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
