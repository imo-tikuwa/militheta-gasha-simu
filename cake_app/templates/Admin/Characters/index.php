<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Character[] $characters
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "キャラクター");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <div class="form-inline">
        <div class="btn-group mr-2" role="group">
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
          <?php } ?>
          <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="javascript:void(0);" data-toggle="modal" data-target="#characters-search-form-modal">検索</a>
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
          <?php } ?>
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EXCEL_EXPORT])) { ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="<?= $this->Url->build(['action' => ACTION_EXCEL_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">Excelエクスポート</a>
          <?php } ?>
          <a class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle d-md-none" href="#" role="button" id="sp-action-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">アクション</a>
          <div class="dropdown-menu" aria-labelledby="sp-action-link">
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
            <?php } ?>
            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#characters-search-form-modal">検索</a>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
            <?php } ?>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EXCEL_EXPORT])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_EXCEL_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">Excelエクスポート</a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover text-sm text-nowrap">
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
                <td><?= h($this->formatDate($character->modified, 'yyyy/MM/dd HH:mm:ss')) ?></td>
                <td class="actions">
                  <div class="btn-group" role="group">
                    <button id="btnGroupDrop<?= $character->id ?>" type="button" class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle index-dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $character->id ?>">
                      <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                        <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $character->id], ['class' => 'dropdown-item']) ?>
                      <?php } ?>
                      <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
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
  </div>
  <?= $this->element('pager') ?>
</div>

<div class="modal search-form fade" id="characters-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="characters-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">キャラクター検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'characters-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('id', [
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'ID',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('name', [
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '名前',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button('検索', ['class' => 'btn btn-sm btn-flat btn-outline-secondary btn-block']) ?>
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

