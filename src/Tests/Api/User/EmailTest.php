<?php


namespace MPScholten\GitHubApi\Tests\Api\User;


use MPScholten\GitHubApi\Api\User\Email;
use MPScholten\GitHubApi\Tests\AbstractTestCase;

class EmailTest extends AbstractTestCase
{
    public function testPopulateWithSampleData()
    {
        $email = new Email();
        $email->populate($this->loadJsonFixture('fixture_email.json'));

        $this->assertEquals('octocat@github.com', $email->getValue());
        $this->assertEquals('octocat@github.com', $email->getEmail());
        $this->assertEquals('octocat@github.com', (string) $email);

        $this->assertTrue($email->isVerified());
        $this->assertTrue($email->isPrimary());
    }

    /**
     * @expectedException \LogicException
     */
    public function testLazyLoadThrowsExceptionBecauseYouCannotLazyLoadEmails()
    {
        $email = new Email();
        $email->getValue();
    }
}
