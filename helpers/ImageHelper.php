<?php

namespace app\helpers;

use Yii;
use Exception;

class ImageHelper
{

  public static function makeThumb($updir, $img)
  {
    $thumbnail_width = 200;
    $arr_image_details = getimagesize("$updir" . "$img"); // pass id to thumb name
    $original_width = $arr_image_details[0];
    $original_height = $arr_image_details[1];

    $new_width = $thumbnail_width;
    $new_height = intval($original_height * $new_width / $original_width);

    if ($arr_image_details[2] == IMAGETYPE_GIF) {
      $imgt = "ImageGIF";
      $imgcreatefrom = "ImageCreateFromGIF";
    }
    if ($arr_image_details[2] == IMAGETYPE_JPEG) {
      $imgt = "ImageJPEG";
      $imgcreatefrom = "ImageCreateFromJPEG";
    }
    if ($arr_image_details[2] == IMAGETYPE_PNG) {
      $imgt = "ImagePNG";
      $imgcreatefrom = "ImageCreateFromPNG";
    }
    if ($imgt) {
      $old_image = $imgcreatefrom("$updir" . "$img");
      $new_image = imagecreatetruecolor($new_width, $new_height);
      imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
      $imgt($new_image, "$updir/thumbs/" . "$img");
    }
  }

  public static function getImages($object, $id, $thumb = true)
  {
    $folder = "images/$object/$id/";
    $files = [];
    if ($thumb) $folder .= "/thumbs/";
    if (file_exists($folder)) {
      try {
        foreach (array_diff(scandir($folder), array('.', '..')) as $file)
          $files[] = $file;
      } catch (Exception $e) {
        return $files;
      }
    }
    return $files;
  }

  public static function ImageFolder($full = true)
  {
    $id = Yii::$app->user->id;
    $part = "uploads/$id/";
    $folder = Yii::$app->basePath . "/" . $part;
    if (!file_exists($folder)) mkdir($folder, 0777, true);
    return $full ? $folder : $part;
  }

  public static function saveImageToServer($image)
  {
    if (isset($_FILES[$image])) {
      $id = Yii::$app->user->id;
      $fileName = $id . "_" . date("Y_m_d__H_i_s");
      $file = self::ImageFolder() . $fileName;
      if (move_uploaded_file($_FILES[$image]["tmp_name"], $file))
        return $fileName;
    }
    return null;
  }
}
