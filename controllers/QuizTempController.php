<?php

namespace app\controllers;

use Yii;
use app\models\Perms;
use app\models\QuizCompetitors;
use app\models\QuizTemp;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\Response;

/**
 * QuizTempController implements the CRUD actions for QuizTemp model.
 */
class QuizTempController extends Controller
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
                        'add-competitor' => ['POST'],
                        'delete-competitor' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Exports generated questions for a Quiz to PDF.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPdf($id)
    {
        $perms = new Perms();
        if (!$perms->canView('QuizTemp')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = $this->findModel($id);

        return $this->render('pdf', ['model' => $model]);
    }

    public function actionAddCompetitor($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);
        if ($this->request->isPost) {
            $data = $this->request->post();
            $model = new QuizCompetitors([
                'quiz_id' => $data['quiz_id'],
                'temp_id' => $id,
                'competitor_id' => $data['competitor_id']
            ]);
            if (!$model->save()) throw new HttpException(500, json_encode($model->errors));
        }
        return [];
    }

    public function actionDeleteCompetitor($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = QuizCompetitors::findOne($id);
        if ($model) $model->delete();
        return [];
    }
    /**
     * Deletes an existing QuizTemp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $perms = new Perms();
        if (!$perms->canDelete('QuizTemp') || $model->isPrivate())
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }
    /**
     * Finds the QuizTemp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return QuizTemp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QuizTemp::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(__('The requested page does not exist.'));
    }
}
