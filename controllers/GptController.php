<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\helpers\Helper;
use yii\web\Controller;
use app\models\ApiCalls;
use app\helpers\GptHelper;
use yii\web\HttpException;
use app\helpers\FileHelper;
use yii\filters\VerbFilter;
use app\helpers\ImageHelper;
use app\models\GptQuestion;

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

  public function actionQuestion()
  {
    $gptQuestion = new GptQuestion();
    if ($this->request->isPost) {
      $filename = time();
      $gptQuestion->populateData($this->request->post());
      if ($gptQuestion->isValid()) {
        Yii::$app->session->setFlash('error', __("You must fill required fields."));
        return $this->render('question', $this->getResponse($gptQuestion));
      }

      $rest_questions = ApiCalls::getRestCalls();
      if ($rest_questions < 0)
        throw new HttpException(500, __("You have exceeded the limit of API calls for today."));

      $gptQuestion->calculateRestQuestions($rest_questions);

      $options = GptHelper::getCurlOptions($gptQuestion);

      $ch = curl_init();
      curl_setopt_array($ch, $options);
      $response = curl_exec($ch);
      if (curl_errno($ch)) {
        throw new HttpException(500, curl_errno($ch));
      } else {
        $response_data = json_decode($response, true);

        if (isset($response_data['error']))
          throw new HttpException(500, "GPT Error: " . $response_data['error']['message']);
        if (isset($response_data['choices'][0]['message']['content']))
          $gptQuestion->response = $response_data["choices"][0]['message']['content'];

        FileHelper::saveResponseToFile($gptQuestion, $filename);
        $gptQuestion->response = explode("\n\n", $gptQuestion->response);
      }
      curl_close($ch);

      ApiCalls::add($_ENV['GPT_COMPLETITION_API'], $this->request->userIP, $gptQuestion->num_of_questions, $filename);
    }

    return $this->render('question', $this->getResponse($gptQuestion));
  }

  private function getResponse($gptQuestion)
  {
    return [
      'model' => $gptQuestion,
      'total_calls' => ApiCalls::getTotalCalls(),
      'files' => FileHelper::readFiles()
    ];
  }

  public function actionOcr()
  {
    $gptQuestion = new GptQuestion();
    if ($this->request->isPost) {
      $gptQuestion->populateData($this->request->post());
      $fileName = ImageHelper::saveImageToServer('ocr');
      if ($fileName) {
        $path = ImageHelper::ImageFolder() . $fileName;
        $gptQuestion->response = exec("python ../tools/ocr.py  $path");
        if ($gptQuestion->response) {
          unlink($path);
          $gptQuestion->response = Helper::formatResponse($gptQuestion->response, false);
          $this->saveOcrToFile($gptQuestion, $fileName);
        }
      }
    }
    return $this->render('ocr', [
      'model' => $gptQuestion
    ]);
  }

  public function actionOcrAjax()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $response = "";
    if ($this->request->isPost) {
      $fileName = ImageHelper::saveImageToServer('ocr');
      if ($fileName) {
        $path = ImageHelper::ImageFolder() . $fileName;
        $response = exec("python ../tools/ocr.py  $path");
        if ($response) {
          unlink($path);
          $response = Helper::formatResponse($response, false);
        }
      }
    }
    return $response;
  }

  public function actionOcrList()
  {
    return $this->render('ocr-list', [
      'files' => $this->readFiles('ocrs')
    ]);
  }

  public function actionGetFileContent($filename)
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $content = explode("\n", FileHelper::readFile($filename, 'ocrs'));
    return br2n($content[2]);
  }

  public function actionAudio()
  {
    return $this->render('audio', ['response' => '']);
  }

  public function actionProcessAudio()
  {
    Yii::$app->response->format = Response::FORMAT_JSON;
    $response = "";
    if ($this->request->isPost) {
      $filename = $this->request->post('filename');
      $upload_dir = $this->getFolder('audios');
      $audio_file = $upload_dir . basename($_FILES[$filename]["name"]);

      if (mime_content_type($_FILES[$filename]["tmp_name"]) !== 'video/webm')
        return ['error' => "Sorry, only audio files are allowed."];

      if (move_uploaded_file($_FILES[$filename]["tmp_name"], $audio_file)) {

        $curl = curl_init();
        curl_setopt_array($curl, GptHelper::getCurlOptions2($audio_file));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
      } else {
        return ['error' => "Sorry, there was an error uploading your file."];
      }
    }
    return $response;
  }

  public function actionDeleteFile($file, $subfolder = 'questions')
  {
    $file = FileHelper::getFolder() . "/$file";
    if (file_exists($file)) unlink($file);
    return $this->redirect($this->request->referrer);
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
