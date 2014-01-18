<?php


namespace MPScholten\GithubApi\Api\User;


use  Guzzle\Http\Client;
use MPScholten\GithubApi\Api\Repository\Repository;

class CurrentUser extends User
{
    private $repositories = [];

    public function __construct(Client $client)
    {
        parent::__construct($client);
        $this->load();
    }

    protected function load()
    {
        $this->populate($this->get('user'));
    }

    /**
     * @param string $type
     * @return Repository[]
     */
    public function getRepositories($type = 'all')
    {
        if (!isset($this->repositories[$type])) {
            $this->repositories[$type] = $this->loadRepositories($type);
        }

        return $this->repositories[$type];
    }

    protected function loadRepositories()
    {
        $repositories = [];
        foreach ($this->get('user/repos') as $data) {
            $repository = new Repository($this->client);
            $repository->populate($data);

            $repositories[] = $repository;
        }

        return $repositories;
    }
}