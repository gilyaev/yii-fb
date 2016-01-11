<?php

class EFacebookUserResponse
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function asJson($options = 0)
    {
        $data = $this->user->getAttributes(['id', 'name']);
        return json_encode($data, $options);
    }

    public function __toString()
    {
        return $this->asJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}