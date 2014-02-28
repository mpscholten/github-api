<?php


namespace MPScholten\GithubApi\Tests\Api\Repository;


use MPScholten\GithubApi\Api\Repository\Key;
use MPScholten\GithubApi\Tests\AbstractTestCase;

class KeyTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $key = new Key();
        $key->populate($this->loadJsonFixture('fixture_key.json'));

        $this->assertEquals(1, $key->getId());
        $this->assertEquals('ssh-rsa AAA...', $key->getKey());
        $this->assertEquals('octocat@octomac', $key->getTitle());
    }

    public function testSetter()
    {
        $key = new Key();
        $key->populate($this->loadJsonFixture('fixture_key.json'));

        $key->setKey('hello world');
        $key->setTitle('my first key');

        $this->assertEquals('hello world', $key->getKey());
        $this->assertEquals('my first key', $key->getTitle());
    }
}
