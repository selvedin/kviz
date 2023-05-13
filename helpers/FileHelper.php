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
}
