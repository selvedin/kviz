<?php

namespace app\helpers;

use yii\bootstrap5\Html;

class Icons
{

  public static function AddIcon($action, $title = "")
  {
    echo Html::a(self::icon('plus'), [$action], ['class' => BTN_CLASS, 'id' => 'btn-create', 'title' => __("Add") . " $title"]);
  }

  public static function ListIcon($action)
  {
    echo Html::a(self::icon('list'), [$action], ['class' => BTN_CLASS, 'id' => 'btn-index', 'title' => __("List")]);
  }

  public static function SaveIcon($noLoading = true, $id = 'btn-save')
  {
    echo Html::a(self::icon('floppy-disk'), "#", ['class' => BTN_CLASS . ($noLoading ? ' no-loading' : ''), 'id' => $id, 'title' => __("Save")]);
  }

  public static function CustomIcon($icon = 'info', $id = 'btn-id', $title = '', $url = '#', $noLoading = true, $isFa = false)
  {
    echo Html::a($isFa ? self::faIcon($icon) : self::icon($icon), $url, ['class' => BTN_CLASS . ($noLoading ? ' no-loading' : ''), 'id' => $id, 'title' => $title]);
  }

  public static function CloneIcon($id, $object)
  {
    echo Html::a(self::icon('copy'), ['clone', 'id' => $id], ['class' => BTN_CLASS, 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'id' => 'btn-clone', 'title' => __("Clone") . " $object", 'data' => [
      'confirm' => __('Are you sure you want to CLONE this item?'),
      'method' => 'post',
    ],]);
  }

  public static function icon($icon)
  {
    return Html::tag('i', '', ['class' => "glyphicon glyphicon-$icon"]);
  }

  public static function faIcon($icon, $size = '')
  {
    return Html::tag('i', '', ['class' => "fa fa-$icon $size"]);
  }
  public static function tfIcon($icon, $size = '')
  {
    return Html::tag('i', '', ['class' => "tf-icons ti ti-$icon ti-$size"]);
  }

  public static function spinner(string $color = 'primary', string $size = '')
  {
    return Html::tag(
      'div',
      Html::tag('span', 'Loading...', ['class' => 'visually-hidden']),
      ['class' => "spinner-border $size text-$color", 'role' => 'status']
    );
  }
}
