<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[] $accounts
 */

$this->assign('title', "アカウント/権限");
// $text_class = '';
$table_class = 'table table-hover text-sm text-nowrap';
// $input_class = '';
$btn_class = 'btn btn-sm btn-flat btn-outline-secondary';
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <div class="form-inline">
        <button type="button" class="<?= h($btn_class) ?>" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">新規登録</button>
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


