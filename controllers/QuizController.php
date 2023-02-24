<?php

namespace app\controllers;

use app\models\Perms;
use app\models\Quiz;
use app\models\QuizConfig;
use app\models\QuizResults;
use app\models\QuizTemp;
use app\models\search\QuizSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\HttpException;
use yii\web\Response;

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
                        'save-results' => ['POST'],
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
        $perms = new Perms();
        if (!$perms->canList('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
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
        $perms = new Perms();
        if (!$perms->canView('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        return $this->render('view', [
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
        $perms = new Perms();
        if (!$perms->canCreate('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
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
        $perms = new Perms();
        if (!$perms->canUpdate('Quiz') || $model->isPrivate())
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));

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
            if (!QuizConfig::find()->where($this->getWhere($id, $d))->exists()) {
                $option = new QuizConfig([
                    'quiz_id' => $id, 'num_of_questions' => $d['num_of_questions'], 'question_type' => $d['question_type'],
                    'grade' => $d['grade'], 'level' => $d['level'], 'category_id' => $d['category_id']
                ]);
                if (!$option->save()) {
                    $errors = "";
                    foreach ($option->errors as $error) $errors .= $error[0] . "\n";
                    Yii::$app->session->setFlash('error', $errors);
                } else {
                    $model = Quiz::findOne($id);
                    $model->save();
                }
            }
        }
    }

    private function getWhere($id, $d)
    {
        $where = "quiz_id=$id";
        if (!empty($d['num_of_questions'])) $where .= " AND num_of_questions=" . $d['num_of_questions'];
        if (!empty($d['question_type'])) $where .= " AND question_type=" . $d['question_type'];
        if (!empty($d['grade'])) $where .= " AND grade=" . $d['grade'];
        if (!empty($d['level'])) $where .= " AND level=" . $d['level'];
        if (!empty($d['category_id'])) $where .= " AND category_id=" . $d['category_id'];
        return $where;
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
        $model = $this->findModel($id);
        $perms = new Perms();
        if (!$perms->canDelete('Quiz') || $model->isPrivate())
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionDeleteConfig($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = QuizConfig::findOne($id);
        $quiz = Quiz::findOne($model->quiz_id);
        $perms = new Perms();
        if (!$perms->canDelete('Quiz') || $model->isPrivate())
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model->delete();
        $quiz->save();
        return ['message' => __('Config deleted')];
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
        if (!$perms->canView('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = $this->findModel($id);
        $data = []; //$model->generateQuestions();
        //TODO - fix this part of printing pdf for the quiz
        return $this->render('pdf', ['model' => $model, 'questions' => $data['questions']]);
    }

    public function actionPrepare($id)
    {
        $perms = new Perms();
        if (!$perms->canView('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = $this->findModel($id);
        $model->generateQuestions(false); // generate new
        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionActivate($id, $active)
    {
        $perms = new Perms();
        if (!$perms->canUpdate('Quiz')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = QuizTemp::findOne($id);
        $model->active = $active;
        $model->save();
        return $this->redirect(['view', 'id' => $model->quizObject->id]);
    }

    /**
     * Exports generated questions for a Quiz to PDF.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionSaveResults($id, $temp)
    {
        $data = [];
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($this->request->isPost) {
            if ($temp)  $model = QuizTemp::findOne($temp);
            else $model = QuizTemp::getById($id);
            if ($model) {
                if ($model->results) {
                    $oldData = unserialize($model->results);
                    $oldData[Yii::$app->user->id] = serialize($this->request->post());
                } else $oldData[Yii::$app->user->id] = serialize($this->request->post());
                $model->results = serialize($oldData);
                if (!$model->save())  throw new HttpException(500, json_encode($model->errors));
                $data = $model->processResults();
            } else throw new HttpException(500, __('Can not find model'));
        }
        return $data;
    }

    private function addResult($id, $d)
    {
        $result = new QuizResults();
        $result->quiz_id = $id;
        $result->question_id = (int)$d['question'];
        $result->competitor_id = Yii::$app->user->id;
        $result->answer_id = (int)$d['answer'];
        $result->save();
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
}
