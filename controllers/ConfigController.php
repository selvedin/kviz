<?php

namespace app\controllers;

use yii\web\Controller;
use Exception;
use yii\filters\VerbFilter;
use Yii;

class ConfigController extends Controller
{
  /**
   * @inheritDoc
   */
  public function behaviors()
  {
    return array_merge(
      parent::behaviors(),
      [
        'verbs' => [
          'class' => VerbFilter::class,
          'actions' => [
            'delete' => ['POST'],
          ],
        ],
      ]
    );
  }
}
