<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\User\User;

/**
 * @link http://developer.github.com/v3/repos/commits/
 */
class Commit extends AbstractApi
{
    const CLASS_NAME = __CLASS__;

    // relations
    protected $committer;

    // attributes
    private $message;
    private $sha;

    // urls
    private $url;

    public function populate(array $data)
    {
        $this->sha = $data['sha'];
        $this->url = $data['url'];

        $this->message = isset($data['commit']['message']) ? $data['commit']['message'] : null;

        if (isset($data['committer'])) {
            $this->committer = new User($this->client);
            $this->committer->populate($data['committer']);
        } else {
            $this->committer = null;
        }
    }

    private function load()
    {
        $this->populate($this->get($this->url));
    }

    /**
     * @return string The commit message
     */
    public function getMessage()
    {
        if ($this->message === null) {
            $this->load();
        }

        return $this->message;
    }

    /**
     * @return User
     */
    public function getCommitter()
    {
        if ($this->committer === null) {
            $this->load();
        }

        return $this->committer;
    }
}
