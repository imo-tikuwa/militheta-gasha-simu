<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint $card_reprint
 */
$button_name = (!empty($card_reprint) && !$card_reprint->isNew()) ? "更新" : "登録";
$this->assign('title', "復刻情報{$button_name}");
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($card_reprint) ?>
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

<?= $this->Html->script('admin/card_reprints_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
