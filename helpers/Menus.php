<?php

namespace app\helpers;

use Yii;
use app\models\Perms;

class Menus
{
  public static function all()
  {
    $perms = new Perms();
    $canSeeHome = $perms->canList('Question') || $perms->canList('Quiz');
    $canSeeSettings = $perms->canList('Categories');
    return [
      [
        'icon' => 'books', 'title' => 'Moduli', 'url' => null, 'visible' => $perms->canList('Modules'),
        'items' => [
          ['icon' => 'question-mark', 'title' => 'Kviz', 'url' => Yii::$app->params['quiz_url'], 'visible' => $perms->canList('Quiz')],
          ['icon' => 'file-invoice', 'title' => 'Pripreme', 'url' => Yii::$app->params['pripreme_url'], 'visible' => $perms->canCreate('Pripreme')],
          ['icon' => 'books', 'title' => 'Knjige', 'url' => Yii::$app->params['books_url'], 'visible' => $perms->canList('Books')],
        ]
      ],
      [
        'icon' => 'question-mark', 'title' => 'Pitanja', 'url' => null, 'visible' => $perms->canList('Question'),
        'items' => [
          ['icon' => 'list', 'title' => 'Lista pitanja', 'url' => '/question', 'visible' => $perms->canList('Question')],
          ['icon' => 'plus', 'title' => 'Dadaj pitanje', 'url' => '/question/create', 'visible' => $perms->canCreate('Question')],
          ['icon' => 'upload', 'title' => 'Importuj pitanja', 'url' => '/question/import', 'visible' => $perms->canCreate('Question')],
          ['icon' => 'robot', 'title' => 'Generiši pitanja', 'url' => '/gpt/question', 'visible' => $perms->canCreate('GenerateQuestion')],
          ['icon' => 'scan', 'title' => 'Skeniraj lekciju', 'url' => '/gpt/ocr', 'visible' => $perms->canCreate('Ocr')],
        ]
      ],
      [
        'icon' => 'tournament', 'title' => 'Kvizovi', 'url' => null, 'visible' => $perms->canList('Question'),
        'items' => [
          ['icon' => 'list', 'title' => 'Kvizovi', 'url' => '/quiz', 'visible' => $perms->canList('Quiz')],
          ['icon' => 'plus', 'title' => 'Napravi kviz', 'url' => '/quiz/create', 'visible' => $perms->canCreate('Quiz')],
        ]
      ],
      [
        'icon' => 'report-analytics', 'title' => 'Rezultati', 'url' => null, 'visible' => $perms->canList('Results'),
        'items' => [
          ['icon' => 'report-analytics', 'title' => 'Moji kvizovi', 'url' => '/reports', 'visible' => $perms->canList('Quiz')],
          ['icon' => 'report-analytics', 'title' => 'Moderirani kvizovi', 'url' => '/reports/moderated', 'visible' => $perms->canList('Quiz')],
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
