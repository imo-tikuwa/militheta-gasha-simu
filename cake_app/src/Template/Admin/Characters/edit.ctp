<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Character $character
 */
$button_name = (!empty($character) && !$character->isNew()) ? "更新" : "登録";
$this->assign('title', "キャラクター{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($character) ?>
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <div class="form-group">
            <?= $this->Form->control('name', ['class' => 'form-control rounded-0 ', 'label' => '名前']); ?>
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
