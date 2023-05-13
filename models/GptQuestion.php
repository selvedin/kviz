<?php

namespace app\models;

class GptQuestion
{
  public $subject;
  public $grade;
  public $title;
  public $num_of_questions;
  public $lesson;
  public $questionType;
  public $prompt;
  public $response;

  public function __construct(
    $subject = null,
    $grade = null,
    $title = '',
    $num_of_questions = 1,
    $lesson = '',
    $questionType = Question::TYPE_ESSAI,
    $prompt = '',
    $response = ''
  ) {
    $this->subject = $subject;
    $this->grade = $grade;
    $this->title = $title;
    $this->num_of_questions = $num_of_questions;
    $this->lesson = $lesson;
    $this->questionType = $questionType;
    $this->prompt = $prompt;
    $this->response = $response;
  }

  public function populateData($data)
  {
    $this->subject = $data['subject'] ?? null;
    $this->title = $data['title'] ?? null;
    $this->grade = $data['grade'] ?? null;
    $this->num_of_questions = $data['num_of_questions'] ?? null;
    $this->lesson = $data['lesson'] ?? null;
    $this->prompt = $data['prompt'] ?? null;
    $this->questionType = $data['questionType'] ?? null;
  }

  public function isValid()
  {
    return empty($this->subject) || empty($this->grade) || empty($this->num_of_questions);
  }

  public function calculateRestQuestions($rest)
  {
    $this->num_of_questions = $rest > $this->num_of_questions ? $this->num_of_questions : $rest;
  }

  public function getFirstRow()
  {
    $subject = $this->subject ? Categories::getName($this->subject) : '';
    $grade = $this->grade ? Grade::getName($this->grade) : '';
    return "SUBJECT: $subject, GRADE: $grade,"
      . " UNIT_TITLE: $this->title, NUM_OF_QUESTIONS: $this->num_of_questions, " .
      "LESSON_TITLE: $this->lesson, QUESTION_TYPE: $this->questionType";
  }

  public static function resolveQuestion($content)
  {
    $question = new GptQuestion();
    $question->parseData($content);
    return $question;
  }

  private function parseData($data)
  {
    $parts = explode(',', $data);
    foreach ($parts as $part) {
      $item = explode(':', $part);
      if (count($item) < 2) continue;
      $text = trim($item[0]);
      if ($text == 'SUBJECT') $this->subject = Categories::getId($item[1]);
      if ($text == 'GRADE') $this->grade = Grade::getId($item[1]);
      if ($text == 'UNIT_TITLE') $this->title = $item[1];
      if ($text == 'NUM_OF_QUESTIONS') $this->num_of_questions = (int)$item[1];
      if ($text == 'LESSON_TITLE') $this->lesson = $item[1];
      if ($text == 'QUESTION_TYPE') $this->questionType = (int)$item[1];
    }
  }
}
