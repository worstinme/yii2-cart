<?php

namespace worstinme\cart\models;

use Yii;
use yii\helpers\Json;

/**
 * Class Orders
 *
 * @property string $token
 * @property int $amount
 * @property int $sum
 */
class Orders extends \yii\db\ActiveRecord
{
    public $emailSubject = 'Заказ с сайта';
    public $adminEmailSubject = 'Заказ с сайта';

    public $jsonParams = ['address','body','contactName','email','phone'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state', 'paid'], 'integer'],
            [$this->jsonParams,'string'],
            [['email','phone','address'],'required'],
            ['params','safe'],
            ['email','email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'state' => 'State',
            'address' => 'Адрес',
            'body' => 'Сообщение',
            'contactName' => 'Контактное лицо',
            'email' => 'Электронная почта',
            'phone' => 'Телефон',
            'paid' => 'Paid',
        ];
    }

    public function getItems() {
        return $this->hasMany(OrdersItems::className(),['order_id'=>'id']);
    }

    public function getSum() {
        return round(OrdersItems::find()->where(['order_id'=>$this->id])->sum('price*count'),2);
    }

    public function getTaxes() {
        if (Yii::$app->cart->tax > 0) {
            return round($this->sum * Yii::$app->cart->tax / 100,2);
        }
        return 0;
    }

    public function getTotal() {
        return $this->sum + $this->taxes;
    }

    public function getAmount() {
        $amount = 0;
        foreach ($this->items as $item) $amount += $item->count;
        return $amount;
    }
    
    public function __get($name)
    { 
        if (in_array($name, $this->jsonParams)) {
            return $this->getJsonParams($name);
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    { 
        if (in_array($name, $this->jsonParams)) {
            return $this->setJsonParams($name, $value);
        } else {
            return parent::__set($name, $value);
        }
    } 

    public function getJsonParams($name) {
        $params = !empty($this->params) ? Json::decode($this->params) : [];
        return isset($params[$name]) ? $params[$name] : null;
    }

    public function setJsonParams($name,$value) {
        $params = !empty($this->params) ? Json::decode($this->params) : [];
        $params[$name] = $value;
        return $this->params = Json::encode($params);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (!Yii::$app->user->isGuest) {
                $this->user_id = Yii::$app->user->identity->id;
            }
            if ($this->token === null) {
                $this->token = Yii::$app->security->generateRandomString();
            }
        }

        return parent::beforeSave($insert);
    }


    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%orders_items}}', ['order_id'=>$this->id])->execute();
        parent::afterDelete();
        
    }

}
