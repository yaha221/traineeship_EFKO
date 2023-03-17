<?php

use yii\db\Migration;

/**
 * Class m230112_082748_insert_release_control
 */
class m230112_082748_insert_release_control extends Migration
{
    const TABLE_NAME = '{{%msdt_release_control}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert(self::TABLE_NAME, [
            'key' => 'use.vue_js_form_realization',
            'description' => 'Реализация главной страницы на Vue',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230112_082748_insert_release_control cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230112_082748_insert_release_control cannot be reverted.\n";

        return false;
    }
    */
}
