<?php


namespace MPScholten\GithubApi\Api\User;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\TemplateUrlGenerator;

class User extends AbstractApi
{
    // relations
    private $organizations;

    // attributes
    private $id;
    private $login;
    private $avatarUrl;
    private $gravatarId;

    // urls
    private $organizationsUrl;
    private $url;

    public function populate($data)
    {
        $this->id = $data['id'];
        $this->login = $data['login'];
        $this->avatarUrl = $data['avatar_url'];
        $this->gravatarId = $data['gravatar_id'];
        $this->url = $data['url'];
        $this->organizationsUrl = $data['organizations_url'];
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

    /**
     * @return Organization[]
     */
    public function getOrganizations()
    {
        if ($this->organizations === null) {
            $this->organizations = $this->loadOrganizations();
        }

        return $this->organizations;
    }

    protected function loadOrganizations()
    {
        $url = TemplateUrlGenerator::generate($this->organizationsUrl, []);

        $organizations = [];
        foreach($this->get($url) as $data) {
            $organization = new Organization($this->client);
            $organization->populate($data);

            $organizations[] = $organization;
        }

        return $organizations;
    }


}