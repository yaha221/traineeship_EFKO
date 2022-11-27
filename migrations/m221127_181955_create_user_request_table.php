<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_request}}`.
 */
class m221127_181955_create_user_request_table extends Migration
{
    const TABLE_NAME = 'user_request';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable(self::TABLE_NAME,[
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'month' => $this->string(50)->notNull(),
            'type' => $this->string(50)->notNull(),
            'tonnage' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'result_value' => $this->string(50)->notNull(),
            'result_table' => $this->text()->notNull(),
            'months_now' => $this->text()->notNull(),
            'tonnages_now' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
