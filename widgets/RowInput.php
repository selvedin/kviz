<?php

namespace app\widgets;

use yii\bootstrap5\Html;

class RowInput extends \yii\bootstrap5\Widget
{

    public $name;
    public $value;
    public $title;
    public $type = 'text';


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $type = $this->type;
        echo Html::tag(
            'div',
            Html::tag(
                'div',
                Html::tag(
                    'div',
                    Html::label($this->title, $this->name, ['class' => 'control-label']) .
                        Html::textInput($this->name, $this->value, ['type' => $this->type, 'class' => 'form-control']),
                    ['class' => 'form-group']
                ),
                ['class' => 'col-12']
            ),
            ['class' => 'row']
        );
    }
}
