<?php

namespace worstinme\cart\widgets\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class WidgetAsset extends AssetBundle
{
    public $sourcePath = '@worstinme/cart/widgets/assets/source';

    public $css = [
        'css/cart.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        if (YII_ENV_DEV) {

            $this->publishOptions = [
                'forceCopy'=>true,
                'appendTimestamp' => true,
            ];

        }

        parent::init();
    }
}
