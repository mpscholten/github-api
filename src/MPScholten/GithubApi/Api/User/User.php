<?php


namespace MPScholten\GithubApi\Api\User;


use MPScholten\GithubApi\Api\AbstractApi;

class User extends AbstractApi
{
    private $id;
    private $login;
    private $avatarUrl;
    private $gravatarId;
    private $url;

    public function populate($data)
    {
        $this->id = $data['id'];
        $this->login = $data['login'];
        $this->avatarUrl = $data['avatar_url'];
        $this->gravatarId = $data['gravatar_id'];
        $this->url = $data['url'];
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    public function getGravatarId()
    {
        return $this->gravatarId;
    }


}