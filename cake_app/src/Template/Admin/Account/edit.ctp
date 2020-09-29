<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Admin $admin
 */
$button_name = (!empty($admin) && !$admin->isNew()) ? "更新" : "登録";
$this->assign('title', "アカウント/権限{$button_name}");
// $text_class = '';
$table_class = 'table table-hover text-sm text-nowrap';
$input_class = 'form-control form-control-sm rounded-0';
$btn_class = 'btn btn-sm btn-flat btn-outline-secondary';
?>
<div class="col-md-12 mb-12">
  <div class="card rounded-0">
    <div class="card-body">
      <?= $this->Form->create($admin) ?>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('mail', ['class' => $input_class, 'label' => 'メールアドレス', 'maxlength' => '100']); ?>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <div class="form-group">
            <?= $this->Form->control('password', ['class' => $input_class, 'label' => 'パスワード', 'maxlength' => '100', 'value' => @$admin->raw_password]); ?>
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
          <?= $this->Form->button($button_name, ['class' => $btn_class]) ?>
        </div>
      </div>
      <?= $this->Form->end() ?>
    </div>
  </div>
</div>

<?= $this->Html->script('admin/account_edit', ['block' => true, 'charset' => 'UTF-8']) ?>