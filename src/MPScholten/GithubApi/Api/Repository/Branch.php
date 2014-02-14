<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;

class Branch extends AbstractApi
{
    const CLASS_NAME = __CLASS__;

    // attributes
    private $name;

    // relations
    private $latestCommit;

    public function populate($data)
    {
        $this->name = $data['name'];

        $this->latestCommit = new Commit();
        $this->latestCommit->populate($data['commit']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Commit
     */
    public function getLatestCommit()
    {
        return $this->latestCommit;
    }
}
