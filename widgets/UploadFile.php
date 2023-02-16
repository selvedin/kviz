<?php

namespace app\widgets;

use Yii;
use app\helpers\Icons;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;

/**
 * UploadFile widget renders a form for uplaoding files.
 * Following properties can be set:
 *
 * ```php
 * <?= UploadFile::widget([
    ]) ?>
 * ```
 *
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class UploadFile extends Widget
{
  public $id;
  public $name;

  /**
   * {@inheritdoc}
   */
  public function run()
  {
    $defaultName = Yii::$app->params['fileFormName'];
    $id = $this->id ? $this->id :  $defaultName;
    $name = $this->name ? $this->name :  $defaultName;
    ActiveForm::begin(['id' => $id . 'Form', 'options' => ['enctype' => 'multipart/form-data']]);
    echo Html::fileInput($name . "[]", null, ['id' => $id . 'Input', 'class' => 'd-none', 'multiple' => true, 'onchange' => 'uploadFile()']);
    echo Html::hiddenInput('controller', Yii::$app->controller->id);
    echo Html::hiddenInput('action', Yii::$app->controller->action->id);
    echo Html::hiddenInput('id', isset($_GET['id']) ? (int)$_GET['id'] : null);
    echo Html::button(Icons::faIcon('upload'), [
      'class' => 'btn btn-success btn-sm',
      'onclick' => "document.getElementById('$id" . "Input').click()"
    ]);
    ActiveForm::end();
  }
}
