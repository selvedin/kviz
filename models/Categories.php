<?php

namespace app\models;

use Yii;
use app\helpers\UtilHelper;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string|null $color
 * @property string|null $icon
 */
class Categories extends BaseModel
{
    public $fullname;
    public $ids;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent'], 'integer'],
            [['name'], 'string', 'max' => 256],
            [['color', 'icon'], 'string', 'max' => 32],
        ];
    }


    public function getIdParent()
    {
        return $this->hasOne(Categories::class, ['id' => 'parent']);
    }

    public static function getAll($id = NULL)
    {
        $all = [];
        $categories = Categories::find()->where("parent " . ($id ? "=$id" : "is NULL"))->all();
        foreach ($categories as $c) $all[] =  $c->mapToJson();
        return $all;
    }

    public function mapToJson()
    {
        return [
            'id' => $this->id, 'name' => $this->name, 'parent' => $this->parent,
            'color' => $this->color, 'icon' => $this->icon,
            'children' => $this->getChildren()
        ];
    }

    public function getChildren()
    {
        $all = [];
        foreach (Categories::find()->where("parent=$this->id")->all() as $cat) $all[] = $cat->mapToJson();
        return $all;
    }

    public static function getRoot()
    {
        $where = "parent is NULL";
        $subjects = Yii::$app->user->identity->subjectList;
        if (is_array($subjects) && count($subjects)) {
            $where .= " AND id IN (" . implode(",", $subjects) . ")";
        }
        return ArrayHelper::map(self::find()
            ->where($where)
            ->select(["id", "name"])->orderBy('name')->all(), "id", "name");
    }

    public static function getChildrenForSelect($id)
    {
        return ArrayHelper::map(self::find()->where("parent=$id")->select(["id", "name"])->all(), "id", "name");
    }

    public function getFullname()
    {
        $name = "";
        $parent = $this->getParent();
        while (isset($parent)) {
            $name = $parent->name . " / " . $name;
            $parent = $parent->getParent();
        }
        return $name . $this->name;
    }

    public function getParentIds()
    {
        $ids = [$this->id];
        $parent = $this->getParent();
        while (isset($parent)) {
            $ids[] = $parent->id;
            $parent = $parent->getParent();
        }
        return $ids;
    }

    public function getChildIds()
    {
        $ids = [$this->id];
        $childs = $this->getChilds();
        foreach ($childs as $child) $ids[] = $child->getChildIds();
        return $ids;
    }

    public function getRootId()
    {
        return array_values(array_slice($this->parentIds, -1))[0];
    }

    private function getParent()
    {
        return $this->parent ? self::find()->where("id=$this->parent")->one() : null;
    }

    private function getChilds()
    {
        return self::find()->where("parent=$this->id")->select(['id'])->all();
    }

    public static function getIdsByName($name)
    {
        $ids = '';
        foreach (self::find()->where("name LIKE '%$name%'")->all() as $cat) {
            $ids .= ',' . implode(',', UtilHelper::flattenArray($cat->getChildIds()));
        }
        return ltrim($ids, ',');
    }

    public function beforeDelete()
    {
        foreach (Categories::find()->where("parent=$this->id")->all() as $q) $q->delete();
        return parent::beforeDelete();
    }
}
