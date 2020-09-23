<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Character $character
 */
$button_name = (!empty($character) && !$character->isNew()) ? "更新" : "登録";
$this->assign('title', "キャラクター{$button_name}");
if ($character->hasErrors()) {
  $this->assign('validation_error', $this->makeValidationErrorHtml($character->getErrorMessages(), 'text-sm'));
}
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($character) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="form-group">
            <?= $this->element('Parts/label', ['field' => 'name', 'label' => '名前', 'require' => true, 'class' => 'item-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('name', ['type' => 'text', 'class' => 'form-control form-control-sm rounded-0 ', 'label' => false, 'required' => false, 'error' => false]); ?>
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

