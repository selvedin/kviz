<?php

namespace app\helpers;

use app\widgets\CardView;
use yii\bootstrap5\Html;

class Utils
{

  public static function getLink($title, $id, $onClick = "return;")
  {
    return Html::a(
      $title,
      "#collapseFor$id",
      [
        'data-bs-toggle' => 'collapse', 'role' => 'button',
        'aria-expanded' => true, 'aria-control' => "collapseFor$id",
        '@click' => $onClick
      ]
    );
  }

  public static function getCollapse($content, $id)
  {
    return Html::tag(
      'div',
      // $content,
      Icons::spinner(),
      ['id' => "collapseFor$id", 'class' => 'collapse']
    );
  }

  public static function getButtons($editFunc, $removeFunc)
  {
    return [
      Buttons::customButton(Icons::faIcon('edit'), "javascript:void(0)", [
        'class' => 'btn btn-sm rounded-pill btn-icon btn-outline-primary mx-2',
        "@click" => $editFunc
      ]),
      Buttons::customButton(Icons::faIcon('trash'), "javascript:void(0)", [
        'class' => 'btn btn-sm rounded-pill btn-icon btn-outline-danger',
        "@click" => $removeFunc
      ])
    ];
  }

  public static function getAddButton($addFunc)
  {
    return [
      Buttons::customButton(Icons::faIcon('plus'), "javascript:void(0)", [
        'class' => 'btn btn-sm rounded-pill btn-icon btn-outline-primary mx-2',
        'title' => __('Add'),
        "@click" => $addFunc
      ])
    ];
  }

  public static function getDeleteButton($removeFunc)
  {
    return [
      Buttons::customButton(Icons::faIcon('trash'), "javascript:void(0)", [
        'class' => 'btn btn-sm rounded-pill btn-icon btn-outline-danger',
        "@click" => $removeFunc
      ])
    ];
  }

  public static function getQuestions($chapter)
  {
    return Html::tag('div', CardView::Widget([
      'title' => "مسائل الباب",
      'buttons' => [
        Buttons::customButton(Icons::faIcon('plus'), "javascript:void(0)", [
          'class' => 'btn btn-sm rounded-pill btn-icon btn-outline-primary',
          "@click" => "addQuestion(null, $chapter->id)"
        ]),
      ],
      'content' => self::generateQuestions($chapter->questions),
    ]), ['class' => 'col-md-12']);
  }

  public static function generateQuestions($chapterQuestions)
  {
    $questions = "";
    foreach ($chapterQuestions as $question) {
      $title = Html::a(
        $question->question->title,
        "#collapseForQuestion$question->id",
        [
          'data-bs-toggle' => 'collapse', 'role' => 'button',
          'aria-expanded' => true, 'aria-control' => "collapseForQuestion$question->id",
          "@click" => "loadQuestion('Question', $question->id)"
        ]
      );
      $questionContent = Html::tag(
        'div',
        Icons::spinner(), //$question->content,
        ['id' => "collapseForQuestion$question->id", 'class' => 'collapse border p-2']
      );
      $questions .= "<li><div class='d-flex justify-content-between'>$title <div>"
        . self::editQuestionButton("editQuestion($question->id, $question->book_content, $question->id_question, '$question->content')")
        . self::deleteQuestionButton("deleteQuestion($question->id)")
        . "</div></div>$questionContent</li>";
    }
    return "<ol>$questions</ol>";
  }

  public static function deleteQuestionButton($deleteFunc)
  {
    return Buttons::customButton(Icons::faIcon('trash'), "javascript:void(0)", [
      'class' => 'btn-sm rounded-pill btn-icon text-danger',
      "@click" => $deleteFunc
    ]);
  }

  public static function editQuestionButton($editFunc)
  {
    return Buttons::customButton(Icons::faIcon('edit'), "javascript:void(0)", [
      'class' => 'btn-sm rounded-pill btn-icon text-primary',
      "@click" => $editFunc
    ]);
  }

  public static function getComments($contents)
  {
    $content = "<ul>";
    foreach ($contents as $c) {
      $itemContent = Html::tag(
        'div',
        Icons::spinner(), //$c->content,
        ['id' => "collapseForBookContent$c->id", 'class' => 'collapse border p-2']
      );

      $title = self::getLink($c->bookChapter->title, "BookContent$c->id", "loadContent('BookContent', $c->id)");
      $content .= "<li><div class='d-flex justify-content-between'>$title <div>"
        . self::editQuestionButton("editContent($c->id, $c->book_comment, $c->book_chapter,$c->book_comment_chapter, '$c->content')")
        . self::deleteQuestionButton("deleteContent($c->id)")
        . "</div></div>$itemContent</li>";
    }
    $content .= "</ul>";
    return $content;
  }
}
