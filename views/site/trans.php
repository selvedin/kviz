<?php

/** @var yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Translation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <a href="<?= Url::to(['site/trans', 'clear' => true]) ?>" class="btn btn-primary mx-2">Clear missing</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <?php ActiveForm::begin()  ?>
            <div class="form-group">
                <input type="checkbox" class='form-check-input' name='add' <?= $add ? 'checked' : '' ?> />
                <label for="add">Add translations</label>
            </div>
            <textarea dir="ltr" name="words" id="words" rows="30" class='form-control' placeholder="Paste words here ..."><?= $words ?></textarea>
            <hr>
            <button class="btn btn-primary">Prepare for translations</button>
            <?php ActiveForm::end()  ?>
        </div>
    </div>

</div>