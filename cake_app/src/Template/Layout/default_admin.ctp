<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
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
    <?= $this->Html->css('/node_modules/select2/dist/css/select2.min.css') ?>
    <?= $this->Html->css('/node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css') ?>
    <?= $this->Html->css('/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') ?>
    <?= $this->Html->css('//fonts.googleapis.com/icon?family=Material+Icons') ?>
    <?= $this->Html->css('/node_modules/bootstrap-fileinput/css/fileinput.min.css') ?>
    <?= $this->Html->css('/node_modules/bootstrap-fileinput/themes/explorer-fas/theme.min.css') ?>
    <?= $this->Html->css('/node_modules/summernote/dist/summernote-bs4.css') ?>
    <?= $this->Html->css('/node_modules/bootstrap4-tagsinput-douglasanpa/tagsinput.css') ?>
    <?= $this->Html->css('admin_style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed <?= $sidemenu_toggle_class ?>">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" id="sidemenu-toggle"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?= $this->Url->build(['controller' => 'Top'])?>" class="nav-link">Home</a>
      </li>
    </ul>

  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="javascript:void(0);" class="brand-link">
      <span class="brand-text-disp-collapse"><?= SITE_NAME_SHORT ?></span>
      <span class="brand-text font-weight-light brand-text-disp-open"><?= SITE_NAME ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?= $this->element('left_side_menu') ?>
          <li class="nav-item mt-3"><a href="/admin/auth/logout" class="nav-link"><i class="fas fa-sign-out-alt mr-2"></i><p>ログアウト</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!--Main layout-->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?= $this->fetch('title') ?></h1>
          </div>
        </div>

        <?= $this->Flash->render() ?>

        <div class="row">
          <?= $this->fetch('content') ?>
        </div>
      </div>
    </div>
  </div>

  <!--Footer-->
  <footer class="main-footer">
    <div class="text-center">
      <a class="btn btn-flat btn-outline-secondary" href="https://blog.imo-tikuwa.com/" target="_blank" role="button">BLOG
        <i class="fa fa-user ml-2"></i>
      </a>
      <a class="btn btn-flat btn-outline-secondary" href="https://github.com/imo-tikuwa/" target="_blank" role="button">GitHub
        <i class="fab fa-github ml-2"></i>
      </a>
      <strong>© 2020 Copyright: <a href="https://github.com/imo-tikuwa/militheta-gasha-simu" target="_blank"> militheta-gasha-simu </a></strong>
    </div>
  </footer>

</div>
<?= $this->Html->script('/node_modules/jquery/dist/jquery.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') ?>
<?= $this->Html->script('/node_modules/admin-lte/dist/js/adminlte.min.js') ?>
<?= $this->Html->script('/node_modules/select2/dist/js/select2.min.js') ?>
<?= $this->Html->script('/node_modules/moment/min/moment-with-locales.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-fileinput/js/plugins/piexif.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-fileinput/js/plugins/purify.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-fileinput/js/plugins/sortable.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-fileinput/js/fileinput.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-fileinput/themes/explorer-fas/theme.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap-fileinput/js/locales/ja.js') ?>
<?= $this->Html->script('/node_modules/summernote/dist/summernote-bs4.min.js') ?>
<?= $this->Html->script('/node_modules/summernote/dist/lang/summernote-ja-JP.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap4-tagsinput-douglasanpa/tagsinput.js') ?>
<?= $this->Html->script('/node_modules/js-cookie/src/js.cookie.js') ?>
<?= $this->Html->script('admin_script.js') ?>
<?= $this->fetch('script') ?>
</body>
</html>
