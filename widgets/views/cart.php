<?php

use \yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

    <div class="cart uk-overflow-auto">

        <?php if (count(Yii::$app->cart->items) <= 0): ?>
            <div class="uk-text-center empty-cart">Ваш заказ пуст.</div>
        <?php else: ?>

            <table class="uk-table uk-table-small uk-table-striped uk-table-hover uk-table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Наименование</th>
                    <th class="uk-text-center" colspan="3">Количество</th>
                    <th class="uk-text-right">Сумма</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (Yii::$app->cart->items as $item): ?>
                    <?= $this->render('cart-row', ['item' => $item]) ?>
                <?php endforeach ?>
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="uk-text-right">Итого:</td>
                    <td></td>
                    <td class="uk-text-center"><?= Yii::$app->cart->amount ?></td>
                    <td></td>
                    <td class="uk-text-right"><b style="font-style: normal;"><?= Yii::$app->cart->sum ?></b></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>

            <?php if (Yii::$app->cart->total >= Yii::$app->cart->min_sum_to_order): ?>
                <p class="uk-text-center">
                    <?= Html::a('Продолжить','', ['data' => ['pjax' => true, 'method' => 'post', 'params' => ['checkout' => 'true']], 'class' => 'cart-submit-button']); ?>
                </p>
            <?php else: ?>
                <p class="uk-text-center">Минимальная сумма
                    заказа: <?= Yii::$app->formatter->asCurrency(Yii::$app->cart->min_sum_to_order) ?></p>
            <?php endif ?>

        <?php endif ?>
    </div>

<?php $js = <<<JS

$("input[name='count']").on("keyup change",function(e){
$(this).next(".cart-save").removeClass("uk-hidden");
});

$("body").on("mouseenter keypress click",".cart-save",function(e){
var params = $(this).data("params");
var input = $(this).prev("input[name='count']");
params.count = input.val() - Number(input.data('count')); 
$(this).data("params",params);
console.log(params);
});

JS;

    $this->registerJs($js, $this::POS_READY);
