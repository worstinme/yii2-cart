<?php

use worstinme\zoo\helpers\ImageHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$relation_name = ArrayHelper::getValue($item->model, Yii::$app->cart->relationNameField);
$relation_image = ArrayHelper::getValue($item->model, Yii::$app->cart->relationImageField);
$relation_category = ArrayHelper::getValue($item->model, Yii::$app->cart->relationCategoryField);

?>

<tr>
    <td class="img">
        <?php if ($relation_image): ?>
            <?= Html::img($relation_image) ?>
        <?php endif ?>
    </td>
    <td class="category">
        <?php if ($relation_category): ?>
            <?= $relation_category ?>
        <?php endif ?>
    </td>
    <td class="itemid">
        <?php if ($relation_name): ?>
            <?= $relation_name ?>
        <?php else: ?>
            <?= $item->item_id ?>
        <?php endif ?>
    </td>
    <td class="uk-text-right">
        <?php if ($item->count > 1): ?>
            <?= Html::a('-', ['index'], ['class' => 'cart-button', 'data' => [
                'method' => 'post', 'pjax' => true,
                'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => -1,],
            ]]); ?>
        <?php endif ?>
    </td>
    <td class="uk-text-center count">
        <?= Html::textInput('count', $item->count, ['size' => 1, 'class' => 'cart-input', 'data' => ['count' => $item->count]]); ?>
        <?= Html::a('Save', ['index'], ['class' => 'cart-button-save  uk-hidden', 'data' => [
            'method' => 'post', 'pjax' => true,
            'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => 0,],
        ]]); ?>
    </td>
    <td>
        <?= Html::a('+', ['index'], ['class' => 'cart-button', 'data' => [
            'method' => 'post', 'pjax' => true,
            'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => 1,],
        ]]); ?>
    </td>
    <td class="uk-text-right price"><b><?= Yii::$app->formatter->asCurrency($item->sum) ?></b></td>
    <td class="uk-text-center">
        <?= Html::a('X', ['index'],
            ['data' => [
                'method' => 'post', 'pjax' => true,
                'confirm' => 'Уверены что хотите убрать товар из заказа?',
                'params' => ['item_id' => $item->item_id, 'relation' => $item->relation, 'count' => -($item->count),],
            ]
            ]); ?>
    </td>
</tr>