<?php


namespace MPScholten\GithubApi;


use Guzzle\Http\Message\Response;

class ResponseDecoder {
    public static function decode(Response $response)
    {
        return json_decode($response->getBody(true), true);
    }
} 