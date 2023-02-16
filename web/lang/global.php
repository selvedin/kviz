<?php
$en = 'en';
$globalLang = isset($_COOKIE['lng']) ? $_COOKIE['lng'] : $en;

$lang__ = require_once("lang/$en.php");
if (isset($globalLang)) {
  if (isset($_POST['Trans'])) resolveTranslations($_POST['Trans'], $globalLang);
  if (isset($_POST['Edit']))  resolveTranslations($_POST['Edit'], $globalLang);

  if (!file_exists("lang/$globalLang.php"))
    copy("lang/$en.php", "lang/$globalLang.php");
  $lang__ = require_once("lang/$globalLang.php");
}

function clearUntranslatedFiles()
{
  $files = scandir('lang');
  foreach ($files as $file)
    if (!in_array($file, ['.', '..']) && pathinfo($file, PATHINFO_EXTENSION) == "txt") {
      file_put_contents("lang/$file", "");
    }
}

function resolveTranslations($array, $lng)
{
  $existingWords = file_get_contents("lang/$lng.php");
  $existingWords = str_replace("];\n", "", $existingWords);
  $existingWords = str_replace("];", "", $existingWords);

  $wordsToAdd = "";
  foreach ($array as $key => $value)
    if ($value != "") $wordsToAdd  .= '"' . $key . '"=>"' . $value . '",' . "\n";

  unlink("lang/$lng.php");
  file_put_contents("lang/$lng.php", $existingWords, FILE_APPEND);
  file_put_contents("lang/$lng.php", $wordsToAdd, FILE_APPEND);
  file_put_contents("lang/$lng.php", "];", FILE_APPEND);

  // file_put_contents("lang/$lng.php", "];", FILE_APPEND);

  clearUntranslatedFiles();
}
