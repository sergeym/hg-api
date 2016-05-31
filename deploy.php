<?php
require 'recipe/symfony3.php';
use Symfony\Component\Console\Input\InputArgument;

set('dump_assets', true);

argument('branch', InputArgument::OPTIONAL, 'Branch to publish.');

serverList('app/config/servers.yml');

set('repository', 'git@gitlab.com:marin-sergey/hanggliding-api.git');

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php5-fpm reload');
});

after('deploy', 'reload:php-fpm');
after('rollback', 'reload:php-fpm');