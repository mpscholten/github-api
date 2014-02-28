<?php


namespace MPScholten\GithubApi\Api\User;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\AbstractModelApi;
use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\Api\PopulateableInterface;
use MPScholten\GithubApi\Api\Repository\Repository;
use MPScholten\GithubApi\TemplateUrlGenerator;

/**
 * @link http://developer.github.com/v3/users/
 */
class User extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    private $organizations;
    private $repositories;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @return string The login name of the github account, e.g. "octocat"
     */
    public function getLogin()
    {
        return $this->getAttribute('login');
    }

    /**
     * @return string The avatar url, e.g. "https://github.com/images/error/octocat_happy.gif"
     */
    public function getAvatarUrl()
    {
        return $this->getAttribute('avatar_url');
    }

    /**
     * @return string The gravatar id
     * @link https://gravatar.com/
     */
    public function getGravatarId()
    {
        return $this->getAttribute('gravatar_id');
    }

    /**
     * @return integer The user id, e.g. 42
     */
    public function getId()
    {
        return $this->getAttribute('id');
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
                return $this->getAttribute('html_url');
            case 'api':
                return $this->getAttribute('url');
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
        return $this->getAttribute('name');
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
        $url = TemplateUrlGenerator::generate($this->getAttribute('organizations_url'), []);
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
        $url = $url = TemplateUrlGenerator::generate($this->getAttribute('repositories_url'), []);

        $repositories = [];
        foreach ($this->get($url, ['type' => $type]) as $data) {
            $repository = new Repository($this->client);
            $repository->populate($data);

            $repositories[] = $repository;
        }

        return $repositories;
    }

    /**
     * The returned email is the user’s publicly visible email address (or null if the user has not specified a public
     * email address in their profile).
     *
     * @return string|null The users publicly visible email address or null if not specified by the user
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }
}
