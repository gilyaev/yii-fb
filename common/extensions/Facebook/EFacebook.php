<?php

/**
 * Class EFacebook
 *
 * Component for getting facebook's users data using FacebookSDK.
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
     * Get user feed filtered by parameters
     *
     * @param string $user_id the facebook profile id
     * @param array $params
     * @return mixed
     * @see https://developers.facebook.com/docs/graph-api/reference/v2.5/post
     *
     * @throws Facebook\Exceptions\FacebookSDKException
     */
    public function getFeed($user_id, array $params = [])
    {
        return $this->fb->sendRequest(
            'GET',
            "/{$user_id}/feed",
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