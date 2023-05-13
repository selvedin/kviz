<?php

namespace app\helpers;

use Yii;

class StringHelper
{
  public static function prepareQuestion($question)
  {
    $digitPattern = '/^\d/'; //remove first char if it is number
    $secondPattern = '/^[\.\(\)]/'; //remove first char if it is dot or bracket
    $question = preg_replace($digitPattern, '', $question);
    $question = preg_replace($secondPattern, '', $question);
    $question = trim(str_replace('\n', '', $question));
    return $question;
  }

  public static function cleanQuestion($question)
  {
    $question = str_replace(['a) ', 'b) ', 'c) ', 'd) ', 'e) ', 'f) ', '[]', '[ ]'], '', $question);
    return $question;
  }
}
