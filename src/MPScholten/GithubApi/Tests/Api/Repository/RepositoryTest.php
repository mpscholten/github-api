<?php


namespace MPScholten\GithubApi\Tests\Api\Repository;


use MPScholten\GithubApi\Api\Repository\Branch;
use MPScholten\GithubApi\Api\Repository\Key;
use MPScholten\GithubApi\Api\Repository\Repository;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class RepositoryTest extends AbstractTestCase
{
    private $fixture1;
    private $fixture2;
    private $fixture3;
    private $fixture4;

    protected function setUp()
    {
        $this->fixture1 = $this->loadJsonFixture('fixture_repository.json');
        $this->fixture2 = $this->loadJsonFixture('fixture2.json');
        $this->fixture3 = $this->loadJsonFixture('fixture3.json');
        $this->fixture4 = $this->loadJsonFixture('fixture4.json');
    }

    public function testPopulateWithExampleData()
    {
        $repository = new Repository();
        $repository->populate($this->fixture1);

        $this->assertEquals(1296269, $repository->getId());
        $this->assertEquals('Hello-World', $repository->getName());
        $this->assertFalse($repository->isPrivate());
        $this->assertFalse($repository->isFork());
        $this->assertEquals('This your first repo!', $repository->getDescription());
        $this->assertEquals('git@github.com:octocat/Hello-World.git', $repository->getSshUrl());
        $this->assertEquals('octocat/Hello-World', $repository->getFullName());
        $this->assertEquals('master', $repository->getDefaultBranch());

        $this->assertInstanceOf('MPScholten\GithubApi\Api\User\User', $repository->getOwner());
        $this->assertEquals('octocat', $repository->getOwner()->getLogin());
    }

    public function testLazyLoadingCommits()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->fixture2));

        $repository = new Repository($httpClient);
        $repository->populate($this->fixture1);

        foreach ($repository->getCommits() as $commit) {
            $this->assertInstanceOf('MPScholten\GithubApi\Api\Repository\Commit', $commit);
        }
    }

    public function testLazyLoadingCollaborators()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->fixture3));

        $repository = new Repository($httpClient);
        $repository->populate($this->fixture1);

        foreach ($repository->getCollaborators() as $collaborator) {
            $this->assertInstanceOf('MPScholten\GithubApi\Api\User\User', $collaborator);
        }
    }

    public function testLazyLoadingBranches()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_branches.json')));

        $repository = new Repository($httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        foreach ($repository->getBranches() as $branch) {
            $this->assertInstanceOf(Branch::CLASS_NAME, $branch);
        }
    }

    public function testLazyLoadingKeys()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->fixture4));

        $repository = new Repository($httpClient);
        $repository->populate($this->fixture1);

        foreach ($repository->getKeys() as $key) {
            $this->assertInstanceOf('MPScholten\GithubApi\Api\Repository\Key', $key);
        }

        $this->assertEquals(
            $repository->getKeys(),
            $repository->getDeployKeys(),
            'getDeployKeys should return the same as getKeys'
        );
    }

    public function testAddKey()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'post', json_encode($this->loadJsonFixture('fixture_key.json')));

        $repository = new Repository($httpClient);
        $repository->populate($this->loadJsonFixture('fixture_repository.json'));

        $key = new Key();
        $key->setTitle('hello word');
        $key->setKey('123');

        $this->assertNull($key->getId());
        $repository->addKey($key);
        $this->assertEquals(1, $key->getId());
    }
}
