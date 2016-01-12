<?php

class User extends EMongoDocument
{
    /**
     * @var
     */
    public $id;

    /**
     * @var
     */
    public $name;

    /**
     *
     */
    public $feed_time_field = 'created_time';

    /**
     * @var
     */
    public $first_post_date;

    public function collectionName()
    {
        return 'users';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getPosts(array $filter)
    {
        $filter['uid'] = $this->id;
        $filter['sorted_field'] = $this->feed_time_field;
        $posts = Post::model()->findByParams($filter);
        return ($posts->count() === 0) ? [] : $posts;
    }

    /**
     * @return \EMongoDocument|mixed
     * @throws \EMongoException
     */
    public function getFirstPost()
    {
        $lastPost = Post::model()->find(
            [
                'uid' => $this->id
            ]
        )->sort(['created_time' => 1])->limit(1);

        $lastPost->next();

        return $lastPost->current();
    }
}
