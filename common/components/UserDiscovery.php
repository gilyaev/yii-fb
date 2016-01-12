<?php

class UserDiscovery
{
    /**
     * @var \EFacebook
     */
    protected $fb;

    /**
     * @var
     */
    protected $profileId;

    public function __construct($profileId)
    {
        $this->profileId = $profileId;
        $this->fb = Yii::app()->facebook;
    }

    /**
     * @return \User|null
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    public function discovery()
    {
        $localUser = $this->getLocal();

        if ($localUser) {
            return $localUser;
        }

        return $this->getRemote();
    }

    public function getLocal()
    {
        $c = new EMongoCriteria();
        return User::model()
            ->findOne($c->addOrCondition(array(
                array('id'   => $this->profileId),
                array('name' => $this->profileId)
            )));
    }

    /**
     * @return \User
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    protected function getRemote()
    {
        $data = $this->fb->getProfile($this->profileId);

        $user = new User();
        foreach ($data as $attr => $value) {
            $user->setAttribute($attr, $value);
        }

        $feed = $this->fb->getFeed($user->id, ['limit' => 1]);

        if ($feed->count() > 0) {
            $fields = $feed[0]->getFieldNames();
            if (in_array('updated_time', $fields)) {
                $user->feed_time_field = 'updated_time';
            }
        }

        $user->save();
        return $user;
    }
}
