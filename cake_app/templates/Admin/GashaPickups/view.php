<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup $gasha_pickup
 */
$this->assign('title', "ピックアップ情報詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body d-grid">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
          <tr>
            <th scope="row">ID</th>
            <td><?= h($gasha_pickup->id) ?></td>
          </tr>
          <tr>
            <th scope="row">ガシャID</th>
            <td><?= $gasha_pickup->has('gasha') ? h($gasha_pickup->gasha->title) : '' ?></td>
          </tr>
          <tr>
            <th scope="row">カードID</th>
            <td><?= $gasha_pickup->has('card') ? h($gasha_pickup->card->name) : '' ?></td>
          </tr>
          <tr>
            <th scope="row">作成日時</th>
            <td><?= h($gasha_pickup?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($gasha_pickup?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

