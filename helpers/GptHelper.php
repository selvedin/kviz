<?php

namespace app\helpers;

use app\models\GptQuestion;
use app\models\Question;
use CURLFile;
use Yii;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\web\HttpException;

class GptHelper
{

  const DELIMITER_TEXT = " Delimiter poslije svakog pitanja treba da bude dvostruki '\\n'.";
  public static function getQuestionPrompt($model)
  {
    switch ($model->questionType) {
      case Question::TYPE_ESSAI:
        return self::getEsseiText($model);
      case Question::TYPE_SINGLE:
        return self::getSingleText($model);
      case Question::TYPE_MULTI:
        return self::getMultipleText($model);
      case Question::TYPE_TRUE:
        return self::getTrueFalseText($model);
      case Question::TYPE_PROMPT:
        return self::getPromptText($model);
    }
    return "";
  }

  public static function getEsseiText($model)
  {
    $content = "U ulozi nastavnika, generiši $model->num_of_questions pitanja iz predmeta '$model->subject' za $model->grade. razred Osnovne škole.";
    if ($model->title) $content .= " Pitanja trebaju biti vezana za nastavnu jedinicu sa naslovom '$model->title'.";
    $content .= self::DELIMITER_TEXT;
    return $content;
  }

  public static function getSingleText($model)
  {
    $content = "U ulozi nastavnika, generiši $model->num_of_questions pitanja iz predmeta '$model->subject' za $model->grade. razred Osnovne škole.";
    if ($model->title) $content .= " Pitanja trebaju biti vezana za nastavnu jedinicu sa naslovom '$model->title'.";
    $content .= " Pitanja trebaju imati više ponuđenih opcija od kojih je samo jedna tačna.";
    $content .= " Između pitanja i njegovih opcija ne smije biti praznih linija.";
    $content .= "Ne dodaji redne brojeve prije pitanja. Poslije tačne opcije dodaj [CORRECT].";
    $content .= self::DELIMITER_TEXT;
    return $content;
  }

  public static function getMultipleText($model)
  {
    $content = "U ulozi nastavnika, generiši $model->num_of_questions pitanja iz predmeta '$model->subject' za $model->grade. razred Osnovne škole.";
    if ($model->title) $content .= " Pitanja trebaju biti vezana za nastavnu jedinicu sa naslovom '$model->title'.";
    $content .= " Pitanja trebaju imati više ponuđenih opcija od kojih je jedna opcija tačna ili nekoliko opcija tačno ili sve opcije tačne.";
    $content .= "  Na kraju teksta tačne opcije dodaj [CORRECT].";
    $content .= " Između pitanja i njegovih opcija ne smije biti praznih linija.";
    $content .= "Ne dodaji redne brojeve prije pitanja.";
    $content .= self::DELIMITER_TEXT;
    return $content;
  }

  public static function getTrueFalseText($model)
  {
    $content = "U ulozi nastavnika, generiši $model->num_of_questions pitanja iz predmeta '$model->subject' za $model->grade. razred Osnovne škole.";
    if ($model->title) $content .= " Pitanja trebaju biti vezana za nastavnu jedinicu sa naslovom '$model->title'.";
    $content .= " Pitanja trebaju biti u formi TAČNO/NETAČNO.";
    $content .= "Tačan odgovor stavi u uglaste zagrade.";
    $content .= " Između pitanja i njegovih opcija ne smije biti praznih linija.";
    $content .= self::DELIMITER_TEXT;
    return $content;
  }

  public static function getPromptText($model)
  {
    $content = "U ulozi nastavnika, generiši $model->num_of_questions pitanja iz predmeta '$model->subject' za $model->grade. razred Osnovne škole.";
    $content .= " Pitanja trebaju biti generisana na osnovu sljedećeg teksta: '$model->prompt'";
    $content .= self::DELIMITER_TEXT;
    return $content;
  }

