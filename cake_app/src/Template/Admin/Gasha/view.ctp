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
      <table class="vertical-table">
        <tr>
            <th scope="row">ID</th>
            <td><?= h($gasha->id) ?></td>
        </tr>
        <tr>
            <th scope="row">ガシャ開始日</th>
            <td>
              <?php if (!is_null($gasha->start_date)) { ?>
                <?= h($gasha->start_date->i18nFormat('yyyy/MM/dd')) ?>
              <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">ガシャ終了日</th>
            <td>
              <?php if (!is_null($gasha->end_date)) { ?>
                <?= h($gasha->end_date->i18nFormat('yyyy/MM/dd')) ?>
              <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">ガシャタイトル</th>
            <td><?= h($gasha->title) ?></td>
        </tr>
        <tr>
            <th scope="row">作成日時</th>
            <td>
              <?php if (!is_null($gasha->created)) { ?>
                <?= h($gasha->created->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
              <?php } ?>
            </td>
        </tr>
        <tr>
            <th scope="row">更新日時</th>
            <td>
              <?php if (!is_null($gasha->modified)) { ?>
                <?= h($gasha->modified->i18nFormat('yyyy/MM/dd HH:mm:ss')) ?>
              <?php } ?>
            </td>
        </tr>
      </table>
    </div>
  </div>
</div>
