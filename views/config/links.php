<?php

use app\widgets\CardView;
use yii\bootstrap5\Html;

$this->title = 'Imports';
echo CardView::begin([ 'title' => 'Imports', 'buttons' => []]);
if (isset($_GET['key']) && $_GET['key'] == Yii::$app->params['cleanKey'])
  foreach ($objects as $obj => $model) {
    $modelName = "app\\models\\$model";
    $count = $modelName::isImported();
?>
  <div class="container">
    <div class="row p-1 border-bottom">
      <div class="col-md-10">
        <h5>
          <?= ucfirst(str_replace("-", " ", $obj)) ?> [<?= $count ?>]
        </h5>
      </div>
      <div class="col-md-2 text-end">
        <?= $count ?
          Html::a('Clear Data', ["clean-data", 'object' => $obj, 'key' => 'selve'], ['class' => 'btn btn-danger btn-sm w-100', 'data-confirm' => 'Are You sure?']) :
          Html::a('Import from WP', ["import-$obj", 'import' => 5], ['class' => 'btn btn-primary btn-sm w-100', 'data-confirm' => 'Are You sure?']) ?>
      </div>
    </div>
  </div>
<?php
  }

echo CardView::end();
