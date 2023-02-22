<?php

namespace app\models;

use app\helpers\CacheHelper;
use Yii;

const PERMS_USERS = "perms_users";
const PERMS_OBJECTS = "perms_objects";

class Perms extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'perms';
    }

    public static function singleName()
    {
        return __("Permission");
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object'], 'required'],
            [['perms'], 'string'],
            [['object'], 'string', 'max' => 256],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return $this->getLabels();
    }


    public static function getPerms($obj = "", $act = "", $id = 0)
    {


        if (Yii::$app->user->isGuest) return 0;
        if (User::isAdmin()) return 5; // superadmin -  grant all

        //create object in permissions if not exists
        $permsObject = Perms::find()->where("object='$obj'")->one();
        if ($obj != "" && !isset($permsObject)) {
            $permsObject = new Perms;
            $permsObject->object = $obj;
            $permsObject->save();
        }
        if ($id > 0) $user = User::findOne($id);
        else $user = User::findOne(Yii::$app->user->identity->id);

        $model = null;
        $data = CacheHelper::get(PERMS_USERS);
        if ($data === false) {
            $all = Perms::find()->all();
            foreach ($all as $a)  $model[$a->object] = $a->perms;
            CacheHelper::set(PERMS_USERS, $model);
        } else $model = $data;

        if (!is_array($user->roles)) return 0;
        if (!isset($model[$obj])) return 0;
        if (!is_array($model[$obj])) return 0;
        foreach ($user->roles as $k) {
            if (array_key_exists($k, $model[$obj])) {
                if ($act == "") return 1;
                if (in_array($act, $model[$obj][$k])) return 1;
                else return 0;
            } else return 0;
        }
        return 0;
    }

    public static function getObjects()
    {
        $objects = CacheHelper::get(PERMS_OBJECTS);
        if ($objects == false) {
            foreach (self::find()->select("id, object")->all() as $object) $objects[$object->id] = __($object->object);
            CacheHelper::set(PERMS_OBJECTS, $objects);
        }
        return $objects;
    }

    public function canList($objectName)
    {
        return $this->havePermission($objectName, 1);
    }

    public function canView($objectName)
    {
        return $this->havePermission($objectName, 2);
    }

    public function canCreate($objectName)
    {
        return $this->havePermission($objectName, 3);
    }

    public function canUpdate($objectName)
    {
        return $this->havePermission($objectName, 4);
    }

    public function canDelete($objectName)
    {
        return $this->havePermission($objectName, 5);
    }

    public static function getAllData()
    {
        return self::getCachedData();
    }


    private static function getPermisionData()
    {
        return self::getCachedData();
    }

    private function havePermission($objectName, $level)
    {
        if (Yii::$app->user->isGuest) return false;
        $user = User::getLoggedInUser();
        if (User::isAdmin()) return true;

        $data = self::getPermisionData();
        if (!isset($data[$objectName])) {
            self::createObjectIfNotExist($objectName);
            return false;
        }

        if (!$user->role_id) return false;

        if (isset($data[$objectName][$user->role_id]) && is_array($data[$objectName][$user->role_id]))
            return $level <= (int)$data[$objectName][$user->role_id][0];
        return false;
    }


    private static function getCachedData()
    {
        $model = CacheHelper::get(PERMS_USERS);
        if ($model === false) self::setCacheData($model);
        return $model;
    }

    private static function setCacheData(&$model)
    {
        $all = Perms::find()->all();
        foreach ($all as $a) $model[$a->object] = $a->perms;
        CacheHelper::set(PERMS_USERS, $model);
    }

    private static function createObjectIfNotExist($objectName)
    {
        if ($objectName == '') return false;
        $existingObject = Perms::find()->where("object='$objectName'")->one();
        if (isset($existingObject)) return false;

        $permsObject = new Perms;
        $permsObject->object = $objectName;
        if ($permsObject->save()) return true;
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->perms = unserialize($this->perms);
    }
}
