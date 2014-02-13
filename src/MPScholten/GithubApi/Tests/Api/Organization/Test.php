<?php


namespace MPScholten\GithubApi\Tests\Api\Organization;


use MPScholten\GithubApi\Api\Organization\Organization;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class OrganizationTest extends AbstractTestCase
{
    private $fixture1;

    protected function setUp()
    {
        $this->fixture1 = $this->loadJsonFixture('fixture1.json');
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


}
 