<?php

namespace MPScholten\GitHubApi\Api\Organization;

use MPScholten\GitHubApi\Api\AbstractModelApi;
use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\TemplateUrlGenerator;

class Organization extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    private $repositories;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @link http://developer.github.com/v3/repos/#list-organization-repositories
     * List repositories for the specified Organization.
     *
     * @param string $type Can be one of all, public, private, forks, sources, member. Default: all
     * @throws \InvalidArgumentException In case the $type is not valid
     * @return Repository[]
     */
    public function getRepositories($type = 'all')
    {
        $validTypes = ['all', 'public', 'private', 'forks', 'sources', 'member'];
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

    private function loadRepositories($type)
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('repos_url'), []);
        return $this->createPaginationIterator($url, Repository::CLASS_NAME, ['type' => $type]);
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
     * @return integer
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string The login name of the Organization, e.g. "github"
     */
    public function getLogin()
    {
        return $this->getAttribute('login');
    }

    /**
     * @return string The name of the Organization, e.g. "GitHub"
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return string The public email adress of the Organization
     */
    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    /**
     * @return string The avatar url of the Organization, e.g. "https://github.com/images/error/octocat_happy.gif"
     */
    public function getAvatarUrl()
    {
        return $this->getAttribute('avatar_url');
    }

    /**
     * @return string The blog of the Organization, e.g. "https://github.com/blog"
     */
    public function getBlog()
    {
        return $this->getAttribute('blog');
    }

    /**
     * @return string The location of the Organization, e.g. "San Francisco"
     */
    public function getLocation()
    {
        return $this->getAttribute('location');
    }
}
