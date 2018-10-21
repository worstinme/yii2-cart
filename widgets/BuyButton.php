<?php

namespace worstinme\cart\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class BuyButton extends \yii\base\Widget
{

    public $model_id;

    public $model_price;

    public $relation = 0;

    public $options;

    public $label = 'Заказать';

    public function init()
    {
        if (!Yii::$app->request->isAjax) {
            \worstinme\cart\widgets\assets\WidgetAsset::register($this->view);
        }
        parent::init();
    }

    public function run()
    {
        $options = ArrayHelper::merge($this->options,[
            'data' => [
                'item_id' => $this->model_id,
                'relation' => $this->relation,
                'price' => $this->model_price
            ]
        ]);

        Html::addCssClass($options,'cart-buy-button');

        return $this->render('to-order', [
            'label' => $this->label,
            'options'=>$options,
        ]);
    }
}