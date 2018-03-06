<?php

use worstinme\zoo\helpers\ImageHelper;
use yii\helpers\Html;
use worstinme\uikit\Breadcrumbs;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model worstinme\cart\models\CartOrders */

$this->title = 'Заказ №' . $model->id;

$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Заказ #' . $model->id;
?>

<div class="cart cart-orders">

    <div class="uk-overflow-container">
        <table class="uk-table uk-form uk-table-small uk-table-condensed uk-table-hover uk-table-striped uk-margin-top">
            <thead>
            <tr>
                <td colspan="3">Наименование</td>
                <td class="uk-text-center">Количество</td>
                <td class="uk-text-right">Сумма</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->items as $item): ?>
                <?= $this->render('row', [
                    'item' => $item,
                ]); ?>
            <?php endforeach ?>
            </tbody>
            <?php if (count($model->items) > 1): ?>

                <tfoot>
                <tr>
                    <td class="uk-hidden-small uk-text-center">
                        <?php $form = ActiveForm::begin() ?>
                        <?= Html::activeDropDownList($model, 'state',Yii::$app->cart->states, ['class' => 'uk-select','onchange'=>'$(this).parents("form").submit()']) ?>
                        <?php ActiveForm::end() ?>
                    </td>
                    <td class="uk-text-right" colspan="2">Итого:</td>
                    <td class="uk-text-center"><?= $model->amount ?></td>
                    <td class="uk-text-right"><b
                                style="font-style: normal;"><?= Yii::$app->formatter->asCurrency($model->sum) ?></b>
                    </td>
                </tr>
                </tfoot>

            <?php endif ?>
        </table>
    </div>

    <div class="uk-panel uk-panel-box" style="padding: 0">
        <ul class="uk-list uk-list-striped">
            <?php foreach ($model->jsonParams as $param): ?>
            <?php if (!empty($model->{$param})): ?>
            <li><strong><?= $model->getAttributeLabel($param) ?></strong>: <?= $model->{$param} ?>
                <?php endif ?>
                <?php endforeach ?>
        </ul>
    </div>
</div>