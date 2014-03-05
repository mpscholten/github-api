github-api
==========
[![Build Status](https://travis-ci.org/mpscholten/github-api.png?branch=master)](https://travis-ci.org/mpscholten/github-api)

An easy to use github api client for PHP.

### Requirements ###
You need php 5.4 or higher to use this library.

### Features ###
* very easy to use and ide-friendly
* pure object oriented interface
* automatically handled pagination
* psr-2
 
----

## Get started ##
Install via composer: `composer require mpscholten/github-api`


### Auth ###
#### OAuth ####

To use oauth just pass your oauth token to `GitHub::create()` like this.
```php
<?php

use MPScholten\GitHubApi\GitHub;

$github = GitHub::create('oauth token');
```

#### No authentication ####
If you want to use the public api without any authentication you can do this by just calling `GitHub::create` without any arguments.
```php
<?php

use MPScholten\GitHubApi\GitHub;

$github = GitHub::create();
```

### User API ###
**In case you are using oauth** you can get the current logged-in user by calling
```php
$user = GitHub::create('oauth token')->getCurrentUser();
```
**Otherwise** you can get users by their github username.
```php
$user = GitHub::create()->getUser('mpscholten');
```

With the user object you can now do
```php
$user->getEmail();
$user->getName();
$user->getUrl();
$user->getAvatarUrl();
// ...

// relations
$user->getRepositories(); // returns an array of Repositories owned by the user
$user->getOrganizations();

// list the users repositories
foreach ($user->getRepositories() as $repository) {
    echo $repository->getName();
}
```


### Repository API ###
```php
$repository = GitHub::create()->getRepository('mpscholten', 'github-api');
$repository->getName();
$repository->getCommits();
$repository->getBranches();

$repository->getOwner(); // returns a user object
$repository->getOwner()->getName(); // chaining 

// list the collaborators of the repo
foreach ($repository->getCollaborators() as $collaborators) {
    echo $collaborators->getName();
}
```

### Organization API ###
```php
foreach ($user->getOrganizations() as $org) {
    $org->getName(); // e.g. GitHub
    $org->getLocation(); // e.g. San Francisco
}
```

### Search API ###
You can use the search api by calling `$github->getSearch()`
```php
// this is equals to https://github.com/search?q=language%3Aphp+&type=Repositories&ref=searchresults
foreach (GitHub::create()->getSearch()->findRepositories('language:php') as $repo) {
    $repo->getName();
    // ...
}
```

### Release API ###
```php
foreach ($repository->getReleases() as $release) {
    $release->getUrl(); // https://github.com/octocat/Hello-World/releases/v1.0.0
    $release->getUrl('zipball'); // https://api.github.com/repos/octocat/Hello-World/zipball/v1.0.0
    $release->getCreatedAt()->format('Y-m-d H:i:s');
}
```


----


### Pagination ###
Don't worry about pagination, all paginated collections are using a custom `Iterator` so we can automatically load more results if you need them. So you can focus on what you really want to do.

**Example**
This will print you all commits of the repository.
```php
foreach ($repository->getCommits() as $commit) {
    echo $commit->getMessage() . "\n";
}
```

### Caching ###
It's builtin! By default we will use in-memory caching but you might want to use file caching. Just pass your cache directory to `GitHub::create()`, like this
```php
<?php

use MPScholten\GitHubApi\GitHub;

$github = GitHub::create('oauth token', 'my-cache-dir/');
```

### Testing ###
```bash
$ phpunit
```

### Contributing ###
Feel free to send pull request!
