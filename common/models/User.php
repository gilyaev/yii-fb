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
        $posts = Post::model()->findByParams($filter);
        return ($posts->count() === 0) ? [] : $posts;
    }

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
