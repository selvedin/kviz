<?php

namespace app\controllers;

use Yii;
use app\helpers\CacheHelper;
use \app\models\Perms;

/**
 * PermsController implements the CRUD actions for Perms model.
 */
class PermsController extends AdminController
{
	/**
	 * @inheritdoc
	 */

	public function actionIndex()
	{
		$this->hasPermissions(4);
		$model = Perms::getAllData();
		return $this->render("index", ['model' => $model]);
	}

	public function actionSave()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$this->hasPermissions(4);
		if (Yii::$app->request->post()) {
			$model = $this->getModel(Yii::$app->request->post());
			if (!$model->save()) return ['error' => true, 'message' => $model->getErrorSummary(false)];
			CacheHelper::clearCache("perms_users");
		}
		return ['error' => false];
	}

	public function actionGet($object, $role)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$this->hasPermissions(1);
		$model = Perms::findOne($object);
		if (isset($model) && isset($model->perms[$role]))
			return $model->perms[$role][0];
		return null;
	}

	public function actionGetRolePermissions($role)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$this->hasPermissions(1);
		$KEY = $role . "_permissions";
		$permissions = CacheHelper::get($KEY);
		if ($permissions == false) {
			foreach (Perms::find()->select('object, perms')->all() as $model)
				if (isset($model->perms[$role][0]))
					$permissions[__($model->object)] = $model->perms[$role][0];
			CacheHelper::set($KEY, $permissions);
		}
		return $permissions;
	}

	private function getModel($data)
	{
		$id = $data['permObject'];
		$role = $data['role'];
		$permission = $data['permission'];
		$model = Perms::findOne($id);
		$perms = $model->perms;
		$perms[$role] = [$permission];
		$model->perms = serialize($perms);
		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action)
	{
		if (in_array($action->id, ['save'])) {
			$this->enableCsrfValidation = false;
		}

		return parent::beforeAction($action);
	}
}
