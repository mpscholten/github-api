<?php


namespace MPScholten\GithubApi\Tests\Api\Repository;


use MPScholten\GithubApi\Api\Repository\Branch;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class BranchTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $branch = new Branch();
        $branch->populate($this->loadJsonFixture('fixture_branch.json'));

        $this->assertEquals('master', $branch->getName());
        $this->assertInstanceOf('MPScholten\GithubApi\Api\Repository\Commit', $branch->getLatestCommit());
    }
}
