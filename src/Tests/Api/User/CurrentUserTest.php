<?php


namespace MPScholten\GitHubApi\Tests\Api\User;



use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Api\User\CurrentUser;
use MPScholten\GitHubApi\Api\User\Email;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class CurrentUserTest extends AbstractTestCase
{
    public function testAutomaticallyPopulates()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_user.json')));

        $user = new CurrentUser($httpClient);
        $user->getId();
    }

    public function testLazyLoadingRepositories()
    {
        $httpClient = $this->createHttpClientMock();

        $user = new CurrentUser($httpClient);
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_repositories.json')));

        $repositories = $user->getRepositories();
        $this->assertCount(1, $repositories);

        foreach ($repositories as $repository) {
            $this->assertInstanceOf(Repository::CLASS_NAME, $repository);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRepositoryTypeOnGetRepositories()
    {
        $organization = new CurrentUser();
        $organization->getRepositories('this is invalid');
    }

    public function testLazyLoadingEmails()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_emails.json')));

        $user = new CurrentUser($httpClient);
        $emails = $user->getEmails();

        $this->assertCount(1, $emails);

        foreach ($emails as $email) {
            $this->assertInstanceOf(Email::CLASS_NAME, $email);
        }
    }

    public function testGetPrimaryEmail()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_emails.json')));

        $user = new CurrentUser($httpClient);

        $this->assertEquals('octocat@github.com', (string) $user->getPrimaryEmail());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetPrimaryEmailThrowsExceptionIfNotVerified()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode([
                ['email' => 'octocat@github.com', 'verified' => false, 'primary' => true],
            ]));

        $user = new CurrentUser($httpClient);
        $user->getPrimaryEmail();
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetPrimaryEmailThrowsExceptionIfNoPrimaryEmailGiven()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode([
                ['email' => 'octocat@github.com', 'verified' => false, 'primary' => false],
            ]));

        $user = new CurrentUser($httpClient);
        $user->getPrimaryEmail();
    }
}
