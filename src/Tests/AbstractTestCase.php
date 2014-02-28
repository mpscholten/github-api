<?php

namespace MPScholten\GithubApi\Tests;

use Guzzle\Http\QueryString;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function loadJsonFixture($name)
    {
        $class = new \ReflectionClass(get_class($this));
        $path = dirname($class->getFileName()) . '/' . $name;

        return json_decode(file_get_contents($path), true);
    }

    protected function createHttpClientMock()
    {
        return $this->getMockBuilder('Guzzle\Http\ClientInterface')->getMock();
    }

    protected function createRequestMockBuilder()
    {
        return $this->getMockBuilder('Guzzle\Http\Message\RequestInterface');
    }

    protected function createResponseMockBuilder()
    {
        return $this->getMockBuilder('Guzzle\Http\Message\Response');
    }

    protected function mockSimpleRequest($httpClientMock, $method, $responseBody, $url = null)
    {
        $response = $this->createResponseMockBuilder()
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMock();
        $request = $this->createRequestMockBuilder()->getMock();


        $request->expects($this->any())->method('getQuery')->will($this->returnValue(new QueryString()));
        $request->expects($this->once())->method('send')->will($this->returnValue($response));

        $response->expects($this->any())->method('getBody')->will($this->returnValue($responseBody));

        if ($url === null) {
            $httpClientMock->expects($this->once())->method($method)->will($this->returnValue($request));
        } else {
            $httpClientMock->expects($this->once())->method($method)->with($url)->will($this->returnValue($request));
        }
    }
}
