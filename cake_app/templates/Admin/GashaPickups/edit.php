<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup $gasha_pickup
 */
$button_name = (!empty($gasha_pickup) && !$gasha_pickup->isNew()) ? "更新" : "登録";
$this->assign('title', "ピックアップ情報{$button_name}");
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($gasha_pickup) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'gasha_id', 'label' => 'ガシャID', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('gasha_id', [
              'id' => 'gasha-id',
              'type' => 'select',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false,
              'options' => $gasha_id_list,
              'empty' => '　'
            ]); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'card_id', 'label' => 'カードID', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('card_id', [
              'id' => 'card-id',
              'type' => 'select',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false,
              'options' => $card_id_list,
              'empty' => '　'
            ]); ?>
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

<?= $this->Html->script('admin/gasha_pickups_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
