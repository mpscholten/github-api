<?php

namespace MPScholten\GitHubApi\Auth;

class NullAuthenticationMethod implements AuthenticationMethodInterface
{
    public static function getSubscribedEvents()
    {
        return [];
    }
}
