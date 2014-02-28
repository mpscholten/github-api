github-api
==========

An easy to use github api client for PHP.

### Requirements ###
This library currently supports PHP5.4 and up.

### Features ###
* very easy to use and ide-friendly
* pur object oriented interface
* automatically handled pagination
* psr-2

### Let me see some code! ###

```php
<?php

use MPScholten\GithubApi\Github;

$github = Github::create('oauth token');

foreach ($github->getCurrentUser()->getRepositories() as $repository) {
    echo $repository->getName() . "\n";
}
  
```

### Get started ###
The easiest way to get started is by adding `mpscholten/github-api` to your composer.json.

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

