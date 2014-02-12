<?php


namespace MPScholten\GithubApi\Tests\Api\User;


use MPScholten\GithubApi\Api\User\User;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class UserTest extends AbstractTestCase
{
    private $fixture1;

    protected function setUp()
    {
        $this->fixture1 = $this->loadJsonFixture('fixture1.json');
    }

    public function testPopulateWithExampleData()
    {
        $user = new User();
        $user->populate($this->fixture1);

        $this->assertEquals('octocat', $user->getLogin());
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('https://github.com/images/error/octocat_happy.gif', $user->getAvatarUrl());
        $this->assertEquals('monalisa octocat', $user->getName());
    }
}
 