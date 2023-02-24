<?php

namespace app\controllers;

use Yii;
use app\models\Quiz;
use app\models\QuizResults;
use app\models\QuizTemp;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;

/**
 * PlayerController implements Quiz player model.
 */
class PlayerController extends Controller
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
     * Displays a single Quiz model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model =  $this->findActive($id);
        return $this->render('view', [
            'id' => $model->id,
            'model' => $model->quizObject,
            'questions' => unserialize($model->quiz),
        ]);
    }

    public function actionResults($id)
    {
        if (Yii::$app->user->isGuest) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model =  $this->findActive($id);
        return $this->render('results', [
            'results' => QuizResults::find()->where([
                'quiz_id' => $model->quiz_id,
                'temp_id' => $model->id,
                'competitor_id' => Yii::$app->user->isGuest ? 0 : Yii::$app->user->id
            ])->one()
        ]);
    }

    /**
     * Finds the Quiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Quiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quiz::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(__('The requested page does not exist.'));
    }

    protected function findActive($id)
    {
        if (($model = QuizTemp::findOne(['id' => $id, 'active' => 1])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(__('The requested page does not exist.'));
    }
}
