<?php

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in('app');

return new Sami($iterator, array(
    'title'                => 'Pipes API',
    'build_dir'            => __DIR__.'/public/docs/',
    'cache_dir'            => __DIR__.'/cache/'
));
