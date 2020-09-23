<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CardReprint $cardReprint
 */
$this->assign('title', "復刻情報詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <table class="table table-sm table-hover table-borderless text-sm">
        <tr>
          <th scope="row">ID</th>
          <td><?= h($cardReprint->id) ?></td>
        </tr>
        <tr>
          <th scope="row">ガシャID</th>
          <td><?= $cardReprint->has('gasha') ? $cardReprint->gasha->title : '' ?></td>
        </tr>
        <tr>
          <th scope="row">カードID</th>
          <td><?= $cardReprint->has('card') ? $cardReprint->card->name : '' ?></td>
        </tr>
        <tr>
          <th scope="row">作成日時</th>
          <td>
            <?php if (!is_null($cardReprint->created)) { ?>
              <?= h($cardReprint->created->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
            <?php } ?>
          </td>
        </tr>
        <tr>
          <th scope="row">更新日時</th>
          <td>
            <?php if (!is_null($cardReprint->modified)) { ?>
              <?= h($cardReprint->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>

