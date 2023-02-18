<?php

namespace app\controllers;

use app\models\Quiz;
use app\models\QuizConfig;
use app\models\search\QuizSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuizController implements the CRUD actions for Quiz model.
 */
class QuizController extends Controller
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
     * Lists all Quiz models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QuizSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quiz model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionRun($id)
    {
        return $this->render('run', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Quiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Quiz(['status' => 0]);

        if ($this->request->isPost) $this->saveModel($model, $this->request->post());
        else $model->loadDefaultValues();

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Quiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) $this->saveModel($model, $this->request->post());

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    private function saveModel($model, $data)
    {
        if ($model->load($data) && $model->save()) {
            if (isset($data['Quiz'])) $this->addData($model->id, $data['Quiz']);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    private function addData($id, $data)
    {
        $dataObjects = [
            'QuizConfig' => 'addConfig',
        ];
        foreach ($dataObjects as $name => $method)
            if (isset($data[$name])) $this->$method($id, $data[$name]);
    }

    private function addConfig($id, $data)
    {

        foreach ($data as $d) {
            if (!QuizConfig::find()->where([
                'quiz_id' => $id, 'num_of_questions' => $d['num_of_questions'],
                'grade' => $d['grade'], 'level' => $d['level'], 'category_id' => $d['category_id']
            ])->exists()) {
                $option = new QuizConfig([
                    'quiz_id' => $id, 'num_of_questions' => $d['num_of_questions'],
                    'grade' => $d['grade'], 'level' => $d['level'], 'category_id' => $d['category_id']
                ]);
                $option->save();
            }
        }
    }

    /**
     * Deletes an existing Quiz model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
