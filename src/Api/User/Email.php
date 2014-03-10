<?php

namespace MPScholten\GitHubApi\Api\User;

use MPScholten\GitHubApi\Api\AbstractModelApi;

/**
 * @link http://developer.github.com/v3/users/emails/
 */
class Email extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    /**
     * Fully loads the model from GitHub.
     */
    protected function load()
    {
        throw new \LogicException('Email needs to be loaded by the user');
    }

    /**
     * @return string The user's email address
     */
    public function getValue()
    {
        return $this->getAttribute('email');
    }

    /**
     * @see getValue()
     * @return string The user's email address
     */
    public function getEmail()
    {
        return $this->getValue();
    }

    /**
     * @return boolean
     */
    public function isVerified()
    {
        return $this->getAttribute('verified');
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->getAttribute('primary');
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
