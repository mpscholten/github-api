<?php

namespace MPScholten\GithubApi;

use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Http\Client;
use Guzzle\Plugin\Cache\CachePlugin;
use MPScholten\GithubApi\Api\Repository\Repository;
use MPScholten\GithubApi\Api\Search\Search;
use MPScholten\GithubApi\Api\User\CurrentUser;
use MPScholten\GithubApi\Api\User\User;
use MPScholten\GithubApi\Auth\AuthenticationMethodInterface;
use MPScholten\GithubApi\Auth\NullAuthenticationMethod;
use MPScholten\GithubApi\Auth\OAuth;
use MPScholten\GithubApi\Exception\GithubException;

class Github
{
    private $client;

    public function __construct(Client $client, AuthenticationMethodInterface $authenticationMethod)
    {
        $this->client = $client;
        $this->client->addSubscriber($authenticationMethod);
        $this->client->setBaseUrl('https://api.github.com/');
    }

    /**
     * This is a easy-to-use facade for using this class. In case you need more customization just create the instace
     * via the constructor.
     *
     * @var AuthenticationMethodInterface|string|null $authenticationMethod If $authenticationMethod is a string,
     *                                                                 the string will be used as a token for
     *                                                                 the OAuth login. If null no authentication will
     *                                                                 be used.
     *
     * @var null|string|false $cachePath If $cachePath is null we will use in-memory caching, if it's a string we will
     *                                   use file caching. In case it's false we disable any caching
     *
     * @return Github
     */
    public static function create($authenticationMethod = null, $cachePath = null)
    {
        $client = new Client();

        if ($cachePath === null) {
            $cachePlugin = new CachePlugin();
            $client->addSubscriber($cachePlugin);
        } elseif (is_string($cachePath)) {
            $cachePlugin = new CachePlugin(new DoctrineCacheAdapter(new FilesystemCache($cachePath)));
            $client->addSubscriber($cachePlugin);
        } elseif ($cachePath === false) {
            // disable cache if false
        }

        if (is_string($authenticationMethod)) {
            $authenticationMethod = new OAuth($authenticationMethod);
        } elseif ($authenticationMethod === null) {
            $authenticationMethod = new NullAuthenticationMethod();
        }

        return new Github($client, $authenticationMethod);
    }

    public function getCurrentUser()
    {
        $api = new CurrentUser($this->client);
        return $api;
    }

    public function getSearch()
    {
        $api = new Search($this->client);
        return $api;
    }

    public function getUser($login)
    {
        $user = new User($this->client);
        $user->populate(['login' => $login]);

        try {
            $user->getId();
        } catch (GithubException $e) {
            throw new GithubException(sprintf('User %s was not found.', $login), 0, $e);
        }

        return $user;
    }

    public function getRepository($owner, $name)
    {
        $repository = new Repository($this->client);
        $repository->populate(['owner' => ['login' => $owner], 'name' => $name]);

        try {
            $repository->getId();
        } catch (GithubException $e) {
            throw new GithubException(sprintf('Repository %s was not found.', $name), 0, $e);
        }

        return $repository;
    }
}
