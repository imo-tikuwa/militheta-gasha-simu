<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint[] $card_reprints
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "復刻情報");
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
          <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-md-inline" href="javascript:void(0);" data-toggle="modal" data-target="#card_reprints-search-form-modal">検索</a>
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
            <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#card_reprints-search-form-modal">検索</a>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
            <?php } ?>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EXCEL_EXPORT])) { ?>
              <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_EXCEL_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">Excelエクスポート</a>
            <?php } ?>
          </div>
        </div>
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'card_reprints-freeword-search-form']) ?>
          <div class="freeword-search input-group input-group-sm d-none d-md-flex">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <?= $this->Form->control('search_snippet_format', ['type' => 'radio', 'options' => _code('Others.search_snippet_format'), 'class' => 'form-check-label col-form-label col-form-label-sm card_reprints-freeword-search-snippet-format', 'default' => 'AND', 'label' => false, 'templates' => ['nestingLabel' => '{{hidden}}{{input}}<small><label {{attrs}}>{{text}}</label></small>', 'radioWrapper' => '{{label}}', 'inputContainer' => '{{content}}']]) ?>
              </div>
            </div>
            <?= $this->Form->text('search_snippet', ['id' => 'card_reprints-freeword-search-snippet', 'class' => 'form-control form-control-sm rounded-0', 'style' => 'width: 200px;', 'placeholder' => 'フリーワード']) ?>
            <div class="input-group-append">
              <button type="submit" id="card_reprints-freeword-search-btn" class="btn btn-sm btn-flat btn-outline-secondary"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <?= $this->Form->hidden('sort') ?>
          <?= $this->Form->hidden('direction') ?>
        <?= $this->Form->end(); ?>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm table-hover text-sm text-nowrap">
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
            <?php foreach ($card_reprints as $card_reprint) { ?>
              <tr>
                <td><?= $this->Html->link($card_reprint->id, ['action' => ACTION_VIEW, $card_reprint->id]) ?></td>
                <td><?= $card_reprint->has('gasha') ? $this->Html->link($card_reprint->gasha->title, ['controller' => 'Gashas', 'action' => ACTION_VIEW, $card_reprint->gasha->id]) : '' ?></td>
                <td><?= $card_reprint->has('card') ? $this->Html->link($card_reprint->card->name, ['controller' => 'Cards', 'action' => ACTION_VIEW, $card_reprint->card->id]) : '' ?></td>
                <td><?= h($card_reprint?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
                <td class="actions">
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                    <?= $this->Html->link('<i title="詳細" class="far fa-list-alt mr-1"></i>', ['action' => ACTION_VIEW, $card_reprint->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                    <?= $this->Html->link('<i title="編集" class="fas fa-pen mr-1"></i>', ['action' => ACTION_EDIT, $card_reprint->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_DELETE])) { ?>
                    <?= $this->Form->postLink('<i title="削除" class="fas fa-trash"></i>', ['action' => ACTION_DELETE, $card_reprint->id], ['escape' => false, 'method' => 'delete', 'confirm' => __('ID {0} を削除します。よろしいですか？', $card_reprint->id)]) ?>
                  <?php } ?>
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

<div class="modal search-form fade" id="card_reprints-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="card_reprints-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">復刻情報検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'card_reprints-search-form']) ?>
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
                <?= $this->Form->control('gasha_id', [
                  'id' => 'gasha-id',
                  'type' => 'select',
                  'options' => $gasha_id_list,
                  'class' => 'form-control form-control-sm',
                  'label' => 'ガシャID',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('card_id', [
                  'id' => 'card-id',
                  'type' => 'select',
                  'options' => $card_id_list,
                  'class' => 'form-control form-control-sm',
                  'label' => 'カードID',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <label for="search_snippet" class="col-form-label col-form-label-sm">フリーワード</label>
                <div class="freeword-search form-inline input-group input-group-sm">
                  <div class="input-group-prepend">
                    <div class="input-group-text">
                      <?= $this->Form->control('search_snippet_format', [
                        'id' => 'modal-search_snippet-format',
                        'type' => 'radio',
                        'options' => _code('Others.search_snippet_format'),
                        'class' => 'form-check-label col-form-label col-form-label-sm',
                        'label' => false,
                        'default' => 'AND',
                        'templates' => [
                          'nestingLabel' => '{{hidden}}{{input}}<small><label {{attrs}}>{{text}}</label></small>',
                          'radioWrapper' => '{{label}}',
                          'inputContainer' => '{{content}}'
                        ],
                      ]) ?>
                    </div>
                  </div>
                  <?= $this->Form->text('search_snippet', [
                    'class' => 'form-control form-control-sm rounded-0',
                  ]) ?>
                </div>
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

<?= $this->Html->script('admin/card_reprints_index', ['block' => true, 'charset' => 'UTF-8']) ?>
