<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint[]|\Cake\Collection\CollectionInterface $card_reprints
 */
$this->assign('title', "復刻情報");
$this->Form->setTemplates([
  'label' => '<label class="col-form-label col-form-label-sm"{{attrs}}>{{text}}</label>',
]);
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_ADD])) { ?>
        <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_ADD]) ?>'">新規登録</button>
      <?php } ?>
      <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" data-toggle="modal" data-target="#card_reprints-search-form-modal">検索</button>
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_CSV_EXPORT])) { ?>
        <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
      <?php } ?>
    </div>
    <div class="card-body table-responsive p-0">
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
              <td>
                <?= $card_reprint->has('gasha') ? $this->Html->link($card_reprint->gasha->title, ['controller' => 'Gashas', 'action' => ACTION_VIEW, $card_reprint->gasha->id]) : '' ?>
              </td>
              <td>
                <?= $card_reprint->has('card') ? $this->Html->link($card_reprint->card->name, ['controller' => 'Cards', 'action' => ACTION_VIEW, $card_reprint->card->id]) : '' ?>
              </td>
              <td>
                <?php if (!is_null($card_reprint->modified)) { ?>
                  <?= h($card_reprint->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                <?php } ?>
              </td>
              <td class="actions">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $card_reprint->id ?>" type="button" class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle index-dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $card_reprint->id ?>">
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_VIEW])) { ?>
                      <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $card_reprint->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_EDIT])) { ?>
                      <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $card_reprint->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_DELETE])) { ?>
                      <?= $this->Form->postLink('削除', ['action' => ACTION_DELETE, $card_reprint->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $card_reprint->id)]) ?>
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

<div class="modal search-form fade" id="card_reprints-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="card_reprints-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">復刻情報検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'card_reprints-search-form']) ?>
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
                <?= $this->Form->control('gasha_id', ['id' => 'gasha_id', 'type' => 'select', 'options' => $gashas, 'class' => 'form-control form-control-sm', 'label' => 'ガシャID', 'value' => @$params['gasha_id']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('card_id', ['id' => 'card_id', 'type' => 'select', 'options' => $cards, 'class' => 'form-control form-control-sm', 'label' => 'カードID', 'value' => @$params['card_id']]); ?>
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

<?= $this->Html->script('admin/card_reprints_index', ['block' => true, 'charset' => 'UTF-8']) ?>