  public static function getCurlOptions(GptQuestion $model)
  {
    return [
      CURLOPT_URL => $_ENV['GPT_COMPLETITION_API'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-3.5-turbo",
        "messages" => [[
          "role" => "user",
          "content" => self::getQuestionPrompt($model)
        ]],
        "temperature" => 0.7
      ]),
      CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer " . $_ENV['CHATGPT_API_KEY'],
      ],
    ];
  }

  public static function getCurlOptions2($file)
  {
    return [
      CURLOPT_URL => $_ENV['GPT_TRANSCIPRION_URL'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array(
        'file' => new CURLFile($file),
        'model' => 'whisper-1',
        'response_format' => 'json',
        'temperature' => '0',
        'language' => 'bs'
      ),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: multipart/form-data',
        'Accept: application/json',
        "Authorization: Bearer " . $_ENV['CHATGPT_API_KEY']
      ),
    ];
  }

  public static function resolveQuestions($content)
  {

    $gptQuestion = GptQuestion::resolveQuestion($content[0]);
    switch ($gptQuestion->questionType) {
      case Question::TYPE_SINGLE:
      case Question::TYPE_MULTI:
        return self::resolveSingle($content, $gptQuestion);
        break;
      case Question::TYPE_TRUE:
        return self::resolveTrueFalse($content, $gptQuestion);
        break;
      case Question::TYPE_ESSAI:
      case Question::TYPE_PROMPT:
        return self::resolvePrompt($content, $gptQuestion);
        break;
    }
  }

  private static function resolvePrompt($content, $gptQuestion)
  {
    $questions = "";
    $start = 1;
    if ($gptQuestion->questionType == Question::TYPE_ESSAI) {
      $content = explode("\n", $content[1]);
      $start = 0;
    }
    for ($i = $start; $i < count($content); $i++) {
      if (empty($content[$i])) continue;
      $question = Html::tag('h6', $content[$i]);
      $questions .= $question;
      $questions .= self::getButtons($question, $gptQuestion);
    }
    return $questions;
  }

  private static function resolveTrueFalse($content, $gptQuestion)
  {
    $questions = "";
    $content = explode("\n", $content[1]);
    for ($i = 0; $i < count($content); $i++) {
      if (empty($content[$i])) continue;
      $answers = str_contains($content[$i], "[Tačno]") ? "[Tačno] / Netačno" : "Tačno / [Netačno]";
      $answer = str_contains($content[$i], "[Tačno]") ? Icons::Correct('') : Icons::Incorrect('');
      $question = str_replace(["[Tačno]", "[Netačno]"], "", $content[$i]);
      $questions .= Html::tag('h6', $question . " " . $answer);
      $questions .= self::getButtons($question, $gptQuestion, $answers);
    }
    return $questions;
  }

  private static function resolveSingle($content, $gptQuestion)
  {
    $questions = "";
    $num = 1;
    $check = Icons::Correct('');
    for ($i = 1; $i < count($content); $i++) {
      if (empty($content[$i])) continue;
      $question = explode("\n", $content[$i]);
      $tempQuestion = Html::tag('h6', $num++ . ". " . $question[0], ['class' => 'mt-4']);
      $tempAnswers = "";
      for ($j = 1; $j < count($question); $j++) {
        if (empty($question[$j])) continue;
        $tempAnswers .= Html::tag('div', str_replace("[CORRECT]", $check, $question[$j]));
      }
      $questions .= $tempQuestion;
      $questions .= $tempAnswers;
      $questions .= self::getButtons($tempQuestion, $gptQuestion, $tempAnswers);
    }
    return $questions;
  }

  private static function getButtons($question, $gptQuestion, $answers = [])
  {
    $existing = Question::find()->where(['content' => $question])->exists();
    $button = "";
    if (!$existing && $question) {

      $button .= "<div class='d-flex justify-content-end border-bottom'>";
      $button .= Html::a(
        __('Save a question'),
        Url::to([
          'question/add-generated',
          'question' => $question,
          'options' => is_array($answers) ? serialize($answers) : $answers,
          'details' => serialize($gptQuestion)
        ]),
        ['class' => 'btn btn-outline-primary btn-sm rounded-pill float-end']
      );
      $button .= "</div>";
    }
    return $button;
  }
}
