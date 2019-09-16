<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m190914_140012_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'price' => $this->float(),
        ]);

        $this->batchInsert('{{%products}}' , ['name', 'price'], [
            ['MacBook', 3000],
            ['Linovo', 200.30],
            ['HP', 255.1],
            ['Samsung', 300],
            ['LG', 400.50],
            ['Motorola', 5.15],
            ['Iphone', 300],
            ['Ipad', 400.70],
            ['Huawei', 25.30],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%products}}');
    }
}
