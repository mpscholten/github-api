<?php


namespace MPScholten\GithubApi\Api;


use Guzzle\Http\Client;
use MPScholten\GithubApi\ResponseDecoder;

class AbstractApi
{
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client;
    }

    protected function get($url, $query = [])
    {
        $request = $this->client->get($url);
        $request->getQuery()->merge($query);

        $response = $request->send();
        return ResponseDecoder::decode($response);
    }

    protected function post($url, $payload = [])
    {
        $response = $this->client->post($url, null, json_encode($payload));
        return ResponseDecoder::decode($response);
    }

    protected function delete($url)
    {
        $response = $this->client->delete($url);
    }
}