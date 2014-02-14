<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;

class Branch extends AbstractApi
{
    // attributes
    private $name;

    // relations
    private $latestCommit;

    // urls
    private $url;
    private $htmlUrl;

    public function populate($data)
    {
        $this->name = $data['name'];

        $this->latestCommit = new Commit();
        $this->latestCommit->populate($data['commit']);

        $this->url = $data['_links']['self'];
        $this->htmlUrl = $data['_links']['html'];
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
