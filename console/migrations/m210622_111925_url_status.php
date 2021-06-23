<?php

use yii\db\Migration;

/**
 * Class m210622_111925_url_status
 */
class m210622_111925_url_status extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%url_status}}', [
            'id' => $this->primaryKey(),
            'hash_string' => $this->string(32)->notNull()->unique(),
            'url' => $this->string(255)->notNull(),
            'status' => $this->integer()->notNull(),
            'query_count' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        if (YII_ENV !== 'prod') {
            $this->dropTable('{{%url_status}}');
        }
    }
}
