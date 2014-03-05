<?php


namespace MPScholten\GitHubApi\Tests\Api\Repository;


use MPScholten\GitHubApi\Api\Repository\Branch;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class BranchTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $branch = new Branch();
        $branch->populate($this->loadJsonFixture('fixture_branch.json'));

        $this->assertEquals('master', $branch->getName());
        $this->assertInstanceOf('MPScholten\GitHubApi\Api\Repository\Commit', $branch->getLatestCommit());
    }
}
