<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', "ログイン");
?>
<!DOCTYPE html>
<html>
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->fetch('title') ?></title>
  <?= $this->Html->meta('icon') ?>
  <?= $this->Html->meta('robots', ['content' => 'noindex']) ?>
  <?= $this->Html->css('vendor/bundle') ?>
  <?= $this->fetch('meta') ?>
  <?= $this->fetch('css') ?>
  <?= $this->fetch('script') ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b><?= SITE_NAME ?></b> Login
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <?= $this->Flash->render() ?>

      <?= $this->Form->create(null) ?>
        <div class="form-group has-feedback">
          <?= $this->Form->control('mail', ['id' => 'login-mail', 'class' => 'form-control rounded-0', 'label' => 'ログインID']) ?>
        </div>
        <div class="form-group has-feedback">
          <?= $this->Form->control('password', ['id' => 'login-password', 'class' => 'form-control rounded-0', 'label' => 'パスワード']) ?>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat">ログイン</button>
          </div>
          <!-- /.col -->
        </div>
      <?= $this->Form->end() ?>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<?= $this->Html->script('jquery-3.4.1.min') ?>
<?= $this->Html->script('vendor/bundle') ?>
</body>
</html>