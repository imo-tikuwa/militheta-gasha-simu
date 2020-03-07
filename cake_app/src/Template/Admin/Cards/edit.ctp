<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card $card
 */
$button_name = (!empty($card) && !$card->isNew()) ? "更新" : "登録";
$this->assign('title', "カード{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($card) ?>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('character_id', ['type' => 'select', 'class' => 'form-control select2 rounded-0 ', 'label' => 'キャラクター']); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('name', ['class' => 'form-control rounded-0 ', 'label' => 'カード名']); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('rarity', ['type' => 'select', 'options' => ["" => "　"] + _code('Cards.rarity'), 'class' => 'form-control select2 ', 'label' => 'レアリティ']); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('type', ['type' => 'select', 'options' => ["" => "　"] + _code('Cards.type'), 'class' => 'form-control select2 ', 'label' => 'タイプ']); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('add_date', ['type' => 'text', 'id' => 'add_date-datepicker', 'class' => 'form-control rounded-0 ', 'label' => '実装日', 'data-toggle' => 'datetimepicker', 'data-target' => '#add_date-datepicker', 'value' => $this->formatDate($card->add_date, 'yyyy-MM-dd')]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('gasha_include', ['type' => 'select', 'options' => _code('Cards.gasha_include'), 'default' => '1', 'class' => 'form-control select2 ', 'label' => 'ガシャ対象？']); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('limited', ['type' => 'select', 'options' => _code('Cards.limited'), 'default' => '0', 'class' => 'form-control select2 ', 'label' => '限定？']); ?>
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

<?= $this->Html->script('admin/cards_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
