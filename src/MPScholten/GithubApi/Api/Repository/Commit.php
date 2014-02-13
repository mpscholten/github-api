<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\User\User;

/**
 * @link http://developer.github.com/v3/repos/commits/
 */
class Commit extends AbstractApi
{
    // relations
    protected $committer;

    // attributes
    private $message;
    private $sha;

    public function populate(array $data)
    {
        $this->sha = $data['sha'];
        $this->message = $data['commit']['message'];

        $this->committer = new User($this->client);
        $this->committer->populate($data['committer']);
    }

    /**
     * @return string The commit message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return User
     */
    public function getCommitter()
    {
        return $this->committer;
    }
}