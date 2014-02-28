<?php

namespace MPScholten\GithubApi\Api;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use MPScholten\GithubApi\ResponseDecoder;

class PaginationIterator implements \Iterator
{
    private $position = 0;
    private $storage = [];

    private $client;
    private $transformer;

    private $nextRequest;

    public function __construct(ClientInterface $client, RequestInterface $request, callable $transformer)
    {
        $this->client = $client;
        $this->transformer = $transformer;
        $this->nextRequest = $request;
    }


    public function current()
    {
        return $this->storage[$this->position];
    }

    public function next()
    {
        $this->position++;

        if (!isset($this->storage[$this->position])) {
            $this->loadNext();
        }
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->storage[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;

        if (count($this->storage) === 0) {
            $this->loadNext();
        }
    }

    public function loadNext()
    {
        if (!$this->nextRequest instanceof RequestInterface) {
            return;
        }

        $response = $this->nextRequest->send();
        foreach (call_user_func($this->transformer, ResponseDecoder::decode($response), $this->client) as $data) {
            $this->storage[] = $data;
        }

        $linkHeader = $response->getHeader('Link');

        if ($linkHeader) {
            $this->nextRequest = $this->client->get($response->getHeader('Link')->getLink('next')['url']);
        } else {
            $this->nextRequest = null;
        }
    }
}
