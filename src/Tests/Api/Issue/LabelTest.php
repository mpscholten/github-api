<?php


namespace MPScholten\GitHubApi\Tests\Api\Issue;


use MPScholten\GitHubApi\Api\Issue\Label;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class LabelTest extends AbstractTestCase
{
    public function testPopulateFixture()
    {
        $label = new Label();
        $label->populate($this->loadJsonFixture('fixture_label.json'));

        $this->assertEquals('enhancement', $label->getName());
        $this->assertEquals('84b6eb', $label->getColor());
    }
}
