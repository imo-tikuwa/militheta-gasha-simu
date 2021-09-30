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
      <div class="table-responsive">
        <table class="table table-sm table-hover table-borderless text-sm">
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
            <td><?= h($character?->created?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
          <tr>
            <th scope="row">更新日時</th>
            <td><?= h($character?->modified?->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

