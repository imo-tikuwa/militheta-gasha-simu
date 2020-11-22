<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\GashaPickup $gashaPickup
 */
$this->assign('title', "ピックアップ情報詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <table class="table table-sm table-hover table-borderless text-sm">
        <tr>
          <th scope="row">ID</th>
          <td><?= h($gashaPickup->id) ?></td>
        </tr>
        <tr>
          <th scope="row">ガシャID</th>
          <td><?= $gashaPickup->has('gasha') ? h($gashaPickup->gasha->title) : '' ?></td>
        </tr>
        <tr>
          <th scope="row">カードID</th>
          <td><?= $gashaPickup->has('card') ? h($gashaPickup->card->name) : '' ?></td>
        </tr>
        <tr>
          <th scope="row">作成日時</th>
          <td>
            <?php if (!is_null($gashaPickup->created)) { ?>
              <?= h($gashaPickup->created->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <th scope="row">更新日時</th>
          <td>
            <?php if (!is_null($gashaPickup->modified)) { ?>
              <?= h($gashaPickup->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>

