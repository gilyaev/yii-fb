<?php

/**
 * Class EFacebook
 *
 * Component for getting facebook's profile data using FacebookSDK.
 * @see https://developers.facebook.com/docs/reference/php
 *
 */
class EFacebook extends CApplicationComponent
{
    /**
     * Facebook application id
     * @var string
     */
    public $app_id;

    /**
     * Facebook application secret key
     * @var string
     */
    public $app_secret;
    public $options = [];

    /**
     * @var \Facebook\Facebook
     */
    private $fb;

    /**
     * Init Facebook super-class object with applications settings
     */
    public function init()
    {
        parent::init();
        $this->fb = new \Facebook\Facebook(array(
            'app_id' => $this->app_id,
            'app_secret' => $this->app_secret
        ));

        $this->fb->setDefaultAccessToken($this->fb->getApp()->getAccessToken());
    }

    /**
     * Get profile feed filtered by parameters
     *
     * @param string $profileId the facebook profile id
     * @param array $params
     * @return mixed
     * @see https://developers.facebook.com/docs/graph-api/reference/v2.5/post
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    public function getFeed($profileId, array $params = [])
    {
        return $this->fb->sendRequest(
            'GET',
            "/{$profileId}/feed",
            $params
        )->getGraphEdge();
    }

    /**
     * Get profile information by specified profile page alias
     *
     * @param string $alias user's page alias
     * @return array
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    public function getProfile($alias)
    {
        return $this->fb->get("/{$alias}")
            ->getDecodedBody();
    }
}
