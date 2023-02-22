<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_subject".
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property int|null $perms
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class UserSubject extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'subject_id'], 'required'],
            [['user_id', 'subject_id', 'perms', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getSubject()
    {
        return $this->hasOne(Categories::class, ['id' => 'subject_id']);
    }
}
