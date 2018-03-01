<?php

use yii\db\Schema;
use yii\db\Migration;

class m160324_030736_cart extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
 
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->string()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'state' => $this->integer()->notNull()->defaultValue(0),
            'params' => $this->text(),
            'paid' => $this->integer()->defaultValue(0),
        ], $tableOptions);

        $this->createTable('{{%orders_items}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'relation' => $this->string()->defaultValue(0),
            'price' => $this->float()->notNull(),
            'count' => $this->integer()->notNull(),
        ], $tableOptions);
 
        
    }

    public function down()
    {

        $this->dropTable('{{%orders}}');
        $this->dropTable('{{%orders_items}}');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
