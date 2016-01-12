<?php

class Profile extends EMongoDocument
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
        return 'profiles';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getPosts(array $filter)
    {
        $filter['pid'] = $this->id;
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
                'pid' => $this->id
            ]
        )->sort(['created_time' => 1])->limit(1);

        $lastPost->next();

        return $lastPost->current();
    }

    /**
     * Update profile first posted post date
     * @return bool
     */
    public function updateFirstPostDate()
    {
        $post = $this->getFirstPost();
        $this->first_post_date = time();

        if ($post) {
            $this->first_post_date = $post->getAttribute('created_time');
        }

        return $this->save();
    }
}
