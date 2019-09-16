<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_produt}}`.
 */
class m190914_140151_create_order_produt_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_product}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'product_id' => $this->integer(),
            'order_id' => $this->integer(),
            'qty' => $this->integer(10),
            'amount' => $this->float(),
        ]);

        $this->addForeignKey(
            'fk-order_product-product_id',
            'order_product',
            'product_id',
            'products',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-order_product-order_id',
            'order_product',
            'order_id',
            'orders',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_produt}}');
    }
}
