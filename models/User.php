<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username 
 * @property string $first_name
 * @property string $last_name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property string $roles
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password


 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            [['roles'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'first_name' => __('First Name'),
            'last_name' => __('Last Name'),
            'username' => __('Username'),
            'email' => __('Email'),
            'password' => __('Password'),
            'status' => __('Status'),
            'roles' => __('Role')
        ];
    }

    public function getRole()
    {
        return $this->hasOne(Roles::class, ['id_role' => 'role_id']);
    }

    public function getIsActive()
    {
        return self::getStatuses()[$this->status];
    }

    // public function getRole()
    // {
    //     return [1 => 'admin', 9 => 'user'];
    // }


    public function getContact()
    {
        return $this->hasOne(Contact::class, ['id_user' => 'id']);
    }

    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // public static function getRoles()
    // {
    //     return [1 => 'admin', 9 => 'user'];
    // }

    // public static function getRolesNames()
    // {
    //     return ['admin' => 'admin', 'user' => 'user'];
    // }


    public static function getAvailable($id)
    {
        $where = "where id_user IS NOT NULL" . ($id ? "  and contact_id != $id" : "");
        return self::find()->where("id not in (select id_user from contact  $where)")->select("id, username")->all();
    }

    public static function getAvailableAsArray($id)
    {
        return ArrayHelper::map(self::getAvailable($id), 'id', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function generatePasswordToken($id)
    {
        $user = User::findOne($id);
        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $user->save();
        return $user->password_reset_token;
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public static function getLoggedInUser()
    {
        if (Yii::$app->user->isGuest) return null;
        return User::findOne(Yii::$app->user->id);
    }

    public static function isAdmin()
    {
        return Yii::$app->user?->identity?->role == 'admin';
    }

    public static function isUser()
    {
        return Yii::$app->user?->identity?->role == 'user';
    }


    public static function createUserWithDefaultPassword($username, $first_name, $last_name, $email)
    {
        $user = new User();
        $user->username = $username;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->roles = Yii::$app->params['userDefaultRole'];
        $user->setPassword(Yii::$app->params['userDefaultPassword']);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = self::STATUS_ACTIVE;
        $user->created_by = Yii::$app->user->id;
        $user->save();
        return $user;
    }


    public static function getStatuses()
    {
        return [__('Deleted'), 9 => __('Inactive'), __('Active')];
    }
}
