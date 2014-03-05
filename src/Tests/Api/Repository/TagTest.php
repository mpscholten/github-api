<?php


namespace MPScholten\GitHubApi\Tests\Api\Repository;


use MPScholten\GitHubApi\Api\Git\Tag;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class TagTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $tag = new Tag();
        $tag->populate($this->loadJsonFixture('fixture_tag.json'));

        $this->assertEquals('v0.0.1', $tag->getName());
        $this->assertEquals('940bd336248efae0f9ee5bc7b2d5c985887b16ac', $tag->getSha());
        $this->assertEquals("initial version\n", $tag->getMessage());
    }

    public function testLazyLoading()
    {
        $fixture = $this->loadJsonFixture('fixture_tag.json');

        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($fixture), $fixture['url']);

        $tag = new Tag($httpClient);
        $tag->populate(['url' => $fixture['url']]);

        $this->assertEquals('940bd336248efae0f9ee5bc7b2d5c985887b16ac', $tag->getSha());
    }
}
