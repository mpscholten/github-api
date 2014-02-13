<?php


namespace MPScholten\GithubApi\Tests\Api\Repository;


use MPScholten\GithubApi\Api\Repository\Commit;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class CommitTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $commit = new Commit();
        $commit->populate($this->loadJsonFixture('fixture5.json'));

        $this->assertEquals('Fix all the bugs', $commit->getMessage());
        $this->assertInstanceOf('MPScholten\GithubApi\Api\User\User', $commit->getCommitter());
    }
}
 