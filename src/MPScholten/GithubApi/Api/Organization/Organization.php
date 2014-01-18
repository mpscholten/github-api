<?php


namespace MPScholten\GithubApi\Api\Organization;


use MPScholten\GithubApi\Api\AbstractApi;

class Organization extends AbstractApi
{
    // attributes
    private $id;
    private $login;
    private $name;
    private $email;

    // urls
    private $url;

    public function populate(array $data)
    {
        $this->id = $data['id'];
        $this->login = $data['login'];
        $this->url = $data['url'];

        // because if we call /users/{user}/orgs we only get the 3 attributes above, we need to populate the other attributes only if the name is given
        if(isset($data['name'])) {
            $this->name = $data['name'];
            $this->email = $data['email'];
        }
    }

    protected function load()
    {
        $this->populate($this->get($this->url));
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getName()
    {
        if($this->name === null) {
            $this->load();
        }

        return $this->name;
    }

    public function getEmail()
    {
        if($this->email === null) {
            $this->load();
        }

        return $this->email;
    }
}