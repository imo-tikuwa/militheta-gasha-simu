<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[] $accounts
 * @var \App\Form\SearchForm $search_form
 */

$this->assign('title', "アカウント/権限");
// $text_class = '';
$table_class = 'table table-hover text-sm text-nowrap';
$input_class = 'form-control form-control-sm rounded-0';
$btn_class = 'btn btn-sm btn-flat btn-outline-secondary';
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <div class="form-inline">
        <button type="button" class="<?= h($btn_class) ?> mr-2" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">新規登録</button>
        <button type="button" class="<?= h($btn_class) ?>" data-toggle="modal" data-target="#accounts-search-form-modal">検索</button>
      </div>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="<?= h($table_class) ?>">
        <thead>
          <tr>
            <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
            <th scope="col"><?= $this->Paginator->sort('mail', 'メールアドレス') ?></th>
            <th scope="col"><?= $this->Paginator->sort('privilege', '権限') ?></th>
            <th scope="col"><?= $this->Paginator->sort('created', '作成日時') ?></th>
            <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
            <th scope="col" class="actions">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($accounts as $account) { ?>
            <tr>
              <td><?= h($account->id) ?></td>
              <td><?= h($account->mail) ?></td>
              <td><?= $this->makePrivilegeListHtml($account->privilege)?></td>
              <td><?= h($this->formatDate($account->created, 'yyyy/MM/dd HH:mm:ss')) ?></td>
              <td><?= h($this->formatDate($account->modified, 'yyyy/MM/dd HH:mm:ss')) ?></td>
              <td class="actions">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $account->id ?>" type="button" class="<?= h($btn_class) ?> dropdown-toggle index-dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $account->id ?>">
                    <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $account->id], ['class' => 'dropdown-item']) ?>
                    <?= $this->Form->postLink('削除', ['action' => ACTION_DELETE, $account->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $account->id)]) ?>
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

<div class="modal search-form fade" id="accounts-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="tikuwa_estates-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">アカウント/権限検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'accounts-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('id', [
                  'class' => $input_class,
                  'label' => 'ID',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('mail', [
                  'class' => $input_class,
                  'label' => 'メールアドレス',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button('検索', ['class' => "{$btn_class} btn-block"]) ?>
              </div>
            </div>
          </div>
          <?= $this->Form->hidden('sort') ?>
          <?= $this->Form->hidden('direction') ?>
        <?= $this->Form->end() ?>
      </div>
      <div class="modal-footer">　</div>
    </div>
  </div>
</div>
