<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card $card
 */
$this->assign('title', "カード詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <table class="vertical-table">
        <tr>
            <th scope="row">ID</th>
            <td><?= h($card->id) ?></td>
        </tr>
        <tr>
            <th scope="row">キャラクター</th>
            <td><?= $card->has('character') ? $card->character->name : '' ?></td>
        </tr>
        <tr>
            <th scope="row">カード名</th>
            <td><?= h($card->name) ?></td>
        </tr>
        <tr>
            <th scope="row">レアリティ</th>
            <td><?= @h(_code("Cards.rarity.{$card->rarity}")) ?></td>
        </tr>
        <tr>
            <th scope="row">タイプ</th>
            <td><?= @h(_code("Cards.type.{$card->type}")) ?></td>
        </tr>
        <tr>
            <th scope="row">実装日</th>
            <td>
              <?php if (!is_null($card->add_date)) { ?>
                <?= h($card->add_date->i18nFormat('yyyy/MM/dd')) ?>
              <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">ガシャ対象？</th>
            <td><?= @h(_code("Cards.gasha_include.{$card->gasha_include}")) ?></td>
        </tr>
        <tr>
            <th scope="row">限定？</th>
            <td><?= @h(_code("Cards.limited.{$card->limited}")) ?></td>
        </tr>
        <tr>
            <th scope="row">作成日時</th>
            <td>
              <?php if (!is_null($card->created)) { ?>
                <?= h($card->created->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
              <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">更新日時</th>
            <td>
              <?php if (!is_null($card->modified)) { ?>
                <?= h($card->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
              <?php } ?>
            </td>
        </tr>
      </table>
    </div>
  </div>
</div>
