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

    <?= $this->element('google_analytics'); ?>
    <?= $this->Html->css('/node_modules/@fortawesome/fontawesome-free/css/all.css') ?>
    <?= $this->Html->css('/node_modules/bootstrap/dist/css/bootstrap.min.css') ?>
    <?= $this->Html->css('target_pick.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
<div class="wrapper">

  <?= $this->fetch('content') ?>

</div>
<?= $this->Html->script('/node_modules/jquery/dist/jquery.min.js') ?>
<?= $this->Html->script('/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') ?>
<?= $this->fetch('script') ?>
</body>
</html>
