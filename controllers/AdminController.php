<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Perms;
use app\models\Files;
use app\models\User;
use Exception;
use yii\web\HttpException;

/**
 * AdminController is parent controller that implements the CRUD and other function for all child classes.
 * Child class can override methods or implement new ones if needed.
 */

define("FORM", "form");
define("VIEW", "view");
define("INDEX", "index");
define("PRINT_PAGE", "print");
define("BARCODE", "barode");
define("MAP", "map");
define("PRINT_EN", "prints/print");
define("PRINT_AR", "prints/print_ar");
class AdminController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'delete-image' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->hasPermissions(1);
        $model = $this->getModel();
        if (!$model)  return $this->goHome();
        $searchModel = $this->getSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 15;
        return $this->render(INDEX, ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, "model" => $model]);
    }

    /**
     * Displays a single Categories model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->hasPermissions(2);
        $model = $this->findModel($id);
        return $this->render(VIEW, ['model' => $model,]);
    }


    public function actionBarcode($id)
    {
        $this->hasPermissions(1);
        return $this->render(BARCODE, ['model' => $this->findModel($id),]);
    }

    public function actionMap($location = null)
    {
        $this->hasPermissions(1);
        if (null == $location) $location = Yii::$app->params['location'];
        return $this->render(MAP, ['location' => $location]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->hasPermissions(3);
        $model = $this->getModel();
        $this->updateModel($model);
        $primaryKey = $model->tableSchema->primaryKey[0];

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) return $this->redirect(['view', 'id' => $model->$primaryKey]);
            return $this->render('_form', ['model' => $model,]);
        }
        return $this->render('_form', ['model' => $model]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->hasPermissions(4);
        $model = $this->findModel($id);
        if (!User::isAdmin() && !$model->isPrivate()) {
            Yii::$app->session->setFlash("warning", __('You are not permited to edit this item') . '.');
            return $this->redirect([VIEW, 'id' => $id]);
        }
        $primaryKey = $model->tableSchema->primaryKey[0];
        $isRead = $model->hasMethod('isRead') ? $model->isRead() : -1;

        if (isset($_FILES, $_FILES['name'])) $this->uploadFiles($_FILES, $model);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) return $this->redirect(['view', 'id' => $model->$primaryKey]);
            return $this->render('_form', ['model' => $model,]);
        }

        if ($isRead == 0) {
            if (isset($_GET['dlg'])) return $this->redirect(['view', 'id' => $model->$primaryKey, 'dlg' => 1]);
            return $this->redirect(['view', 'id' => $model->$primaryKey]);
        }

        return $this->render('_form', ['model' => $model]);
    }

    public function actionPrint($id, $title = '', $document = 'default')
    {
        $this->hasPermissions(1);
        $content = $id ? $this->findModel($id) : $this->getModel();
        return $this->render(PRINT_PAGE, ['title' => $title, 'document' => $document, 'content' => $content]);
    }

    public function actionClone($id)
    {
        $this->hasPermissions(4);
        $model = $this->findModel($id);
        $primaryKey = $model->tableSchema->primaryKey[0];
        $clone = new $this->getModel();
        $clone->attributes = $model->attributes;
        if ($model->title) $clone->title = $model->title . ' - CLONE';
        if ($model->name) $clone->name = $model->name . ' - CLONE';
        $clone->$primaryKey = null;
        $clone->save();

        return $this->redirect(['view', 'id' => $clone->$primaryKey]);
    }
    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->hasAttribute('created_by') && $model->created_by != Yii::$app->user->id)
            $this->hasPermissions(5);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelName = "app\models\\" . $this->getObjectName();

        if (($model = $modelName::findOne($id)) !== null) return $model;
        throw new NotFoundHttpException(__('The requested page does not exist') . '.');
    }

    protected function hasPermissions($level = 5, $object = null)
    {
        $permissions = new Perms();
        $can = false;
        if ($object == null)  $object = $this->getObjectName();
        switch ($level) {
            case 1:
                $can = $permissions->canList($object);
                break;
            case 2:
                $can = $permissions->canView($object);
                break;
            case 3:
                $can = $permissions->canCreate($object);
                break;
            case 4:
                $can = $permissions->canUpdate($object);
                break;
            case 5:
                $can = $permissions->canDelete($object);
                break;
            default:
                break;
        }
        if (!$can) throw new HttpException(403, __(NO_PERMISSION_MESSAGE));
    }

    public function UploadFile($file, $formData)
    {
        $SHA = sha1(microtime());
        $fileName = $file['name'];
        $path = Yii::$app->basePath . '/docs';
        if (!file_exists($path)) mkdir($path, 0775, TRUE);

        $newFile = new Files();
        $newFile->name = $fileName;
        // $newFile->f_type = $t ? $t : $_POST['filetype'];
        // $newFile->private = (int)@$_POST['private'];
        $newFile->f_name = $SHA;
        $newFile->f_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFile->title = basename($fileName, "." . $newFile->f_ext);
        $newFile->f_date = date("Y-m-d", filemtime($file['tmp_name']));
        $newFile->f_size = filesize($file['tmp_name']);
        $newFile->f_date = date("Y-m-d H:i:s", filemtime($file['tmp_name']));
        $newFile->object = $formData['controller'] . "/" . $formData['action'];
        $newFile->id_object = $formData['id'];

        if ($newFile->save())  copy($file['tmp_name'], $path . "/" . $SHA);
        else throw new HttpException(500, serialize($newFile->errors));
    }


    public function uploadFiles($file, $id, $object = 'products')
    {
        $fileName = $file["name"];
        $targetDir = "images/$object/" . $id . "/";
        $thumbDir = "images/$object/" . $id . "/thumbs/";
        if (!file_exists($targetDir)) mkdir($thumbDir, 0775, TRUE);
        $baseName = basename($fileName);
        $targetFile = $targetDir . $baseName;
        $thumbFile = $thumbDir . $baseName;
        try {
            $this->resizeAndSaveImage($targetFile, $file["tmp_name"],  Yii::$app->params['imageSize']);
            $this->resizeAndSaveImage($thumbFile, $file["tmp_name"],  Yii::$app->params['thumbSize']);
            // move_uploaded_file($file["tmp_name"], $targetFile);
            return "";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function resizeAndSaveImage($path, $img, $width)
    {
        $thumbnail_width = $width;
        $arr_image_details = getimagesize($img); // pass id to thumb name
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];

        $new_width = $thumbnail_width;
        $new_height = intval($original_height * $new_width / $original_width);

        if ($arr_image_details[2] == IMAGETYPE_JPEG) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == IMAGETYPE_PNG) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom($img);
            $new_image = imagecreatetruecolor($new_width, $new_height);
            if ($arr_image_details[2] == IMAGETYPE_PNG) {
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
            }

            imagecopyresized(
                $new_image,
                $old_image,
                0,
                0,
                0,
                0,
                $new_width,
                $new_height,
                $original_width,
                $original_height
            );
            $imgt($new_image, $path);
        }
    }

    protected function getObjectName()
    {
        return ucfirst(Yii::$app->controller->id);
    }

    private function getModel()
    {
        $modelName = "app\models\\" . $this->getObjectName();
        if (!file_exists('../models/' . $this->getObjectName() . '.php'))
            throw new NotFoundHttpException(__('The requested page does not exist') . '.');
        return new $modelName();
    }

    private function getSearchModel()
    {
        $searchModelName = "app\models\search\\" . $this->getObjectName() . "Search";
        if (!file_exists('../models/search/' . $this->getObjectName() . 'Search.php'))
            throw new NotFoundHttpException(__('The requested page does not exist') . '.');
        return new $searchModelName();
    }

    public function updateModel(&$model)
    {
    }
}
