<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup $gasha_pickup
 */
$button_name = (!empty($gasha_pickup) && !$gasha_pickup->isNew()) ? "更新" : "登録";
$this->assign('title', "ピックアップ情報{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($gasha_pickup) ?>
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('gasha_id', ['type' => 'select', 'class' => 'form-control rounded-0 ', 'label' => 'ガシャID']); ?>
          </div>
        </div>
        <div class="col-md-6 col-sm-12">
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
