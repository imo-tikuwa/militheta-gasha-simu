<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card[]|\Cake\Collection\CollectionInterface $cards
 */
$this->assign('title', "カード");
?>
<div class="col-md-12 mb-12">
 <div class="card">
  <div class="card-header">
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">新規登録</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" data-toggle="modal" data-target="#cards-search-form-modal">検索</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'csvExport', '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="$('#csv-import-file').trigger('click');">CSVインポート</button>
   <?= $this->Form->create(null, ['id' => 'csv-import-form', 'action' => 'csvImport', 'enctype' => 'multipart/form-data', 'style' => 'display:none;']) ?>
     <input type="file" name="csv_import_file" id="csv-import-file"/>
   <?= $this->Form->end(); ?>
   <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
   $(function(){
     $('#csv-import-file').on('change', function(){
       $('#csv-import-form').submit();
     });
   });
   <?= $this->Html->scriptEnd() ?>
  </div>
  <div class="card-body table-responsive p-0">
   <table class="table table-hover">
    <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th scope="col"><?= $this->Paginator->sort('character_id', 'キャラクター') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name', 'カード名') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rarity', 'レアリティ') ?></th>
                <th scope="col"><?= $this->Paginator->sort('type', 'タイプ') ?></th>
                <th scope="col"><?= $this->Paginator->sort('add_date', '実装日') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
                <th scope="col" class="actions">操作</th>
            </tr>
    </thead>
    <tbody>
            <?php foreach ($cards as $card) { ?>
            <tr>
                <td><?= $this->Html->link($card->id, ['action' => 'view', $card->id]) ?></td>
                <td>
                  <?= $card->has('character') ? $this->Html->link($card->character->name, ['controller' => 'Characters', 'action' => 'view', $card->character->id]) : '' ?>
                </td>
                <td><?= h($card->name) ?></td>
                <td><?= @h(_code("Cards.rarity.{$card->rarity}")) ?></td>
                <td><?= @h(_code("Cards.type.{$card->type}")) ?></td>
                <td>
                  <?php if (!is_null($card->add_date)) { ?>
                    <?= h($card->add_date->i18nFormat('yyyy/MM/dd')) ?>
                  <?php } ?>
                </td>
                <td>
                  <?php if (!is_null($card->modified)) { ?>
                    <?= h($card->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                  <?php } ?>
                </td>
                <td class="actions">
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop<?= $card->id ?>" type="button" class="btn btn-flat btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $card->id ?>">
                      <?= $this->Html->link('詳細', ['action' => 'view', $card->id], ['class' => 'dropdown-item']) ?>
                      <?= $this->Html->link('編集', ['action' => 'edit', $card->id], ['class' => 'dropdown-item']) ?>
                    </div>
                  </div>
                </td>
            </tr>
          <?php } ?>
    </tbody>
   </table>
  </div>
 </div>
 <?= $this->element('pager') ?>
</div>

<div class="modal search-form fade" id="cards-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="cards-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">カード検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'cards-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('id', ['class' => 'form-control rounded-0', 'label' => 'ID', 'value' => @$params['id']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('name', ['class' => 'form-control rounded-0', 'label' => 'カード名', 'value' => @$params['name']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('rarity', ['type' => 'select', 'options' => ["" => "　"] + _code('Cards.rarity'), 'class' => 'form-control', 'label' => 'レアリティ', 'value' => @$params['rarity']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('type', ['type' => 'select', 'options' => ["" => "　"] + _code('Cards.type'), 'class' => 'form-control', 'label' => 'タイプ', 'value' => @$params['type']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('add_date', ['type' => 'text', 'id' => 'add_date-datepicker', 'class' => 'form-control rounded-0', 'label' => '実装日', 'value' => @$params['add_date']]); ?>
                <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
                $(function(){
                  $('#add_date-datepicker').bootstrapMaterialDatePicker({
                    lang: 'ja',
                    nowButton: true,
                    clearButton: true,
                    format: 'YYYY-MM-DD',
                    time: false,
                  });
                });
                <?= $this->Html->scriptEnd() ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button('検索', ['class' => "btn btn-flat btn-outline-secondary btn-block"]) ?>
              </div>
            </div>
          </div>
        <?= $this->Form->end() ?>
      </div>
      <div class="modal-footer">　</div>
    </div>
  </div>
</div>
