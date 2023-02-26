<?php

use app\helpers\Buttons;
use app\helpers\Icons;
use app\models\Quiz;
use yii\bootstrap5\Html;


function getTableHeader()
{
  return Html::tag(
    'tr',
    Html::tag('th', '#', ['style' => 'width:5%;']) .
      Html::tag('th', __('Num of questions'), ['style' => 'width:10%;']) .
      Html::tag('th', __('Competitors')) .
      Html::tag('th',  __('Results')) .
      Html::tag('th',  '', ['style' => 'width:20%;'])
  );
}

function printRow($num, $quiz)
{
  $hasResults = $quiz->results && count($quiz->results);
  $hasCompetitors = $quiz->competitors && count($quiz->competitors);
  $results = getResults($quiz);
  $resultText = $results;
  if (!$hasResults && !$hasCompetitors)
    $resultText = __('Add competitors to be able to activate the quiz');
  echo Html::tag(
    'tr',
    Html::tag('td', $num + 1 . '.') .
      Html::tag('td', $quiz->quizObject->num_of_questions)
      . Html::tag('td', getCompetitors($quiz))
      . Html::tag('td', $resultText, ['class' => $results != '' ? 'd-flex' : ''])
      . Html::tag('td', getButtons($quiz), ['class' => 'text-end'])
  );
}

function getCompetitors($quiz)
{
  $competitors =  "";
  $isActive = $quiz->active;
  foreach ($quiz->competitors as $c) {
    $competitors .= Html::a(
      Html::tag('span', $c->user->name, ['class' => 'ms-auto']) . ($isActive ? null : __badge()),
      'javascript:void(0)',
      [
        'class' => 'rounded-pill btn btn-outline-secondary me-2',
        '@click' => ($isActive ? "return;" : "removeCompetitor($c->id)")
      ]
    );
  }
  if ($quiz->active == 0) {
    $competitors .= Html::a(
      Icons::faIcon('plus'),
      '#',
      [
        'class' => 'btn btn-icon btn-success rounded-pill ms-2',
        '@click' => "toggleModal($quiz->id)",
        'title' => __('Add competitor')
      ]
    );
  }
  return $competitors;
}

function getResults($quiz)
{
  $results = "";
  foreach ($quiz->userResults as $r) {
    $totals = unserialize($r->totals);
    $totals = $totals['totalCorrect'];
    $results .= Html::tag(
      'div',
      Html::tag(
        'h6',
        Html::a($r->user->name, 'javascript:void(0)', ['@click' => "getUserSummary($r->id)"]),
        ['class' => 'mb-0']
      ) .
        Html::tag(
          'div',
          $totals . "/" . count(unserialize($quiz->quiz)),
          ['class' => 'badge rounded bg-label-success']
        ),
      ['class' => 'd-flex gap-2 align-items-center mx-2']
    );
  }
  return $results;
}

function getButtons($quiz)
{
  $buttons = "";
  switch ($quiz->active) {
    case Quiz::STATUS_PENDING:
      //activate
      $buttons .= count($quiz->competitors) ? Html::a( // can activate if competitors are signed
        __('Activate'),
        ['quiz/activate', 'id' => $quiz->id, 'active' => Quiz::STATUS_ACTIVE],
        ['class' => 'btn-outline-primary btn btn-sm rounded-pill mx-1']
      ) : Html::a( // can delete if there are no competitors assigned
        __('Delete'),
        ['quiz-temp/delete', 'id' => $quiz->id],
        [
          'class' => 'btn-outline-danger btn btn-sm rounded-pill mx-1',
          'data-method' => 'POST',
          'data-confirm' => __('Are You sure')
        ]
      );
      break;
    case Quiz::STATUS_ACTIVE:
      if (count($quiz->userResults)) {
        //archive
        $buttons .= Html::a(
          __('Archive'),
          ['quiz/activate', 'id' => $quiz->id, 'active' => Quiz::STATUS_ARCHIVED],
          ['class' => 'btn-outline-secondary btn btn-sm rounded-pill mx-1']
        );
      } else {
        //deactivate
        $buttons .= Html::a(
          __('Deactive'),
          ['quiz/activate', 'id' => $quiz->id, 'active' => 0],
          ['class' => 'btn-outline-primary btn btn-sm rounded-pill mx-1']
        );
        if ($quiz->quizObject->quiz_type == Quiz::TYPE_REMOTE)
          //start
          $buttons .= Html::a(
            __('Start'),
            ['quiz/activate', 'id' => $quiz->id, 'active' => 2],
            ['class' => 'btn-outline-success btn btn-sm rounded-pill mx-1']
          );
        //finish
        else
          $buttons .= Html::a(
            __('Finish'),
            ['quiz/activate', 'id' => $quiz->id, 'active' => Quiz::STATUS_FINISHED],
            ['class' => 'btn-outline-dark btn btn-sm rounded-pill mx-1 animate__animated animate__pulse animate__infinite']
          );
        break;
      }
      break;
    case Quiz::STATUS_STARTED:
      $buttons .= Html::a(
        __('Run'),
        ['quiz/activate', 'id' => $quiz->id, 'active' => Quiz::STATUS_RUNNING],
        ['class' => 'btn-outline-success btn btn-sm rounded-pill mx-1']
      );
      break;
    case Quiz::STATUS_RUNNING:
      $buttons .= Html::a(
        __('Finish'),
        ['quiz/activate', 'id' => $quiz->id, 'active' => Quiz::STATUS_FINISHED],
        ['class' => 'btn-outline-dark btn btn-sm rounded-pill mx-1 animate__animated animate__pulse animate__infinite']
      );
      break;
    default:
      break;
  } {
  }

  $buttons .= Buttons::Pdf($quiz->id, 'quiz-temp'); //pdf
  return $buttons;
}

function __badge($text = null)
{
  $text = $text ? $text : Html::tag('i', '', ['class' => 'fas fa-times']);
  return Html::tag(
    'span',
    $text,
    [
      'class' => 'badge badge-center rounded-pill bg-danger ms-4',
      'style' => 'margin-right:-15px;'
    ]
  );
}
