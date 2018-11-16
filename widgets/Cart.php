<?php

namespace worstinme\cart\widgets;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class Cart extends \yii\base\Widget
{
    public $senderEmail = 'example@example.com';
    public $adminEmail = 'example@example.com';

    public function init()
    {
        if (!Yii::$app->request->isAjax) {
            \worstinme\cart\widgets\assets\WidgetAsset::register($this->view);
        }
        parent::init();
    }

    public function run()
    {

        if (Yii::$app->request->post('back') === null && Yii::$app->request->post('checkout') == 'true') {

            $class = Yii::$app->cart->orderModel;

            $model = new $class();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                foreach (Yii::$app->cart->items as $item) {
                    $item->order_id = $model->id;
                    $item->save();
                }

                Yii::$app->cart->close();

                if (Yii::$app->cart->sendAdminCheckoutEmail) {

                    Yii::$app->mailer->compose(Yii::$app->cart->checkout_email_template, ['order' => $model])
                        ->setFrom($this->senderEmail)
                        ->setTo($this->adminEmail)
                        ->setSubject($model->adminEmailSubject)
                        ->send();

                }

                if (!empty($model->email)) {

                    $mailer = Yii::$app->mailer->compose(Yii::$app->cart->checkout_client_email_template, ['order' => $model])
                        ->setFrom($this->senderEmail)
                        ->setTo($model->email)
                        ->setSubject($model->emailSubject);

                    if ($mailer->send()) {
                        Yii::$app->getSession()->setFlash('success', Yii::t('cart','SUCCESS_CHECKOUT'));
                    }

                }

                if (($orderRoute = Yii::$app->cart->orderRoute) !== null) {
                    $orderRoute['id'] = $model->id;
                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'order-'.$model->id,
                        'value' => $model->token,
                    ]));
                    Yii::$app->response->redirect($orderRoute);
                    Yii::$app->end();

                }

                return $this->render('cart-checkout-success', [

                ]);

            } else {

                return $this->render('cart-checkout', [
                    'model' => $model,
                ]);

            }

        }

        if (Yii::$app->request->isPost && Yii::$app->request->post('back') === null) {
            if (Yii::$app->cart->add(Yii::$app->request->post())) {
                Yii::$app->cart->save();
            }
        }

        return $this->render('cart');
    }
}