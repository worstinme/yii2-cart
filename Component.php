<?php

namespace worstinme\cart;

use worstinme\cart\models\OrdersItems;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use worstinme\widgets\models\Widgets;
use worstinme\widgets\models\WidgetsSearch;
use worstinme\widgets\helpers\ShortcodeHelper;
use yii\i18n\PhpMessageSource;
use yii\web\Response;
use yii\web\View;

class Component extends \yii\base\Component implements BootstrapInterface
{
    public $items = [];

    public $min_sum_to_order = 500;

    public $checkout_email_template = '@worstinme/cart/mail/checkout';

    public $checkout_client_email_template = '@worstinme/cart/mail/checkout';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        'cart/<action:(\w|-)+>' => 'cart/<action>',
    ];

    public $relations = [
        '\worstinme\zoo\models\Items',
    ];

    public $orderModel = '\worstinme\cart\models\Orders';

    public $checkoutAccess =  ['?'];

    public $orderRoute = [];

    public $relationNameField = 'name';

    public $relationImageField = 'image';

    public $relationCategoryField = 'category';

    public $states = [
        'new',
        'done',
    ];

    public function init() {

        $cartCookies = Yii::$app->request->cookies->getValue('cart');

        if (is_array($cartCookies)) {
            foreach($cartCookies as $item) {
                $this->add($item);
            }
        }

    }

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /* @var $module Module */

        if ($app instanceof \yii\web\Application) {

            $app->controllerMap['cart'] = [
                'class' => 'worstinme\cart\controllers\CartController',
                'checkoutAccess'=>$this->checkoutAccess,
            ];

            if (!isset($app->get('i18n')->translations['cart*'])) {
                $app->get('i18n')->translations['cart*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => '@worstinme/cart/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }

            $app->urlManager->addRules($this->urlRules, false);

        }

    }

    public function add($data) {

        $item = new OrdersItems();

        $item->setAttributes($data);

        if ($item->validate()) {

            foreach ($this->items as $it) {

                if ($it->item_id == $item->item_id && $it->relation == $item->relation) {
                    $it->count += $item->count;
                    return true;
                }
            }

            $this->items[] = $item;

            return true;
        }

        return false;

    }

    public function close() {
        return Yii::$app->response->cookies->remove('cart');
    }

    public function save()
    {
        $items = [];

        foreach ($this->items as $key=> $item) {
            if ($item->count > 0) {
                $items[] = $item->attributes;
            }
            else {
                unset($this->items[$key]);
            }
        }

        return Yii::$app->response->cookies->add(new \yii\web\Cookie(['name'=>'cart','value'=>$items]));
    }

    public function getTotal() {
        $sum = 0;
        foreach ($this->items as $item) $sum += $item->price*$item->count;
        return round($sum,2);
    }

    public function getSum() {
        return Yii::$app->formatter->asCurrency($this->total);
    }

    public function getAmount() {
        $amount = 0;
        foreach ($this->items as $item) $amount += $item->count;
        return $amount;
    }

}