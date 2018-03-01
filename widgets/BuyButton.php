<?php

namespace worstinme\cart\widgets;

use Yii;

class BuyButton extends \yii\base\Widget
{

    public $model_id;

    public $model_price;

    public $options;

    public $label = 'Заказать';

    public function init()
    {
        if (!Yii::$app->request->isAjax) {
            \worstinme\cart\widgets\assets\WidgetAsset::register($this->view);
        }
        parent::init();
    }

    public function run() {
        
        return $this->render('to-order',[
            'id'=> $this->model_id,
            'price'=>$this->model_price,
            'label'=>$this->label,
        ]);
    }
}