<?php


namespace MPScholten\GithubApi\Tests\Api\Search;


use MPScholten\GithubApi\Api\Repository\Repository;
use MPScholten\GithubApi\Api\Search\Search;
use MPScholten\GithubApi\Tests\AbstractTestCase;

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
