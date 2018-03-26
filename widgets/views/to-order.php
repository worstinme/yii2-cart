<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
    <div class="cart-widget">
        <div class="cart-button-group">
            <a class="uk-hidden-small cart-button" data-minus>-</a><!--
            --><?= Html::textInput('count', 1, ['size' => 1, 'required' => true, 'placeholder' => '0', 'class' => 'uk-hidden-small cart-input']); ?><!--
            --><a class="uk-hidden-small cart-button" data-plus>+</a>
        </div>

        <?= Html::a($label, $url = null, ['class' => 'cart-buy-button',
            'data' => [
                'item_id' => $id,
                'relation' => 0,
                'price' => $price
            ]
        ]); ?>

    </div>

<?php $url = yii\helpers\Url::toRoute(['/cart/to-order']);

$js = <<<JS

$(document)
    .on("click", ".cart-widget [data-minus]",function(e){
        e.preventDefault();
        var input = $(this).parent("div").find("[name='count']");
        var val = Number(input.val()) - 1;
        if (val < 1) { val = 1; }
        input.val(val);
        console.log('-1');
    })
    .on("click",".cart-widget [data-plus]",function(e){
        e.preventDefault();
        var input = $(this).parent("div").find("[name='count']");
        input.val(Number(input.val()) + 1);
        console.log('+1');
    })
    .on("click", ".cart-widget .cart-buy-button", function(e) {
        var widget = $(this).parents(".cart-widget"),
            count = $(this).parents(".cart-widget").find("[name='count']").val(), 
            relation = $(this).data("relation"), 
            price = $(this).data("price"), 
            item_id = $(this).data("item_id");
    
        $.ajax({
            url: '$url',
            type: 'POST',
            data: {
                count: count,
                relation: relation,
                item_id: item_id,
                price: price,
                _csrf: yii.getCsrfToken()
            },
            success: function(data) {
                widget.trigger("cart:ordered",data);
            }
        });
        e.preventDefault();
});

JS;

if (!Yii::$app->request->isPjax) {

$this->registerJs($js, $this::POS_READY);

}