<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = "Migration";
ActiveForm::begin();
?>
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-md-10">
        <?= Html::label('Field:', 'field', ['class' => 'control-label']) ?>
        <?= Html::textInput('field', null, ['class' => 'form-control']) ?>
      </div>
      <div class="col-md-2 pt-3">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary mt-1']) ?>
      </div>

    </div>
  </div>
</div>

<?php
ActiveForm::end();
?>
<div class="card">
  <div class="card-body">
    <div class="card-header">
      <h3 class='card-title'>Total Sciences: <strong><?= count($sciences) ?></strong></h3>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($sciences as $row) :
            ?>
              <tr>
                <td><?= $row['id'] ?>.</td>
                <td><?= $row['name'] ?></td>
              </tr>
            <?php
            endforeach;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>