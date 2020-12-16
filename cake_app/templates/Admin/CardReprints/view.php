<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint $card_reprint
 */
$this->assign('title', "復刻情報詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <table class="table table-sm table-hover table-borderless text-sm">
        <tr>
          <th scope="row">ID</th>
          <td><?= h($card_reprint->id) ?></td>
        </tr>
        <tr>
          <th scope="row">ガシャID</th>
          <td><?= $card_reprint->has('gasha') ? h($card_reprint->gasha->title) : '' ?></td>
        </tr>
        <tr>
          <th scope="row">カードID</th>
          <td><?= $card_reprint->has('card') ? h($card_reprint->card->name) : '' ?></td>
        </tr>
        <tr>
          <th scope="row">作成日時</th>
          <td><?= h($this->formatDate($card_reprint->created, 'yyyy/MM/dd HH:mm:ss')) ?></td>
        </tr>
        <tr>
          <th scope="row">更新日時</th>
          <td><?= h($this->formatDate($card_reprint->modified, 'yyyy/MM/dd HH:mm:ss')) ?></td>
        </tr>
      </table>
    </div>
  </div>
</div>

