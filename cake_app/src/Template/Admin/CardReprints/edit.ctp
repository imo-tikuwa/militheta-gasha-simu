<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint $card_reprint
 */
$button_name = (!empty($card_reprint) && !$card_reprint->isNew()) ? "更新" : "登録";
$this->assign('title', "復刻情報{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($card_reprint) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('gasha_id', ['type' => 'select', 'class' => 'form-control rounded-0 ', 'label' => 'ガシャID']); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('card_id', ['type' => 'select', 'class' => 'form-control rounded-0 ', 'label' => 'カードID']); ?>
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
