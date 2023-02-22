<?php

namespace app\controllers;

use Yii;
use app\models\Settings;
use app\models\search\SettingsSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends AdminController
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
                        'add' => ['POST'],
                        'remove' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionObjects()
    {
        return $this->render('objects');
    }

    public function actionEnums()
    {
        return $this->render('enums');
    }

    public function actionCompany()
    {
        $model = Settings::getSetting(Settings::COMPANY_SETTING_KEY);
        $fields = Settings::getGeneral(Settings::COMPANY_SETTING_KEY);
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            unset($data['_csrf']);
            $model->text_value = serialize($data);
            $model->save();
        }
        return $this->render(
            'company',
            [
                'model' => unserialize($model->text_value),
                'fields' => $fields
            ]
        );
    }

    public function actionGeneral()
    {
        $model = Settings::getSetting(Settings::GENERAL_SETTING_KEY);
        $fields = Settings::getGeneral(Settings::GENERAL_SETTING_KEY);
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            unset($data['_csrf']);
            $model->text_value = serialize($data);
            $model->save();
        }
        return $this->render(
            'general',
            [
                'model' => unserialize($model->text_value),
                'fields' => $fields
            ]
        );
    }

    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->hasPermissions(3);
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            if ($data['Settings']['id'] != '') $model = Settings::findOne((int)$data['Settings']['id']);
            if (!isset($model)) {
                $model = new Settings();
                $data['Settings']['text_value'] = json_encode([["val" => "Title"]]);
            }
            $model->load($data);
            $model->save();
        }
        return $model->type ? ["setting" => $model->mapToJson(), "settings" => $this->getAll($model->type)] : ["setting" => [], "settings" => []];
    }

    public function actionAddField()
    {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->hasPermissions(3);
        $model = Settings::findOne($id);
        if (isset($model)) {
            $fields = json_decode($model->text_value, true);
            $fields[] = ["val" => $content];
            $model->text_value = json_encode($fields);
            $model->save();
        }
        return ["setting" => $model->mapToJson(), "settings" => $this->getAll($model->type)];
    }

    public function actionAddImage()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($_FILES["logoImage"])) {
            $file = "images/logo.png";
            move_uploaded_file($_FILES["logoImage"]["tmp_name"], $file);
        }
        return 'logo.png';
    }

    public function actionGetAll($type)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->hasPermissions(1);
        return $type ? $this->getAll($type) : [];
    }

    public function actionRemove($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->hasPermissions(5);
        $model = Settings::findOne($id);
        if (isset($model)) $type =  $model->type;
        $model->delete();
        return isset($type) ? $this->getAll($type) : [];
    }

    private function getAll($type)
    {
        $all = [];
        foreach (Settings::find()->where("type='$type'")->all() as $setting)
            $all[] = $setting->mapToJson();
        return $all;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['remove', 'add-field', 'add-image'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
}
