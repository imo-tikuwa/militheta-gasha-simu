<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card[] $cards
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "カード");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header bg-body">
      <div class="row">
        <div class="col-auto">
          <div class="btn-group" role="group">
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
            <?php } ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#cards-search-form-modal">検索</a>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
            <?php } ?>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_IMPORT])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary csv-import-btn d-none d-lg-inline" href="javascript:void(0);">CSVインポート</a>
              <?= $this->Form->create(null, ['id' => 'csv-import-form', 'url' => ['action' => ACTION_CSV_IMPORT], 'enctype' => 'multipart/form-data', 'style' => 'display:none;']) ?>
                <input type="file" name="csv_import_file" id="csv-import-file" accept=".csv"/>
              <?= $this->Form->end(); ?>
            <?php } ?>
            <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EXCEL_EXPORT])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary d-none d-lg-inline" href="<?= $this->Url->build(['action' => ACTION_EXCEL_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">Excelエクスポート</a>
            <?php } ?>
            <a class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle d-lg-none" href="#" role="button" id="sp-action-link" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">アクション</a>
            <div class="dropdown-menu" aria-labelledby="sp-action-link">
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
                <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_ADD]) ?>">新規登録</a>
              <?php } ?>
              <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#cards-search-form-modal">検索</a>
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
                <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">CSVエクスポート</a>
              <?php } ?>
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_IMPORT])) { ?>
                <a class="dropdown-item csv-import-btn" href="javascript:void(0);">CSVインポート</a>
              <?php } ?>
              <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EXCEL_EXPORT])) { ?>
                <a class="dropdown-item" href="<?= $this->Url->build(['action' => ACTION_EXCEL_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>">Excelエクスポート</a>
              <?php } ?>
            </div>
          </div>
        </div>
        <div class="col">
          <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'cards-freeword-search-form']) ?>
            <div class="freeword-search input-group input-group-sm d-none d-xl-flex">
              <div class="input-group-text">
                <?= $this->Form->control('search_snippet_format', ['type' => 'radio', 'options' => _code('Others.search_snippet_format'), 'class' => 'form-check-label col-form-label col-form-label-sm cards-freeword-search-snippet-format', 'default' => 'AND', 'label' => false, 'templates' => ['nestingLabel' => '{{hidden}}{{input}}<small><label {{attrs}}>{{text}}</label></small>', 'radioWrapper' => '{{label}}', 'inputContainer' => '{{content}}']]) ?>
              </div>
              <?= $this->Form->text('search_snippet', ['id' => 'cards-freeword-search-snippet', 'class' => 'form-control form-control-sm rounded-0 border-start-0 border-end-0', 'style' => 'flex-grow: 0; flex-basis: 200px;', 'placeholder' => 'フリーワード']) ?>
              <button type="submit" id="cards-freeword-search-btn" class="btn btn-sm btn-flat btn-outline-secondary"><i class="fas fa-search"></i></button>
            </div>
            <?= $this->Form->hidden('sort') ?>
            <?= $this->Form->hidden('direction') ?>
          <?= $this->Form->end(); ?>
        </div>
      </div>
    </div>
    <div class="card-body p-0 d-grid">
      <div class="table-responsive">
        <table class="table table-sm table-hover text-sm text-nowrap">
          <thead>
            <tr>
              <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
              <th scope="col"><?= $this->Paginator->sort('character_id', 'キャラクター') ?></th>
              <th scope="col"><?= $this->Paginator->sort('name', 'カード名') ?></th>
              <th scope="col"><?= $this->Paginator->sort('rarity', 'レアリティ') ?></th>
              <th scope="col"><?= $this->Paginator->sort('type', 'タイプ') ?></th>
              <th scope="col"><?= $this->Paginator->sort('add_date', '実装日') ?></th>
              <th scope="col"><?= $this->Paginator->sort('gasha_include', 'ガシャ対象？') ?></th>
              <th scope="col"><?= $this->Paginator->sort('limited', '限定？') ?></th>
              <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
              <th scope="col" class="actions">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cards as $card) { ?>
              <tr>
                <td><?= $this->Html->link($card->id, ['action' => ACTION_VIEW, $card->id]) ?></td>
                <td><?= $card->has('character') ? $this->Html->link($card->character->name, ['controller' => 'Characters', 'action' => ACTION_VIEW, $card->character->id]) : '' ?></td>
                <td><?= h($card->name) ?></td>
                <td><?= @h(_code("Codes.Cards.rarity.{$card->rarity}")) ?></td>
                <td><?= @h(_code("Codes.Cards.type.{$card->type}")) ?></td>
                <td><?= h($card?->add_date?->i18nFormat('yyyy/MM/dd')) ?></td>
                <td><?= @h(_code('Codes.Cards.gasha_include.' . (int)$card->gasha_include)) ?></td>
                <td><?= @h(_code("Codes.Cards.limited.{$card->limited}")) ?></td>
                <td><?= h($card?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
                <td class="actions">
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                    <?= $this->Html->link('<i title="詳細" class="far fa-list-alt me-1"></i>', ['action' => ACTION_VIEW, $card->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                    <?= $this->Html->link('<i title="編集" class="fas fa-pen me-1"></i>', ['action' => ACTION_EDIT, $card->id], ['escape' => false]) ?>
                  <?php } ?>
                  <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_DELETE])) { ?>
                    <?= $this->Form->postLink('<i title="削除" class="fas fa-trash"></i>', ['action' => ACTION_DELETE, $card->id], ['escape' => false, 'method' => 'delete', 'confirm' => __('ID {0} を削除します。よろしいですか？', $card->id)]) ?>
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

<div class="modal search-form fade" id="cards-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="cards-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">カード検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'cards-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
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
              <div class="mb-3">
                <?= $this->Form->control('character_id', [
                  'id' => 'character-id',
                  'type' => 'select',
                  'options' => $character_id_list,
                  'class' => 'form-control form-control-sm',
                  'label' => 'キャラクター',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('name', [
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'カード名',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('rarity', [
                  'id' => 'rarity',
                  'type' => 'select',
                  'options' => _code('Codes.Cards.rarity'),
                  'class' => 'form-control form-control-sm',
                  'label' => 'レアリティ',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('type', [
                  'id' => 'type',
                  'type' => 'select',
                  'options' => _code('Codes.Cards.type'),
                  'class' => 'form-control form-control-sm',
                  'label' => 'タイプ',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('add_date', [
                  'id' => 'add-date-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => '実装日',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#add-date-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('gasha_include', [
                  'id' => 'gasha-include',
                  'type' => 'select',
                  'options' => _code('Codes.Cards.gasha_include'),
                  'class' => 'form-control form-control-sm',
                  'label' => 'ガシャ対象？',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <?= $this->Form->control('limited', [
                  'id' => 'limited',
                  'type' => 'select',
                  'options' => _code('Codes.Cards.limited'),
                  'class' => 'form-control form-control-sm',
                  'label' => '限定？',
                  'empty' => '　',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="mb-3">
                <label for="search_snippet" class="col-form-label col-form-label-sm">フリーワード</label>
                <div class="freeword-search form-inline input-group input-group-sm">
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
                  <?= $this->Form->text('search_snippet', [
                    'class' => 'form-control form-control-sm rounded-0',
                  ]) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3 d-grid">
                <?= $this->Form->button('検索', ['class' => 'btn btn-sm btn-flat btn-outline-secondary']) ?>
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

<?= $this->Html->script('admin/cards_index', ['block' => true, 'charset' => 'UTF-8']) ?>
