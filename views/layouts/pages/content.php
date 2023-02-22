<?php

$ctrl = Yii::$app->controller->id;
?>
<div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
  <div class="layout-container">
    <?php require_once('navbar.php') ?>
    <div class="layout-page bg-home">
      <div class="content-wrapper">
        <?php $ctrl != 'player' && require_once('menus.php'); ?>
        <div id="mainApp" class="container-fluid flex-grow-1 container-p-y">
          <?= $content ?>
        </div>
        <?php $ctrl != 'player' && require_once('footer.php'); ?>
        <div class="content-backdrop fade"></div>
      </div>
    </div>
    <?php
    require_once("$path/web/script.php");
    if (file_exists("$path/web/vue/$controller.php"))
      require_once("$path/web/vue/$controller.php");
    ?>
  </div>
</div>