<?php


namespace MPScholten\GitHubApi\Tests\Api\Repository;


use MPScholten\GitHubApi\Api\Repository\Commit;
use MPScholten\GitHubApi\Api\User\User;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class CommitTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $commit = new Commit();
        $commit->populate($this->loadJsonFixture('fixture5.json'));

        $this->assertEquals('Fix all the bugs', $commit->getMessage());
        $this->assertInstanceOf('MPScholten\GitHubApi\Api\User\User', $commit->getCommitter());
    }

    public function testCommitWillLazyLoadInformation()
    {
        $data = $this->loadJsonFixture('fixture5.json');
        unset($data['committer']);

        $this->assertArrayNotHasKey('committer', $data);

        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'GET', json_encode($this->loadJsonFixture('fixture5.json')));

        $commit = new Commit($httpClient);
        $commit->populate($data);

        $this->assertInstanceOf(User::CLASS_NAME, $commit->getCommitter());

    }
}
