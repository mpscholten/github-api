<?php


namespace MPScholten\GithubApi;


use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Http\Client;
use Guzzle\Plugin\Cache\CachePlugin;
use MPScholten\GithubApi\Api\Search\Search;
use MPScholten\GithubApi\Api\User\CurrentUser;
use MPScholten\GithubApi\Auth\AuthenticationMethodInterface;

class Github
{
    private $client;

    public function __construct(Client $client, AuthenticationMethodInterface $authenticationMethod)
    {
        $this->client = $client;
        $this->client->addSubscriber($authenticationMethod);
        $this->client->setBaseUrl('https://api.github.com/');
    }

    public static function create($authenticationMethod, $cachePath = null)
    {
        $client = new Client();

        if ($cachePath === null) {
            $cachePlugin = new CachePlugin();
            $client->addSubscriber($cachePlugin);
        } elseif(is_string($cachePath)) {
            $cachePlugin = new CachePlugin(new DoctrineCacheAdapter(new FilesystemCache($cachePath)));
            $client->addSubscriber($cachePlugin);
        } elseif($cachePath === false) {
            // disable cache if false
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
}