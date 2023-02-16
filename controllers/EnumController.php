<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use Yii;
use yii\web\Response;

/**
 * EnumController - Base controller for all modes that has title field only.
 */
class EnumController extends Controller
{

    const ENUM_FORM = '/enum/_form';
    const ENUM_VIEW = '/enum/view';
    const ENUM_INDEX = '/enum/index';
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
     * Lists all Sciences models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = $this->getSearchModel();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render(self::ENUM_INDEX, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelName' => $this->getModelName()
        ]);
    }

    /**
     * Displays a single Sciences model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render(self::ENUM_VIEW, [
            'model' => $this->findModel($id),
            'modelName' => $this->getModelName()
        ]);
    }

    /**
     * Creates a new Sciences model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = $this->getModel();


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save())
                return $this->redirect(['index']);
        }
        $model->loadDefaultValues();

        return $this->render(self::ENUM_FORM, ['model' => $model, 'modelName' => $this->getModelName()]);
    }

    /**
     * Updates an existing Sciences model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render(self::ENUM_FORM, [
            'model' => $model,
            'modelName' => $this->getModelName()
        ]);
    }

    /**
     * Deletes an existing Sciences model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Enum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Enum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelName = $this->getModel();
        if (($model = $modelName::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(__('The requested page does not exist.'));
    }

    private function getModelName()
    {
        $model = "";
        $controller = Yii::$app->controller->id;
        $parts = explode('-', $controller);
        foreach ($parts as $v) $model .= ucfirst($v);
        return $model;
    }

    private function getModel()
    {
        $model = "\\app\\models\\" . $this->getModelName();
        return new $model();
    }

    private function getSearchModel()
    {
        $model = "\\app\\models\\search\\" . $this->getModelName() . "Search";
        return new $model();
    }


    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($this->request->isPost) {
            $data = Yii::$app->request->post();
            $modelName = "\\app\\models\\" . $data['enum'];
            $type = new $modelName();
            $type->title = $data['title'];
            if (!$type->save()) return ['error' => $type->getErrorSummary(true)];
        }
        return $modelName::list();
    }
}
