<?php

use worstinme\zoo\helpers\ImageHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$relation_name = ArrayHelper::getValue($item->model, Yii::$app->cart->relationNameField);
$relation_image = ArrayHelper::getValue($item->model, Yii::$app->cart->relationImageField);
$relation_category = ArrayHelper::getValue($item->model, Yii::$app->cart->relationCategoryField);

?>

<div class="cart__line-product">
    <?php if ($relation_image): ?>
        <div class="cart__line-img">
            <?= Html::img($relation_image) ?>
        </div>
    <?php endif ?>
    <div class="cart__line-name">
        <?php if ($relation_name): ?>
            <?= $relation_name ?>
        <?php else: ?>
            <?= $item->item_id ?>
        <?php endif ?>
    </div>
    <?php if ($relation_category): ?>
        <div class="cart__line-description">
            <?= $relation_category ?>
        </div>
    <?php endif ?>
</div>
<div class="cart__line-count-control">
        <?= Html::a('-', '', ['class' => 'cart-button ' . ($item->count > 1 ? '' : 'disabled'), 'data' => [
            'method' => 'post', 'pjax' => true,
            'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => -1,],
        ]]); ?>
        <div class="cart__line-count-control-input">
            <?= Html::textInput('count', $item->count, ['size' => 1, 'class' => 'cart-input', 'data' => ['count' => $item->count]]); ?>
            <?= Html::a('Save', '', ['class' => 'cart-button-save  uk-hidden', 'data' => [
                'method' => 'post', 'pjax' => true,
                'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => 0,],
            ]]); ?>
        </div>
        <?= Html::a('+', '#cart-pjax', ['class' => 'cart-button', 'data' => [
            'method' => 'post', 'pjax' => true,
            'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => 1,],
        ]]); ?>
</div>
<div class="cart__line-price">
    <b><?= Yii::$app->formatter->asCurrency($item->sum) ?></b>
    <?= Html::a('X', '',
        ['data' => [
            'method' => 'post', 'pjax' => true,
            'confirm' => 'Уверены что хотите убрать товар из заказа?',
            'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => -($item->count),],
        ]
        ]); ?>
</div>