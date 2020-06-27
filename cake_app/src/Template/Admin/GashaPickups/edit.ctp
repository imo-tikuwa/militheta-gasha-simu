<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup $gasha_pickup
 */
$button_name = (!empty($gasha_pickup) && !$gasha_pickup->isNew()) ? "更新" : "登録";
$this->assign('title', "ピックアップ情報{$button_name}");
if ($gasha_pickup->hasErrors()) {
  $this->assign('validation_error', $this->makeValidationErrorHtml($gasha_pickup->getErrors()));
}
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($gasha_pickup) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'gasha_id', 'label' => 'ガシャID', 'require' => true]); ?>
            <?= $this->Form->control('gasha_id', ['id' => 'gasha_id', 'type' => 'select', 'class' => 'form-control rounded-0 ', 'label' => false, 'required' => false, 'error' => false]); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'card_id', 'label' => 'カードID', 'require' => true]); ?>
            <?= $this->Form->control('card_id', ['id' => 'card_id', 'type' => 'select', 'class' => 'form-control rounded-0 ', 'label' => false, 'required' => false, 'error' => false]); ?>
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

<?= $this->Html->script('admin/gasha_pickups_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
