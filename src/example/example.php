<?php

use MPScholten\GitHubApi\Api\Search\Search;
use MPScholten\GitHubApi\GitHub;

require __DIR__ . '/../../../../vendor/autoload.php';


foreach (GitHub::create()->getSearch()->findRepositories('language:php', Search::SORT_BY_STARS) as $i => $repository) {
    echo sprintf("%s by %s \n", $repository->getName(), $repository->getOwner()->getName());

    if ($i > 20) {
        break;
    }
}
