<?php

namespace app\helpers;

use Yii;
use app\models\Actions;
use app\models\Employees;
use app\models\Projects;
use app\models\Settings;
use app\models\Users;
use yii\bootstrap4\Html;

class NotificationHelper
{
  public static function getNotifications()
  {
    $notify = Settings::getGeneral(6);
    $user = Users::findOne(Yii::$app->user->id);
    $employee = $user->id_employee ? $user->id_employee : 0;
    $taskDueDays = isset($notify['task']['due']) ? $notify['task']['due'] : 0;
    $projectDueDays = isset($notify['project']['due']) ? $notify['project']['due'] : 0;
    $hasTasksToNotify = $taskDueDays && isset($notify['task']['show']);
    $hasProjectsToNotify = $projectDueDays && isset($notify['project']['show']);
    $notes = [];

    if ($hasTasksToNotify) {
      $notes = self::getTaskNotifications($user->id, $employee, $taskDueDays);
    }
    if ($hasProjectsToNotify) {
    }
    return json_encode($notes);
  }

  private static function getTaskNotifications($userId, $employeeID, $dueDays)
  {
    $notes = [];
    $actions = Actions::find()->where("(created_by=$userId || assigned_to=$employeeID) and status<2 and id_parent=0 and (id_project=0 || (id_project>0 and aid>0) 
    and datediff(NOW(), due_date)>0 and datediff(NOW(), due_date)<=$dueDays) || id_action 
    in (select id_action from actions where id_project in (select id_project from projects where sales_manager=$employeeID and operation_status=1) 
    and id_parent=0 and aid>0 and datediff(NOW(), due_date)>0 and datediff(NOW(), due_date)<=$dueDays)")
      ->select("id_action, title, due_date, assigned_to")->orderBy("due_date ASC")->all();

    $total = Actions::find()->where("(created_by=$userId || assigned_to=$employeeID) and status<2 and id_parent=0 and (id_project=0 || (id_project>0 and aid>0) 
    and datediff(NOW(), due_date)>0 and datediff(NOW(), due_date)<=$dueDays) || id_action in (select id_action from actions where id_project 
    in (select id_project from projects where sales_manager=$employeeID and operation_status=1) and id_parent=0 and aid>0 and datediff(NOW(), due_date)>0 
    and datediff(NOW(), due_date)<=$dueDays)")->count();

    if ($total) $notes = self::mapTaskNotes($total, $dueDays, $actions);
    return $notes;
  }

  private static function mapTaskNotes($total, $dueDays, $actions)
  {
    $items = "";
    $br = 1;
    $notes = [];
    $title = sprintf("You have %u tasks expiring in %u days", $total, $dueDays);
    $message = "%u. %s, assigned to %s, and it is expiring on %s.";
    foreach ($actions as $act) {
      $assignedto = Employees::getNames($act->assigned_to);
      if (!is_string($assignedto)) {
        $assignedto = __("Nobody");
      }
      $items .= "<p>" . sprintf($message, $br, Html::a($act->title, ['actions/update', 'id' => $act->id_action]), $assignedto, $act->due_date) . "</p>";
      $br++;
    }
    $notes['tasks'] = ['title' => $title, 'message' => $items];
    return $notes;
  }

  private static function getProjectNotifications($userId, $employeeId, $dueDays)
  {
    $notes = [];
    $actions = Projects::find()->where("(created_by=$userId|| sales_manager=$employeeId) and operation_status=1 and datediff(expected_date, NOW())>0 
    and datediff(expected_date, NOW())<=$dueDays")->select("id_project, title, expected_date, sales_manager")->orderBy("expected_date ASC")->all();
    $total = Projects::find()->where("(created_by=$userId || sales_manager=$employeeId) and operation_status=1 and datediff(expected_date, NOW())>0 
      and datediff(expected_date, NOW())<=$dueDays")->count();
    if ($total) {
      $notes = self::mapProjectTasks($total, $dueDays, $actions);
    }
    return $notes;
  }

  private static function mapProjectTasks($total, $dueDays, $actions)
  {
    $items = "";
    $br = 1;
    $notes = [];
    $title = sprintf("You have %u projects expiring in %u days", $total, $dueDays);
    $message = "%u. %s, Project Manager-  %s, and due date is on %s.";
    foreach ($actions as $act) {
      $assignedto = \app\models\Employees::getNames($act->sales_manager);
      if (!is_string($assignedto)) {
        $assignedto = __("Nobody");
      }
      $items .= "<p>" . sprintf($message, $br, Html::a($act->title, ['projects/update', 'id' => $act->id_project]), $assignedto, $act->expected_date) . "</p>";
      $br++;
    }
    $notes['projects'] = ['title' => $title, 'message' => $items];
    return $notes;
  }

  public static function notifyUser($user, $object, $templateName, $emailTemplate = 'task', $setCalendar = false)
  {
    $emailHelper = new EmailHelper();
    if ($emailHelper->isValidEmail($user->email) && $emailHelper->canSendEmailForTemplate($templateName)) {
      $files = [];
      if ($setCalendar) {
        $emailHelper->createInvitation($user->address, $object->start_date, $object->due_date, $object->title, __('Action is waiting for your response'));
        $files = [];
        if (file_exists("meeting.ics")) $files[] = "meeting.ics";
      }
      $mailTitle = isset(EMAIL_TEMPLATES[$templateName]) ? __(EMAIL_TEMPLATES[$templateName]) : Yii::$app->name . ' ' . __('Notification');
      $emailHelper->sendEmail($object, $user->email,  $mailTitle, $templateName, $emailTemplate, $files, $user->name, $user->getLang());
      if (file_exists("meeting.ics")) unlink("meeting.ics");
    }
  }
}
