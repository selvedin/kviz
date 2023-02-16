<?php

namespace app\controllers;

use app\helpers\ImageHelper;
use Yii;
use app\models\Files;
use app\models\Places;
use Exception;
use Symfony\Component\Console\Exception\MissingInputException;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\Controller;

/**
 * FilesController implements the CRUD actions for Files model.
 */
class FilesController extends Controller
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
                    'upload' => ['POST'],
                    'delete-file' => ['POST'],
                    'unlink-file' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetFiles($object, $id = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ImageHelper::getImages($object, $id);
    }

    public function actionAddTag($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $file = Files::findOne($id);
        if (!$file) throw new HttpException(400);
        if ($this->request->isPost) {
            $data = $this->request->post();
            $tag = isset($data['tag']) ? $data['tag'] : null;
            $tags = explode(',', $file->tags);
            $tags[] = $tag;
            $file->tags = implode(",", $tags);
            $file->save();
        }
        return explode(",", $file->tags);
    }

    public function actionRemoveTag($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $file = Files::findOne($id);
        if (!$file) throw new HttpException(400);
        if ($this->request->isPost) {
            $data = $this->request->post();
            $tag = isset($data['tag']) ? $data['tag'] : null;
            $tags = explode(',', $file->tags);
            if (($key = array_search(trim($tag), $tags)) !== false)
                unset($tags[$key]);
            $file->tags = implode(",", $tags);
            $file->save();
        }
        return explode(",", $file->tags);
    }

    public function actionUpload($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $fileFormName = Yii::$app->params['fileFormName'];
        $model = Places::findOne($id);
        if (Yii::$app->request->post() && isset($_FILES[$fileFormName])) {
            if ($model) {
                for ($i = 0; $i < count($_FILES[$fileFormName]['name']); $i++) {
                    $this->uploadFiles($_FILES[$fileFormName]['name'][$i], $_FILES[$fileFormName]['tmp_name'][$i], $id);
                }
            } else throw new HttpException(500, 'Fajl nije snimljen.');
            return ImageHelper::getImages('places', $id);
        }
        throw new HttpException(500, 'File is not saved');
        return [];
    }

    public function actionUnlinkFile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($this->request->isPost) {
            $data = $this->request->post();
            $file = "images/places/" . $data['id'] . "/" . $data['name'];
            $fileThumb = "images/places/" . $data['id'] . "/thumbs/" . $data['name'];
            if (file_exists($file)) unlink($file);
            if (file_exists($fileThumb)) unlink($fileThumb);
            return ImageHelper::getImages('places', $data['id']);
        }
        return [];
    }
    public function actionDeleteFile($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        // $this->hasPermissions(5);
        $file = Files::findOne($id);
        $object = $file->object;
        $id = $file->id_object;
        $file->delete();
        return Files::getAllAsJson($object, $id);
    }

    public function actionDownload($id, $name)
    {
        $file = "images/places/$id/$name";
        if (file_exists($file))
            return Yii::$app->response->sendContentAsFile(file_get_contents($file), $name);
        throw new NotFoundHttpException(__("File not found") . ".");
    }

    public function actionShowImage($id, $name)
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $file = "images/places/$id/$name";
        if (file_exists($file))  return file_get_contents($file);
        throw new NotFoundHttpException(__("File not found") . ".");
    }

    public function uploadFile($file, $formData)
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

    public function uploadFiles($fileName, $tempName, $id, $object = 'places')
    {
        $targetDir = "images/$object/" . $id . "/";
        $thumbDir = "images/$object/" . $id . "/thumbs/";
        if (!file_exists($targetDir)) mkdir($thumbDir, 0775, TRUE);
        $baseName = basename($fileName);
        $targetFile = $targetDir . $baseName;
        $thumbFile = $thumbDir . $baseName;
        try {
            $this->resizeAndSaveImage($targetFile, $tempName,  Yii::$app->params['imageSize']);
            $this->resizeAndSaveImage($thumbFile, $tempName,  Yii::$app->params['thumbSize']);
            // move_uploaded_file($tempName, $targetFile);
            return "";
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function resizeAndSaveImage($path, $img, $width)
    {
        // $image = imagecreatefromstring(file_get_contents($img));
        $exif = exif_read_data($img);
        try {
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

                if (isset($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 8:
                            $old_image = imagerotate($old_image, 90, 0);
                            break;
                        case 3:
                            $old_image = imagerotate($old_image, 180, 0);
                            break;
                        case 6:
                            $old_image = imagerotate($old_image, -90, 0);
                            break;
                    }
                }

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
        } catch (Exception $e) {
            throw new MissingInputException($path . " - \n" . $e->getMessage() . "\n");
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['upload', 'unlink-file'])) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
}
