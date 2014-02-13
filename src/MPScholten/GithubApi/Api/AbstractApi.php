<?php


namespace MPScholten\GithubApi\Api;


use Guzzle\Http\ClientInterface;
use MPScholten\GithubApi\ResponseDecoder;

class AbstractApi
{
    protected $client;

    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client;
    }

    protected function get($url, $query = [])
    {
        if (!$this->client) {
            throw new \RuntimeException(vsprintf(
                'Did you forget to pass the http-client in the constructor of %s?',
                get_class($this)
            ));
        }

        $request = $this->client->get($url);
        $request->getQuery()->merge($query);

        $response = $request->send();
        return ResponseDecoder::decode($response);
    }

    protected function post($url, $payload = [])
    {
        if (!$this->client) {
            throw new \RuntimeException(vsprintf(
                'Did you forget to pass the http-client in the constructor of %s?',
                get_class($this)
            ));
        }

        $response = $this->client->post($url, null, json_encode($payload))->send();
        return ResponseDecoder::decode($response);
    }

    protected function delete($url)
    {
        if (!$this->client) {
            throw new \RuntimeException(vsprintf(
                'Did you forget to pass the http-client in the constructor of %s?',
                get_class($this)
            ));
        }

        $response = $this->client->delete($url);
    }
}
