<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup[]|\Cake\Collection\CollectionInterface $gasha_pickups
 */
$this->assign('title', "ピックアップ情報");
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_ADD])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_ADD]) ?>'">新規登録</button>
      <?php } ?>
      <button type="button" class="btn btn-flat btn-outline-secondary" data-toggle="modal" data-target="#gasha_pickups-search-form-modal">検索</button>
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_CSV_EXPORT])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
      <?php } ?>
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
              <td><?= $this->Html->link($gasha_pickup->id, ['action' => ACTION_VIEW, $gasha_pickup->id]) ?></td>
              <td>
                <?= $gasha_pickup->has('gasha') ? $this->Html->link($gasha_pickup->gasha->title, ['controller' => 'Gashas', 'action' => ACTION_VIEW, $gasha_pickup->gasha->id]) : '' ?>
              </td>
              <td>
                <?= $gasha_pickup->has('card') ? $this->Html->link($gasha_pickup->card->name, ['controller' => 'Cards', 'action' => ACTION_VIEW, $gasha_pickup->card->id]) : '' ?>
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
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_VIEW])) { ?>
                      <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $gasha_pickup->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_EDIT])) { ?>
                      <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $gasha_pickup->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_DELETE])) { ?>
                      <?= $this->Form->postLink('削除', ['action' => ACTION_DELETE, $gasha_pickup->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $gasha_pickup->id)]) ?>
                    <?php } ?>
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
                <?= $this->Form->control('gasha_id', ['type' => 'select', 'options' => $gashas, 'class' => 'form-control select2', 'label' => 'ガシャID', 'value' => @$params['gasha_id']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('card_id', ['type' => 'select', 'options' => $cards, 'class' => 'form-control select2', 'label' => 'カードID', 'value' => @$params['card_id']]); ?>
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
          <?= $this->Form->hidden('sort', ['value' => @$params['sort']]) ?>
          <?= $this->Form->hidden('direction', ['value' => @$params['direction']]) ?>
        <?= $this->Form->end() ?>
      </div>
      <div class="modal-footer">　</div>
    </div>
  </div>
</div>

