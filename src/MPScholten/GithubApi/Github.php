<?php


namespace MPScholten\GithubApi;


use Doctrine\Common\Cache\FilesystemCache;
use Guzzle\Cache\DoctrineCacheAdapter;
use Guzzle\Http\Client;
use Guzzle\Plugin\Cache\CachePlugin;
use MPScholten\GithubApi\Api\Search\Search;
use MPScholten\GithubApi\Api\User\CurrentUser;
use MPScholten\GithubApi\Auth\AuthenticationMethodInterface;
use MPScholten\GithubApi\Auth\OAuth;

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
     * @var AuthenticationMethodInterface|string $authenticationMethod If $authenticationMethod is a string,
     *                                                                 the string will be used as a token for
     *                                                                 the OAuth login
     * @var null|string|false $cachePath If $cachePath is null we will use in-memory caching, if it's a string we will
     *                                   use file caching. In case it's false we disable any caching
     *
     * @return Github
     */
    public static function create($authenticationMethod, $cachePath = null)
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
