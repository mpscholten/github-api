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
    private $gitUrl;
    private $sshUrl;
    private $htmlUrl;

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
        $this->gitUrl = $data['git_url'];
        $this->sshUrl = $data['ssh_url'];
        $this->htmlUrl = $data['html_url'];

        // populate relations
        $this->owner = new User($this->client);
        $this->owner->populate($data['owner']);
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
        $url = TemplateUrlGenerator::generate($this->keysUrl, ['key_id' => null]);
        $keys = [];
        foreach ($this->get($url) as $data) {
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
        return new PaginationIterator(
            $client,
            $this->client->get(TemplateUrlGenerator::generate($this->commitsUrl, ['sha' => null])),
            function ($response, $client) {
                $commits = [];
                foreach ($response as $data) {
                    $commit = new Commit($client);
                    $commit->populate($data);
                    $commits[] = $commit;
                }

                return $commits;
            }
        );
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
        return $this->defaultBranch;
    }

    /**
     * @return string The description of the repository, e.g. "This your first repo!"
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string The full name of the repository, e.g. "octocat/Hello-World"
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function isFork()
    {
        return $this->isFork;
    }

    /**
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->isPrivate;
    }

    /**
     * @return string The repository name, e.g. "Hello-World"
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return User The owner of the repository
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return string The clone url if you want to clone via ssh, e.g. "git@github.com:octocat/Hello-World.git"
     */
    public function getSshUrl()
    {
        return $this->sshUrl;
    }
}
