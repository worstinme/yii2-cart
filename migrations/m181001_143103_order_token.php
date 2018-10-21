<?php

use yii\db\Migration;

/**
 * Class m181001_143103_order_token
 */
class m181001_143103_order_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%orders}}','token',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181001_143103_order_token cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181001_143103_order_token cannot be reverted.\n";

        return false;
    }
    */
}
