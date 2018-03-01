<?php

use worstinme\zoo\helpers\ImageHelper;
use yii\helpers\Html;
use worstinme\uikit\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model worstinme\cart\models\CartOrders */

$this->title = 'Заказ №' . $model->id;

$this->params['breadcrumbs'][] = ['label'=>'Заказы','url'=>['index']];
$this->params['breadcrumbs'][] = 'Заказ #'.$model->id;
?>

<div class="cart cart-orders">

    <div class="breadcrumbs uk-margin-top">
        <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
    </div>
    <div class="uk-overflow-container">
        <table class="uk-table uk-form uk-table-condensed uk-table-striped uk-table-hover uk-table-bordered">
            <thead>
            <tr>
                <td></td>
                <td>Наименование</td>
                <td class="uk-hidden-small uk-text-center">Статус</td>
                <td class="uk-text-center">Количество</td>
                <td class="uk-text-right">Сумма</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->items as $item): ?>
                <tr>
                    <td> <?php if (!empty($item->model->preview)): ?><?= ImageHelper::thumbnailImg('@webroot'.$item->model->preview, 50, 50, ImageHelper::THUMBNAIL_OUTBOUND, [], 100) ?><?php endif ?> » <?=$item->model->parentCategory->name?></td>
                    <td><?= $item->model->name ?></td>
                    <td class="uk-hidden-small uk-text-center"><?=!empty($model::$states[$model->state]) ? $model::$states[$model->state] : $model->state?></td>
                    <td class="uk-text-center count"><?=$item->count?></td>
                    <td class="uk-text-right price"><b><?=Yii::$app->formatter->asCurrency($item->sum)?></b></td>
                </tr>
            <?php endforeach ?>
            </tbody>
            <?php if (count($model->items) > 1): ?>

                <tfoot>
                <tr>
                    <td class="uk-text-right" colspan="2">Итого:</td>
                    <td class="uk-text-center"><?=$model->amount?></td>
                    <td class="uk-text-right"><b style="font-style: normal;"><?=Yii::$app->formatter->asCurrency($model->sum)?></b></td>
                </tr>
                </tfoot>

            <?php endif ?>
        </table>
    </div>

    <ul class="uk-list uk-list-striped">
        <?php foreach ($model->jsonParams as $param): ?>
        <?php if (!empty($model->{$param})): ?>
        <li><strong><?=$model->getAttributeLabel($param)?></strong>: <?=$model->{$param}?>
            <?php endif ?>
            <?php endforeach ?>
    </ul>

</div>