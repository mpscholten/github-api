<?php

namespace MPScholten\GitHubApi\Api\User;

use MPScholten\GitHubApi\Api\Repository\Repository;
use MPScholten\GitHubApi\Tests\Api\User\EmailTest;

/**
 * This class is mostly the same as User, the only difference is that it also loads some
 * private data (e.g. private repositories) if you're allowed to do so.
 *
 * @link http://developer.github.com/v3/users/#update-the-authenticated-user
 */
class CurrentUser extends User
{
    protected $repositories = [];
    protected $organizations;
    protected $emails;

    /**
     * @link http://developer.github.com/v3/users/#get-the-authenticated-user
     *
     * Loads the current authenticated user
     */
    protected function load()
    {
        $this->populate($this->get('user'));
    }

    /**
     * @link http://developer.github.com/v3/repos/#list-your-repositories
     *
     * @param string $type Can be one of all, owner, public, private, member. Default: all
     * @throws \InvalidArgumentException In case the $type is not valid
     * @return Repository[]
     */
    public function getRepositories($type = 'all')
    {
        $validTypes = ['all', 'owner', 'public', 'private', 'member'];
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid type, expected one of "%s"',
                implode(', ', $validTypes)
            ));
        }

        if (!isset($this->repositories[$type])) {
            $this->repositories[$type] = $this->loadRepositories($type);
        }

        return $this->repositories[$type];
    }

    protected function loadRepositories($type)
    {
        $url = 'user/repos';
        return $this->createPaginationIterator($url, Repository::CLASS_NAME, ['type' => $type]);
    }

    /**
     * List email addresses for a user
     *
     * @link http://developer.github.com/v3/users/emails/
     * @return Email[]
     */
    public function getEmails()
    {
        if ($this->emails === null) {
            $this->emails = $this->loadEmails();
        }

        return $this->emails;
    }

    protected function loadEmails()
    {
        return $this->createPaginationIterator('user/emails', Email::CLASS_NAME);
    }

    /**
     * @param bool $expectedVerified If you expected the email to be verified, in most cases you don't want a not
     *                               verified email address.
     * @throws \RuntimeException In case there is not primary email (should never happen)
     * @return Email The primary email address of the current user
     */
    public function getPrimaryEmail($expectedVerified = true)
    {
        foreach ($this->getEmails() as $email) {
            if ($email->isPrimary()) {
                if ($expectedVerified && !$email->isVerified()) {
                    throw new \RuntimeException('The primary email is not verified');
                }

                return $email;
            }
        }

        throw new \RuntimeException('There is not primary email');
    }
}
