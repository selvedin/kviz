<?php

namespace app\helpers;

use Yii;
use app\models\Settings;

require_once(Yii::$app->basePath . '/tcpdf/tcpdf.php');
$defaultFont = isset(Yii::$app->params['defaultFont']) ? Yii::$app->params['defaultFont'] : 'helvetica';
define('DEFAULT_FONT', $defaultFont);
class MYPDF extends \TCPDF
{


  public function Header()
  {
    $company = Settings::getCompanyInfo();
    $image_file = 'img/logo.png';
    $this->Image($image_file, 15, 5, '', 25, 'PNG', '', 'T', false, 300, 'L', false, false, 0, false, false, false);
    $this->SetFont(DEFAULT_FONT, 'B', 10);

    // Title
    $this->SetY(15);

    $this->SetFont(DEFAULT_FONT, 'B', 8);
    $this->Cell(0, 0, $company['name'], 0, false, 'R', 0, '', 0, true, 'M', 'M');

    $this->SetFont(DEFAULT_FONT, '', 8);
    $this->SetY(20);
    $this->Cell(0, 0, __('Address') . ": " . $company['address'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(24);
    $this->Cell(0, 0, __('Mobile') . ": " . $company['mobile'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(28);
    $this->Cell(0, 0, __('Phone') . ": " . $company['phone'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetY(32);
    $this->Cell(0, 0, __('Email') . ": " . $company['email'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    $this->SetLineStyle(array('width' => 0.1, 'color' => array(128, 128, 128)));
    $this->Line(15, 35, 195, 35);
  }


  public function Footer()
  {
    $this->SetY(-10);
    $this->SetX(0);
    $this->SetFont(DEFAULT_FONT, 'B', 10);
    $this->SetFillColor(255, 255, 255);
    $this->SetTextColor(0, 0, 0);
    $this->Cell(8, 11, "", 0, false, 'L', 1, '', 0, false, 'T', 'M');
    $this->Cell(10, 11, $this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
  }

  public function fillGray()
  {
    $this->setFillColor(128, 128, 128);
  }
  public function textWhite()
  {
    $this->setTextColor(255, 255, 255);
  }
  public function textBlack()
  {
    $this->setTextColor(0, 0, 0);
  }
  public function header1()
  {
    $this->setFont(DEFAULT_FONT, 'B', 18);
  }
  public function header2()
  {
    $this->setFont(DEFAULT_FONT, 'B', 16);
  }

  public function header3()
  {
    $this->setFont(DEFAULT_FONT, 'B', 14);
  }

  public function customFontSize($size)
  {
    $this->setFont(DEFAULT_FONT, '', $size);
  }

  public function regularText()
  {
    $this->setFont(DEFAULT_FONT, '', 12);
  }

  public function boldText()
  {
    $this->setFont(DEFAULT_FONT, 'B', 12);
  }

  public function borderColor($color = [255, 255, 255])
  {
    return $this->SetLineStyle(array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => $color));
  }

  public function writeBarcode($barcode)
  {
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

    // PRINT VARIOUS 1D BARCODES

    // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
    $this->Cell(0, 0, 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
    $this->write1DBarcode($barcode, 'C39', '', '', '', 18, 0.4, $style, 'N');
  }
}
