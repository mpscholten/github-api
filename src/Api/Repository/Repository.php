<?php

namespace MPScholten\GithubApi\Api\Repository;

use MPScholten\GithubApi\Api\AbstractModelApi;
use MPScholten\GithubApi\Api\User\User;
use MPScholten\GithubApi\TemplateUrlGenerator;

class Repository extends AbstractModelApi
{
    const CLASS_NAME = __CLASS__;

    protected $owner;
    protected $collaborators;
    protected $keys;
    protected $commits;
    protected $hooks;
    protected $branches;


    /**
     * Fully loads the model
     */
    protected function load()
    {
        if ($this->isAttributeLoaded('url')) {
            $url = $this->getAttribute('url');
        } elseif ($this->isAttributeLoaded('owner') && $this->isAttributeLoaded('name')) {
            $url = TemplateUrlGenerator::generate(
                '/repos/{/owner}/{/repo}',
                ['owner' => $this->getOwner()->getLogin(), 'name' => $this->getName()]
            );
        } else {
            throw new \RuntimeException('Unable to guess repository url, not enough informations given.');
        }

        $this->populate($this->get($url));
    }

    /**
     * @link http://developer.github.com/v3/repos/collaborators/#list
     * When authenticating as an organization owner of an organization-owned repository, all organization owners are
     * included in the list of collaborators. Otherwise, only users with access to the repository are returned in the
     * collaborators list.
     *
     * @return User[]
     */
    public function getCollaborators()
    {
        if ($this->collaborators === null) {
            $this->collaborators = $this->loadCollaborators();
        }

        return $this->collaborators;
    }

    protected function loadCollaborators()
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('collaborators_url'), ['collaborator' => null]);
        return $this->createPaginationIterator($url, User::CLASS_NAME);
    }

    /**
     * @link http://developer.github.com/v3/repos/keys/#list
     * @see Repository::getDeployKeys()
     *
     * @return Key[]
     */
    public function getKeys()
    {
        if ($this->keys === null) {
            $this->keys = $this->loadKeys();
        }

        return $this->keys;
    }

    protected function loadKeys()
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('keys_url'), ['key_id' => null]);
        return $this->createPaginationIterator($url, Key::CLASS_NAME);
    }

    public function addKey(Key $key)
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('keys_url'), ['key_id' => null]);
        $response = $this->post($url, ['title' => $key->getTitle(), 'key' => $key->getKey()]);

        $key->populate($response); // repopulate for getting the id
    }

    public function removeKey(Key $key)
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('keys_url'), ['key_id' => $key->getId()]);
        $this->delete($url);
    }

    protected function loadCommits()
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('commits_url'), ['sha' => null]);
        return $this->createPaginationIterator($url, Commit::CLASS_NAME);
    }

    /**
     * @link http://developer.github.com/v3/repos/commits/#list-commits-on-a-repository
     *
     * @return Commit[]
     */
    public function getCommits()
    {
        if ($this->commits === null) {
            $this->commits = $this->loadCommits();
        }

        return $this->commits;
    }

    protected function loadBranches()
    {
        $url = TemplateUrlGenerator::generate($this->getAttribute('branches_url'), ['branch' => null]);
        return $this->createPaginationIterator($url, Branch::CLASS_NAME);
    }

    /**
     * @link http://developer.github.com/v3/repos/#list-branches
     *
     * @return Branch[]
     */
    public function getBranches()
    {
        if ($this->branches === null) {
            $this->branches = $this->loadBranches();
        }

        return $this->branches;
    }

    /**
     * @link http://developer.github.com/v3/repos/keys/#list
     * @see Repository::getKeys()
     */
    public function getDeployKeys()
    {
        return $this->getKeys();
    }

    /**
     * @return string The default branch of the repository, in most cases this is "master"
     */
    public function getDefaultBranch()
    {
        return $this->getAttribute('default_branch');
    }

    /**
     * @return string The description of the repository, e.g. "This your first repo!"
     */
    public function getDescription()
    {
        return $this->getAttribute('description');
    }

    /**
     * @return string The full name of the repository, e.g. "octocat/Hello-World"
     */
    public function getFullName()
    {
        return $this->getAttribute('full_name');
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return boolean
     */
    public function isFork()
    {
        return $this->getAttribute('fork');
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->getAttribute('private');
    }

    /**
     * @return string The repository name, e.g. "Hello-World"
     */
    public function getName()
    {
        return $this->getAttribute('name');
    }

    /**
     * @return User The owner of the repository
     */
    public function getOwner()
    {
        if ($this->owner === null) {
            $this->owner = new User($this->client);
            $this->owner->populate($this->getAttribute('owner'));
        }
        return $this->owner;
    }

    /**
     * @return string The clone url if you want to clone via ssh, e.g. "git@github.com:octocat/Hello-World.git"
     */
    public function getSshUrl()
    {
        return $this->getAttribute('ssh_url');
    }

}
