<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\ToastAlert;
use yii\bootstrap5\Html;

$url = Yii::getAlias('@web');
$path = Yii::$app->basePath;
$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
$isLogin = in_array($action, ['login', 'signup', 'request-password-reset', 'reset-password']);

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="hr-HR" class="light-style layout-menu-fixed layout-footer-fixed" data-theme="theme-default" data-assets-path="<?= $url ?>/" data-template="horizontal-menu-template">

<head>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <!-- <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" /> -->
    <title><?= Html::encode($this->title) ?></title>
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= $url ?>/vendor/css/rtl/core.css" class="--template-customizer-core-css" />
    <link rel="stylesheet" href="<?= $url ?>/vendor/css/rtl/theme-default.css" class="--template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= $url ?>/css/demo.css" />
    <link rel="stylesheet" href="<?= $url ?>/css/custom.css?<?= time() ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <?php if ($isLogin) : ?>
        <link rel="stylesheet" href="<?= $url ?>/vendor/css/pages/page-auth.css" />
    <?php endif; ?>

    <?php $this->head() ?>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="<?= $url ?>/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= $url ?>/vendor/libs/toastr/toastr.js"></script>
    <script src="<?= $url ?>/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="<?= $url ?>/vendor/js/helpers.js"></script>
    <script src="<?= $url ?>/js/hijri.js"></script>
    <script src="<?= $url ?>/js/config.js"></script>
</head>

<body>
    <?php
    $this->beginBody();
    if ($isLogin) echo $content;
    else require_once('pages/content.php');
    $this->endBody();
    ?>
    <div id='loading-div' class='d-none'>
        <div class="spinner-border spinner-border-lg text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <?= ToastAlert::widget() ?>
    <?php require_once('imageModal.php') ?>
    <?php require_once('imageModal.php') ?>
    <?= Yii::$app->user->isGuest ? null : Html::tag('script', '', ['src' => Yii::$app->request->baseUrl . "/worker/worker.js"]); ?>
</body>

</html>
<?php $this->endPage() ?>