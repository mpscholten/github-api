<?php


namespace MPScholten\GithubApi\Tests\Auth;


use Guzzle\Common\Event;
use Guzzle\Http\Message\Request;
use MPScholten\GithubApi\Auth\OAuth;

class OAuthTest extends \PHPUnit_Framework_TestCase
{
    public function testOnRequestBeforeSend()
    {
        $request = new Request('GET', 'https://example.com');
        $event = new Event(['request' => $request]);

        $oauth = new OAuth('example');
        $oauth->onRequestBeforeSend($event);

        $this->assertTrue($request->getQuery()->hasKey('access_token'));
        $this->assertEquals('example', $request->getQuery()->get('access_token'));
    }
}
