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

	<?= $this->Html->css('/node_modules/@fortawesome/fontawesome-free/css/all.css') ?>
	<?= $this->Html->css('/node_modules/bootstrap/dist/css/bootstrap.min.css') ?>
	<?= $this->Html->css('/node_modules/admin-lte/dist/css/adminlte.min.css') ?>
	<?= $this->Html->css('admin_style.css') ?>

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
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      <?= $this->Form->end() ?>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>

<?= $this->Html->script('/node_modules/jquery/dist/jquery.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') ?>
<?= $this->Html->script('/node_modules/admin-lte/dist/js/adminlte.min.js') ?>
</body>
</html>