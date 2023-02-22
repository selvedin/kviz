<?php

namespace app\widgets;

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
            echo "<div class='card-header bg-$this->type'>";
            echo "<div class='card-action-title d-flex'><h4 class='card-title'>$this->title</h4>";
            echo "<div class='mx-2' style='margin-top:-5px;'>$this->control</div></div>";
            echo "<div class='card-action-element'>";
            echo "<ul class='card-inlinelist list-inline mb-0'><li class='list-inline-item'>";
            foreach ($this->buttons as $button) echo $button;
            echo "</li></ul>";
            self::dropDown($this->buttons);
            echo "</div></div>";
        }
        echo "<div class='card-content'>$this->content</div>";
        echo "<div class='card-footer'></div>";
        echo "</div></div></div>";
    }

    public static function begin($config = ['buttons' => [], 'title' => 'Card', 'type' => 'info'])
    {
        echo "<div><div class='row'><div class='col-md-12'>
        <div class='card card-action'><div class='card-header bg-" . (isset($config['type']) ? $config['type'] : 'default') . " border-bottom'>";
        echo "<div class='card-action-title'><h5 class='m-0 text-white'>" . $config["title"] . "</h5></div>";
        echo "<div class='card-action-element'>";
        echo "<ul class='card-inlinelist list-inline mb-0 '><li class='list-inline-item'>";
        foreach ($config["buttons"] as $button) echo $button;
        echo "</li></ul>";
        self::dropDown($config['buttons']);
        echo "</div></div>";
        echo "<div class='card-body'>";
    }

    public static function end()
    {
        echo "</div><div class='card-footer'></div>";
        echo "</div></div></div></div>";
    }

    public static function dropDown($buttons)
    {
        echo '<div class="card-dropdown btn-group">';
        echo '<button type="button" 
        class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow waves-effect waves-light" 
        data-bs-toggle="dropdown" 
        aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
        </button>';
        echo '<ul class="dropdown-menu dropdown-menu-end" style="">';
        foreach ($buttons as $button)
            echo "<li>$button</li>";
        echo '</ul>';
        echo '</div>';
    }
}
