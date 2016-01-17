<?php

class m160115_113138_create_posts_collection extends EMongoMigration
{
    public function up()
    {
        $this->setCollectionName('posts');
        $this->createCollection('posts');
        $this->ensureIndex(['id' => 1, 'pid' => 1], ['unique' => true]);
    }

    public function down()
    {
        $this->setCollectionName('posts');
        $this->dropCollection();
    }
}
