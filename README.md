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

## Get started ##
Install via composer: `composer require mpscholten/github-api`

### Let me see some code! ###

```php
<?php

use MPScholten\GithubApi\Github;

$github = Github::create('oauth token');

foreach ($github->getCurrentUser()->getRepositories() as $repository) {
    echo $repository->getName() . "\n";
}
  
```

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
It's builtin! By default we will use in-memory caching but you might want to use file caching. Just pass your cache directory to `Github::create()`, like this
```php
<?php

use MPScholten\GithubApi\Github;

$github = Github::create('oauth token', 'my-cache-dir/');
```

### Testing ###
```bash
$ phpunit
```

### Constributing ###
Feel free to send pull request!
