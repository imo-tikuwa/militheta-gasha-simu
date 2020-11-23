<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint $card_reprint
 */
$button_name = (!empty($card_reprint) && !$card_reprint->isNew()) ? "更新" : "登録";
$this->assign('title', "復刻情報{$button_name}");
if ($card_reprint->hasErrors()) {
  $this->assign('validation_error', $this->makeValidationErrorHtml($card_reprint->getErrorMessages(), 'text-sm'));
}
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($card_reprint) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'gasha_id', 'label' => 'ガシャID', 'require' => true, 'class' => 'item-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('gasha_id', ['id' => 'gasha_id', 'type' => 'select', 'class' => 'form-control form-control-sm rounded-0 ', 'label' => false, 'required' => false, 'error' => false, 'empty' => '　']); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'card_id', 'label' => 'カードID', 'require' => true, 'class' => 'item-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('card_id', ['id' => 'card_id', 'type' => 'select', 'class' => 'form-control form-control-sm rounded-0 ', 'label' => false, 'required' => false, 'error' => false, 'empty' => '　']); ?>
          </div>
        </div>
        <div class="col-md-12">
          <?= $this->Form->button($button_name, ['class' => "btn btn-sm btn-flat btn-outline-secondary"]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/card_reprints_edit', ['block' => true, 'charset' => 'UTF-8']) ?>