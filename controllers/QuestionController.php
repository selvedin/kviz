<?php

namespace app\controllers;

use app\models\Options;
use app\models\Question;
use app\models\search\QuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
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
                        'delete-option' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Question models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Question model.
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

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Question();

        if ($this->request->isPost) {
            $data = $this->request->post();
            if ($model->load($data) && $model->save()) {
                if (isset($data['Question']['Options']))
                    $this->addData($model->id, $data['Question']);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    private function addData($id, $data)
    {
        $dataObjects = [
            'Options' => 'addOptions',
        ];
        foreach ($dataObjects as $name => $method)
            if (isset($data[$name]))
                $this->$method($id, $data[$name]);
    }

    private function addOptions($id, $data)
    {
        foreach ($data as $d) {
            if (!Options::find()->where(['question_id' => $id, 'content' => $d['content']])->exists()) {
                $option = new Options(['question_id' => $id, 'content' => $d['content'], 'is_true' => (int)$d['is_true']]);
                $option->save();
            }
        }
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Question model.
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
     * Finds the Question model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Question::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDeleteOptions($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Options::findOne($id)->delete();
        return ['message' => __('Option deleted')];
    }
}
