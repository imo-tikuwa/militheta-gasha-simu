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
    <?= $this->Html->css('vendor/front_target_pick') ?>
    <?= $this->Html->script('pace.min') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
<div class="wrapper">

  <?= $this->fetch('content') ?>

</div>
<?= $this->Html->script('vendor/front') ?>
<?= $this->fetch('script') ?>
</body>
</html>
