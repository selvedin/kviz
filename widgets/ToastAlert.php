<?php

namespace app\widgets;

use app\models\Settings;
use Yii;
use yii\web\View;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class ToastAlert extends \yii\bootstrap5\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - key: the name of the session flash variable
     * - value: the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'error',
        'danger'  => 'error',
        'success' => 'success',
        'info'    => 'info',
        'warning' => 'warning'
    ];
    /**
     * @var array the options for rendering the close button tag.
     * Array will be passed to [[\yii\bootstrap\Alert::closeButton]].
     */
    public $closeButton = [];


    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $duration = Settings::getMessageDuration() * 1000;
        echo "<script>toastr.options = {'closeButton': true,'progressBar': true, 'timeOut': '$duration',};";
        $session = Yii::$app->session;
        foreach (array_keys($this->alertTypes) as $type) {
            $flash = $session->getFlash($type);
            foreach ((array) $flash as $i => $message)
                echo "toastr." . $this->alertTypes[$type] . "('$message', '" . ucfirst($type) . "!');";
            $session->removeFlash($type);
        }
        echo "</script>";
    }
}
