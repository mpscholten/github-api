<?php

namespace MPScholten\GitHubApi\Auth;

use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;

class OAuth implements AuthenticationMethodInterface
{
    private $accessToken;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public static function getSubscribedEvents()
    {
        return ['request.before_send' => ['onRequestBeforeSend', -1000]];
    }

    public function onRequestBeforeSend(Event $event)
    {
        /** @var $request RequestInterface */
        $request = $event['request'];
        $request->getQuery()->add('access_token', $this->accessToken);
    }
}
