<?php

namespace app\helpers;


require_once(\Yii::$app->basePath . '/tcpdf/tcpdf.php');

class MYPDF extends \TCPDF
{
  public $posY = 12;
  public $startX = 15;
  public $endX = 195;

  public function Header()
  {
    $this->SetFont('droidarabickufi', 'B', 10);
    $this->SetY($this->posY);
    $this->Cell(0, 0, "المنصة العقدية" . $this->posY, 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetLineStyle(array('width' => 0.1, 'color' => array(128, 128, 128)));
    $this->Line($this->startX, $this->posY + 3, $this->endX, $this->posY + 3);
  }


  public function Footer()
  {
    $this->SetY(-10);
    $this->SetX(0);
    $this->SetFont('droidarabickufi', 'B', 10);
    $this->SetFillColor(255, 255, 255);
    $this->SetTextColor(0, 0, 0);
    $this->Cell(8, 11, "", 0, false, 'L', 1, '', 0, false, 'T', 'M');
    $this->Cell(10, 11, $this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
  }

  public function title($title)
  {
    $this->setFont('droidarabickufi', 'B', 16);
    $this->Cell('', '', $title, 'B', 1);
    $this->setFont('droidarabickufi', 'B', 12);
    $this->Ln(5);
  }

  public function subTitle($title)
  {
    $this->setFont('droidarabickufi', 'B', 12);
    $this->Ln(5);
    $this->Cell('', '', $title, 0, 1);
    $this->Ln(3);
    $this->setFont('droidarabickufi', '', 10);
  }

  public function setCustomFont($fontSize)
  {
    $this->setFont('droidarabickufi', '', $fontSize);
  }

  public function regularFont()
  {
    $this->setFont('droidarabickufi', '', 10);
  }

  public function _cell($title)
  {
    $this->Cell(195, '', $title, 0, 1);
  }


  public function mCell($title)
  {
    $this->Ln(4);
    $this->MultiCell('', '', $title, 0, 'J', 0, 1, '', '', true, null, true);
  }

  public function _line($title1, $cont1, $title2, $cont2)
  {
    $this->Ln(4);
    $this->setFont('droidarabickufi', 'B', 9);
    $this->Cell(35, '', $title1);
    $this->setFont('droidarabickufi', '', 9);
    $this->Cell(55, '', $cont1, 'B');
    $this->Cell(5, '', '');
    $this->setFont('droidarabickufi', 'B', 9);
    $this->Cell(35, '', $title2);
    $this->setFont('droidarabickufi', '', 9);
    $this->Cell(55, '', $cont2, empty($cont2) ? '' : 'B', 1);
  }

  public function _singleLine($title, $content)
  {
    $this->Ln(4);
    $this->setFont('droidarabickufi', 'B', 9);
    $this->Cell(50, '', $title . ":");
    $this->setFont('droidarabickufi', '', 9);
    $this->Cell(2, '', '', 'B', 0);
    $this->Cell('', '', $content, 'B', 1);
  }

  public function separator()
  {
    $this->Ln(2);
    $this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
    $this->Ln(2);
  }
}
