<?php

namespace app\helpers;

use app\models\Settings;
use Yii;

define("LOGO", Yii::$app->basePath . '/web/images/logo.png');
define("APP_LANG", Settings::getLang());
define("DEFAULT_SUBJECT", Yii::$app->name);
define("COMPANY_EMAIL", isset(Yii::$app->params['companyEmail']) ? Yii::$app->params['companyEmail'] : null);

class EmailHelper
{
  public static function sendEmail(
    $content,
    $to,
    $subject = DEFAULT_SUBJECT,
    $template = "",
    $view = "default",
    $files = [],
    $user = COMPANY_EMAIL,
    $lang = APP_LANG
  ) {

    if (
      $to && filter_var($to, FILTER_VALIDATE_EMAIL) &&
      filter_var(COMPANY_EMAIL, FILTER_VALIDATE_EMAIL)
    ) {
      $message = Yii::$app->mailer->compose(
        "layouts/$view",
        ['content' => $content, 'lang' => $lang, 'template' => $template, 'user' => $user, 'logo' => LOGO]
      )->setFrom(COMPANY_EMAIL)->setTo($to)->setSubject($subject);

      if (count($files))  foreach ($files as $file)  $message->attach($file);
      if ($message->send()) {
        Yii::$app->session->setFlash('success', __("Email Sent to") . " " . $user . ".");
        return true;
      }
    }

    Yii::$app->session->setFlash('danger', __("Email Not Sent to") . " "
      . $user . ". " . __("User email or company email is not valid."));
    return false;
  }

  public static function getHeader($message, $logo)
  {
    $company = Settings::getCompanyInfo();
    return "<table class='table table-striped'><tbody>
              <tr><td class='text-left'><img src='" . $message->embed($logo) . " alt='$logo' width='150px'></td>
                <td class='text-right'>
                  <div><strong>" . $company['name'] . "</strong></div>
                  <div><strong>" . $company['address'] . "</strong></div>
                  <div><strong>" . $company['phone'] . "</strong></div>
                  <div><strong>" . $company['mobile'] . "</strong></div>
                  <div><strong>" . $company['email'] . "</strong></div>
                </td>
              </tr>
            </tbody></table>";
  }

  public function createInvitation($start, $end, $summary, $description)
  {
    $company = Settings::getCompanyInfo();
    $cName = isset($company['company']['name']) ? $company['company']['name'] : "Company";
    $cEmail = isset($company['company']['email']) ? $company['company']['email'] : "selvedinh@gmail.com";
    $cAddress = isset($company['company']['address']) ? $company['company']['address'] : "Zmaja od Bosne 77, Sarajevo";
    $myfile = fopen("meeting.ics", "w") or die("Unable to open file!");
    $dStart = date("Ymd", strtotime($start)) . "T" . date("His", strtotime($start));
    $dEnd = date("Ymd", strtotime($end)) . "T" . date("His", strtotime($end));
    //Create unique identifier
    $cal_uid = date('Ymd') . 'T' . date('His') . "-" . rand() . "@gmail.com";
    $ical = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:' . $cal_uid . '
STATUS:NEEDS-ACTION
DTSTAMP:' . date("Ymd") . 'T' . date("His") . '
ORGANIZER;CN=' . $cName . ':MAILTO:' . $cEmail . '
LOCATION:' . $cAddress . '
BEGIN:VALARM
TRIGGER:-PT30M
ACTION:DISPLAY
END:VALARM
DTSTART:' . $dStart . '
DTEND:' . $dEnd . '
SUMMARY:' . $summary . '
DESCRIPTION:' . $description . '
END:VEVENT
END:VCALENDAR';

    fwrite($myfile, $ical);
  }

  public function isValidEmail($email)
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }
}
