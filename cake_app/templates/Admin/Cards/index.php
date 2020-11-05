<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card[]|\Cake\Collection\CollectionInterface $cards
 */
$this->assign('title', "カード");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <div class="form-inline">
        <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_ADD])) { ?>
          <button type="button" class="btn btn-sm btn-flat btn-outline-secondary mr-2" onclick="location.href='<?= $this->Url->build(['action' => ACTION_ADD]) ?>'">新規登録</button>
        <?php } ?>
        <button type="button" class="btn btn-sm btn-flat btn-outline-secondary mr-2" data-toggle="modal" data-target="#cards-search-form-modal">検索</button>
        <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
          <button type="button" class="btn btn-sm btn-flat btn-outline-secondary mr-2" onclick="location.href='<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>'">CSVエクスポート</button>
        <?php } ?>
        <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_IMPORT])) { ?>
          <button type="button" class="btn btn-sm btn-flat btn-outline-secondary mr-2" id="csv-import-btn">CSVインポート</button>
          <?= $this->Form->create(null, ['id' => 'csv-import-form', 'url' => ['action' => ACTION_CSV_IMPORT], 'enctype' => 'multipart/form-data', 'style' => 'display:none;']) ?>
            <input type="file" name="csv_import_file" id="csv-import-file"/>
          <?= $this->Form->end(); ?>
        <?php } ?>
        <div class="freeword-search input-group input-group-sm">
          <div class="input-group-prepend">
            <div class="input-group-text">
              <?= $this->Form->control('search_snippet_format', ['type' => 'radio', 'options' => _code('Others.search_snippet_format'), 'class' => 'form-check-label col-form-label col-form-label-sm cards-freeword-search-snippet-format', 'default' => 'AND', 'value' => @$params['search_snippet_format'], 'label' => false, 'templates' => ['nestingLabel' => '{{hidden}}{{input}}<small><label {{attrs}}>{{text}}</label></small>', 'radioWrapper' => '{{label}}', 'inputContainer' => '{{content}}']]) ?>
            </div>
          </div>
          <?= $this->Form->text('search_snippet', ['id' => 'cards-freeword-search-snippet', 'class' => 'form-control form-control-sm rounded-0', 'value' => @$params['search_snippet'], 'style' => 'width: 200px;', 'placeholder' => 'フリーワード']) ?>
          <div class="input-group-append">
            <button type="button" id="cards-freeword-search-btn" class="btn btn-sm btn-flat btn-outline-secondary"><i class="fas fa-search"></i></button>
          </div>
        </div>
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'cards-freeword-search-form', 'class' => 'd-none']) ?>
          <?= $this->Form->hidden('search_snippet', ['id' => 'cards-freeword-hidden-search-snippet', 'value' => @$params['search_snippet']]) ?>
          <?= $this->Form->hidden('search_snippet_format', ['id' => 'cards-freeword-hidden-search-snippet-format', 'value' => @$params['search_snippet_format']]) ?>
          <?= $this->Form->hidden('sort', ['value' => @$params['sort']]) ?>
          <?= $this->Form->hidden('direction', ['value' => @$params['direction']]) ?>
        <?= $this->Form->end(); ?>
      </div>
    </div>
    <div class="card-body table-responsive p-0">
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
              <td>
                <?= $card->has('character') ? $this->Html->link($card->character->name, ['controller' => 'Characters', 'action' => ACTION_VIEW, $card->character->id]) : '' ?>
              </td>
              <td><?= h($card->name) ?></td>
              <td><?= @h(_code("Codes.Cards.rarity.{$card->rarity}")) ?></td>
              <td><?= @h(_code("Codes.Cards.type.{$card->type}")) ?></td>
              <td>
                <?php if (!is_null($card->add_date)) { ?>
                  <?= h($card->add_date->i18nFormat('yyyy/MM/dd')) ?>
                <?php } ?>
              </td>
              <td><?= @h(_code("Codes.Cards.gasha_include.{$card->gasha_include}")) ?></td>
              <td><?= @h(_code("Codes.Cards.limited.{$card->limited}")) ?></td>
              <td>
                <?php if (!is_null($card->modified)) { ?>
                  <?= h($card->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                <?php } ?>
              </td>
              <td class="actions">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $card->id ?>" type="button" class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle index-dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $card->id ?>">
                    <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                      <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $card->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                      <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $card->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_DELETE])) { ?>
                      <?= $this->Form->postLink('削除', ['action' => ACTION_DELETE, $card->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $card->id)]) ?>
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
                <?= $this->Form->control('id', ['class' => 'form-control form-control-sm rounded-0', 'label' => 'ID', 'value' => @$params['id']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('character_id', ['id' => 'character_id', 'type' => 'select', 'options' => $characters, 'class' => 'form-control form-control-sm', 'label' => 'キャラクター', 'value' => @$params['character_id']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('name', ['class' => 'form-control form-control-sm rounded-0', 'label' => 'カード名', 'value' => @$params['name']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('rarity', ['id' => 'rarity', 'type' => 'select', 'options' => _code('Codes.Cards.rarity'), 'class' => 'form-control form-control-sm', 'label' => 'レアリティ', 'empty' => '　', 'value' => @$params['rarity']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('type', ['id' => 'type', 'type' => 'select', 'options' => _code('Codes.Cards.type'), 'class' => 'form-control form-control-sm', 'label' => 'タイプ', 'empty' => '　', 'value' => @$params['type']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('add_date', ['type' => 'text', 'id' => 'add_date-datepicker', 'class' => 'form-control form-control-sm rounded-0', 'label' => '実装日', 'data-toggle' => 'datetimepicker', 'data-target' => '#add_date-datepicker', 'value' => @$params['add_date']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('gasha_include', ['id' => 'gasha_include', 'type' => 'select', 'options' => _code('Codes.Cards.gasha_include'), 'class' => 'form-control form-control-sm', 'label' => 'ガシャ対象？', 'empty' => '　', 'value' => @$params['gasha_include']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('limited', ['id' => 'limited', 'type' => 'select', 'options' => _code('Codes.Cards.limited'), 'class' => 'form-control form-control-sm', 'label' => '限定？', 'empty' => '　', 'value' => @$params['limited']]); ?>
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
                      <?= $this->Form->control('search_snippet_format', ['type' => 'radio', 'id' => 'modal-search_snippet-format', 'options' => _code('Others.search_snippet_format'), 'class' => 'form-check-label col-form-label col-form-label-sm', 'default' => 'AND', 'value' => @$params['search_snippet_format'], 'label' => false, 'templates' => ['nestingLabel' => '{{hidden}}{{input}}<small><label {{attrs}}>{{text}}</label></small>', 'radioWrapper' => '{{label}}', 'inputContainer' => '{{content}}']]) ?>
                    </div>
                  </div>
                  <?= $this->Form->text('search_snippet', ['class' => 'form-control form-control-sm rounded-0', 'value' => @$params['search_snippet']]) ?>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <?= $this->Form->button('検索', ['class' => "btn btn-sm btn-flat btn-outline-secondary btn-block"]) ?>
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

<?= $this->Html->script('admin/cards_index', ['block' => true, 'charset' => 'UTF-8']) ?>
