<?php

class EFacebookProfileResponse
{
    /**
     * @var \Profile
     */
    private $profile;

    public function __construct(\Profile $profile)
    {
        $this->profile = $profile;
    }

    public function asJson($options = 0)
    {
        $data = $this->profile->getAttributes(['id', 'name']);
        return json_encode($data, $options);
    }

    public function __toString()
    {
        return $this->asJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}