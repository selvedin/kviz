<?php

// comment out the following two lines when deployed to production
if ($_SERVER['SERVER_NAME'] == 'localhost' || isset($_GET['log'])) {
  defined('YII_DEBUG') or define('YII_DEBUG', true);
  defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
  defined('YII_DEBUG') or define('YII_DEBUG', false);
  defined('YII_ENV') or define('YII_ENV', 'production');
}


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

require_once(__DIR__ . '/constants.php');

require_once(__DIR__ . '/lang/global.php');
require_once('helpers.php');

function __($w, $param1 = '', $param2 = '', $param3 = '', $lng = null)
{
  global $lang__;
  if ($lng && file_exists("lang/$lng.php")) {
    $localLang = require_once("lang/$lng.php");
    if (isset($localLang[$w])) return $localLang[$w];
  }

  if (isset($lang__[$w])) {
    if ($param1 == '' && $param2 == '' && $param3 == '')
      return $lang__[$w];
    return sprintf($lang__[$w], $param1 = '', $param2 = '', $param3 = '');
  }

  return resolveUnexistingWord($w, $lng);
}

function resolveUnexistingWord($w, $lng = null)
{
  if (!isset($_COOKIE['lng']) && !$lng)  return $w;

  if (!$lng) $lng = $lng = $_COOKIE['lng'];

  if (!file_exists("lang/missing_$lng.txt"))
    file_put_contents("lang/missing_$lng.txt", "");

  $untranslatedWords = file("lang/missing_$lng.txt");
  $trimmed  =  array_map('trim', $untranslatedWords);
  if (!in_array($w, $trimmed))
    file_put_contents("lang/missing_$lng.txt", "$w\n", FILE_APPEND | LOCK_EX);
  return $w;
}

if (isset($_GET['lang'])) {
  setcookie("lng", $_GET['lang'], time() + 60 * 60 * 24 * 30, "/");
  header("Location: ?");
  die();
}

function __isUser($content)
{
  return Yii::$app->user->isGuest ? null : $content;
}

function set($model, $field)
{
  return isset($model[$field]) ? $model[$field] : null;
}

function fileSizeFormat($bytes)
{
  if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
  if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
  if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' kB';
  if ($bytes > 1) return $bytes . ' bytes';
  if ($bytes == 1) return $bytes . ' byte';
  return '0 bytes';
}

(new yii\web\Application($config))->run();
