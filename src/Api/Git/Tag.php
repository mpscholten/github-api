<?php

namespace MPScholten\GithubApi\Api\Git;

use MPScholten\GithubApi\Api\AbstractModelApi;

/**
 * @link http://developer.github.com/v3/git/tags/
 */
class Tag extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    /**
     * Fully loads the model from GitHub.
     */
    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    /**
     * @return string The tag name, e.g. "v.0.0.1"
     */
    public function getName()
    {
        return $this->getAttribute('tag');
    }

    /**
     * @return string
     */
    public function getSha()
    {
        return $this->getAttribute('sha');
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getAttribute('message');
    }
}
