<?php

namespace MPScholten\GitHubApi\Api\Repository;

use MPScholten\GitHubApi\Api\AbstractApi;
use MPScholten\GitHubApi\Api\PopulateableInterface;

/**
 * @link http://developer.github.com/v3/repos/keys/
 */
class Key extends AbstractApi implements PopulateableInterface
{
    const CLASS_NAME = __CLASS__;

    private $id;
    private $key;
    private $title;

    public function populate(array $data)
    {
        $this->id = $data['id'];
        $this->key = $data['key'];
        $this->title = $data['title'];
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string The public key, e.g. "ssh-rsa AAA..."
     */
    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string The name of the key
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
}
