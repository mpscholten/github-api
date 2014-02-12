<?php


namespace MPScholten\GithubApi\Tests\Api\Repository;


use MPScholten\GithubApi\Api\Repository\Repository;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class RepositoryTest extends AbstractTestCase
{
    private $fixture1;

    protected function setUp()
    {
        $this->fixture1 = $this->loadJsonFixture('fixture1.json');
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

        $this->assertInstanceOf('MPScholten\GithubApi\Api\User\User', $repository->getOwner());
        $this->assertEquals('octocat', $repository->getOwner()->getLogin());
    }

}
 