<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha[]|\Cake\Collection\CollectionInterface $gashas
 */
$this->assign('title', "ガシャ");
?>
<div class="col-md-12 mb-12">
 <div class="card">
  <div class="card-header">
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'add']) ?>'">新規登録</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" data-toggle="modal" data-target="#gashas-search-form-modal">検索</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => 'csvExport', '?' => $this->request->getQueryParams()]) ?>'">CSVエクスポート</button>
   <button type="button" class="btn btn-flat btn-outline-secondary" onclick="$('#csv-import-file').trigger('click');">CSVインポート</button>
   <?= $this->Form->create(null, ['id' => 'csv-import-form', 'action' => 'csvImport', 'enctype' => 'multipart/form-data', 'style' => 'display:none;']) ?>
     <input type="file" name="csv_import_file" id="csv-import-file"/>
   <?= $this->Form->end(); ?>
   <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
   $(function(){
     $('#csv-import-file').on('change', function(){
       $('#csv-import-form').submit();
     });
   });
   <?= $this->Html->scriptEnd() ?>
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
                <td><?= $this->Html->link($gasha->id, ['action' => 'view', $gasha->id]) ?></td>
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
                      <?= $this->Html->link('詳細', ['action' => 'view', $gasha->id], ['class' => 'dropdown-item']) ?>
                      <?= $this->Html->link('編集', ['action' => 'edit', $gasha->id], ['class' => 'dropdown-item']) ?>
                      <?= $this->Form->postLink('削除', ['action' => 'delete', $gasha->id], ['class' => 'dropdown-item', 'confirm' => __('ID {0} を削除します。よろしいですか？', $gasha->id)]) ?>
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
                <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
                $(function(){
                  $('#start_date-datepicker').datetimepicker({
                    dayViewHeaderFormat: 'YYYY年 M月',
                    locale: 'ja',
                    buttons: {
                      showClear: true
                    },
                    icons: {
                      time: 'far fa-clock',
                      date: 'far fa-calendar-alt',
                      up: 'fas fa-arrow-up',
                      down: 'fas fa-arrow-down',
                      previous: 'fas fa-chevron-left',
                      next: 'fas fa-chevron-right',
                      today: 'far fa-calendar-alt',
                      clear: 'far fa-trash-alt',
                      close: 'fas fa-times'
                    },
                    format: 'YYYY-MM-DD',
                  });
                });
                <?= $this->Html->scriptEnd() ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('end_date', ['type' => 'text', 'id' => 'end_date-datepicker', 'class' => 'form-control rounded-0', 'label' => 'ガシャ終了日', 'data-toggle' => 'datetimepicker', 'data-target' => '#end_date-datepicker', 'value' => @$params['end_date']]); ?>
                <?= $this->Html->scriptStart(['block' => true, 'type' => 'text/javascript']) ?>
                $(function(){
                  $('#end_date-datepicker').datetimepicker({
                    dayViewHeaderFormat: 'YYYY年 M月',
                    locale: 'ja',
                    buttons: {
                      showClear: true
                    },
                    icons: {
                      time: 'far fa-clock',
                      date: 'far fa-calendar-alt',
                      up: 'fas fa-arrow-up',
                      down: 'fas fa-arrow-down',
                      previous: 'fas fa-chevron-left',
                      next: 'fas fa-chevron-right',
                      today: 'far fa-calendar-alt',
                      clear: 'far fa-trash-alt',
                      close: 'fas fa-times'
                    },
                    format: 'YYYY-MM-DD',
                  });
                });
                <?= $this->Html->scriptEnd() ?>
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
