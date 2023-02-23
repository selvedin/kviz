<?php

use app\models\Settings;

$url = Yii::$app->request->baseUrl;
$this->title = $model->title;
$company = Settings::getCompanyInfo();
?>
<section class="content">
  <div class="container-fluid">
    <div class="row border-bottom mb-4">
      <div class="col-md-3">
        <img src="<?= $url ?>/images/logo.png" alt="<?= Yii::$app->name ?>" width="100px">
      </div>
      <div class="col-md-6">
      </div>
      <div class="col-md-3 text-right">
        <div><strong><?= $company['name'] ?></strong></div>
        <div><strong><?= $company['address'] ?></strong></div>
        <div><strong><?= $company['phone'] ?></strong></div>
        <div><strong><?= $company['mobile'] ?></strong></div>
        <div><strong><?= $company['email'] ?></strong></div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <?php require_once('view/desc.php') ?>
        <!-- Main content -->
        <div class="invoice p-3 mb-3">
          <!-- title row -->
          <div class="row border-bottom mb-4">
            <div class="col-12">
              <h4>
                <i class="fas fa-box"></i> <?= $model->title ?>
                <small class="float-right"><?= __('Date') ?>: <?= date("Y-m-d", strtotime($model->created_at)) ?></small>
              </h4>
            </div>
          </div>
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">

            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">

            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">

            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
          <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
              <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">

              </p>
            </div>
          </div>
          <!-- /.invoice -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<script>
  $(document).ready(function() {
    $('footer').hide();
    window.print();
    setTimeout(window.close, 2000);
  })
</script>