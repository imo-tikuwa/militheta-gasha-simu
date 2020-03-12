<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha[]|\Cake\Collection\CollectionInterface $gashas
 */
$this->assign('title', "ガシャ");
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-header">
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_ADD])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_ADD]) ?>'">新規登録</button>
      <?php } ?>
      <button type="button" class="btn btn-flat btn-outline-secondary" data-toggle="modal" data-target="#gashas-search-form-modal">検索</button>
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_CSV_EXPORT])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
      <?php } ?>
      <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_CSV_IMPORT])) { ?>
        <button type="button" class="btn btn-flat btn-outline-secondary" onclick="$('#csv-import-file').trigger('click');">CSVインポート</button>
        <?= $this->Form->create(null, ['id' => 'csv-import-form', 'action' => ACTION_CSV_IMPORT, 'enctype' => 'multipart/form-data', 'style' => 'display:none;']) ?>
          <input type="file" name="csv_import_file" id="csv-import-file"/>
        <?= $this->Form->end(); ?>
      <?php } ?>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col"><?= $this->Paginator->sort('id', 'ID') ?></th>
            <th scope="col"><?= $this->Paginator->sort('start_date', 'ガシャ開始日') ?></th>
            <th scope="col"><?= $this->Paginator->sort('end_date', 'ガシャ終了日') ?></th>
            <th scope="col"><?= $this->Paginator->sort('title', 'ガシャタイトル') ?></th>
            <th scope="col"><?= $this->Paginator->sort('ssr_rate', 'SSRレート') ?></th>
            <th scope="col"><?= $this->Paginator->sort('sr_rate', 'SRレート') ?></th>
            <th scope="col"><?= $this->Paginator->sort('modified', '更新日時') ?></th>
            <th scope="col" class="actions">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($gashas as $gasha) { ?>
            <tr>
              <td><?= $this->Html->link($gasha->id, ['action' => ACTION_VIEW, $gasha->id]) ?></td>
              <td>
                <?php if (!is_null($gasha->start_date)) { ?>
                  <?= h($gasha->start_date->i18nFormat('yyyy/MM/dd')) ?>
                <?php } ?>
              </td>
              <td>
                <?php if (!is_null($gasha->end_date)) { ?>
                  <?= h($gasha->end_date->i18nFormat('yyyy/MM/dd')) ?>
                <?php } ?>
              </td>
              <td><?= h($gasha->title) ?></td>
              <td><?= $this->Number->format($gasha->ssr_rate) ?>%</td>
              <td><?= $this->Number->format($gasha->sr_rate) ?>%</td>
              <td>
                <?php if (!is_null($gasha->modified)) { ?>
                  <?= h($gasha->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
                <?php } ?>
              </td>
              <td class="actions">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $gasha->id ?>" type="button" class="btn btn-flat btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $gasha->id ?>">
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_VIEW])) { ?>
                      <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $gasha->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_EDIT])) { ?>
                      <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $gasha->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->request, ['action' => ACTION_DELETE])) { ?>
                      <?= $this->Form->postLink('削除', ['action' => ACTION_DELETE, $gasha->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $gasha->id)]) ?>
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

<div class="modal search-form fade" id="gashas-search-form-modal" tabindex="-1" role="dialog" aria-labelledby="gashas-search-form-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ガシャ検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create(null, ['type' => 'get', 'id' => 'gashas-search-form']) ?>
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
                <?= $this->Form->control('start_date', ['type' => 'text', 'id' => 'start_date-datepicker', 'class' => 'form-control rounded-0', 'label' => 'ガシャ開始日', 'data-toggle' => 'datetimepicker', 'data-target' => '#start_date-datepicker', 'value' => @$params['start_date']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('end_date', ['type' => 'text', 'id' => 'end_date-datepicker', 'class' => 'form-control rounded-0', 'label' => 'ガシャ終了日', 'data-toggle' => 'datetimepicker', 'data-target' => '#end_date-datepicker', 'value' => @$params['end_date']]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('title', ['class' => 'form-control rounded-0', 'label' => 'ガシャタイトル', 'value' => @$params['title']]); ?>
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

<?= $this->Html->script('admin/gashas_index', ['block' => true, 'charset' => 'UTF-8']) ?>
