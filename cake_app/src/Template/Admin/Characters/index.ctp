<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Character[]|\Cake\Collection\CollectionInterface $characters
 */
$this->assign('title', "キャラクター");
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_ADD])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_ADD]) ?>'">新規登録</button>
      <?php } ?>
      <button type="button" class="btn btn-flat btn-outline-secondary" data-toggle="modal" data-target="#characters-search-form-modal">検索</button>
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_CSV_EXPORT])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
      <?php } ?>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
            <th scope="col"><?= $this->Paginator->sort('name', '名前') ?></th>
            <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
            <th scope="col" class="actions">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($characters as $character) { ?>
            <tr>
              <td><?= $this->Html->link($character->id, ['action' => ACTION_VIEW, $character->id]) ?></td>
              <td><?= h($character->name) ?></td>
              <td>
                <?php if (!is_null($character->modified)) { ?>
                  <?= h($character->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                <?php } ?>
              </td>
              <td class="actions">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $character->id ?>" type="button" class="btn btn-flat btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $character->id ?>">
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_VIEW])) { ?>
                      <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $character->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_EDIT])) { ?>
                      <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $character->id], ['class' => 'dropdown-item']) ?>
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

<div class="modal search-form fade" id="characters-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="characters-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">キャラクター検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'characters-search-form']) ?>
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
                <?= $this->Form->control('name', ['class' => 'form-control rounded-0', 'label' => '名前', 'value' => @$params['name']]); ?>
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

