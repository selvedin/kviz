<?php

use app\helpers\MYPDF;
use app\helpers\PdfHelper;
use yii\helpers\Url;

$title = $model->quizObject->title;

$style = array(
  'position' => '',
  'align' => 'C',
  'stretch' => false,
  'fitwidth' => true,
  'cellfitalign' => '',
  'border' => true,
  'hpadding' => 'auto',
  'vpadding' => 'auto',
  'fgcolor' => array(0, 0, 0),
  'bgcolor' => false, //array(255,255,255),
  'text' => true,
  'font' => 'helvetica',
  'fontsize' => 8,
  'stretchtext' => 4
);

$styleQr = array(
  'border' => true,
  'vpadding' => 'auto',
  'hpadding' => 'auto',
  'fgcolor' => array(0, 0, 0),
  'bgcolor' => false, //array(255,255,255)
  'module_width' => 1, // width of a single module in points
  'module_height' => 1 // height of a single module in points
);

$this->title = __('Quiz') . ' - ' . $title;


$pdf = PdfHelper::createPdf();
$pdf->SetTitle(__('Quiz'));
$pdf->SetSubject(__('Quiz'));
$pdf->AddPage();
$pdf->header3();
$x = 6;
$y = 40;

$pdf->Cell('', '', $title, '', 1);
$pdf->Ln(3);
$pdf->regularText();

$pdf->Cell(35, '', __('Student name') . ":", '', 0);
$pdf->Cell(70, '', '', 'B', 0);
$pdf->Cell(10, '', '', '', 0);
$pdf->Cell(20, '', __('Date') . ":", '', 0);
$pdf->Cell(50, '', '', 'B', 1);

$pdf->Ln(4);
$num = 1;
$questions = unserialize($model->quiz);
foreach ($questions as $q) {
  $pdf->Ln(3);
  switch ($q['question_type']) {
    case 1:
      writeTrueFalse($pdf, $num++, $q['content']);
      break;
    case 2:
      writeOptions($pdf, $num++, $q['content'], $q['options']);
      break;
    case 3:
      writeOptions($pdf, $num++, $q['content'], $q['options'], true);
      break;
    case 4:
      writeJoins($pdf, $num++, $q['content'], $q['pairs']);
      break;
    case 5:
      writeInputs($pdf, $num++, $q['content'], $q['options']);
      break;
    default:
      break;
  }
}

ob_get_clean();
$pdf->Output("$this->title.pdf", 'I');

function writeTrueFalse(MYPDF $pdf, int $num, array | string $content)
{
  $box = '<span style="font-size:20px;">&#x25A2;</span>' . ' ';
  $pdf->boldText();
  $pdf->MultiCell('', '', "$num. " . $content, '', '', false, 1);
  $pdf->regularText();
  $pdf->Cell(10, '', '', '', 0);
  $pdf->Cell('', '', Yii::$app->params['true_false_text'], '', 1);
  $pdf->Ln(2);
  $pdf->Cell(10, '', '', '', 0);
  $pdf->writeHTMLCell(30, '', '', '', $box . __('True'), '', 0);
  $pdf->writeHTMLCell(30, '', '', '', $box . __('False'), '', 1);
  $pdf->Ln(3);
}

function writeOptions($pdf, $num, $content, $options, $isMulti = false)
{
  $p = Yii::$app->params;
  $box = '<span style="font-size:20px;">&#x25A2;</span>' . ' ';
  $pdf->boldText();
  $pdf->MultiCell('', '', "$num. " . $content, '', '', false, 1);
  $pdf->regularText();
  $pdf->Cell(10, '', '', '', 0);
  $pdf->Cell('', '', $isMulti ? $p['multi_text'] : $p['single_text'], '', 1);
  $pdf->Ln(2);
  $opt = 1;
  foreach ($options as $o) {
    $newLine = 0;
    if ($opt == 1) $pdf->Cell(10, '', '', '', 0);
    if ($opt++ == 3) {
      $newLine = 1;
      $opt = 1;
    }
    $pdf->writeHTMLCell(60, '', '', '', $box . $o['content'], '', $newLine);
  }
  $pdf->Ln(7);
}

function writeJoins($pdf, $num, $content, $pairs)
{
  $pdf->boldText();
  $pdf->MultiCell('', '', "$num. " . $content, '', '', false, 1);
  $pdf->regularText();
  $pdf->Cell(10, '', '', '', 0);
  $pdf->Cell('', '', Yii::$app->params['join_text'], '', 1);
  $pdf->Ln(2);

  foreach ($pairs['left'] as $k => $p) {
    $pdf->Cell(15, '', '', '', 0);
    $pdf->writeHTMLCell(60, '', '', '', $p['one'], '', 0);
    $pdf->writeHTMLCell(60, '', '', '', $pairs['right'][$k]['two'], '', 1);
  }
  $pdf->Ln(3);
}

function writeInputs(MYPDF $pdf, $num, $content, $options)
{
  $pdf->boldText();
  $pdf->MultiCell('', '', "$num. " . Yii::$app->params['input_text'], '', '', false, 1);
  $pdf->regularText();
  $pdf->Cell(10, '', '', '', 0);
  $pdf->Ln(3);

  foreach ($options as $o) {
    $pdf->Cell(15, '', '', '', 0);
    $pdf->Write('', $content . " = ", '', 0);
    $pdf->Cell(30, '', '', 'B', 1);
  }
  $pdf->Ln(3);
}
