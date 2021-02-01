<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha[] $gashas
 * @var \App\Form\SearchForm $search_form
 */
$this->assign('title', "ガシャ");
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
            <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_ADD]) ?>'">新規登録</button>
          <?php } ?>
          <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" data-toggle="modal" data-target="#gashas-search-form-modal">検索</button>
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_EXPORT])) { ?>
            <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_CSV_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>'">CSVエクスポート</button>
          <?php } ?>
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_CSV_IMPORT])) { ?>
            <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" id="csv-import-btn">CSVインポート</button>
            <?= $this->Form->create(null, ['id' => 'csv-import-form', 'url' => ['action' => ACTION_CSV_IMPORT], 'enctype' => 'multipart/form-data', 'style' => 'display:none;']) ?>
              <input type="file" name="csv_import_file" id="csv-import-file" accept=".csv"/>
            <?= $this->Form->end(); ?>
          <?php } ?>
          <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EXCEL_EXPORT])) { ?>
            <button type="button" class="btn btn-sm btn-flat btn-outline-secondary" onclick="location.href='<?= $this->Url->build(['action' => ACTION_EXCEL_EXPORT, '?' => $this->getRequest()->getQueryParams()]) ?>'">Excelエクスポート</button>
          <?php } ?>
        </div>
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'gashas-freeword-search-form']) ?>
          <div class="freeword-search input-group input-group-sm">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <?= $this->Form->control('search_snippet_format', ['type' => 'radio', 'options' => _code('Others.search_snippet_format'), 'class' => 'form-check-label col-form-label col-form-label-sm gashas-freeword-search-snippet-format', 'default' => 'AND', 'label' => false, 'templates' => ['nestingLabel' => '{{hidden}}{{input}}<small><label {{attrs}}>{{text}}</label></small>', 'radioWrapper' => '{{label}}', 'inputContainer' => '{{content}}']]) ?>
              </div>
            </div>
            <?= $this->Form->text('search_snippet', ['id' => 'gashas-freeword-search-snippet', 'class' => 'form-control form-control-sm rounded-0', 'style' => 'width: 200px;', 'placeholder' => 'フリーワード']) ?>
            <div class="input-group-append">
              <button type="submit" id="gashas-freeword-search-btn" class="btn btn-sm btn-flat btn-outline-secondary"><i class="fas fa-search"></i></button>
            </div>
          </div>
          <?= $this->Form->hidden('sort') ?>
          <?= $this->Form->hidden('direction') ?>
        <?= $this->Form->end(); ?>
      </div>
    </div>
    <div class="card-body table-responsive p-0">
      <table class="table table-sm table-hover text-sm text-nowrap">
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
              <td><?= h($this->formatDate($gasha->start_date, 'yyyy/MM/dd')) ?></td>
              <td><?= h($this->formatDate($gasha->end_date, 'yyyy/MM/dd')) ?></td>
              <td><?= h($gasha->title) ?></td>
              <td><?= $this->Number->format($gasha->ssr_rate) ?>%</td>
              <td><?= $this->Number->format($gasha->sr_rate) ?>%</td>
              <td><?= h($this->formatDate($gasha->modified, 'yyyy/MM/dd HH:mm:ss')) ?></td>
              <td class="actions">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop<?= $gasha->id ?>" type="button" class="btn btn-sm btn-flat btn-outline-secondary dropdown-toggle index-dropdown-toggle" data-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop<?= $gasha->id ?>">
                    <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_VIEW])) { ?>
                      <?= $this->Html->link('詳細', ['action' => ACTION_VIEW, $gasha->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_EDIT])) { ?>
                      <?= $this->Html->link('編集', ['action' => ACTION_EDIT, $gasha->id], ['class' => 'dropdown-item']) ?>
                    <?php } ?>
                    <?php if (AuthUtils::hasRole($this->getRequest(), ['action' => ACTION_DELETE])) { ?>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ガシャ検索</h5>
      </div>
      <div class="modal-body">
        <?= $this->Form->create($search_form, ['type' => 'get', 'id' => 'gashas-search-form']) ?>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('id', [
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'ID',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('start_date', [
                  'id' => 'start_date-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'ガシャ開始日',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#start_date-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('end_date', [
                  'id' => 'end_date-datepicker',
                  'type' => 'text',
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'ガシャ終了日',
                  'data-toggle' => 'datetimepicker',
                  'data-target' => '#end_date-datepicker',
                ]); ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="form-group">
                <?= $this->Form->control('title', [
                  'class' => 'form-control form-control-sm rounded-0',
                  'label' => 'ガシャタイトル',
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
                          'radioWrapper' => '{{label}}', 'inputContainer' => '{{content}}'
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

<?= $this->Html->script('admin/gashas_index', ['block' => true, 'charset' => 'UTF-8']) ?>
