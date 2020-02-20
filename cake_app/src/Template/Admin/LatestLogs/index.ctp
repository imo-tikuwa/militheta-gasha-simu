<?php
/**
 * @var \App\View\AppView $this
 * @var \OperationLogs\Model\Entity\OperationLog[]|\Cake\Collection\CollectionInterface $operation_logs
 */
$this->assign('title', "直近のログ");
?>

<div class="col-md-12 mb-12">
 <div class="card">
  <div class="card-body table-responsive p-0">
   <table class="table table-hover">
    <thead>
            <tr>
                <th scope="col">クライアントIP</th>
                <th scope="col">ユーザーエージェント</th>
                <th scope="col">リクエストURL</th>
                <th scope="col">リクエスト日時</th>
                <th scope="col">リクエスト実行時間(秒)</th>
            </tr>
    </thead>
    <tbody>
            <?php foreach ($operation_logs as $operation_log) { ?>
            <tr>
                <td><?= h($operation_log->client_ip) ?></td>
                <td><?= h($operation_log->user_agent) ?></td>
                <td><?= h($operation_log->request_url) ?></td>
                <td><?= h($operation_log->request_time->i18nFormat('yyyy-MM-dd HH:mm:ss')) ?></td>
                <td><?= h($operation_log->exec_time) ?></td>
            </tr>
          <?php } ?>
    </tbody>
   </table>
  </div>
 </div>
</div>