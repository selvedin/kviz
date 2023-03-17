<?php

namespace app\controllers;

use app\models\ApiCalls;
use app\models\Categories;
use app\models\Grade;
use yii\web\Controller;
use yii\web\HttpException;
use Yii;

class GptController extends Controller
{

  public function actionQuestion()
  {

    $response = "";
    $subject = $grade = $title = $num_of_questions = null;
    if ($this->request->isPost) {
      $data = $this->request->post();
      $subject = Categories::getName($data['subject']);
      $grade = Grade::getName($data['grade']) . ".";
      $num_of_questions = $data['num_of_questions'];
      $title = $data['title'];
      $num_of_questions = (int)$num_of_questions - ApiCalls::getTotalCalls();
      if ($num_of_questions < 0)
        throw new HttpException(500, __("You have exceeded the limit of API calls for today."));

      $api_url = "https://api.openai.com/v1/chat/completions";
      $api_key = Yii::$app->params['CHATGPT_API_KEY'];

      $input = "Generiši $num_of_questions pitanja iz predmeta '$subject' za $grade razred Osnovne škole.";
      if ($title) $input .= " Pitanja trebaju biti vezana za nastavnu jedinicu sa naslovom '$title'.";
      $instruction = " Pitanja trebaju imati više ponuđenih opcija od kojih je jedna tačna.";
      $instruction .= " Poslije tačne opcije stavi [x].";
      $instruction .= " Delimiter poslije svakog pitanja treba da bude dvostruki '\\n'.";

      $options = array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode(array(
          "model" => "gpt-3.5-turbo",
          "messages" => [["role" => "user", "content" => "$input $instruction"]],
          "temperature" => 0.7
        )),
        CURLOPT_HTTPHEADER => array(
          "Content-Type: application/json",
          "Authorization: Bearer $api_key",
        ),
      );
      $ch = curl_init();
      curl_setopt_array($ch, $options);
      $response = curl_exec($ch);
      if (curl_errno($ch)) {
        throw new HttpException(500, curl_errno($ch));
      } else {
        $response_data = json_decode($response, true);

        if (isset($response_data['error']))
          throw new HttpException(500, $response_data['error']['message']);
        $response = $response_data;
        $response = explode("\n\n", $response["choices"][0]['message']['content']);
      }
      curl_close($ch);

      ApiCalls::add($api_key, $this->request->userIP, $num_of_questions);
    }

    return $this->render('question', [
      'response' => $response,
      'subject' => $data['subject'] ?? null,
      'grade' => $data['grade'] ?? null,
      'title' => $data['title'] ?? null,
      'num_of_questions' => $data['num_of_questions'] ?? null,
      'total_calls' => ApiCalls::getTotalCalls()
    ]);
  }
}
