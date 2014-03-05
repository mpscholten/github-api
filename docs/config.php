<?php

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Tests')
    ->in(__DIR__.'/../src/MPScholten/GithubApi/')
;

return new Sami($iterator, [
    'title' => 'GitHub Api',
    'theme' => 'default',
    'build_dir' => __DIR__.'/api/',
    'cache_dir' => __DIR__.'/api/cache',
    'default_opened_level' => 2
]);
