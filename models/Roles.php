<?php

namespace app\models;

use Yii;
use \yii\helpers\ArrayHelper;

class Roles extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'roles';
    }

    public static function singleName()
    {
        return __("Role");
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 256],
            [['description'], 'string', 'max' => 1000],
            [['created_at', 'updated_at', 'updated_on', 'updated_by', 'private'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->getLabels();
    }

    public function getIsPrivate()
    {
        return $this->private ? __('Yes') : __('No');
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getVals()
    {
        return $this->resolveValues([]);
    }



    public function getFields()
    {
        $flds = [
            'info' => [
                ['fld' => 'name', 'type' => 'text', 'attrs' => ['maxlength' => 256]],
                ['fld' => 'private', 'type' => 'drop', 'vals' => [0 => __("No"), 1 => __("Yes")]],
                ['fld' => 'description', 'type' => 'area', 'attrs' => ['maxlength' => 1000]],
            ],
        ];
        return $flds;
    }

    public static function getNames($id = 0)
    {
        if ($id == 0)
            return ArrayHelper::map(Roles::find()->all(), 'id_role', 'name');
        else
            return ArrayHelper::map(Roles::findOne($id), 'id_role', 'name');
    }

    public static function getRoles($id = 0)
    {
        $roles = ArrayHelper::map(Roles::find()->all(), "id_role", "name");
        if (!is_array($id) && $id)
            return $roles[$id];
        else if (is_array($id)) {
            $r = "";
            foreach ($id as $i) {
                $r .= $roles[$i] . ",";
            }
            return $r;
        } else
            return $roles;
    }
}
