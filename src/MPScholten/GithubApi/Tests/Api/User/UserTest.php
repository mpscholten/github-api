<?php


namespace MPScholten\GithubApi\Tests\Api\User;


use MPScholten\GithubApi\Api\User\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private $fixture1;

    protected function setUp()
    {
        $this->fixture1 = json_decode(file_get_contents(__DIR__ . '/fixture1.json'), true);
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
 