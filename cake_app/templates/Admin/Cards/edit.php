<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card $card
 */
$button_name = (!empty($card) && !$card->isNew()) ? "更新" : "登録";
$this->assign('title', "カード{$button_name}");
?>
<div class="col">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($card) ?>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'character_id', 'label' => 'キャラクター', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('character_id', [
              'id' => 'character-id',
              'type' => 'select',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false,
              'options' => $character_id_list,
              'empty' => '　'
            ]); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'name', 'label' => 'カード名', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('name', [
              'type' => 'text',
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'required' => false,
              'error' => false
            ]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'rarity', 'label' => 'レアリティ', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('rarity', [
              'id' => 'rarity',
              'type' => 'select',
              'options' => _code('Codes.Cards.rarity'),
              'class' => 'form-control form-control-sm ',
              'label' => false,
              'required' => false,
              'error' => false,
              'default' => '02',
              'empty' => '　'
            ]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'type', 'label' => 'タイプ', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('type', [
              'id' => 'type',
              'type' => 'select',
              'options' => _code('Codes.Cards.type'),
              'class' => 'form-control form-control-sm ',
              'label' => false,
              'required' => false,
              'error' => false,
              'default' => '01',
              'empty' => '　'
            ]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'add-date-datepicker', 'label' => '実装日', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('add_date', [
              'type' => 'text',
              'id' => 'add-date-datepicker',
              'required' => false,
              'error' => false,
              'class' => 'form-control form-control-sm rounded-0 ',
              'label' => false,
              'data-toggle' => 'datetimepicker',
              'data-target' => '#add-date-datepicker',
              'value' => $card?->add_date?->i18nFormat('yyyy-MM-dd')
            ]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'gasha_include', 'label' => 'ガシャ対象？', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('gasha_include', [
              'id' => 'gasha-include',
              'type' => 'select',
              'options' => _code('Codes.Cards.gasha_include'),
              'default' => true,
              'class' => 'form-control form-control-sm ',
              'label' => false,
              'required' => false,
              'error' => false,
              'empty' => false
            ]); ?>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
          <div class="mb-3">
            <?= $this->element('Parts/label', ['field' => 'limited', 'label' => '限定？', 'require' => true, 'class' => 'form-label col-form-label col-form-label-sm']); ?>
            <?= $this->Form->control('limited', [
              'id' => 'limited',
              'type' => 'select',
              'options' => _code('Codes.Cards.limited'),
              'class' => 'form-control form-control-sm ',
              'label' => false,
              'required' => false,
              'error' => false,
              'default' => '01',
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

<?= $this->Html->script('admin/cards_edit', ['block' => true, 'charset' => 'UTF-8']) ?>
