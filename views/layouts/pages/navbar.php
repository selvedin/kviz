<?php

use yii\helpers\Url;

$url = Yii::getAlias('@web');
?>
<!-- Navbar -->

<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="container-xxl">
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
      <a href="<?= Url::to(['site/home']) ?>" class="app-brand-link gap-2">
        <span class="app-brand-logo demo">
          <!--- Logo goes here  -->
        </span>
        <span class="app-brand-text demo menu-text fw-bold">
          <?= Yii::$app->name ?>
        </span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
        <i class="ti ti-x ti-sm align-middle"></i>
      </a>
    </div>

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="ti ti-menu-2 ti-sm"></i>
      </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
      <ul class="navbar-nav flex-row align-items-center ms-auto">
        <?php
        // require_once('navbar/language.php');
        // require_once('navbar/search.php');
        ?>

        <?php
        // require_once('navbar/quick_links.php');
        // require_once('navbar/notifications.php');
        require_once('navbar/user.php');
        ?>


      </ul>
    </div>

    <!-- Search Small Screens -->
    <div class="navbar-search-wrapper search-input-wrapper container-xxl d-none">
      <input type="text" class="form-control search-input border-0" placeholder="Search..." aria-label="Search..." />
      <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
  </div>
</nav>

<!-- / Navbar -->