<?php


namespace MPScholten\GithubApi\Tests\Api\User;


use Guzzle\Http\Message\Response;
use Guzzle\Http\QueryString;
use MPScholten\GithubApi\Api\User\User;
use MPScholten\GithubApi\Tests\AbstractTestCase;
use MPScholten\GithubApi\UrlType;

class UserTest extends AbstractTestCase
{
    private $fixture1;
    private $fixture2;

    protected function setUp()
    {
        $this->fixture1 = $this->loadJsonFixture('fixture1.json');
        $this->fixture2 = $this->loadJsonFixture('fixture2.json');
    }

    public function testPopulateWithExampleData()
    {
        $user = new User();
        $user->populate($this->fixture1);

        $this->assertEquals('octocat', $user->getLogin());
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('https://github.com/images/error/octocat_happy.gif', $user->getAvatarUrl());
        $this->assertEquals('monalisa octocat', $user->getName());
        $this->assertEquals('somehexcode', $user->getGravatarId());
        $this->assertEquals('https://api.github.com/users/octocat', $user->getUrl());
        $this->assertEquals('https://github.com/octocat', $user->getUrl(UrlType::HTML));
    }

    public function testLazyLoadingOrganizations()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->fixture2));

        $user = new User($httpClient);
        $user->populate($this->fixture1);


        $orgs = $user->getOrganizations();
        $this->assertCount(1, $orgs);
    }
}
 