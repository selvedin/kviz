<?php

use app\helpers\Buttons;
use app\widgets\CardView;
use app\widgets\RowInput;
use app\widgets\SwitchInput;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = __('General Settings');
?>
<div class="row" id='settingsApp'>
  <div class="col-12">
    <?php $form = ActiveForm::begin(); ?>
    <div class="card" style="background:none; border:none;box-shadow:none;">
      <div id="sticky-wrapper" class="sticky-wrapper" style="height: 80px;">
        <div class="card-header sticky-element bg-label-info d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
          <h1 class="card-title mb-sm-0 me-2"><?= $this->title ?></h1>
          <div class="action-btns">
            <?= Buttons::Save() ?>
          </div>
        </div>
      </div>
      <div class="row">
        <?php
        $content = "";
        foreach ($fields as $key => $value)
          if (str_contains($key, 'check_'))
            $content .= _div(SwitchInput::widget(['name' => $key, 'label' => $value, 'value' => isset($model[$key]) ? 1 : 0, 'id' => $key]));
          else if (str_contains($key, 'number_'))
            $content .= _div(RowInput::widget(['name' => $key, 'title' => $value, 'type' => 'number', 'value' => set($model, $key)]));
          else if (str_contains($key, 'select_'))
            $content .= _div(
              Html::label($value['title'], $key, ['class' => 'control-label']) .
                Select2::widget([
                  'name' => $key,
                  'data' => $value['data'],
                  'value' => set($model, $key),
                  'options' => ['placeholder' => $value['title']],
                  'pluginOptions' => [
                    'allowClear' => true
                  ]
                ]),
              '3 mt-2'
            );
          else
            $content .= _div(RowInput::widget(['name' => $key, 'title' => $value, 'value' => set($model, $key)]));

        echo  Html::tag('div', CardView::widget([
          'title' => null,
          'buttons' => [],
          'content' => Html::tag('div', $content, ['class' => 'row m-4'])
        ]), ['class' => 'col-md-12']);
        ?>
      </div>
    </div>
  </div>
  <?php ActiveForm::end(); ?>
</div>
<?php

function _div($field, $col = 3)
{
  return Html::tag('div', $field, ['class' => "col-md-$col"]);
}
?>