<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 */
$this->assign('title', '管理画面TOP');
?>
<?php if (!empty($functions) && count($functions) > 0) { ?>
  <?php foreach ($functions as $function) { ?>
    <?php if (AuthUtils::hasRole($this->request, ['controller' => $function['controller'], 'action' => ACTION_INDEX])) { ?>
      <div class="col-md-4 col-sm-12">
        <div class="card">
          <div class="card-header">
            <?= $function['label'] ?>
          </div>
          <div class="card-body">
            <p class="card-text">データ登録数：<?= $function['data_count'] ?></p>
            <a class="btn btn-sm btn-flat btn-outline-secondary" href="<?= $this->Url->build(['controller' => "{$function['controller']}", 'action' => 'index']) ?>">一覧画面</a>
            <?php if (AuthUtils::hasRole($this->request, ['controller' => $function['controller'], 'action' => ACTION_ADD])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary" href="<?= $this->Url->build(['controller' => "{$function['controller']}", 'action' => 'add']) ?>">登録画面</a>
            <?php } ?>
            <?php if ($function['exist_csv_export'] === true && AuthUtils::hasRole($this->request, ['controller' => $function['controller'], 'action' => ACTION_CSV_EXPORT])) { ?>
              <a class="btn btn-sm btn-flat btn-outline-secondary" href="<?= $this->Url->build(['controller' => "{$function['controller']}", 'action' => 'csvExport']) ?>">CSVエクスポート</a>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  <?php } ?>
<?php }?>
