<?php

namespace app\controllers;

use app\models\Options;
use app\models\Pairs;
use app\models\Perms;
use app\models\Question;
use app\models\search\QuestionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\HttpException;
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
                        'activate' => ['POST'],
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
        $perms = new Perms();
        if (!$perms->canList('Question')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
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
        $perms = new Perms();
        if (!$perms->canView('Question')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
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
        $perms = new Perms();
        if (!$perms->canCreate('Question')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = new Question(['status' => 0, 'question_type' => 1]);

        if ($this->request->isPost) $this->saveModel($model, $this->request->post());
        else $model->loadDefaultValues();

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    private function saveModel($model, $data)
    {
        if ($model->load($data) && $model->save()) {

            if (isset($data['Question'])) {
                if ($model->question_type == Question::TYPE_INPUT) $this->addOption($model->id, $data['Question']);
                else $this->addData($model->id, $data['Question']);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    private function addOption($id, $data)
    {
        $option = Options::find()->where(['question_id' => $id])->one();
        if (isset($option) && isset($data['Options'])) $option->content = $data['Options'][0]['content'];
        else $option = new Options(['question_id' => $id, 'content' => $data['Options'][0]['content'], 'is_true' => 1]);
        $option->save();
    }

    private function addData($id, $data)
    {
        $dataObjects = [
            'Options' => 'addOptions',
            'Pairs' => 'addPairs',
        ];
        foreach ($dataObjects as $name => $method)
            if (isset($data[$name])) $this->$method($id, $data[$name]);
    }

    private function addOptions($id, $data)
    {
        if (isset($data['is_true'])) {
            $model = Options::find()->where(['question_id' => $id, 'content' => 'tf'])->one();
            if (isset($model)) $model->is_true = $data['is_true'];
            else {
                $model = new Options(['question_id' => $id, 'content' => 'tf', 'is_true' => $data['is_true']]);
            }
            $model->save();
            return;
        }
        foreach ($data as $d) {
            if (!Options::find()->where(['question_id' => $id, 'content' => $d['content']])->exists()) {
                $option = new Options(['question_id' => $id, 'content' => $d['content'], 'is_true' => 1]);
                if (!$option->save()) $this->showErrors($option);
            }
        }
    }

    private function addPairs($id, $data)
    {
        foreach ($data as $d) {
            if (!Pairs::find()->where(['question_id' => $id, 'one' => $d['one'], 'two' => $d['two']])->exists()) {
                $option = new Pairs(['question_id' => $id, 'one' => $d['one'], 'two' => $d['two']]);
                if (!$option->save()) $this->showErrors($option);
            }
        }
    }

    private function showErrors($option)
    {
        $errors = "";
        foreach ($option->errors as $error) $errors .= $error[0] . "\n";
        Yii::$app->session->setFlash('error', $errors);
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
        $perms = new Perms();
        if (!$perms->canUpdate('Question') || $this->isPrivate($model->created_by))
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));

        if ($this->request->isPost)
            $this->saveModel($model, $this->request->post());

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
        $model = $this->findModel($id);
        $perms = new Perms();
        if (!$perms->canDelete('Question') || $this->isPrivate($model->created_by))
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionActivate($id)
    {
        $perms = new Perms();
        if (!$perms->canUpdate('Question')) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        $model = $this->findModel($id);
        $model->status = 1; //active
        $model->save();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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

    public function actionDeleteOptions($id, $isPair = false)
    {
        $model = Options::findOne($id);
        $perms = new Perms();
        if (!$perms->canDelete('Question') || $this->isPrivate($model->created_by))
            throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($isPair)  Pairs::findOne($id)->delete();
        else Options::findOne($id)->delete();
        return ['message' => __('Option deleted')];
    }

    private function isPrivate($creator)
    {
        if (Yii::$app->user->identity->role->private)
            if ($creator != Yii::$app->user->id) return true;
        return false;
    }
}
