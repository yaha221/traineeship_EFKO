<?php

use yii\db\Migration;

/**
 * Class m221117_150801_create_table_month
 */
class m221117_150801_create_table_month extends Migration
{
    const TABLE_NAME = 'month';

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

        $months = [ 
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Август',
            4 => 'Сентябрь',
            5 => 'Октябрь',
            6 => 'Ноябрь',
        ];

        foreach ($months as $id => $name) {
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
        echo "m221117_150801_create_table_month cannot be reverted.\n";

        return false;
    }
    */
}
