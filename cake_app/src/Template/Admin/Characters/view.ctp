<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Character $character
 */
$this->assign('title', "キャラクター詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <table class="vertical-table">
        <tr>
            <th scope="row">ID</th>
            <td><?= h($character->id) ?></td>
        </tr>
        <tr>
            <th scope="row">名前</th>
            <td><?= h($character->name) ?></td>
        </tr>
        <tr>
            <th scope="row">作成日時</th>
            <td>
              <?php if (!is_null($character->created)) { ?>
                <?= h($character->created->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
              <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">更新日時</th>
            <td>
              <?php if (!is_null($character->modified)) { ?>
                <?= h($character->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
              <?php } ?>
            </td>
        </tr>
      </table>
    </div>
  </div>
</div>
