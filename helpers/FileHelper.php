<?php

namespace app\helpers;

use app\models\GptQuestion;
use Exception;
use Yii;

class FileHelper
{
  public static function getFolder(string $folder = 'questions'): string
  {
    $id = Yii::$app->user->id;
    $path = Yii::getAlias('@runtime') . "/$folder/$id/";
    if (!file_exists($path)) mkdir($path, 0777, true);
    return $path;
  }

  public static function readFile(string $file, string $subfolder = 'questions'): string
  {
    $content = '';
    $file = self::getFolder($subfolder) . $file;
    if (file_exists($file)) $content = file_get_contents($file);
    return $content;
  }

  public static function readFiles(string $subfolder = 'questions'): array
  {
    $folder = self::getFolder($subfolder);
    $files = [];
    if (file_exists($folder)) {
      try {
        foreach (array_diff(scandir($folder), array('.', '..')) as $file)
          $files[] = $file;
      } catch (Exception $e) {
      }
    }
    return $files;
  }

  public static function saveResponseToFile(GptQuestion $model, string $filename, string $folder = 'questions'): void
  {
    file_put_contents(
      self::getFolder($folder) .  $filename . ".txt",
      $model->getFirstRow() . "\n\n" . $model->response,
      FILE_APPEND
    );
  }

  public static function LoadExcelFile($filename, $folder = EXCEL_FILES_PATH)
  {
    $id = Yii::$app->user->id;
    $data = null;
    $file = Yii::$app->basePath . "/web/$folder/$id/$filename";
    if (file_exists($file)) {
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      $data = $reader->load($file);
    }
    return $data;
  }

  public static function UploadFile($folder = EXCEL_FILES_PATH, $filename = 'excelFile')
  {
    $f_name = null;
    if (isset($_FILES[$filename])) {
      $id = Yii::$app->user->id;
      $target_dir = "$folder/$id/";
      if (!file_exists($target_dir))  mkdir($target_dir, 0777, true);

      $target_file = $target_dir . basename($_FILES[$filename]["name"]);
      if (!move_uploaded_file($_FILES[$filename]["tmp_name"], $target_file))
        throw new Exception("Sorry, there was an error uploading your file.");

      $f_name = basename($_FILES[$filename]["name"]);
    }
    return $f_name;
  }
}
