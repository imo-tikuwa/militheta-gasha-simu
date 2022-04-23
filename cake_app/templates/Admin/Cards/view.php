<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Card $card
 */
$this->assign('title', "カード詳細");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body d-grid">
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
          <tr>
            <th scope="row">ID</th>
            <td><?= h($card->id) ?></td>
          </tr>
          <tr>
            <th scope="row">キャラクター</th>
            <td><?= $card->has('character') ? h($card->character->name) : '' ?></td>
          </tr>
          <tr>
            <th scope="row">カード名</th>
            <td><?= h($card->name) ?></td>
          </tr>
          <tr>
            <th scope="row">レアリティ</th>
            <td><?= @h(_code("Codes.Cards.rarity.{$card->rarity}")) ?></td>
          </tr>
          <tr>
            <th scope="row">タイプ</th>
            <td><?= @h(_code("Codes.Cards.type.{$card->type}")) ?></td>
          </tr>
          <tr>
            <th scope="row">実装日</th>
            <td><?= h($card?->add_date?->i18nFormat('yyyy/MM/dd')) ?></td>
          </tr>
          <tr>
            <th scope="row">ガシャ対象？</th>
            <td><?= @h(_code('Codes.Cards.gasha_include.' . (int)$card->gasha_include)) ?></td>
          </tr>
          <tr>
            <th scope="row">限定？</th>
            <td><?= @h(_code("Codes.Cards.limited.{$card->limited}")) ?></td>
          </tr>
          <tr>
            <th scope="row">作成日時</th>
            <td><?= h($card?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($card?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

