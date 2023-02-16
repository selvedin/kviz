<?php
use yii\bootstrap5\Html;

?>

<!-- Footer -->
  <footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
      <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
        <div>
          <?=Yii::$app->name?> © <?=date('Y')?>
        </div>
        <div class="pull-end d-none">
          <span><?=Html::a('سياسة الخصوصية',['site/policy'],[])?></span>
        </div>
      </div>
    </div>
  </footer>
  <!-- / Footer -->