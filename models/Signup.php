<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "signup".
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property int|null $expiry_date
 * @property int|null $is_active
 */
class Signup extends BaseModel
{
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'signup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'token'], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            [['expiry_date', 'is_active'], 'integer'],
            [['email', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->getLabels();
    }

    public function generateToken()
    {
        $this->is_active = self::STATUS_INACTIVE;
        $this->expiry_date = strtotime("+7 days", time());
        $this->token = Yii::$app->security->generateRandomString() . '_' . $this->expiry_date;
    }

    public static function findByToken($token)
    {
        return static::findOne([
            'token' => $token,
            'is_active' => self::STATUS_INACTIVE
        ]);
    }

    public function sendEmail()
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'signup-html', 'text' => 'signup-text'],
                ['model' => $this]
            )
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' mosannefat'])
            ->setTo($this->email)
            ->setSubject('Signup request: ' . Yii::$app->name)
            ->send();
    }

    public function isRegistered()
    {
        return User::find()->where("email='$this->email'")->exists();
    }

    public function verifySignup()
    {
        $this->is_active = self::STATUS_ACTIVE;
        $this->save();
    }

    public function hasExpiredToken()
    {
        return $this->expiry_date < time();
    }
}
