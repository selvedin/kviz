<?php

namespace app\widgets;

class SimpleAlert extends \yii\bootstrap5\Widget
{
    public $body = 'Your text goes here ...';
    public $type = 'success';
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo "<div id='w2' class='alert-$this->type my-4 alert alert-dismissible' role='alert'>";
        echo $this->body;
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
        echo "</div>";
    }
}
