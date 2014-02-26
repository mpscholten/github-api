<?php


namespace MPScholten\GithubApi\Api\User;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\Api\Repository\Repository;
use MPScholten\GithubApi\TemplateUrlGenerator;

/**
 * @link http://developer.github.com/v3/users/
 */
class User extends AbstractApi
{
    const CLASS_NAME = __CLASS__;

    // relations
    private $organizations;
    private $repositories;

    // attributes
    private $id;
    private $login;
    private $avatarUrl;
    private $gravatarId;
    private $name;
    private $email;

    // urls
    private $organizationsUrl;
    private $repositoriesUrl;

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
        $this->email = isset($data['email']) ? $data['email'] : null;

        // urls
        $this->url = $data['url'];
        $this->htmlUrl = $data['html_url'];
        $this->repositoriesUrl = isset($data['repositories_url']) ? $data['repositories_url'] : null;
    }

    private function load()
    {
        $this->populate($this->get($this->url));
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

        throw new \InvalidArgumentException(sprintf(
            'Invalid url type "%s", expected one of "%s"',
            $type,
            implode(', ', ['html', 'api'])
        ));
    }

    /**
     * @return string The real name of the user, e.g. "monalisa octocat"
     */
    public function getName()
    {
        if ($this->name === null) {
            $this->load();
        }

        return $this->name;
    }

    /**
     * @link http://developer.github.com/v3/orgs/#list-user-organizations
     * List all public organizations for an unauthenticated user.
     * Lists private and public organizations for authenticated users.
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
        return $this->createPaginationIterator($url, Organization::CLASS_NAME);
    }

    /**
     * @link http://developer.github.com/v3/repos/#list-user-repositories
     * "List public repositories for the specified user."
     *
     * @param string $type Can be one of all, owner, member. Default: owner
     * @throws \InvalidArgumentException In case the $type is not valid
     * @return Repository[]
     */
    public function getRepositories($type = 'owner')
    {
        $validTypes = ['all', 'owner', 'member'];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid type, expected one of "%s"',
                implode(', ', $validTypes)
            ));
        }

        if (!isset($this->repositories[$type])) {
            $this->repositories[$type] = $this->loadRepositories($type);
        }

        return $this->repositories[$type];
    }

    protected function loadRepositories($type)
    {
        $url = $url = TemplateUrlGenerator::generate($this->getRepositoriesUrl(), []);

        $repositories = [];
        foreach ($this->get($url, ['type' => $type]) as $data) {
            $repository = new Repository($this->client);
            $repository->populate($data);

            $repositories[] = $repository;
        }

        return $repositories;
    }

    private function getRepositoriesUrl()
    {
        if ($this->repositoriesUrl === null) {
            $this->load();
        }

        return $this->repositoriesUrl;
    }

    /**
     * The returned email is the user’s publicly visible email address (or null if the user has not specified a public
     * email address in their profile).
     *
     * @return string|null The users publicly visible email address or null if not specified by the user
     */
    public function getEmail()
    {
        return $this->email;
    }
}
