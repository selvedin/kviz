<?php

namespace app\controllers;

use Yii;
use app\models\Quiz;
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
        if (Yii::$app->user->isGuest)
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model =  $this->findActive($id);
        // $data = $model->generateQuestions();
        // if (!isset($data['questions']))
        //     Yii::$app->session->setFlash(
        //         'error',
        //         __('There are no questions satisfying the quiz criteria. The question has to be in an Active state to be ready for the quiz.')
        //     );
        return $this->render('view', [
            'id' => $model->id,
            'model' => $model->quizObject,
            'questions' => unserialize($model->quiz)
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
        if (($model = QuizTemp::findOne(['quiz_id' => $id, 'active' => 1])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(__('The requested page does not exist.'));
    }
}
