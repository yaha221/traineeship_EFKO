<?php

use yii\db\Migration;

/**
 * Class m230111_114944_msdt_release_control
 */
class m230111_114944_msdt_release_control extends Migration
{

    const TABLE_NAME = '{{%msdt_release_control}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'release_control_id' => $this->primaryKey(),
            'key' => $this->string(50)->notNull(),
            'description' => $this->string(255),
            'active' => $this->tinyInteger()->defaultValue(0),
            'locked' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ],);

        $this->createIndex(
            'idx_release_control_key',
            self::TABLE_NAME,
            'key',
            true
        );

        $this->createIndex(
            'idx_release_control_active',
            self::TABLE_NAME,
            'active'
        );

        $this->createIndex(
            'idx_release_control_locked',
            self::TABLE_NAME,
            'locked'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230111_114944_msdt_release_control cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230111_114944_msdt_release_control cannot be reverted.\n";

        return false;
    }
    */
}
