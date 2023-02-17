<?php

namespace app\helpers;

use yii\bootstrap5\Html;

define('BUTTONS_CLASS', 'btn btn-sm rounded-pill mx-1 text-white btn-');
define('PADDING', ['style' => 'padding:0 5px;']);
class Buttons
{
  public static function Update($param, $value)
  {
    $title = __('Update');
    return Html::a(
      Icons::faIcon('edit') .
        Html::tag('span', $title, PADDING),
      ['update', "$param" => $value],
      [
        'class' => BUTTONS_CLASS . 'primary',
        'title' => $title
      ]
    );
  }

  public static function Delete($param, $value)
  {
    $title = __('Delete');
    return Html::a(Icons::faIcon('trash') .
      Html::tag('span', $title, PADDING), ['delete', "$param" => $value], [
      'class' => BUTTONS_CLASS . 'danger',
      'title' =>  $title,
      'data' => [
        'confirm' => __('Are you sure you want to delete this item') . '?',
        'method' => 'post',
      ],
    ]);
  }

  public static function Create()
  {
    $title = __('Create');
    return Html::a(Icons::faIcon('plus') . Html::tag('span', $title, PADDING), ['create'], [
      'class' => BUTTONS_CLASS . 'primary',
      'title' => $title
    ]);
  }

  public static function Save()
  {
    $title = __('Save');
    return Html::submitButton(Icons::faIcon('save') . Html::tag('span', $title, PADDING), [
      'class' => BUTTONS_CLASS . 'success',
      'title' => $title
    ]);
  }

  public static function View($param, $value)
  {
    $title = __('View');
    return Html::a(Icons::faIcon('eye') . Html::tag('span', $title, PADDING), ['view', "$param" => $value], [
      'class' => BUTTONS_CLASS . 'secondary',
      'title' => $title,
    ]);
  }

  public static function List()
  {
    $title = __('List');
    return Html::a(Icons::faIcon('list') . Html::tag('span', $title, PADDING), ['index'], [
      'class' => BUTTONS_CLASS . 'secondary',
      'title' => $title
    ]);
  }

  public static function ResetList()
  {
    $title = __('Reset list filter');
    return Html::a(Icons::faIcon('refresh') . Html::tag('span', $title, PADDING), ['index'], [
      'class' => BUTTONS_CLASS . 'warning',
      'title' => $title
    ]);
  }

  public static function Pdf($id, $flag = false)
  {
    $title = __('Export to PDF');
    return Html::a(
      Icons::faIcon('file-pdf') . Html::tag('span', $title, PADDING),
      ['pdf', 'id' => $id, 'flag' => $flag],
      [
        'class' => BUTTONS_CLASS . 'danger',
        'target' => 'blank',
        'title' => $title
      ]
    );
  }

  public static function toggleCard()
  {
    return Html::button(Icons::faIcon('minus'), ['class' => 'btn btn-tool', 'data-card-widget' => 'collapse']);
  }

  public static function removeCard()
  {
    return Html::button(Icons::faIcon('times'), ['class' => 'btn btn-tool', 'data-card-widget' => 'remove']);
  }

  public static function customButton(string $title, array | string $link, $attrs = [], $class = "info",)
  {
    return Html::a($title, $link, array_merge(['class' => "card-buttons btn btn-sm btn-$class"], $attrs));
  }

  public static function closeModal()
  {
    return Html::button("&times;", ['class' => 'close', 'data' => ['dismiss' => 'modal'], 'aria-label' => 'Close']);
  }

  public static function collapseCard()
  {
    return Html::a(Icons::tfIcon('chevron-right'), null, [
      'href' => 'javascript:void(0);',
      'class' => 'card-collapsible',
      'title' => __('Collapse')
    ]);
  }
}
