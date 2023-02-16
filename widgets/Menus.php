<?php

namespace app\widgets;

use yii\helpers\Url;
use Yii;

/**
 * Displaying menus for app theme
 *
 * ```php
 * Menus::widget(['items'=>$items]);
 * ```
 *
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class Menus extends \yii\bootstrap5\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: name of menu
     * - value: string - menu url, or items - list of submenus
     */

    public $menus = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        foreach ($this->menus as $menu) $this->rootMenu($menu);
    }

    public function rootMenu($menu)
    {
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        $ca = "$controller/$action";
        $active = "";
        if ($menu['visible']) {
            $active = (isset($menu['items']) && $this->hasActiveItem($menu['items'], $ca)) || $menu['url'] == $ca ? "active" : "";
            $url = $menu['url'] ? Url::to([$menu['url']]) : 'javascript:void(0);';
            echo "<li class='menu-item $active'>";
            echo '<a href=' . $url;
            if (isset($menu['items'])) {
                echo ' class="menu-link menu-toggle"';
            } else {
                echo ' class="menu-link"';
            }
            echo '><i class="menu-icon tf-icons ti ti-' . $menu['icon'] . '"></i>';
            echo '<div data-i18n="' . $menu['title'] . '">' . $menu['title'] . '</div>';
            echo '</a>';
            if (isset($menu['items'])) self::menus($menu['items']);
            echo '</li>';
        }
    }

    public function hasActiveItem($items, $ca)
    {
        foreach ($items as $item) {
            if ($item['url'] ==  $ca) return true;
        }
        return false;
    }

    public static function menus($menus)
    {
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;
        echo "<ul class='menu-sub'>";
        foreach ($menus as $menu) {
            if ($menu['visible']) {
                $active = $menu['url'] == "$controller/$action" ? "active" : "";
                $url = $menu['url'] ? Url::to([$menu['url']]) : 'javascript:void(0);';
                $toggle = $menu['url'] ? "" : "menu-toggle";
                echo "<li class='menu-item $active'>";
                echo "<a href='$url' class='menu-link $toggle'>";
                echo "<i class='menu-icon tf-icons ti ti-" . $menu['icon'] . "'></i>";
                echo "<div data-i18n='" . $menu['title'] . "'>" . $menu['title']  . "</div>";
                echo "</a>";
                if (isset($menu['items'])) self::subItem($menu['items']);
                echo "</li>";
            }
        }
        echo "</ul>";
    }

    public static function subItem($items)
    {

        echo '<ul class="menu-sub">';
        foreach ($items as $item) {
            if ($item['visible']) {
                $url = $item['url'] ? Url::to([$item['url']]) : 'javascript:void(0);';
                echo self::_li($url, $item['title']);
            }
        }
        echo '</ul>';
    }

    public static function _li($url, $title)
    {
        echo '<li class="menu-item">';
        echo self::_link($url, $title);
        echo '</li>';
    }

    public static function _link($url, $title)
    {
        echo "<a href='$url' class='menu-link'>";
        echo self::_div($title);
        echo '</a>';
    }

    public static function _div($title)
    {
        echo "<div data-i18n='$title'>$title</div>";
    }
}
