<?php


namespace MPScholten\GithubApi\Api\Organization;

use MPScholten\GithubApi\Api\AbstractModelApi;

class Organization extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    protected function load()
    {
        $url = $this->getAttribute('url');
        $this->populate($this->get($url));
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
     * @return integer
     */
    public function getId()
    {
        return $this->getAttribute('id');
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
     * @return string The avatar url of the Organization, e.g. "https://github.com/images/error/octocat_happy.gif"
     */
    public function getAvatarUrl()
    {
        return $this->getAttribute('avatar_url');
    }
}
