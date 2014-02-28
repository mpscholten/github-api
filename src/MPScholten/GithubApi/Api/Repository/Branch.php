<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\PopulateableInterface;

class Branch extends AbstractApi implements PopulateableInterface
{
    const CLASS_NAME = __CLASS__;

    private $name;
    private $latestCommit;

    public function populate(array $data)
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
