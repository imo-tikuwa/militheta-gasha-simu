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
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
<div class="wrapper">

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

</div>
<?= $this->Html->script('/node_modules/jquery/dist/jquery.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') ?>
<?= $this->fetch('script') ?>
</body>
</html>
