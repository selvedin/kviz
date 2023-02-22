<?php

use app\models\User;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$isGuest = Yii::$app->user->isGuest;
$defaultImage = "/img/nouser.png";
?>

<!-- User -->
<li class="nav-item navbar-dropdown dropdown-user dropdown">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
    <div class="avatar">
      <img src="<?= $url . $defaultImage ?>" alt class="h-auto rounded-circle" />
    </div>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li>
      <a class="dropdown-item" href="<?= Url::to([$isGuest ? 'site/login' : 'site/profile']) ?>">
        <div class="d-flex">
          <div class="flex-shrink-0 me-3">
            <div class="avatar">
              <img src="<?= $url . $defaultImage ?>" alt class="h-auto rounded-circle" />
            </div>
          </div>
          <div class="flex-grow-1">
            <span class="fw-semibold d-block">
              <?= $isGuest ? 'Gost' : Yii::$app->user->identity->username; ?>
            </span>
            <small class="text-muted"><?= $isGuest ? 'Gost' : Yii::$app->user->identity->role->name ?></small>
          </div>
        </div>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <a class="dropdown-item" href="<?= Url::to(['site/home', 'lang' => 'bs']) ?>">
        <i class="ti ti-flag me-2 ti-sm"></i>
        <span class="align-middle">Bosanski jezik</span>
      </a>
    </li>
    <li>
      <div class="dropdown-divider"></div>
    </li>
    <li>
      <a class="dropdown-item" href="javascript:void(0)" onclick="$('#logout-form').submit();">
        <i class="ti ti-logout me-2 ti-sm"></i>
        <span class="align-middle"><?= $isGuest ? __('Login') : __('Log Out') ?></span>
      </a>
    </li>
  </ul>
</li>
<!--/ User -->
<?php

echo Html::beginForm(['/site/logout'], 'post', ['id' => 'logout-form', 'class' => 'd-none'])
  . Html::submitButton(
    '<i class="dropdown-item"></i><span class="align-middle">' .
      __('Log Out') . "</span>",
    ['class' => 'btn btn-link logout']
  )
  . Html::endForm();
?>