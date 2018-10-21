<?php

use \yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;

?>

    <div class="cart">
        <?php Pjax::begin(['id' => 'cart-pjax']) ?>

        <?php if (count(Yii::$app->cart->items) <= 0): ?>
            <div class="uk-text-center empty-cart"><?=Yii::t('cart','YOUR_CART_IS_EMPTY')?></div>
        <?php else: ?>

            <?php ActiveForm::begin() ?>
            <div class="cart__list">
                <?php foreach (Yii::$app->cart->items as $item): ?>
                    <div class="cart__line">
                        <?= $this->render('cart-row', ['item' => $item]) ?>
                    </div>
                <?php endforeach ?>
            </div>
            <?php ActiveForm::end() ?>

            <div class="cart__sum"><span><?=Yii::t('cart','ORDER_TOTAL')?>:</span> <?= Yii::$app->cart->sum ?></div>

            <?php if (Yii::$app->cart->total >= Yii::$app->cart->min_sum_to_order): ?>
                <p class="uk-text-center">
                    <?= Html::a(Yii::t('cart','ORDER_CHECKOUT_BUTTON'), '', ['data' => ['method' => 'post', 'params' => ['checkout' => 'true']], 'class' => 'cart-submit-button']); ?>
                </p>
            <?php else: ?>
                <p class="uk-text-center"><?=Yii::t('cart','MIN_ORDER_SUM')?>: <?= Yii::$app->formatter->asCurrency(Yii::$app->cart->min_sum_to_order) ?></p>
            <?php endif ?>

        <?php endif ?>


        <?php Pjax::end() ?>
    </div>

<?php $js = <<<JS
$.pjax.defaults.scrollTo = false;
$("input[name='count']").on("keyup change",function(e){
$(this).next(".cart-button-save").removeClass("uk-hidden");
});

$("body").on("mouseenter keypress click",".cart-button-save",function(e){
var params = $(this).data("params");
var input = $(this).prev("input[name='count']");
params.count = input.val() - Number(input.data('count')); 
$(this).data("params",params);
console.log(params);
});

JS;

$this->registerJs($js, $this::POS_READY);
