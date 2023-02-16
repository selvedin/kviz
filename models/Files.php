<?php

namespace app\models;

use Yii;

define('FILE_TYPES', [
    __("General"), __("Document"), __("Drawings"), __("Image"),
    __("Offer"), __("Purchase Order"), __("Contract"), __("Cheque"),
    __("Visa Copy"), __("Passport Copy")
]);
class Files extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['f_type', 'id_object', 'created_by', 'updated_by', 'deleted', 'private', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 1500],
            [['name'], 'string', 'max' => 1600],
            [['f_name'], 'string', 'max' => 5000],
            [['f_ext'], 'string', 'max' => 128],
            [['object'], 'string', 'max' => 512],
            [['f_size', 'f_date'], 'safe'],
            [['tags'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels =  $this->getLabels();
        $labels['id_file'] = '#';
        $labels['f_size'] = __('Size');
        $labels['f_type'] = __('Type');
        return $labels;
    }

    public static function getTypes($id = -1)
    {
        if ($id == -1) return FILE_TYPES;
        else return isset(FILE_TYPES[$id]) ? FILE_TYPES[$id] : __("NOT SET");
    }

    public static function getObjects()
    {
        $all = [];
        $allObjects = Files::find()->select('object')->distinct()->all();
        foreach ($allObjects as $a) $all[$a->object] = $a->object;
        return $all;
    }

    public static function getAll($object, $id = null)
    {
        return self::find()->where("object='$object' and " . ($id ? " id_object=$id" : " id_object IS NULL"))->all();
    }

    public static function getAllAsJson($object, $id = null)
    {
        $data = [];
        foreach (self::getAll($object, $id) as $file) $data[] = $file->mapToJson();
        return $data;
    }

    public static function getFilesObjects()
    {
        // return Settings::getEnums('FilesObjects');
        return ['apps/view'];
    }

    private function mapToJson()
    {
        return [
            'id_file' => $this->id_file, 'title' => $this->title, 'name' => $this->name,
            'f_name' => $this->f_name, 'f_type' => $this->f_type, 'f_ext' => $this->f_ext,
            'f_size' => $this->f_size, 'f_date' => $this->f_date, 'object' => $this->object,
            'id_object' => $this->id_object, 'deleted' => $this->deleted, 'private' => $this->private,
            'tags' => explode(",", $this->tags)
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->f_size = is_numeric($this->f_size) ? fileSizeFormat($this->f_size) : $this->f_size;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        unlink(Yii::$app->basePath . "/docs/" . $this->f_name);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }
}
