<?php

namespace worstinme\cart\widgets;

use Yii;

class Cart extends \yii\base\Widget
{
    public function init()
    {
        if (!Yii::$app->request->isAjax) {
            \worstinme\cart\widgets\assets\WidgetAsset::register($this->view);
        }
        parent::init();
    }

    public function run()
    {

        if (Yii::$app->request->post('checkout') == 'true') {


            $class = Yii::$app->cart->orderModel;

            $model = new $class();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                foreach (Yii::$app->cart->items as $item) {
                    $item->order_id = $model->id;
                    $item->save();
                }

                Yii::$app->cart->close();

                Yii::$app->mailer->compose('@worstinme/cart/mail/checkout', ['order' => $model])
                    ->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo(Yii::$app->params['adminEmail'])
                    ->setSubject($model->adminEmailSubject)
                    ->send();

                if (!empty($model->email)) {

                    $mailer = Yii::$app->mailer->compose('@worstinme/cart/mail/checkout', ['order' => $model])
                        ->setFrom($from)
                        ->setTo($model->email)
                        ->setSubject($model->emailSubject);

                    if ($mailer->send()) {
                        Yii::$app->getSession()->setFlash('success', 'Спасибо! Ваш заказ отправлен.');
                    }

                }

                return $this->render('cart-checkout-success', [

                ]);

            } else {

                return $this->render('cart-checkout', [
                    'model' => $model,
                ]);

            }

        }

        if (Yii::$app->request->isPost) {
            if (Yii::$app->cart->add(Yii::$app->request->post())) {
                Yii::$app->cart->save();
            }
        }

        return $this->render('cart');
    }
}