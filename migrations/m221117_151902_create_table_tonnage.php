<?php

use yii\db\Migration;

/**
 * Class m221117_151902_create_table_tonnage
 */
class m221117_151902_create_table_tonnage extends Migration
{
    const TABLE_NAME = 'tonnage';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey(),
            'value' => $this->integer()->notNull(),
            'update_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
            'create_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $tonnages = [
            1 => 25,
            2 => 50,
            3 => 75,
            4 => 100,
        ];

        foreach ($tonnages as $id => $value) {
            $this->insert(self::TABLE_NAME,[
                'id' => $id,
                'value' => $value,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221117_151902_create_table_tonnage cannot be reverted.\n";

        return false;
    }
    */
}
