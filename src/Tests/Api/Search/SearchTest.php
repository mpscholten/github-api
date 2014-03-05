<?php


namespace MPScholten\GitHubApi\Tests\Api\Search;


use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Api\Search\Search;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class SearchTest extends AbstractTestCase
{
    public function testFindRepositories()
    {
        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_repositories.json')));

        $search = new Search($httpClient);
        $result = $search->findRepositories('hello world');

        $this->assertCount(1, $result);

        foreach ($result as $repo) {
            $this->assertInstanceOf(Repository::CLASS_NAME, $repo);
        }

    }
}
