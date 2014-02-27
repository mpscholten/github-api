<?php


namespace MPScholten\GithubApi\Tests;


use MPScholten\GithubApi\Utils;

class UtilsTest extends \PHPUnit_Framework_TestCase {
    public function flatArrayKeyExistsProvider()
    {
        return [
            [['hello' => 'world'], 'hello', true],
            [['hello' => ['world' => 'test']], 'hello.world', true],
        ];
    }

    /**
     * @dataProvider flatArrayKeyExistsProvider
     */
    public function testFlatArrayKeyExists($array, $key, $expected)
    {
        $this->assertEquals($expected, Utils::flatArrayKeyExists($array, $key));
    }

    public function flatArrayGetProvider()
    {
        return [
            ['hello', 'hello.world', ['hello' => ['world' => 'hello']]]
        ];
    }

    /**
     * @dataProvider flatArrayGetProvider
     */
    public function testFlatArrayGet($expected, $key, $array)
    {
        $this->assertEquals($expected, Utils::flatArrayGet($array, $key));
    }
}
