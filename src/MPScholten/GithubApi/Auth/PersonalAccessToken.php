<?php


namespace MPScholten\GithubApi\Auth;


use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersonalAccessToken implements AuthenticationMethodInterface
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
        $request = $event['request'];
        $request->setAuth($this->accessToken);
    }
}