<?php

namespace MPScholten\GithubApi\Auth;

class NullAuthenticationMethod implements AuthenticationMethodInterface
{
    public static function getSubscribedEvents()
    {
        return [];
    }
}
