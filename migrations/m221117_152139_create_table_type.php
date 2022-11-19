<?php

use yii\db\Migration;

/**
 * Class m221117_152139_create_table_type
 */
class m221117_152139_create_table_type extends Migration
{
    const TABLE_NAME = 'type';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'update_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
            'create_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);

        $types = [
            1 => 'Соя',
            2 => 'Жмых',
            3 => 'Шрот',
        ];

        foreach ($types as $id => $name) {
            $this->insert(self::TABLE_NAME,[
                'id' => $id,
                'name' => $name,
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
        echo "m221117_152139_create_table_type cannot be reverted.\n";

        return false;
    }
    */
}
