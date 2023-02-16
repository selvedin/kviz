<?php

namespace app\helpers;

use Yii;

class CacheHelper
{
  public static function get($key)
  {
    $cache = Yii::$app->cache;
    return $cache->get(Yii::$app->id . $key);
  }

  public static function set($key, $data, $expiry = 900)
  {
    $cache = Yii::$app->cache;
    $cache->set(Yii::$app->id . "_" . $key, $data, $expiry);
  }

  public static function clearCache($key)
  {
    $cache = Yii::$app->cache;
    $cache->delete(Yii::$app->id . "_" . $key);
  }
}
