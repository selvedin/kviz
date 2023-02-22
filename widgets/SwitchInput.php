<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\Html;

class SwitchInput extends \yii\bootstrap5\Widget
{

  public $name = "check";
  public $label = "";
  public $value = 0;
  public $id = "";
  public $type = 'primary';
  public $isRadio = false;
  public $checked = false;


  /**
   * {@inheritdoc}
   */
  public function run()
  {
    $checked = $this->checked ? 'checked' : '';
    $type = $this->isRadio ? 'radio' : 'checkbox';
    if (empty($this->id)) $this->id = Yii::$app->controller->id . "-check";
    echo "<label class='switch switch-$this->type'>
        <input type='$type' name='$this->name' class='switch-input' value='$this->value' $checked>
        <span class='switch-toggle-slider'>
          <span class='switch-on'>
            <i class='ti ti-check'></i>
          </span>
          <span class='switch-off'>
            <i class='ti ti-x'></i>
          </span>
        </span>
        <span class='switch-label'>$this->label</span>
      </label>";
  }
}
