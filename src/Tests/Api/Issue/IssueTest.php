<?php


namespace MPScholten\GitHubApi\Tests\Api\Issue;


use MPScholten\GitHubApi\Api\Issue\Issue;
use MPScholten\GitHubApi\Api\Issue\Label;
use MPScholten\GitHubApi\Api\User\User;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class IssueTest extends AbstractTestCase
{
    private $httpClient;

    protected function setUp()
    {
        $this->httpClient = $this->createHttpClientMock();
    }

    public function testPopulateFixture1()
    {
        $issue = new Issue();
        $issue->populate($this->loadJsonFixture('fixture_issue1.json'));

        $this->assertEquals(1, $issue->getNumber());
        $this->assertEquals('Fixed repository URL', $issue->getTitle());
        $this->assertInstanceOf(User::CLASS_NAME, $issue->getUser());
        $this->assertTrue($issue->isClosed());
        $this->assertFalse($issue->isOpen());
        $this->assertEquals('closed', $issue->getState());
        $this->assertEquals('', $issue->getBody());
        $this->assertInstanceOf(User::CLASS_NAME, $issue->getClosedBy());
    }

    public function testPopulateFixture2()
    {
        $issue = new Issue();
        $issue->populate($this->loadJsonFixture('fixture_issue2.json'));

        $this->assertEquals(2, $issue->getNumber());
        $this->assertEquals('Add Issue-API', $issue->getTitle());
        $this->assertInstanceOf(User::CLASS_NAME, $issue->getUser());
        $this->assertContainsOnlyInstancesOf(Label::CLASS_NAME, $issue->getLabels());
        $this->assertEquals(1, $issue->getLabels()->count());
    }
}
