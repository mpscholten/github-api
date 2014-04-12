<?php


namespace MPScholten\GitHubApi\Tests\Api\Repository;


use MPScholten\GitHubApi\Api\Repository\Branch;
use MPScholten\GitHubApi\Api\Repository\Key;
use MPScholten\GitHubApi\Api\Repository\Release;
use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class RepositoryTest extends AbstractTestCase
{
    private $httpClient;

    protected function setUp()
    {
        $this->httpClient = $this->createHttpClientMock();
    }


    public function testPopulateWithExampleData()
    {
        $repository = new Repository();
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        $this->assertEquals(1296269, $repository->getId());
        $this->assertEquals('Hello-World', $repository->getName());
        $this->assertFalse($repository->isPrivate());
        $this->assertFalse($repository->isFork());
        $this->assertEquals('This your first repo!', $repository->getDescription());
        $this->assertEquals('git@github.com:octocat/Hello-World.git', $repository->getSshUrl());
        $this->assertEquals('octocat/Hello-World', $repository->getFullName());
        $this->assertEquals('master', $repository->getDefaultBranch());

        $this->assertInstanceOf('MPScholten\GitHubApi\Api\User\User', $repository->getOwner());
        $this->assertEquals('octocat', $repository->getOwner()->getLogin());
    }

    public function testLazyLoadingCommits()
    {
        $expectedResponse = json_encode($this->loadJsonFixture('fixture2.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $expectedResponse, 'https://api.github.com/repos/octocat/Hello-World/commits');

        $repository = new Repository($this->httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        foreach ($repository->getCommits() as $commit) {
            $this->assertInstanceOf('MPScholten\GitHubApi\Api\Repository\Commit', $commit);
        }
    }

    public function testLazyLoadingCollaborators()
    {
        $expectedResponse = json_encode($this->loadJsonFixture('fixture3.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $expectedResponse, 'https://api.github.com/repos/octocat/Hello-World/collaborators');

        $repository = new Repository($this->httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        foreach ($repository->getCollaborators() as $collaborator) {
            $this->assertInstanceOf('MPScholten\GitHubApi\Api\User\User', $collaborator);
        }
    }

    public function testLazyLoadingBranches()
    {
        $expectedResponse = json_encode($this->loadJsonFixture('fixture_branches.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $expectedResponse, 'https://api.github.com/repos/octocat/Hello-World/branches');

        $repository = new Repository($this->httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        foreach ($repository->getBranches() as $branch) {
            $this->assertInstanceOf(Branch::CLASS_NAME, $branch);
        }
    }

    public function testLazyLoadingKeys()
    {
        $expectedResponse = json_encode($this->loadJsonFixture('fixture4.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $expectedResponse, 'https://api.github.com/repos/octocat/Hello-World/keys');

        $repository = new Repository($this->httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        foreach ($repository->getKeys() as $key) {
            $this->assertInstanceOf('MPScholten\GitHubApi\Api\Repository\Key', $key);
        }

        $this->assertEquals(
            $repository->getKeys(),
            $repository->getDeployKeys(),
            'getDeployKeys should return the same as getKeys'
        );
    }

    public function testLazyLoadingReleases()
    {
        $expectedResponse = json_encode($this->loadJsonFixture('fixture_releases.json'));
        $this->mockSimpleRequest($this->httpClient, 'get', $expectedResponse, 'https://api.github.com/repos/octocat/Hello-World/releases');

        $repository = new Repository($this->httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        foreach ($repository->getReleases() as $release) {
            $this->assertInstanceOf(Release::CLASS_NAME, $release);
        }
    }

    public function testAddKey()
    {
        $this->mockSimpleRequest($this->httpClient, 'post', json_encode($this->loadJsonFixture('fixture_key.json')));

        $repository = new Repository($this->httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        $key = new Key();
        $key->setTitle('hello word');
        $key->setKey('123');

        $this->assertNull($key->getId());
        $repository->addKey($key);
        $this->assertEquals(1, $key->getId());
    }
}
