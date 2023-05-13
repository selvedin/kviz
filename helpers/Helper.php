<?php

namespace app\helpers;

class Helper
{

  public static function removeNewLine($words)
  {
    if ((strcasecmp(substr(PHP_OS, 0, 3), 'WIN') == 0))
      return explode("\r\n", $words);
    return explode("\n", ltrim($words));
  }

  public static function getLang()
  {
    return isset($_COOKIE['lng']) ? $_COOKIE['lng'] : 'en';
  }

  public static function getExistingWords()
  {
    $lang = self::getLang();
    $w = file_get_contents("lang/$lang.php");
    $w = str_replace("];\n", "", $w);
    $w = str_replace("];", "", $w);
    return $w;
  }

  public static function addNewTranslations($words)
  {
    $lng = self::getLang();
    $existingWords = self::getExistingWords();

    unlink("lang/$lng.php");
    file_put_contents("lang/$lng.php", $existingWords, FILE_APPEND);
    file_put_contents("lang/$lng.php", $words, FILE_APPEND);
    file_put_contents("lang/$lng.php", "];", FILE_APPEND);
  }

  public static function formatResponse($response, $isHtml = true)
  {
    $chars = [
      '\xc4\x8d' => 'č',
      '\xc4\x87' => 'ć',
      '\xc4\x91' => 'đ',
      '\xc5\xa1' => 'š',
      '\xc5\xbe' => 'ž',
      '\xc4\x8c' => 'Č',
      '\xc4\x86' => 'Ć',
      '\xc4\x90' => 'Đ',
      '\xc5\xa0' => 'Š',
      '\xc5\xbd' => 'Ž',
      '\xe2\x80\x94' => '-',
    ];
    if ($isHtml)
      $response = n2br($response);
    $response = strtr($response, $chars);
    return $response;
  }

  public static function dropDown($buttons)
  {
    if (count($buttons)) {
      echo '<div class="card-dropdown btn-group">';
      echo '<button type="button" 
            class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow waves-effect waves-light" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
            <i class="ti ti-dots-vertical"></i>
            </button>';
      echo '<ul class="dropdown-menu dropdown-menu-end" style="">';
      foreach ($buttons as $button) {
        $formatedButton = str_replace('btn', 'dropdown-link', str_replace(['btn-primary', 'rounded-pill', 'text-white'], "", $button));
        echo "<li>$formatedButton</li>";
      }
      echo '</ul>';
      echo '</div>';
    }
  }
}
