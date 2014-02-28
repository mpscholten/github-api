<?php


namespace MPScholten\GithubApi\Api\Organization;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\AbstractModelApi;
use MPScholten\GithubApi\Api\PopulateableInterface;

class Organization extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
    }

    public function getLogin()
    {
        return $this->getAttribute('login');
    }

    public function getName()
    {
        return $this->getAttribute('name');
    }

    public function getEmail()
    {
        return $this->getAttribute('email');
    }

    public function getId()
    {
        return $this->getAttribute('id');
    }

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

    public function getAvatarUrl()
    {
        return $this->getAttribute('avatar_url');
    }
}
