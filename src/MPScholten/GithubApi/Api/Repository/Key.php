<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;

class Key extends AbstractApi
{
    private $id;
    private $key;
    private $title;

    public function populate($data)
    {
        $this->id = $data['id'];
        $this->key = $data['key'];
        $this->title = $data['title'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

} 