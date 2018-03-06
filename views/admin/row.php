<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
    <td class="uk-text-center count"><?= $item->count ?></td>
    <td class="uk-text-right price"><b><?= Yii::$app->formatter->asCurrency($item->sum) ?></b></td>
</tr>
