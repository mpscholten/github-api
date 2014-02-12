<?php


namespace MPScholten\GithubApi\Api\User;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\TemplateUrlGenerator;
use MPScholten\GithubApi\UrlType;

class User extends AbstractApi
{
    // relations
    private $organizations;

    // attributes
    private $id;
    private $login;
    private $avatarUrl;
    private $gravatarId;
    private $name;

    // urls
    private $organizationsUrl;

    private $htmlUrl;
    private $url;

    public function populate($data)
    {
        $this->id = $data['id'];
        $this->login = $data['login'];
        $this->avatarUrl = $data['avatar_url'];
        $this->gravatarId = $data['gravatar_id'];
        $this->organizationsUrl = $data['organizations_url'];
        $this->name = isset($data['name']) ? $data['name'] : null;

        // urls
        $this->url = $data['url'];
        $this->htmlUrl = $data['html_url'];
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

    public function getId()
    {
        return $this->id;
    }

    public function getUrl($type = null)
    {
        switch ($type) {
            case UrlType::HTML:
                return $this->htmlUrl;
            default:
                return $this->url;
        }
    }

    public function getName()
    {
        return $this->name;
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
        foreach ($this->get($url) as $data) {
            $organization = new Organization($this->client);
            $organization->populate($data);

            $organizations[] = $organization;
        }

        return $organizations;
    }


}