<?php

use app\helpers\PdfHelper;
use yii\helpers\Url;

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

$this->title = __('Quiz') . ' - ' . $model->title;


$pdf = PdfHelper::createPdf();
$pdf->SetTitle(__('Quiz'));
$pdf->SetSubject(__('Quiz'));
$pdf->AddPage();
$pdf->header3();
$x = 6;
$y = 40;

ob_get_clean();
$pdf->Output("$this->title.pdf", 'I');
