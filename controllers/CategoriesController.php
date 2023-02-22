<?php

namespace app\controllers;

use Yii;
use app\models\Categories;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends AdminController
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
            'remove' => ['POST'],
            'add' => ['POST'],
          ],
        ],
      ]
    );
  }

  public function actionAdd()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $this->hasPermissions(3);
    if (Yii::$app->request->post()) {
      $data['Categories'] = Yii::$app->request->post();
      if ($data['Categories']['id']) $model = Categories::findOne((int)$data['Categories']['id']);
      if (!isset($model)) $model = new Categories();
      $model->load($data);
      $model->save();
    }
    return ['category' => $model->mapToJson(), 'categories' => Categories::getAll(), 'error' => false];
  }

  public function actionGetAll()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $this->hasPermissions(1);
    return Categories::getAll();
  }

  public function actionGetFullname($id)
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $this->hasPermissions(1);
    $category = Categories::findOne($id);
    return $category->getFullname();
  }

  public function actionGetByParent($id)
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $this->hasPermissions(1);
    return ArrayHelper::map(Categories::find()->where("parent=$id")->select(['id', 'name'])->all(), 'id', 'name');
  }


  public function actionRemove($id)
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $this->hasPermissions(5);
    $model = Categories::findOne($id);
    $model->delete();
    return ['categories' => Categories::getAll(), 'error' => false];
  }

  /**
   * @inheritdoc
   */
  public function beforeAction($action)
  {
    if (in_array($action->id, ['remove', 'add'])) {
      $this->enableCsrfValidation = false;
    }

    return parent::beforeAction($action);
  }
}
