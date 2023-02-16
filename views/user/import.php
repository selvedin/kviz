<?php

use app\models\User;
use yii\bootstrap5\Html;

$this->title = "Import";
$fields = [
    'ID', 'user_login', 'user_pass', 'user_nicename', 'user_email',
    'user_url', 'user_registered', 'user_activation_key', 'user_status', 'display_name'
]
?>
<table dir="ltr" class="table table-striped">
    <thead>
        <tr>
            <?php
            foreach ($fields as $f) echo Html::tag('th', $f);
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($old as $o) {
            echo "<tr>";
            foreach ($fields as $f) echo Html::tag('td', $o[$f]);
            echo "</tr>";
            //Preventing re-import of users
            if (false) {
                $user = User::createUserWithDefaultPassword($o['user_login'], $o['display_name'], '', $o['user_email']);
                echo Html::tag(
                    'tr',
                    Html::tag('td', "") .
                        Html::tag('td', $user->username) .
                        Html::tag('td', $user->password_hash) .
                        Html::tag('td', $user->username) .
                        Html::tag('td', $user->email) .
                        Html::tag('td', "") .
                        Html::tag('td', $user->created_at) .
                        Html::tag('td', $user->verification_token) .
                        Html::tag('td', $user->status) .
                        Html::tag('td', $user->name)
                );
            }
        }
        ?>
    </tbody>

</table>