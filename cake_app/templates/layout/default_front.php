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
    <?= $this->Html->css('vendor/front_index') ?>
    <?= $this->Html->script('jquery-3.4.1.min') ?>
    <?= $this->Html->script('vendor/front', ['defer' => true]) ?>
    <?= $this->fetch('script') ?>

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
            <h1 class="m-0 mt-2 text-dark"><?= $this->fetch('title') ?></h1>
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
</body>
</html>
