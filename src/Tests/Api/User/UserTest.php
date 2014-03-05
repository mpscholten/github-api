<?php


namespace MPScholten\GitHubApi\Tests\Api\User;


use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Api\User\User;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class UserTest extends AbstractTestCase
{
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
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_organizations.json')));

        $user = new User($httpClient);
        $user->populate($this->loadJsonFixture('fixture_user.json'));


        $orgs = $user->getOrganizations();
        $this->assertCount(1, $orgs);
    }

    public function testLazyLoadingRepositories()
    {
        $httpClient = $this->createHttpClientMock();
        $responseBody = json_encode($this->loadJsonFixture('fixture_repositories.json'));
        $this->mockSimpleRequest($httpClient, 'get', $responseBody);


        $user = new User($httpClient);
        $user->populate($this->loadJsonFixture('fixture_user.json'));

        $repositories = $user->getRepositories();
        $this->assertInstanceOf('MPScholten\GitHubApi\Api\PaginationIterator', $repositories);
        $this->assertCount(1, $repositories);

        foreach ($repositories as $repository) {
            $this->assertInstanceOf(Repository::CLASS_NAME, $repository);
        }
    }

    public function testLazyLoadUserByLogin()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_user.json')));

        $user = new User($httpClient);
        $user->populate(['login' => 'octocat']);

        $this->assertEquals(1, $user->getId());
    }

    public function testLazyLoadUserByUrl()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_user.json')));

        $user = new User($httpClient);
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
}
