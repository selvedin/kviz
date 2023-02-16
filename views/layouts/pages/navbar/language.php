<?php

use yii\helpers\Url;

$url = Yii::getAlias('@web');
?>

<!-- Language -->
<li class="nav-item dropdown-language dropdown me-2 me-xl-0">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
    <i class="fi fi-<?= Yii::$app->language == 'en' ? 'us' : 'sa' ?> fis rounded-circle me-1 fs-3"></i>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="<?= Url::to(['site/home', 'lang' => 'en']) ?>">
        <i class="fi fi-us fis rounded-circle me-1 fs-3"></i>
        <span class="align-middle">English</span>
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="<?= Url::to(['site/home', 'lang' => 'ba']) ?>">
        <i class="fi fi-sa fis rounded-circle me-1 fs-3"></i>
        <span class="align-middle"> </span>Bosanski
      </a>
    </li>
  </ul>
</li>
<!--/ Language -->