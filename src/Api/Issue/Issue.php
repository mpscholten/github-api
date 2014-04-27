<?php

namespace MPScholten\GitHubApi\Api\Issue;

use MPScholten\GitHubApi\Api\AbstractModelApi;
use MPScholten\GitHubApi\Api\User\User;

/**
 * @link https://developer.github.com/v3/issues/
 */
class Issue extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    private $user;
    private $closedBy;
    private $labels;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @return int The number of the issue, e.g. 1
     */
    public function getNumber()
    {
        return $this->getAttribute('number');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }

    /**
     * @return User The author of this issue
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = new User($this->client);
            $this->user->populate($this->getAttribute('user'));
        }

        return $this->user;
    }

    /**
     * @see getUser()
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->getUser();
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->getAttribute('state');
    }

    /**
     * @return boolean
     */
    public function isClosed()
    {
        return $this->getState() === 'closed';
    }

    /**
     * @return boolean
     */
    public function isOpen()
    {
        return $this->getState() === 'open';
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->getAttribute('body');
    }

    /**
     * @return User
     */
    public function getClosedBy()
    {
        if ($this->closedBy === null) {
            $this->closedBy = new User($this->client);
            $this->closedBy->populate($this->getAttribute('closed_by'));
        }

        return $this->closedBy;
    }

    /**
     * @return Label[]|\ArrayObject
     */
    public function getLabels()
    {
        if ($this->labels === null) {
            $labels = array_map(function ($data) {
                $label = new Label($this->client);
                $label->populate($data);
                return $label;
            }, $this->getAttribute('labels'));

            // use \ArrayObject instead of good old php arrays
            $this->labels = new \ArrayObject($labels);
        }

        return $this->labels;
    }
}
