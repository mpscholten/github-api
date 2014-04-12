<?php


namespace MPScholten\GitHubApi\Tests\Api\User;


use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Api\User\User;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class UserTest extends AbstractTestCase
{
    private $httpClient;

    protected function setUp()
    {
        $this->httpClient = $this->createHttpClientMock();
    }

    public function testPopulateWithExampleData()
    {
        $user = new User();
        $user->populate($this->loadJsonFixture('fixture_user.json'));

        $this->assertEquals('octocat', $user->getLogin());
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('https://github.com/images/error/octocat_happy.gif', $user->getAvatarUrl());
        $this->assertEquals('monalisa octocat', $user->getName());
        $this->assertEquals('somehexcode', $user->getGravatarId());
        $this->assertEquals('https://api.github.com/users/octocat', $user->getUrl('api'));
        $this->assertEquals('https://github.com/octocat', $user->getUrl());
        $this->assertEquals('octocat@github.com', $user->getEmail());
    }

    public function testLazyLoadingOrganizations()
    {
        $this->mockSimpleRequest($this->httpClient, 'get', json_encode($this->loadJsonFixture('fixture_organizations.json')));

        $user = new User($this->httpClient);
        $user->populate($this->loadJsonFixture('fixture_user.json'));


        $orgs = $user->getOrganizations();
        $this->assertCount(1, $orgs);
    }

    public function testLazyLoadingRepositories()
    {
        $responseBody = json_encode($this->loadJsonFixture('fixture_repositories.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $responseBody);


        $user = new User($this->httpClient);
        $user->populate($this->loadJsonFixture('fixture_user.json'));

        $repositories = $user->getRepositories();
        $this->assertInstanceOf('MPScholten\GitHubApi\Api\PaginationIterator', $repositories);
        $this->assertCount(1, $repositories);
        $this->assertContainsOnlyInstancesOf(Repository::CLASS_NAME, $repositories);
    }

    public function testLazyLoadUserByLogin()
    {
        $this->mockSimpleRequest($this->httpClient, 'get', json_encode($this->loadJsonFixture('fixture_user.json')));

        $user = new User($this->httpClient);
        $user->populate(['login' => 'octocat']);

        $this->assertEquals(1, $user->getId());
    }

    public function testLazyLoadUserByUrl()
    {
        $this->mockSimpleRequest($this->httpClient, 'get', json_encode($this->loadJsonFixture('fixture_user.json')));

        $user = new User($this->httpClient);
        $user->populate(['url' => 'https://api.github.com/users/octocat']);

        $this->assertEquals(1, $user->getId());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testLazyLoadingThrowsExceptionIfNotAbleToLoad()
    {
        $user = new User();
        $user->populate([]);
        $user->getEmail();
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRepositoryTypeOnGetRepositories()
    {
        $organization = new User();
        $organization->getRepositories('this is invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidUrlTypeThrowsException()
    {
        $organization = new User();
        $organization->getUrl('invalid type');
    }

    public function testLazyLoadFollowers()
    {
        $expectedResponse = json_encode($this->loadJsonFixture('fixture_followers.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $expectedResponse, 'https://api.github.com/users/octocat/followers');

        $user = new User($this->httpClient);
        $user->populate($this->loadJsonFixture('fixture_user.json'));

        $this->assertContainsOnlyInstancesOf(User::CLASS_NAME, $user->getFollowers());
    }
}
