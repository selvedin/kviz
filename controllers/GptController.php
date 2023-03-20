<?php

namespace app\controllers;

use app\models\ApiCalls;
use app\models\Categories;
use app\models\Grade;
use CURLFile;
use yii\web\Controller;
use yii\web\HttpException;
use Exception;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Response;

const API_KEY = "https://api.openai.com/v1/chat/completions";
class GptController extends Controller
{

  /**
   * @inheritDoc
   */
  public function behaviors()
  {
    return array_merge(
      parent::behaviors(),
      [
        'verbs' => [
          'class' => VerbFilter::class,
          'actions' => [
            'delete-file' => ['POST'],
          ],
        ],
      ]
    );
  }

  public function actionDeleteFile($file)
  {
    $id = Yii::$app->user->id;
    $file = Yii::$app->basePath . "/runtime/questions/$id/$file";
    if (file_exists($file)) unlink($file);
    return $this->redirect($this->request->referrer);
  }

  public function actionProcessAudio()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $response = "";
    if ($this->request->isPost) {
      $id = Yii::$app->user->id;
      $upload_dir = Yii::$app->basePath . "/runtime/audios/$id/";
      if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
      $filename = $this->request->post('filename');
      $audio_file = $upload_dir . basename($_FILES[$filename]["name"]);

      // return mime_content_type($_FILES[$filename]["tmp_name"]);
      if (mime_content_type($_FILES[$filename]["tmp_name"]) !== 'video/webm') {
        return ['error' => "Sorry, only audio files are allowed."];
      }

      // Move the uploaded file to the upload directory
      if (move_uploaded_file($_FILES[$filename]["tmp_name"], $audio_file)) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.openai.com/v1/audio/transcriptions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
            'file' => new CURLFile($audio_file),
            'model' => 'whisper-1',
            'response_format' => 'json',
            'temperature' => '0',
            'language' => 'bs'
          ),
          CURLOPT_HTTPHEADER => array(
            'Content-Type: multipart/form-data',
            'Accept: application/json',
            "Authorization: Bearer " . Yii::$app->params['CHATGPT_API_KEY']
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
      } else {
        return ['error' => "Sorry, there was an error uploading your file."];
      }
    }
    return $response;
  }

  public function actionAudio()
  {
    return $this->render('audio', ['response' => '']);
  }

  public function actionQuestion()
  {

    $response = "";
    $subject = $grade = $title =  null;
    $num_of_questions = 1;

    if ($this->request->isPost) {
      $data = $this->request->post();
      $subject = Categories::getName($data['subject']);
      $grade = Grade::getName($data['grade']) . ".";
      $num_of_questions = $data['num_of_questions'];
      $filename = time();

      $title = $data['title'];
      if (empty($subject) || empty($grade) || empty($num_of_questions)) {
        Yii::$app->session->setFlash('error', __("You must fill required fields."));
        return $this->render('question', [
          'response' => $response,
          'subject' => $data['subject'] ?? null,
          'grade' => $data['grade'] ?? null,
          'title' => $data['title'] ?? null,
          'num_of_questions' => $data['num_of_questions'] ?? null,
          'total_calls' => ApiCalls::getTotalCalls()
        ]);
      }

      $rest_questions = Yii::$app->params['max_api_calls'] - (int) ApiCalls::getTotalCalls();
      if ($rest_questions < 0)
        throw new HttpException(500, __("You have exceeded the limit of API calls for today."));

      $num_of_questions = $rest_questions > $num_of_questions ? $num_of_questions : $rest_questions;

      $options = $this->getOptions($num_of_questions, $subject, $grade, $title);

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

        $this->saveResponse(
          $subject,
          $grade,
          $title,
          $num_of_questions,
          $response["choices"][0]['message']['content'],
          $filename
        );

        $response = explode("\n\n", $response["choices"][0]['message']['content']);
      }
      curl_close($ch);

      ApiCalls::add(API_KEY, $this->request->userIP, $num_of_questions, $filename);
    }

    return $this->render('question', [
      'response' => $response,
      'subject' => $data['subject'] ?? null,
      'grade' => $data['grade'] ?? null,
      'title' => $data['title'] ?? null,
      'num_of_questions' => $data['num_of_questions'] ?? null,
      'total_calls' => ApiCalls::getTotalCalls(),
      'files' => $this->readFiles()
    ]);
  }

  private function saveResponse($subject, $grade, $title, $num_of_questions, $response, $filename)
  {
    $id = Yii::$app->user->id;
    $path = Yii::$app->basePath . "/runtime/questions/$id/";
    if (!file_exists($path)) mkdir($path, 0777, true);
    file_put_contents(
      $path .  $filename,
      __('Subject') . ': ' . $subject
        . ', ' . __('Grade') . ': ' . $grade
        . ', ' . __('Unit title') . ': ' . $title
        . ', ' . __('Num of questions') . ': ' . $num_of_questions . "\n\n" .
        $response,
      FILE_APPEND
    );
  }

  private function getOptions($num_of_questions, $subject, $grade, $title)
  {

    return [
      CURLOPT_URL => API_KEY,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => json_encode(array(
        "model" => "gpt-3.5-turbo",
        "messages" => [[
          "role" => "user",
          "content" => $this->getContent($num_of_questions, $subject, $grade, $title)
        ]],
        "temperature" => 0.7
      )),
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Authorization: Bearer " . Yii::$app->params['CHATGPT_API_KEY'],
      ),
    ];
  }

  private function getContent($num_of_questions, $subject, $grade, $title)
  {
    $content = "Generiši $num_of_questions pitanja iz predmeta '$subject' za $grade razred Osnovne škole.";
    if ($title) $content .= " Pitanja trebaju biti vezana za nastavnu jedinicu sa naslovom '$title'.";
    $content .= " Pitanja trebaju imati više ponuđenih opcija od kojih je jedna tačna.";
    $content .= "Ne dodaji redne brojeve prije pitanja. Poslije tačne opcije stavi [x].";
    $content .= " Delimiter poslije svakog pitanja treba da bude dvostruki '\\n'.";
    return $content;
  }

  private function readFiles()
  {
    $id = Yii::$app->user->id;
    $folder = Yii::$app->basePath . "/runtime/questions/$id/";

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

  /**
   * @inheritdoc
   */
  public function beforeAction($action)
  {
    if (in_array($action->id, [
      'process-audio',
    ])) {
      $this->enableCsrfValidation = false;
    }

    return parent::beforeAction($action);
  }
}
