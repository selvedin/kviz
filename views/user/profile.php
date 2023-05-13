<?php

use app\widgets\CardView;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->name == ' ' ? ucfirst($model->username) : $model->name;
\yii\web\YiiAsset::register($this);
?>
<?= CardView::begin($this->title, 'info') ?>
<br />
<?= DetailView::widget([
  'model' => $model,
  'attributes' => [
    'first_name',
    'last_name',
    'username',
    'email:email',
    ['attribute' => 'isActive', 'label' => __('Status')],
    ['attribute' => 'role.name', 'label' => __('Role')],
    ['attribute' => 'subjectList', 'label' => __('Subjects'), 'value' => $model->subjectsLabel],
  ],
]);
?>
<?= CardView::end();
