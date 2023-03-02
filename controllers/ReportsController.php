<?php

namespace app\controllers;

use app\models\Perms;
use app\models\Quiz;
use app\models\QuizResults;
use app\models\QuizTemp;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * ReportsController.
 */
class ReportsController extends Controller
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

  /**
   * Lists all Quiz runned by current user.
   *
   * @return string
   */
  public function actionIndex()
  {
    $perms = new Perms();
    if (!$perms->canList('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
    $id = Yii::$app->user->id;
    // $status = "active=" . Quiz::STATUS_RUNNING;
    $where = "id IN (SELECT temp_id from quiz_results where competitor_id=$id)";
    $quizes = QuizTemp::find()->where($where)->select(['id', 'quiz_id', 'active'])->all();

    return $this->render('index', ['quizes' => $quizes]);
  }

  public function actionView($id, $moderated = false)
  {
    $model = $moderated ? QuizTemp::findOne($id) : QuizResults::findOne($id);
    return $this->render($moderated ? 'details' : 'view', ['model' => $model]);
  }

  public function actionModerated()
  {
    $perms = new Perms();
    if (!$perms->canList('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
    $id = Yii::$app->user->id;
    $where = "id IN (SELECT temp_id from quiz_results where quiz_id IN (SELECT id from quiz where moderator_id=$id))";
    $quizes = QuizTemp::find()->where($where)->select(['id', 'quiz_id', 'active'])->all();
    return $this->render('moderated', ['quizes' => $quizes]);
  }
}
