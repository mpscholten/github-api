<?php


namespace MPScholten\GithubApi\Api;


use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use MPScholten\GithubApi\Exception\GithubException;
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

        $response = $this->sendRequest($request);
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

        $request = $this->client->post($url, null, json_encode($payload));
        $response = $this->sendRequest($request);

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

        $request = $this->client->delete($url);
        $this->sendRequest($request);
    }

    private function sendRequest(RequestInterface $request)
    {
        try {
            return $request->send();
        } catch (\Exception $e) {
            throw new GithubException('Unexpected response.', 0, $e);
        }
    }

    protected function createPaginationIterator($url, $class)
    {
        return new PaginationIterator(
            $this->client,
            $this->client->get($url),
            function ($response, $client) use ($class) {
                $models = [];
                foreach ($response as $data) {
                    $model = new $class($this->client);
                    $model->populate($data);
                    $models[] = $model;
                }

                return $models;
            }
        );
    }
}
