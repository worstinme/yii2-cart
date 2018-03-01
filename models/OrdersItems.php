<?php

namespace worstinme\cart\models;

use Yii;

/**
 * This is the model class for table "{{%orders_items}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $item_id
 * @property string $model
 * @property double $price
 * @property integer $count
 */
class OrdersItems extends \yii\db\ActiveRecord
{
    private $model;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'relation', 'count'], 'required'],
            [['item_id','relation'], 'integer'],
            [['price'], 'number'],
            [['count'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'item_id' => 'Item ID',
            'relation' => 'relation',
            'price' => 'Price',
            'count' => 'Count',
        ];
    }


    public function getModel() {
        if($this->model === null && !empty(Yii::$app->cart->relations[$this->relation])) {
            $class = Yii::$app->cart->relations[$this->relation];
            $this->model = $class::find()->from($class::tablename())->where([$class::tablename().'.id'=>$this->item_id])->one();
        }
        return $this->model;
    }

    public function getSum() {
        return round($this->count*$this->price,2);
    }
}
