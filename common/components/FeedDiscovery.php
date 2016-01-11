<?php

/**
 * Class FeedDiscovery
 */
class FeedDiscovery
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var \User
     */
    protected $profile;

    public function __construct($profileId, array $params = [])
    {
        if (!empty($params['limit']) && $params['limit'] > 100) {
            throw new Exception("The 'limit' parameter should not exceed 100");
        }

        if (!isset($params['limit'])) {
            $params['limit'] = 25;
        }

        if (!empty($params['fields'])) {
            if (is_string($params['fields'])) {
                $params['fields'] = explode(',', $params['fields']);
            }
            $diff = array_diff(
                $params['fields'],
                EFacebookFields::getPostFields()
            );

            if (count($diff) > 0) {
                throw new Exception('Unknown fields:' . implode(',', $diff));
            }
        }

        $this->initProfile($profileId);
        $this->params = $params;
    }

    /**
     * @return array|\EMongoCursor|\EMongoDocument[]|mixed|null
     *
     * @throws \Exception
     */
    public function discovery()
    {
        if (!$this->profile->getIsNewRecord()) {
            $data = $this->getLocal();

            if ($data) {
                return $data;
            }
        }

        $data = $this->getRemote();
        $count = $data->count();

        if ($count > 0) {
            $data = $this->saveRemoteData($data);
        }
        $this->profile->save();
        return $count > 0 ? $data : [];
    }

    /**
     * get profile posts from local db
     *
     * @return array|\EMongoCursor|\EMongoDocument[]|mixed|null
     */
    protected function getLocal()
    {
        $posts = $this->profile->getPosts($this->params);
        if (!empty($posts)) {
            $posts = iterator_to_array($posts);
            if (!empty($this->params['since'])) {
                $posts = array_reverse($posts);
            }
        }
        return $posts;
    }

    /**
     * get profile posts from facebook api
     *
     * @return mixed
     */
    protected function getRemote()
    {
        $fb = Yii::app()->facebook;

        $params = CMap::mergeArray(
            $this->params,
            ['fields' => implode(',', EFacebookFields::getPostFields())]
        );

        if (isset($params['until'])) {
            $params['until'] = ($params['until'] - 2);
        }

        return $fb->getFeed($this->profile->id, $params);
    }

    /**
     * @param \Facebook\GraphNodes\GraphEdge $edge
     * @return array
     * @throws \Exception
     */
    protected function saveRemoteData(Facebook\GraphNodes\GraphEdge $edge)
    {
        $arrPosts = ApiHelper::toPosts($edge);
        try {
            Post::model()->batchInsert($arrPosts, ['continueOnError' => true]);
        } catch (\Exception $e) {
            if ($e->getCode() !== 11000) {
                throw $e;
            }
        }

        $posts = [];
        foreach ($arrPosts as $item) {
            $post = new Post();
            foreach ($item as $attr => $value) {
                $post->setAttribute($attr, $value);
            }
            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * init user object by specified id
     * @param $id
     */
    protected function initProfile($id)
    {
        $profile = User::model()->findOne(['id' => $id]);
        if (!$profile) {
            $profile = new User();
            $profile->id = $id;
        }
        $this->profile = $profile;
    }
}
