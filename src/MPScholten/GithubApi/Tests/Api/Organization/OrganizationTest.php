<?php


namespace MPScholten\GithubApi\Tests\Api\Organization;


use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class OrganizationTest extends AbstractTestCase
{
    private $fixture1;
    private $fixture2;

    protected function setUp()
    {
        $this->fixture1 = $this->loadJsonFixture('fixture1.json');
        $this->fixture2 = $this->loadJsonFixture('fixture2.json');
    }

    public function testPopulateWithSampleData()
    {
        $organization = new Organization();
        $organization->populate($this->fixture1);

        $this->assertEquals('github', $organization->getLogin());
        $this->assertEquals(1, $organization->getId());
        $this->assertEquals('https://api.github.com/orgs/github', $organization->getUrl('api'));
        $this->assertEquals('https://github.com/images/error/octocat_happy.gif', $organization->getAvatarUrl());
    }

    private function setupLazyLoadingTest()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->fixture2));

        $organization = new Organization($httpClient);
        $organization->populate($this->fixture1);

        return $organization;
    }

    public function testLazyLoadingEmail()
    {
        $organization = $this->setupLazyLoadingTest();
        $this->assertEquals('octocat@github.com', $organization->getEmail());
    }

    public function testLazyLoadingHtmlUrl()
    {
        $organization = $this->setupLazyLoadingTest();
        $this->assertEquals('https://github.com/octocat', $organization->getUrl('html'));
    }

    public function testLazyLoadingName()
    {
        $organization = $this->setupLazyLoadingTest();
        $this->assertEquals('github', $organization->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidUrlTypeThrowsException()
    {
        $organization = new Organization();
        $organization->populate($this->fixture2);
        $organization->getUrl('invalid type');
    }


}
 