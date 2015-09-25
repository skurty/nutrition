<?php
require 'recipe/common.php';

serverList('servers.yml');

 task('deploy:bower_install', function () {
    run('cd {{release_path}} && bower install');
})->desc('Install Bower');

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:bower_install'
]);

set('repository', 'https://github.com/skurty/nutrition.git');