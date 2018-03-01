<?php

namespace worstinme\cart\controllers;

use worstinme\cart\models\CartOrders;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use Yii;

class CartController extends \yii\web\Controller
{
    public $checkoutAccess = ['*'];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->checkoutAccess,
                    ],
                ],
            ],
        ];
    }

    public function actionToOrder() {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {
            if (Yii::$app->cart->add(Yii::$app->request->post())) {
                Yii::$app->cart->save();
            }
        }

        return [
            'items'=>Yii::$app->cart->items,
            'amount'=>Yii::$app->cart->amount,
            'sum'=> Yii::$app->cart->sum,
            'success'=>true,
        ];

    }

    protected function findModel($id)
    {
        $order = $this->orderModel;

        if (($model = $order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

