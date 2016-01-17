<?php

class m160115_105959_create_profiles_collection extends EMongoMigration
{
    public function up()
    {
        $this->createCollection('profiles');
    }

    public function down()
    {
        $this->setCollectionName('profiles');
        $this->dropCollection();
    }
}
