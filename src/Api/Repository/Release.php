<?php

namespace MPScholten\GitHubApi\Api\Repository;

use DateTime;
use MPScholten\GitHubApi\Api\AbstractModelApi;
use MPScholten\GitHubApi\Api\Git\Tag;
use MPScholten\GitHubApi\Api\User\User;

/**
 * @link http://developer.github.com/v3/repos/releases/
 */
class Release extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    private $author;
    private $tag;

    /**
     * Fully loads the model from GitHub.
     */
    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $type Can be 'html', 'api', 'tarball', or 'zipball'
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
            case 'tarball':
                return $this->getAttribute('tarball_url');
            case 'zipball':
                return $this->getAttribute('zipball_url');
        }

        throw new \InvalidArgumentException(sprintf(
            'Invalid url type "%s", expected one of "%s"',
            $type,
            implode(', ', ['html', 'api', 'tarball', 'zipball'])
        ));
    }

    /**
     * @return string The name of this release, e.g. "First working version"
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return string The description of the release
     */
    public function getBody()
    {
        return $this->getAttribute('body');
    }

    /**
     * @see getBody()
     */
    public function getDescription()
    {
        return $this->getBody();
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        if ($this->author === null) {
            $this->author = new User($this->client);
            $this->author->populate($this->getAttribute('author'));
        }

        return $this->author;
    }

    /**
     * @return boolean
     */
    public function isPreRelease()
    {
        return $this->getAttribute('prerelease');
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return new DateTime($this->getAttribute('created_at'));
    }

    /**
     * @return DateTime
     */
    public function getPublishedAt()
    {
        return new DateTime($this->getAttribute('published_at'));
    }

    /**
     * @return Tag
     */
    public function getTag()
    {
        if ($this->tag === null) {
            $this->tag = new Tag($this->client);
            $this->tag->populate(['tag' => $this->getAttribute('tag_name')]);
        }

        return $this->tag;
    }
}
