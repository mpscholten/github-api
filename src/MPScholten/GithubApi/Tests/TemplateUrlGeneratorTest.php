<?php


namespace MPScholten\GithubApi\Tests;


use MPScholten\GithubApi\TemplateUrlGenerator;

class TemplateUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function urlDataProvider()
    {
        return [
            [
                'https://api.github.com/repos/mpscholten/symfony-docs/collaborators',
                'https://api.github.com/repos/mpscholten/symfony-docs/collaborators{/collaborator}',
                ['collaborator' => null]
            ],
        ];
    }

    /**
     * @dataProvider urlDataProvider
     */
    public function testUrl($expected, $url, $data)
    {
        $this->assertEquals($expected, TemplateUrlGenerator::generate($url, $data));
    }
}
