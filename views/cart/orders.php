<?php

use yii\helpers\Html;
use yii\helpers\Url;
use worstinme\uikit\Breadcrumbs;
use yii\grid\GridView;

$this->registerJs('$.pjax.defaults.scrollTo = false', $this::POS_READY);
$this->title = 'Заказы';

$this->params['breadcrumbs'][] = ['label'=>'Ваш заказ','url'=>['index']];
$this->params['breadcrumbs'][] = 'История заказов';

\yii\widgets\Pjax::begin(['id'=>'cart-orders','timeout'=>5000,'options'=>['data-uk-observe'=>true,'scrollTo'=>false,'class'=>'cart']]); ?>

<?php if ($this->context->breadcrumbs): ?>
    <div class="breadcrumbs">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
    </div>
<?php endif ?>

    <h1 class="uk-text-center"><span><?=$this->title?></span></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summaryOptions'=>['class'=>'uk-text-center'],
    'tableOptions'=> ['class' => 'uk-table uk-form uk-table-condensed uk-table-hover uk-table-striped uk-table-bordered uk-margin-top'],
    'options'=> ['class' => 'items'],
    'layout' => '{items}{pager}',
    'pager' => ['class'=> 'worstinme\uikit\widgets\LinkPager'],
    'columns' => [
        [
            'attribute'=>'id',
            'label'=>'#',
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'format'=>'raw',
            'value' => function ($model, $index, $widget) {
                return Html::a('<i class="uk-icon-file-text"></i>', ['order','id'=>$model->id]);
            },
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'created_at',
            'label'=>'Дата заказа',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Yii::$app->formatter->asDate($model->created_at-60*60, 'long');
            },
        ],
        [
            'attribute'=>'created_at',
            'label'=>'Время',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return Yii::$app->formatter->asDate($model->created_at-60*60, 'php:H:i');
            },
        ],
        [
            'attribute'=>'state',
            'label'=>'Статус заказа',
            'format' => 'raw',
            'value' => function ($model, $index, $widget) {
                return !empty($model::$states[$model->state]) ? $model::$states[$model->state] : $model->state;
            },
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'label'=>'Сумма',
            'value' => function ($model, $index, $widget) {
                return Yii::$app->formatter->asCurrency($model->sum);
            },
            'headerOptions'=>['class'=>'uk-text-right'],
            'contentOptions'=>['class'=>'uk-text-right'],
        ],
        [
            'label'=>'Позиций товаров',
            'value' => function ($model, $index, $widget) {
                return $model->getItems()->count();
            },
            'headerOptions'=>['class'=>'uk-text-center'],
            'contentOptions'=>['class'=>'uk-text-center'],
        ],
        [
            'attribute'=>'phone',
        ],

    ],
]); ?>

<?php  \yii\widgets\Pjax::end(); ?>