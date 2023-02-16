<?php

namespace app\widgets;

use Yii;

/**
 * Card veiw for forms
 *
 * ```php
 * BrickView::widget(['title'=>'Title', 'buttons'=>[], 'content'=>null]);
 * ```
 *
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class BrickView extends \yii\bootstrap5\Widget
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
    public $shadow = true;

    /**
     * {@inheritdoc}
     */

    public static function begin($config = ['subtitle' => '', 'title' => '', 'url' => null])
    {
        echo '
        <div class="card card-brick" data-name="' . $config['title'] . '">
        <div class="card-body">
        <h1><a href="' . $config['url'] . '">' . $config['title'] . '</a></h1>
        <h6>' . $config['subtitle'] . '</h6>
        ';
    }

    public static function end()
    {
        echo '</div></div>';
    }
}
