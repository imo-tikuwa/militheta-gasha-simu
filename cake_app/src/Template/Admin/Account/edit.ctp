<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin $admin
 */
$button_name = (!empty($admin) && !$admin->isNew()) ? "更新" : "登録";
$this->assign('title', "アカウント/権限{$button_name}");
?>
<div class="col-md-12 mb-12">
  <div class="card">
    <div class="card-body">
      <?= $this->Form->create($admin) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('mail', ['class' => 'form-control rounded-0 ', 'label' => 'メールアドレス', 'maxlength' => '100']); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('password', ['class' => 'form-control rounded-0 ', 'label' => 'パスワード', 'maxlength' => '100', 'value' => @$admin->raw_password]); ?>
            <label class="text-info" id="password-toggle-label"><input type="checkbox" id="password-toggle"/> パスワードを表示</label>
          </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
          <label>権限</label>
          <div id="privilege-form" class="form-group">
            <?= $this->makePrivilegeEditHtml($admin->privilege); ?>
          </div>
        </div>
        <div class="col-md-12">
          <?= $this->Form->button($button_name, ['class' => "btn btn-flat btn-outline-secondary"]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/account_edit', ['block' => true, 'charset' => 'UTF-8']) ?>