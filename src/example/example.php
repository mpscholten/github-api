<?php

use MPScholten\GitHubApi\Api\Search\Search;
use MPScholten\GitHubApi\Github;

require __DIR__ . '/../../../../vendor/autoload.php';


foreach (Github::create()->getSearch()->findRepositories('language:php', Search::SORT_BY_STARS) as $i => $repository) {
    echo sprintf("%s by %s \n", $repository->getName(), $repository->getOwner()->getName());

    if ($i > 20) {
        break;
    }
}
