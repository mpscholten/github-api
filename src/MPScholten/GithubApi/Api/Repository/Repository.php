<?php


namespace MPScholten\GithubApi\Api\Repository;


use MPScholten\GithubApi\Api\AbstractApi;
use MPScholten\GithubApi\Api\PaginationIterator;
use MPScholten\GithubApi\Api\User\User;
use MPScholten\GithubApi\TemplateUrlGenerator;

class Repository extends AbstractApi
{
    // relations
    protected $owner;
    protected $collaborators = null;
    protected $keys = null;
    protected $commits = null;
    protected $hooks;

    // attributes
    private $id;
    private $name;
    private $fullName;
    private $description;
    private $isPrivate;
    private $isFork;
    private $defaultBranch;

    // urls
    private $collaboratorsUrl;
    private $keysUrl;
    private $commitsUrl;

    public function populate($data)
    {
        // attributes
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->fullName = $data['full_name'];
        $this->description = $data['description'];
        $this->isPrivate = $data['private'];
        $this->isFork = $data['fork'];
        $this->defaultBranch = $data['default_branch'];

        // urls
        $this->collaboratorsUrl = $data['collaborators_url'];
        $this->keysUrl = $data['keys_url'];
        $this->commitsUrl = $data['commits_url'];

        // populate relations
        $this->owner = new User($this->client);
        $this->owner->populate($data['owner']);
    }

    /**
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
        $url = TemplateUrlGenerator::generate($this->collaboratorsUrl, ['collaborator' => null]);
        $collaborators = [];
        foreach ($this->get($url) as $data) {
            $collaborator = new User($this->client);
            $collaborator->populate($data);

            $collaborators[] = $collaborator;
        }

        return $collaborators;
    }

    /**
     * @return Key[]
     */
    public function getKeys()
    {
        if($this->keys === null) {
            $this->keys = $this->loadKeys();
        }

        return $this->keys;
    }

    protected function loadKeys()
    {
        $url = TemplateUrlGenerator::generate($this->keysUrl, ['key_id' => null]);
        $keys = [];
        foreach($this->get($url) as $data) {
            $key = new Key($this->client);
            $key->populate($data);

            $keys[] = $key;
        }

        return $keys;
    }

    public function addKey(Key $key)
    {
        $url = TemplateUrlGenerator::generate($this->keysUrl, ['key_id' => null]);
        $response = $this->post($url, ['title' => $key->getTitle(), 'key' => $key->getKey()]);

        $key->populate($response); // repopulate for getting the id
    }

    public function removeKey(Key $key)
    {
        $url = TemplateUrlGenerator::generate($this->keysUrl, ['key_id' => $key->getId()]);
        $this->delete($url);
    }

    protected function loadCommits()
    {
        $client = $this->client;
        return new PaginationIterator($client,
            $this->client->get(TemplateUrlGenerator::generate($this->commitsUrl, ['sha' => null])),
            function($response, $client) {
                $commits = [];
                foreach($response as $data) {
                    $commit = new Commit($client);
                    $commit->populate($data);
                    $commits[] = $commit;
                }

                return $commits;
            }
        );
    }

    /**
     * @return Commit[]
     */
    public function getCommits()
    {
        if($this->commits === null) {
            $this->commits = $this->loadCommits();
        }

        return $this->commits;
    }

    public function getDeployKeys()
    {
        return $this->getKeys();
    }

    public function getDefaultBranch()
    {
        return $this->defaultBranch;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsFork()
    {
        return $this->isFork;
    }

    public function getIsPrivate()
    {
        return $this->isPrivate;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOwner()
    {
        return $this->owner;
    }
}