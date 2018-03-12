<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

?>

<div class="cart">

    <?php $form = ActiveForm::begin(['id' => 'checkout-form', 'options' => ['data-pjax' => 1]]); ?>

    <?= $form->field($model, 'contactName') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+7 (999) 9999999',
    ]) ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'body')->textArea(['rows' => 6, 'placeholder' => 'Оставьте комментарий к заказу']) ?>

    <div class="uk-form-row uk-text-center">
        <?= Html::submitButton('Оформить', ['class' => 'cart-submit-button']) ?>
    </div>

    <?= Html::hiddenInput('checkout', 'true'); ?>

    <?php ActiveForm::end(); ?>

    <?= Html::a('Вернуться назад', ['data-pjax' => 1, 'class' => 'cart-submit-button', 'name' => 'back', 'value' => 'true']) ?>

</div>