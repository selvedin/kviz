<?php

namespace app\models;

use Exception;
use Yii;

/**
 * This is the model class for table "api_calls".
 *
 * @property int $id
 * @property string $api_key
 * @property string $ip
 * @property int $total
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 */
class ApiCalls extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_calls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['api_key', 'ip', 'total'], 'required'],
            [['total', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['api_key'], 'string', 'max' => 256],
            [['ip'], 'string', 'max' => 32],
        ];
    }

    public static function getTotalCalls()
    {
        $start = strtotime(date("Y-m-d 00:00:00"));
        $end = strtotime(date("Y-m-d 23:59:59"));
        return ApiCalls::find()
            ->where("created_by=" . Yii::$app->user->id . " AND created_at BETWEEN $start AND $end")
            ->sum('total');
    }

    public static function add($api, $ip, $total)
    {
        $api_call = new ApiCalls([
            'api_key' => $api,
            'ip' => $ip,
            'total' => $total,
        ]);
        if (!$api_call->save())
            throw new Exception("Error saving API call. | " . json_encode($api_call->errors));
    }
}
