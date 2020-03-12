<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin[]|\Cake\Collection\CollectionInterface $accounts
 */
$this->assign('title', "アカウント/権限");
?>
<div class="col-md-12 mb-12">
 <div class="card">
  <div class="card-header">
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">新規登録</button>
  </div>
  <div class="card-body table-responsive p-0">
   <table class="table table-hover">
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
                <td>
                  <?php if (!is_null($account->created)) { ?>
                    <?= h($account->created->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                  <?php } ?>
                </td>
                <td>
                  <?php if (!is_null($account->modified)) { ?>
                    <?= h($account->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                  <?php } ?>
                </td>
                <td class="actions">
                  <?= $this->Html->link('編集', ['action' => 'edit', $account->id], ['class' => 'btn btn-flat btn-outline-secondary']) ?>
                </td>
            </tr>
          <?php } ?>
    </tbody>
   </table>
  </div>
 </div>
 <?= $this->element('pager') ?>
</div>


