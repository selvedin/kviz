<?php

namespace app\helpers;


use app\models\Settings;
use TCPDF2DBarcode;
use Yii;

require_once(Yii::$app->basePath . '/tcpdf/tcpdf_barcodes_1d.php');
require_once(Yii::$app->basePath . '/tcpdf/tcpdf_barcodes_2d.php');

use TCPDFBarcode;
use yii\bootstrap4\Html;
use yii\helpers\Url;

class PdfHelper
{
  public static function createPdf()
  {
    $APP_NAME = Yii::$app->name;
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator($APP_NAME);
    $pdf->SetAuthor($APP_NAME);
    $pdf->SetKeywords('PDF');

    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

    // set header and footer fonts
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    // set some language-dependent strings (optional)
    if (file_exists(dirname(__FILE__) . '/lang/eng.php')) {
      require_once(dirname(__FILE__) . '/lang/eng.php');
      $pdf->setLanguageArray($l);
    }
    $pdf->setFont(DEFAULT_FONT);
    return $pdf;
  }

  public static function resolveTitle($content, $title)
  {
    if ($content  instanceof Settings) {
      switch ($content->type) {
        case 'Documents':
          return $content->n;
      }
    }
    return $title;
  }

  public static function filterContent($content)
  {
    if ($content  instanceof Settings) {
      switch ($content->type) {
        case 'Documents':
          return $content->a4;
        default:
          return null;
      }
    }
  }

  public static function barcode($code, $type = 'C39')
  {
    $width = 1.5;
    $height = 60;
    $barcodeobj = new TCPDFBarcode($code, $type);
    $qrCode = new TCPDF2DBarcode(__host() . Url::to(['package/get', 'code' => $code, 'user' => Yii::$app->user->id]), "QRCODE,Q");
    $file_png = "images/barcodes/$code.png";
    $file_qr = "images/barcodes/qr_$code.png";
    file_put_contents($file_png, $barcodeobj->getBarcodePngData($width, $height));
    file_put_contents($file_qr, $qrCode->getBarcodePngData(10, 10));
    // return $barcodeobj->getBarcodeHTML($width, $height, 'black');
    // return $barcodeobj->getBarcodeSVG($width, $height, 'black');
    // return $barcodeobj->getBarcodePNG($width, $height, array(0, 128, 0));
  }
}
