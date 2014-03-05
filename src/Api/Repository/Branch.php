<?php

namespace MPScholten\GitHubApi\Api\Repository;

use MPScholten\GitHubApi\Api\AbstractApi;
use MPScholten\GitHubApi\Api\PopulateableInterface;

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
