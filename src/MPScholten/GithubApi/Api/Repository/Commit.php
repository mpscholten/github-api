<?php

namespace MPScholten\GithubApi\Api\Repository;

use MPScholten\GithubApi\Api\AbstractModelApi;
use MPScholten\GithubApi\Api\User\User;

/**
 * @link http://developer.github.com/v3/repos/commits/
 */
class Commit extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    protected $committer;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @return string The commit message
     */
    public function getMessage()
    {
        return $this->getAttribute('commit')['message'];
    }

    /**
     * @return User
     */
    public function getCommitter()
    {
        if ($this->committer === null) {
            $this->committer = new User($this->client);
            $this->committer->populate($this->getAttribute('committer'));
        }

        return $this->committer;
    }
}
