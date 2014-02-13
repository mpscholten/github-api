<?php


namespace MPScholten\GithubApi\Api\User;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\TemplateUrlGenerator;
use MPScholten\GithubApi\UrlType;

/**
 * @link http://developer.github.com/v3/users/
 */
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

    /**
     * @return string The login name of the github account, e.g. "octocat"
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string The avatar url, e.g. "https://github.com/images/error/octocat_happy.gif"
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @return string The gravatar id
     * @link https://gravatar.com/
     */
    public function getGravatarId()
    {
        return $this->gravatarId;
    }

    /**
     * @return integer The user id, e.g. 42
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type Can be 'html' or 'api'
     * @return string The url, e.g. https://github.com/octocat (if $type is html)
     * @throws \InvalidArgumentException
     */
    public function getUrl($type = 'html')
    {
        switch ($type) {
            case 'html':
                return $this->htmlUrl;
            case 'api':
                return $this->url;
        }

        throw new \InvalidArgumentException(sprintf('Invalid url type "%s", expected one of "%s"', $type, implode(', ', ['html', 'api'])));
    }

    /**
     * @return string The real name of the user, e.g. "monalisa octocat"
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @link http://developer.github.com/v3/orgs/#list-user-organizations
     * "List all public organizations for an unauthenticated user. Lists private and public organizations for authenticated users."
     *
     * @return Organization[] The public organizations the user is member of
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