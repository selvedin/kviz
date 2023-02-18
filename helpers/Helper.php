<?php

namespace app\helpers;

use Yii;

class Helper
{

  public static function removeNewLine($words)
  {
    if ((strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0))
      return explode("\r\n", $words);
    return explode("\n", ltrim($words));
  }

  public static function getLang()
  {
    return isset($_COOKIE['lng']) ? $_COOKIE['lng'] : 'en';
  }

  public static function getExistingWords()
  {
    $lang = self::getLang();
    $w = file_get_contents("lang/$lang.php");
    $w = str_replace("];\n", "", $w);
    $w = str_replace("];", "", $w);
    return $w;
  }

  public static function addNewTranslations($words)
  {
    $lng = self::getLang();
    $existingWords = self::getExistingWords();

    unlink("lang/$lng.php");
    file_put_contents("lang/$lng.php", $existingWords, FILE_APPEND);
    file_put_contents("lang/$lng.php", $words, FILE_APPEND);
    file_put_contents("lang/$lng.php", "];", FILE_APPEND);
  }

  public static function Menus()
  {
    $isGuest = Yii::$app->user->isGuest;
    $canSee = true;
    return [
      [
        'icon' => 'home', 'title' => 'Početna', 'url' => null, 'visible' => $canSee,
        'items' => [
          ['icon' => 'question-mark', 'title' => 'Pitanja', 'url' => '/question', 'visible' => $canSee],
          ['icon' => 'tournament', 'title' => 'Kvizovi', 'url' => '/quiz', 'visible' => $canSee],
        ]
      ],
      [
        'icon' => 'settings', 'title' => 'Podešavanja', 'url' => null, 'visible' => $canSee,
        'items' => [
          ['icon' => 'category', 'title' => 'Kategorije', 'url' => '/categories', 'visible' => $canSee],

        ]
      ],
    ];
  }
}
