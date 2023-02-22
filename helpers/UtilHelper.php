<?php

namespace app\helpers;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class UtilHelper
{
  public static function flattenArray($array)
  {
    $flattenArray = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
    foreach ($iterator as $value) {
      $flattenArray[] = $value;
    }
    return $flattenArray;
  }
}
