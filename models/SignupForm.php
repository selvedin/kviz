<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    const SCENARIO_PSWREC = 'pswrec';

    public $username;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $password_repeat;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username', 'email'], 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => __('This username has already been taken') . '.'],
            [['username', 'first_name', 'last_name'], 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => __('This email address has already been taken') . '.'],
            ['password', 'string', 'min' => Yii::$app->params['passwordMinLength']],
            ['password', 'checkPasswordRepeat'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => __('Password does not match')],
            [['username', 'email', 'password', 'password_repeat'], 'required', 'on' => self::SCENARIO_PSWREC],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => __('Password does not match'), 'on' => self::SCENARIO_PSWREC],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_PSWREC] = ['first_name', 'last_name', 'username', 'email', 'password', 'password_repeat'];
        return $scenarios;
    }

    public function checkPasswordRepeat()
    {
        if (!$this->hasErrors()) {
            if ($this->password != '' && $this->password != $this->password_repeat)
                $this->addError('password_repeat', __('Password does not match') . ".");
        }
    }
    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->email = $this->email;
        $user->role_id = Yii::$app->params['userDefaultRole'];
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $isUserSaved = $user->save();
        $isEmailSent = $this->sendEmail($user);
        return $isUserSaved && $isEmailSent;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' mosannefat'])
            ->setTo($this->email)
            ->setSubject(__('Account registration at ') . Yii::$app->name)
            ->send();
    }
}
