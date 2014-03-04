<?php

namespace MPScholten\GithubApi\Tests\Api\Repository;

use MPScholten\GithubApi\Api\Git\Tag;
use MPScholten\GithubApi\Api\Repository\Release;
use MPScholten\GithubApi\Api\User\User;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class ReleaseTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $release = new Release();
        $release->populate($this->loadJsonFixture('fixture_release.json'));

        $this->assertEquals(1, $release->getId());
        $this->assertEquals('https://api.github.com/repos/octocat/Hello-World/releases/1', $release->getUrl('api'));
        $this->assertEquals('https://github.com/octocat/Hello-World/releases/v1.0.0', $release->getUrl());


        $expectedTarball = 'https://api.github.com/repos/octocat/Hello-World/tarball/v1.0.0';
        $expectedZipball = 'https://api.github.com/repos/octocat/Hello-World/zipball/v1.0.0';
        $this->assertEquals($expectedTarball, $release->getUrl('tarball'));
        $this->assertEquals($expectedZipball, $release->getUrl('zipball'));

        $this->assertEquals('v1.0.0', $release->getName());
        $this->assertInstanceOf(User::CLASS_NAME, $release->getAuthor());
        $this->assertEquals('Description of the release', $release->getBody());
        $this->assertEquals('Description of the release', $release->getDescription());
        $this->assertFalse($release->isPreRelease());

        $this->assertInstanceOf('\DateTime', $release->getCreatedAt());
        $this->assertInstanceOf('\DateTime', $release->getPublishedAt());

        $this->assertEquals(strtotime('2013-02-27T19:35:32Z'), $release->getCreatedAt()->getTimestamp());
        $this->assertEquals(strtotime('2013-02-27T19:35:32Z'), $release->getPublishedAt()->getTimestamp());

        $this->assertInstanceOf(Tag::CLASS_NAME, $release->getTag());
        $this->assertEquals('v1.0.0', $release->getTag()->getName());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetUrlThrowsExceptionIfInvalidType()
    {
        $release = new Release();
        $release->getUrl('invalid type');
    }

    public function testLazyLoading()
    {
        $incompleteData = $this->loadJsonFixture('fixture_release.json');
        unset($incompleteData['body']);
        $this->assertArrayNotHasKey('body', $incompleteData);

        $httpClient = $this->createHttpClientMock();
        $this->mockSimpleRequest($httpClient, 'get', json_encode($this->loadJsonFixture('fixture_release.json')));

        $release = new Release($httpClient);
        $release->populate($incompleteData);
        $this->assertEquals('Description of the release', $release->getBody());
    }
}
