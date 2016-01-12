<?php

class ProfileDiscovery
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
     * @return \Profile|null
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    public function discovery()
    {
        $profile = $this->getLocal();

        if ($profile) {
            return $profile;
        }

        return $this->getRemote();
    }

    public function getLocal()
    {
        $c = new EMongoCriteria();
        return Profile::model()
            ->findOne($c->addOrCondition(array(
                array('id'   => $this->profileId),
                array('name' => $this->profileId)
            )));
    }

    /**
     * @return \Profile
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    protected function getRemote()
    {
        $data = $this->fb->getProfile($this->profileId);

        $profile = new Profile();
        foreach ($data as $attr => $value) {
            $profile->setAttribute($attr, $value);
        }

        $feed = $this->fb->getFeed($profile->id, ['limit' => 1]);

        if ($feed->count() > 0) {
            $fields = $feed[0]->getFieldNames();
            if (in_array('updated_time', $fields)) {
                $profile->feed_time_field = 'updated_time';
            }
        }

        $profile->save();
        return $profile;
    }
}
