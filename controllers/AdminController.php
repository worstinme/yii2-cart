<?php

namespace worstinme\cart\controllers;

use Yii;
use worstinme\cart\models\CartOrders;
use worstinme\cart\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for CartOrders model.
 */
class AdminController extends Controller
{
    public $access = ['admin'];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->access,
                        'actions'=>['index','update','delete'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CartOrders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Updates an existing CartOrders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $stateBefore = $model->state;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success','Ствтус заказа обновлён');
        }

        if ($model->state == 5 && $stateBefore != $model->state) {

            Yii::$app->mailer->compose(Yii::$app->cart->delivered_email_template,['order'=>$model])
                ->setTo($model->email)
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setSubject('Completed order')
                ->send();

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CartOrders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CartOrders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CartOrders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {   
        $order = Yii::$app->cart->orderModel;
        
        if (($model = $order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getViewPath()
    {
        return Yii::getAlias('@worstinme/cart/views/admin');
    }
}
