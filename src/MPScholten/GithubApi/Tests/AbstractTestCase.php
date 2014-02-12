<?php


namespace MPScholten\GithubApi\Tests;


abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function loadJsonFixture($name)
    {
        $class = new \ReflectionClass(get_class($this));
        $path = dirname($class->getFileName()) . '/' . $name;

        return json_decode(file_get_contents($path), true);
    }
} 