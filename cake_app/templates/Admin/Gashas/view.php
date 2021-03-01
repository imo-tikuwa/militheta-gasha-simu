<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Gasha $gasha
 */
$this->assign('title', "ガシャ詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
          <tr>
            <th scope="row">ID</th>
            <td><?= h($gasha->id) ?></td>
          </tr>
          <tr>
            <th scope="row">ガシャ開始日</th>
            <td><?= h($this->formatDate($gasha->start_date, 'yyyy/MM/dd')) ?></td>
          </tr>
          <tr>
            <th scope="row">ガシャ終了日</th>
            <td><?= h($this->formatDate($gasha->end_date, 'yyyy/MM/dd')) ?></td>
          </tr>
          <tr>
            <th scope="row">ガシャタイトル</th>
            <td><?= h($gasha->title) ?></td>
          </tr>
          <tr>
            <th scope="row">SSRレート</th>
            <td><?= $this->Number->format($gasha->ssr_rate) ?>%</td>
          </tr>
          <tr>
            <th scope="row">SRレート</th>
            <td><?= $this->Number->format($gasha->sr_rate) ?>%</td>
          </tr>
          <tr>
            <th scope="row">作成日時</th>
            <td><?= h($this->formatDate($gasha->created, 'yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($this->formatDate($gasha->modified, 'yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

