<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup[]|\Cake\Collection\CollectionInterface $gasha_pickups
 */
$this->assign('title', "ピックアップ情報");
?>
<div class="col-md-12 mb-12">
 <div class="card">
  <div class="card-header">
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">新規登録</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" data-toggle="modal" data-target="#gasha_pickups-search-form-modal">検索</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'csvExport', '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
  </div>
  <div class="card-body table-responsive p-0">
   <table class="table table-hover">
    <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
                <th scope="col"><?= $this->Paginator->sort('gasha_id', 'ガシャID') ?></th>
                <th scope="col"><?= $this->Paginator->sort('card_id', 'カードID') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
                <th scope="col" class="actions">操作</th>
            </tr>
    </thead>
    <tbody>
            <?php foreach ($gasha_pickups as $gasha_pickup) { ?>
            <tr>
                <td><?= $this->Html->link($gasha_pickup->id, ['action' => 'view', $gasha_pickup->id]) ?></td>
                <td>
                  <?= $gasha_pickup->has('gasha') ? $this->Html->link($gasha_pickup->gasha->title, ['controller' => 'Gashas', 'action' => 'view', $gasha_pickup->gasha->id]) : '' ?>
                </td>
                <td>
                  <?= $gasha_pickup->has('card') ? $this->Html->link($gasha_pickup->card->name, ['controller' => 'Cards', 'action' => 'view', $gasha_pickup->card->id]) : '' ?>
                </td>
                <td>
                  <?php if (!is_null($gasha_pickup->modified)) { ?>
                    <?= h($gasha_pickup->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                  <?php } ?>
                </td>
                <td class="actions">
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop<?= $gasha_pickup->id ?>" type="button" class="btn btn-flat btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $gasha_pickup->id ?>">
                      <?= $this->Html->link('詳細', ['action' => 'view', $gasha_pickup->id], ['class' => 'dropdown-item']) ?>
                      <?= $this->Html->link('編集', ['action' => 'edit', $gasha_pickup->id], ['class' => 'dropdown-item']) ?>
                      <?= $this->Form->postLink('削除', ['action' => 'delete', $gasha_pickup->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $gasha_pickup->id)]) ?>
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

<div class="modal search-form fade" id="gasha_pickups-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="gasha_pickups-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ピックアップ情報検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'gasha_pickups-search-form']) ?>
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
                <?= $this->Form->control('gasha_id', ['type' => 'select', 'options' => ["" => "　"] + $gashas, 'class' => 'form-control', 'label' => 'ガシャID', 'value' => @$params['gasha_id']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('card_id', ['type' => 'select', 'options' => ["" => "　"] + $cards, 'class' => 'form-control', 'label' => 'カードID', 'value' => @$params['card_id']]); ?>
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
