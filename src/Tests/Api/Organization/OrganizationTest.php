<?php


namespace MPScholten\GitHubApi\Tests\Api\Organization;


use MPScholten\GitHubApi\Api\Organization\Organization;
use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class OrganizationTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $organization = new Organization();
        $organization->populate($this->loadJsonFixture('fixture_organization_full.json'));

        $this->assertEquals('github', $organization->getLogin());
        $this->assertEquals(1, $organization->getId());
        $this->assertEquals('https://api.github.com/orgs/github', $organization->getUrl('api'));
        $this->assertEquals('https://github.com/images/error/octocat_happy.gif', $organization->getAvatarUrl());
        $this->assertEquals('https://github.com/blog', $organization->getBlog());
        $this->assertEquals('San Francisco', $organization->getLocation());
    }

    private function setupLazyLoadingTest()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_organization_full.json')));

        $organization = new Organization($httpClient);
        $organization->populate($this->loadJsonFixture('fixture_organization.json'));

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

    public function testLazyLoadingRepositories()
    {
        $httpClient = $this->createHttpClientMock();
        $responseBody = json_encode($this->loadJsonFixture('fixture_repositories.json'));
        $this->mockSimpleRequest($httpClient, 'get', $responseBody);


        $organization = new Organization($httpClient);
        $organization->populate($this->loadJsonFixture('fixture_organization_full.json'));

        $repositories = $organization->getRepositories();
        $this->assertCount(1, $repositories);
        $this->assertInstanceOf('MPScholten\GitHubApi\Api\PaginationIterator', $repositories);

        foreach ($repositories as $repository) {
            $this->assertInstanceOf(Repository::CLASS_NAME, $repository);
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRepositoryTypeOnGetRepositories()
    {
        $organization = new Organization();
        $organization->getRepositories('this is invalid');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidUrlTypeThrowsException()
    {
        $organization = new Organization();
        $organization->populate($this->loadJsonFixture('fixture_organization_full.json'));
        $organization->getUrl('invalid type');
    }
}
