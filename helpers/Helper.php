<?php

namespace app\helpers;

use app\models\Perms;
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
    $perms = new Perms();
    $canSeeHome = $perms->canList('Question') || $perms->canList('Quiz');
    $canSeeSettings = $perms->canList('Categories');
    return [
      [
        'icon' => 'home', 'title' => 'Početna', 'url' => null, 'visible' => $canSeeHome,
        'items' => [
          ['icon' => 'question-mark', 'title' => 'Pitanja', 'url' => '/question', 'visible' => $perms->canList('Question')],
          ['icon' => 'tournament', 'title' => 'Kvizovi', 'url' => '/quiz', 'visible' => $perms->canList('Quiz')],
        ]
      ],
      [
        'icon' => 'settings', 'title' => 'Podešavanja', 'url' => null, 'visible' => $canSeeSettings,
        'items' => [
          ['icon' => 'category', 'title' => 'Kategorije', 'url' => '/categories', 'visible' => $perms->canList('Categories')],
          ['icon' => 'stack-pop', 'title' => 'Razredi', 'url' => '/grade', 'visible' => $perms->canList('Grade')],
          ['icon' => 'building', 'title' => 'Škola', 'url' => '/settings/company', 'visible' => $perms->canUpdate('Settings')],
          ['icon' => 'adjustments-alt', 'title' => 'Generalno', 'url' => '/settings/general', 'visible' => $perms->canUpdate('Settings')],

        ]
      ],
      [
        'icon' => 'lock', 'title' => 'Pristup', 'url' => null, 'visible' => $perms->canList('User'),
        'items' => [
          ['icon' => 'users', 'title' => 'Korisnici', 'url' => '/user', 'visible' => $perms->canList('User')],
          ['icon' => 'lock-off', 'title' => 'Korisničke uloge', 'url' => '/roles', 'visible' => $perms->canList('Roles')],
          ['icon' => 'lock-off', 'title' => 'Permisije', 'url' => '/perms', 'visible' => $perms->canList('Perms')],
        ]
      ],
    ];
  }
}
