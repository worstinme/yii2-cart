<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

?>

<div class="cart">

    <?php $form = ActiveForm::begin(['id' => 'checkout-form']); ?>

        <?= $form->field($model, 'contactName') ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 (999) 9999999',
        ]) ?>

        <?= $form->field($model, 'address') ?>

        <?= $form->field($model, 'body')->textArea(['rows' => 6, 'placeholder' => 'Оставьте комментарий к заказу']) ?>


        <div class="uk-form-row uk-text-center">
            <?= Html::submitButton('Вернуться назад', ['class' => 'cart-back-button']) ?>
            <?= Html::submitButton('Оформить', ['class' => 'cart-checkout-button']) ?>
        </div>

        <?= Html::hiddenInput('checkout', 'true'); ?>

    <?php ActiveForm::end(); ?>

</div>