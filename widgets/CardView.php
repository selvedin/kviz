<?php

namespace app\widgets;

use app\helpers\Helper;
use Yii;

/**
 * Card veiw for forms
 *
 * ```php
 * CardView::widget(['title'=>'Title', 'buttons'=>[], 'content'=>null]);
 * ```
 *
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class CardView extends \yii\bootstrap5\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: name of menu
     * - value: string - menu url, or items - list of submenus
     */
    public $title = "";
    public $control = "";
    public $buttons = [];
    public $content = null;
    public $type = "info";

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo "<div class='row'><div class='col-md-12'><div class='card card-action'>";
        if ($this->title) {
            echo "<div class='card-header bg-$this->type pb-1'>";
            echo "<div class='card-action-title d-flex'>
            <h5 class='card-title'>$this->title</h5>";
            echo "<div class='mx-2' style='margin-top:-5px;'>$this->control</div></div>";
            echo "<div class='card-action-element'>";
            echo "<ul class='card-inlinelist list-inline mb-0'><li class='list-inline-item'>";
            foreach ($this->buttons as $button) echo $button;
            echo "</li></ul>";
            Helper::dropDown($this->buttons);
            echo "</div></div>";
        }
        echo "<div class='card-content'>$this->content</div>";
        echo "<div class='card-footer'></div>";
        echo "</div></div></div>";
    }

    public static function begin($title = 'Card', $type = 'default', $buttons = [])
    {
        echo "<div><div class='row'><div class='col-md-12'>
        <div class='card card-action'><div class='card-header bg-$type border-bottom'>";
        echo "<div class='card-action-title'><h5 class='m-0'>$title</h5></div>";
        echo "<div class='card-action-element'>";
        echo "<ul class='card-inlinelist list-inline mb-0 '><li class='list-inline-item'>";
        foreach ($buttons as $button) echo $button;
        echo "</li></ul>";
        Helper::dropDown($buttons);
        echo "</div></div>";
        echo "<div class='card-body'>";
    }

    public static function end()
    {
        echo "</div><div class='card-footer'></div>";
        echo "</div></div></div></div>";
    }
}
